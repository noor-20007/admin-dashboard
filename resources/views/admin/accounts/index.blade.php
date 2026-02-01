@extends('layouts.admin')

@section('title', 'إدارة الحسابات')

@section('content')
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>{{ number_format($stats['total_amount'], 2) }}</h3>
                <p>إجمالي المبالغ</p>
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
                <p>المبالغ المحصلة (Paid)</p>
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
                <p>ديون / معلقة (Pending)</p>
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
                <p>ملغاة (Canceled)</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-secondary mb-3">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter"></i> تصفية البحث</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.accounts.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو رقم السند..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <select name="group_id" class="form-control">
                        <option value="">جميع المجموعات</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="status" class="form-control">
                        <option value="">كل الحالات</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Canceled" {{ request('status') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>
                 <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> بحث</button>
                </div>
            </div>
             <div class="row">
                <div class="col-md-3 mb-2">
                    <label>من تاريخ:</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label>إلى تاريخ:</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 mb-2 d-flex align-items-end">
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary w-100">إلغاء الفلتر</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">قائمة الحسابات</h3>
        <div class="card-tools">
            <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> إضافة  
            </a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped text-nowrap">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>المجموعة</th>
                    <th>التاريخ</th>
                    <th>المطلوب (Amount)</th>
                    <th>رقم السند</th>
                    <th>الحالة</th>
                    <th>الملاحظات</th>
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
                            <span class="badge bg-secondary">غير محدد</span>
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
                            <h5 class="text-primary mb-3">تعديل: {{ $account->name }}</h5>
                            <form action="{{ route('admin.accounts.update', $account->id) }}" method="POST" id="update-form-{{ $account->id }}" class="account-edit-form"
                                data-original-name="{{ $account->name }}"
                                data-original-amount="{{ $account->amount }}"
                                data-original-status="{{ $account->status }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الاسم <span class="text-danger">*</span></label>
                                        <select name="name" class="form-control select2 client-select" required>
                                            <option value="">-- اختر العميل --</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->name }}" {{ old('name', $account->name) == $client->name ? 'selected' : '' }}>{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المجموعة</label>
                                        <select name="group_id" class="form-control">
                                            <option value="">-- اختر --</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" {{ old('group_id', $account->group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">التاريخ</label>
                                        <input type="date" name="date" class="form-control" value="{{ old('date', $account->date) }}">
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">المطلوب (Amount)</label>
                                        <input type="number" step="0.01" name="amount" class="form-control amount-input" value="{{ old('amount', $account->amount) }}">
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">رقم السند</label>
                                        <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $account->reference_number) }}">
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الحالة</label>
                                        <select name="status" class="form-control status-select">
                                            <option value="Pending" {{ old('status', $account->status) == 'Pending' ? 'selected' : '' }}>مديونية (Pending)</option>
                                            <option value="Paid" {{ old('status', $account->status) == 'Paid' ? 'selected' : '' }}>خالص (Paid)</option>
                                            <option value="Canceled" {{ old('status', $account->status) == 'Canceled' ? 'selected' : '' }}>ملغي (Canceled)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <strong>الرصيد السابق:</strong> <span class="previous-balance">0.00</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success">
                                            <strong>الرصيد الحالي (بعد التعديل):</strong> <span class="new-balance">0.00</span>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="mb-3">
                                    <label class="form-label">الملاحظات</label>
                                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $account->notes) }}</textarea>
                                </div>
                    
                            </form>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" form="update-form-{{ $account->id }}" class="btn btn-primary"><i class="fas fa-save"></i> حفظ التعديلات</button>
                                    
                                    <form action="{{ route('admin.accounts.destroy', $account->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> حذف</button>
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
