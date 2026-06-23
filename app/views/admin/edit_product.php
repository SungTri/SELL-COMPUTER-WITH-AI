<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<?php $p = $data['product']; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-40">
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/products" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-h2 font-bold text-primary">Chỉnh sửa sản phẩm</h1>
                <p class="text-[12px] text-on-surface-variant">ID: #<?php echo $p['id']; ?> • Cập nhật thông tin chi tiết</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" form="editProductForm" class="px-8 py-2.5 bg-primary text-white rounded-lg text-[14px] font-bold hover:bg-secondary transition-all flex items-center gap-2 shadow-md">
                <span class="material-symbols-outlined text-[20px]">save</span> Cập nhật
            </button>
        </div>
    </header>

    <div class="p-10 max-w-5xl mx-auto w-full">
        <form id="editProductForm" action="<?php echo URLROOT; ?>/admin/updateProduct/<?php echo $p['id']; ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-8">
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
                            <input type="text" name="name" value="<?php echo htmlspecialchars($p['name']); ?>" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[14px] font-bold text-on-surface">Giá bán (VNĐ) <span class="text-error">*</span></label>
                                <input type="number" name="price" value="<?php echo (int)$p['price']; ?>" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[14px] font-bold text-on-surface">Số lượng tồn kho <span class="text-error">*</span></label>
                                <input type="number" name="stock" value="<?php echo $p['stock']; ?>" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all">
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
                            <textarea name="short_description" rows="3" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all resize-none"><?php echo htmlspecialchars($p['short_description']); ?></textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-on-surface">Mô tả chi tiết</label>
                            <textarea name="detailed_description" rows="8" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all resize-none"><?php echo htmlspecialchars($p['detailed_description']); ?></textarea>
                        </div>
                    </div>
                </section>
                
                <!-- Product Variants Configuration Section -->
                <?php
                // Extract existing attributes and values
                $existingAttributes = [];
                if (!empty($data['variants'])) {
                    foreach ($data['variants'] as $v) {
                        if (!empty($v['options'])) {
                            foreach ($v['options'] as $opt) {
                                $existingAttributes[$opt['attribute_name']][] = $opt['attribute_value'];
                            }
                        }
                    }
                    foreach ($existingAttributes as $name => $values) {
                        $existingAttributes[$name] = array_values(array_unique($values));
                    }
                }
                ?>
                <section class="bg-white p-8 rounded-2xl border border-outline-variant shadow-sm space-y-6">
                    <h2 class="text-h3 font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">layers</span> Cấu hình phiên bản sản phẩm
                    </h2>
                    <p class="text-xs text-on-surface-variant">Sử dụng phiên bản để bán sản phẩm với các cấu hình (RAM, SSD, màu sắc,...) khác nhau, mỗi phiên bản có SKU, giá bán và tồn kho riêng.</p>
                    
                    <input type="hidden" name="variants_json" id="variantsJsonInput" value="">
                    
                    <!-- Attributes list -->
                    <div id="attributesContainer" class="space-y-4">
                        <!-- Dynamic attribute rows here -->
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="button" id="addAttributeBtn" class="px-4 py-2 border border-outline-variant rounded-lg text-xs font-bold hover:bg-surface-container transition-all flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">add</span> Thêm thuộc tính
                        </button>
                        <button type="button" id="generateVariantsBtn" class="px-4 py-2 bg-secondary/10 text-secondary rounded-lg text-xs font-bold hover:bg-secondary/20 transition-all flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">build</span> Tạo / Cập nhật phiên bản
                        </button>
                    </div>

                    <!-- Variants Table -->
                    <div id="variantsTableContainer" class="hidden border border-outline-variant rounded-xl overflow-hidden mt-6">
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse text-left">
                                <thead>
                                    <tr class="bg-surface-container-low border-b border-outline-variant text-[12px] font-bold text-on-surface-variant">
                                        <th class="p-4">Phiên bản</th>
                                        <th class="p-4">SKU</th>
                                        <th class="p-4 w-40">Giá bán (VNĐ)</th>
                                        <th class="p-4 w-28">Kho hàng</th>
                                        <th class="p-4">Đường dẫn ảnh</th>
                                        <th class="p-4">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="variantsTableBody" class="divide-y divide-outline-variant/50 text-[13px]">
                                    <!-- Dynamic rows here -->
                                </tbody>
                            </table>
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
                            <?php if(!empty($p['main_image'])): ?>
                                <img id="previewImg" src="<?php echo get_product_image($p['main_image']); ?>" class="absolute inset-0 w-full h-full object-contain bg-white" />
                            <?php else: ?>
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant group-hover:scale-110 transition-transform">add_a_photo</span>
                                <p class="text-[12px] text-on-surface-variant mt-2 px-4 text-center">Click để tải ảnh lên</p>
                                <img id="previewImg" class="absolute inset-0 w-full h-full object-contain bg-white hidden" />
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[12px] font-bold">
                                THAY ĐỔI ẢNH
                            </div>
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
                        <p class="text-[11px] text-on-surface-variant text-center italic">Để trống/không chọn nếu muốn giữ nguyên ảnh cũ</p>
                    </div>
                </section>

                <!-- Secondary Images Card -->
                <section class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-[16px] font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined">collections</span> Ảnh phụ
                        </h2>
                        <span id="secondaryCount" class="text-[12px] text-on-surface-variant"><?php echo count($data['secondary_images']); ?> ảnh</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div id="deletedImagesContainer"></div>
                        <div id="secondaryPreviewGrid" class="grid grid-cols-3 gap-2 min-h-[100px] p-2 rounded-xl bg-surface-container-low border border-outline-variant">
                            <!-- Existing Images -->
                            <?php foreach($data['secondary_images'] as $img): ?>
                                <div class="existing-image-card aspect-square rounded-lg overflow-hidden border border-outline-variant relative group">
                                    <img src="<?php echo get_product_image($img['image_path']); ?>" class="w-full h-full object-cover">
                                    <button type="button" onclick="deleteExistingImage(<?php echo $img['id']; ?>, this)" class="absolute top-1 right-1 w-6 h-6 bg-red-600/90 hover:bg-red-700 text-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10" title="Xóa ảnh">
                                        <span class="material-symbols-outlined text-[16px] font-bold">close</span>
                                    </button>
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center pointer-events-none">
                                        <span class="material-symbols-outlined text-white text-[18px]">visibility</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <!-- Add New Button -->
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
                        <p class="text-[11px] text-on-surface-variant italic">Ảnh mới hoặc chọn từ thư viện sẽ được thêm vào danh sách ảnh phụ.</p>
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
                                    <option value="<?php echo $cat['id']; ?>" <?php echo $p['category_id'] == $cat['id'] ? 'selected' : ''; ?>><?php echo $cat['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-bold text-on-surface">Thương hiệu</label>
                            <select name="brand_id" required class="w-full px-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg outline-none focus:ring-2 focus:ring-secondary/20 appearance-none cursor-pointer">
                                <option value="">Chọn thương hiệu</option>
                                <?php foreach($data['brands'] as $brand): ?>
                                    <option value="<?php echo $brand['id']; ?>" <?php echo $p['brand_id'] == $brand['id'] ? 'selected' : ''; ?>><?php echo $brand['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-bold text-on-surface">Trạng thái</label>
                            <div class="flex gap-4 p-1 bg-surface-container-low rounded-lg border border-outline-variant">
                                <label class="flex-1">
                                    <input type="radio" name="status" value="1" <?php echo $p['status'] == 1 ? 'checked' : ''; ?> class="hidden peer">
                                    <div class="py-2 text-center rounded-md text-[12px] font-bold cursor-pointer transition-all peer-checked:bg-green-100 peer-checked:text-green-700">Đang bán</div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" name="status" value="0" <?php echo $p['status'] == 0 ? 'checked' : ''; ?> class="hidden peer">
                                    <div class="py-2 text-center rounded-md text-[12px] font-bold cursor-pointer transition-all peer-checked:bg-red-100 peer-checked:text-red-700">Ngừng bán</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>
                
                <div class="pt-4">
                    <button type="button" class="w-full py-3 border border-red-200 text-red-600 rounded-xl text-[14px] font-bold hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">delete</span> Xóa sản phẩm này
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    const imageInput = document.getElementById('imageInput');
    const previewImg = document.getElementById('previewImg');
    const imagePreview = document.getElementById('imagePreview');
    const mainImageLibraryInput = document.getElementById('mainImageLibraryInput');
    const selectMainImageLibraryBtn = document.getElementById('selectMainImageLibraryBtn');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            mainImageLibraryInput.value = ''; // clear library selection
            const reader = new FileReader();
            reader.onload = function(e) {
                if(!document.getElementById('previewImg')) {
                    const img = document.createElement('img');
                    img.id = 'previewImg';
                    img.className = 'absolute inset-0 w-full h-full object-contain bg-white';
                    imagePreview.appendChild(img);
                    const icon = imagePreview.querySelector('.material-symbols-outlined');
                    if(icon) icon.remove();
                    const text = imagePreview.querySelector('p');
                    if(text) text.remove();
                }
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('previewImg').classList.remove('hidden');
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
            imageInput.value = ''; // clear file input
            if(!document.getElementById('previewImg')) {
                const img = document.createElement('img');
                img.id = 'previewImg';
                img.className = 'absolute inset-0 w-full h-full object-contain bg-white';
                imagePreview.appendChild(img);
                const icon = imagePreview.querySelector('.material-symbols-outlined');
                if(icon) icon.remove();
                const text = imagePreview.querySelector('p');
                if(text) text.remove();
            }
            document.getElementById('previewImg').src = url;
            document.getElementById('previewImg').classList.remove('hidden');
        }
    });
    selectMainImageLibraryBtn.addEventListener('click', () => mainSelector.open());

    // Secondary Images Preview logic
    const secondaryInput = document.getElementById('secondaryInput');
    const secondaryPreviewGrid = document.getElementById('secondaryPreviewGrid');
    const secondaryCountLabel = document.getElementById('secondaryCount');
    const selectSecondaryLibraryBtn = document.getElementById('selectSecondaryLibraryBtn');
    const secondaryImagesLibraryInput = document.getElementById('secondaryImagesLibraryInput');
    let existingCount = <?php echo count($data['secondary_images']); ?>;
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

    window.deleteExistingImage = function(imgId, element) {
        const container = document.getElementById('deletedImagesContainer');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'deleted_images[]';
        input.value = imgId;
        container.appendChild(input);

        element.closest('.existing-image-card').remove();
        existingCount--;
        updateCount();
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
            div.className = 'file-preview aspect-square rounded-lg overflow-hidden border-2 border-secondary relative group';
            div.innerHTML = `
                <img class="w-full h-full object-cover" id="new-file-img-${index}">
                <button type="button" onclick="removeUploadedSecondary(${index})" class="absolute top-1 right-1 w-6 h-6 bg-red-600/90 hover:bg-red-700 text-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10" title="Gỡ ảnh">
                    <span class="material-symbols-outlined text-[16px] font-bold">close</span>
                </button>
                <div class="absolute top-0 left-0 bg-secondary text-white text-[10px] px-1.5 py-0.5 rounded-br-lg font-bold z-10">MỚI</div>
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
        secondaryCountLabel.textContent = `${existingCount + selectedSecondaryFiles.length + selectedSecondaryLibraryUrls.length} ảnh`;
    }

    // Product Variants JS Logic
    let attributes = <?php echo !empty($existingAttributes) ? json_encode($existingAttributes) : '{}'; ?>;
    let variants = <?php 
        $formattedVariants = [];
        if (!empty($data['variants'])) {
            foreach ($data['variants'] as $v) {
                $optsAssoc = [];
                if (!empty($v['options'])) {
                    foreach ($v['options'] as $opt) {
                        $optsAssoc[$opt['attribute_name']] = $opt['attribute_value'];
                    }
                }
                $formattedVariants[] = [
                    'id' => $v['id'],
                    'sku' => $v['sku'],
                    'price' => (float)$v['price'],
                    'stock' => (int)$v['stock'],
                    'image' => $v['image'],
                    'options' => $optsAssoc
                ];
            }
        }
        echo json_encode($formattedVariants); 
    ?>;

    function initAttributes() {
        const container = document.getElementById('attributesContainer');
        container.innerHTML = '';
        
        const keys = Object.keys(attributes);
        if (keys.length === 0) {
            addAttributeRow('', []);
        } else {
            keys.forEach(key => {
                addAttributeRow(key, attributes[key]);
            });
        }
    }

    function addAttributeRow(name = '', values = []) {
        const container = document.getElementById('attributesContainer');
        const div = document.createElement('div');
        div.className = 'flex gap-4 items-end bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/30 attr-row';
        div.innerHTML = `
            <div class="flex-1 space-y-1.5">
                <label class="text-[12px] font-bold text-on-surface-variant">Tên thuộc tính</label>
                <input type="text" value="${name}" placeholder="Ví dụ: RAM, SSD, Màu sắc" class="attr-name w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm outline-none focus:ring-2 focus:ring-secondary/20 transition-all">
            </div>
            <div class="flex-[2] space-y-1.5">
                <label class="text-[12px] font-bold text-on-surface-variant">Giá trị (cách nhau bằng dấu phẩy)</label>
                <input type="text" value="${values.join(', ')}" placeholder="Ví dụ: 8GB, 16GB" class="attr-values w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm outline-none focus:ring-2 focus:ring-secondary/20 transition-all">
            </div>
            <button type="button" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm transition-all" onclick="this.closest('.attr-row').remove(); collectAttributes();">
                <span class="material-symbols-outlined text-[20px] align-middle">delete</span>
            </button>
        `;
        container.appendChild(div);
    }

    function collectAttributes() {
        attributes = {};
        const rows = document.querySelectorAll('.attr-row');
        rows.forEach(row => {
            const nameInput = row.querySelector('.attr-name');
            const valuesInput = row.querySelector('.attr-values');
            const name = nameInput.value.trim();
            if (name) {
                const values = valuesInput.value.split(',')
                    .map(v => v.trim())
                    .filter(v => v !== '');
                if (values.length > 0) {
                    attributes[name] = values;
                }
            }
        });
    }

    function generateVariants() {
        const keys = Object.keys(attributes);
        if (keys.length === 0) {
            variants = [];
            renderVariantsTable();
            updateVariantsJson();
            return;
        }

        const combos = getCartesianProduct(attributes);
        const newVariants = [];

        combos.forEach(combo => {
            const existing = variants.find(v => {
                const comboKeys = Object.keys(combo);
                const vKeys = Object.keys(v.options);
                if (comboKeys.length !== vKeys.length) return false;
                return comboKeys.every(k => v.options[k] === combo[k]);
            });

            if (existing) {
                newVariants.push(existing);
            } else {
                const basePriceInput = document.querySelector('input[name="price"]');
                const basePrice = basePriceInput ? parseFloat(basePriceInput.value) || 0 : 0;
                newVariants.push({
                    id: null,
                    sku: '',
                    price: basePrice,
                    stock: 0,
                    image: '',
                    options: combo
                });
            }
        });

        variants = newVariants;
        renderVariantsTable();
        updateVariantsJson();
    }

    function getCartesianProduct(obj) {
        const keys = Object.keys(obj);
        if (keys.length === 0) return [];
        const results = [];
        
        function helper(index, currentCombo) {
            if (index === keys.length) {
                results.push({ ...currentCombo });
                return;
            }
            const key = keys[index];
            const values = obj[key];
            values.forEach(val => {
                currentCombo[key] = val;
                helper(index + 1, currentCombo);
            });
        }
        helper(0, {});
        return results;
    }

    function renderVariantsTable() {
        const container = document.getElementById('variantsTableContainer');
        const tbody = document.getElementById('variantsTableBody');
        tbody.innerHTML = '';

        if (variants.length === 0) {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden');

        variants.forEach((v, index) => {
            const optionsStr = Object.keys(v.options).map(k => `${k}: ${v.options[k]}`).join(', ');
            
            const tr = document.createElement('tr');
            tr.className = 'border-b border-outline-variant/30 hover:bg-surface-container-lowest/50 transition-colors';
            tr.innerHTML = `
                <td class="p-4 font-bold text-primary">${optionsStr}</td>
                <td class="p-4">
                    <input type="text" value="${v.sku || ''}" placeholder="Mã SKU..." oninput="updateVariantField(${index}, 'sku', this.value)" class="w-full px-2 py-1.5 bg-surface-container-low border border-outline-variant rounded outline-none focus:ring-1 focus:ring-secondary text-xs">
                </td>
                <td class="p-4">
                    <input type="number" value="${v.price}" placeholder="Giá bán..." oninput="updateVariantField(${index}, 'price', this.value)" class="w-full px-2 py-1.5 bg-surface-container-low border border-outline-variant rounded outline-none focus:ring-1 focus:ring-secondary text-xs">
                </td>
                <td class="p-4">
                    <input type="number" value="${v.stock}" placeholder="Số lượng..." oninput="updateVariantField(${index}, 'stock', this.value)" class="w-full px-2 py-1.5 bg-surface-container-low border border-outline-variant rounded outline-none focus:ring-1 focus:ring-secondary text-xs">
                </td>
                <td class="p-4">
                    <input type="text" value="${v.image || ''}" placeholder="URL ảnh..." oninput="updateVariantField(${index}, 'image', this.value)" class="w-full px-2 py-1.5 bg-surface-container-low border border-outline-variant rounded outline-none focus:ring-1 focus:ring-secondary text-xs">
                </td>
                <td class="p-4 text-center">
                    <button type="button" class="text-error hover:text-red-700 font-medium text-xs" onclick="deleteVariant(${index})">Xóa</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    window.updateVariantField = function(index, field, value) {
        if (field === 'price') {
            variants[index][field] = parseFloat(value) || 0;
        } else if (field === 'stock') {
            variants[index][field] = parseInt(value) || 0;
        } else {
            variants[index][field] = value;
        }
        updateVariantsJson();
    };

    window.deleteVariant = function(index) {
        variants.splice(index, 1);
        renderVariantsTable();
        updateVariantsJson();
    };

    function updateVariantsJson() {
        document.getElementById('variantsJsonInput').value = JSON.stringify(variants);
    }

    // Attach listeners
    document.getElementById('addAttributeBtn').addEventListener('click', () => {
        addAttributeRow('', []);
    });

    document.getElementById('generateVariantsBtn').addEventListener('click', () => {
        collectAttributes();
        generateVariants();
    });

    // Initialize on load
    initAttributes();
    if (variants.length > 0) {
        renderVariantsTable();
        updateVariantsJson();
    }
</script>

</body>
</html>
