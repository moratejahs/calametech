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
        {{-- <div class="page-heading">
            <h3>Dashboard</h3>
        </div> --}}

        <div class="page-content">
            <section class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="map"></div>
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
                        <form id="storeIncidenResponse" method="post" accept="POST" action="{{ route('incident.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <p><strong></strong> <input hidden type="text" id="sosId" name="id"
                                    class="form-control">
                            </p>
                            {{-- <input type="file" name="image" class="form-control" accept=".jpeg,.jpg,.png"> --}}

                            <p><strong>Reported by:</strong> <span id="sosPerson"></span></p>
                            <p><strong>Contact No.:</strong> <span id="sosNo"></span></p>
                            <p><strong>Description:</strong> <span id="sosDescription"></span></p>
                            <p style="display:none;"><strong>Location:</strong> <span id="sosLocation"></span></p>


                            <p>
                                <strong>Address:</strong>
                                <input id="sosAddress" type="text" name="address" class="form-control" value="">
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
        }, 10000); // Refresh every 5000 milliseconds (5 seconds)
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
        var map = L.map('map', {
            scrollWheelZoom: false, // Disable scroll wheel zoom
            dragging: false, // Disable map dragging (panning)
            touchZoom: false, // Disable touch zoom
            doubleClickZoom: false, // Disable double click zoom
            boxZoom: false, // Disable box zoom
            keyboard: false, // Disable keyboard navigation
            zoomControl: false // Remove zoom control buttons
        }).setView([9.078408, 126.199289], 13);

            // Use Esri World Imagery for satellite background (no API key required)
            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 20,
                attribution: 'Tiles © Esri — Source: Esri, Maxar, Earthstar Geographics, and the GIS User Community'
        }).addTo(map);

        // --- Map Weather Widget (Open-Meteo, no API key) ---
        const mapWeatherControl = L.control({ position: 'topright' });
        mapWeatherControl.onAdd = function() {
            const div = L.DomUtil.create('div', 'map-weather-widget p-2 rounded');
            div.style.minWidth = '220px';
            div.style.maxWidth = '360px';
            div.style.background = 'rgba(10,10,10,0.75)';
            div.style.color = '#fff';
            div.style.fontSize = '12px';
            div.style.boxShadow = '0 2px 10px rgba(0,0,0,0.4)';
            div.innerHTML = '<div id="mapWeatherTitle" style="font-weight:600;margin-bottom:6px">Map Weather</div><div id="mapWeatherBody">Loading…</div>';
            L.DomEvent.disableClickPropagation(div);
            return div;
        };
        mapWeatherControl.addTo(map);

        async function fetchMapWeather(lat, lon) {
            try {
                const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&daily=weather_code,temperature_2m_max,temperature_2m_min&forecast_days=5&timezone=auto`;
                const res = await fetch(url);
                if (!res.ok) return null;
                return await res.json();
            } catch (e) {
                console.warn('Map weather fetch failed', e);
                return null;
            }
        }

        function renderMapWeather(data) {
            const body = document.getElementById('mapWeatherBody');
            if (!body) return;
            if (!data || !data.daily) { body.innerHTML = 'Unavailable'; return; }
            const days = data.daily.time || [];
            const wcode = data.daily.weather_code || [];
            const tmax = data.daily.temperature_2m_max || [];
            const tmin = data.daily.temperature_2m_min || [];
            const emap = {0:'☀️',1:'🌤️',2:'⛅',3:'☁️',45:'🌫️',48:'🌫️',51:'🌦️',53:'🌦️',55:'🌧️',61:'🌦️',63:'🌧️',65:'🌧️',71:'🌨️',73:'❄️',75:'❄️',77:'🌨️',80:'🌧️',81:'🌧️',82:'⛈️',95:'⛈️',96:'⛈️',99:'⛈️'};
            let html = `<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px"><div style="font-size:12px;color:#cfd8dc">Center</div><div id="mapWeatherCoords" style="font-size:12px"></div></div>`;
            html += '<div style="display:flex;gap:8px;align-items:flex-start">';
            for (let i = 0; i < Math.min(5, days.length); i++) {
                const date = new Date(days[i]).toLocaleDateString(undefined, { weekday: 'short' });
                const icon = emap[wcode[i]] || '⛅';
                html += `<div style="text-align:center;min-width:48px"><div style="font-size:18px">${icon}</div><div style="font-size:11px">${date}</div><div style="font-size:11px">${Math.round(tmax[i]||0)}°/${Math.round(tmin[i]||0)}°</div></div>`;
            }
            html += '</div>';
            body.innerHTML = html;
        }

        let mapWeatherTimer = null;
        async function refreshMapWeather() {
            const c = map.getCenter();
            const coordsEl = document.getElementById('mapWeatherCoords');
            if (coordsEl) coordsEl.textContent = `${c.lat.toFixed(3)}, ${c.lng.toFixed(3)}`;
            const data = await fetchMapWeather(c.lat, c.lng);
            renderMapWeather(data);
        }

        map.on('moveend', function() {
            if (mapWeatherTimer) clearTimeout(mapWeatherTimer);
            mapWeatherTimer = setTimeout(refreshMapWeather, 400);
        });

        // Initial fetch
        refreshMapWeather();

        // --- end Map Weather Widget ---

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

        // Only render SOS markers whose status is 'pending'
        const pendingSos = sosData.filter(function(s) {
            const st = (s.status || '').toString().trim().toLowerCase();
            return st === 'pending';
        });

        // Keep an array of marker positions to compute bounds
        const pendingMarkerLatLngs = [];

        pendingSos.forEach(function(sos) {
            if (sos.lat && sos.long) {
                var latitude = parseFloat(sos.lat);
                var longitude = parseFloat(sos.long);

                var marker = L.marker([latitude, longitude], {
                    icon: getIconByType(sos.type)
                }).addTo(map);

                // collect for bounds
                pendingMarkerLatLngs.push([latitude, longitude]);

                var geocodeUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`;

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
                                sos.user ? sos.user.name : null,
                                sos.user ? sos.user.contact_number : null // Pass the reporter's name
                            );
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching address:', error);
                        marker.bindPopup(`<b>SOS Alert</b><br>${sos.description || ''}<br>Address not available`);
                    });
            }
        });

        // If we added any pending markers, adjust the map view to include them
        if (pendingMarkerLatLngs.length > 0) {
            try {
                if (pendingMarkerLatLngs.length === 1) {
                    // Single marker: zoom in reasonably
                    map.setView(pendingMarkerLatLngs[0], 15);
                } else {
                    const bounds = L.latLngBounds(pendingMarkerLatLngs);
                    map.fitBounds(bounds.pad(0.25));
                }
            } catch (err) {
                console.warn('Failed to fit bounds for pending markers', err);
            }
        }
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
