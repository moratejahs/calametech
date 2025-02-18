<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProjectsCompletedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $userId = auth()->id();
        $completedProjectData = Project::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'Done')
            ->get();
        // dd($completedProjectData);
        return view('admin.admin-projects-completed', compact(
            'completedProjectData',
        ));
    }

    public function edit(Request $request)
    {
        $projectStatus = $request->status;
        $projectId = $request->projectId;
        $project = Project::findOrFail($projectId);
        $project->update([
            'status' => $projectStatus,
        ]);

        return redirect()->route('admin.admin-projects-completed');
    }

    public function destroy(Request $request)
    {
        $projectId = $request->projectId;
        $userId = auth()->id();
        $project = Project::findOrFail($projectId);
        $project->users()->detach($userId);
        $project->delete();
        $request->session()->flash('success_message', 'Deleted Successfully!');
        return redirect()->route('admin.admin-projects-completed');
    }
}
