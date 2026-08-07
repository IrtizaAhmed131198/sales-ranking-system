@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="max-width: 600px;">
    <div class="mb-4">
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Salespersons
        </a>
        <h2 class="fw-bold m-0">Edit Salesperson</h2>
        <p class="text-secondary m-0">Update salesperson details</p>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label text-secondary small">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus placeholder="e.g. John Doe">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-secondary small">Email Address (Optional)</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="e.g. john@example.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="department_id" class="form-label text-secondary small">Department (Optional)</label>
                    <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="benchmark_id" class="form-label text-secondary small">Benchmark Category (Optional)</label>
                    <select name="benchmark_id" id="benchmark_id" class="form-select @error('benchmark_id') is-invalid @enderror">
                        <option value="">Select Benchmark Category</option>
                        @foreach($benchmarks as $bm)
                            <option value="{{ $bm->id }}" {{ old('benchmark_id', $user->benchmark_id) == $bm->id ? 'selected' : '' }}>
                                {{ $bm->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('benchmark_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="roles" class="form-label text-secondary small">Salesperson Roles (Optional)</label>
                    <select name="roles[]" id="roles" class="form-select @error('roles') is-invalid @enderror" multiple>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Hold Ctrl (Windows) or Cmd (Mac) to select multiple roles.</div>
                    @error('roles')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label text-secondary small">Salesperson Image (Optional)</label>
                    @if($user->image_path)
                        <div class="mb-2">
                            <img src="{{ asset($user->image_path) }}" alt="{{ $user->name }}" class="rounded-circle border border-secondary" style="width: 80px; height: 80px; object-fit: cover;">
                            <span class="text-secondary small ms-2">Current Image</span>
                        </div>
                    @endif
                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label text-secondary small" for="is_active">Active Status</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        Update Salesperson
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
