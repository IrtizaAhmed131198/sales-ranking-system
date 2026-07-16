@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0">Monthly Targets</h2>
            <p class="text-secondary m-0">Manage targets assigned to salespersons</p>
        </div>
        <a href="{{ route('targets.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-bullseye me-2"></i>Assign Target
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="targetsTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Salesperson Name</th>
                            <th>Department</th>
                            <th>Target Month</th>
                            <th>Target Amount</th>
                            <th class="text-end" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($targets as $target)
                            <tr>
                                <td>#{{ $target->id }}</td>
                                <td class="fw-semibold">{{ $target->user->name }}</td>
                                <td>
                                    @if($target->user->department)
                                        <span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-30 px-2.5 py-1.5">
                                            {{ $target->user->department->name }}
                                        </span>
                                    @else
                                        <span class="text-secondary small">No Department</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ date('F Y', strtotime($target->month . '-01')) }}</td>
                                <td class="fw-bold">${{ number_format($target->target_amount, 2) }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('targets.edit', $target->id) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <form action="{{ route('targets.destroy', $target->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this target?');">
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
        $('#targetsTable').DataTable({
            "pageLength": 10,
            "ordering": true
        });
    });
</script>
@endsection
