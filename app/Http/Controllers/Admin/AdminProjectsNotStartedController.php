<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProjectsNotStartedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $userId = auth()->id();
        $notStartedProjectData = Project::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'Not Started')
            ->where('due_date', '>=', Carbon::now()->startOfDay()->format('Y-m-d'))
            ->get();
        // dd($notStartedProjectData);
        return view('admin.admin-projects-not-started', compact(
            'notStartedProjectData',
        ));
    }

    public function edit(Request $request)
    {
        $projectId = $request->projectId;
        $projectStatus = $request->status;
        $budget = $request->budget;
        $project = Project::findOrFail($projectId);
        $createdBy = $project->created_by;
        if ($createdBy != auth()->id()) {
            $project->update([
                'status' => $request->status,
            ]);
        } else {
            $project->update([
                'project_name'      => $request->taskName,
                'project_owner'     => $request->projectOwner,
                'due_date'          => $request->dueDate,
                'status'            => $request->status,
                'priority'          => $request->priority,
                'budget'            => $request->userBudget,
                'remarks'           => $request->userRemark
            ]);
        }

        return redirect()->route('admin.admin-projects-not-started');
    }

    public function destroy(Request $request)
    {
        $projectId = $request->projectId;
        $userId = auth()->id();
        $project = Project::findOrFail($projectId);
        $project->users()->detach($userId);
        $project->delete();
        $request->session()->flash('success_message', 'Deleted Successfully!');
        return redirect()->route('admin.admin-projects-not-started');
    }
}
