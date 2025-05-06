@extends('layout.admin-panel')

@section('links')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
@endsection

@section('content')
    <div id="main">

        <div class="page-heading">
            <h3>Incident histories</h3>
        </div>

        <div class="page-heading">
            <section class="section">
                <div class="card">

                    <div class="card-body">
                        <table class="table table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th class="text-white" style="background-color: #0099FF;">Img</th>
                                    <th class="text-white" style="background-color: #0099FF;">Address</th>
                                    <th class="text-white" style="background-color: #0099FF;">Type</th>
                                    <th class="text-white" style="background-color: #0099FF;">Date Incident</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sos as $so)
                                    <tr>
                                        <td>
                                            <img src="{{ $so->image_path ? asset('storage/' . $so->image_path) : asset('assets/images/picture.png') }}"
                                                alt="Incident Image"
                                                style="max-width: 90px; max-height: 90px; width: 90px; height: 90px;">


                                        </td>
                                        <td>
                                            {{ $so->address }}
                                        </td>
                                        <td>
                                            <span class="badge text-white"
                                                style="background-color: {{ $so->type === 'flood' ? '#0099FF' : ($so->type === 'fire' ? '#dc3545' : '#6c757d') }}">
                                                {{ ucfirst($so->type) }}
                                            </span>

                                        </td>
                                        <td>
                                            {{ $so->created_at->format('F d, Y') }}
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

        // Add print functionality
        document.addEventListener('DOMContentLoaded', function() {
            let printButton = document.createElement('button');
            printButton.textContent = 'Print Report';
            printButton.classList.add('btn', 'btn-primary', 'mb-3');
            table1.parentElement.insertBefore(printButton, table1);

            printButton.addEventListener('click', function() {
                let printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Print Table</title>');
                printWindow.document.write(
                    '<link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">'
                );
                printWindow.document.write('</head><body>');
                printWindow.document.write('<table>' + table1.outerHTML + '</table>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            });
        });
    </script>
@endsection
