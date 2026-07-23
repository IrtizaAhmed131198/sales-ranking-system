@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0">Notice Board</h2>
            <p class="text-secondary m-0">Post announcements and bulletins</p>
        </div>
        <a href="{{ route('notices.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Add Notice
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="noticesTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Title</th>
                            <th>Announcement Content</th>
                            <th>Posted At</th>
                            <th class="text-end" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#noticesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('notices.index') }}",
            columns: [
                { data: 'id', name: 'id', render: function(data) { return '#' + data; } },
                { data: 'title', name: 'title', className: 'fw-semibold' },
                { data: 'content', name: 'content', className: 'text-secondary text-truncate', render: function(data) {
                    return data.length > 80 ? data.substr(0, 80) + '...' : data;
                }},
                { data: 'formatted_date', name: 'created_at', className: 'text-secondary small' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
            ],
            pageLength: 10,
            ordering: true,
            order: [[3, "desc"]] // Show newest notices first
        });
    });
</script>
@endsection
