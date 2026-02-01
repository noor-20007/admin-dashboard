@extends('layouts.admin')

@section('title', 'إضافة عميل جديد')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">بيانات العميل</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">الاسم <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">الجنس</label>
                    <select name="gender" class="form-control">
                        <option value="">-- اختر --</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>ذكر</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">العمر</label>
                    <input type="number" name="age" class="form-control" value="{{ old('age') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">رقم الهوية</label>
                    <input type="text" name="identity_num" class="form-control" value="{{ old('identity_num') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">المنطقة</label>
                    <input type="text" name="region" class="form-control" value="{{ old('region') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">النوع (Type)</label>
                    <input type="text" name="type" class="form-control" value="{{ old('type') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">المجموعة</label>
                    <select name="group_id" class="form-control">
                        <option value="">-- اختر --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الصورة</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
