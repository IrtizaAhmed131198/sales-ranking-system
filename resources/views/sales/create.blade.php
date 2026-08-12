@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="max-width: 600px;">
    <div class="mb-4">
        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Sales
        </a>
        <h2 class="fw-bold m-0">Add Sales Entry</h2>
        <p class="text-secondary m-0">Record a new sales amount for a salesperson</p>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('sales.store') }}" method="POST">
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

                <div class="mb-3">
                    <label for="role_id" class="form-label text-secondary small">Role</label>
                    <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required disabled>
                        <option value="">Select a user first...</option>
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label text-secondary small">Sales Amount ($)</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required placeholder="e.g. 1500.00">
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="date" class="form-label text-secondary small">Sales Date</label>
                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        Save Entry
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

        $('#user_id').on('change', function() {
            var userId = $(this).val();
            var roleSelect = $('#role_id');
            var oldRoleId = "{{ old('role_id') }}";
            
            roleSelect.html('<option value="">Loading...</option>');
            roleSelect.prop('disabled', true);

            if (userId) {
                $.ajax({
                    url: '/users/' + userId + '/roles',
                    type: 'GET',
                    success: function(data) {
                        roleSelect.empty();
                        if (data.length > 0) {
                            if (data.length === 1 && !oldRoleId) {
                                roleSelect.append('<option value="' + data[0].id + '" selected>' + data[0].name + '</option>');
                            } else {
                                roleSelect.append('<option value="">Select Role</option>');
                                $.each(data, function(index, role) {
                                    var selected = (oldRoleId == role.id || data.length === 1) ? 'selected' : '';
                                    roleSelect.append('<option value="' + role.id + '" ' + selected + '>' + role.name + '</option>');
                                });
                            }
                            roleSelect.prop('disabled', false);
                        } else {
                            roleSelect.append('<option value="">No roles found</option>');
                        }
                    },
                    error: function() {
                        roleSelect.html('<option value="">Error loading roles</option>');
                    }
                });
            } else {
                roleSelect.html('<option value="">Select a user first...</option>');
            }
        });

        if ($('#user_id').val()) {
            $('#user_id').trigger('change');
        }
    });
</script>
@endsection
