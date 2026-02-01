@extends('layouts.admin')

@section('title', 'إضافة مجموعة جديدة')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">بيانات المجموعة</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.groups.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">اسم المجموعة <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">المشرف</label>
                <select name="supervisor_id" class="form-control">
                    <option value="">-- اختر مشرف --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('supervisor_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
            <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
