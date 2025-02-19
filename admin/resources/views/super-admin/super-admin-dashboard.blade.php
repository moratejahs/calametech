@extends('layout.super-admin-panel')
@section('title', 'Dashboard')
@push('links')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/dashboard.css') }}">
@endpush
@section('content')
    <div id="main">

        <div class="page-heading">
            <h3>Dashboard</h3>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="col-lg-12">
                    <div class="row">
                        {{-- Not Started Project --}}
                        <div class="col-6 col-lg-3 col-md-6 pointer" id="notStartedProject">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon green">
                                                {{-- <span class="text-white" style="font-size: 20px;"> ₱ </span> --}}
                                                <span class="text-white pt-2">
                                                    <i class="bi bi-pin-angle"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Not Started Project</h6>
                                            <h6 class="font-extrabold mb-0">{{ $projectStatusData['notStartedCount'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Completed Project --}}
                        <div class="col-6 col-lg-3 col-md-6 pointer" id="completedProject">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon blue">
                                                <span class="text-white pt-3">
                                                    <i class="bi bi-check fs-1"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Completed Project</h6>
                                            <h6 class="font-extrabold mb-0">{{ $projectStatusData['completedCount'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- In Progress Project --}}
                        <div class="col-6 col-lg-3 col-md-6 pointer" id="inProgressProject">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon purple">
                                                <span class="text-white pt-2">
                                                    <i class="bi bi-clock-history"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">In Progress Project</h6>
                                            <h6 class="font-extrabold mb-0">{{ $projectStatusData['inProgressCount'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Stuck Project --}}
                        <div class="col-6 col-lg-3 col-md-6 pointer" id="behindScheduleProject">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon red">
                                                <span class="text-white pt-2">
                                                    <i class="bi bi-x-octagon"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Stuck Project</h6>
                                            <h6 class="font-extrabold mb-0">{{ $projectStatusData['stuckCount'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Monthly Project Revenue --}}
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4>
                                        {{ $thisMonthName }} Project Revenue
                                        {{-- <span class="text-muted font-semibold">
                                            | Monthly
                                        </span> --}}
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div id="monthly-project-revenue"></div>
                                </div>
                            </div>
                        </div>
                        {{-- Recently Completed Projects --}}
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Recently Completed Projects</h4>
                                    <div class="list-group">
                                        @forelse ($recentlyCompletedProject as $project)
                                            <div class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <p class="mb-1 fw-bold fs-6">
                                                        {{ $project->project_name }}
                                                    </p>
                                                    <small>
                                                        @if ($project->updated_at->isToday())
                                                            Today at
                                                            {{ $project->updated_at->setTimezone('Asia/Manila')->format('H:i') }}
                                                        @elseif ($project->updated_at->isYesterday())
                                                            Yesterday at
                                                            {{ $project->updated_at->setTimezone('Asia/Manila')->format('H:i') }}
                                                        @else
                                                            {{ $project->updated_at->setTimezone('Asia/Manila')->format('d') }}
                                                            {{ $project->updated_at->setTimezone('Asia/Manila')->format('F') }}
                                                            at
                                                            {{ $project->updated_at->setTimezone('Asia/Manila')->format('H:i') }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="mb-1 fw-semibold fs-6">
                                                No completed project yet.
                                            </p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer>

        </footer>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/pages/cards-dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/pages/barchart-dashboard.js') }}"></script>
    <script>
        let notStartedProjectUrl = "{{ route('super-admin.super-admin-projects-not-started') }}";
        let completedProjectUrl = "{{ route('super-admin.super-admin-projects-completed') }}";
        let inProgressProjectUrl = "{{ route('super-admin.super-admin-projects-in-progress') }}";
        let behindScheduleProjectUrl = "{{ route('super-admin.super-admin-projects-behind-schedule') }}";
        let revenue = @json($revenueData);
        let weekRange = @json($weekRangeData);
    </script>
@endpush
