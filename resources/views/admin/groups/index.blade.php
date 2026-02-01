@extends('layouts.admin')

@section('title', 'إدارة المجموعات')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">قائمة المجموعات</h3>
        <div class="card-tools">
            <a href="{{ route('admin.groups.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> إضافة مجموعة جديدة
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المجموعة</th>
                    <th>الوصف</th>
                    <th>المشرف</th>
                    <th>تاريخ الإنشاء</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                <tr onclick="window.location='{{ route('admin.groups.edit', $group->id) }}'" style="cursor: pointer;">
                    <td>{{ $group->id }}</td>
                    <td>{{ $group->name }}</td>
                    <td>{{ Str::limit($group->description, 50) }}</td>
                    <td>
                        @if($group->supervisor)
                            <span class="badge bg-success">{{ $group->supervisor->name }}</span>
                        @else
                            <span class="badge bg-secondary">غير محدد</span>
                        @endif
                    </td>
                    <td>{{ $group->created_at->format('Y-m-d') }}</td>
                    <td>
                        <form action="{{ route('admin.groups.destroy', $group->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
