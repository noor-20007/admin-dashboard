@extends('layouts.admin')

@section('title', 'إدارة العملاء')

@section('content')
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>إجمالي العملاء</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['new_month'] }}</h3>
                <p>عملاء جدد (هذا الشهر)</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['males'] }}</h3>
                <p>الذكور</p>
            </div>
            <div class="icon">
                <i class="fas fa-male"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['females'] }}</h3>
                <p>الإناث</p>
            </div>
            <div class="icon">
                <i class="fas fa-female"></i>
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
        <form action="{{ route('admin.clients.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو الهاتف..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="group_id" class="form-control">
                        <option value="">جميع المجموعات</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="gender" class="form-control">
                        <option value="">الكل (الجنس)</option>
                        <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>ذكر</option>
                        <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> بحث</button>
                    <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">قائمة العملاء</h3>
        <div class="card-tools">
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> إضافة عميل جديد
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>الاسم</th>
                    <th>الجنس</th>
                    <th>العمر</th>
                    <th>رقم الهوية</th>
                    <th>الهاتف</th>
                    <th>المنطقة</th>
                    <th>النوع</th>
                    <th>المجموعة</th>
                    <th>تاريخ الإضافة</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr onclick="window.location='{{ route('admin.clients.edit', $client->id) }}'" style="cursor: pointer;">
                    <td>
                        @if($client->image)
                            <img src="{{ asset($client->image) }}" alt="{{ $client->name }}" width="50" class="img-circle">
                        @else
                            <i class="fas fa-user-circle fa-2x text-muted"></i>
                        @endif
                    </td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->gender }}</td>
                    <td>{{ $client->age }}</td>
                    <td>{{ $client->identity_num }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->region }}</td>
                    <td>{{ $client->type }}</td>
                    <td>
                        @if($client->group)
                            <span class="badge bg-primary">{{ $client->group->name }}</span>
                        @else
                            <span class="badge bg-secondary">غير محدد</span>
                        @endif
                    </td>
                    <td>{{ $client->created_at->format('d-m-Y') }}</td>
                    <td>
                        <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
