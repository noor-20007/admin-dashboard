@extends('layouts.admin')

@section('title', __('messages.posts'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.manage') }} {{ __('messages.posts') }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#create-form" aria-expanded="false" aria-controls="create-form">
                <i class="bi bi-plus"></i> {{ __('messages.add') }}
            </button>
        </div>
    </div>
    <div class="collapse" id="create-form">
        <div class="card-body border-bottom bg-light">
            <h5 class="text-primary mb-3">{{ __('messages.add') }} {{ __('messages.post') }}</h5>
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>{{ __('messages.title_en') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title_en" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>{{ __('messages.title_ar') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title_ar" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>{{ __('messages.content_en') }}</label>
                            <textarea name="content_en" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>{{ __('messages.content_ar') }}</label>
                            <textarea name="content_ar" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label>{{ __('messages.published_date') }}</label>
                    <input type="date" name="published_at" class="form-control" value="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group mb-3">
                    <label>{{ __('messages.image') }}</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
            </form>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped projects text-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th style="width: 20%">{{ __('messages.image') }}</th>
                    <th>{{ __('messages.title') }}</th>
                    <th>{{ __('messages.published_date') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $post->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($post->image)
                        <img src="{{ asset($post->image) }}" alt="Post" class="img-fluid" style="max-height: 50px;">
                        @endif
                    </td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->published_at ? $post->published_at->format('Y-m-d') : __('messages.draft') }}</td>
                </tr>
                <tr id="edit-form-{{ $post->id }}" class="collapse">
                    <td colspan="4">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">{{ __('messages.edit') }} {{ __('messages.post') }}</h5>
                            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="update-form-{{ $post->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                     <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_en">{{ __('messages.title_en') }}</label>
                                                <input type="text" name="title_en" class="form-control" id="title_en" value="{{ $post->getTitleEnAttribute() }}" required>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_ar">{{ __('messages.title_ar') }}</label>
                                                <input type="text" name="title_ar" class="form-control" id="title_ar" value="{{ $post->getTitleArAttribute() }}" required>
                                            </div>
                                        </div>
                                     </div>
                        
                                     <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="content_en">{{ __('messages.content_en') }}</label>
                                                <textarea name="content_en" class="form-control" id="content_en" rows="5">{{ $post->getContentEnAttribute() }}</textarea>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="content_ar">{{ __('messages.content_ar') }}</label>
                                                <textarea name="content_ar" class="form-control" id="content_ar" rows="5">{{ $post->getContentArAttribute() }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="published_at">{{ __('messages.published_date') }}</label>
                                        <input type="date" name="published_at" class="form-control" id="published_at" value="{{ $post->published_at ? $post->published_at->format('Y-m-d') : '' }}">
                                    </div>
                        
                                    <div class="form-group mb-3">
                                        <label for="image">{{ __('messages.image') }}</label>
                                         @if($post->image)
                                            <div class="mb-2">
                                                <img src="{{ asset($post->image) }}" width="150" class="img-thumbnail">
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <input type="file" name="image" class="form-control" id="image">
                                        </div>
                                         <small class="text-muted">{{ __('messages.leave_empty_to_keep_image') }}</small>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $post->id }}" class="btn btn-primary">{{ __('messages.update') }}</button>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('messages.confirm') }}')">
                                        <i class="bi bi-trash"></i> {{ __('messages.delete') }}
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
