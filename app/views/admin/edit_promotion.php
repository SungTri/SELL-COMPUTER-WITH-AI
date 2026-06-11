<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<?php $v = $data['voucher']; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-10">
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/promotions" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-h2 font-bold text-primary">Chỉnh sửa khuyến mãi</h1>
                <p class="text-[12px] text-on-surface-variant">Mã: <?php echo $v['code']; ?> • Cấu hình chương trình</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" form="editPromotionForm" class="px-8 py-2.5 bg-primary text-white rounded-lg text-[14px] font-bold hover:bg-secondary transition-all flex items-center gap-2 shadow-md">
                <span class="material-symbols-outlined text-[20px]">save</span> Cập nhật
            </button>
        </div>
    </header>

    <div class="p-10 max-w-3xl mx-auto w-full">
        <form id="editPromotionForm" action="<?php echo URLROOT; ?>/admin/updatePromotion/<?php echo $v['id']; ?>" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php echo csrf_field(); ?>
            <!-- Left: Main Info -->
            <div class="md:col-span-2 space-y-8">
                <section class="bg-white p-8 rounded-2xl border border-outline-variant shadow-sm space-y-6">
                    <h2 class="text-h3 font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">confirmation_number</span> Cấu hình mã
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-on-surface">Mã Code <span class="text-error">*</span></label>
                            <input type="text" name="code" value="<?php echo htmlspecialchars($v['code']); ?>" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all uppercase font-mono">
                        </div>
                        
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-on-surface">Mô tả</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all resize-none"><?php echo htmlspecialchars($v['description']); ?></textarea>
                        </div>
                    </div>
                </section>

                <section class="bg-white p-8 rounded-2xl border border-outline-variant shadow-sm space-y-6">
                    <h2 class="text-h3 font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">payments</span> Giá trị giảm giá
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-on-surface">Giảm theo %</label>
                            <div class="relative">
                                <input type="number" name="discount_percentage" value="<?php echo $v['discount_percentage']; ?>" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-on-surface-variant">%</span>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[14px] font-bold text-on-surface">Giảm tiền mặt (đ)</label>
                            <input type="number" name="discount_amount" value="<?php echo (int)$v['discount_amount']; ?>" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary/20 outline-none transition-all">
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right: Settings -->
            <div class="space-y-8">
                <section class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm space-y-6">
                    <h2 class="text-[16px] font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">event</span> Hiệu lực
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-bold text-on-surface">Ngày bắt đầu</label>
                            <input type="date" name="start_date" value="<?php echo $v['start_date']; ?>" class="w-full px-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg outline-none focus:ring-2 focus:ring-secondary/20">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-bold text-on-surface">Ngày kết thúc</label>
                            <input type="date" name="end_date" value="<?php echo $v['end_date']; ?>" class="w-full px-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg outline-none focus:ring-2 focus:ring-secondary/20">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-bold text-on-surface">Trạng thái</label>
                            <div class="flex gap-4 p-1 bg-surface-container-low rounded-lg border border-outline-variant">
                                <label class="flex-1">
                                    <input type="radio" name="status" value="1" <?php echo $v['status'] == 1 ? 'checked' : ''; ?> class="hidden peer">
                                    <div class="py-2 text-center rounded-md text-[12px] font-bold cursor-pointer transition-all peer-checked:bg-green-100 peer-checked:text-green-700">Bật</div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" name="status" value="0" <?php echo $v['status'] == 0 ? 'checked' : ''; ?> class="hidden peer">
                                    <div class="py-2 text-center rounded-md text-[12px] font-bold cursor-pointer transition-all peer-checked:bg-red-100 peer-checked:text-red-700">Tắt</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>
                
                <div class="pt-4">
                    <button type="button" onclick="confirmDelete(<?php echo $v['id']; ?>)" class="w-full py-3 border border-red-200 text-red-600 rounded-xl text-[14px] font-bold hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">delete</span> Xóa khuyến mãi này
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa mã khuyến mãi này? Thao tác này không thể hoàn tác.')) {
        window.location.href = '<?php echo URLROOT; ?>/admin/deletePromotion/' + id;
    }
}
</script>

</body>
</html>
