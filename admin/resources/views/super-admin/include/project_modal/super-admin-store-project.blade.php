

<div class="modal fade text-left" id="store-project" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">New Project Information</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="storeProjectValidationForm" action="{{ route('store.super-admin.admin-projects') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="container-fluid">

                         <div class="row">

                            <div class="col-8">
                                <label for="Incharge">In-Charge</label>
                                <div class="form-group">

                                    <select name="created_by" class="choices form-select bg-light">
                                        @foreach ($userRecord as $users)
                                            <option value="{{ $users->id }}">{{ $users->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                             <div class="col-6">

                                <div class="mb-3">

                                    <label for="taskName">Task Name</label>
                                    <div class="form-group pb-0">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="taskNameIcon">
                                                <i class="bi bi-list-task"></i>
                                            </span>
                                            <input id="taskName" name="project_name" type="text" class="form-control" placeholder="Enter task name" aria-describedby="taskNameIcon">
                                        </div>
                                    </div>

                                </div>

                                 <div class="mb-3">
                                     <label for="Task Name">Owner</label>
                                     <div class="form-group">
                                         <div class="input-group mb-3">
                                             <span class="input-group-text" id="basic-addon1">
                                                 <i class="bi bi-person-circle"></i>
                                             </span>
                                             <input name="project_owner" type="text" class="form-control"  placeholder="Client name" aria-describedby="basic-addon1">
                                         </div>
                                     </div>
                                 </div>

                                 <div class="mb-3">
                                     <label for="Task Name">Due Date</label>
                                     <div class="form-group">
                                         <div class="input-group mb-3">
                                             <span class="input-group-text" id="basic-addon1">
                                                 <i class="bi bi-calendar-check-fill"></i>
                                             </span>
                                             <input name="due_date" type="date" class="form-control" aria-describedby="basic-addon1">
                                         </div>
                                     </div>
                                 </div>

                             </div>

                             <div class="col-6">

                                 <div class="mb-3">
                                     <label for="Task Name">Remarks</label>
                                     <div class="form-group">
                                         <div class="input-group mb-3">
                                             <span class="input-group-text" id="basic-addon1">
                                                 <i class="bi bi-pencil-square"></i>
                                             </span>
                                             <input name="remarks" type="text" class="form-control" placeholder="Enter Remark" aria-describedby="basic-addon1">
                                         </div>
                                     </div>
                                 </div>

                                 <div class="mb-3">
                                     <label for="Task Name">Budget</label>
                                     <div class="form-group">
                                         <div class="input-group mb-3">
                                             <span class="input-group-text" id="basic-addon1">
                                                 ₱
                                             </span>
                                             <input name="budget" type="number" class="form-control" placeholder="Enter Budget" aria-describedby="basic-addon1">
                                         </div>
                                     </div>
                                 </div>

                                 <div class="mb-3">
                                     <label for="Task Name">Priority</label>
                                     <div class="form-group">
                                         <select name="priority" class="form-select" id="basicSelect">
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                                <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1z"/>
                            </svg>
                            Save
                        </span>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


