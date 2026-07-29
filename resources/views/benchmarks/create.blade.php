@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="max-width: 600px;">
    <div class="mb-4">
        <a href="{{ route('benchmarks.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Benchmarks
        </a>
        <h2 class="fw-bold m-0">Add Benchmark</h2>
        <p class="text-secondary m-0">Create a new salesperson benchmark tier</p>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('benchmarks.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label text-secondary small">Name (e.g. Titan)</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus placeholder="e.g. Titan">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="front_sale_value" class="form-label text-secondary small">Front Sale Value</label>
                    <input type="number" step="0.01" min="0" name="front_sale_value" id="front_sale_value" class="form-control @error('front_sale_value') is-invalid @enderror" value="{{ old('front_sale_value') }}" required placeholder="e.g. 15000">
                    @error('front_sale_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="upsell_value" class="form-label text-secondary small">Upsell Value</label>
                    <input type="number" step="0.01" min="0" name="upsell_value" id="upsell_value" class="form-control @error('upsell_value') is-invalid @enderror" value="{{ old('upsell_value') }}" required placeholder="e.g. 20000">
                    @error('upsell_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Save Benchmark</button>
                    <a href="{{ route('benchmarks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
