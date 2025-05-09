<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminProjectsInProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $userId = auth()->id();
        $inProgressProjectData = Project::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'In Progress')
            // ->where('due_date', '>', Carbon::now())
            ->get();

        // dd($inProgressProjectData);
        return view('admin.admin-projects-in-progress', compact(
            'inProgressProjectData',
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

        return redirect()->route('admin.admin-projects-in-progress');
    }

    public function destroy(Request $request)
    {
        $projectId = $request->projectId;
        $userId = auth()->id();
        $project = Project::findOrFail($projectId);
        $project->users()->detach($userId);
        $project->delete();
        $request->session()->flash('success_message', 'Deleted Successfully!');

        return redirect()->route('admin.admin-projects-in-progress');
    }
}
