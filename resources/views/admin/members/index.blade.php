@extends('layouts.admin')

@section('title', 'أعضاء الفريق')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">إدارة الأعضاء</h3>
        <div class="card-tools">
            <a href="{{ route('admin.members.create') }}" class="btn btn-primary btn-sm">
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
                    <th>الاسم</th>
                    <th>الدور الوظيفي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $member->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($member->image)
                        <img src="{{ asset($member->image) }}" alt="Member" class="img-circle" style="width: 50px; height: 50px; object-fit: cover;">
                        @endif
                    </td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->job_title }}</td>
                </tr>
                <tr id="edit-form-{{ $member->id }}" class="collapse">
                    <td colspan="4">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">تعديل العضو</h5>
                            <form action="{{ route('admin.members.update', $member->id) }}" method="POST" enctype="multipart/form-data" id="update-form-{{ $member->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                        
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs mb-4" id="langTab-{{ $member->id }}" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="ar-tab-{{ $member->id }}" data-bs-toggle="tab" data-bs-target="#ar-{{ $member->id }}" type="button" role="tab" aria-controls="ar-{{ $member->id }}" aria-selected="true">العربية</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="en-tab-{{ $member->id }}" data-bs-toggle="tab" data-bs-target="#en-{{ $member->id }}" type="button" role="tab" aria-controls="en-{{ $member->id }}" aria-selected="false">English</button>
                                        </li>
                                    </ul>
                        
                                    <div class="tab-content mb-4" id="langTabContent-{{ $member->id }}">
                                        <!-- Arabic Tab -->
                                        <div class="tab-pane fade show active" id="ar-{{ $member->id }}" role="tabpanel" aria-labelledby="ar-tab-{{ $member->id }}">
                                            <div class="form-group mb-3">
                                                <label>الاسم (عربي)</label>
                                                <input type="text" name="name_ar" class="form-control" value="{{ $member->getNameArAttribute() }}" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>المسمى الوظيفي (عربي)</label>
                                                <input type="text" name="job_title_ar" class="form-control" value="{{ $member->getJobTitleArAttribute() }}" required>
                                            </div>
                                        </div>
                        
                                        <!-- English Tab -->
                                        <div class="tab-pane fade" id="en-{{ $member->id }}" role="tabpanel" aria-labelledby="en-tab-{{ $member->id }}" dir="ltr">
                                            <div class="form-group mb-3">
                                                <label>Name (English)</label>
                                                <input type="text" name="name_en" class="form-control" value="{{ $member->getNameEnAttribute() }}" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Job Title (English)</label>
                                                <input type="text" name="job_title_en" class="form-control" value="{{ $member->getJobTitleEnAttribute() }}" required>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <hr>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="facebook">رابط Facebook</label>
                                            <input type="text" name="facebook" class="form-control" id="facebook" value="{{ $member->facebook }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="twitter">رابط Twitter</label>
                                            <input type="text" name="twitter" class="form-control" id="twitter" value="{{ $member->twitter }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="linkedin">رابط LinkedIn</label>
                                            <input type="text" name="linkedin" class="form-control" id="linkedin" value="{{ $member->linkedin }}">
                                        </div>
                                         <div class="col-md-6 mb-3">
                                            <label for="instagram">رابط Instagram</label>
                                            <input type="text" name="instagram" class="form-control" id="instagram" value="{{ $member->instagram }}">
                                        </div>
                                    </div>
                        
                                    <div class="form-group mb-3">
                                        <label for="image">الصورة الشخصية</label>
                                         @if($member->image)
                                            <div class="mb-2">
                                                <img src="{{ asset($member->image) }}" width="150" class="img-thumbnail">
                                            </div>
                                        @endif
                                        <input type="file" name="image" class="form-control" id="image">
                                         <small class="text-muted">اتركها فارغة للإبقاء على الصورة الحالية</small>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $member->id }}" class="btn btn-primary">تحديث</button>
                                <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" style="display: inline-block;">
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
