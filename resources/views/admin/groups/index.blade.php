@extends('layouts.admin')

@section('title', 'إدارة المجموعات')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">قائمة المجموعات</h3>
        <div class="card-tools">
            <a href="{{ route('admin.groups.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> إضافة  
            </a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped text-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المجموعة</th>
                    <th>الوصف</th>
                    <th>المشرف</th>
                    <th>تاريخ الإنشاء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $group->id }}" style="cursor: pointer;">
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
                </tr>
                <tr id="edit-form-{{ $group->id }}" class="collapse">
                    <td colspan="5">
                         <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">تعديل: {{ $group->name }}</h5>
                            <form action="{{ route('admin.groups.update', $group->id) }}" method="POST" id="update-form-{{ $group->id }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label class="form-label">اسم المجموعة <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $group->name) }}">
                                </div>
                    
                                <div class="mb-3">
                                    <label class="form-label">الوصف</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $group->description) }}</textarea>
                                </div>
                    
                                <div class="mb-3">
                                    <label class="form-label">المشرف</label>
                                    <select name="supervisor_id" class="form-control">
                                        <option value="">-- اختر مشرف --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('supervisor_id', $group->supervisor_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $group->id }}" class="btn btn-primary"><i class="fas fa-save"></i> حفظ التعديلات</button>
                                
                                <form action="{{ route('admin.groups.destroy', $group->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
</div>
@endsection
