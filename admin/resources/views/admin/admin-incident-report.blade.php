@extends('layout.admin-panel')
@section('title', 'Dashboard')
@section('links')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/validation/validation.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        #map {
            height: 600px;
        }

        .barangay-label {
            background: rgba(255, 255, 255, 0.8);
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }

        /* Weather strip (top of map) */
        .weather-strip {
            display: flex;
            gap: 16px;
            align-items: stretch;
            margin-bottom: 16px;
        }

        .weather-card-today {
            display: grid;
            grid-template-columns: 1fr auto;
            grid-template-rows: auto auto 1fr auto;
            gap: 6px 12px;
            background: #1f2937;
            color: #e5e7eb;
            border-radius: 16px;
            padding: 16px;
            min-width: 280px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        }

        .weather-card-today .meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            grid-column: 1 / -1;
            font-weight: 600;
            color: #93c5fd;
        }

        .weather-card-today .temp {
            font-size: 48px;
            font-weight: 800;
            line-height: 1;
        }

        .weather-card-today .icon {
            width: 80px;
            height: 80px;
            align-self: center;
            justify-self: end;
        }

        .weather-card-today .facts {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 4px 12px;
            font-size: 12px;
            color: #cbd5e1;
        }

        .mini-forecast {
            display: flex;
            gap: 10px;
        }

        .mini-card {
            background: #111827;
            color: #e5e7eb;
            border-radius: 14px;
            padding: 12px;
            width: 84px;
            text-align: center;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        .mini-card .day { font-weight: 700; font-size: 12px; color: #9ca3af; }
        .mini-card .deg { font-weight: 800; font-size: 16px; }
        .mini-card .mini-icon { width: 28px; height: 28px; margin: 6px auto; display: block; }
    </style>

@endsection
@section('content')
    <div id="main">
        <div class="page-heading">
            <h3>Dashboard</h3>
        </div>

        <div class="page-content">
            <section class="row">
                <div class="col-lg-12">
                    <div class="weather-strip">
                        <!-- Today card -->
                        <div class="weather-card-today" id="weatherTodayCard">
                            <div class="meta">
                                <span id="weatherTodayName">Today</span>
                                <span id="weatherNowTime">--:--</span>
                            </div>
                            <div class="temp" id="weatherTemp">26°</div>
                            <svg class="icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="32" cy="32" r="12" fill="#FBBF24"/>
                                <g stroke="#F59E0B" stroke-width="4" stroke-linecap="round">
                                    <path d="M32 6v8"/>
                                    <path d="M32 50v8"/>
                                    <path d="M6 32h8"/>
                                    <path d="M50 32h8"/>
                                    <path d="M12.9 12.9l5.7 5.7"/>
                                    <path d="M45.4 45.4l5.7 5.7"/>
                                    <path d="M12.9 51.1l5.7-5.7"/>
                                    <path d="M45.4 18.6l5.7-5.7"/>
                                </g>
                            </svg>
                            <div class="facts">
                                <div>Real feel: <strong id="weatherFeels">28</strong></div>
                                <div>Humidity: <strong id="weatherHumidity">29%</strong></div>
                                <div>Pressure: <strong id="weatherPressure">1012mb</strong></div>
                                <div>Wind: <strong id="weatherWind">2–4 km/h</strong></div>
                            </div>
                        </div>

                        <!-- Optional upcoming mini-cards (design only) -->
                        <div class="mini-forecast" aria-hidden="true">
                            <div class="mini-card">
                                <div class="day">Tue</div>
                                <svg class="mini-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 40h28" stroke="#60A5FA" stroke-width="6" stroke-linecap="round"/>
                                    <circle cx="24" cy="28" r="8" fill="#9CA3AF"/>
                                    <circle cx="38" cy="30" r="10" fill="#6B7280"/>
                                </svg>
                                <div class="deg">22°</div>
                            </div>
                            <div class="mini-card">
                                <div class="day">Wed</div>
                                <svg class="mini-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="32" cy="28" r="10" fill="#FBBF24"/>
                                </svg>
                                <div class="deg">25°</div>
                            </div>
                            <div class="mini-card">
                                <div class="day">Thu</div>
                                <svg class="mini-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 40h28" stroke="#60A5FA" stroke-width="6" stroke-linecap="round"/>
                                    <circle cx="24" cy="28" r="8" fill="#9CA3AF"/>
                                    <circle cx="38" cy="30" r="10" fill="#6B7280"/>
                                </svg>
                                <div class="deg">19°</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Monthly SOS Reports</h5>
                                    <canvas id="monthlySOSChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- <audio id="sosAudio" src="{{ asset('assets/sound/sos.mp3') }}" type="audio/mpeg" autoplay muted></audio> --}}
    </div>
@endsection
@push('scripts')
    <script>
        setInterval(function() {
            location.reload();
        }, 5000); // Refresh every 5000 milliseconds (5 seconds)
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fill current time on the Today card
            const nowTimeEl = document.getElementById('weatherNowTime');
            if (nowTimeEl) {
                const updateClock = () => {
                    const now = new Date();
                    const options = { hour: '2-digit', minute: '2-digit' };
                    nowTimeEl.textContent = now.toLocaleTimeString([], options);
                };
                updateClock();
                setInterval(updateClock, 60000);
            }

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation/validation-project.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation/show-validation-project.js') }}"></script>

    <script>
        let table1 = document.querySelector('#userProjectTable');
        if (table1) {
            let dataTable = new simpleDatatables.DataTable(table1);
        }
    </script>

    <script src="{{ asset('assets/js/pages/cards-dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/pages/barchart-dashboard.js') }}"></script>
@endpush
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('monthlySOSChart').getContext('2d');

            // Get monthly SOS data from Laravel
            const monthlySOSCounts = @json($monthlySOSCounts);

            // Define all months for consistent ordering
            const monthlyLabels = ["January", "February", "March", "April", "May", "June", "July", "August",
                "September", "October", "November", "December"
            ];

            // Initialize data arrays
            let fireReports = Array(12).fill(0);
            let floodReports = Array(12).fill(0);

            // Populate data arrays based on available database data
            Object.keys(monthlySOSCounts).forEach(month => {
                const monthIndex = parseInt(month) - 1; // Convert month from 1-based to 0-based index
                fireReports[monthIndex] = monthlySOSCounts[month].fire_count;
                floodReports[monthIndex] = monthlySOSCounts[month].flood_count;
            });

            // Create Chart.js bar chart
            new Chart(ctx, {
                type: 'bar', // Change to 'bar' for a bar chart
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                            label: 'Fire Reports',
                            data: fireReports,
                            backgroundColor: 'rgba(255, 0, 0, 0.6)', // Bar color for fire reports
                            borderColor: '#eb4d4b',
                            borderWidth: 1
                        },
                        {
                            label: 'Flood Reports',
                            data: floodReports,
                            backgroundColor: 'rgba(0, 0, 255, 0.6)', // Bar color for flood reports
                            borderColor: '#3498db',
                            borderWidth: 1
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
                        x: {
                            stacked: false // Set to true if you want stacked bars
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
