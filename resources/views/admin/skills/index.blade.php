@extends('layouts.admin')

@section('title', 'المهارات')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">إدارة المهارات</h3>
        <div class="card-tools">
            <a href="{{ route('admin.skills.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> إضافة  
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped projects text-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th>اسم المهارة</th>
                    <th>النسبة المئوية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($skills as $skill)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $skill->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $skill->name }}</td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-success" style="width: {{ $skill->percent }}%"></div>
                        </div>
                        <small>{{ $skill->percent }}%</small>
                    </td>
                </tr>
                <tr id="edit-form-{{ $skill->id }}" class="collapse">
                    <td colspan="3">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">تعديل المهارة</h5>
                            <form action="{{ route('admin.skills.update', $skill->id) }}" method="POST" id="update-form-{{ $skill->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs mb-4" id="langTab-{{ $skill->id }}" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="ar-tab-{{ $skill->id }}" data-bs-toggle="tab" data-bs-target="#ar-{{ $skill->id }}" type="button" role="tab" aria-controls="ar-{{ $skill->id }}" aria-selected="true">العربية</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="en-tab-{{ $skill->id }}" data-bs-toggle="tab" data-bs-target="#en-{{ $skill->id }}" type="button" role="tab" aria-controls="en-{{ $skill->id }}" aria-selected="false">English</button>
                                        </li>
                                    </ul>
                        
                                    <div class="tab-content mb-4" id="langTabContent-{{ $skill->id }}">
                                        <!-- Arabic Tab -->
                                        <div class="tab-pane fade show active" id="ar-{{ $skill->id }}" role="tabpanel" aria-labelledby="ar-tab-{{ $skill->id }}">
                                            <div class="form-group mb-3">
                                                <label for="name_ar">اسم المهارة (عربي)</label>
                                                <input type="text" name="name_ar" class="form-control" id="name_ar" value="{{ $skill->getNameArAttribute() }}" required>
                                            </div>
                                        </div>
                        
                                        <!-- English Tab -->
                                        <div class="tab-pane fade" id="en-{{ $skill->id }}" role="tabpanel" aria-labelledby="en-tab-{{ $skill->id }}" dir="ltr">
                                             <div class="form-group mb-3">
                                                <label for="name_en">Skill Name (English)</label>
                                                <input type="text" name="name_en" class="form-control" id="name_en" value="{{ $skill->getNameEnAttribute() }}" required>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="form-group mb-3">
                                        <label for="percentage">النسبة المئوية (0-100)</label>
                                        <input type="number" name="percentage" class="form-control" id="percentage" min="0" max="100" value="{{ $skill->percent }}" required>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $skill->id }}" class="btn btn-primary">تحديث</button>
                                <form action="{{ route('admin.skills.destroy', $skill->id) }}" method="POST" style="display: inline-block;">
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
