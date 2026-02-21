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
        min-width: 180px;
    }
    
    .context-menu-item {
        padding: 10px 20px;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .context-menu-item:hover {
        background: #f8f9fa;
    }
    
    .context-menu-item i {
        width: 20px;
        margin-left: 10px;
    }
    
    .context-menu-item.text-danger:hover {
        background: #fee;
        color: #dc3545;
    }
    
    .file-actions .btn-more {
        padding: 2px 8px;
        font-size: 18px;
        line-height: 1;
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
    <div class="context-menu-item" onclick="fileManager.copySelected()">
        <i class="fas fa-copy"></i> {{ __('messages.copy') }}
    </div>
    <div class="context-menu-item" onclick="fileManager.moveSelected()">
        <i class="fas fa-arrows-alt"></i> {{ __('messages.move') }}
    </div>
    <div class="context-menu-item" onclick="fileManager.renameSelected()">
        <i class="fas fa-edit"></i> {{ __('messages.rename') }}
    </div>
    <div class="context-menu-item" onclick="fileManager.zipSelected()">
        <i class="fas fa-file-archive"></i> ضغط (Zip)
    </div>
    <div class="context-menu-item" onclick="fileManager.copyLink()">
        <i class="fas fa-link"></i> نسخ الرابط
    </div>
    <hr style="margin: 5px 0;">
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

<!-- Move Modal -->
<div class="modal fade" id="moveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">نقل إلى</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="folderList" style="max-height: 300px; overflow-y: auto;">
                    <div class="list-group">
                        <!-- Folders will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- File Manager Script - Inline -->
<script>
/**
 * File Manager JavaScript Client
 * Handles all frontend interactions with the File Manager API
 */
class FileManager {
    constructor(config) {
        this.apiUrl = config.apiUrl;
        this.token = config.token;
        this.currentPath = '';
        this.selectedItems = [];
        this.clipboard = null;
        
        this.initializeEventListeners();
    }

    /**
     * Initialize event listeners
     */
    initializeEventListeners() {
        // Drag and drop
        const uploadArea = document.getElementById('uploadArea');
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            this.handleFileDrop(e.dataTransfer.files);
        });

        // Hide context menu on click outside
        document.addEventListener('click', () => {
            document.getElementById('contextMenu').style.display = 'none';
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                if (e.key === 'a') {
                    e.preventDefault();
                    this.selectAll();
                }
            } else if (e.key === 'Delete') {
                this.deleteSelected();
            } else if (e.key === 'F2') {
                e.preventDefault();
                this.renameSelected();
            }
        });
    }

    /**
     * Make API request
     */
    async apiRequest(endpoint, options = {}) {
        const url = `${this.apiUrl}${endpoint}`;
        const headers = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            ...options.headers
        };

        if (!(options.body instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
        }

        try {
            const response = await fetch(url, {
                ...options,
                headers,
                credentials: 'same-origin'
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'حدث خطأ في الطلب');
            }

            return await response.json();
        } catch (error) {
            this.showError(error.message);
            throw error;
        }
    }

    /**
     * Load directory contents
     */
    async loadDirectory(path) {
        try {
            this.currentPath = path;
            const data = await this.apiRequest(`/list?path=${encodeURIComponent(path)}`);
            
            this.updateBreadcrumb(path);
            this.renderFileList(data.items);
        } catch (error) {
            console.error('Error loading directory:', error);
        }
    }

    /**
     * Update breadcrumb navigation
     */
    updateBreadcrumb(path) {
        const breadcrumb = document.getElementById('breadcrumb');
        let html = `
            <li class="breadcrumb-item">
                <a href="#" onclick="fileManager.navigateTo(''); return false;">
                    <i class="fas fa-home ms-1"></i> الرئيسية
                </a>
            </li>
        `;

        if (path) {
            const parts = path.split('/');
            let currentPath = '';
            
            parts.forEach((part, index) => {
                currentPath += (index > 0 ? '/' : '') + part;
                const isLast = index === parts.length - 1;
                
                if (isLast) {
                    html += `<li class="breadcrumb-item active">${part}</li>`;
                } else {
                    html += `
                        <li class="breadcrumb-item">
                            <a href="#" onclick="fileManager.navigateTo('${currentPath}'); return false;">
                                ${part}
                            </a>
                        </li>
                    `;
                }
            });
        }

        breadcrumb.innerHTML = html;
    }

    /**
     * Render file list
     */
    renderFileList(items) {
        const fileList = document.getElementById('fileList');
        
        if (!items || items.length === 0) {
            fileList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <h5>لا توجد ملفات</h5>
                    <p class="text-muted">ابدأ برفع الملفات أو إنشاء مجلدات جديدة</p>
                </div>
            `;
            return;
        }

        let html = '';
        items.forEach(item => {
            const icon = item.type === 'dir' 
                ? '<i class="fas fa-folder file-icon folder"></i>'
                : this.getFileIcon(item.name);
            
            const size = item.type === 'file' ? this.formatFileSize(item.size) : '-';
            const date = new Date(item.modified * 1000).toLocaleDateString('ar-EG');
            
            const itemPath = this.currentPath ? `${this.currentPath}/${item.name}` : item.name;

            html += `
                <div class="file-item" 
                     data-path="${itemPath}" 
                     data-type="${item.type}"
                     onclick="fileManager.handleItemClick(event, '${itemPath}', '${item.type}')"
                     ondblclick="fileManager.handleItemDoubleClick('${itemPath}', '${item.type}')">
                    ${icon}
                    <div class="file-info">
                        <div class="file-name">${item.name}</div>
                        <div class="file-meta">${size} • ${date}</div>
                    </div>
                    <div class="file-actions">
                        <button class="btn btn-sm btn-outline-secondary btn-action btn-more" 
                                onclick="event.stopPropagation(); fileManager.showItemMenu(event, '${itemPath}', '${item.type}')">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        fileList.innerHTML = html;
    }

    /**
     * Get file icon based on extension
     */
    getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        const iconMap = {
            'jpg': 'fa-file-image', 'jpeg': 'fa-file-image', 'png': 'fa-file-image',
            'pdf': 'fa-file-pdf', 'doc': 'fa-file-word', 'docx': 'fa-file-word',
            'zip': 'fa-file-archive', 'rar': 'fa-file-archive',
            'html': 'fa-file-code', 'css': 'fa-file-code', 'js': 'fa-file-code',
            'mp4': 'fa-file-video', 'mp3': 'fa-file-audio',
            'txt': 'fa-file-alt'
        };
        const iconClass = iconMap[ext] || 'fa-file';
        return `<i class="fas ${iconClass} file-icon file"></i>`;
    }

    /**
     * Format file size
     */
    formatFileSize(bytes) {
        if (bytes === 0) return '0 بايت';
        const k = 1024;
        const sizes = ['بايت', 'كيلوبايت', 'ميجابايت', 'جيجابايت'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    /**
     * Handle item click
     */
    handleItemClick(event, path, type) {
        const item = event.currentTarget;
        
        if (event.ctrlKey || event.metaKey) {
            item.classList.toggle('selected');
            if (item.classList.contains('selected')) {
                this.selectedItems.push({ path, type });
            } else {
                this.selectedItems = this.selectedItems.filter(i => i.path !== path);
            }
        } else {
            document.querySelectorAll('.file-item').forEach(el => el.classList.remove('selected'));
            item.classList.add('selected');
            this.selectedItems = [{ path, type }];
        }
    }

    /**
     * Handle item double click
     */
    handleItemDoubleClick(path, type) {
        if (type === 'dir') {
            this.navigateTo(path);
        } else {
            this.viewFile(path);
        }
    }

    /**
     * View file in new tab
     */
    viewFile(path) {
        const url = `${window.location.origin}/filemanager/view?path=${encodeURIComponent(path)}`;
        window.open(url, '_blank');
    }

    /**
     * Navigate to path
     */
    navigateTo(path) {
        this.selectedItems = [];
        this.loadDirectory(path);
    }

    /**
     * Create folder
     */
    createFolder() {
        const modal = new bootstrap.Modal(document.getElementById('createFolderModal'));
        document.getElementById('folderName').value = '';
        modal.show();
    }

    /**
     * Confirm create folder
     */
    async confirmCreateFolder() {
        const folderName = document.getElementById('folderName').value.trim();
        
        if (!folderName) {
            this.showError('الرجاء إدخال اسم المجلد');
            return;
        }

        try {
            const path = this.currentPath ? `${this.currentPath}/${folderName}` : folderName;
            await this.apiRequest('/create-directory', {
                method: 'POST',
                body: JSON.stringify({ path })
            });

            bootstrap.Modal.getInstance(document.getElementById('createFolderModal')).hide();
            this.showSuccess('تم إنشاء المجلد بنجاح');
            this.refresh();
        } catch (error) {
            console.error('Error creating folder:', error);
        }
    }

    /**
     * Upload file
     */
    uploadFile() {
        document.getElementById('fileInput').click();
    }

    /**
     * Handle file select
     */
    handleFileSelect(event) {
        const files = event.target.files;
        this.uploadFiles(files);
    }

    /**
     * Handle file drop
     */
    handleFileDrop(files) {
        this.uploadFiles(files);
    }

    /**
     * Upload files
     */
    async uploadFiles(files) {
        const progressContainer = document.getElementById('progressContainer');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');

        progressContainer.style.display = 'block';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            try {
                await this.uploadSmallFile(file);
                const progress = ((i + 1) / files.length) * 100;
                progressBar.style.width = progress + '%';
                progressText.textContent = Math.round(progress) + '%';
            } catch (error) {
                console.error('Error uploading file:', error);
            }
        }

        progressContainer.style.display = 'none';
        this.showSuccess('تم رفع الملفات بنجاح');
        this.refresh();
    }

    /**
     * Upload small file
     */
    async uploadSmallFile(file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('path', this.currentPath);

        await this.apiRequest('/upload', {
            method: 'POST',
            body: formData
        });
    }

    /**
     * Download file
     */
    downloadFile(path) {
        const url = `${window.location.origin}/filemanager/download?path=${encodeURIComponent(path)}`;
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = url;
        document.body.appendChild(iframe);
        setTimeout(() => document.body.removeChild(iframe), 5000);
    }

    /**
     * Download selected items
     */
    async downloadSelected() {
        if (this.selectedItems.length === 0) {
            this.showError('الرجاء تحديد ملف أو أكثر');
            return;
        }
        this.downloadFile(this.selectedItems[0].path);
    }

    /**
     * Delete item
     */
    async deleteItem(path) {
        if (!confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
            return;
        }

        try {
            const response = await this.apiRequest('/delete', {
                method: 'POST',
                body: JSON.stringify({ path })
            });

            if (response.success) {
                this.showSuccess('تم الحذف بنجاح');
                setTimeout(() => this.refresh(), 500);
            }
        } catch (error) {
            console.error('Error deleting item:', error);
            this.showError('فشل الحذف: ' + error.message);
        }
    }

    /**
     * Delete selected items
     */
    async deleteSelected() {
        if (this.selectedItems.length === 0) {
            this.showError('الرجاء تحديد عنصر أو أكثر');
            return;
        }

        if (!confirm(`هل أنت متأكد من حذف ${this.selectedItems.length} عنصر؟`)) {
            return;
        }

        for (const item of this.selectedItems) {
            await this.deleteItem(item.path);
        }
        this.selectedItems = [];
    }

    /**
     * Rename selected item
     */
    renameSelected() {
        if (this.selectedItems.length !== 1) {
            this.showError('الرجاء تحديد عنصر واحد فقط');
            return;
        }

        const item = this.selectedItems[0];
        const currentName = item.path.split('/').pop();
        
        const modal = new bootstrap.Modal(document.getElementById('renameModal'));
        document.getElementById('newName').value = currentName;
        modal.show();
    }

    /**
     * Confirm rename
     */
    async confirmRename() {
        const newName = document.getElementById('newName').value.trim();
        
        if (!newName) {
            this.showError('الرجاء إدخال الاسم الجديد');
            return;
        }

        const item = this.selectedItems[0];
        const pathParts = item.path.split('/');
        pathParts[pathParts.length - 1] = newName;
        const newPath = pathParts.join('/');

        try {
            await this.apiRequest('/move', {
                method: 'POST',
                body: JSON.stringify({ from: item.path, to: newPath })
            });

            bootstrap.Modal.getInstance(document.getElementById('renameModal')).hide();
            this.showSuccess('تم إعادة التسمية بنجاح');
            this.refresh();
        } catch (error) {
            console.error('Error renaming item:', error);
        }
    }

    /**
     * Copy selected items
     */
    async copySelected() {
        if (this.selectedItems.length === 0) {
            this.showError('الرجاء تحديد عنصر أو أكثر');
            return;
        }

        try {
            for (const item of this.selectedItems) {
                const filename = item.path.split('/').pop();
                const ext = filename.includes('.') ? '.' + filename.split('.').pop() : '';
                const nameWithoutExt = filename.replace(ext, '');
                
                // Find next available copy number
                let copyNumber = 1;
                let newName = `${nameWithoutExt} (${copyNumber})${ext}`;
                let newPath = this.currentPath ? `${this.currentPath}/${newName}` : newName;
                
                // Keep incrementing until we find a name that doesn't exist
                while (true) {
                    // Check if this file exists by trying to list current directory
                    const data = await this.apiRequest(`/list?path=${encodeURIComponent(this.currentPath)}`);
                    const exists = data.items.some(i => i.name === newName);
                    
                    if (!exists) {
                        break; // Found available name
                    }
                    
                    // Try next number
                    copyNumber++;
                    newName = `${nameWithoutExt} (${copyNumber})${ext}`;
                    newPath = this.currentPath ? `${this.currentPath}/${newName}` : newName;
                }

                console.log('Copying:', { from: item.path, to: newPath });

                const response = await this.apiRequest('/copy', {
                    method: 'POST',
                    body: JSON.stringify({
                        from: item.path,
                        to: newPath
                    })
                });

                console.log('Copy response:', response);
            }

            this.showSuccess('تم النسخ بنجاح');
            this.refresh();
        } catch (error) {
            console.error('Copy error:', error);
            this.showError('فشل النسخ: ' + error.message);
        }
    }

    /**
     * Move selected items
     */
    async moveSelected() {
        if (this.selectedItems.length === 0) {
            this.showError('الرجاء تحديد عنصر أو أكثر');
            return;
        }

        // Load all folders
        await this.loadFoldersForMove();
        
        const modal = new bootstrap.Modal(document.getElementById('moveModal'));
        modal.show();
    }

    /**
     * Load folders for move operation
     */
    async loadFoldersForMove(path = '') {
        try {
            const data = await this.apiRequest(`/list?path=${encodeURIComponent(path)}`);
            const folderList = document.querySelector('#folderList .list-group');
            
            let html = '';
            
            // Add root folder option
            if (path === '') {
                html += `
                    <a href="#" class="list-group-item list-group-item-action" 
                       onclick="fileManager.confirmMove(''); return false;">
                        <i class="fas fa-home ms-2"></i> المجلد الرئيسي
                    </a>
                `;
            } else {
                // Add back button
                const parentPath = path.split('/').slice(0, -1).join('/');
                html += `
                    <a href="#" class="list-group-item list-group-item-action" 
                       onclick="fileManager.loadFoldersForMove('${parentPath}'); return false;">
                        <i class="fas fa-arrow-right ms-2"></i> رجوع
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" 
                       onclick="fileManager.confirmMove('${path}'); return false;">
                        <i class="fas fa-check ms-2"></i> نقل إلى هنا
                    </a>
                `;
            }
            
            // Add folders
            const folders = data.items.filter(item => item.type === 'dir');
            folders.forEach(folder => {
                const folderPath = path ? `${path}/${folder.name}` : folder.name;
                html += `
                    <a href="#" class="list-group-item list-group-item-action" 
                       onclick="fileManager.loadFoldersForMove('${folderPath}'); return false;">
                        <i class="fas fa-folder ms-2"></i> ${folder.name}
                    </a>
                `;
            });
            
            if (folders.length === 0 && path !== '') {
                html += `
                    <div class="list-group-item text-muted">
                        لا توجد مجلدات فرعية
                    </div>
                `;
            }
            
            folderList.innerHTML = html;
        } catch (error) {
            console.error('Error loading folders:', error);
        }
    }

    /**
     * Confirm move to selected folder
     */
    async confirmMove(targetPath) {
        for (const item of this.selectedItems) {
            const filename = item.path.split('/').pop();
            const newPath = targetPath ? `${targetPath}/${filename}` : filename;

            try {
                await this.apiRequest('/move', {
                    method: 'POST',
                    body: JSON.stringify({
                        from: item.path,
                        to: newPath
                    })
                });
            } catch (error) {
                console.error('Error moving item:', error);
            }
        }

        bootstrap.Modal.getInstance(document.getElementById('moveModal')).hide();
        this.showSuccess('تم النقل بنجاح');
        this.refresh();
    }

    /**
     * Select all items
     */
    selectAll() {
        document.querySelectorAll('.file-item').forEach(item => item.classList.add('selected'));
        this.selectedItems = Array.from(document.querySelectorAll('.file-item')).map(item => ({
            path: item.dataset.path,
            type: item.dataset.type
        }));
    }

    /**
     * Refresh current directory
     */
    refresh() {
        this.selectedItems = [];
        this.loadDirectory(this.currentPath);
    }

    /**
     * Show success message
     */
    showSuccess(message) {
        alert(message);
    }

    /**
     * Show error message
     */
    showError(message) {
        alert(message);
    }

    /**
     * Show item menu (three dots)
     */
    showItemMenu(event, path, type) {
        event.preventDefault();
        event.stopPropagation();
        
        const menu = document.getElementById('contextMenu');
        menu.style.display = 'block';
        
        // Get button position
        const button = event.target.closest('button');
        const rect = button.getBoundingClientRect();
        
        // Calculate position
        const menuHeight = 280;
        const menuWidth = 180;
        
        let left = rect.left;
        let top = rect.bottom + 5; // 5px below button
        
        // Check if menu goes beyond bottom of viewport
        if (top + menuHeight > window.innerHeight) {
            top = rect.top - menuHeight - 5; // Show above button
        }
        
        // Check if menu goes beyond right edge
        if (left + menuWidth > window.innerWidth) {
            left = rect.right - menuWidth;
        }
        
        menu.style.left = left + 'px';
        menu.style.top = top + 'px';
        menu.style.position = 'fixed'; // Use fixed instead of absolute
        
        // Select this item
        document.querySelectorAll('.file-item').forEach(el => el.classList.remove('selected'));
        button.closest('.file-item').classList.add('selected');
        this.selectedItems = [{ path, type }];
    }

    /**
     * Zip selected items
     */
    async zipSelected() {
        if (this.selectedItems.length === 0) {
            this.showError('الرجاء تحديد ملف أو أكثر');
            return;
        }

        // Get default name from first selected item
        let defaultName = 'archive';
        if (this.selectedItems.length === 1) {
            const filename = this.selectedItems[0].path.split('/').pop();
            defaultName = filename.includes('.') ? filename.split('.').slice(0, -1).join('.') : filename;
        }

        let zipName = prompt('اسم ملف الـ ZIP:', defaultName);
        if (!zipName) return;

        // Clean the filename - remove any path traversal characters and .zip extension
        zipName = zipName.replace(/\.\./g, '').replace(/[\/\\]/g, '').replace(/\.zip$/i, '');
        
        // Add .zip extension
        zipName = zipName + '.zip';

        try {
            const paths = this.selectedItems.map(item => item.path);
            
            this.showSuccess('جاري إنشاء ملف ZIP...');
            
            // Create ZIP using batch download endpoint
            const response = await fetch(`${this.apiUrl}/download/batch`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    paths: paths,
                    archiveName: zipName
                })
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'فشل إنشاء ملف ZIP');
            }

            // Get blob
            const blob = await response.blob();
            
            // Upload the ZIP file back to current directory
            const formData = new FormData();
            formData.append('file', blob, zipName);
            formData.append('path', this.currentPath);

            await this.apiRequest('/upload', {
                method: 'POST',
                body: formData
            });
            
            this.showSuccess('تم إنشاء ملف ZIP بنجاح');
            this.refresh();
        } catch (error) {
            console.error('Error creating zip:', error);
            this.showError('فشل إنشاء ملف ZIP: ' + error.message);
        }
    }

    /**
     * Copy link to clipboard
     */
    async copyLink() {
        if (this.selectedItems.length !== 1) {
            this.showError('الرجاء تحديد ملف واحد فقط');
            return;
        }

        const item = this.selectedItems[0];
        if (item.type === 'dir') {
            this.showError('لا يمكن نسخ رابط مجلد');
            return;
        }

        const url = `${window.location.origin}/filemanager/view?path=${encodeURIComponent(item.path)}`;
        
        try {
            await navigator.clipboard.writeText(url);
            this.showSuccess('تم نسخ الرابط');
        } catch (error) {
            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = url;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            this.showSuccess('تم نسخ الرابط');
        }
    }
}

// Initialize File Manager after DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    try {
        window.fileManager = new FileManager({
            apiUrl: '/filemanager',
            token: ''
        });

        // Load initial directory
        fileManager.loadDirectory('').catch(error => {
            console.error('Failed to load directory:', error);
            alert('فشل تحميل الملفات. تحقق من Console للتفاصيل.');
        });
    } catch (error) {
        console.error('Failed to initialize FileManager:', error);
        alert('فشل تهيئة File Manager: ' + error.message);
    }
});
</script>
@endpush
