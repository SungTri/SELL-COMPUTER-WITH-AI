<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-10">
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/products" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-h2 font-bold text-primary">Thêm sản phẩm mới</h1>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" form="addProductForm" class="px-8 py-2.5 bg-primary text-white rounded-lg text-[14px] font-bold hover:bg-secondary transition-all flex items-center gap-2 shadow-md">
                <span class="material-symbols-outlined text-[20px]">save</span> Lưu sản phẩm
            </button>
        </div>
    </header>

    <div class="p-10 max-w-5xl mx-auto w-full">
        <form id="addProductForm" action="<?php echo URLROOT; ?>/admin/storeProduct" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php echo csrf_field(); ?>
            <!-- Left Column: Main Info -->
            <div class="md:col-span-2 space-y-8">
                <!-- Basic Info Card -->
                <section class="bg-white p-8 rounded-2xl border border-outline-variant shadow-sm space-y-6">
                    <h2 class="text-h3 font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">info</span> Thông tin cơ bản
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-on-surface">Tên sản phẩm <span class="text-error">*</span></label>
                            <input type="text" name="name" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all" placeholder="VD: Intel Core i9-14900K">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[14px] font-bold text-on-surface">Giá bán (VNĐ) <span class="text-error">*</span></label>
                                <input type="number" name="price" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all" placeholder="0">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[14px] font-bold text-on-surface">Số lượng tồn kho <span class="text-error">*</span></label>
                                <input type="number" name="stock" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all" placeholder="0">
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Description Card -->
                <section class="bg-white p-8 rounded-2xl border border-outline-variant shadow-sm space-y-6">
                    <h2 class="text-h3 font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">description</span> Mô tả sản phẩm
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-on-surface">Mô tả ngắn</label>
                            <textarea name="short_description" rows="3" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all resize-none" placeholder="Tóm tắt đặc điểm nổi bật..."></textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-on-surface">Mô tả chi tiết</label>
                            <textarea name="detailed_description" rows="8" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all resize-none" placeholder="Thông số kỹ thuật, đánh giá chi tiết..."></textarea>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column: Sidebar Options -->
            <div class="space-y-8">
                <!-- Main Image Card -->
                <section class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm space-y-6">
                    <h2 class="text-[16px] font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">image</span> Hình ảnh đại diện
                    </h2>
                    
                    <div class="space-y-4">
                        <div id="imagePreview" class="w-full aspect-square rounded-xl border-2 border-dashed border-outline-variant flex flex-col items-center justify-center bg-surface-container-lowest overflow-hidden group relative cursor-pointer">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant group-hover:scale-110 transition-transform">add_a_photo</span>
                            <p class="text-[12px] text-on-surface-variant mt-2 px-4 text-center">Click để tải ảnh lên (JPG, PNG)</p>
                            <img id="previewImg" class="absolute inset-0 w-full h-full object-contain bg-white hidden" />
                        </div>
                        <input type="file" name="main_image" id="imageInput" class="hidden" accept="image/*">
                        <input type="hidden" name="main_image_library" id="mainImageLibraryInput">
                        
                        <div class="flex gap-2">
                            <button type="button" onclick="document.getElementById('imageInput').click()" class="flex-1 py-2 border border-outline-variant rounded-lg text-[13px] font-bold hover:bg-surface-container transition-all">
                                Tải ảnh lên
                            </button>
                            <button type="button" id="selectMainImageLibraryBtn" class="flex-1 py-2 bg-secondary/10 text-secondary rounded-lg text-[13px] font-bold hover:bg-secondary/20 transition-all flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">photo_library</span> Thư viện
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Secondary Images Card -->
                <section class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-[16px] font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined">collections</span> Ảnh phụ
                        </h2>
                        <span id="secondaryCount" class="text-[12px] text-on-surface-variant">0 ảnh</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div id="secondaryPreviewGrid" class="grid grid-cols-3 gap-2 min-h-[100px] p-2 rounded-xl bg-surface-container-low border border-outline-variant">
                            <!-- Previews will appear here -->
                            <div onclick="document.getElementById('secondaryInput').click()" class="aspect-square rounded-lg border-2 border-dashed border-outline-variant flex items-center justify-center cursor-pointer hover:bg-surface-container transition-all">
                                <span class="material-symbols-outlined text-on-surface-variant">add</span>
                            </div>
                        </div>
                        <input type="file" name="secondary_images[]" id="secondaryInput" class="hidden" accept="image/*" multiple>
                        <input type="hidden" name="secondary_images_library" id="secondaryImagesLibraryInput">
                        
                        <div class="flex gap-2">
                            <button type="button" onclick="document.getElementById('secondaryInput').click()" class="flex-1 py-2 border border-outline-variant rounded-lg text-[13px] font-bold hover:bg-surface-container transition-all">
                                Tải nhiều ảnh
                            </button>
                            <button type="button" id="selectSecondaryLibraryBtn" class="flex-1 py-2 bg-secondary/10 text-secondary rounded-lg text-[13px] font-bold hover:bg-secondary/20 transition-all flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">photo_library</span> Thư viện
                            </button>
                        </div>
                        <p class="text-[11px] text-on-surface-variant italic">Bạn có thể tải ảnh mới hoặc chọn từ thư viện ảnh cũ.</p>
                    </div>
                </section>

                <!-- Categorization Card -->
                <section class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm space-y-6">
                    <h2 class="text-[16px] font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">category</span> Phân loại
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-bold text-on-surface">Danh mục</label>
                            <select name="category_id" required class="w-full px-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg outline-none focus:ring-2 focus:ring-secondary/20 appearance-none cursor-pointer">
                                <option value="">Chọn danh mục</option>
                                <?php foreach($data['categories'] as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-bold text-on-surface">Thương hiệu</label>
                            <select name="brand_id" required class="w-full px-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg outline-none focus:ring-2 focus:ring-secondary/20 appearance-none cursor-pointer">
                                <option value="">Chọn thương hiệu</option>
                                <?php foreach($data['brands'] as $brand): ?>
                                    <option value="<?php echo $brand['id']; ?>"><?php echo $brand['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-bold text-on-surface">Trạng thái</label>
                            <div class="flex gap-4 p-1 bg-surface-container-low rounded-lg border border-outline-variant">
                                <label class="flex-1">
                                    <input type="radio" name="status" value="1" checked class="hidden peer">
                                    <div class="py-2 text-center rounded-md text-[12px] font-bold cursor-pointer transition-all peer-checked:bg-green-100 peer-checked:text-green-700">Đang bán</div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" name="status" value="0" class="hidden peer">
                                    <div class="py-2 text-center rounded-md text-[12px] font-bold cursor-pointer transition-all peer-checked:bg-red-100 peer-checked:text-red-700">Ngừng bán</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </form>
    </div>
</main>

<script>
    // Image Preview logic
    const imageInput = document.getElementById('imageInput');
    const previewImg = document.getElementById('previewImg');
    const imagePreview = document.getElementById('imagePreview');
    const mainImageLibraryInput = document.getElementById('mainImageLibraryInput');
    const selectMainImageLibraryBtn = document.getElementById('selectMainImageLibraryBtn');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            mainImageLibraryInput.value = ''; // clear library choice if uploading
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                imagePreview.classList.add('border-solid');
                imagePreview.classList.remove('border-dashed');
            }
            reader.readAsDataURL(file);
        }
    });

    imagePreview.addEventListener('click', () => {
        if (!mainImageLibraryInput.value) {
            imageInput.click();
        }
    });

    // Reusable media selector for main image
    const mainSelector = new MediaSelector({
        urlRoot: '<?php echo URLROOT; ?>',
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,
        folder: 'products',
        onSelect: function(url) {
            mainImageLibraryInput.value = url;
            imageInput.value = ''; // clear manual file select
            previewImg.src = url;
            previewImg.classList.remove('hidden');
            imagePreview.classList.add('border-solid');
            imagePreview.classList.remove('border-dashed');
        }
    });
    selectMainImageLibraryBtn.addEventListener('click', () => mainSelector.open());

    // Secondary Images Preview logic
    const secondaryInput = document.getElementById('secondaryInput');
    const secondaryPreviewGrid = document.getElementById('secondaryPreviewGrid');
    const secondaryCountLabel = document.getElementById('secondaryCount');
    const selectSecondaryLibraryBtn = document.getElementById('selectSecondaryLibraryBtn');
    const secondaryImagesLibraryInput = document.getElementById('secondaryImagesLibraryInput');
    let selectedSecondaryLibraryUrls = [];
    let selectedSecondaryFiles = [];

    // Reusable media selector for secondary images
    const secondarySelector = new MediaSelector({
        urlRoot: '<?php echo URLROOT; ?>',
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,
        folder: 'products',
        multiple: true,
        onSelect: function(urls) {
            const urlArray = Array.isArray(urls) ? urls : [urls];
            urlArray.forEach(url => {
                if (!selectedSecondaryLibraryUrls.includes(url)) {
                    selectedSecondaryLibraryUrls.push(url);
                }
            });
            renderSecondaryPreviews();
        }
    });
    selectSecondaryLibraryBtn.addEventListener('click', () => secondarySelector.open());

    secondaryInput.addEventListener('change', function() {
        const files = Array.from(this.files);
        files.forEach((file) => {
            if (!selectedSecondaryFiles.some(existing => existing.name === file.name && existing.size === file.size)) {
                selectedSecondaryFiles.push(file);
            }
        });
        updateSecondaryInputFiles();
        renderSecondaryPreviews();
    });

    function updateSecondaryInputFiles() {
        const dt = new DataTransfer();
        selectedSecondaryFiles.forEach(file => dt.items.add(file));
        secondaryInput.files = dt.files;
        updateCount();
    }

    window.removeUploadedSecondary = function(index) {
        selectedSecondaryFiles.splice(index, 1);
        updateSecondaryInputFiles();
        renderSecondaryPreviews();
    };

    function renderSecondaryPreviews() {
        const addButton = secondaryPreviewGrid.querySelector('div[onclick]');
        
        // Remove existing library previews and file previews
        const generatedPreviews = secondaryPreviewGrid.querySelectorAll('.library-preview, .file-preview, .new-preview');
        generatedPreviews.forEach(p => p.remove());

        // Update hidden input & counts
        secondaryImagesLibraryInput.value = JSON.stringify(selectedSecondaryLibraryUrls);
        updateCount();

        // Render library image previews
        selectedSecondaryLibraryUrls.forEach((url, index) => {
            const div = document.createElement('div');
            div.className = 'library-preview aspect-square rounded-lg overflow-hidden border border-outline-variant relative group';
            div.innerHTML = `
                <img src="${url}" class="w-full h-full object-cover">
                <button type="button" onclick="removeLibrarySecondary(${index})" class="absolute top-1 right-1 w-6 h-6 bg-red-600/90 hover:bg-red-700 text-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10" title="Gỡ ảnh">
                    <span class="material-symbols-outlined text-[16px] font-bold">close</span>
                </button>
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center pointer-events-none">
                    <span class="material-symbols-outlined text-white text-[18px]">visibility</span>
                </div>
            `;
            secondaryPreviewGrid.insertBefore(div, addButton);
        });

        // Render file image previews
        selectedSecondaryFiles.forEach((file, index) => {
            const div = document.createElement('div');
            div.className = 'file-preview aspect-square rounded-lg overflow-hidden border border-outline-variant relative group';
            div.innerHTML = `
                <img class="w-full h-full object-cover" id="new-file-img-${index}">
                <button type="button" onclick="removeUploadedSecondary(${index})" class="absolute top-1 right-1 w-6 h-6 bg-red-600/90 hover:bg-red-700 text-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10" title="Gỡ ảnh">
                    <span class="material-symbols-outlined text-[16px] font-bold">close</span>
                </button>
            `;
            secondaryPreviewGrid.insertBefore(div, addButton);

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(`new-file-img-${index}`);
                if (img) img.src = e.target.result;
            }
            reader.readAsDataURL(file);
        });
    }

    window.removeLibrarySecondary = function(index) {
        selectedSecondaryLibraryUrls.splice(index, 1);
        renderSecondaryPreviews();
    };

    function updateCount() {
        secondaryCountLabel.textContent = `${selectedSecondaryFiles.length + selectedSecondaryLibraryUrls.length} ảnh`;
    }
</script>

</body>
</html>
