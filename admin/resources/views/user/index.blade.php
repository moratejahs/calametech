@extends('layout.admin-panel')

@section('links')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
@endsection

@section('content')
    <div id="main">

        <div class="page-heading">
            <h3>Users Monitoring</h3>
        </div>
        {{-- <div class="mb-3 d-flex gap-2">
            <a href="{{ route('admin.backups.index') }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Open backup manager">
                <i class="bi bi-database"></i> Database Backups
            </a>
        </div> --}}
        <div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-6 col-lg-4 col-md-6 pointer">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div>
                                    {{-- <span class="text-white" style="font-size: 20px;"> ₱ </span> --}}
                                    <span class="text-white pt-2">
                                        <img src="https://scontent.fdvo2-2.fna.fbcdn.net/v/t1.15752-9/488024277_1924577208279588_1802506262116112324_n.png?_nc_cat=110&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeEanJo5W7hUwrYBTE_udqj_wnJ5nASTX97CcnmcBJNf3hQ3_szylU2jKaugW15PWfmqnINW6ShfBlW6Lry4SAOp&_nc_ohc=vEO0TGbv6wkQ7kNvwFH61lE&_nc_oc=AdnLgd15mQ27EE8jk_-iQhcTyHQrNg5y_H_4flUvQ3fwo8UzG34wg0tVsIFjcKfUt7Q&_nc_zt=23&_nc_ht=scontent.fdvo2-2.fna&oh=03_Q7cD2AFJ-Fr2Y7B75VGO_LOrHIkpL0NqDkS58EFqetZmgTS53g&oe=68271CDA"
                                            alt="" width="60px">
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">Total Users</h6>
                                <h6 class="font-extrabold mb-0">{{ $totalUsers }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-md-6 pointer">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div>
                                    {{-- <span class="text-white" style="font-size: 20px;"> ₱ </span> --}}
                                    <span class="text-white pt-2">
                                        <img src="https://scontent.fdvo2-2.fna.fbcdn.net/v/t1.15752-9/488024277_1924577208279588_1802506262116112324_n.png?_nc_cat=110&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeEanJo5W7hUwrYBTE_udqj_wnJ5nASTX97CcnmcBJNf3hQ3_szylU2jKaugW15PWfmqnINW6ShfBlW6Lry4SAOp&_nc_ohc=vEO0TGbv6wkQ7kNvwFH61lE&_nc_oc=AdnLgd15mQ27EE8jk_-iQhcTyHQrNg5y_H_4flUvQ3fwo8UzG34wg0tVsIFjcKfUt7Q&_nc_zt=23&_nc_ht=scontent.fdvo2-2.fna&oh=03_Q7cD2AFJ-Fr2Y7B75VGO_LOrHIkpL0NqDkS58EFqetZmgTS53g&oe=68271CDA"
                                            alt="" width="60px">
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">Verified Users</h6>
                                <h6 class="font-extrabold mb-0">{{ $totalVerifiedUsers }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-md-6 pointer">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div>
                                    {{-- <span class="text-white" style="font-size: 20px;"> ₱ </span> --}}
                                    <span class="text-white pt-2">
                                        <img src="https://scontent.fdvo2-2.fna.fbcdn.net/v/t1.15752-9/488024277_1924577208279588_1802506262116112324_n.png?_nc_cat=110&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeEanJo5W7hUwrYBTE_udqj_wnJ5nASTX97CcnmcBJNf3hQ3_szylU2jKaugW15PWfmqnINW6ShfBlW6Lry4SAOp&_nc_ohc=vEO0TGbv6wkQ7kNvwFH61lE&_nc_oc=AdnLgd15mQ27EE8jk_-iQhcTyHQrNg5y_H_4flUvQ3fwo8UzG34wg0tVsIFjcKfUt7Q&_nc_zt=23&_nc_ht=scontent.fdvo2-2.fna&oh=03_Q7cD2AFJ-Fr2Y7B75VGO_LOrHIkpL0NqDkS58EFqetZmgTS53g&oe=68271CDA"
                                            alt="" width="60px">
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">Unverified Users</h6>
                                <h6 class="font-extrabold mb-0">{{ $totalUnverifiedUsers }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-heading">
            <section class="section">
                <div class="card">

                    <div class="card-body">
                        <table class="table table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th class="text-white" style="background-color: #0099FF;">Id Picture</th>
                                    <th class="text-white" style="background-color: #0099FF;">Id Type</th>
                                    <th class="text-white" style="background-color: #0099FF;">Avatar</th>
                                    <th class="text-white" style="background-color: #0099FF;">Name</th>
                                    <th class="text-white" style="background-color: #0099FF;">Address</th>
                                    <th class="text-white" style="background-color: #0099FF;">Email</th>
                                    <th class="text-white" style="background-color: #0099FF;">Contact Number</th>
                                    <th class="text-white" style="background-color: #0099FF;">Status</th>
                                    <th class="text-white" style="background-color: #0099FF;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            @if ($user->id_picture)
                                                <img src="{{ asset('storage/' . $user->id_picture) }}" alt="Avatar"
                                                    class="img-thumbnail" style="width: 50px; height: 50px;">
                                            @else
                                                <span>No Id Picture</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $user->id_type }}
                                        </td>


                                        <td>
                                            @if ($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                                    class="img-thumbnail" style="width: 50px; height: 50px;">
                                            @else
                                                <span>No Avatar</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $user->name }}
                                        </td>
                                        <td>
                                            {{ $user->address }}
                                        </td>
                                        <td>
                                            {{ $user->email }}
                                        </td>
                                        <td>
                                            {{ $user->contact_number }}
                                        </td>
                                        <td>
                                            @if ($user->is_verified)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-secondary">Unverified</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.userVerification') }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="id" value="{{ $user->id }}" hidden>
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-check-circle-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                </button>
                                            </form>
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
    {{-- <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script> --}}

    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script>
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>
@endsection
