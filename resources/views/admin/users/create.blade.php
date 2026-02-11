@extends('layouts.admin')

@section('title', __('messages.add') . ' ' . __('messages.user'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.add') . ' ' . __('messages.user') . ' ' . __('messages.new') }}</h3>
    </div>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group mb-3">
                <label for="name">{{ __('messages.name') }}</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">{{ __('messages.email') }}</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">{{ __('messages.password') }}</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
             <div class="form-group mb-3">
                <label for="password_confirmation">{{ __('messages.password_confirmation') }}</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-default float-end">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
