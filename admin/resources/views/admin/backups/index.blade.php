@extends('layout.admin-panel')
@section('title','Database Backups')
@section('links')
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
@endsection
@section('content')
    <div id="main">
        <div class="page-heading">
            <h3>Database Backups</h3>
            <p class="text-muted">Recent database backups.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.backups.generate') }}" method="post" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-hdd"></i> Backup now
                    </button>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>File</th>
                                <th>Backed up since</th>
                                <th>Size</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($backups as $b)
                                <tr>
                                    <td>
                                        <i class="bi bi-database"></i>
                                        {{ $b->filename }}
                                    </td>
                                    <td>
                                        {{ $b->created_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        {{ number_format($b->size_bytes / 1024, 2) }} KB
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.backups.download', $b->id) }}">Download</a>
                                        <form action="{{ route('admin.backups.delete', $b->id) }}" method="post" style="display:inline-block" onsubmit="return confirm('Delete this backup?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No backups yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

