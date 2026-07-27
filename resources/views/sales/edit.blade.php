@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="max-width: 600px;">
    <div class="mb-4">
        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Sales
        </a>
        <h2 class="fw-bold m-0">Edit Sales Entry</h2>
        <p class="text-secondary m-0">Update sales amount or date details</p>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="user_id" class="form-label text-secondary small">Salesperson</label>
                    <select name="user_id" id="user_id" class="form-select ajax-select @error('user_id') is-invalid @enderror" required>
                        <option value="">Select Salesperson</option>
                        @php
                            $selectedUserId = old('user_id', $sale->user_id);
                            $selectedUser = $selectedUserId ? \App\Models\User::find($selectedUserId) : null;
                        @endphp
                        @if($selectedUser)
                            <option value="{{ $selectedUser->id }}" selected>
                                {{ $selectedUser->name }} ({{ $selectedUser->department->name ?? 'No Dept' }})
                            </option>
                        @endif
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label text-secondary small">Sales Amount ($)</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $sale->amount) }}" required placeholder="e.g. 1500.00">
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="date" class="form-label text-secondary small">Sales Date</label>
                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $sale->date) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        Update Entry
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#user_id').select2({
            width: '100%',
            dropdownParent: $('body'),
            ajax: {
                url: "{{ route('users.search') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Select Salesperson',
            allowClear: true,
            minimumInputLength: 0
        });
    });
</script>
@endsection
