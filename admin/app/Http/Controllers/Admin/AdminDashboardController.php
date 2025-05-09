<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\SOS;
use App\Models\User;
use App\Models\Project;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
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

            $sos = SOS::query()
                ->with('user')
                ->where('status', 'pending')
                ->get();

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
            'monthlySOSCounts' // Add this to pass the data
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
