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
                <i class="fas fa-plus"></i> إضافة حساب جديد
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>المجموعة</th>
                    <th>التاريخ</th>
                    <th>المطلوب (Amount)</th>
                    <th>رقم السند</th>
                    <th>الحالة</th>
                    <th>الملاحظات</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                <tr onclick="window.location='{{ route('admin.accounts.edit', $account->id) }}'" style="cursor: pointer;">
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
                    <td>
                        <form action="{{ route('admin.accounts.destroy', $account->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger custom-delete-btn" onclick="event.stopPropagation();"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
