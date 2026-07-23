@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0">Sales Management</h2>
                <p class="text-secondary m-0">Record and track salesperson sales entries</p>
            </div>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-2"></i>Add Sales Entry
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="salesTable">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Salesperson Name</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Sales Amount</th>
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
        $(document).ready(function () {
            $('#salesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sales.index') }}",
                columns: [
                    { data: 'id', name: 'id', render: function (data) { return '#' + data; } },
                    { data: 'user_name', name: 'user.name', className: 'fw-semibold' },
                    {
                        data: 'department_name', name: 'user.department.name', orderable: false, render: function (data) {
                            if (data !== 'No Department') {
                                return '<span class="badge bg-primary text-white bg-opacity-20 text-primary border border-primary border-opacity-30 px-2.5 py-1.5">' + data + '</span>';
                            }
                            return '<span class="text-secondary small">' + data + '</span>';
                        }
                    },
                    { data: 'formatted_date', name: 'date' },
                    { data: 'formatted_amount', name: 'amount', className: 'fw-bold text-success' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
                ],
                pageLength: 10,
                ordering: true,
                order: [[3, "desc"]] // Sort by date desc by default
            });
        });
    </script>
@endsection
