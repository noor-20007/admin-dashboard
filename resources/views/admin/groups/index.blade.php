@extends('layouts.admin')

@section('title', __('messages.groups'))

@section('content')
<!-- Simple Filter Bar -->
<div class="row mb-3">
    <div class="col-md-4">
        <select name="search" class="form-select" id="groupSearch">
            <option value="">{{ __('messages.select_group') }}</option>
            @if(isset($groups))
                @foreach($groups as $group)
                    <option value="{{ $group->name }}" {{ request('search') == $group->name ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-4">
        <select name="supervisor_id" class="form-select" id="supervisorFilter">
            <option value="">{{ __('messages.all_supervisors') }}</option>
            @php
                $supervisorsList = collect($groups)->whereNotNull('supervisor')->pluck('supervisor')->unique('id');
            @endphp
            @if($supervisorsList->count() > 0)
                @foreach($supervisorsList as $supervisor)
                    <option value="{{ $supervisor->id }}" {{ request('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                        {{ $supervisor->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-4">
        <div class="btn-group w-100" role="group">
            <button type="submit" class="btn btn-primary" id="searchBtn">
                <i class="fas fa-search"></i> {{ __('messages.search') }}
            </button>
            <a href="{{ route('admin.groups.index') }}" class="btn btn-outline-secondary" title="{{ __('messages.reset') }}">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all filter elements
    const groupSearch = document.getElementById('groupSearch');
    const supervisorFilter = document.getElementById('supervisorFilter');
    const searchBtn = document.getElementById('searchBtn');
    
    // Function to build URL and redirect
    function applyFilters() {
        const params = new URLSearchParams();
        
        // Add all filter values
        if (groupSearch.value) params.append('search', groupSearch.value);
        if (supervisorFilter.value) params.append('supervisor_id', supervisorFilter.value);
        
        // Redirect to new URL
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.location.href = newUrl;
    }
    
    // Function to apply group filter only
    function applyGroupFilter() {
        const params = new URLSearchParams();
        
        // Add current supervisor filter if exists
        if (supervisorFilter.value) params.append('supervisor_id', supervisorFilter.value);
        // Add new group filter
        if (groupSearch.value) params.append('search', groupSearch.value);
        
        // Redirect to new URL
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.location.href = newUrl;
    }
    
    // Function to apply supervisor filter only
    function applySupervisorFilter() {
        const params = new URLSearchParams();
        
        // Add current group filter if exists
        if (groupSearch.value) params.append('search', groupSearch.value);
        // Add new supervisor filter
        if (supervisorFilter.value) params.append('supervisor_id', supervisorFilter.value);
        
        // Redirect to new URL
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.location.href = newUrl;
    }
    
    // Add event listeners for immediate filtering
    groupSearch.addEventListener('change', applyGroupFilter);
    supervisorFilter.addEventListener('change', applySupervisorFilter);
    
    // Keep button functionality for manual search
    searchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        applyFilters();
    });
});
</script>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.groups') }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.groups.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('messages.add') }}
            </a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped text-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.group_name') }}</th>
                    <th>{{ __('messages.description') }}</th>
                    <th>{{ __('messages.supervisor') }}</th>
                    <th>{{ __('messages.created_at') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $group->id }}" style="cursor: pointer;">
                    <td>{{ $group->id }}</td>
                    <td>{{ $group->name }}</td>
                    <td>{{ Str::limit($group->description, 50) }}</td>
                    <td>
                        @if($group->supervisor)
                            <span class="badge bg-success">{{ $group->supervisor->name }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('messages.not_specified') }}</span>
                        @endif
                    </td>
                    <td>{{ $group->created_at->format('Y-m-d') }}</td>
                </tr>
                <tr id="edit-form-{{ $group->id }}" class="collapse">
                    <td colspan="5">
                         <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">{{ __('messages.edit') }}: {{ $group->name }}</h5>
                            <form action="{{ route('admin.groups.update', $group->id) }}" method="POST" id="update-form-{{ $group->id }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.group_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $group->name) }}">
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                    
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.description') }}</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $group->description) }}</textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                    
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.supervisor') }}</label>
                                    <select name="supervisor_id" class="form-control">
                                        <option value="">-- {{ __('messages.select') }} {{ __('messages.supervisor') }} --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('supervisor_id', $group->supervisor_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('supervisor_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </form>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $group->id }}" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
                                
                                <form action="{{ route('admin.groups.destroy', $group->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('messages.confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> {{ __('messages.delete') }}</button>
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
