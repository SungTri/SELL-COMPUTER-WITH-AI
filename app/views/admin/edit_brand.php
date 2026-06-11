<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-10">
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/brands" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-h2 font-bold text-primary">Chỉnh sửa thương hiệu</h1>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" form="editBrandForm" class="px-8 py-2.5 bg-primary text-white rounded-lg text-[14px] font-bold hover:bg-secondary transition-all flex items-center gap-2 shadow-md">
                <span class="material-symbols-outlined text-[20px]">save</span> Cập nhật
            </button>
        </div>
    </header>

    <div class="p-10 max-w-2xl mx-auto w-full">
        <?php $b = $data['brand']; ?>
        <div class="bg-white p-8 rounded-2xl border border-outline-variant shadow-sm space-y-8">
            <form id="editBrandForm" action="<?php echo URLROOT; ?>/admin/updateBrand/<?php echo $b['id']; ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?php echo csrf_field(); ?>
                <!-- Name -->
                <div class="space-y-1.5">
                    <label class="text-[14px] font-bold text-on-surface">Tên thương hiệu <span class="text-error">*</span></label>
                    <input type="text" name="name" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all <?php echo isset($data['errors']['name']) ? 'border-error' : ''; ?>" placeholder="VD: Intel, ASUS, Logitech..." value="<?php echo htmlspecialchars($b['name']); ?>">
                    <?php if(isset($data['errors']['name'])): ?>
                        <p class="text-[12px] text-error font-medium"><?php echo $data['errors']['name']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Logo -->
                <div class="space-y-1.5">
                    <label class="text-[14px] font-bold text-on-surface">Logo thương hiệu</label>
                    <div id="logoPreview" class="w-32 h-32 rounded-xl border-2 border-dashed border-outline-variant flex flex-col items-center justify-center bg-surface-container-low overflow-hidden group relative cursor-pointer">
                        <?php if($b['logo']): ?>
                            <img id="previewImg" src="<?php echo $b['logo']; ?>" class="absolute inset-0 w-full h-full object-contain bg-white" />
                        <?php else: ?>
                            <span class="material-symbols-outlined text-3xl text-on-surface-variant group-hover:scale-110 transition-transform">add_photo_alternate</span>
                            <p class="text-[10px] text-on-surface-variant mt-1">Chọn ảnh</p>
                            <img id="previewImg" class="absolute inset-0 w-full h-full object-contain bg-white hidden" />
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                            THAY ĐỔI
                        </div>
                    </div>
                    <input type="file" name="logo" id="logoInput" class="hidden" accept="image/*">
                    <input type="hidden" name="logo_library" id="logoLibraryInput">
                    
                    <div class="flex gap-2 w-48">
                        <button type="button" onclick="document.getElementById('logoInput').click()" class="flex-1 py-1 border border-outline-variant rounded-lg text-[11px] font-bold hover:bg-surface-container transition-all">
                            Tải lên
                        </button>
                        <button type="button" id="selectLogoLibraryBtn" class="flex-1 py-1 bg-secondary/10 text-secondary rounded-lg text-[11px] font-bold hover:bg-secondary/20 transition-all flex items-center justify-center gap-0.5">
                            <span class="material-symbols-outlined text-[14px]">photo_library</span> Thư viện
                        </button>
                    </div>
                    <p class="text-[11px] text-on-surface-variant italic">Để trống/không chọn nếu muốn giữ nguyên logo cũ</p>
                </div>

                <!-- Description -->
                <div class="space-y-1.5">
                    <label class="text-[14px] font-bold text-on-surface">Mô tả</label>
                    <textarea name="description" rows="5" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all resize-none" placeholder="Nhập mô tả về thương hiệu này..."><?php echo htmlspecialchars($b['description']); ?></textarea>
                </div>

                <?php if(isset($data['errors']['general'])): ?>
                    <div class="p-4 bg-red-50 text-red-600 rounded-xl border border-red-100 text-[13px] flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">error</span>
                        <?php echo $data['errors']['general']; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</main>

<script>
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');
    const previewImg = document.getElementById('previewImg');
    const logoLibraryInput = document.getElementById('logoLibraryInput');
    const selectLogoLibraryBtn = document.getElementById('selectLogoLibraryBtn');

    logoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            logoLibraryInput.value = ''; // Clear library selection if uploading new file
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('previewImg');
                if(img) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                } else {
                    const newImg = document.createElement('img');
                    newImg.id = 'previewImg';
                    newImg.src = e.target.result;
                    newImg.className = 'absolute inset-0 w-full h-full object-contain bg-white';
                    logoPreview.appendChild(newImg);
                }
            }
            reader.readAsDataURL(file);
        }
    });

    logoPreview.addEventListener('click', () => {
        if (!logoLibraryInput.value) {
            logoInput.click();
        }
    });

    const logoSelector = new MediaSelector({
        urlRoot: '<?php echo URLROOT; ?>',
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,
        folder: 'brands',
        onSelect: function(url) {
            logoLibraryInput.value = url;
            logoInput.value = ''; // Clear manual upload select
            
            const img = document.getElementById('previewImg');
            if(img) {
                img.src = url;
                img.classList.remove('hidden');
            } else {
                const newImg = document.createElement('img');
                newImg.id = 'previewImg';
                newImg.src = url;
                newImg.className = 'absolute inset-0 w-full h-full object-contain bg-white';
                logoPreview.appendChild(newImg);
            }
        }
    });
    selectLogoLibraryBtn.addEventListener('click', () => logoSelector.open());
</script>

</body>
</html>
