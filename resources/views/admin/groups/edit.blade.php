@extends('layouts.admin')

@section('title', 'تعديل المجموعة')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">تعديل: {{ $group->name }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.groups.update', $group->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">اسم المجموعة <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $group->name) }}">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
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

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ التعديلات</button>
            <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">إلغاء</a>

            <form action="{{ route('admin.groups.destroy', $group->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> حذف</button>
            </form>
        </form>
    </div>
</div>
@endsection
