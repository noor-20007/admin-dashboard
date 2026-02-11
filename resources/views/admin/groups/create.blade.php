@extends('layouts.admin')

@section('title', __('messages.add') . ' ' . __('messages.group') . ' ' . __('messages.new'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.group') . ' ' . __('messages.data') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.groups.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">{{ __('messages.group_name') }} <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.description') }}</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.supervisor') }}</label>
                <select name="supervisor_id" class="form-control">
                    <option value="">-- {{ __('messages.select') }} {{ __('messages.supervisor') }} --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('supervisor_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
