@extends('layouts.admin')

@section('title', __('messages.add') . ' ' . __('messages.client'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.data') }} {{ __('messages.client') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.phone') }}</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">{{ __('messages.gender') }}</label>
                    <select name="gender" class="form-control">
                        <option value="">-- {{ __('messages.select') }} --</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">{{ __('messages.age') }}</label>
                    <input type="number" name="age" class="form-control" value="{{ old('age') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">{{ __('messages.identity_num') }}</label>
                    <input type="text" name="identity_num" class="form-control" value="{{ old('identity_num') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">{{ __('messages.region') }}</label>
                    <input type="text" name="region" class="form-control" value="{{ old('region') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">{{ __('messages.type') }}</label>
                    <input type="text" name="type" class="form-control" value="{{ old('type') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">{{ __('messages.group') }}</label>
                    <select name="group_id" class="form-control">
                        <option value="">-- {{ __('messages.select') }} --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.image') }}</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
