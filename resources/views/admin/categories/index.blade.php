@extends('layouts.admin')

@section('title', 'الأقسام')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">إدارة الأقسام</h3>
        <div class="card-tools">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> إضافة 
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped projects text-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th>الاسم (EN)</th>
                    <th>الاسم (AR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $category->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->name_en }}</td>
                    <td>{{ $category->name_ar }}</td>
                </tr>
                <tr id="edit-form-{{ $category->id }}" class="collapse">
                    <td colspan="4">
                        <div class="p-3 bg-light border">
                             <h5 class="text-primary mb-3">تعديل القسم</h5>
                             <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" id="update-form-{{ $category->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                     <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="name_en">الاسم (English)</label>
                                                <input type="text" name="name_en" class="form-control" id="name_en" value="{{ $category->name_en }}" required>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="name_ar">الاسم (Arabic)</label>
                                                <input type="text" name="name_ar" class="form-control" id="name_ar" value="{{ $category->name_ar }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="card-footer d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $category->id }}" class="btn btn-primary">تحديث</button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline-block;">
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
