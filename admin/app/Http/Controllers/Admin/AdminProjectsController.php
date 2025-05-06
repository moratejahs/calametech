<?php

namespace App\Http\Controllers\Admin;

use App\Models\SOS;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    // this is for the ajax
    public function updateProjectStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'projectId' => 'required|exists:projects,id',
            'newStatus' => 'required|in:Not Started,In progress,Done', // Add other statuses as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $projectId = $request->input('projectId');
        $newStatus = $request->input('newStatus');

        $project = Project::findOrFail($projectId);
        $project->status = $newStatus;
        $project->save();

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function index()
    {
        $sos = SOS::query()
            ->where('status', '!=', 'pending')
            ->orderBy('id', 'desc')
            ->get();
        // dd($sos);
        return view('admin.admin-projects',[
            'sos' => $sos
        ]);
    }

    public function store(Request $request)
    {

        $userId = auth()->id();

        $adminProjects = Project::create([
            'project_name' => $request->project_name,
            'project_owner' => $request->project_owner,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'budget' => $request->budget,
            'created_by' => $userId,
            'remarks' => $request->remarks,
        ]);

        $adminProjects->users()->attach($userId);

        // $request->session()->flash('success_message', 'Saved Successfully!');

        return redirect()->route('admin.admin-projects');
    }

    public function edit(Request $request)
    {

        $projectId = $request->projectId;
        $isUnauthorized = $request->isUnauthorized;

        $projectEdit = Project::findOrFail($projectId);

        if ($isUnauthorized == 'Unauthorized') {
            $projectEdit->update([
                'status' => $request->status,
            ]);
        } else {
            $projectEdit->update([
                'project_name' => $request->taskName,
                'project_owner' => $request->projectOwner,
                'due_date' => $request->dueDate,
                'status' => $request->status,
                'priority' => $request->priority,
                'budget' => $request->userBudget,
                'remarks' => $request->userRemark,
            ]);
        }

        $request->session()->flash('success_message', 'Updated Successfully!');

        return redirect()->route('admin.admin-projects');
    }

    public function destroy(Request $request)
    {

        $projectId = $request->projectId;
        $userId = auth()->id();

        $projectDestroy = Project::findOrFail($projectId);

        $projectDestroy->users()->detach($userId);

        $request->session()->flash('success_message', 'Deleted Successfully!');

        return redirect()->route('admin.admin-projects');
    }
}
