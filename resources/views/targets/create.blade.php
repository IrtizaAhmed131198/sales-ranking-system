@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="max-width: 600px;">
    <div class="mb-4">
        <a href="{{ route('targets.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Targets
        </a>
        <h2 class="fw-bold m-0">Assign Target</h2>
        <p class="text-secondary m-0">Set a sales target for a salesperson</p>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('targets.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="user_id" class="form-label text-secondary small">Salesperson</label>
                    <select name="user_id" id="user_id" class="form-select ajax-select @error('user_id') is-invalid @enderror" required autofocus>
                        <option value="">Select Salesperson</option>
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
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="target_amount" class="form-label text-secondary small">Target Amount ($)</label>
                    <input type="number" step="0.01" name="target_amount" id="target_amount" class="form-control @error('target_amount') is-invalid @enderror" value="{{ old('target_amount') }}" required placeholder="e.g. 5000.00">
                    @error('target_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        Save Target
                    </button>
                    <a href="{{ route('targets.index') }}" class="btn btn-outline-secondary">
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
