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
                        <!-- Add News Button -->
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                            Add News
                        </button>

                        <table class="table table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th class="text-white" style="background-color: #0099FF;">Img</th>
                                    <th class="text-white" style="background-color: #0099FF;">Title</th>
                                    <th class="text-white" style="background-color: #0099FF;">Description</th>
                                    <th class="text-white" style="background-color: #0099FF;">Url</th>
                                    <th class="text-white" style="background-color: #0099FF;">Date Posted</th>
                                    <th class="text-white" style="background-color: #0099FF;">Actions</th>
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
                                        <td>{{ $new->title }}</td>
                                        <td>{{ $new->description }}</td>
                                        <td>{{ $new->url }}</td>
                                        <td>{{ $new->created_at->format('F d, Y') }}</td>
                                        <td>
                                            <!-- Edit Button -->
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editNewsModal{{ $new->id }}">
                                                Edit
                                            </button>
                                            <!-- Delete Button -->
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteNewsModal{{ $new->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit News Modal -->
                                    <div class="modal fade" id="editNewsModal{{ $new->id }}" tabindex="-1"
                                        aria-labelledby="editNewsModalLabel{{ $new->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="POST" action="{{ route('admin.news.update', $new->id) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editNewsModalLabel{{ $new->id }}">
                                                            Edit News</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Form fields for editing (title, description, url, image, etc.) -->
                                                        <div class="mb-3">
                                                            <label>Title</label>
                                                            <input type="text" name="title" class="form-control"
                                                                value="{{ $new->title }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Description</label>
                                                            <textarea name="description" class="form-control" required>{{ $new->description }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Url</label>
                                                            <input type="text" name="url" class="form-control"
                                                                value="{{ $new->url }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Image</label>
                                                            <input type="file" name="image" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Delete News Modal -->
                                    <div class="modal fade" id="deleteNewsModal{{ $new->id }}" tabindex="-1"
                                        aria-labelledby="deleteNewsModalLabel{{ $new->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="POST" action="{{ route('admin.news.destroy', $new->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="deleteNewsModalLabel{{ $new->id }}">Delete News</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete this news item?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add News Modal -->
                <div class="modal fade" id="addNewsModal" tabindex="-1" aria-labelledby="addNewsModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addNewsModalLabel">Add News</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form fields for adding (title, description, url, image, etc.) -->
                                    <div class="mb-3">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Url</label>
                                        <input type="text" name="url" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Image</label>
                                        <input type="file" name="image_path" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add News</button>
                                </div>
                            </div>
                        </form>
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
