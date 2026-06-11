/**
 * TechExpert Reusable Media Selector Modal Component
 * 
 * Allows selecting existing images from the media library or uploading new ones on the fly.
 * Supports single and multiple selection modes.
 */
class MediaSelector {
    constructor(options = {}) {
        this.options = Object.assign({
            urlRoot: '',
            csrfToken: '',
            folder: 'products', // Default upload/view folder ('products', 'brands', 'reviews')
            multiple: false, // Enable multi-selection mode
            onSelect: null // Callback function: function(url) or function(urls)
        }, options);

        this.files = [];
        this.filteredFiles = [];
        this.selectedFile = null; // Single selection or current active selection
        this.selectedFiles = []; // Holds multiple selected files if multiple: true
        this.categoryFilter = this.options.folder || 'all';
        this.searchQuery = '';
        this.modalEl = null;

        this.createModalMarkup();
        this.setupEvents();
    }

    createModalMarkup() {
        // Unique ID for the modal wrapper
        const modalId = 'media-selector-modal-' + Math.random().toString(36).substr(2, 9);
        
        const markup = `
            <div id="${modalId}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 sm:p-6 md:p-10">
                <!-- Backdrop -->
                <div class="modal-backdrop absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>
                
                <!-- Modal Box -->
                <div class="relative w-full max-w-5xl h-[80vh] bg-white rounded-3xl shadow-2xl border border-outline-variant overflow-hidden flex flex-col z-10 transform scale-95 opacity-0 transition-all duration-300">
                    <!-- Header -->
                    <div class="h-16 border-b border-outline-variant flex items-center justify-between px-6 shrink-0">
                        <div>
                            <h3 class="text-[16px] font-bold text-primary">Chọn ảnh từ thư viện</h3>
                            <p class="text-[11px] text-on-surface-variant font-medium">Chọn ảnh có sẵn hoặc tải ảnh mới lên</p>
                        </div>
                        <button class="close-btn w-8 h-8 rounded-lg flex items-center justify-center hover:bg-surface-container-low transition-all text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>
                    
                    <!-- Body -->
                    <div class="flex-1 min-h-0 flex flex-col md:flex-row">
                        <!-- Left Panel: Grid & Toolbar -->
                        <div class="flex-1 min-w-0 flex flex-col bg-surface-container-lowest">
                            <!-- Toolbar -->
                            <div class="p-4 border-b border-outline-variant flex flex-col sm:flex-row gap-3 items-center justify-between shrink-0 bg-white">
                                <!-- Tabs -->
                                <div class="flex bg-surface-container-low p-1 rounded-lg border border-outline-variant w-full sm:w-auto overflow-x-auto select-none">
                                    <button type="button" data-cat="all" class="tab-btn px-3 py-1.5 rounded-md text-[12px] font-bold transition-all bg-white text-primary shadow-sm">Tất cả</button>
                                    <button type="button" data-cat="products" class="tab-btn px-3 py-1.5 rounded-md text-[12px] font-bold transition-all text-on-surface-variant hover:text-primary">Sản phẩm</button>
                                    <button type="button" data-cat="brands" class="tab-btn px-3 py-1.5 rounded-md text-[12px] font-bold transition-all text-on-surface-variant hover:text-primary">Thương hiệu</button>
                                    <button type="button" data-cat="reviews" class="tab-btn px-3 py-1.5 rounded-md text-[12px] font-bold transition-all text-on-surface-variant hover:text-primary">Đánh giá</button>
                                </div>
                                
                                <!-- Search & Quick Upload -->
                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <div class="relative flex-1 sm:w-48">
                                        <span class="material-symbols-outlined absolute left-2.5 top-2 text-[18px] text-on-surface-variant">search</span>
                                        <input type="text" class="search-input w-full pl-8 pr-3 py-1.5 rounded-lg bg-[#F8F9FB] border border-outline-variant text-[12px] text-primary placeholder-on-surface-variant focus:outline-none" placeholder="Tìm tên ảnh...">
                                    </div>
                                    
                                    <button type="button" class="upload-trigger-btn px-3 py-1.5 bg-primary text-white rounded-lg text-[12px] font-bold flex items-center gap-1.5 hover:bg-neutral-800 transition-all">
                                        <span class="material-symbols-outlined text-[16px]">upload</span>
                                        Tải lên
                                    </button>
                                    <input type="file" class="modal-file-input hidden" accept="image/*">
                                </div>
                            </div>
                            
                            <!-- Grid Area -->
                            <div class="flex-1 overflow-y-auto p-4 relative">
                                <!-- Uploading Loading Overlay -->
                                <div class="upload-progress-overlay hidden absolute inset-0 bg-white/80 z-20 flex flex-col items-center justify-center p-6 text-center">
                                    <span class="material-symbols-outlined animate-spin text-3xl text-primary mb-2">sync</span>
                                    <p class="text-[14px] font-bold text-primary">Đang tải tệp lên...</p>
                                    <div class="w-48 bg-surface-container-low rounded-full h-1.5 mt-2">
                                        <div class="progress-bar-fill bg-primary h-1.5 rounded-full transition-all duration-200" style="width: 0%"></div>
                                    </div>
                                </div>
 
                                <!-- Loading Indicator -->
                                <div class="loading-indicator absolute inset-0 flex items-center justify-center bg-white/70 z-10">
                                    <span class="material-symbols-outlined animate-spin text-3xl text-primary">sync</span>
                                </div>
                                
                                <!-- Image Grid -->
                                <div class="image-grid grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                                    <!-- Dynamic elements -->
                                </div>
                                
                                <!-- Empty state -->
                                <div class="grid-empty-state hidden flex flex-col items-center justify-center text-center py-20">
                                    <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">photo_library</span>
                                    <p class="text-[14px] font-bold text-primary">Không tìm thấy ảnh</p>
                                    <p class="text-[12px] text-on-surface-variant">Tải ảnh mới hoặc đổi bộ lọc tìm kiếm</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Panel: Image Details & Select Button -->
                        <div class="w-full md:w-64 border-t md:border-t-0 md:border-l border-outline-variant p-4 flex flex-col bg-white shrink-0">
                            <!-- Empty Preview State -->
                            <div class="preview-empty flex-1 flex flex-col items-center justify-center text-center py-8">
                                <span class="material-symbols-outlined text-3xl text-on-surface-variant mb-2">info</span>
                                <p class="text-[13px] font-bold text-primary">Chọn một ảnh</p>
                                <p class="text-[11px] text-on-surface-variant">để xem chi tiết</p>
                            </div>
                            
                            <!-- Detailed Info (Hidden initially) -->
                            <div class="preview-details hidden flex-1 flex flex-col gap-4 min-h-0 overflow-y-auto">
                                <div class="aspect-square bg-surface-container-low rounded-xl overflow-hidden border border-outline-variant flex items-center justify-center shrink-0">
                                    <img class="detail-img w-full h-full object-contain" src="" alt="">
                                </div>
                                
                                <div class="space-y-2 min-w-0">
                                    <div class="min-w-0">
                                        <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider block">Tên tệp</span>
                                        <span class="detail-name text-[12px] font-bold text-primary break-all"></span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider block">Thư mục</span>
                                            <span class="detail-folder text-[12px] font-medium text-primary capitalize"></span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider block">Dung lượng</span>
                                            <span class="detail-size text-[12px] font-medium text-primary"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Confirm Button -->
                            <div class="pt-4 border-t border-outline-variant mt-auto">
                                <button type="button" class="select-confirm-btn w-full py-2.5 bg-primary text-white rounded-xl text-[13px] font-bold hover:bg-neutral-800 transition-all disabled:bg-neutral-200 disabled:text-neutral-400 disabled:cursor-not-allowed" disabled>
                                    Chọn ảnh này
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Inject into body
        const container = document.createElement('div');
        container.innerHTML = markup;
        document.body.appendChild(container);
        
        this.modalWrapper = document.getElementById(modalId);
        this.modalBox = this.modalWrapper.querySelector('.relative.w-full');
        this.backdrop = this.modalWrapper.querySelector('.modal-backdrop');
    }

    setupEvents() {
        // Close events
        const closeBtn = this.modalWrapper.querySelector('.close-btn');
        closeBtn.addEventListener('click', () => this.close());
        this.backdrop.addEventListener('click', () => this.close());

        // Tabs
        const tabBtns = this.modalWrapper.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                tabBtns.forEach(b => b.className = 'tab-btn px-3 py-1.5 rounded-md text-[12px] font-bold transition-all text-on-surface-variant hover:text-primary');
                btn.className = 'tab-btn px-3 py-1.5 rounded-md text-[12px] font-bold transition-all bg-white text-primary shadow-sm';
                
                this.categoryFilter = btn.dataset.cat;
                this.filterFiles();
            });
        });

        // Search
        const searchInput = this.modalWrapper.querySelector('.search-input');
        searchInput.addEventListener('input', (e) => {
            this.searchQuery = e.target.value.trim().toLowerCase();
            this.filterFiles();
        });

        // Upload Trigger
        const uploadTrigger = this.modalWrapper.querySelector('.upload-trigger-btn');
        const fileInput = this.modalWrapper.querySelector('.modal-file-input');
        uploadTrigger.addEventListener('click', () => fileInput.click());
        
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                this.uploadFile(fileInput.files[0]);
            }
        });

        // Confirm selection
        const confirmBtn = this.modalWrapper.querySelector('.select-confirm-btn');
        confirmBtn.addEventListener('click', () => {
            if (this.options.onSelect) {
                if (this.options.multiple) {
                    if (this.selectedFiles.length > 0) {
                        this.options.onSelect(this.selectedFiles.map(f => f.url));
                        this.close();
                    }
                } else {
                    if (this.selectedFile) {
                        this.options.onSelect(this.selectedFile.url);
                        this.close();
                    }
                }
            }
        });
    }

    open() {
        // Reset state
        this.selectedFile = null;
        this.selectedFiles = [];
        this.updateDetailsPanel();
        
        // Show modal wrapper
        this.modalWrapper.classList.remove('hidden');
        
        // Trigger opening animation
        setTimeout(() => {
            this.backdrop.classList.add('opacity-100');
            this.modalBox.classList.remove('scale-95', 'opacity-0');
            this.modalBox.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // Fetch files
        this.fetchFiles();
    }

    close() {
        this.modalBox.classList.remove('scale-100', 'opacity-100');
        this.modalBox.classList.add('scale-95', 'opacity-0');
        this.backdrop.classList.remove('opacity-100');
        
        setTimeout(() => {
            this.modalWrapper.classList.add('hidden');
        }, 300);
    }

    fetchFiles() {
        const loading = this.modalWrapper.querySelector('.loading-indicator');
        loading.classList.remove('hidden');

        fetch(`${this.options.urlRoot}/admin/media?json=1`)
            .then(res => res.json())
            .then(data => {
                loading.classList.add('hidden');
                if (data.success) {
                    this.files = data.files;
                    this.filterFiles();
                }
            })
            .catch(err => {
                loading.classList.add('hidden');
                console.error('Error fetching media files:', err);
            });
    }

    filterFiles() {
        this.filteredFiles = this.files.filter(file => {
            const matchesCat = this.categoryFilter === 'all' || file.folder === this.categoryFilter;
            const matchesSearch = !this.searchQuery || file.name.toLowerCase().includes(this.searchQuery);
            return matchesCat && matchesSearch;
        });

        this.renderGrid();
    }

    renderGrid() {
        const grid = this.modalWrapper.querySelector('.image-grid');
        const emptyState = this.modalWrapper.querySelector('.grid-empty-state');
        grid.innerHTML = '';

        if (this.filteredFiles.length === 0) {
            emptyState.classList.remove('hidden');
            grid.classList.add('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        grid.classList.remove('hidden');

        this.filteredFiles.forEach(file => {
            const isSelected = this.options.multiple
                ? this.selectedFiles.some(f => f.url === file.url)
                : (this.selectedFile && this.selectedFile.url === file.url);

            const card = document.createElement('div');
            card.className = `aspect-square bg-surface-container-low border border-outline-variant rounded-xl overflow-hidden cursor-pointer hover:border-primary transition-all relative flex flex-col group ${isSelected ? 'ring-2 ring-primary border-transparent' : ''}`;
            
            let badgeIndicator = '';
            if (this.options.multiple && isSelected) {
                const index = this.selectedFiles.findIndex(f => f.url === file.url) + 1;
                badgeIndicator = `
                    <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-primary text-white text-[10px] font-black flex items-center justify-center shadow-md z-10 border border-white">
                        ${index}
                    </div>
                `;
            }

            card.innerHTML = `
                ${badgeIndicator}
                <img src="${file.url}" class="w-full h-full object-cover group-hover:scale-105 duration-200" onerror="this.src='https://ui-avatars.com/api/?name=Error&background=f44336&color=fff';">
                <div class="absolute bottom-0 inset-x-0 bg-black/60 p-1.5 text-white truncate text-[10px] text-center font-bold">
                    ${file.name}
                </div>
            `;

            card.addEventListener('click', () => {
                if (this.options.multiple) {
                    const existingIndex = this.selectedFiles.findIndex(f => f.url === file.url);
                    if (existingIndex > -1) {
                        this.selectedFiles.splice(existingIndex, 1);
                    } else {
                        this.selectedFiles.push(file);
                    }
                    this.selectedFile = file; // Still set selectedFile for details preview
                    this.renderGrid(); // Redraw grid to update number badges
                } else {
                    this.modalWrapper.querySelectorAll('.image-grid > div').forEach(c => c.classList.remove('ring-2', 'ring-primary', 'border-transparent'));
                    card.classList.add('ring-2', 'ring-primary', 'border-transparent');
                    this.selectedFile = file;
                }
                this.updateDetailsPanel();
            });

            grid.appendChild(card);
        });
    }

    updateDetailsPanel() {
        const emptyPanel = this.modalWrapper.querySelector('.preview-empty');
        const detailPanel = this.modalWrapper.querySelector('.preview-details');
        const confirmBtn = this.modalWrapper.querySelector('.select-confirm-btn');

        const hasSelection = this.options.multiple ? (this.selectedFiles.length > 0) : (this.selectedFile !== null);

        if (!hasSelection) {
            emptyPanel.classList.remove('hidden');
            detailPanel.classList.add('hidden');
            confirmBtn.disabled = true;
            confirmBtn.textContent = this.options.multiple ? 'Chọn ảnh' : 'Chọn ảnh này';
            return;
        }

        emptyPanel.classList.add('hidden');
        detailPanel.classList.remove('hidden');
        confirmBtn.disabled = false;

        if (this.options.multiple) {
            confirmBtn.textContent = `Chọn ${this.selectedFiles.length} ảnh`;
        } else {
            confirmBtn.textContent = 'Chọn ảnh này';
        }

        const previewFile = this.selectedFile || (this.options.multiple ? this.selectedFiles[this.selectedFiles.length - 1] : null);
        if (previewFile) {
            detailPanel.querySelector('.detail-img').src = previewFile.url;
            detailPanel.querySelector('.detail-name').textContent = previewFile.name;
            detailPanel.querySelector('.detail-folder').textContent = previewFile.folder;
            detailPanel.querySelector('.detail-size').textContent = previewFile.formatted_size;
        }
    }

    uploadFile(file) {
        const overlay = this.modalWrapper.querySelector('.upload-progress-overlay');
        const barFill = overlay.querySelector('.progress-bar-fill');
        
        let uploadFolder = this.categoryFilter;
        if (uploadFolder === 'all') {
            uploadFolder = this.options.folder || 'products';
        }

        const formData = new FormData();
        formData.append('folder', uploadFolder);
        formData.append('csrf_token', this.options.csrfToken);
        formData.append('files[]', file);

        overlay.classList.remove('hidden');
        barFill.style.width = '0%';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', `${this.options.urlRoot}/admin/uploadMedia`, true);

        xhr.upload.onprogress = (e) => {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                barFill.style.width = percent + '%';
            }
        };

        xhr.onload = () => {
            overlay.classList.add('hidden');
            if (xhr.status === 200) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.success && res.files && res.files.length > 0) {
                        const uploaded = res.files[0];
                        fetch(`${this.options.urlRoot}/admin/media?json=1`)
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    this.files = data.files;
                                    const match = this.files.find(f => f.name === uploaded.name && f.folder === uploadFolder);
                                    if (match) {
                                        if (this.options.multiple) {
                                            this.selectedFiles.push(match);
                                        }
                                        this.selectedFile = match;
                                        this.categoryFilter = uploadFolder;
                                        
                                        const tabs = this.modalWrapper.querySelectorAll('.tab-btn');
                                        tabs.forEach(b => {
                                            if (b.dataset.cat === uploadFolder) {
                                                b.className = 'tab-btn px-3 py-1.5 rounded-md text-[12px] font-bold transition-all bg-white text-primary shadow-sm';
                                            } else {
                                                b.className = 'tab-btn px-3 py-1.5 rounded-md text-[12px] font-bold transition-all text-on-surface-variant hover:text-primary';
                                            }
                                        });
                                    }
                                    this.filterFiles();
                                    this.updateDetailsPanel();
                                }
                            });
                    } else {
                        alert(res.message || 'Lỗi tải ảnh lên');
                    }
                } catch (err) {
                    alert('Lỗi parse JSON phản hồi tải lên');
                }
            } else {
                alert('Tải lên thất bại');
            }
        };

        xhr.onerror = () => {
            overlay.classList.add('hidden');
            alert('Lỗi kết nối tải lên');
        };

        xhr.send(formData);
    }
}
