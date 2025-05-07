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
                    <div class="row">
                        {{-- Not Started Project --}}
                        <div class="col-6 col-lg-3 col-md-6 pointer">
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
                                            <div>
                                                <span class="text-white pt-2">
                                                    <img src="https://scontent.fdvo2-2.fna.fbcdn.net/v/t1.15752-9/491004676_1464360264969856_2941979217056718572_n.png?_nc_cat=100&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeHL1MOkJA7ZNHR5zLcYVkvjuD_P_b8Xmb64P8_9vxeZvhqYq6R1O6odpPCMiCuf4oiWaHmV6i8W2UM-Cj0A9-bW&_nc_ohc=_DkRVyweKvYQ7kNvwESxBeK&_nc_oc=Adkto7uW-fZJZvOVww4-oSVeyLZ4AdnED9CMCmh8ER3u7FgfigptyE07U4VWtAikz9g&_nc_zt=23&_nc_ht=scontent.fdvo2-2.fna&oh=03_Q7cD2AG7J4o_cbT4uhEFmBSUAgEfdZ2_fTr3d3lGxp5CnOy2Sg&oe=68272A8B"
                                                        alt="" width="60px">
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
                                            <div>
                                                <span class="text-white pt-2">
                                                    <img src="https://scontent.fdvo2-2.fna.fbcdn.net/v/t1.15752-9/491004676_1464360264969856_2941979217056718572_n.png?_nc_cat=100&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeHL1MOkJA7ZNHR5zLcYVkvjuD_P_b8Xmb64P8_9vxeZvhqYq6R1O6odpPCMiCuf4oiWaHmV6i8W2UM-Cj0A9-bW&_nc_ohc=_DkRVyweKvYQ7kNvwESxBeK&_nc_oc=Adkto7uW-fZJZvOVww4-oSVeyLZ4AdnED9CMCmh8ER3u7FgfigptyE07U4VWtAikz9g&_nc_zt=23&_nc_ht=scontent.fdvo2-2.fna&oh=03_Q7cD2AG7J4o_cbT4uhEFmBSUAgEfdZ2_fTr3d3lGxp5CnOy2Sg&oe=68272A8B"
                                                        alt="" width="60px">
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
                                            <div>
                                                <span class="text-white pt-2">
                                                    <img src="https://scontent.fdvo2-2.fna.fbcdn.net/v/t1.15752-9/491004676_1464360264969856_2941979217056718572_n.png?_nc_cat=100&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeHL1MOkJA7ZNHR5zLcYVkvjuD_P_b8Xmb64P8_9vxeZvhqYq6R1O6odpPCMiCuf4oiWaHmV6i8W2UM-Cj0A9-bW&_nc_ohc=_DkRVyweKvYQ7kNvwESxBeK&_nc_oc=Adkto7uW-fZJZvOVww4-oSVeyLZ4AdnED9CMCmh8ER3u7FgfigptyE07U4VWtAikz9g&_nc_zt=23&_nc_ht=scontent.fdvo2-2.fna&oh=03_Q7cD2AG7J4o_cbT4uhEFmBSUAgEfdZ2_fTr3d3lGxp5CnOy2Sg&oe=68272A8B"
                                                        alt="" width="60px">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Flood Reports</h6>
                                            <h6 class="font-extrabold mb-0">{{ $sosFood }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="map"></div>
                                </div>
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
        {{-- Bootstrap Modal for SOS Details --}}
        <div class="modal fade" id="sosModal" tabindex="-1" aria-labelledby="sosModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sosModalLabel">SOS Alert Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <center>
                            <img id="sosImage" src="" alt="SOS Image" width="100" height="100">
                        </center>
                        <form id="storeIncidenResponse" method="post" accept="POST"
                            action="{{ route('incident.store') }}" enctype="multipart/form-data">
                            @csrf
                            <p><strong></strong> <input hidden type="text" id="sosId" name="id"
                                    class="form-control">
                            </p>
                            <input type="file" name="image" class="form-control" accept=".jpeg,.jpg,.png">

                            <p><strong>Reported by:</strong> <span id="sosPerson"></span></p>
                            <p><strong>Contact No.:</strong> <span id="sosNo"></span></p>
                            <p style="display:none;"><strong>Description:</strong> <span id="sosDescription"></span></p>
                            <p style="display:none;"><strong>Location:</strong> <span id="sosLocation"></span></p>


                            <p>
                                <strong>Address:</strong>
                                <input id="sosAddress" type="text" name="address" class="form-control"
                                    value="">
                            </p>
                            <p><strong>Status:</strong>
                                <select id="sosStatus" name="status" class="form-control">
                                    <option value="pending">Pending</option>
                                    <option value="resolved">Resolved</option>
                                </select>
                            </p>

                            <select id="sosType" name="type" hidden class="form-control">
                                <option value="fire">Fire</option>
                                <option value="flood">Flood</option>
                            </select>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="storeIncidenResponse" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
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
    <script>
        const sosIconUrl = "{{ asset('assets/images/placeholder.png') }}";
        const sosSoundUrl = "{{ asset('assets/sound/sos.mp3') }}";
        var map = L.map('map').setView([9.078408, 126.199289], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© Calamitech'
        }).addTo(map);

        // Fetch SOS data from Laravel
        var sosData = @json($sos);
        console.log("Fetched SOS Data:", sosData); // Debugging: Check if the data is correct

        var sosIcon = L.icon({
            iconUrl: sosIconUrl,
            iconSize: [50, 50],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        var sosFireIcon = L.icon({
            iconUrl: "{{ asset('assets/images/fire.png') }}",
            iconSize: [50, 50],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        var sosFloodIcon = L.icon({
            iconUrl: "{{ asset('assets/images/flood.png') }}",
            iconSize: [50, 50],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        function getIconByType(type) {
            if (type === 'fire') {
                return sosFireIcon;
            } else if (type === 'flood') {
                return sosFloodIcon;
            } else {
                return sosIcon;
            }
        }

        function showSOSAlert(id, description, location, address, status, type, image_path, name, contact_number) {
            console.log("SOS Status Received:", status); // Debugging: Check what status is received
            console.log("🔹 SOS ID:", id);
            console.log("🔹 SOS Description:", description);
            console.log("🔹 SOS Location:", location);
            console.log("🔹 SOS Address:", address);
            console.log("🔹 SOS Status (Raw):", status);
            console.log("🔹 SOS Image Path:", image_path);
            console.log("🔹 Reported By:", name); // Debugging: Log the reporter's name
            console.log("🔹 Reported By:", contact_number);

            document.getElementById('sosId').value = id;
            document.getElementById('sosDescription').textContent = description || "No description provided";
            document.getElementById('sosLocation').textContent = location || "No location provided";
            document.getElementById('sosPerson').textContent = name || "Unknown"; // Set the reporter's name
            document.getElementById('sosNo').textContent = contact_number || "Unknown"; // Set the reporter's name
            document.getElementById('sosAddress').value = address || "No address available";

            let statusDropdown = document.getElementById('sosStatus');
            let typeDropdown = document.getElementById('sosType');
            typeDropdown.value = type;
            status = (status || "").trim();

            let isValidStatus = [...statusDropdown.options].some(option => option.value === status);

            if (isValidStatus) {
                statusDropdown.value = status;
            } else {
                console.warn("Invalid status value:", status);
                statusDropdown.value = statusDropdown.options[0].value;
            }

            var sosImage = document.getElementById('sosImage');
            if (image_path) {
                sosImage.src = `/storage/${image_path}`;
            } else {
                sosImage.src = "{{ asset('assets/images/placeholder.png') }}";
            }

            var sosModal = new bootstrap.Modal(document.getElementById('sosModal'));
            sosModal.show();
        }

        sosData.forEach(function(sos) {
            if (sos.lat && sos.long) {
                var latitude = parseFloat(sos.lat);
                var longitude = parseFloat(sos.long);

                var marker = L.marker([latitude, longitude], {
                    icon: getIconByType(sos.type)
                }).addTo(map);

                var geocodeUrl =
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`;

                fetch(geocodeUrl)
                    .then(response => response.json())
                    .then(data => {
                        var address = data.display_name || "Address not found";

                        // Attach click event to open modal
                        marker.on('click', function() {
                            showSOSAlert(
                                sos.id,
                                sos.description,
                                latitude + ', ' + longitude,
                                address,
                                sos.status,
                                sos.type,
                                sos.image_path,
                                sos.user.name,
                                sos.user.contact_number // Pass the reporter's name
                            );
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching address:', error);
                        marker.bindPopup(`<b>SOS Alert</b><br>${sos.description}<br>Address not available`);
                    });
            }
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
