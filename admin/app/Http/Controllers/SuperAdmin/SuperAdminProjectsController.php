<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SuperAdminProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super-admin');
    }

    // This is my ajax
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
        $projectDetails = DB::table('project_user AS project_users')
            ->join('users', 'users.id', '=', 'project_users.user_id')
            ->join('projects', 'projects.id', '=', 'project_users.project_id')
            ->select(
                'projects.id',
                'projects.created_by',
                'projects.project_name',
                'projects.project_owner',
                'projects.due_date as standard_due_date',
                DB::raw(
                    '
                CASE
                    WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "Stuck"
                    ELSE projects.status
                END AS status'
                ),
                DB::raw('CASE
                        WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "badge bg-danger"
                        WHEN projects.status = "Not Started" THEN "badge bg-warning"
                        WHEN projects.status = "In progress" THEN "badge bg-primary"
                        WHEN projects.status = "Done" THEN "badge bg-success"
                        WHEN projects.status = "Stuck" THEN "badge bg-danger"
                        ELSE NULL
                    END AS status_css'),
                DB::raw('DATE_FORMAT(projects.due_date, "%b %d") AS due_date'),
                'projects.priority',
                DB::raw('CASE
                        WHEN projects.priority = "Low" THEN "badge bg-secondary"
                        WHEN projects.priority = "Medium" THEN "badge bg-info"
                        WHEN projects.priority = "High" THEN "badge bg-primary"
                    END AS priority_css'),
                'projects.remarks',
                'projects.budget',
                DB::raw('CASE
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
            END AS timeline'),
                'users.name AS in_charge',
                'users.id AS users_id',
                'projects.id AS project_id',
                'project_users.id AS project_user_id'
            )
            ->orderByRaw('CASE WHEN projects.priority = "High" THEN 0 ELSE 1 END')
            ->orderBy('timeline', 'ASC')
            ->get();

        return view('super-admin.super-admin-projects', compact('projectDetails'));
    }

    public function store(Request $request)
    {
        $userId = auth()->id();
        $assignedId = $request->created_by;

        $adminProjects = Project::create([
            'project_name' => $request->project_name,
            'project_owner' => $request->project_owner,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'budget' => $request->budget,
            'created_by' => $userId,
            'remarks' => $request->remarks,
        ]);

        $adminProjects->users()->attach($assignedId);

        $request->session()->flash('success_message', 'Save Successfully!');

        return redirect()->back();
    }

    public function edit(Request $request)
    {

        $projectId = $request->projectId;
        $inChargedId = $request->created_by;
        $project = Project::findOrFail($projectId);

        $project->update([
            'project_name' => $request->vproject_name,
            'project_owner' => $request->vproject_owner,
            'due_date' => $request->vdue_date,
            'status' => $request->status,
            'priority' => $request->priority,
            'budget' => $request->vbudget,
            'remarks' => $request->vremarks,
        ]);

        $project->users()->sync($inChargedId);

        $request->session()->flash('success_message', 'Updated Successfully!');

        return redirect()->back();
    }

    public function destroy(Request $request)
    {

        $projectId = $request->projectId;
        $userId = $request->userId;
        $project = Project::findOrFail($projectId);

        $project->delete();
        $project->users()->detach($userId);

        $request->session()->flash('success_message', 'Deleted Successfully!');

        return redirect()->back();
    }

    public function notStartedProjects()
    {
        $projectDetails = DB::table('project_user AS project_users')
            ->join('users', 'users.id', '=', 'project_users.user_id')
            ->join('projects', 'projects.id', '=', 'project_users.project_id')
            ->select(
                'projects.id',
                'projects.created_by',
                'projects.project_name',
                'projects.project_owner',
                'projects.due_date as standard_due_date',
                DB::raw(
                    '
                    CASE
                        WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "Stuck"
                        ELSE projects.status
                    END AS status'
                ),
                DB::raw('CASE
                            WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "badge bg-danger"
                            WHEN projects.status = "Not Started" THEN "badge bg-warning"
                            WHEN projects.status = "In progress" THEN "badge bg-primary"
                            WHEN projects.status = "Done" THEN "badge bg-success"
                            WHEN projects.status = "Stuck" THEN "badge bg-danger"
                            ELSE NULL
                        END AS status_css'),
                DB::raw('DATE_FORMAT(projects.due_date, "%b %d") AS due_date'),
                'projects.priority',
                DB::raw('CASE
                            WHEN projects.priority = "Low" THEN "badge bg-secondary"
                            WHEN projects.priority = "Medium" THEN "badge bg-info"
                            WHEN projects.priority = "High" THEN "badge bg-primary"
                        END AS priority_css'),
                'projects.remarks',
                'projects.budget',
                DB::raw('CASE
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
                END AS timeline'),
                'users.name AS in_charge',
                'users.id AS users_id',
                'projects.id AS project_id',
                'project_users.id AS project_user_id'
            )
            ->orderByRaw('CASE WHEN projects.priority = "High" THEN 0 ELSE 1 END')
            ->orderBy('timeline', 'ASC')
            ->get();

        return view('super-admin.super-admin-projects-not-started', compact('projectDetails'));
    }

    public function completedProjects()
    {
        $projectDetails = DB::table('project_user AS project_users')
            ->join('users', 'users.id', '=', 'project_users.user_id')
            ->join('projects', 'projects.id', '=', 'project_users.project_id')
            ->select(
                'projects.id',
                'projects.created_by',
                'projects.project_name',
                'projects.project_owner',
                'projects.due_date as standard_due_date',
                DB::raw(
                    '
                CASE
                    WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "Stuck"
                    ELSE projects.status
                END AS status'
                ),
                DB::raw('CASE
                        WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "badge bg-danger"
                        WHEN projects.status = "Not Started" THEN "badge bg-warning"
                        WHEN projects.status = "In progress" THEN "badge bg-primary"
                        WHEN projects.status = "Done" THEN "badge bg-success"
                        WHEN projects.status = "Stuck" THEN "badge bg-danger"
                        ELSE NULL
                    END AS status_css'),
                DB::raw('DATE_FORMAT(projects.due_date, "%b %d") AS due_date'),
                'projects.priority',
                DB::raw('CASE
                        WHEN projects.priority = "Low" THEN "badge bg-secondary"
                        WHEN projects.priority = "Medium" THEN "badge bg-info"
                        WHEN projects.priority = "High" THEN "badge bg-primary"
                    END AS priority_css'),
                'projects.remarks',
                'projects.budget',
                DB::raw('CASE
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
            END AS timeline'),
                'users.name AS in_charge',
                'users.id AS users_id',
                'projects.id AS project_id',
                'project_users.id AS project_user_id'
            )
            ->orderByRaw('CASE WHEN projects.priority = "High" THEN 0 ELSE 1 END')
            ->orderBy('timeline', 'ASC')
            ->get();

        return view('super-admin.super-admin-projects-completed', compact('projectDetails'));
    }

    public function inProgressProjects()
    {
        $projectDetails = DB::table('project_user AS project_users')
            ->join('users', 'users.id', '=', 'project_users.user_id')
            ->join('projects', 'projects.id', '=', 'project_users.project_id')
            ->select(
                'projects.id',
                'projects.created_by',
                'projects.project_name',
                'projects.project_owner',
                'projects.due_date as standard_due_date',
                DB::raw(
                    '
                CASE
                    WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "Stuck"
                    ELSE projects.status
                END AS status'
                ),
                DB::raw('CASE
                        WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "badge bg-danger"
                        WHEN projects.status = "Not Started" THEN "badge bg-warning"
                        WHEN projects.status = "In progress" THEN "badge bg-primary"
                        WHEN projects.status = "Done" THEN "badge bg-success"
                        WHEN projects.status = "Stuck" THEN "badge bg-danger"
                        ELSE NULL
                    END AS status_css'),
                DB::raw('DATE_FORMAT(projects.due_date, "%b %d") AS due_date'),
                'projects.priority',
                DB::raw('CASE
                        WHEN projects.priority = "Low" THEN "badge bg-secondary"
                        WHEN projects.priority = "Medium" THEN "badge bg-info"
                        WHEN projects.priority = "High" THEN "badge bg-primary"
                    END AS priority_css'),
                'projects.remarks',
                'projects.budget',
                DB::raw('CASE
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
            END AS timeline'),
                'users.name AS in_charge',
                'users.id AS users_id',
                'projects.id AS project_id',
                'project_users.id AS project_user_id'
            )
            ->orderByRaw('CASE WHEN projects.priority = "High" THEN 0 ELSE 1 END')
            ->orderBy('timeline', 'ASC')
            ->get();

        return view('super-admin.super-admin-projects-in-progress', compact('projectDetails'));
    }

    public function behindScheduleProjects()
    {
        $projectDetails = DB::table('project_user AS project_users')
            ->join('users', 'users.id', '=', 'project_users.user_id')
            ->join('projects', 'projects.id', '=', 'project_users.project_id')
            ->select(
                'projects.id',
                'projects.created_by',
                'projects.project_name',
                'projects.project_owner',
                'projects.due_date as standard_due_date',
                DB::raw(
                    '
                CASE
                    WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "Stuck"
                    ELSE projects.status
                END AS status'
                ),
                DB::raw('CASE
                        WHEN DATEDIFF(projects.due_date, CURDATE()) < 0 AND projects.status = "Not Started" THEN "badge bg-danger"
                        WHEN projects.status = "Not Started" THEN "badge bg-warning"
                        WHEN projects.status = "In progress" THEN "badge bg-primary"
                        WHEN projects.status = "Done" THEN "badge bg-success"
                        WHEN projects.status = "Stuck" THEN "badge bg-danger"
                        ELSE NULL
                    END AS status_css'),
                DB::raw('DATE_FORMAT(projects.due_date, "%b %d") AS due_date'),
                'projects.priority',
                DB::raw('CASE
                        WHEN projects.priority = "Low" THEN "badge bg-secondary"
                        WHEN projects.priority = "Medium" THEN "badge bg-info"
                        WHEN projects.priority = "High" THEN "badge bg-primary"
                    END AS priority_css'),
                'projects.remarks',
                'projects.budget',
                DB::raw('CASE
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
            END AS timeline'),
                'users.name AS in_charge',
                'users.id AS users_id',
                'projects.id AS project_id',
                'project_users.id AS project_user_id'
            )
            ->orderByRaw('CASE WHEN projects.priority = "High" THEN 0 ELSE 1 END')
            ->orderBy('timeline', 'ASC')
            ->get();

        return view('super-admin.super-admin-projects-behind-schedule', compact('projectDetails'));
    }
}
