@extends('layouts.admin')

@section('title', 'تعديل الحساب')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">تعديل: {{ $account->name }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.accounts.update', $account->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">الاسم <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $account->name) }}">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">المجموعة</label>
                    <select name="group_id" class="form-control">
                        <option value="">-- اختر --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id', $account->group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">التاريخ</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', $account->date) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">المطلوب (Amount)</label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $account->amount) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">رقم السند</label>
                    <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $account->reference_number) }}">
                </div>
            </div>

             <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">الحالة</label>
                     <select name="status" class="form-control">
                        <option value="Pending" {{ old('status', $account->status) == 'Pending' ? 'selected' : '' }}>مديونية (Pending)</option>
                        <option value="Paid" {{ old('status', $account->status) == 'Paid' ? 'selected' : '' }}>خالص (Paid)</option>
                        <option value="Canceled" {{ old('status', $account->status) == 'Canceled' ? 'selected' : '' }}>ملغي (Canceled)</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الملاحظات</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $account->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ التعديلات</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
