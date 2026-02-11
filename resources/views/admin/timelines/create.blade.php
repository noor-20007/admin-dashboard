@extends('layouts.admin')

@section('title', __('messages.add') . ' ' . __('messages.timeline'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.add') }} {{ __('messages.timeline') }} {{ __('messages.new') }}</h3>
    </div>
    <form action="{{ route('admin.timelines.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
             <div class="form-group mb-3">
                <label for="year">{{ __('messages.year') }}</label>
                <input type="text" name="year" class="form-control" id="year" required>
            </div>

             <div class="row">
                <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="title_en">{{ __('messages.title_en') }}</label>
                        <input type="text" name="title_en" class="form-control" id="title_en" required>
                    </div>
                </div>
                 <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="title_ar">{{ __('messages.title_ar') }}</label>
                        <input type="text" name="title_ar" class="form-control" id="title_ar" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="description_en">{{ __('messages.description_en') }}</label>
                        <textarea name="description_en" class="form-control" id="description_en" rows="3" required></textarea>
                    </div>
                </div>
                 <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="description_ar">{{ __('messages.description_ar') }}</label>
                        <textarea name="description_ar" class="form-control" id="description_ar" rows="3" required></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            <a href="{{ route('admin.timelines.index') }}" class="btn btn-default float-end">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
