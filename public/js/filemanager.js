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
                } else if (e.key === 'c') {
                    e.preventDefault();
                    this.copySelected();
                } else if (e.key === 'v') {
                    e.preventDefault();
                    this.pasteClipboard();
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

        // Add Authorization header only if token exists
        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        if (!(options.body instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
        }

        try {
            const response = await fetch(url, {
                ...options,
                headers,
                credentials: 'same-origin' // Important for session-based auth
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
            
            // Build relative path from current path + item name
            const itemPath = this.currentPath ? `${this.currentPath}/${item.name}` : item.name;

            html += `
                <div class="file-item" 
                     data-path="${itemPath}" 
                     data-type="${item.type}"
                     onclick="fileManager.handleItemClick(event, '${itemPath}', '${item.type}')"
                     oncontextmenu="fileManager.showContextMenu(event, '${itemPath}'); return false;"
                     ondblclick="fileManager.handleItemDoubleClick('${itemPath}', '${item.type}')">
                    ${icon}
                    <div class="file-info">
                        <div class="file-name">${item.name}</div>
                        <div class="file-meta">${size} • ${date}</div>
                    </div>
                    <div class="file-actions">
                        ${item.type === 'file' ? `
                            <button class="btn btn-sm btn-outline-primary btn-action" 
                                    onclick="event.stopPropagation(); fileManager.downloadFile('${itemPath}')">
                                <i class="fas fa-download"></i>
                            </button>
                        ` : ''}
                        <button class="btn btn-sm btn-outline-danger btn-action" 
                                onclick="event.stopPropagation(); fileManager.deleteItem('${itemPath}')">
                            <i class="fas fa-trash"></i>
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
            // Images
            'jpg': 'fa-file-image',
            'jpeg': 'fa-file-image',
            'png': 'fa-file-image',
            'gif': 'fa-file-image',
            'svg': 'fa-file-image',
            // Documents
            'pdf': 'fa-file-pdf',
            'doc': 'fa-file-word',
            'docx': 'fa-file-word',
            'xls': 'fa-file-excel',
            'xlsx': 'fa-file-excel',
            'ppt': 'fa-file-powerpoint',
            'pptx': 'fa-file-powerpoint',
            // Archives
            'zip': 'fa-file-archive',
            'rar': 'fa-file-archive',
            '7z': 'fa-file-archive',
            // Code
            'html': 'fa-file-code',
            'css': 'fa-file-code',
            'js': 'fa-file-code',
            'php': 'fa-file-code',
            'json': 'fa-file-code',
            // Video
            'mp4': 'fa-file-video',
            'avi': 'fa-file-video',
            'mov': 'fa-file-video',
            // Audio
            'mp3': 'fa-file-audio',
            'wav': 'fa-file-audio',
            // Text
            'txt': 'fa-file-alt',
            'md': 'fa-file-alt',
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
            // Multi-select
            item.classList.toggle('selected');
            if (item.classList.contains('selected')) {
                this.selectedItems.push({ path, type });
            } else {
                this.selectedItems = this.selectedItems.filter(i => i.path !== path);
            }
        } else {
            // Single select
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
            // Open file in new tab for viewing
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
     * Show context menu
     */
    showContextMenu(event, path) {
        event.preventDefault();
        const menu = document.getElementById('contextMenu');
        menu.style.display = 'block';
        menu.style.left = event.pageX + 'px';
        menu.style.top = event.pageY + 'px';
        
        // Select item if not already selected
        const item = event.currentTarget;
        if (!item.classList.contains('selected')) {
            document.querySelectorAll('.file-item').forEach(el => el.classList.remove('selected'));
            item.classList.add('selected');
            this.selectedItems = [{ path, type: item.dataset.type }];
        }
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
                // Check file size (100MB limit)
                if (file.size > 100 * 1024 * 1024) {
                    // Use chunked upload for large files
                    await this.uploadLargeFile(file, (progress) => {
                        progressBar.style.width = progress + '%';
                        progressText.textContent = Math.round(progress) + '%';
                    });
                } else {
                    // Simple upload for small files
                    await this.uploadSmallFile(file);
                    const progress = ((i + 1) / files.length) * 100;
                    progressBar.style.width = progress + '%';
                    progressText.textContent = Math.round(progress) + '%';
                }
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
     * Upload large file with chunking
     */
    async uploadLargeFile(file, onProgress) {
        // Initialize upload
        const initData = await this.apiRequest('/upload/init', {
            method: 'POST',
            body: JSON.stringify({
                filename: file.name,
                filesize: file.size,
                path: this.currentPath
            })
        });

        const { uploadId, chunkSize } = initData;
        const totalChunks = Math.ceil(file.size / chunkSize);

        // Upload chunks
        for (let i = 0; i < totalChunks; i++) {
            const start = i * chunkSize;
            const end = Math.min(start + chunkSize, file.size);
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('chunk', chunk);
            formData.append('uploadId', uploadId);
            formData.append('chunkIndex', i);
            formData.append('totalChunks', totalChunks);
            formData.append('filename', file.name);
            formData.append('path', this.currentPath);

            await this.apiRequest('/upload/chunk', {
                method: 'POST',
                body: formData
            });

            onProgress(((i + 1) / totalChunks) * 100);
        }
    }

    /**
     * Download file
     */
    downloadFile(path) {
        // Use full URL with proper encoding
        const url = `${window.location.origin}/filemanager/download?path=${encodeURIComponent(path)}`;
        
        // Create iframe to trigger download without leaving page
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = url;
        document.body.appendChild(iframe);
        
        // Remove iframe after download starts
        setTimeout(() => {
            document.body.removeChild(iframe);
        }, 5000);
    }

    /**
     * Download selected items
     */
    async downloadSelected() {
        if (this.selectedItems.length === 0) {
            this.showError('الرجاء تحديد ملف أو أكثر');
            return;
        }

        if (this.selectedItems.length === 1) {
            this.downloadFile(this.selectedItems[0].path);
        } else {
            // Batch download as ZIP
            const paths = this.selectedItems.map(item => item.path);
            
            try {
                const response = await fetch(`${this.apiUrl}/download/batch`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${this.token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        paths: paths,
                        archiveName: 'download.zip'
                    })
                });

                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'download.zip';
                a.click();
                window.URL.revokeObjectURL(url);
            } catch (error) {
                this.showError('حدث خطأ أثناء التحميل');
            }
        }
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
                method: 'POST', // Changed from DELETE to POST
                body: JSON.stringify({ path })
            });

            if (response.success) {
                this.showSuccess('تم الحذف بنجاح');
                // Force refresh after short delay
                setTimeout(() => {
                    this.refresh();
                }, 500);
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
                body: JSON.stringify({
                    from: item.path,
                    to: newPath
                })
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
    copySelected() {
        if (this.selectedItems.length === 0) {
            this.showError('الرجاء تحديد عنصر أو أكثر');
            return;
        }

        this.clipboard = {
            action: 'copy',
            items: [...this.selectedItems]
        };

        this.showSuccess('تم النسخ إلى الحافظة');
    }

    /**
     * Move selected items
     */
    moveSelected() {
        if (this.selectedItems.length === 0) {
            this.showError('الرجاء تحديد عنصر أو أكثر');
            return;
        }

        this.clipboard = {
            action: 'move',
            items: [...this.selectedItems]
        };

        this.showSuccess('تم القص إلى الحافظة');
    }

    /**
     * Paste clipboard
     */
    async pasteClipboard() {
        if (!this.clipboard) {
            this.showError('الحافظة فارغة');
            return;
        }

        for (const item of this.clipboard.items) {
            const filename = item.path.split('/').pop();
            const newPath = this.currentPath ? `${this.currentPath}/${filename}` : filename;

            try {
                if (this.clipboard.action === 'copy') {
                    await this.apiRequest('/copy', {
                        method: 'POST',
                        body: JSON.stringify({
                            from: item.path,
                            to: newPath
                        })
                    });
                } else {
                    await this.apiRequest('/move', {
                        method: 'POST',
                        body: JSON.stringify({
                            from: item.path,
                            to: newPath
                        })
                    });
                }
            } catch (error) {
                console.error('Error pasting item:', error);
            }
        }

        if (this.clipboard.action === 'move') {
            this.clipboard = null;
        }

        this.showSuccess('تم اللصق بنجاح');
        this.refresh();
    }

    /**
     * Select all items
     */
    selectAll() {
        document.querySelectorAll('.file-item').forEach(item => {
            item.classList.add('selected');
        });

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
        alert(message); // يمكن استبدالها بـ toast notification
    }

    /**
     * Show error message
     */
    showError(message) {
        alert(message); // يمكن استبدالها بـ toast notification
    }
}
