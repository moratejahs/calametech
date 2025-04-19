@extends('layout.admin-panel')

@section('links')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
@endsection

@section('content')
    <div id="main">

        <div class="page-heading">
            <h3>News</h3>
        </div>

        <div class="page-heading">
            <section class="section">
                <div class="card">

                    <div class="card-body">
                        <table class="table table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th class="text-white" style="background-color: #0099FF;">Img</th>
                                    <th class="text-white" style="background-color: #0099FF;">Title</th>
                                    <th class="text-white" style="background-color: #0099FF;">Description</th>
                                    <th class="text-white" style="background-color: #0099FF;">Url</th>
                                    <th class="text-white" style="background-color: #0099FF;">Date Posted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($news as $new)
                                    <tr>
                                        <td>
                                            <img src="{{ $new->image_path ? asset('storage/' . $new->image_path) : asset('assets/images/picture.png') }}"
                                                alt="Incident Image"
                                                style="max-width: 90px; max-height: 90px; width: 90px; height: 90px;">
                                        </td>
                                        <td>
                                            {{ $new->title }}
                                        </td>
                                        <td>
                                            {{ $new->description }}
                                        </td>
                                        <td>
                                            {{ $new->url }}
                                        </td>
                                        <td>
                                            {{ $new->created_at->format('F d, Y') }}
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
    </script>
@endsection
