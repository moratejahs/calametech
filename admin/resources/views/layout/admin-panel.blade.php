<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Calamitech')</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/iconly/bold.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}"> --}}

    {{-- <link rel="shortcut icon" href="{{ asset('assets/images/logo/reygenix.png') }}" type="image/x-icon"> --}}
    {{--
    <script>
        const body = document.body;
        const theme = localStorage.getItem('theme')

        if (theme)
            document.documentElement.setAttribute('data-bs-theme', theme)
    </script> --}}

    @yield('links')
</head>

<body style="background: linear-gradient(to bottom, #08BFF1, #FFFFFF);">
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active"
                style="width: 250px; height: 100vh; background: linear-gradient(to bottom, #08BFF1, #FFFFFF);">

                <div class="sidebar-menu">
                    <ul class="menu">

                        <li class="sidebar-item ">
                            <div>
                                <div class="toggler" style="float: right;">
                                    <a href="#" class="sidebar-hide d-xl-none d-block">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </a>

                                </div>
                                <div class="text-center pt-3">
                                    <img src="{{ asset('assets/images/calametech-logo.png') }}" alt="Logo"
                                        width="70">
                                    <div class="my-3">
                                        <div class="bg-primary rounded-circle mx-auto"
                                            style="width: 70px; height: 70px;">
                                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                                                class="img-fluid rounded-circle p-1" alt="User">
                                        </div>
                                        <h6 class="text-dark mt-2 mb-0">{{ auth()->user()->name }}</h6>
                                    </div>
                                </div>


                            </div>
                        </li>
                        <br>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="logo">
                                <span style="color: white;">Pages</span>
                            </div>
                        </div>


                        <li class="sidebar-item">
                            <a href="{{ route('admin.admin-dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill" style="color: white;"></i>
                                <span style="color: white;">Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.incident-reports') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill" style="color: white;"></i>
                                <span style="color: white;">Incident Analytics</span>
                            </a>
                        </li>
                        <li class="sidebar-item ">
                            <a href="{{ route('admin.admin-projects') }}" class='sidebar-link'>
                                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-git" viewBox="0 0 16 16">
                                    <path
                                        d="M15.698 7.287 8.712.302a1.03 1.03 0 0 0-1.457 0l-1.45 1.45 1.84 1.84a1.223 1.223 0 0 1 1.55 1.56l1.773 1.774a1.224 1.224 0 0 1 1.267 2.025 1.226 1.226 0 0 1-2.002-1.334L8.58 5.963v4.353a1.226 1.226 0 1 1-1.008-.036V5.887a1.226 1.226 0 0 1-.666-1.608L5.093 2.465l-4.79 4.79a1.03 1.03 0 0 0 0 1.457l6.986 6.986a1.03 1.03 0 0 0 1.457 0l6.953-6.953a1.031 1.031 0 0 0 0-1.457" />
                                </svg> --}}
                                <i class="bi bi-exclamation-circle-fill" style="color: white;"></i>
                                <span style="color: white;">Incidents</span>
                            </a>
                        </li>

                        <li class="sidebar-item ">
                            <a href="{{ route('manage-users.index') }}" class='sidebar-link'>
                                <i class="bi bi-people-fill" style="color: white;"></i>
                                <span style="color: white;">Users</span>
                            </a>
                        </li>

                        <li class="sidebar-item ">
                            <a href="{{ route('news.index') }}" class='sidebar-link'>
                                <i class="bi bi-rss" style="color: white;"></i>
                                <span style="color: white;">News</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>

        <div class='layout-navbar'>

            <header class='mb p-0'>
                <nav class="navbar navbar-expand bg-white color-black">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="{{ asset('assets/images/profile/no-image-icon.png') }}">
                                            </div>
                                        </div>
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">{{ auth()->user()->name }}</h6>
                                            <p class="mb-0 text-sm text-gray-600">Administrator</p>
                                        </div>

                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">

                                    {{-- <li><a class="dropdown-item" href="{{ route('admin.admin-profile') }}"><i
                                                class="icon-mid bi bi-person me-2"></i> My
                                            Profile</a></li>

                                    <hr class="dropdown-divider">
                                    </li> --}}
                                    <li>
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#logout-modal" style="cursor: pointer;">
                                            <i class="icon-mid bi bi-box-arrow-left me-2 text-danger"></i>
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>


        </div>


        {{-- Modal Logout --}}
        <div class="modal fade" id="logout-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Log out?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div>
                            Are you sure you want to logout?
                        </div>

                        <div class="d-flex justify-content-end">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    Confirm
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        {{-- Modal Logout --}}
        <div class="modal fade" id="logout-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Log out?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div>
                            Are you sure you want to logout?
                        </div>

                        <div class="d-flex justify-content-end">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    Confirm
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @yield('content')

        @php
            // Query the SOS model for the 'pending' status directly in the Blade file
            $sos = \App\Models\SOS::where('status', 'pending')->first();
        @endphp

        @if ($sos)
            <audio id="sosAudio" src="{{ asset('assets/sound/sos.mp3') }}" type="audio/mpeg" autoplay loop
                muted></audio>
            <script>
                window.onload = function() {
                    var audio = document.getElementById('sosAudio');
                    audio.play();
                    audio.muted = false; // Unmute after autoplay
                }
            </script>
        @endif

    </div>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- <script src="{{ asset('assets/js/dark.js') }}"></script> --}}

    @yield('scripts')
    @stack('scripts')


</body>

</html>
