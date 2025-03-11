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
                                            <h6 class="font-extrabold mb-0">{{ $users }}</h6>
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
                                            <h6 class="text-muted font-semibold">Total Incidents</h6>
                                            <h6 class="font-extrabold mb-0">{{ $total }}</h6>
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
                                            <h6 class="font-extrabold mb-0">{{ $sosFire }}</h6>
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
                                            <h6 class="font-extrabold mb-0">{{ $sosFood }}</h6>
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

            // Get chart data from Laravel
            const chartData = @json($chartData);

            // Define all months for consistent ordering
            const monthlyLabels = ["January", "February", "March", "April", "May", "June", "July", "August",
                "September", "October", "November", "December"
            ];

            // Initialize data arrays
            let fireReports = Array(12).fill(0);
            let floodReports = Array(12).fill(0);

            // Populate data arrays based on available database data
            chartData.forEach(entry => {
                let monthIndex = parseInt(entry.month) - 1; // Convert month from "01" to index (0-based)
                fireReports[monthIndex] = entry.fire_count;
                floodReports[monthIndex] = entry.flood_count;
            });

            // Create Chart.js line chart
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                            label: 'Fire Reports',
                            data: fireReports,
                            borderColor: '#eb4d4b',
                            backgroundColor: 'rgba(255, 0, 0, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Flood Reports',
                            data: floodReports,
                            borderColor: '#3498db',
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
