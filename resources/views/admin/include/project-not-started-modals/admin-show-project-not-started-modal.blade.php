<div class="modal fade text-left" id="viewProjectNotStartedModal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">Project Information</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>


            <form id="showProjectValidation" action="{{ route('edit.admin.admin-projects-not-started') }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <div class="col-12 pb-2">
                                    <span class=" fst-italic" style="font-size: 25px;">
                                        Timeline: <label id="timeLine"></label>
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <label>Project Status</label>
                                <div class="form-group">

                                    <div class="form-group">
                                        <select name="status" class="form-select" id="status">
                                            <option>Not Started</option>
                                            <option>In progress</option>
                                            <option>Done</option>

                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="row">

                            {{-- THIS IS THE PROJECT ID --}}
                            <input type="hidden" name="projectId" id="projectId">

                            <input type="hidden" name="isUnauthorized" id="isUnauthorized">

                            <div class="col-6">

                                <div class="mb-3">
                                    <label>Task Name</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="taskNameIcon">
                                                <i class="bi bi-list-task"></i>
                                            </span>
                                            <input id="taskName" name="taskName" type="text" class="form-control"
                                                placeholder="Enter task name" aria-describedby="taskNameIcon">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Owner</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="ownerNameIcon">
                                                <i class="bi bi-person-circle"></i>
                                            </span>
                                            <input id="ownerName" name="projectOwner" type="text"
                                                class="form-control" placeholder="Client name"
                                                aria-describedby="ownerNameIcon">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Due Date</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="dueDateIcon">
                                                <i class="bi bi-calendar-check-fill"></i>
                                            </span>
                                            <input id="dueDate" name="dueDate" type="date" class="form-control"
                                                aria-describedby="dueDateIcon">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-6">

                                <div class="mb-3">
                                    <label>Remark</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="remarkIcon">
                                                <i class="bi bi-pencil-square"></i>
                                            </span>
                                            <input id="remark" name="userRemark" type="text" class="form-control"
                                                placeholder="Enter Remark" aria-describedby="remarkIcon">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">

                                    <label>Budget</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="budgetIcon">
                                                ₱
                                            </span>
                                            <input id="budget" name="userBudget" type="text" class="form-control"
                                                placeholder="Enter Budget" aria-describedby="budgetIcon">
                                        </div>
                                    </div>

                                </div>

                                <div class="mb-3">

                                    <label for="Task Name">Priority</label>
                                    <div class="form-group">
                                        <select class="form-select" name="priority" id="priority">
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
                        <span class="d-none d-sm-block">Update</span>
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
