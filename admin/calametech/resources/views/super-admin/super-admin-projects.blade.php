@extends('layout.super-admin-panel')
@section('title', 'Projects')
@section('links')

    {{-- This is for the css validation --}}
    <link rel="stylesheet" href="{{ asset('assets/css/validation/validation.css') }}">

    {{-- This is for the choices --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" />

    {{-- This is for the sweetalert --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <style></style>

@endsection

@section('content')
    {{-- project modal data --}}
    @include('super-admin.include.project_modal.super-admin-store-project')
    @include('super-admin.include.project_modal.super-admin-view-project')
    @include('super-admin.include.project_modal.super-admin-destroy-project')


    <div id="main">

        {{-- sweetalert notification --}}
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

        <div class="page-heading">

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
                        <table class="table table-hover" id="projectRecords">
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
                                    <th>In-Charge</th>
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

                                        <td class="text-danger hover-timeLine" data-timeline="{{ $projects->timeline }}">
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

                                        <td class="fst-italic text-center">
                                            {{ $projects->budget }}
                                        </td>

                                        <td>
                                            {{ $projects->in_charge }}
                                        </td>

                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">

                                                {{-- SHOW PROJECT --}}
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#viewProject"
                                                    data-bs-projectId      = "{{ $projects->id }}"
                                                    data-bs-incharged-id   = "{{ $projects->users_id }}"
                                                    data-bs-taskname       = "{{ $projects->project_name }}"
                                                    data-bs-owner          = "{{ $projects->project_owner }}"
                                                    data-bs-status         = "{{ $projects->status }}"
                                                    data-bs-due-date       = "{{ $projects->standard_due_date }}"
                                                    data-bs-remark         = "{{ $projects->remarks }}"
                                                    data-bs-budget         = "{{ $projects->budget }}"
                                                    data-bs-priority       = "{{ $projects->priority }}"
                                                    data-bs-timeline       = "{{ $projects->timeline }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                        <path
                                                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                    </svg>

                                                </button>


                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#destroyProject" data-bs-id = "{{ $projects->id }}"
                                                    data-bs-incharged-userId   = "{{ $projects->users_id }}">

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-trash3-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                                    </svg>

                                                </button>


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

    {{-- hover timeline --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- This is for the sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

    {{-- This is for the choices --}}
    <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script>

    {{-- This is for the datatable --}}
    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>

    {{-- This is for the javascript validation form --}}
    <script src="{{ asset('assets/js/form-validation/super-admin-project-validation.js') }}"></script>

    <script>
        let projectRecords = document.querySelector('#projectRecords');
        let dataTable = new simpleDatatables.DataTable(projectRecords);
    </script>

    {{-- TO PASS THE VALUE FROM VIEW MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewRecords = document.querySelectorAll('button[data-bs-target="#viewProject"]');
            const removeRecords = document.querySelectorAll('button[data-bs-target="#destroyProject"]');

            viewRecords.forEach(function(button) {
                button.addEventListener('click', function() {
                    const projectId = this.getAttribute('data-bs-projectId');
                    const inChargedId = this.getAttribute('data-bs-incharged-id');
                    const taskName = this.getAttribute('data-bs-taskname');
                    const ownerName = this.getAttribute('data-bs-owner');
                    const status = this.getAttribute('data-bs-status');
                    const dueDate = this.getAttribute('data-bs-due-date');
                    const remark = this.getAttribute('data-bs-remark');
                    const budget = this.getAttribute('data-bs-budget');
                    const priority = this.getAttribute('data-bs-priority');
                    const timeline = this.getAttribute('data-bs-timeline');

                    document.getElementById('projectId').value = projectId;
                    document.getElementById('userInchargedId').value = inChargedId;
                    document.getElementById('projectTaskname').value = taskName;
                    document.getElementById('projectOwner').value = ownerName;
                    document.getElementById('projectStatus').value = status;
                    document.getElementById('projectDueDate').value = dueDate;
                    document.getElementById('projectRemark').value = remark;
                    document.getElementById('projectBudget').value = budget;
                    document.getElementById('projectPriority').value = priority;
                    document.getElementById('projectTimeline').textContent = timeline;
                });
            });

            removeRecords.forEach(function(button) {
                button.addEventListener('click', function() {
                    const projectId = this.getAttribute('data-bs-id');
                    const userId = this.getAttribute('data-bs-incharged-userId');

                    document.getElementById('projectIdf').value = projectId;
                    document.getElementById('userIdf').value = userId;
                });
            });
        });
    </script>

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
                    url: "{{ route('update.project.status') }}",
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
