@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0">Departments</h2>
            <p class="text-secondary m-0">Manage organizational departments</p>
        </div>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Add Department
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-theme-overview" id="departmentsTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Department Name</th>
                            <th class="text-center">Total Salespersons</th>
                            <th class="text-end" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $dept)
                            <tr>
                                <td>#{{ $dept->id }}</td>
                                <td class="fw-semibold">{{ $dept->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary px-2.5 py-1.5">{{ $dept->users_count }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('departments.edit', $dept->id) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this department? All associated users will remain but without a department assigned.');">
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
        $('#departmentsTable').DataTable({
            "pageLength": 10,
            "ordering": true
        });
    });
</script>
@endsection
