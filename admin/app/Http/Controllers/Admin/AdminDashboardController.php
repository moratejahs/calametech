<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Project;
use Illuminate\Http\Request;
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

        return view('admin.admin-dashboard', compact(
            'projectStatusData',
            'revenueData',
            'weekRangeData',
            'recentlyCompletedProject',
            'thisMonthName'
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


    function thisMonthWeeks()
    {
        $weeks = [];
        $now = Carbon::now();
        $lastDayOfThisMonth = $now->endOfMonth()->day;
        for ($i = 1; $i <= $lastDayOfThisMonth; $i += 7) {
            $startDay = $i;
            $endDay = min($i + 6, $lastDayOfThisMonth);
            $weeks[] = $startDay . ' - ' . $endDay;
        }
        return $weeks;
    }
}
