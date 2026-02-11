@extends('layouts.admin')

@section('title', __('messages.clients'))

@section('content')
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>{{ __('messages.total') }} {{ __('messages.clients') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['new_month'] }}</h3>
                <p>{{ __('messages.new_month') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['males'] }}</h3>
                <p>{{ __('messages.males') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-male"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['females'] }}</h3>
                <p>{{ __('messages.females') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-female"></i>
            </div>
        </div>
    </div>
</div>

<!-- Simple Filter Bar -->
<div class="row mb-3">
    <div class="col-md-3">
        <select name="search" class="form-select" id="clientSearch">
            <option value="">{{ __('messages.select_client') }}</option>
            @if(isset($clients))
                @foreach($clients as $client)
                    <option value="{{ $client->name }}" {{ request('search') == $client->name ? 'selected' : '' }}>
                        {{ $client->name }}
                        @if($client->phone)
                            ({{ $client->phone }})
                        @endif
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-3">
        <select name="group_id" class="form-select" id="groupFilter">
            <option value="">{{ __('messages.all_groups') }}</option>
            @foreach($groups as $group)
                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="gender" class="form-select" id="genderFilter">
            <option value="">{{ __('messages.all_genders') }}</option>
            <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
            <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
        </select>
    </div>
    <div class="col-md-3">
        <div class="btn-group w-100" role="group">
            <button type="submit" class="btn btn-primary" id="searchBtn">
                <i class="fas fa-search"></i> {{ __('messages.search') }}
            </button>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary" title="{{ __('messages.reset') }}">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all filter elements
    const clientSearch = document.getElementById('clientSearch');
    const groupFilter = document.getElementById('groupFilter');
    const genderFilter = document.getElementById('genderFilter');
    const searchBtn = document.getElementById('searchBtn');
    
    // Function to build URL and redirect
    function applyFilters() {
        const params = new URLSearchParams();
        
        // Add all filter values
        if (clientSearch.value) params.append('search', clientSearch.value);
        if (groupFilter.value) params.append('group_id', groupFilter.value);
        if (genderFilter.value) params.append('gender', genderFilter.value);
        
        // Redirect to new URL
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.location.href = newUrl;
    }
    
    // Add event listeners for immediate filtering
    clientSearch.addEventListener('change', applyFilters);
    groupFilter.addEventListener('change', applyFilters);
    genderFilter.addEventListener('change', applyFilters);
    
    // Keep button functionality for manual search
    searchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        applyFilters();
    });
});
</script>


<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.clients') }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#create-form" aria-expanded="false" aria-controls="create-form">
                <i class="fas fa-plus"></i> {{ __('messages.add') }}
            </button>
        </div>
    </div>
    <div class="collapse" id="create-form">
        <div class="card-body border-bottom bg-light">
            <h5 class="text-primary mb-3">{{ __('messages.add') }} {{ __('messages.client') }}</h5>
            <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('messages.phone') }}</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('messages.gender') }}</label>
                        <select name="gender" class="form-control">
                            <option value="">-- {{ __('messages.select') }} --</option>
                            <option value="Male">{{ __('messages.male') }}</option>
                            <option value="Female">{{ __('messages.female') }}</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('messages.age') }}</label>
                        <input type="number" name="age" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('messages.identity_num') }}</label>
                        <input type="text" name="identity_num" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('messages.region') }}</label>
                        <input type="text" name="region" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('messages.type') }}</label>
                        <input type="text" name="type" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('messages.group') }}</label>
                        <select name="group_id" class="form-control">
                            <option value="">-- {{ __('messages.select') }} --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.image') }}</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
            </form>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped text-nowrap">
            <thead>
                <tr>
                    <th>{{ __('messages.image') }}</th>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.gender') }}</th>
                    <th>{{ __('messages.age') }}</th>
                    <th>{{ __('messages.identity_num') }}</th>
                    <th>{{ __('messages.phone') }}</th>
                    <th>{{ __('messages.region') }}</th>
                    <th>{{ __('messages.type') }}</th>
                    <th>{{ __('messages.group') }}</th>
                    <th>{{ __('messages.created_at') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td>
                        @if($client->image)
                            <img src="{{ asset($client->image) }}" alt="{{ $client->name }}" width="50" class="img-circle">
                        @else
                            <i class="fas fa-user-circle fa-2x text-muted"></i>
                        @endif
                    </td>
                    <td data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $client->id }}" style="cursor: pointer;">{{ $client->name }}</td>
                    <td data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $client->id }}" style="cursor: pointer;">{{ $client->gender }}</td>
                    <td data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $client->id }}" style="cursor: pointer;">{{ $client->age }}</td>
                    <td data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $client->id }}" style="cursor: pointer;">{{ $client->identity_num }}</td>
                    <td>
                        @if($client->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->phone) }}" 
                               target="_blank" 
                               class="text-success text-decoration-none"
                               title="{{ __('messages.open_in_whatsapp') }}"
                               onclick="event.stopPropagation()">
                                <i class="fab fa-whatsapp"></i> {{ $client->phone }}
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $client->id }}" style="cursor: pointer;">{{ $client->region }}</td>
                    <td data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $client->id }}" style="cursor: pointer;">{{ $client->type }}</td>
                    <td data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $client->id }}" style="cursor: pointer;">
                        @if($client->group)
                            <span class="badge bg-primary">{{ $client->group->name }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('messages.not_specified') }}</span>
                        @endif
                    </td>
                    <td data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $client->id }}" style="cursor: pointer;">{{ $client->created_at->format('d-m-Y') }}</td>
                </tr>
                <tr id="edit-form-{{ $client->id }}" class="collapse">
                    <td colspan="10">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">{{ __('messages.edit') }}: {{ $client->name }}</h5>
                            <form action="{{ route('admin.clients.update', $client->id) }}" method="POST" enctype="multipart/form-data" id="update-form-{{ $client->id }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $client->name) }}">
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('messages.phone') }}</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone) }}">
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.gender') }}</label>
                                        <select name="gender" class="form-control">
                                            <option value="">-- {{ __('messages.select') }} --</option>
                                            <option value="Male" {{ old('gender', $client->gender) == 'Male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                            <option value="Female" {{ old('gender', $client->gender) == 'Female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                        </select>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.age') }}</label>
                                        <input type="number" name="age" class="form-control" value="{{ old('age', $client->age) }}">
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.identity_num') }}</label>
                                        <input type="text" name="identity_num" class="form-control" value="{{ old('identity_num', $client->identity_num) }}">
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.region') }}</label>
                                        <input type="text" name="region" class="form-control" value="{{ old('region', $client->region) }}">
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.type') }}</label>
                                        <input type="text" name="type" class="form-control" value="{{ old('type', $client->type) }}">
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.group') }}</label>
                                        <select name="group_id" class="form-control">
                                            <option value="">-- {{ __('messages.select') }} --</option>
                                            @foreach($groups as $group)
                                                 <option value="{{ $group->id }}" {{ old('group_id', $client->group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                    
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.image') }}</label>
                                    @if($client->image)
                                        <div class="mb-2"><img src="{{ asset($client->image) }}" width="100" class="img-thumbnail"></div>
                                    @endif
                                    <input type="file" name="image" class="form-control">
                                </div>
                            
                            </form>
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $client->id }}" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
                                
                                <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('messages.confirm') }}')">
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
