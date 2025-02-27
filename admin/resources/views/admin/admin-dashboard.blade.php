@extends('layout.admin-panel')
@section('title', 'Dashboard')
@section('links')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
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
                        <div class="col-6 col-lg-3 col-md-6 pointer">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon green">
                                                {{-- <span class="text-white" style="font-size: 20px;"> ₱ </span> --}}
                                                <span class="text-white pt-2">
                                                    <i class="bi bi-person"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Total Users</h6>
                                            <h6 class="font-extrabold mb-0">1</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Completed Project --}}
                        <div class="col-6 col-lg-3 col-md-6 pointer">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon bg-warning">
                                                <span class="text-white pt-3">
                                                    <i class="bi bi-flag"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Total Reports</h6>
                                            <h6 class="font-extrabold mb-0">2</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- In Progress Project --}}
                        <div class="col-6 col-lg-3 col-md-6 pointer">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon red">
                                                <span class="text-white pt-2">
                                                    <i class="bi bi-flag"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Fire Reports</h6>
                                            <h6 class="font-extrabold mb-0">3</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Stuck Project --}}
                        <div class="col-6 col-lg-3 col-md-6 pointer">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon blue">
                                                <span class="text-white pt-2">
                                                    <i class="bi bi-flag"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Flood Reportst</h6>
                                            <h6 class="font-extrabold mb-0">4</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="row">
                        <div class="col-lg-12">
                            <canvas id="reportsChart"></canvas>
                        </div>
                    </section>

                </div>
            </section>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('reportsChart').getContext('2d');

            // Static data for demonstration
            const monthlyLabels = ["January", "February", "March", "April", "May", "June", "July", "August",
                "September", "October", "November", "December"
            ];
            const fireReports = [5, 8, 12, 7, 6, 9, 14, 10, 15, 18, 12, 7]; // Example fire reports data
            const waterReports = [3, 5, 9, 4, 8, 7, 10, 6, 12, 14, 9, 5]; // Example water reports data

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                            label: 'Fire Reports',
                            data: fireReports,
                            borderColor: 'red',
                            backgroundColor: 'rgba(255, 0, 0, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Water Reports',
                            data: waterReports,
                            borderColor: 'blue',
                            backgroundColor: 'rgba(0, 0, 255, 0.2)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/pages/cards-dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/pages/barchart-dashboard.js') }}"></script>
@endpush
