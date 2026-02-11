@extends('layouts.admin')

@section('title', __('messages.add') . ' ' . __('messages.slide'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.add') }} {{ __('messages.slide') }} {{ __('messages.new') }}</h3>
    </div>
    <form action="{{ route('admin.slides.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">

             <!-- Tabs -->
             <ul class="nav nav-tabs mb-4" id="langTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="ar-tab" data-bs-toggle="tab" data-bs-target="#ar" type="button" role="tab" aria-controls="ar" aria-selected="true">{{ __('messages.arabic') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="en-tab" data-bs-toggle="tab" data-bs-target="#en" type="button" role="tab" aria-controls="en" aria-selected="false">{{ __('messages.english') }}</button>
                </li>
            </ul>

            <div class="tab-content mb-4" id="langTabContent">
                <!-- Arabic Tab -->
                <div class="tab-pane fade show active" id="ar" role="tabpanel" aria-labelledby="ar-tab">
                    <div class="form-group mb-3">
                        <label for="title_ar">{{ __('messages.title_ar') }}</label>
                        <input type="text" name="title_ar" class="form-control" id="title_ar">
                    </div>
                </div>
                 <!-- English Tab -->
                <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab" dir="ltr">
                    <div class="form-group mb-3">
                        <label for="title_en">{{ __('messages.title_en') }}</label>
                        <input type="text" name="title_en" class="form-control" id="title_en">
                    </div>
                </div>
            </div>

            <hr>

            <div class="form-group mb-3">
                <label for="image">{{ __('messages.background_image') }}</label>
                <div class="input-group">
                    <input type="file" name="image" class="form-control" id="image" required>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="foreground_image">{{ __('messages.foreground_image') }} ({{ __('messages.not_specified') }})</label>
                <div class="input-group">
                    <input type="file" name="foreground_image" class="form-control" id="foreground_image">
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="sort_order">{{ __('messages.sort_order') }}</label>
                <input type="number" name="sort_order" class="form-control" id="sort_order" value="0">
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            <a href="{{ route('admin.slides.index') }}" class="btn btn-default float-end">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
