@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="max-width: 700px;">
    <div class="mb-4">
        <a href="{{ route('notices.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Notices
        </a>
        <h2 class="fw-bold m-0">Edit Notice</h2>
        <p class="text-secondary m-0">Update notice board details</p>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('notices.update', $notice->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label text-secondary small">Notice Title</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $notice->title) }}" required autofocus placeholder="e.g. System Maintenance Notice">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="content" class="form-label text-secondary small">Notice Content</label>
                    <textarea name="content" id="content" rows="6" class="form-control @error('content') is-invalid @enderror" required placeholder="Write the announcement description...">{{ old('content', $notice->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        Update Notice
                    </button>
                    <a href="{{ route('notices.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
