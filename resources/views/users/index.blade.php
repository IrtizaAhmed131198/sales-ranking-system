@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0">Salespersons</h2>
            <p class="text-secondary m-0">Manage salesperson records</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-user-plus me-2"></i>Add Salesperson
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="usersTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Benchmark</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Created At</th>
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
        $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            columns: [
                { data: 'id', name: 'id', render: function(data) { return '#' + data; } },
                { data: 'name', name: 'name', className: 'fw-semibold' },
                { data: 'email', name: 'email', defaultContent: 'N/A' },
                { data: 'benchmark_name', name: 'benchmark.name', render: function(data) {
                    return '<span class="badge bg-success bg-opacity-20 text-success text-white border border-success border-opacity-30 px-2.5 py-1.5">' + data + '</span>';
                }},
                { data: 'department_name', name: 'department.name', orderable: false, render: function(data) {
                    if (data !== 'No Department') {
                        return '<span class="badge bg-primary bg-opacity-20 text-primary text-white border border-primary border-opacity-30 px-2.5 py-1.5">' + data + '</span>';
                    }
                    return '<span class="text-secondary small">' + data + '</span>';
                }},
                { data: 'role_name', name: 'role.name', render: function(data) {
                    return '<span class="badge bg-danger bg-opacity-20 text-danger text-white border border-danger border-opacity-30 px-2.5 py-1.5">' + data + '</span>';
                }},
                { data: 'created_date', name: 'created_at', className: 'text-secondary small' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
            ],
            pageLength: 10,
            ordering: true
        });
    });
</script>
@endsection
