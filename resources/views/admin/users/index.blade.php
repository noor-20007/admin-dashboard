@extends('layouts.admin')

@section('title', 'المستخدمين')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">إدارة المستخدمين</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> إضافة 
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped projects text-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>تاريخ التسجيل</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $user->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                </tr>
                <tr id="edit-form-{{ $user->id }}" class="collapse">
                    <td colspan="4">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">تعديل المستخدم</h5>
                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="update-form-{{ $user->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="name">الاسم</label>
                                        <input type="text" name="name" class="form-control" id="name" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email">البريد الإلكتروني</label>
                                        <input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="password">كلمة المرور</label>
                                        <input type="text" name="password" class="form-control" id="password" value="{{ $user->password }}">
                                    </div>
                                     <div class="form-group mb-3">
                                        <label for="password_confirmation">تأكيد كلمة المرور</label>
                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $user->id }}" class="btn btn-primary">تحديث</button>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;">
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
