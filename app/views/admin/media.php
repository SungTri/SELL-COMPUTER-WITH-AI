<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-10">
        <div>
            <h1 class="text-h2 font-bold text-primary">Thư viện ảnh</h1>
            <p class="text-[13px] text-on-surface-variant">Quản lý, tải lên và kiểm tra mức độ sử dụng của các tệp đa phương tiện</p>
        </div>
        
        <div class="flex items-center gap-4">
            <span class="px-4 py-2 bg-primary/10 text-primary rounded-lg text-[13px] font-bold">
                Tổng số tệp: <span id="total-count-badge"><?php echo count($data['files']); ?></span>
            </span>
        </div>
    </header>

    <div class="p-10 flex flex-col lg:flex-row gap-8 flex-1 min-h-0">
        <!-- Main Media Browser (Left Side) -->
        <div class="flex-1 flex flex-col gap-6 min-w-0">
            <!-- Upload & Toolbar Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-outline-variant flex flex-col gap-6">
                <!-- Upload Zone -->
                <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="w-full md:w-auto flex items-center gap-3">
                        <label for="upload-folder-select" class="text-[14px] font-bold text-primary whitespace-nowrap">Tải lên thư mục:</label>
                        <select id="upload-folder-select" class="bg-[#F8F9FB] border border-outline-variant pl-4 pr-10 py-2.5 rounded-xl text-[14px] font-medium text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 min-w-[220px]">
                            <option value="products">products (Sản phẩm)</option>
                            <option value="brands">brands (Thương hiệu)</option>
                            <option value="reviews">reviews (Đánh giá)</option>
                        </select>
                    </div>
                    
                    <div class="w-full md:flex-1">
                        <!-- Dropzone -->
                        <div id="dropzone" class="border-2 border-dashed border-outline-variant hover:border-primary hover:bg-primary/5 transition-all rounded-2xl p-6 flex flex-col items-center justify-center cursor-pointer text-center group">
                            <input type="file" id="file-input" class="hidden" multiple accept="image/*">
                            <span class="material-symbols-outlined text-3xl text-on-surface-variant group-hover:text-primary mb-2 transition-colors">upload_file</span>
                            <p class="text-[14px] font-bold text-primary">Kéo thả ảnh vào đây hoặc nhấp để chọn</p>
                            <p class="text-[12px] text-on-surface-variant mt-1">Chấp nhận JPG, PNG, WEBP, GIF, SVG (Tối đa 5MB/tệp)</p>
                        </div>
                    </div>
                </div>

                <!-- Progress container -->
                <div id="upload-progress-container" class="hidden space-y-2">
                    <div class="flex items-center justify-between text-[13px] font-bold text-primary">
                        <span>Đang tải lên...</span>
                        <span id="upload-progress-text">0%</span>
                    </div>
                    <div class="w-full bg-surface-container-low rounded-full h-2">
                        <div id="upload-progress-bar" class="bg-primary h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Toolbar Filters -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center pt-4 border-t border-outline-variant">
                    <!-- Tabs -->
                    <div class="flex bg-surface-container-low p-1.5 rounded-xl border border-outline-variant w-full sm:w-auto">
                        <a href="?category=all&search=<?php echo urlencode($data['search']); ?>" class="px-4 py-2 rounded-lg text-[13px] font-bold transition-all <?php echo $data['category'] === 'all' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'; ?>">Tất cả</a>
                        <a href="?category=products&search=<?php echo urlencode($data['search']); ?>" class="px-4 py-2 rounded-lg text-[13px] font-bold transition-all <?php echo $data['category'] === 'products' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'; ?>">Sản phẩm</a>
                        <a href="?category=brands&search=<?php echo urlencode($data['search']); ?>" class="px-4 py-2 rounded-lg text-[13px] font-bold transition-all <?php echo $data['category'] === 'brands' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'; ?>">Thương hiệu</a>
                        <a href="?category=reviews&search=<?php echo urlencode($data['search']); ?>" class="px-4 py-2 rounded-lg text-[13px] font-bold transition-all <?php echo $data['category'] === 'reviews' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-primary'; ?>">Đánh giá</a>
                    </div>

                    <!-- Search -->
                    <form method="GET" action="" class="relative w-full sm:w-80 flex gap-2">
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($data['category']); ?>">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-[20px] text-on-surface-variant">search</span>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($data['search']); ?>" placeholder="Tìm kiếm tên ảnh..." class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#F8F9FB] border border-outline-variant text-[14px] text-primary placeholder-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <?php if(!empty($data['search'])): ?>
                            <a href="?category=<?php echo htmlspecialchars($data['category']); ?>" class="absolute right-3 top-3 text-on-surface-variant hover:text-primary">
                                <span class="material-symbols-outlined text-[20px]">close</span>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Media Grid -->
            <div id="media-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">
                <?php if (empty($data['files'])): ?>
                    <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-outline-variant shadow-sm">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-3 block">photo_library</span>
                        <p class="text-[16px] font-bold text-primary">Không tìm thấy ảnh nào</p>
                        <p class="text-[13px] text-on-surface-variant mt-1">Hãy thử đổi bộ lọc hoặc tải thêm ảnh mới</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($data['files'] as $file): ?>
                        <div class="media-card bg-white border border-outline-variant rounded-2xl overflow-hidden cursor-pointer hover:shadow-md hover:border-primary/40 transition-all group relative flex flex-col"
                             data-name="<?php echo htmlspecialchars($file['name']); ?>"
                             data-folder="<?php echo htmlspecialchars($file['folder']); ?>"
                             data-size="<?php echo $file['size']; ?>"
                             data-formatted-size="<?php echo htmlspecialchars($file['formatted_size']); ?>"
                             data-mtime="<?php echo $file['mtime']; ?>"
                             data-formatted-date="<?php echo htmlspecialchars($file['formatted_date']); ?>"
                             data-url="<?php echo htmlspecialchars($file['url']); ?>"
                             data-in-use="<?php echo $file['in_use'] ? 'true' : 'false'; ?>"
                             data-usages="<?php echo htmlspecialchars(json_encode($file['usages'])); ?>">
                            
                            <!-- Thumbnail Area -->
                            <div class="aspect-square bg-surface-container-low flex items-center justify-center overflow-hidden border-b border-outline-variant relative">
                                <img src="<?php echo htmlspecialchars($file['url']); ?>" 
                                     alt="<?php echo htmlspecialchars($file['name']); ?>" 
                                     class="w-full h-full object-cover transition-transform group-hover:scale-105 duration-300"
                                     onerror="this.src='https://ui-avatars.com/api/?name=Error&background=f44336&color=fff';">
                                
                                <!-- Folder Tag -->
                                <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-black/60 text-white shadow-sm">
                                    <?php echo $file['folder']; ?>
                                </span>

                                <!-- In Use Tag -->
                                <?php if ($file['in_use']): ?>
                                    <span class="absolute top-2.5 right-2.5 w-6 h-6 rounded-full flex items-center justify-center bg-green-500 text-white shadow-sm" title="Đang sử dụng">
                                        <span class="material-symbols-outlined text-[14px]">link</span>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Name / Info Footer -->
                            <div class="p-3 flex-1 flex flex-col justify-between">
                                <p class="text-[13px] font-bold text-primary truncate" title="<?php echo htmlspecialchars($file['name']); ?>">
                                    <?php echo htmlspecialchars($file['name']); ?>
                                </p>
                                <div class="flex items-center justify-between text-[11px] text-on-surface-variant mt-1.5 font-medium">
                                    <span><?php echo htmlspecialchars($file['formatted_size']); ?></span>
                                    <span><?php echo date('d/m/y', $file['mtime']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detail Panel Drawer (Right Side) -->
        <div id="detail-panel" class="w-full lg:w-96 bg-white rounded-3xl border border-outline-variant p-6 shadow-sm flex flex-col gap-6 sticky top-28 self-start h-[calc(100vh-10rem)] overflow-y-auto">
            <!-- Empty State -->
            <div id="detail-empty-state" class="flex-1 flex flex-col items-center justify-center text-center py-10">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">info</span>
                <p class="text-[14px] font-bold text-primary">Chọn một hình ảnh</p>
                <p class="text-[12px] text-on-surface-variant">để xem thông tin chi tiết và tùy chọn quản lý</p>
            </div>

            <!-- Content Area (Hidden by default) -->
            <div id="detail-content" class="hidden flex flex-col gap-6">
                <h3 class="text-[16px] font-bold text-primary pb-3 border-b border-outline-variant">Chi tiết hình ảnh</h3>
                
                <!-- Preview -->
                <div class="aspect-square bg-surface-container-low rounded-2xl overflow-hidden border border-outline-variant flex items-center justify-center">
                    <img id="detail-preview-img" src="" alt="" class="w-full h-full object-contain">
                </div>

                <!-- Info Table -->
                <div class="space-y-3.5">
                    <div>
                        <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider block">Tên tệp</span>
                        <span id="detail-name" class="text-[13px] font-bold text-primary break-all"></span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider block">Thư mục</span>
                            <span id="detail-folder" class="text-[13px] font-medium text-primary capitalize"></span>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider block">Dung lượng</span>
                            <span id="detail-size" class="text-[13px] font-medium text-primary"></span>
                        </div>
                    </div>

                    <div>
                        <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider block">Ngày tải lên</span>
                        <span id="detail-date" class="text-[13px] font-medium text-primary"></span>
                    </div>

                    <div>
                        <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider block">Đường dẫn URL</span>
                        <div class="flex items-center gap-2 mt-1">
                            <input id="detail-url-input" type="text" readonly class="flex-1 text-[12px] font-mono bg-[#F8F9FB] border border-outline-variant px-3 py-1.5 rounded-lg text-primary focus:outline-none">
                            <button id="copy-url-btn" class="p-1.5 bg-primary/5 hover:bg-primary/10 rounded-lg text-primary transition-all flex items-center justify-center" title="Sao chép đường dẫn">
                                <span class="material-symbols-outlined text-[18px]">content_copy</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Usage Check Alert Box -->
                <div id="detail-usage-container" class="rounded-2xl p-4 border transition-all">
                    <div class="flex items-start gap-3">
                        <span id="usage-status-icon" class="material-symbols-outlined text-[20px] mt-0.5"></span>
                        <div>
                            <p id="usage-status-title" class="text-[13px] font-bold"></p>
                            <p id="usage-status-desc" class="text-[12px] mt-0.5 leading-relaxed"></p>
                        </div>
                    </div>
                    <ul id="usage-list" class="mt-3.5 space-y-1.5 text-[12px] font-medium pl-8 list-disc text-primary"></ul>
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t border-outline-variant">
                    <button id="delete-media-btn" class="w-full py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                        <span>Xóa hình ảnh</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Notification Toast -->
<div id="toast" class="fixed bottom-6 right-6 px-6 py-4 rounded-2xl text-white shadow-xl flex items-center gap-3 transform translate-y-20 opacity-0 transition-all duration-300 z-50">
    <span id="toast-icon" class="material-symbols-outlined text-[22px]">info</span>
    <span id="toast-msg" class="text-[14px] font-bold"></span>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Elements
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file-input');
    const folderSelect = document.getElementById('upload-folder-select');
    const progressContainer = document.getElementById('upload-progress-container');
    const progressBar = document.getElementById('upload-progress-bar');
    const progressText = document.getElementById('upload-progress-text');
    
    const mediaCards = document.querySelectorAll('.media-card');
    const detailPanel = document.getElementById('detail-panel');
    const detailEmpty = document.getElementById('detail-empty-state');
    const detailContent = document.getElementById('detail-content');
    
    const previewImg = document.getElementById('detail-preview-img');
    const detailName = document.getElementById('detail-name');
    const detailFolder = document.getElementById('detail-folder');
    const detailSize = document.getElementById('detail-size');
    const detailDate = document.getElementById('detail-date');
    const detailUrlInput = document.getElementById('detail-url-input');
    
    const copyUrlBtn = document.getElementById('copy-url-btn');
    const usageContainer = document.getElementById('detail-usage-container');
    const usageIcon = document.getElementById('usage-status-icon');
    const usageTitle = document.getElementById('usage-status-title');
    const usageDesc = document.getElementById('usage-status-desc');
    const usageList = document.getElementById('usage-list');
    
    const deleteBtn = document.getElementById('delete-media-btn');
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toast-icon');
    const toastMsg = document.getElementById('toast-msg');

    let selectedFile = null;

    // Toast show helper
    function showToast(msg, isSuccess = true) {
        toastMsg.textContent = msg;
        if (isSuccess) {
            toast.className = 'fixed bottom-6 right-6 px-6 py-4 rounded-2xl text-white shadow-xl flex items-center gap-3 transform translate-y-0 opacity-100 transition-all duration-300 z-50 bg-green-600';
            toastIcon.textContent = 'check_circle';
        } else {
            toast.className = 'fixed bottom-6 right-6 px-6 py-4 rounded-2xl text-white shadow-xl flex items-center gap-3 transform translate-y-0 opacity-100 transition-all duration-300 z-50 bg-red-600';
            toastIcon.textContent = 'error';
        }
        
        setTimeout(() => {
            toast.className = 'fixed bottom-6 right-6 px-6 py-4 rounded-2xl text-white shadow-xl flex items-center gap-3 transform translate-y-20 opacity-0 transition-all duration-300 z-50';
        }, 4000);
    }

    // Copy URL helper
    copyUrlBtn.addEventListener('click', function() {
        if (!detailUrlInput.value) return;
        detailUrlInput.select();
        document.execCommand('copy');
        showToast('Đã sao chép đường dẫn hình ảnh!');
    });

    // Drag and Drop Upload logic
    dropzone.addEventListener('click', () => fileInput.click());
    
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-primary', 'bg-primary/5');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-primary', 'bg-primary/5');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-primary', 'bg-primary/5');
        if (e.dataTransfer.files.length > 0) {
            uploadFiles(e.dataTransfer.files);
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            uploadFiles(fileInput.files);
        }
    });

    function uploadFiles(files) {
        const formData = new FormData();
        formData.append('folder', folderSelect.value);
        formData.append('csrf_token', csrfToken);
        
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        // Show progress UI
        progressContainer.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressText.textContent = '0%';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo URLROOT; ?>/admin/uploadMedia', true);
        
        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percentComplete + '%';
                progressText.textContent = percentComplete + '%';
            }
        };

        xhr.onload = function() {
            progressContainer.classList.add('hidden');
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showToast(response.message || 'Tải ảnh lên thành công!');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(response.message || 'Có lỗi xảy ra', false);
                    }
                } catch(e) {
                    showToast('Lỗi parse JSON phản hồi tải lên', false);
                }
            } else {
                showToast('Tải lên thất bại với mã lỗi: ' + xhr.status, false);
            }
        };

        xhr.onerror = function() {
            progressContainer.classList.add('hidden');
            showToast('Kết nối tải lên bị lỗi', false);
        };

        xhr.send(formData);
    }

    // Media Card Click details display
    mediaCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active from other cards
            mediaCards.forEach(c => c.classList.remove('ring-2', 'ring-primary', 'border-transparent'));
            
            this.classList.add('ring-2', 'ring-primary', 'border-transparent');
            
            selectedFile = {
                name: this.dataset.name,
                folder: this.dataset.folder,
                size: this.dataset.size,
                formattedSize: this.dataset.formattedSize,
                mtime: this.dataset.mtime,
                formattedDate: this.dataset.formattedDate,
                url: this.dataset.url,
                inUse: this.dataset.inUse === 'true',
                usages: JSON.parse(this.dataset.usages || '[]')
            };

            showDetails();
        });
    });

    function showDetails() {
        if (!selectedFile) return;

        detailEmpty.classList.add('hidden');
        detailContent.classList.remove('hidden');

        previewImg.src = selectedFile.url;
        detailName.textContent = selectedFile.name;
        detailFolder.textContent = selectedFile.folder;
        detailSize.textContent = selectedFile.formattedSize;
        detailDate.textContent = selectedFile.formattedDate;
        detailUrlInput.value = selectedFile.url;

        // Render usages
        usageList.innerHTML = '';
        if (selectedFile.inUse) {
            // In Use styling
            usageContainer.className = 'rounded-2xl p-4 border border-amber-200 bg-amber-50 text-amber-800';
            usageIcon.textContent = 'warning';
            usageIcon.className = 'material-symbols-outlined text-[20px] mt-0.5 text-amber-600';
            usageTitle.textContent = 'Đang được sử dụng';
            usageTitle.className = 'text-[13px] font-bold text-amber-800';
            usageDesc.textContent = 'Ảnh này được liên kết với cơ sở dữ liệu và KHÔNG THỂ XÓA để tránh làm vỡ giao diện.';
            
            selectedFile.usages.forEach(u => {
                const li = document.createElement('li');
                li.textContent = u.detail;
                usageList.appendChild(li);
            });
            usageList.classList.remove('hidden');

            // Disable delete
            deleteBtn.disabled = true;
            deleteBtn.className = 'w-full py-3 rounded-xl font-bold flex items-center justify-center gap-2 bg-neutral-200 text-neutral-400 cursor-not-allowed';
            deleteBtn.title = 'Vui lòng gỡ bỏ hình ảnh này khỏi các sản phẩm/đánh giá/thương hiệu trước khi xóa.';
        } else {
            // Safe styling
            usageContainer.className = 'rounded-2xl p-4 border border-green-200 bg-green-50 text-green-800';
            usageIcon.textContent = 'check_circle';
            usageIcon.className = 'material-symbols-outlined text-[20px] mt-0.5 text-green-600';
            usageTitle.textContent = 'Chưa sử dụng';
            usageTitle.className = 'text-[13px] font-bold text-green-800';
            usageDesc.textContent = 'Hình ảnh này không liên kết với cơ sở dữ liệu. Bạn có thể xóa an toàn.';
            usageList.classList.add('hidden');

            // Enable delete
            deleteBtn.disabled = false;
            deleteBtn.className = 'w-full py-3 rounded-xl font-bold flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white shadow-lg shadow-red-200';
            deleteBtn.title = '';
        }
    }

    // Delete Media event
    deleteBtn.addEventListener('click', function() {
        if (!selectedFile || selectedFile.inUse) return;

        if (confirm(`Bạn có chắc chắn muốn xóa vĩnh viễn hình ảnh "${selectedFile.name}" khỏi ổ đĩa?`)) {
            const formData = new FormData();
            formData.append('folder', selectedFile.folder);
            formData.append('filename', selectedFile.name);
            formData.append('csrf_token', csrfToken);

            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[20px]">sync</span><span>Đang xóa...</span>';

            fetch('<?php echo URLROOT; ?>/admin/deleteMedia', {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (res.status === 200) {
                    return res.json().then(data => {
                        showToast(data.message || 'Xóa ảnh thành công!');
                        setTimeout(() => location.reload(), 1000);
                    });
                } else {
                    return res.json().then(data => {
                        showToast(data.message || 'Không thể xóa ảnh', false);
                        showDetails(); // refresh buttons state
                    }).catch(() => {
                        showToast('Có lỗi xảy ra khi gửi yêu cầu', false);
                        showDetails();
                    });
                }
            })
            .catch(err => {
                showToast('Không kết nối được tới máy chủ', false);
                showDetails();
            });
        }
    });
});
</script>

</body>
</html>
