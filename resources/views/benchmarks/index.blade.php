@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0">Benchmarks</h2>
            <p class="text-secondary m-0">Manage salesperson benchmark categories</p>
        </div>
        <a href="{{ route('benchmarks.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Add Benchmark
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="benchmarksTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Benchmark Value</th>
                            <th class="text-center">Active Salespersons</th>
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
        $('#benchmarksTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('benchmarks.index') }}",
            columns: [
                { data: 'id', name: 'id', render: function(data) { return '#' + data; } },
                { data: 'name', name: 'name', className: 'fw-semibold' },
                { data: 'users_count', name: 'users_count', searchable: false, className: 'text-center', render: function(data) {
                    return '<span class="badge bg-secondary px-2.5 py-1.5">' + data + '</span>';
                }},
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
            ],
            pageLength: 10,
            ordering: true
        });
    });
</script>
@endsection
