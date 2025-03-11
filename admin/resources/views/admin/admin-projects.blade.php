@extends('layout.admin-panel')

@section('title', 'Projects')

@section('links')

    {{-- CSS Styles --}}
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
            <h3>Incidents</h3>
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
                        <img id="sosImage" src="" alt="SOS Image" width="200" height="200">
                    </center>
                    <form id="storeIncidenResponse" method="post" accept="POST" action="{{ route('incident.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <p><strong></strong> <input hidden type="text" id="sosId" name="id"
                                class="form-control">
                        </p>
                        <input type="file" name="image" class="form-control" accept=".jpeg,.jpg,.png">
                        <p style="display:none;"><strong>Description:</strong> <span id="sosDescription"></span></p>
                        <p style="display:none;"><strong>Location:</strong> <span id="sosLocation"></span></p>
                        <p><strong>Address:</strong> <span id="sosAddress"></span></p>
                        <p><strong>Status:</strong>
                            <select id="sosStatus" name="status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="resolved">Resolved</option>
                                <option value="dismissed">Dismissed</option>
                            </select>
                        </p>
                        <p><strong>Incident Type:</strong>
                            <select id="sosType" name="type" class="form-control">
                                <option value="fire">Fire</option>
                                <option value="flood">Flood</option>
                            </select>
                        </p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="storeIncidenResponse" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        var map = L.map('map').setView([9.078408, 126.199289], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© Calamitech'
        }).addTo(map);

        // Fetch SOS data from Laravel
        var sosData = @json($sos);
        console.log("Fetched SOS Data:", sosData); // Debugging: Check if the data is correct

        var sosIcon = L.icon({
            iconUrl: "{{ asset('assets/images/placeholder.png') }}",
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        var sosFireIcon = L.icon({
            iconUrl: "{{ asset('assets/images/fire.png') }}",
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        var sosFloodIcon = L.icon({
            iconUrl: "{{ asset('assets/images/flood.png') }}",
            iconSize: [32, 32],
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
                            showSOSAlert(sos.id, sos.description, latitude + ', ' + longitude, address,
                                sos.status, sos.type, sos.image_path);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching address:', error);
                        marker.bindPopup(`<b>SOS Alert</b><br>${sos.description}<br>Address not available`);
                    });
            }
        });

        function showSOSAlert(id, description, location, address, status, type, image_path) {
            console.log("SOS Status Received:", status); // Debugging: Check what status is received
            console.log("🔹 SOS ID:", id);
            console.log("🔹 SOS Description:", description);
            console.log("🔹 SOS Location:", location);
            console.log("🔹 SOS Address:", address);
            console.log("🔹 SOS Status (Raw):", status);
            console.log("🔹 SOS Image Path:", image_path);
            document.getElementById('sosId').value = id;
            document.getElementById('sosDescription').textContent = description;
            document.getElementById('sosLocation').textContent = location;
            document.getElementById('sosAddress').textContent = address;

            let statusDropdown = document.getElementById('sosStatus');
            let typeDropdown = document.getElementById('sosType');
            typeDropdown.value = type;
            // Trim status to remove extra spaces and ensure it's a string
            status = (status || "").trim();

            // Check if the value exists in the dropdown options
            let isValidStatus = [...statusDropdown.options].some(option => option.value === status);

            if (isValidStatus) {
                statusDropdown.value = status;
            } else {
                console.warn("Invalid status value:", status); // Debugging: Show warning if invalid
                statusDropdown.value = statusDropdown.options[0].value; // Fallback to first option
            }

            var sosImage = document.getElementById('sosImage');
            if (image_path) {
                sosImage.src = `/storage/${image_path}`; // Debugging: Log the image path
            } else {
                sosImage.src = "{{ asset('assets/images/placeholder.png') }}";
            }

            var sosModal = new bootstrap.Modal(document.getElementById('sosModal'));
            sosModal.show();
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

@endsection
