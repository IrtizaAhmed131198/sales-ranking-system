@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0">Monthly Refunds</h2>
                <p class="text-secondary m-0">Manage lump-sum monthly refunds for salespersons</p>
            </div>
            <a href="{{ route('refunds.create') }}" class="btn btn-warning text-dark fw-semibold">
                <i class="fa-solid fa-plus me-2"></i>Add Refund Entry
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="refundsTable">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Salesperson Name</th>
                                <th>Department</th>
                                <th>Refund Month</th>
                                <th>Amount Deducted</th>
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
            $('#refundsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('refunds.index') }}",
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
                    { data: 'formatted_month', name: 'refund_month' },
                    { data: 'formatted_amount', name: 'amount', className: 'fw-bold text-danger' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
                ],
                pageLength: 10,
                ordering: true,
                order: [[3, "desc"]]
            });
        });
    </script>
@endsection
