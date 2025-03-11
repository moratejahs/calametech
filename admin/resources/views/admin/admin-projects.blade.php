@extends('layout.admin-panel')
@section('title', 'Projects')
@section('links')

    {{-- This is for the css validation --}}
    <link rel="stylesheet" href="{{ asset('assets/css/validation/validation.css') }}">

    {{-- This is for the sweetalert --}}
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
@endsection

@section('scripts')

    <script>
        var map = L.map('map').setView([9.078408, 126.199289], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© Calamitech'
        }).addTo(map);



        var barangays = [{
                name: 'Awasian',
                latitude: 9.071651,
                longitude: 126.162487
            },
            {
                name: 'Bagong Lungsod',
                latitude: 9.078408,
                longitude: 126.199289
            },
            {
                name: 'Bioto',
                latitude: 9.066121,
                longitude: 126.178940
            },
            {
                name: 'Bongtod Poblacion',
                latitude: 9.084141,
                longitude: 126.193231
            },
            {
                name: 'Buenavista',
                latitude: 9.121600,
                longitude: 126.159831
            },
            {
                name: 'Dagocdoc',
                latitude: 9.078319,
                longitude: 126.194536
            },
            {
                name: 'Mabua',
                latitude: 9.071682,
                longitude: 126.205704
            },
            {
                name: 'Mabuhay',
                latitude: 9.091768,
                longitude: 126.132823
            },
            {
                name: 'Maitum',
                latitude: 9.067148,
                longitude: 126.122245
            },
            {
                name: 'Maticdum',
                latitude: 9.036726,
                longitude: 126.151949
            }
        ];

        // Custom icons
        var fireIcon = L.icon({
            iconUrl: "{{ asset('assets/images/fire.png') }}",
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        var floodIcon = L.icon({
            iconUrl: "{{ asset('assets/images/flood.png') }}",
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        var floodIcon = L.icon({
            iconUrl: "{{ asset('assets/images/placeholder.png') }}",
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // Assign disasters randomly
        barangays.forEach(function(barangay) {
            var disasterType = Math.random() < 0.5 ? 'fire' : 'flood'; // 50% chance for each
            var icon = (disasterType === 'fire') ? fireIcon : floodIcon;

            var marker = L.marker([barangay.latitude, barangay.longitude], {
                icon: icon
            }).addTo(map);

            marker.bindPopup("<b>" + barangay.name + "</b><br>Disaster: " + (disasterType === 'fire' ? '🔥 Fire' :
                '🌊 Flood'));

            var label = L.divIcon({
                className: 'barangay-label',
                html: barangay.name,
                iconSize: [60, 20], // Width x Height of label
                iconAnchor: [30, -10] // Positioning above the marker
            });

            // Add label to the map
            L.marker([barangay.latitude, barangay.longitude], {
                icon: label
            }).addTo(map);
        });
    </script>

    {{-- This is for timeline hover --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- This is for the sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

    {{-- This is for the choices js --}}
    <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script>

    {{-- This is for the datatable js --}}
    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>

    {{-- This is for the javascript validation form --}}
    <script src="{{ asset('assets/js/form-validation/validation-project.js') }}"></script>

    <script src="{{ asset('assets/js/form-validation/show-validation-project.js') }}"></script>

    <script>
        let table1 = document.querySelector('#userProjectTable');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    {{-- PASS VALUE TO MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewRecordUser = document.querySelectorAll('button[data-bs-target="#viewProjectUser"]');
            const removeRecord = document.querySelectorAll('button[data-bs-target="#deleteProjectUser"]');

            viewRecordUser.forEach(function(button) {
                button.addEventListener('click', function() {

                    const projectId = this.getAttribute('data-bs-projectId');
                    const taskName = this.getAttribute('data-bs-taskname');
                    const ownerName = this.getAttribute('data-bs-owner');
                    const dueDate = this.getAttribute('data-bs-dueDate');
                    const remark = this.getAttribute('data-bs-remark');
                    const budget = this.getAttribute('data-bs-budget');
                    const timeLine = this.getAttribute('data-bs-timeLine');
                    const formattedDate = this.getAttribute('data-bs-formatedDate');
                    const priority = this.getAttribute('data-bs-priority');
                    const status = this.getAttribute('data-bs-status');
                    const isUnauthorized = this.getAttribute('data-bs-isUnauthorized');

                    document.getElementById('projectId').value = projectId;
                    document.getElementById('taskName').value = taskName;
                    document.getElementById('ownerName').value = ownerName;
                    document.getElementById('dueDate').value = dueDate;
                    document.getElementById('remark').value = remark;
                    document.getElementById('budget').value = budget;
                    document.getElementById('priority').value = priority;
                    document.getElementById('status').value = status;
                    document.getElementById('timeLine').textContent = timeLine;
                    document.getElementById('isUnauthorized').value = isUnauthorized;

                    var taskNameInput = document.getElementById('taskName');
                    var ownerNameInput = document.getElementById('ownerName');
                    var dueDateInput = document.getElementById('dueDate');
                    var remarkInput = document.getElementById('remark');
                    var budgetInput = document.getElementById('budget');
                    var priorityInput = document.getElementById('priority');


                    if (budget === 'Unauthorized') {

                        taskNameInput.disabled = true;
                        ownerNameInput.disabled = true;
                        dueDateInput.disabled = true;
                        remarkInput.disabled = true;
                        budgetInput.disabled = true;
                        priorityInput.disabled = true;

                    } else {

                        taskNameInput.disabled = false;
                        ownerNameInput.disabled = false;
                        dueDateInput.disabled = false;
                        remarkInput.disabled = false;
                        budgetInput.disabled = false;
                        priorityInput.disabled = false;

                    }

                });
            });

            removeRecord.forEach(function(button) {
                button.addEventListener('click', function() {
                    const projectId = this.getAttribute('data-bs-project-Id');
                    const userId = this.getAttribute('data-bs-userId');

                    document.getElementById('projectIdf').value = projectId;
                    document.getElementById('userId').value = userId;
                });
            });
        });
    </script>

    {{-- THIS IS FOR HOVER TIMELINE --}}
    <script>
        $(document).ready(function() {
            $('.hover-timeLine').hover(
                function() {
                    var timeLine = $(this).data('timeline');
                    $(this).data('original-content', $(this).html());
                    if (timeLine === 'Finished') {
                        $(this).addClass('text-success');
                    } else {
                        $(this).addClass('text-danger');
                    }
                    $(this).html(timeLine);
                },
                function() {
                    $(this).removeClass('text-success text-danger');
                    $(this).html($(this).data('original-content'));
                }
            );
        });
    </script>




    <script>
        $(document).ready(function() {
            $('.update-status').on('click', function(e) {
                e.preventDefault();

                var projectId = $(this).data('project-id');
                var newStatus = $(this).data('status');

                $.ajax({
                    url: "{{ route('admin.update.project.status') }}",
                    method: 'POST',
                    data: {
                        projectId: projectId,
                        newStatus: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#statusDropdown' + projectId).text(newStatus);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error or show error message
                        console.error(error);
                    }
                });
            });
        });
    </script>

@endsection
