@extends('layouts.admin')

@section('title', __('messages.portfolios'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.manage') }} {{ __('messages.portfolios') }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.portfolios.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> {{ __('messages.add') }}
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped projects text-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th style="width: 20%">{{ __('messages.image') }}</th>
                    <th>{{ __('messages.title') }}</th>
                    <th>{{ __('messages.category') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($portfolios as $portfolio)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $portfolio->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($portfolio->image)
                        <img src="{{ asset($portfolio->image) }}" alt="Portfolio" class="img-fluid" style="max-height: 50px;">
                        @endif
                    </td>
                    <td>{{ $portfolio->title }}</td>
                    <td>{{ $portfolio->category ? $portfolio->category->name : '-' }}</td>
                </tr>
                <tr id="edit-form-{{ $portfolio->id }}" class="collapse">
                    <td colspan="4">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">{{ __('messages.edit') }} {{ __('messages.portfolio') }}</h5>
                            <form action="{{ route('admin.portfolios.update', $portfolio->id) }}" method="POST" enctype="multipart/form-data" id="update-form-{{ $portfolio->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                     <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_en">{{ __('messages.title_en') }}</label>
                                                <input type="text" name="title_en" class="form-control" id="title_en" value="{{ $portfolio->getTitleEnAttribute() }}" required>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_ar">{{ __('messages.title_ar') }}</label>
                                                <input type="text" name="title_ar" class="form-control" id="title_ar" value="{{ $portfolio->getTitleArAttribute() }}" required>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="description_en">{{ __('messages.description_en') }}</label>
                                                <textarea name="description_en" class="form-control" id="description_en" rows="3">{{ $portfolio->getDescriptionEnAttribute() }}</textarea>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="description_ar">{{ __('messages.description_ar') }}</label>
                                                <textarea name="description_ar" class="form-control" id="description_ar" rows="3">{{ $portfolio->getDescriptionArAttribute() }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="category_id">{{ __('messages.category') }}</label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            <option value="">{{ __('messages.select_category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $portfolio->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                        
                                    <div class="form-group mb-3">
                                        <label for="image">{{ __('messages.image') }}</label>
                                         @if($portfolio->image)
                                            <div class="mb-2">
                                                <img src="{{ asset($portfolio->image) }}" width="150" class="img-thumbnail">
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
                                <button type="submit" form="update-form-{{ $portfolio->id }}" class="btn btn-primary">{{ __('messages.update') }}</button>
                                <form action="{{ route('admin.portfolios.destroy', $portfolio->id) }}" method="POST" style="display: inline-block;">
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
