@extends('layouts.admin')

@section('title', 'التايم لاين')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">إدارة التايم لاين</h3>
        <div class="card-tools">
            <a href="{{ route('admin.timelines.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> إضافة   
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped projects text-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th>السنة</th>
                    <th>العنوان (EN)</th>
                    <th>العنوان (AR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timelines as $timeline)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $timeline->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $timeline->year }}</td>
                    <td>{{ $timeline->title_en }}</td>
                    <td>{{ $timeline->title_ar }}</td>
                </tr>
                <tr id="edit-form-{{ $timeline->id }}" class="collapse">
                    <td colspan="4">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">تعديل التايم لاين</h5>
                            <form action="{{ route('admin.timelines.update', $timeline->id) }}" method="POST" enctype="multipart/form-data" id="update-form-{{ $timeline->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                     <div class="form-group mb-3">
                                        <label for="year">السنة (Year)</label>
                                        <input type="text" name="year" class="form-control" id="year" value="{{ $timeline->year }}" required>
                                    </div>
                        
                                     <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_en">العنوان (English)</label>
                                                <input type="text" name="title_en" class="form-control" id="title_en" value="{{ $timeline->title_en }}" required>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_ar">العنوان (Arabic)</label>
                                                <input type="text" name="title_ar" class="form-control" id="title_ar" value="{{ $timeline->title_ar }}" required>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="description_en">الوصف (English)</label>
                                                <textarea name="description_en" class="form-control" id="description_en" rows="3" required>{{ $timeline->description_en }}</textarea>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="description_ar">الوصف (Arabic)</label>
                                                <textarea name="description_ar" class="form-control" id="description_ar" rows="3" required>{{ $timeline->description_ar }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $timeline->id }}" class="btn btn-primary">تحديث</button>
                                <form action="{{ route('admin.timelines.destroy', $timeline->id) }}" method="POST" style="display: inline-block;">
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
