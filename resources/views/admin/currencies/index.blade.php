@extends('layouts.admin')

@section('title', __('messages.currencies'))

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.manage') }} {{ __('messages.currencies') }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#create-form" aria-expanded="false" aria-controls="create-form">
                <i class="bi bi-plus"></i> {{ __('messages.add') }}
            </button>
        </div>
    </div>
    <div class="card-body">
        
        <!-- Add New Currency Form -->
        <div class="collapse" id="create-form">
            <div class="mb-4 p-3 bg-light border rounded">
                <h5 class="mb-3 text-primary"><i class="fas fa-plus-circle"></i> {{ __('messages.add') }}</h5>
                <form action="{{ route('admin.currencies.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label>{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="{{ __('messages.example_dollar') }}" required>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label>{{ __('messages.symbol') }} <span class="text-danger">*</span></label>
                            <input type="text" name="symbol" class="form-control" placeholder="{{ __('messages.example_symbol') }}" required>
                        </div>
                         <div class="col-md-2 mb-2">
                            <label>{{ __('messages.code') }}</label>
                            <input type="text" name="code" class="form-control" placeholder="{{ __('messages.example_usd') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                             <label>&nbsp;</label>
                             <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="defaultNew">
                                <label class="form-check-label" for="defaultNew">
                                    {{ __('messages.is_default') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-success btn-block w-100">{{ __('messages.add') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <hr>

        <!-- Currencies List -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.symbol') }}</th>
                        <th>{{ __('messages.code') }}</th>
                        <th>{{ __('messages.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($currencies as $currency)
                    <tr data-bs-toggle="collapse" data-bs-target="#editRow{{ $currency->id }}" style="cursor: pointer;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $currency->name }}</td>
                        <td>{{ $currency->symbol }}</td>
                        <td>{{ $currency->code }}</td>
                        <td>
                            @if($currency->is_default)
                                <span class="badge bg-success">{{ __('messages.default') }}</span>
                            @else
                                <span class="badge bg-secondary">--</span>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Inline Edit Row -->
                    <tr class="collapse bg-light" id="editRow{{ $currency->id }}">
                        <td colspan="5" class="p-3">
                            <form action="{{ route('admin.currencies.update', $currency->id) }}" method="POST" id="update-form-{{ $currency->id }}">
                                @csrf
                                @method('PUT')
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <label class="small text-muted">{{ __('messages.name') }}</label>
                                        <input type="text" name="name" class="form-control form-control-sm" value="{{ $currency->name }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small text-muted">{{ __('messages.symbol') }}</label>
                                        <input type="text" name="symbol" class="form-control form-control-sm" value="{{ $currency->symbol }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small text-muted">{{ __('messages.code') }}</label>
                                        <input type="text" name="code" class="form-control form-control-sm" value="{{ $currency->code }}">
                                    </div>
                                    <div class="col-md-2">
                                         <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" name="is_default" value="1" id="defaultEdit{{ $currency->id }}" {{ $currency->is_default ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="defaultEdit{{ $currency->id }}">
                                                {{ __('messages.default') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-4 text-end">
                                        <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.save') }}</button>
                                        
                                         @if(!$currency->is_default)
                                        <button type="submit" form="delete-form-{{ $currency->id }}" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.confirm') }}')">{{ __('messages.delete') }}</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                            @if(!$currency->is_default)
                            <form action="{{ route('admin.currencies.destroy', $currency->id) }}" method="POST" id="delete-form-{{ $currency->id }}" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
