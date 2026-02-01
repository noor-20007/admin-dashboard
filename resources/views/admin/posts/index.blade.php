@extends('layouts.admin')

@section('title', 'المقالات')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">إدارة المقالات</h3>
        <div class="card-tools">
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> إضافة  
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped projects text-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th style="width: 20%">الصورة</th>
                    <th>العنوان</th>
                    <th>تاريخ النشر</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $post->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($post->image)
                        <img src="{{ asset($post->image) }}" alt="Post" class="img-fluid" style="max-height: 50px;">
                        @endif
                    </td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->published_at ? $post->published_at->format('Y-m-d') : 'مسودة' }}</td>
                </tr>
                <tr id="edit-form-{{ $post->id }}" class="collapse">
                    <td colspan="4">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">تعديل المقال</h5>
                            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="update-form-{{ $post->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                     <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_en">العنوان (English)</label>
                                                <input type="text" name="title_en" class="form-control" id="title_en" value="{{ $post->getTitleEnAttribute() }}" required>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_ar">العنوان (Arabic)</label>
                                                <input type="text" name="title_ar" class="form-control" id="title_ar" value="{{ $post->getTitleArAttribute() }}" required>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="content_en">المحتوى (English)</label>
                                                <textarea name="content_en" class="form-control" id="content_en" rows="5">{{ $post->getContentEnAttribute() }}</textarea>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="content_ar">المحتوى (Arabic)</label>
                                                <textarea name="content_ar" class="form-control" id="content_ar" rows="5">{{ $post->getContentArAttribute() }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="published_at">تاريخ النشر</label>
                                        <input type="date" name="published_at" class="form-control" id="published_at" value="{{ $post->published_at ? $post->published_at->format('Y-m-d') : '' }}">
                                    </div>
                        
                                    <div class="form-group mb-3">
                                        <label for="image">الصورة</label>
                                         @if($post->image)
                                            <div class="mb-2">
                                                <img src="{{ asset($post->image) }}" width="150" class="img-thumbnail">
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <input type="file" name="image" class="form-control" id="image">
                                        </div>
                                         <small class="text-muted">اتركها فارغة للإبقاء على الصورة الحالية</small>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $post->id }}" class="btn btn-primary">تحديث</button>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display: inline-block;">
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
