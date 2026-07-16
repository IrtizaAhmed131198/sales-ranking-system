@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="max-width: 600px;">
    <div class="mb-4">
        <a href="{{ route('departments.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Departments
        </a>
        <h2 class="fw-bold m-0">Add Department</h2>
        <p class="text-secondary m-0">Create a new organizational department</p>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="form-label text-secondary small">Department Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus placeholder="e.g. Enterprise Sales">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        Save Department
                    </button>
                    <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
