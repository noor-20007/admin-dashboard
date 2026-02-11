@extends('layouts.admin')

@section('title', __('messages.accounts'))

@section('content')
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>{{ number_format($stats['total_amount'], 2) }}</h3>
                <p>{{ __('messages.total_amount') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($stats['total_paid'], 2) }}</h3>
                <p>{{ __('messages.total_paid') }} ({{ __('messages.paid') }})</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($stats['total_pending'], 2) }}</h3>
                <p>{{ __('messages.total_pending') }} ({{ __('messages.pending') }})</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($stats['total_canceled'], 2) }}</h3>
                <p>{{ __('messages.total_canceled') }} ({{ __('messages.canceled') }})</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Simple Filter Bar -->
<div class="row mb-3">
    <div class="col-md-3">
        <select name="search" class="form-select" id="accountSearch">
            <option value="">{{ __('messages.select_account') }}</option>
            @if(isset($accounts))
                @foreach($accounts as $account)
                    <option value="{{ $account->name }}" {{ request('search') == $account->name ? 'selected' : '' }}>
                        {{ $account->name }}
                        @if($account->voucher_number)
                            ({{ $account->voucher_number }})
                        @endif
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-2">
        <select name="group_id" class="form-select" id="groupFilter">
            <option value="">{{ __('messages.groups') }}</option>
            @foreach($groups as $group)
                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select" id="statusFilter">
            <option value="">{{ __('messages.status') }}</option>
            <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
            <option value="Canceled" {{ request('status') == 'Canceled' ? 'selected' : '' }}>{{ __('messages.canceled') }}</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="date_from" class="form-control" id="dateFrom" placeholder="{{ __('messages.from_date') }}" value="{{ request('date_from') }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="date_to" class="form-control" id="dateTo" placeholder="{{ __('messages.to_date') }}" value="{{ request('date_to') }}">
    </div>
    <div class="col-md-1">
        <div class="btn-group w-100" role="group">
            <button type="submit" class="btn btn-primary" id="searchBtn">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary" title="{{ __('messages.reset') }}">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all filter elements
    const accountSearch = document.getElementById('accountSearch');
    const groupFilter = document.getElementById('groupFilter');
    const statusFilter = document.getElementById('statusFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const searchBtn = document.getElementById('searchBtn');
    
    // Function to build URL and redirect
    function applyFilters() {
        const params = new URLSearchParams();
        
        // Add all filter values
        if (accountSearch.value) params.append('search', accountSearch.value);
        if (groupFilter.value) params.append('group_id', groupFilter.value);
        if (statusFilter.value) params.append('status', statusFilter.value);
        if (dateFrom.value) params.append('date_from', dateFrom.value);
        if (dateTo.value) params.append('date_to', dateTo.value);
        
        // Redirect to new URL
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.location.href = newUrl;
    }
    
    // Add event listeners for immediate filtering
    accountSearch.addEventListener('change', applyFilters);
    groupFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    dateFrom.addEventListener('change', applyFilters);
    dateTo.addEventListener('change', applyFilters);
    
    // Keep button functionality for manual search
    searchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        applyFilters();
    });
});
</script>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.accounts') }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('messages.add') }}
            </a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped text-nowrap">
            <thead>
                <tr>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.group') }}</th>
                    <th>{{ __('messages.date') }}</th>
                    <th>{{ __('messages.amount') }}</th>
                    <th>{{ __('messages.reference_number') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.notes') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $account->id }}" style="cursor: pointer;">
                    <td>{{ $account->name }}</td>
                    <td>
                        @if($account->group)
                            <span class="badge bg-primary">{{ $account->group->name }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('messages.not_specified') }}</span>
                        @endif
                    </td>
                    <td>{{ $account->date }}</td>
                    <td>{{ number_format($account->amount, 2) }}</td>
                    <td>{{ $account->reference_number }}</td>
                    <td>{{ $account->status }}</td>
                    <td>{{ Str::limit($account->notes, 30) }}</td>
                </tr>
                <tr id="edit-form-{{ $account->id }}" class="collapse">
                    <td colspan="7">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">{{ __('messages.edit') }}: {{ $account->name }}</h5>
                            <form action="{{ route('admin.accounts.update', $account->id) }}" method="POST" id="update-form-{{ $account->id }}" class="account-edit-form"
                                data-original-name="{{ $account->name }}"
                                data-original-amount="{{ $account->amount }}"
                                data-original-status="{{ $account->status }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                        <select name="name" class="form-control select2 client-select" required>
                                            <option value="">{{ __('messages.select_client') }}</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->name }}" {{ old('name', $account->name) == $client->name ? 'selected' : '' }}>{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('messages.group') }}</label>
                                        <select name="group_id" class="form-control">
                                            <option value="">{{ __('messages.select') }}</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" {{ old('group_id', $account->group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.date') }}</label>
                                        <input type="date" name="date" class="form-control" value="{{ old('date', $account->date) }}">
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.amount') }}</label>
                                        <input type="number" step="0.01" name="amount" class="form-control amount-input" value="{{ old('amount', $account->amount) }}">
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.reference_number') }}</label>
                                        <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $account->reference_number) }}">
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('messages.status') }}</label>
                                        <select name="status" class="form-control status-select">
                                            <option value="Pending" {{ old('status', $account->status) == 'Pending' ? 'selected' : '' }}>{{ __('messages.pending') }} (Pending)</option>
                                            <option value="Paid" {{ old('status', $account->status) == 'Paid' ? 'selected' : '' }}>{{ __('messages.paid') }} (Paid)</option>
                                            <option value="Canceled" {{ old('status', $account->status) == 'Canceled' ? 'selected' : '' }}>{{ __('messages.canceled') }} (Canceled)</option>
                                        </select>
                                    </div>
                                </div>
 
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <strong>{{ __('messages.previous_balance') }}:</strong> <span class="previous-balance">0.00</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success">
                                            <strong>{{ __('messages.current_balance') }}:</strong> <span class="new-balance">0.00</span>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.notes') }}</label>
                                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $account->notes) }}</textarea>
                                </div>
                    
                            </form>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" form="update-form-{{ $account->id }}" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.save_changes') }}</button>
                                    
                                    <form action="{{ route('admin.accounts.destroy', $account->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('messages.delete_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> {{ __('messages.delete') }}</button>
                                    </form>
                                </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clientBalances = @json($clientBalances ?? []);

            function updateRowBalances(form) {
                const nameSelect = form.querySelector('.client-select');
                const amountInput = form.querySelector('.amount-input');
                const statusSelect = form.querySelector('.status-select');
                const prevBalanceSpan = form.querySelector('.previous-balance');
                const newBalanceSpan = form.querySelector('.new-balance');

                if (!nameSelect || !amountInput || !statusSelect) return;

                const clientName = nameSelect.value;
                const newAmount = parseFloat(amountInput.value) || 0;
                const newStatus = statusSelect.value;

                // Original values to subtract from base balance
                const originalName = form.dataset.originalName;
                const originalAmount = parseFloat(form.dataset.originalAmount) || 0;
                const originalStatus = form.dataset.originalStatus;

                // Get base balance for the selected client from DB
                let baseBalance = 0;
                if (clientName && clientBalances[clientName]) {
                    baseBalance = parseFloat(clientBalances[clientName]);
                }

                // If we are editing the same client, the baseBalance ALREADY includes the original transaction
                // So we must subtract the original amount if it was Pending
                let truePrevBalance = baseBalance;
                if (clientName === originalName && originalStatus === 'Pending') {
                    truePrevBalance -= originalAmount;
                }

                // Calculate new balance
                let newBalance = truePrevBalance;
                if (newStatus === 'Pending') {
                    newBalance += newAmount;
                }

                prevBalanceSpan.textContent = truePrevBalance.toFixed(2);
                newBalanceSpan.textContent = newBalance.toFixed(2);
            }

            // Initialize all forms
            document.querySelectorAll('.account-edit-form').forEach(form => {
                updateRowBalances(form); // Initial calculation
                
                form.addEventListener('change', function(e) {
                    if (e.target.matches('.client-select, .amount-input, .status-select')) {
                        updateRowBalances(form);
                    }
                });
                
                form.addEventListener('input', function(e) {
                    if (e.target.matches('.amount-input')) {
                        updateRowBalances(form);
                    }
                });
            });
        });
    </script>
</div>
@endsection
