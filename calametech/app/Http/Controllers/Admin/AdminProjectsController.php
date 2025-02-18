<?php

namespace App\Http\Controllers\Admin;

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

    //this is for the ajax
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
        $userId = auth()->user()->id;

        $projectDetails = DB::table(DB::raw('(
            SELECT
                projects.id,
                projects.project_name,
                projects.project_owner,
                CASE WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "Stuck" ELSE projects.status END AS status,
                projects.due_date,
                projects.due_date AS standard_due_date,
                projects.priority,
                CASE
                    WHEN roles.description LIKE \'%Head Administrator%\' THEN \'Unauthorized\'
                    ELSE projects.budget
                END AS budget,
                projects.remarks,
                projects.created_by,
                CASE
                    WHEN projects.`status` = \'Done\' THEN \'Finished\'
                    ELSE CONCAT(
                        CASE
                            WHEN DATEDIFF(projects.due_date, CURDATE()) = 1 THEN CONCAT(DATEDIFF(projects.due_date, CURDATE()), \' Day\')
                            ELSE DATEDIFF(projects.due_date, CURDATE())
                        END,
                        CASE
                            WHEN DATEDIFF(projects.due_date, CURDATE()) = 1 AND projects.`status` <> \'Done\' THEN \'\'
                            WHEN DATEDIFF(projects.due_date, CURDATE()) <> 1 THEN \' Days\'
                            ELSE \' Day\'
                        END
                    )
                END AS timeline,
                users.name AS in_charge,
                users.id AS users_id,
                project_users.id AS project_user_id,
                GROUP_CONCAT(roles.description SEPARATOR \', \') AS created_by_roles
            FROM
                project_user project_users
            LEFT JOIN
                users ON users.id = project_users.user_id
            LEFT JOIN
                projects ON projects.id = project_users.project_id
            LEFT JOIN
                role_user ON role_user.user_id = projects.created_by
            LEFT JOIN
                roles ON roles.id = role_user.role_id
            WHERE
                project_users.user_id = :userId
            GROUP BY
                projects.id,
                projects.project_name,
                projects.project_owner,
                projects.status,
                projects.due_date,
                standard_due_date,
                projects.priority,
                budget,
                projects.remarks,
                projects.created_by,
                timeline,
                in_charge,
                users_id,
                project_user_id,
                roles.description
        ) AS project_details'))->setBindings(['userId' => $userId]);

        $projectDetails->select([
            'project_details.id',
            'project_details.project_name',
            'project_details.project_owner',
            'project_details.status',
            DB::raw("CASE

                WHEN project_details.`status` = 'Not Started' THEN 'badge bg-warning'
                WHEN project_details.`status` = 'In progress' THEN 'badge bg-primary'
                WHEN project_details.`status` = 'Done' THEN 'badge bg-success'
                WHEN project_details.`status` = 'Stuck' THEN 'badge bg-danger'
                ELSE NULL
            END AS status_css"),
            DB::raw("DATE_FORMAT(project_details.due_date, '%b %d') AS due_date"),
            'project_details.standard_due_date',
            'project_details.priority',
            DB::raw("CASE
                WHEN project_details.priority = 'Low' THEN 'badge bg-secondary'
                WHEN project_details.priority = 'Medium' THEN 'badge bg-info'
                WHEN project_details.priority = 'High' THEN 'badge bg-primary'
            END AS priority_css"),
            'project_details.timeline',
            'project_details.budget',
            'project_details.remarks',
            'project_details.created_by',
            'project_details.in_charge',
            'project_details.users_id',
            'project_details.project_user_id'
        ]);

        $projectDetails->orderByRaw("CASE
                WHEN project_details.priority = 'High' THEN 0
                ELSE 1
            END ASC, timeline ASC");

        $projectDetails = $projectDetails->get();

        return view('admin.admin-projects', compact('projectDetails'));
    }



    public function store(Request $request)
    {

        $userId = auth()->id();

        $adminProjects = Project::create([
            'project_name'      => $request->project_name,
            'project_owner'     => $request->project_owner,
            'due_date'          => $request->due_date,
            'priority'          => $request->priority,
            'budget'            => $request->budget,
            'created_by'        => $userId,
            'remarks'           => $request->remarks,
        ]);

        $adminProjects->users()->attach($userId);

        $request->session()->flash('success_message', 'Saved Successfully!');

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
                'project_name'      => $request->taskName,
                'project_owner'     => $request->projectOwner,
                'due_date'          => $request->dueDate,
                'status'            => $request->status,
                'priority'          => $request->priority,
                'budget'            => $request->userBudget,
                'remarks'           => $request->userRemark
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
