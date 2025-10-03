<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\SOS;
use App\Models\User;
use App\Models\Project;
use App\Models\Barangay;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $selectedBarangayId = $request->get('barangay_id');
        $purokQuery = $request->get('purok');

        $projectStatusData = $this->projectStatusData();
        $recentlyCompletedProject = $this->recentlyCompletedProject();
        $weeklyProjectRevenueData = $this->weeklyProjectRevenueData();
        $weekRangeData = $this->thisMonthWeeks();
        $revenueData = [];
        foreach ($weeklyProjectRevenueData as $weekRange => $revenue) {
            $revenueData[] = $revenue;
        }

        $now = Carbon::now();
        $thisMonthName = $now->format('F');

        $users  = User::count();
        $sosFire = SOS::where('type', 'fire')->count();
        $sosFood = SOS::where('type', 'flood')->count();
        $total = SOS::count();

        $chartData = SOS::selectRaw("
            DATE_FORMAT(created_at, '%m') as month,
            SUM(CASE WHEN type = 'fire' THEN 1 ELSE 0 END) as fire_count,
            SUM(CASE WHEN type = 'flood' THEN 1 ELSE 0 END) as flood_count
            ")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            $sosQuery = SOS::query()
                ->with('user')
                ->where('status', 'pending');

            // Apply barangay filter by matching address text if available
            if ($selectedBarangayId) {
                $barangay = Barangay::find($selectedBarangayId);
                if ($barangay) {
                    $sosQuery->where(function ($q) use ($barangay) {
                        $q->where('address', 'like', '%'.$barangay->barangay_name.'%')
                          ->orWhere('address', 'like', '%'.$barangay->barangay_address.'%');
                    });
                }
            }

            // Apply simple purok text filter against address
            if (!empty($purokQuery)) {
                $sosQuery->where('address', 'like', '%'.$purokQuery.'%');
            }

            $sos = $sosQuery->get();

            // Build weather map per SOS via Open-Meteo (no key required)
            $weatherBySosId = [];
            foreach ($sos as $item) {
                if (!empty($item->lat) && !empty($item->long)) {
                    try {
                        $params = 'current=temperature_2m,relative_humidity_2m,precipitation,weather_code,wind_speed_10m';
                        $resp = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                            'latitude' => $item->lat,
                            'longitude' => $item->long,
                            'current' => 'temperature_2m,relative_humidity_2m,precipitation,weather_code,wind_speed_10m',
                        ]);
                        if ($resp->ok()) {
                            $data = $resp->json();
                            if (isset($data['current'])) {
                                $c = $data['current'];
                                $code = $c['weather_code'] ?? null;
                                $map = [
                                    0 => 'Clear', 1 => 'Mainly clear', 2 => 'Partly cloudy', 3 => 'Overcast',
                                    45 => 'Fog', 48 => 'Rime fog', 51 => 'Light drizzle', 53 => 'Drizzle', 55 => 'Dense drizzle',
                                    61 => 'Light rain', 63 => 'Rain', 65 => 'Heavy rain', 71 => 'Light snow', 73 => 'Snow', 75 => 'Heavy snow',
                                    77 => 'Snow grains', 80 => 'Rain showers', 81 => 'Rain showers', 82 => 'Violent rain showers',
                                    95 => 'Thunderstorm', 96 => 'Thunderstorm w/ hail', 99 => 'Thunderstorm w/ heavy hail',
                                ];
                                $label = $map[$code] ?? ('Code '.$code);
                                $summary = $label.
                                    ', '.($c['temperature_2m'] ?? 'n/a')."°C, humidity ".($c['relative_humidity_2m'] ?? 'n/a').'%'.
                                    ", precip ".($c['precipitation'] ?? 'n/a')."mm, wind ".($c['wind_speed_10m'] ?? 'n/a')." m/s";
                                $weatherBySosId[$item->id] = $summary;
                            }
                        }
                    } catch (\Throwable $e) {
                        // Ignore weather failures per item
                    }
                }
            }

            $currentYear = Carbon::now()->year;

            $monthlySOSCounts = SOS::selectRaw("
                MONTH(created_at) as month,
                SUM(CASE WHEN type = 'fire' THEN 1 ELSE 0 END) as fire_count,
                SUM(CASE WHEN type = 'flood' THEN 1 ELSE 0 END) as flood_count
            ")
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            $monthlyData = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthlyData[$month] = [
                    'fire_count' => $monthlySOSCounts->get($month)->fire_count ?? 0,
                    'flood_count' => $monthlySOSCounts->get($month)->flood_count ?? 0,
                ];
            }
            // For filters/dropdowns & weather cards (need coords)
            $barangays = Barangay::orderBy('barangay_name')->get(['id','barangay_name','lat','long']);

            // Build compact forecast per barangay (current + next 3 days)
            $barangayWeather = [];
            foreach ($barangays as $b) {
                if (empty($b->lat) || empty($b->long)) { continue; }
                try {
                    $resp = Http::timeout(6)->get('https://api.open-meteo.com/v1/forecast', [
                        'latitude' => $b->lat,
                        'longitude' => $b->long,
                        'current' => 'temperature_2m,apparent_temperature,relative_humidity_2m,wind_speed_10m,weather_code,pressure_msl',
                        'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum,wind_speed_10m_max,sunrise,sunset',
                        'forecast_days' => 4, // today + next 3
                        'timezone' => 'auto',
                    ]);
                    if ($resp->ok()) {
                        $j = $resp->json();
                        $barangayWeather[$b->id] = [
                            'current' => $j['current'] ?? [],
                            'daily' => [
                                'time' => $j['daily']['time'] ?? [],
                                'weather_code' => $j['daily']['weather_code'] ?? [],
                                'tmax' => $j['daily']['temperature_2m_max'] ?? [],
                                'tmin' => $j['daily']['temperature_2m_min'] ?? [],
                                'sunrise' => $j['daily']['sunrise'] ?? [],
                                'sunset' => $j['daily']['sunset'] ?? [],
                            ],
                        ];
                    }
                } catch (\Throwable $e) {
                    // ignore per barangay failures
                }
            }
            // Dynamic data processing disclosure (column names)
            $processedTables = [
                's_o_s' => Schema::getColumnListing('s_o_s'),
                'incidents' => Schema::hasTable('incidents') ? Schema::getColumnListing('incidents') : [],
            ];
            // dd($sos);
        return view('admin.admin-dashboard', compact(
            'projectStatusData',
            'revenueData',
            'weekRangeData',
            'recentlyCompletedProject',
            'thisMonthName',
            'users',
            'sosFire',
            'sosFood',
            'total',
            'chartData',
            'sos',
            'monthlySOSCounts', // Add this to pass the data
            'barangays',
            'selectedBarangayId',
            'purokQuery',
            'weatherBySosId',
            'processedTables',
            'barangayWeather'
        ));
    }

    public function projectStatusData()
    {
        $userId = auth()->id();
        $projects = Project::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        $notStartedCount = $projects->where('status', 'Not Started')->where('due_date', '>=', Carbon::now()->startOfDay()->format('Y-m-d'))->count();
        $completedCount = $projects->where('status', 'Done')->count();
        $inProgressCount = $projects->where('status', 'In progress')->count();
        $stuckCount = $projects->where('due_date', '<', Carbon::now()->startOfDay()->format('Y-m-d'))
            ->where('status', '!=', 'Done')
            ->count();
        $projectStatusData = [
            'notStartedCount' => $notStartedCount,
            'completedCount' => $completedCount,
            'inProgressCount' => $inProgressCount,
            'stuckCount' => $stuckCount,
        ];

        return $projectStatusData;
    }

    public function weeklyProjectRevenueData()
    {

        // !: fix the revenue is counted even its not the month of the project
        $userId = auth()->id();

        // $projects = Project::whereHas('users', function ($query) use ($userId) {
        //     $query->where('user_id', $userId);
        // })->select('budget', 'status', 'updated_at')->get();

        $projects = Project::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where(function ($query) use ($userId) {
            $query->where('created_by', $userId);
        })->whereMonth('updated_at', date('m'))
            ->select('budget', 'status', 'updated_at')->get();

        // dd($projects);

        // Group projects by week
        $projectsByWeek = $projects->groupBy(function ($project) {
            return Carbon::parse($project->updated_at)->startOfWeek();
        });

        // Calculate revenue for each week
        $weeklyRevenues = [];
        foreach ($projectsByWeek as $weekProjects) {
            $revenue = $weekProjects->where('status', 'Done')->sum('budget');
            $weeklyRevenues[] = $revenue;
        }

        if (count($weeklyRevenues) < count($this->thisMonthWeeks())) {
            $count = count($this->thisMonthWeeks()) - count($weeklyRevenues);
            for ($i = 0; $i < $count; $i++) {
                $weeklyRevenues[] = 0;
            }
        }

        return $weeklyRevenues;
    }

    public function recentlyCompletedProject()
    {
        $userId = auth()->id();

        $projects = Project::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->select('project_name', 'status', 'updated_at')->get();

        $recentlyCompletedProject = $projects->where('status', 'Done')
            ->sortByDesc('updated_at')
            ->take(10);

        return $recentlyCompletedProject;
    }

    public function thisMonthWeeks()
    {
        $weeks = [];
        $now = Carbon::now();
        $lastDayOfThisMonth = $now->endOfMonth()->day;
        for ($i = 1; $i <= $lastDayOfThisMonth; $i += 7) {
            $startDay = $i;
            $endDay = min($i + 6, $lastDayOfThisMonth);
            $weeks[] = $startDay.' - '.$endDay;
        }

        return $weeks;
    }
}
