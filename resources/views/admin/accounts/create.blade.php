@extends('layouts.admin')

@section('title', __('messages.add') . ' ' . __('messages.account'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.data') }} {{ __('messages.account') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.accounts.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                    <select name="name" id="name" class="form-control select2" required>
                        <option value="">-- {{ __('messages.select_client') }} --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->name }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.group') }}</label>
                    <select name="group_id" class="form-control">
                        <option value="">-- {{ __('messages.select') }} --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">{{ __('messages.date') }}</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">{{ __('messages.amount') }} (Amount)</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">{{ __('messages.voucher_number') }}</label>
                    <input type="text" name="reference_number" class="form-control @error('reference_number') is-invalid @enderror" value="{{ old('reference_number') }}" required>
                    @error('reference_number')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.status') }}</label>
                     <select name="status" id="status" class="form-control">
                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>{{ __('messages.pending') }} (Pending)</option>
                        <option value="Paid" {{ old('status') == 'Paid' ? 'selected' : '' }}>{{ __('messages.paid') }} (Paid)</option>
                        <option value="Canceled" {{ old('status') == 'Canceled' ? 'selected' : '' }}>{{ __('messages.canceled') }} (Canceled)</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <strong>{{ __('messages.previous_balance') }}:</strong> <span id="previous-balance">0.00</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>{{ __('messages.current_balance') }}:</strong> <span id="new-balance">0.00</span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.notes') }}</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clientBalances = @json($clientBalances);
        const nameSelect = document.getElementById('name');
        const amountInput = document.getElementById('amount');
        const statusSelect = document.getElementById('status');
        const prevBalanceSpan = document.getElementById('previous-balance');
        const newBalanceSpan = document.getElementById('new-balance');

        function updateBalances() {
            const clientName = nameSelect.value;
            const amount = parseFloat(amountInput.value) || 0;
            const status = statusSelect.value;

            // Get previous balance (default to 0 if not found)
            let prevBalance = 0;
            if (clientName && clientBalances[clientName]) {
                prevBalance = parseFloat(clientBalances[clientName]);
            }
            
            // Calculate new balance
            let newBalance = prevBalance;
            if (status === 'Pending') {
                newBalance += amount;
            }

            // Update UI
            prevBalanceSpan.textContent = prevBalance.toFixed(2);
            newBalanceSpan.textContent = newBalance.toFixed(2);
        }

        // Attach event listeners
        nameSelect.addEventListener('change', updateBalances);
        amountInput.addEventListener('input', updateBalances);
        statusSelect.addEventListener('change', updateBalances);
    });
</script>
@endsection
