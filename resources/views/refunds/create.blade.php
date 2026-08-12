@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0">Add Refund Entry</h2>
                <p class="text-secondary m-0">Record a monthly refund deduction for a salesperson</p>
            </div>
            <a href="{{ route('refunds.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Back to Refunds
            </a>
        </div>

        <div class="card border-0 shadow-sm" style="max-width: 600px;">
            <div class="card-body p-4">
                <form action="{{ route('refunds.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="user_id" class="form-label text-secondary small">Salesperson</label>
                        <select name="user_id" id="user_id" class="form-select ajax-select" required>
                            <option value="">Select a salesperson</option>
                            @if(old('user_id'))
                                @php
                                    $oldUser = \App\Models\User::find(old('user_id'));
                                @endphp
                                @if($oldUser)
                                    <option value="{{ $oldUser->id }}" selected>
                                        {{ $oldUser->name }} ({{ $oldUser->department->name ?? 'No Dept' }})
                                    </option>
                                @endif
                            @endif
                        </select>
                        @error('user_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="refund_month" class="form-label text-secondary small">Refund Month</label>
                            <input type="month" name="refund_month" id="refund_month" class="form-control" value="{{ old('refund_month', date('Y-m')) }}" required>
                            @error('refund_month')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="amount" class="form-label text-secondary small">Refund Amount ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" value="{{ old('amount') }}" required>
                            </div>
                            @error('amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning fw-semibold px-4">Save Refund</button>
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
            placeholder: 'Select a salesperson',
            allowClear: true,
            minimumInputLength: 0
        });
    });
</script>
@endsection
