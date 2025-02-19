@extends('layout.super-admin-panel')
@section('links')

    {{-- This is for the choices --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" />

    {{-- This is for the sweetalert --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">

    {{-- This is for the datatable --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">


    {{-- This is for the css validation --}}
    <link rel="stylesheet" href="{{ asset('assets/css/validation/validation.css') }}">
@endsection

@section('content')

    @include('super-admin.include.user_modal.super-admin-store-user')
    @include('super-admin.include.user_modal.super-admin-show-user')
    @include('super-admin.include.user_modal.super-admin-delete-user')

    <div id="main">

        {{-- sweetalert notification --}}
        @if(session('success_message'))
         <script>
             document.addEventListener('DOMContentLoaded', function () {
                 Swal.fire({
                     toast: true,
                     position: 'top-end',
                     icon: 'success',
                     title: '{{ session('success_message') }}',
                     showConfirmButton: false,
                     timer: 3000 // Adjust the duration as needed
                 });
             });
         </script>
        @endif

        <div class="page-heading">

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h4>User Records</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#store-user">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                                </svg>
                                Add New User
                            </button>
                        </div>
                        <table class="table table-hover" id="userRecord">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($userRecord as $user)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{ $user->name }} </td>
                                        <td> {{ $user->email }} </td>
                                        <td> {{ $user->address }} </td>
                                        <td> {{ $user->contact_number }} </td>
                                        <td> {{ $user->description }} </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#showUser"

                                                    data-bs-userId          = "{{ $user->user_id }}"
                                                    data-bs-roleId          = "{{ $user->role_id }}"
                                                    data-bs-name            = "{{ $user->name }}"
                                                    data-bs-email           = "{{ $user->email }}"
                                                    data-bs-address         = "{{ $user->address }}"
                                                    data-bs-contact         = "{{ $user->contact_number }}"
                                                    data-bs-password        = "{{ $user->password }}"
                                                    data-bs-plainPassword   = "{{ Crypt::decrypt($user->plain_password) }}"

                                                    >

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                        <path
                                                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                    </svg>

                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteUser"

                                                    data-bs-remove-userId = "{{ $user->user_id }}"
                                                    data-bs-remove-roleId = "{{ $user->role_id }}"

                                                    >

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
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

    {{-- This is for the sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

    {{-- This is for the choices --}}
    <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script>

    {{-- This is for the datatable --}}
    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>

    {{-- This is for the store validation --}}
    <script src="{{ asset('assets/js/form-validation/store-user-validation.js') }}"></script>

    {{-- This is for the show validation --}}
    <script src="{{ asset('assets/js/form-validation/show-user-validation.js') }}"></script>

    <script>
        let table1 = document.querySelector('#userRecord');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function(){

            const viewUser = document.querySelectorAll('button[data-bs-target="#showUser"]');
            const removeUser = document.querySelectorAll('button[data-bs-target="#deleteUser"]');

            viewUser.forEach(function(button){
                button.addEventListener('click', function(){

                    const userId        = this.getAttribute('data-bs-userId');
                    const roleId        = this.getAttribute('data-bs-roleId');
                    const name          = this.getAttribute('data-bs-name');
                    const email         = this.getAttribute('data-bs-email');
                    const address       = this.getAttribute('data-bs-address');
                    const contact       = this.getAttribute('data-bs-contact');
                    const plainPassword = this.getAttribute('data-bs-plainPassword');

                    document.getElementById('viewUserId').value         = userId;
                    document.getElementById('viewRoleId').value         = roleId;
                    document.getElementById('viewName').value           = name;
                    document.getElementById('viewEmail').value          = email;
                    document.getElementById('viewAddress').value        = address;
                    document.getElementById('viewContact').value        = contact;
                    document.getElementById('viewPlainPassword').value  = plainPassword;

                });
            });


            removeUser.forEach(function(button){
                button.addEventListener('click', function(){

                    const userId = this.getAttribute('data-bs-remove-userId');
                    const roleId = this.getAttribute('data-bs-remove-roleId');

                    document.getElementById('removeUserId').value = userId;
                    document.getElementById('removeRoleId').value = roleId;
                });
            });
        });

    </script>

@endsection
