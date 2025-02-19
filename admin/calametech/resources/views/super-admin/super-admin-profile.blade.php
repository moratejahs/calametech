@extends('layout.super-admin-panel')
@section('title', 'Profile')
@section('content')
    <div id="main">
        <section class="section">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body py-4 px-5">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl">
                                    <img src="{{ asset('assets/images/profile/no-image-icon.png') }}">
                                </div>
                                <div class="ms-3 name">
                                    <h5 class="font-bold">{{ auth()->user()->name }}</h5>
                                    <h6 class="text-muted mb-0">{{ auth()->user()->email }}</h6>
                                    <h6 class="text-muted mb-0">{{ auth()->user()->address }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h4 class="card-title">Recently Completed Projects</h4>
                        </div> --}}
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title mb-3">Recently Completed Projects</h4>
                                <div class="list-group">
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <p class="mb-1 fw-bold fs-6">List group item heading</p>
                                            <small>3 days ago</small>
                                        </div>
                                        <p class="mb-1">
                                            Donec id elit non mi porta gravida at eget metus. Maecenas sed
                                            diam eget risus varius blandit.
                                        </p>
                                        <small><span class="badge bg-light-warning">Late</span></small>
                                    </div>
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <p class="mb-1 fw-bold fs-6">List group item heading</p>
                                            <small>3 days ago</small>
                                        </div>
                                        <p class="mb-1">
                                            Donec id elit non mi porta gravida at eget metus. Maecenas sed
                                            diam eget risus varius blandit.
                                        </p>
                                        <small><span class="badge bg-light-warning">Late</span></small>
                                    </div>
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <p class="mb-1 fw-bold fs-6">List group item heading</p>
                                            <small>3 days ago</small>
                                        </div>
                                        <p class="mb-1">
                                            Donec id elit non mi porta gravida at eget metus. Maecenas sed
                                            diam eget risus varius blandit.
                                        </p>
                                        <small><span class="badge bg-light-success">On Time</span></small>
                                    </div>
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <p class="mb-1 fw-bold fs-6">List group item heading</p>
                                            <small>3 days ago</small>
                                        </div>
                                        <p class="mb-1">
                                            Donec id elit non mi porta gravida at eget metus. Maecenas sed
                                            diam eget risus varius blandit.
                                        </p>
                                        <small><span class="badge bg-light-success">On Time</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    </div>
@endsection
@section('scripts')
@endsection
