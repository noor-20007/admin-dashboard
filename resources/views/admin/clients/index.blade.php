@extends('layouts.admin')

@section('title', 'إدارة العملاء')

@section('content')
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>إجمالي العملاء</p>
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
                <p>عملاء جدد (هذا الشهر)</p>
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
                <p>الذكور</p>
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
                <p>الإناث</p>
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
            <option value="">اختر عميلاً...</option>
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
            <option value="">جميع المجموعات</option>
            @foreach($groups as $group)
                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="gender" class="form-select" id="genderFilter">
            <option value="">الكل (الجنس)</option>
            <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>ذكر</option>
            <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>أنثى</option>
        </select>
    </div>
    <div class="col-md-3">
        <div class="btn-group w-100" role="group">
            <button type="submit" class="btn btn-primary" id="searchBtn">
                <i class="fas fa-search"></i> بحث
            </button>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary" title="إلغاء الفلتر">
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
        <h3 class="card-title">قائمة العملاء</h3>
        <div class="card-tools">
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> إضافة  
            </a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped text-nowrap">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>الاسم</th>
                    <th>الجنس</th>
                    <th>العمر</th>
                    <th>رقم الهوية</th>
                    <th>الهاتف</th>
                    <th>المنطقة</th>
                    <th>النوع</th>
                    <th>المجموعة</th>
                    <th>تاريخ الإضافة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr data-bs-toggle="collapse" data-bs-target="#edit-form-{{ $client->id }}" style="cursor: pointer;">
                    <td>
                        @if($client->image)
                            <img src="{{ asset($client->image) }}" alt="{{ $client->name }}" width="50" class="img-circle">
                        @else
                            <i class="fas fa-user-circle fa-2x text-muted"></i>
                        @endif
                    </td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->gender }}</td>
                    <td>{{ $client->age }}</td>
                    <td>{{ $client->identity_num }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->region }}</td>
                    <td>{{ $client->type }}</td>
                    <td>
                        @if($client->group)
                            <span class="badge bg-primary">{{ $client->group->name }}</span>
                        @else
                            <span class="badge bg-secondary">غير محدد</span>
                        @endif
                    </td>
                    <td>{{ $client->created_at->format('d-m-Y') }}</td>
                </tr>
                <tr id="edit-form-{{ $client->id }}" class="collapse">
                    <td colspan="10">
                        <div class="p-3 bg-light border">
                            <h5 class="text-primary mb-3">تعديل: {{ $client->name }}</h5>
                            <form action="{{ route('admin.clients.update', $client->id) }}" method="POST" enctype="multipart/form-data" id="update-form-{{ $client->id }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الاسم <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $client->name) }}">
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الهاتف</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone) }}">
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">الجنس</label>
                                        <select name="gender" class="form-control">
                                            <option value="">-- اختر --</option>
                                            <option value="Male" {{ old('gender', $client->gender) == 'Male' ? 'selected' : '' }}>ذكر</option>
                                            <option value="Female" {{ old('gender', $client->gender) == 'Female' ? 'selected' : '' }}>أنثى</option>
                                        </select>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">العمر</label>
                                        <input type="number" name="age" class="form-control" value="{{ old('age', $client->age) }}">
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">رقم الهوية</label>
                                        <input type="text" name="identity_num" class="form-control" value="{{ old('identity_num', $client->identity_num) }}">
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">المنطقة</label>
                                        <input type="text" name="region" class="form-control" value="{{ old('region', $client->region) }}">
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">النوع (Type)</label>
                                        <input type="text" name="type" class="form-control" value="{{ old('type', $client->type) }}">
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">المجموعة</label>
                                        <select name="group_id" class="form-control">
                                            <option value="">-- اختر --</option>
                                            @foreach($groups as $group)
                                                 <option value="{{ $group->id }}" {{ old('group_id', $client->group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                    
                                <div class="mb-3">
                                    <label class="form-label">الصورة</label>
                                    @if($client->image)
                                        <div class="mb-2"><img src="{{ asset($client->image) }}" width="100" class="img-thumbnail"></div>
                                    @endif
                                    <input type="file" name="image" class="form-control">
                                </div>
                            
                            </form>
                            <div class="d-flex justify-content-between">
                                <button type="submit" form="update-form-{{ $client->id }}" class="btn btn-primary"><i class="fas fa-save"></i> حفظ التعديلات</button>
                                
                                <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> حذف</button>
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
