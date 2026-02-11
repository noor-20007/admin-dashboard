@extends('layouts.admin')

@section('title', __('messages.timelines'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.manage') }} {{ __('messages.timelines') }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.timelines.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> {{ __('messages.add') }}
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped projects text-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th>{{ __('messages.year') }}</th>
                    <th>{{ __('messages.title_en') }}</th>
                    <th>{{ __('messages.title_ar') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timelines as $timeline)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $timeline->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $timeline->year }}</td>
                    <td>{{ $timeline->title_en }}</td>
                    <td>{{ $timeline->title_ar }}</td>
                </tr>
                <tr id="edit-form-{{ $timeline->id }}" class="collapse">
                    <td colspan="4">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">{{ __('messages.edit') }} {{ __('messages.timeline') }}</h5>
                            <form action="{{ route('admin.timelines.update', $timeline->id) }}" method="POST" enctype="multipart/form-data" id="update-form-{{ $timeline->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                     <div class="form-group mb-3">
                                        <label for="year">{{ __('messages.year') }}</label>
                                        <input type="text" name="year" class="form-control" id="year" value="{{ $timeline->year }}" required>
                                    </div>
                        
                                     <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_en">{{ __('messages.title_en') }}</label>
                                                <input type="text" name="title_en" class="form-control" id="title_en" value="{{ $timeline->title_en }}" required>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_ar">{{ __('messages.title_ar') }}</label>
                                                <input type="text" name="title_ar" class="form-control" id="title_ar" value="{{ $timeline->title_ar }}" required>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="description_en">{{ __('messages.description_en') }}</label>
                                                <textarea name="description_en" class="form-control" id="description_en" rows="3" required>{{ $timeline->description_en }}</textarea>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="description_ar">{{ __('messages.description_ar') }}</label>
                                                <textarea name="description_ar" class="form-control" id="description_ar" rows="3" required>{{ $timeline->description_ar }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $timeline->id }}" class="btn btn-primary">{{ __('messages.update') }}</button>
                                <form action="{{ route('admin.timelines.destroy', $timeline->id) }}" method="POST" style="display: inline-block;">
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
