<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-40">
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/categories" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-h2 font-bold text-primary">Thêm danh mục mới</h1>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" form="addCategoryForm" class="px-8 py-2.5 bg-primary text-white rounded-lg text-[14px] font-bold hover:bg-secondary transition-all flex items-center gap-2 shadow-md">
                <span class="material-symbols-outlined text-[20px]">save</span> Lưu danh mục
            </button>
        </div>
    </header>

    <div class="p-10 max-w-2xl mx-auto w-full">
        <div class="bg-white p-8 rounded-2xl border border-outline-variant shadow-sm space-y-8">
            <form id="addCategoryForm" action="<?php echo URLROOT; ?>/admin/storeCategory" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[14px] font-bold text-on-surface">Tên danh mục <span class="text-error">*</span></label>
                        <input type="text" name="name" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all <?php echo isset($data['errors']['name']) ? 'border-error' : ''; ?>" placeholder="VD: Laptop Gaming, Linh kiện..." value="<?php echo $data['name']; ?>">
                        <?php if(isset($data['errors']['name'])): ?>
                            <p class="text-[12px] text-error font-medium"><?php echo $data['errors']['name']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[14px] font-bold text-on-surface">Danh mục cha</label>
                        <select name="parent_id" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all cursor-pointer appearance-none">
                            <option value="">--- Đây là danh mục chính ---</option>
                            <?php foreach($data['parent_categories'] as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-[12px] text-on-surface-variant">Chọn danh mục cha nếu đây là danh mục con</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[14px] font-bold text-on-surface">Mô tả</label>
                        <textarea name="description" rows="5" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all resize-none" placeholder="Nhập mô tả cho danh mục này..."><?php echo $data['description']; ?></textarea>
                    </div>
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

</body>
</html>
