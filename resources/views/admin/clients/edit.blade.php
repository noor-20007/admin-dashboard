@extends('layouts.admin')

@section('title', 'تعديل بيانات العميل')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">تعديل: {{ $client->name }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">الاسم <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $client->name) }}">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">الجنس</label>
                    <select name="gender" class="form-control">
                        <option value="">-- اختر --</option>
                        <option value="Male" {{ old('gender', $client->gender) == 'Male' ? 'selected' : '' }}>ذكر</option>
                        <option value="Female" {{ old('gender', $client->gender) == 'Female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">العمر</label>
                    <input type="number" name="age" class="form-control" value="{{ old('age', $client->age) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">رقم الهوية</label>
                    <input type="text" name="identity_num" class="form-control" value="{{ old('identity_num', $client->identity_num) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">المنطقة</label>
                    <input type="text" name="region" class="form-control" value="{{ old('region', $client->region) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">النوع (Type)</label>
                    <input type="text" name="type" class="form-control" value="{{ old('type', $client->type) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">المجموعة</label>
                    <select name="group_id" class="form-control">
                        <option value="">-- اختر --</option>
                        @foreach($groups as $group)
                             <option value="{{ $group->id }}" {{ old('group_id', $client->group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الصورة</label>
                @if($client->image)
                    <div class="mb-2"><img src="{{ asset($client->image) }}" width="100" class="img-thumbnail"></div>
                @endif
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ التعديلات</button>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">إلغاء</a>

            <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> حذف</button>
            </form>
        </form>
    </div>
</div>
@endsection
