<div class="modal fade text-left" id="viewProject" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">View Project Information</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="viewProjectValidationForm" action="{{ route('edit.super-admin.admin-project') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="row">

                            {{-- THIS IS THE PROJECT ID --}}
                            <input type="hidden" name="projectId" id="projectId">

                            <div class="col-12 pb-2">
                                <span class=" fst-italic">

                                    Timeline: <label id="projectTimeline"></label>

                                </span>
                            </div>


                            <div class="col-6">
                                <label for="Incharge">In-Charge</label>
                                <div class="form-group">
                                    <select name="created_by" class="form-select" id="userInchargedId">

                                        @foreach ($userRecord as $users)
                                            <option value="{{ $users->id }}">
                                                {{ $users->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>

                            <div class="col-6">
                                <label for="Status">Project Status</label>
                                <div class="form-group">

                                    <div class="form-group">
                                        <select name="status" class="form-select" id="projectStatus">
                                            <option>Not Started</option>
                                            <option>In progress</option>
                                            <option>Done</option>

                                        </select>
                                    </div>

                                </div>
                            </div>


                            <div class="col-6">

                                <div class="mb-3">
                                    <label for="Task Name">Task Name</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="taskname">
                                                <i class="bi bi-list-task"></i>
                                            </span>
                                            <input id="projectTaskname" name="vproject_name" type="text"
                                                class="form-control" placeholder="Enter task name"
                                                aria-describedby="taskname">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="Task Name">Owner</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="owner">
                                                <i class="bi bi-person-circle"></i>
                                            </span>
                                            <input id="projectOwner" name="vproject_owner" type="text"
                                                class="form-control" placeholder="Client name" aria-describedby="owner">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="Task Name">Due Date</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="duedate">
                                                <i class="bi bi-calendar-check-fill"></i>
                                            </span>
                                            <input id="projectDueDate" name="vdue_date" type="date"
                                                class="form-control" aria-describedby="duedate">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-6">

                                <div class="mb-3">
                                    <label for="Task Name">Remarks</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="remarks">
                                                <i class="bi bi-pencil-square"></i>
                                            </span>
                                            <input id="projectRemark" name="vremarks" type="text"
                                                class="form-control" placeholder="Enter Remark"
                                                aria-describedby="remarks">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="Task Name">Budget</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="budget">
                                                ₱
                                            </span>
                                            <input id="projectBudget" name="vbudget" type="number"
                                                class="form-control" placeholder="Enter Budget"
                                                aria-describedby="budget">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="Task Name">Priority</label>
                                    <div class="form-group">
                                        <select name="priority" class="form-select" id="projectPriority">
                                            <option>Low</option>
                                            <option>Medium</option>
                                            <option>High</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        <span class="d-none d-sm-block">
                            Update
                        </span>
                    </button>
                </div>


            </form>

        </div>
    </div>
</div>
