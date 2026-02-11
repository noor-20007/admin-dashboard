@extends('layouts.admin')

@section('title', __('messages.add') . ' ' . __('messages.category'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.add') }} {{ __('messages.category') }} {{ __('messages.new') }}</h3>
    </div>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="card-body">
             <div class="row">
                <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="name_en">{{ __('messages.name_en') }}</label>
                        <input type="text" name="name_en" class="form-control" id="name_en" required>
                    </div>
                </div>
                 <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="name_ar">{{ __('messages.name_ar') }}</label>
                        <input type="text" name="name_ar" class="form-control" id="name_ar" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-default float-end">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
