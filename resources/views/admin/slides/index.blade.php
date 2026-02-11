@extends('layouts.admin')

@section('title', __('messages.slides'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.manage') }} {{ __('messages.slides') }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#create-form" aria-expanded="false" aria-controls="create-form">
                <i class="bi bi-plus"></i> {{ __('messages.add') }}
            </button>
        </div>
    </div>
    <div class="collapse" id="create-form">
        <div class="card-body border-bottom bg-light">
            <h5 class="text-primary mb-3">{{ __('messages.add') }} {{ __('messages.slide') }}</h5>
            <form action="{{ route('admin.slides.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>{{ __('messages.title_ar') }}</label>
                            <input type="text" name="title_ar" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>{{ __('messages.title_en') }}</label>
                            <input type="text" name="title_en" class="form-control">
                        </div>
                    </div>
                </div>
    
                <div class="form-group mb-3">
                    <label>{{ __('messages.background_image') }} <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control" required>
                </div>
    
                <div class="form-group mb-3">
                    <label>{{ __('messages.foreground_image') }}</label>
                    <input type="file" name="foreground_image" class="form-control">
                </div>
    
                <div class="form-group mb-3">
                    <label>{{ __('messages.sort_order') }}</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
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
                    <th style="width: 20%">{{ __('messages.background_image') }}</th>
                    <th style="width: 20%">{{ __('messages.foreground_image') }}</th>
                    <th>{{ __('messages.title') }}</th>
                    <th style="width: 10%">{{ __('messages.sort_order') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($slides as $slide)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $slide->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ asset($slide->image) }}" alt="Background" class="img-fluid" style="max-height: 50px;">
                    </td>
                    <td>
                        @if($slide->foreground_image)
                        <img src="{{ asset($slide->foreground_image) }}" alt="Foreground" class="img-fluid" style="max-height: 50px;">
                        @else
                        -
                        @endif
                    </td>
                    <td>{{ $slide->title ?? '-' }}</td>
                    <td>{{ $slide->sort_order }}</td>
                </tr>
                <tr id="edit-form-{{ $slide->id }}" class="collapse">
                    <td colspan="5">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">{{ __('messages.edit') }} {{ __('messages.slide') }}</h5>
                            <form action="{{ route('admin.slides.update', $slide->id) }}" method="POST" enctype="multipart/form-data" id="update-form-{{ $slide->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="title_ar">{{ __('messages.title_ar') }}</label>
                                                <input type="text" name="title_ar" class="form-control" id="title_ar" value="{{ $slide->getTitleArAttribute() }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="title_en">{{ __('messages.title_en') }}</label>
                                                <input type="text" name="title_en" class="form-control" id="title_en" value="{{ $slide->getTitleEnAttribute() }}">
                                            </div>
                                        </div>
                                    </div>
                        
                                    <hr>
                        
                                    <div class="form-group mb-3">
                                        <label for="image">{{ __('messages.background_image') }}</label>
                                        @if($slide->image)
                                            <div class="mb-2">
                                                <img src="{{ asset($slide->image) }}" width="150" class="img-thumbnail">
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <input type="file" name="image" class="form-control" id="image">
                                        </div>
                                        <small class="text-muted">{{ __('messages.leave_empty_to_keep_image') }}</small>
                                    </div>
                        
                                    <div class="form-group mb-3">
                                        <label for="foreground_image">{{ __('messages.foreground_image') }}</label>
                                        @if($slide->foreground_image)
                                            <div class="mb-2">
                                                <img src="{{ asset($slide->foreground_image) }}" width="150" class="img-thumbnail">
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <input type="file" name="foreground_image" class="form-control" id="foreground_image">
                                        </div>
                                        <small class="text-muted">{{ __('messages.leave_empty_to_keep_image') }}</small>
                                    </div>
                        
                                    <div class="form-group mb-3">
                                        <label for="sort_order">{{ __('messages.sort_order') }}</label>
                                        <input type="number" name="sort_order" class="form-control" id="sort_order" value="{{ $slide->sort_order }}">
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $slide->id }}" class="btn btn-primary">{{ __('messages.update') }}</button>
                                <form action="{{ route('admin.slides.destroy', $slide->id) }}" method="POST" style="display: inline-block;">
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
