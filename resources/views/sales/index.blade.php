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
                        @foreach($sales as $sale)
                            <tr>
                                <td>#{{ $sale->id }}</td>
                                <td class="fw-semibold">{{ $sale->user->name }}</td>
                                <td>
                                    @if($sale->user->department)
                                        <span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-30 px-2.5 py-1.5">
                                            {{ $sale->user->department->name }}
                                        </span>
                                    @else
                                        <span class="text-secondary small">No Department</span>
                                    @endif
                                </td>
                                <td>{{ date('M d, Y', strtotime($sale->date)) }}</td>
                                <td class="fw-bold text-success">${{ number_format($sale->amount, 2) }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this sales entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
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
        $('#salesTable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [[3, "desc"]] // Sort by date desc by default
        });
    });
</script>
@endsection
