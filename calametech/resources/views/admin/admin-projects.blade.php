@extends('layout.admin-panel')
@section('title', 'Projects')
@section('links')

    {{-- This is for the css validation --}}
    <link rel="stylesheet" href="{{ asset('assets/css/validation/validation.css') }}">

    {{-- This is for the sweetalert --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}">

@endsection

@section('content')
    {{-- project modal data --}}
    @include('admin.include.admin-store-project')
    @include('admin.include.admin-show-project')
    @include('admin.include.admin-delete-project')

    <div id="main">

        <div class="page-heading">

            @if (session('success_message'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: '{{ session('success_message') }}',
                            showConfirmButton: false,
                            timer: 2000 // Adjust the duration as needed
                        });
                    });
                </script>
            @endif

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5>Project Records</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#store-project">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-plus-lg" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                </svg>
                                Add New Project
                            </button>
                        </div>
                        <table class="table table-hover" id="userProjectTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Task</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Priority</th>
                                    <th>Remarks</th>
                                    <th>Budget</th>
                                    <th>In-Charged</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($projectDetails as $projects)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $projects->project_name }}
                                        </td>

                                        <td>
                                            {{ $projects->project_owner }}
                                        </td>

                                        <td class="text-center">
                                            <div class="dropdown ">
                                                <button class="btn btn-sm dropdown-toggle p-1 {{ $projects->status_css }}" type="button" id="statusDropdown{{ $projects->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="{{ $projects->status_css }}">{{ $projects->status }}</span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $projects->id }}">
                                                    <li><a class="dropdown-item update-status p-1" href="#" data-project-id="{{ $projects->id }}" data-status="Not Started"><span class="badge bg-warning">Not Started</span></a></li>
                                                    <li><a class="dropdown-item update-status p-1" href="#" data-project-id="{{ $projects->id }}" data-status="In progress"><span class="badge bg-primary">In Progress</span></a></li>
                                                    <li><a class="dropdown-item update-status p-1" href="#" data-project-id="{{ $projects->id }}" data-status="Done"><span class="badge bg-success">Done</span></a></li>
                                                </ul>
                                            </div>
                                        </td>

                                        <td class="text-center hover-timeLine" data-timeline="{{ $projects->timeline }}">
                                            {{ $projects->due_date }}
                                        </td>

                                        <td class="text-center">
                                            <span class="{{ $projects->priority_css }}">
                                                {{ $projects->priority }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $projects->remarks }}
                                        </td>

                                        <td class="text-center fst-italic">
                                            {{ $projects->budget }}
                                        </td>


                                        <td class="text-center">
                                            <span>You</span>
                                        </td>

                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">

                                                {{-- VIEW RECORDS BUTTON --}}
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#viewProjectUser"
                                                    data-bs-projectId        = "{{ $projects->id }}"
                                                    data-bs-taskname         = "{{ $projects->project_name }}"
                                                    data-bs-owner            = "{{ $projects->project_owner }}"
                                                    data-bs-dueDate          = "{{ $projects->standard_due_date }}"
                                                    data-bs-remark           = "{{ $projects->remarks }}"
                                                    data-bs-budget           = "{{ $projects->budget }}"
                                                    data-bs-isUnauthorized   = "{{ $projects->budget }}"
                                                    data-bs-priority         = "{{ $projects->priority }}"
                                                    data-bs-status           = "{{ $projects->status }}"
                                                    data-bs-formatedDate     = "{{ $projects->due_date }}"
                                                    data-bs-timeLine         = "{{ $projects->timeline }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                        <path
                                                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                    </svg>
                                                </button>


                                                {{-- REMOVED BUTTON --}}
                                                @if ($projects->budget != 'Unauthorized')
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteProjectUser"
                                                        data-bs-project-Id      = "{{ $projects->id }}"
                                                        data-bs-userId         = "{{ $projects->users_id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-trash3-fill"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </div>


    </div>
@endsection

@section('scripts')

    {{-- This is for timeline hover --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- This is for the sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

    {{-- This is for the choices js --}}
    <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script>

    {{-- This is for the datatable js --}}
    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>

    {{-- This is for the javascript validation form --}}
    <script src="{{ asset('assets/js/form-validation/validation-project.js') }}"></script>

    <script src="{{ asset('assets/js/form-validation/show-validation-project.js') }}"></script>

    <script>
        let table1 = document.querySelector('#userProjectTable');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    {{-- PASS VALUE TO MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewRecordUser = document.querySelectorAll('button[data-bs-target="#viewProjectUser"]');
            const removeRecord = document.querySelectorAll('button[data-bs-target="#deleteProjectUser"]');

            viewRecordUser.forEach(function(button) {
                button.addEventListener('click', function() {

                    const projectId = this.getAttribute('data-bs-projectId');
                    const taskName = this.getAttribute('data-bs-taskname');
                    const ownerName = this.getAttribute('data-bs-owner');
                    const dueDate = this.getAttribute('data-bs-dueDate');
                    const remark = this.getAttribute('data-bs-remark');
                    const budget = this.getAttribute('data-bs-budget');
                    const timeLine = this.getAttribute('data-bs-timeLine');
                    const formattedDate = this.getAttribute('data-bs-formatedDate');
                    const priority = this.getAttribute('data-bs-priority');
                    const status = this.getAttribute('data-bs-status');
                    const isUnauthorized = this.getAttribute('data-bs-isUnauthorized');

                    document.getElementById('projectId').value = projectId;
                    document.getElementById('taskName').value = taskName;
                    document.getElementById('ownerName').value = ownerName;
                    document.getElementById('dueDate').value = dueDate;
                    document.getElementById('remark').value = remark;
                    document.getElementById('budget').value = budget;
                    document.getElementById('priority').value = priority;
                    document.getElementById('status').value = status;
                    document.getElementById('timeLine').textContent = timeLine;
                    document.getElementById('isUnauthorized').value = isUnauthorized;

                    var taskNameInput = document.getElementById('taskName');
                    var ownerNameInput = document.getElementById('ownerName');
                    var dueDateInput = document.getElementById('dueDate');
                    var remarkInput = document.getElementById('remark');
                    var budgetInput = document.getElementById('budget');
                    var priorityInput = document.getElementById('priority');


                    if (budget === 'Unauthorized') {

                        taskNameInput.disabled = true;
                        ownerNameInput.disabled = true;
                        dueDateInput.disabled = true;
                        remarkInput.disabled = true;
                        budgetInput.disabled = true;
                        priorityInput.disabled = true;

                    } else {

                        taskNameInput.disabled = false;
                        ownerNameInput.disabled = false;
                        dueDateInput.disabled = false;
                        remarkInput.disabled = false;
                        budgetInput.disabled = false;
                        priorityInput.disabled = false;

                    }

                });
            });

            removeRecord.forEach(function(button) {
                button.addEventListener('click', function() {
                    const projectId = this.getAttribute('data-bs-project-Id');
                    const userId = this.getAttribute('data-bs-userId');

                    document.getElementById('projectIdf').value = projectId;
                    document.getElementById('userId').value = userId;
                });
            });
        });
    </script>

    {{-- THIS IS FOR HOVER TIMELINE --}}
    <script>
        $(document).ready(function() {
            $('.hover-timeLine').hover(
                function() {
                    var timeLine = $(this).data('timeline');
                    $(this).data('original-content', $(this).html());
                    if (timeLine === 'Finished') {
                        $(this).addClass('text-success');
                    } else {
                        $(this).addClass('text-danger');
                    }
                    $(this).html(timeLine);
                },
                function() {
                    $(this).removeClass('text-success text-danger');
                    $(this).html($(this).data('original-content'));
                }
            );
        });
    </script>




<script>
    $(document).ready(function() {
        $('.update-status').on('click', function(e) {
            e.preventDefault();

            var projectId = $(this).data('project-id');
            var newStatus = $(this).data('status');

            $.ajax({
                url: "{{ route('admin.update.project.status') }}",
                method: 'POST',
                data: {
                    projectId: projectId,
                    newStatus: newStatus,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#statusDropdown' + projectId).text(newStatus);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error or show error message
                    console.error(error);
                }
            });
        });
    });
</script>

@endsection
