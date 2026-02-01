@extends('layouts.admin')

@section('title', 'الشرائح (Slider)')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">إدارة الشرائح</h3>
        <div class="card-tools">
            <a href="{{ route('admin.slides.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> إضافة 
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped projects text-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th style="width: 20%">الخلفية</th>
                    <th style="width: 20%">الصورة الأمامية</th>
                    <th>العنوان</th>
                    <th style="width: 10%">الترتيب</th>
                </tr>
            </thead>
            <tbody>
                @foreach($slides as $slide)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $slide->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ asset($slide->image) }}" alt="Background" class="img-fluid" style="max-height: 50px;">
                    </td>
                    <td>
                        @if($slide->foreground_image)
                        <img src="{{ asset($slide->foreground_image) }}" alt="Foreground" class="img-fluid" style="max-height: 50px;">
                        @else
                        -
                        @endif
                    </td>
                    <td>{{ $slide->title ?? '-' }}</td>
                    <td>{{ $slide->sort_order }}</td>
                </tr>
                <tr id="edit-form-{{ $slide->id }}" class="collapse">
                    <td colspan="5">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">تعديل الشريحة</h5>
                            <form action="{{ route('admin.slides.update', $slide->id) }}" method="POST" enctype="multipart/form-data" id="update-form-{{ $slide->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs mb-4" id="langTab-{{ $slide->id }}" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="ar-tab-{{ $slide->id }}" data-bs-toggle="tab" data-bs-target="#ar-{{ $slide->id }}" type="button" role="tab" aria-controls="ar-{{ $slide->id }}" aria-selected="true">العربية</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="en-tab-{{ $slide->id }}" data-bs-toggle="tab" data-bs-target="#en-{{ $slide->id }}" type="button" role="tab" aria-controls="en-{{ $slide->id }}" aria-selected="false">English</button>
                                        </li>
                                    </ul>
                        
                                     <div class="tab-content mb-4" id="langTabContent-{{ $slide->id }}">
                                        <!-- Arabic Tab -->
                                        <div class="tab-pane fade show active" id="ar-{{ $slide->id }}" role="tabpanel" aria-labelledby="ar-tab-{{ $slide->id }}">
                                            <div class="form-group mb-3">
                                                <label for="title_ar">العنوان (عربي)</label>
                                                <input type="text" name="title_ar" class="form-control" id="title_ar" value="{{ $slide->getTitleArAttribute() }}">
                                            </div>
                                        </div>
                                         <!-- English Tab -->
                                        <div class="tab-pane fade" id="en-{{ $slide->id }}" role="tabpanel" aria-labelledby="en-tab-{{ $slide->id }}" dir="ltr">
                                            <div class="form-group mb-3">
                                                <label for="title_en">Title (English)</label>
                                                <input type="text" name="title_en" class="form-control" id="title_en" value="{{ $slide->getTitleEnAttribute() }}">
                                            </div>
                                        </div>
                                    </div>
                        
                                    <hr>
                        
                                    <div class="form-group mb-3">
                                        <label for="image">صورة الخلفية</label>
                                        @if($slide->image)
                                            <div class="mb-2">
                                                <img src="{{ asset($slide->image) }}" width="150" class="img-thumbnail">
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <input type="file" name="image" class="form-control" id="image">
                                        </div>
                                        <small class="text-muted">اتركها فارغة للإبقاء على الصورة الحالية</small>
                                    </div>
                        
                                    <div class="form-group mb-3">
                                        <label for="foreground_image">الصورة الأمامية</label>
                                        @if($slide->foreground_image)
                                            <div class="mb-2">
                                                <img src="{{ asset($slide->foreground_image) }}" width="150" class="img-thumbnail">
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <input type="file" name="foreground_image" class="form-control" id="foreground_image">
                                        </div>
                                        <small class="text-muted">اتركها فارغة للإبقاء على الصورة الحالية</small>
                                    </div>
                        
                                    <div class="form-group mb-3">
                                        <label for="sort_order">ترتيب الظهور</label>
                                        <input type="number" name="sort_order" class="form-control" id="sort_order" value="{{ $slide->sort_order }}">
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $slide->id }}" class="btn btn-primary">تحديث</button>
                                <form action="{{ route('admin.slides.destroy', $slide->id) }}" method="POST" style="display: inline-block;">
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
