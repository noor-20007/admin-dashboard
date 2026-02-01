@extends('layouts.admin')

@section('title', 'الخدمات')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">إدارة الخدمات</h3>
        <div class="card-tools">
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> إضافة  
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped projects text-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th>الأيقونة</th>
                    <th>العنوان (EN)</th>
                    <th>العنوان (AR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $service->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $service->icon }}</td>
                    <td>{{ $service->getTitleEnAttribute() }}</td>
                    <td>{{ $service->getTitleArAttribute() }}</td>
                </tr>
                <tr id="edit-form-{{ $service->id }}" class="collapse">
                    <td colspan="4">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">تعديل الخدمة</h5>
                            <form action="{{ route('admin.services.update', $service->id) }}" method="POST" id="update-form-{{ $service->id }}">
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
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $service->id }}" class="btn btn-primary">تحديث</button>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد؟')">
                                        <i class="bi bi-trash"></i> حذف
                                    </button>
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
