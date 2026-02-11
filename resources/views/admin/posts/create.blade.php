@extends('layouts.admin')

@section('title', __('messages.add') . ' ' . __('messages.post'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.add') }} {{ __('messages.post') }} {{ __('messages.new') }}</h3>
    </div>
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
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
                        <label for="content_en">{{ __('messages.content_en') }}</label>
                        <textarea name="content_en" class="form-control" id="content_en" rows="5"></textarea>
                    </div>
                </div>
                 <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="content_ar">{{ __('messages.content_ar') }}</label>
                        <textarea name="content_ar" class="form-control" id="content_ar" rows="5"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label for="published_at">{{ __('messages.published_date') }}</label>
                <input type="date" name="published_at" class="form-control" id="published_at">
            </div>

            <div class="form-group mb-3">
                <label for="image">{{ __('messages.image') }}</label>
                <div class="input-group">
                    <input type="file" name="image" class="form-control" id="image" required>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-default float-end">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
