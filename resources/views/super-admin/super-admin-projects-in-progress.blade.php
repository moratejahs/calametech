@extends('layout.super-admin-panel')
@section('title', 'Not Started Projects')
@section('links')

    {{-- This is for the css validation --}}
    <link rel="stylesheet" href="{{ asset('assets/css/validation/validation.css') }}">

    {{-- This is for the sweetalert --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">

@endsection

@section('content')
    {{-- project modal data --}}
    @include('super-admin.include.project-in-progress-modals.super-admin-show-project-in-progress-modal')
    @include('super-admin.include.project-in-progress-modals.super-admin-delete-project-in-progress-modal')
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
                        <h5>In Progress Project Records</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" id="userProjectTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Task</th>
                                    <th>Owner</th>
                                    <th>Due Date</th>
                                    <th>Priority</th>
                                    <th>Remarks</th>
                                    <th>Budget</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($inProgressProjectData as $project)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $project->project_name }}
                                        </td>

                                        <td>
                                            {{ $project->project_owner }}
                                        </td>
                                        @php
                                            $currentDate = \Carbon\Carbon::now();
                                            $dueDate = \Carbon\Carbon::parse($project->due_date);
                                            if ($dueDate->diffInDays($currentDate) == 0) {
                                                $timeLine = 'Today';
                                            } elseif ($project->due_date < $currentDate) {
                                                $timeLine = $dueDate->diffInDays($currentDate) . ' days behind';
                                            } else {
                                                $timeLine = $dueDate->diffInDays($currentDate) . ' days left';
                                            }
                                        @endphp
                                        <td class="hover-timeLine" data-timeline="{{ $timeLine }}"
                                            data-project-status="{{ $project->status }}">
                                            {{ $project->due_date }}
                                        </td>
                                        <td>
                                            @if ($project->priority == 'High')
                                                <span class="badge bg-primary">
                                                    {{ $project->priority }}
                                                </span>
                                            @elseif ($project->priority == 'Medium')
                                                <span class="badge bg-info">
                                                    {{ $project->priority }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    {{ $project->priority }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $project->remarks }}
                                        </td>
                                        <td class="fst-italic">
                                            @if ($project->created_by == auth()->user()->id)
                                                ₱{{ $project->budget }}
                                            @else
                                                Unauthorized
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                {{-- View record button --}}
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#viewProjectInProgressModal"
                                                    data-bs-projectId        = "{{ $project->id }}"
                                                    data-bs-taskname         = "{{ $project->project_name }}"
                                                    data-bs-owner            = "{{ $project->project_owner }}"
                                                    data-bs-dueDate          = "{{ $project->due_date }}"
                                                    data-bs-remark           = "{{ $project->remarks }}"
                                                    data-bs-budget           =
                                                    "@if ($project->created_by == auth()->user()->id) {{ $project->budget }}
                                                    @else Unauthorized @endif"
                                                    data-bs-isUnauthorized   = "{{ $project->budget }}"
                                                    data-bs-priority         = "{{ $project->priority }}"
                                                    data-bs-status           = "{{ $project->status }}"
                                                    data-bs-timeLine         = "{{ $timeLine }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                        <path
                                                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                    </svg>
                                                </button>
                                                {{-- Delete record button --}}
                                                @if ($project->created_by == auth()->user()->id)
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target     ="#deleteProjectInProgressModal"
                                                        data-bs-project-Id = "{{ $project->id }}"
                                                        data-bs-userId     = "{{ $project->users_id }}">
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
            const viewRecordUser = document.querySelectorAll(
                'button[data-bs-target="#viewProjectInProgressModal"]');
            const removeRecord = document.querySelectorAll(
                'button[data-bs-target="#deleteProjectInProgressModal"]');

            viewRecordUser.forEach(function(button) {
                button.addEventListener('click', function() {
                    const projectId = this.getAttribute('data-bs-projectId');
                    const taskName = this.getAttribute('data-bs-taskname');
                    const ownerName = this.getAttribute('data-bs-owner');
                    const dueDate = this.getAttribute('data-bs-dueDate');
                    const remark = this.getAttribute('data-bs-remark');
                    const budget = this.getAttribute('data-bs-budget').trim();
                    const priority = this.getAttribute('data-bs-priority');
                    const status = this.getAttribute('data-bs-status');
                    const timeLine = this.getAttribute('data-bs-timeLine');

                    document.getElementById('projectId').value = projectId;
                    document.getElementById('taskName').value = taskName;
                    document.getElementById('ownerName').value = ownerName;
                    document.getElementById('dueDate').value = dueDate;
                    document.getElementById('remark').value = remark;
                    document.getElementById('budget').value = budget;
                    document.getElementById('priority').value = priority;
                    document.getElementById('status').value = status;
                    document.getElementById('timeLine').textContent = timeLine;

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
                    let timeLine = $(this).data('timeline');
                    $(this).data('original-content', $(this).html());
                    $(this).addClass('text-danger');
                    console.log(timeLine);
                    $(this).html(timeLine);
                },
                function() {
                    $(this).removeClass('text-danger');
                    $(this).html($(this).data('original-content'));
                }
            );
        });
    </script>

@endsection
