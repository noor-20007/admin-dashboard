@extends('layouts.admin')

@section('title', __('messages.skills'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.manage') }} {{ __('messages.skills') }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#create-form" aria-expanded="false" aria-controls="create-form">
                <i class="bi bi-plus"></i> {{ __('messages.add') }}
            </button>
        </div>
    </div>
    <div class="collapse" id="create-form">
        <div class="card-body border-bottom bg-light">
            <h5 class="text-primary mb-3">{{ __('messages.add') }} {{ __('messages.skill') }}</h5>
            <form action="{{ route('admin.skills.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label>{{ __('messages.skill_name_ar') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name_ar" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>{{ __('messages.skill_name_en') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name_en" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>{{ __('messages.percent') }} (0-100) <span class="text-danger">*</span></label>
                    <input type="number" name="percentage" class="form-control" min="0" max="100" required>
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
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.percent') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($skills as $skill)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $skill->id }}" style="cursor: pointer;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $skill->name }}</td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-success" style="width: {{ $skill->percent }}%"></div>
                        </div>
                        <small>{{ $skill->percent }}%</small>
                    </td>
                </tr>
                <tr id="edit-form-{{ $skill->id }}" class="collapse">
                    <td colspan="3">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">{{ __('messages.edit') }} {{ __('messages.skill') }}</h5>
                            <form action="{{ route('admin.skills.update', $skill->id) }}" method="POST" id="update-form-{{ $skill->id }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="name_ar">{{ __('messages.skill_name_ar') }}</label>
                                        <input type="text" name="name_ar" class="form-control" id="name_ar" value="{{ $skill->getNameArAttribute() }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="name_en">{{ __('messages.skill_name_en') }}</label>
                                        <input type="text" name="name_en" class="form-control" id="name_en" value="{{ $skill->getNameEnAttribute() }}" required>
                                    </div>
                        
                                    <div class="form-group mb-3">
                                        <label for="percentage">{{ __('messages.percent') }} (0-100)</label>
                                        <input type="number" name="percentage" class="form-control" id="percentage" min="0" max="100" value="{{ $skill->percent }}" required>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $skill->id }}" class="btn btn-primary">{{ __('messages.update') }}</button>
                                <form action="{{ route('admin.skills.destroy', $skill->id) }}" method="POST" style="display: inline-block;">
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
