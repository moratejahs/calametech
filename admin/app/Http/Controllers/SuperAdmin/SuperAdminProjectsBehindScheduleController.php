<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuperAdminProjectsBehindScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super-admin');
    }

    public function index()
    {
        $userId = auth()->id();
        $behindScheduleProjectData = Project::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('due_date', '<', Carbon::now()->startOfDay()->format('Y-m-d'))
            ->where('status', '!=', 'Done')
            ->count();

        // dd($behindScheduleProjectData);
        return view('super-admin.super-admin-projects-behind-schedule', compact(
            'behindScheduleProjectData',
        ));
    }

    public function edit(Request $request)
    {
        $projectId = $request->projectId;
        $project = Project::findOrFail($projectId);
        $createdBy = $project->created_by;
        if ($createdBy != auth()->id()) {
            $project->update([
                'status' => $request->status,
            ]);
        } else {
            $project->update([
                'project_name' => $request->taskName,
                'project_owner' => $request->projectOwner,
                'due_date' => $request->dueDate,
                'status' => $request->status,
                'priority' => $request->priority,
                'budget' => $request->userBudget,
                'remarks' => $request->userRemark,
            ]);
        }

        return redirect()->route('super-admin.super-admin-projects-behind-schedule');
    }

    public function destroy(Request $request)
    {
        $projectId = $request->projectId;
        $userId = auth()->id();
        $project = Project::findOrFail($projectId);
        $project->users()->detach($userId);
        $project->delete();
        $request->session()->flash('success_message', 'Deleted Successfully!');

        return redirect()->route('super-admin.super-admin-projects-behind-schedule');
    }
}
