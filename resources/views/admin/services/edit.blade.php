@extends('layouts.admin')

@section('title', 'تعديل الخدمة')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">تعديل الخدمة</h3>
    </div>
    <form action="{{ route('admin.services.update', $service->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
             <div class="row">
                <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="title_en">العنوان (English)</label>
                        <input type="text" name="title_en" class="form-control" id="title_en" value="{{ $service->getTitleEnAttribute() }}" required>
                    </div>
                </div>
                 <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="title_ar">العنوان (Arabic)</label>
                        <input type="text" name="title_ar" class="form-control" id="title_ar" value="{{ $service->getTitleArAttribute() }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="description_en">الوصف (English)</label>
                        <textarea name="description_en" class="form-control" id="description_en" rows="3">{{ $service->getDescriptionEnAttribute() }}</textarea>
                    </div>
                </div>
                 <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="description_ar">الوصف (Arabic)</label>
                        <textarea name="description_ar" class="form-control" id="description_ar" rows="3">{{ $service->getDescriptionArAttribute() }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label for="icon">فئة الأيقونة (Icon Class)</label>
                <input type="text" name="icon" class="form-control" id="icon" value="{{ $service->icon }}">
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">تحديث</button>
            <a href="{{ route('admin.services.index') }}" class="btn btn-default float-end">إلغاء</a>
            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد؟')">
                    <i class="bi bi-trash"></i> حذف
                </button>
            </form>
        </div>
    </form>
</div>
@endsection
