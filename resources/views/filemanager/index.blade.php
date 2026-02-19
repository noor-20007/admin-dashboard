@extends('layouts.admin')

@section('title', __('messages.file_manager'))

@section('content')
<style>
    .file-manager-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .toolbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
        color: white;
    }
    
    .breadcrumb-nav {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
    }
    
    .breadcrumb {
        margin: 0;
        background: transparent;
    }
    
    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        text-decoration: underline;
    }
    
    .file-list {
        padding: 20px;
        min-height: 400px;
    }
    
    .file-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .file-item:hover {
        background: #f8f9fa;
        border-color: #667eea;
        transform: translateX(-5px);
    }
    
    .file-item.selected {
        background: #e7f3ff;
        border-color: #667eea;
    }
    
    .file-icon {
        font-size: 32px;
        margin-left: 15px;
        width: 50px;
        text-align: center;
    }
    
    .file-icon.folder {
        color: #ffc107;
    }
    
    .file-icon.file {
        color: #6c757d;
    }
    
    .file-info {
        flex: 1;
    }
    
    .file-name {
        font-weight: 500;
        margin-bottom: 3px;
    }
    
    .file-meta {
        font-size: 12px;
        color: #6c757d;
    }
    
    .file-actions {
        display: flex;
        gap: 5px;
    }
    
    .btn-action {
        padding: 5px 10px;
        font-size: 14px;
    }
    
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 40px;
        text-align: center;
        margin: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .upload-area:hover {
        border-color: #667eea;
        background: #f8f9fa;
    }
    
    .upload-area.dragover {
        border-color: #667eea;
        background: #e7f3ff;
    }
    
    .progress-container {
        margin: 20px;
        display: none;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .context-menu {
        position: fixed;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        padding: 5px 0;
        z-index: 1000;
        display: none;
    }
    
    .context-menu-item {
        padding: 10px 20px;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .context-menu-item:hover {
        background: #f8f9fa;
    }
    
    .context-menu-item i {
        width: 20px;
        margin-left: 10px;
    }
</style>

<div class="file-manager-container">
    <!-- Toolbar -->
    <div class="toolbar">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-folder-open ms-2"></i>
                {{ __('messages.file_manager') }}
            </h4>
            <div class="btn-group">
                <button class="btn btn-light btn-sm" onclick="fileManager.createFolder()">
                    <i class="fas fa-folder-plus ms-1"></i> {{ __('messages.new_folder') }}
                </button>
                <button class="btn btn-light btn-sm" onclick="fileManager.uploadFile()">
                    <i class="fas fa-upload ms-1"></i> {{ __('messages.upload_file') }}
                </button>
                <button class="btn btn-light btn-sm" onclick="fileManager.refresh(); return false;">
                    <i class="fas fa-sync-alt ms-1"></i> {{ __('messages.refresh') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="breadcrumb-nav">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" id="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#" onclick="fileManager.navigateTo(''); return false;">
                        <i class="fas fa-home ms-1"></i> {{ __('messages.home') }}
                    </a>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Upload Area -->
    <div class="upload-area" id="uploadArea" onclick="document.getElementById('fileInput').click()">
        <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #667eea;"></i>
        <h5>{{ __('messages.drag_drop') }}</h5>
        <p class="text-muted mb-0">{{ __('messages.supported_files') }}</p>
        <input type="file" id="fileInput" multiple style="display: none;" onchange="fileManager.handleFileSelect(event)">
    </div>

    <!-- Progress Bar -->
    <div class="progress-container" id="progressContainer">
        <div class="progress" style="height: 25px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                 id="progressBar" 
                 role="progressbar" 
                 style="width: 0%">
                <span id="progressText">0%</span>
            </div>
        </div>
    </div>

    <!-- File List -->
    <div class="file-list" id="fileList">
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <h5>{{ __('messages.no_files') }}</h5>
            <p class="text-muted">{{ __('messages.start_uploading') }}</p>
        </div>
    </div>
</div>

<!-- Context Menu -->
<div class="context-menu" id="contextMenu">
    <div class="context-menu-item" onclick="fileManager.downloadSelected()">
        <i class="fas fa-download"></i> {{ __('messages.download') }}
    </div>
    <div class="context-menu-item" onclick="fileManager.renameSelected()">
        <i class="fas fa-edit"></i> {{ __('messages.rename') }}
    </div>
    <div class="context-menu-item" onclick="fileManager.copySelected()">
        <i class="fas fa-copy"></i> {{ __('messages.copy') }}
    </div>
    <div class="context-menu-item" onclick="fileManager.moveSelected()">
        <i class="fas fa-arrows-alt"></i> {{ __('messages.move') }}
    </div>
    <div class="context-menu-item text-danger" onclick="fileManager.deleteSelected()">
        <i class="fas fa-trash"></i> {{ __('messages.delete') }}
    </div>
</div>

<!-- Modals -->
<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.create_folder_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="folderName" placeholder="{{ __('messages.folder_name') }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="fileManager.confirmCreateFolder()">{{ __('messages.create') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Rename Modal -->
<div class="modal fade" id="renameModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.rename_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="newName" placeholder="{{ __('messages.new_name') }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="fileManager.confirmRename()">{{ __('messages.save') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- File Manager Script -->
<script src="{{ asset('js/filemanager.js') }}"></script>

<script>
    // Pass translations to JavaScript
    window.fileManagerTranslations = {
        deleteConfirm: "{{ __('messages.delete_confirm') }}",
        deleteMultipleConfirm: "{{ __('messages.delete_multiple_confirm') }}",
        deletedSuccess: "{{ __('messages.deleted_success') }}",
        uploadedSuccess: "{{ __('messages.uploaded_success') }}",
        createdSuccess: "{{ __('messages.created_success') }}",
        renamedSuccess: "{{ __('messages.renamed_success') }}",
        copiedSuccess: "{{ __('messages.copied_success') }}",
        movedSuccess: "{{ __('messages.moved_success') }}",
        error: "{{ __('messages.error') }}",
        selectItem: "{{ __('messages.select_item') }}",
        selectItems: "{{ __('messages.select_items') }}",
        selectOneItem: "{{ __('messages.select_one_item') }}",
        clipboardEmpty: "{{ __('messages.clipboard_empty') }}",
        enterName: "{{ __('messages.enter_name') }}",
        home: "{{ __('messages.home') }}",
        noFiles: "{{ __('messages.no_files') }}",
        startUploading: "{{ __('messages.start_uploading') }}"
    };

    // Initialize File Manager
    const fileManager = new FileManager({
        apiUrl: '/filemanager',
        token: '',
        translations: window.fileManagerTranslations
    });

    // Load initial directory
    fileManager.loadDirectory('');
</script>
@endpush
