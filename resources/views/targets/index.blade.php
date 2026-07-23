@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0">Targets</h2>
            <p class="text-secondary m-0">Manage targets assigned to salespersons</p>
        </div>
        <a href="{{ route('targets.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-bullseye me-2"></i>Assign Target
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-theme-overview" id="targetsTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Salesperson Name</th>
                            <th>Department</th>
                            <th>Target Amount</th>
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
        $('#targetsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('targets.index') }}",
            columns: [
                { data: 'id', name: 'id', render: function(data) { return '#' + data; } },
                { data: 'user_name', name: 'user.name', className: 'fw-semibold' },
                { data: 'department_name', name: 'user.department.name', orderable: false, render: function(data) {
                    if (data !== 'No Department') {
                        return '<span class="badge bg-primary bg-opacity-20 text-primary text-white border border-primary border-opacity-30 px-2.5 py-1.5">' + data + '</span>';
                    }
                    return '<span class="text-secondary small">' + data + '</span>';
                }},
                { data: 'formatted_amount', name: 'target_amount', className: 'fw-bold' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
            ],
            pageLength: 10,
            ordering: true
        });
    });
</script>
@endsection
