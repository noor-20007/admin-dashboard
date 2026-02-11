@extends('layouts.admin')

@section('title', __('messages.services'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.manage') }} {{ __('messages.services') }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#create-form" aria-expanded="false" aria-controls="create-form">
                <i class="bi bi-plus"></i> {{ __('messages.add') }}
            </button>
        </div>
    </div>
    <div class="collapse" id="create-form">
        <div class="card-body border-bottom bg-light">
            <h5 class="text-primary mb-3">{{ __('messages.add') }} {{ __('messages.service') }}</h5>
            <form action="{{ route('admin.services.store') }}" method="POST">
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
                            <label>{{ __('messages.description_en') }}</label>
                            <textarea name="description_en" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>{{ __('messages.description_ar') }}</label>
                            <textarea name="description_ar" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label>{{ __('messages.icon_class') }}</label>
                    <input type="text" name="icon" class="form-control" placeholder="e.g. fas fa-cogs">
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
                    <th>{{ __('messages.icon') }}</th>
                    <th>{{ __('messages.title_en') }}</th>
                    <th>{{ __('messages.title_ar') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $service->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $service->icon }}</td>
                    <td>{{ $service->getTitleEnAttribute() }}</td>
                    <td>{{ $service->getTitleArAttribute() }}</td>
                </tr>
                <tr id="edit-form-{{ $service->id }}" class="collapse">
                    <td colspan="4">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">{{ __('messages.edit') }} {{ __('messages.service') }}</h5>
                            <form action="{{ route('admin.services.update', $service->id) }}" method="POST" id="update-form-{{ $service->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                     <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_en">{{ __('messages.title_en') }}</label>
                                                <input type="text" name="title_en" class="form-control" id="title_en" value="{{ $service->getTitleEnAttribute() }}" required>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="title_ar">{{ __('messages.title_ar') }}</label>
                                                <input type="text" name="title_ar" class="form-control" id="title_ar" value="{{ $service->getTitleArAttribute() }}" required>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="description_en">{{ __('messages.description_en') }}</label>
                                                <textarea name="description_en" class="form-control" id="description_en" rows="3">{{ $service->getDescriptionEnAttribute() }}</textarea>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                             <div class="form-group mb-3">
                                                <label for="description_ar">{{ __('messages.description_ar') }}</label>
                                                <textarea name="description_ar" class="form-control" id="description_ar" rows="3">{{ $service->getDescriptionArAttribute() }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="icon">{{ __('messages.icon_class') }}</label>
                                        <input type="text" name="icon" class="form-control" id="icon" value="{{ $service->icon }}">
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $service->id }}" class="btn btn-primary">{{ __('messages.update') }}</button>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display: inline-block;">
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
