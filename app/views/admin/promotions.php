<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-10">
        <h1 class="text-h2 font-bold text-primary">Quản lý Khuyến mãi</h1>
        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                    <img alt="Admin" class="w-10 h-10 rounded-full object-cover" src="<?php echo $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=Admin&background=0453cd&color=fff'; ?>"/>
                    <div class="text-right">
                        <p class="text-[14px] font-bold"><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></p>
                        <p class="text-[12px] text-on-surface-variant">Quản trị viên</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="p-10 space-y-8 w-full">
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[14px] text-on-surface-variant">Tổng cộng: <span class="font-bold text-primary"><?php echo count($data['vouchers']); ?></span> mã khuyến mãi</p>
            </div>
            <a href="<?php echo URLROOT; ?>/admin/addPromotion" class="px-6 py-2.5 bg-primary text-white rounded-lg text-[14px] font-bold hover:bg-secondary transition-all flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">add</span> Thêm mã mới
            </a>
        </div>

        <!-- Alerts -->
        <?php if(isset($_GET['msg'])): ?>
            <div class="p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 text-[14px] flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <?php echo $_GET['msg']; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="p-4 bg-red-100 text-red-700 rounded-xl border border-red-200 text-[14px] flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <?php echo $_GET['error']; ?>
            </div>
        <?php endif; ?>

        <!-- Promotions Table -->
        <section class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-on-surface-variant text-[12px] font-bold uppercase tracking-wider border-b border-outline-variant bg-[#F8F9FB]">
                            <th class="px-8 py-4">MÃ CODE</th>
                            <th class="px-8 py-4">GIẢM GIÁ</th>
                            <th class="px-8 py-4">THỜI GIAN</th>
                            <th class="px-8 py-4 text-center">TRẠNG THÁI</th>
                            <th class="px-8 py-4 text-center">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <?php if(empty($data['vouchers'])): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-on-surface-variant">Không tìm thấy mã khuyến mãi nào.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($data['vouchers'] as $v): ?>
                            <tr class="hover:bg-[#F8F9FB] transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center border border-yellow-100">
                                            <span class="material-symbols-outlined text-yellow-600">confirmation_number</span>
                                        </div>
                                        <div>
                                            <span class="text-[14px] font-bold text-primary block"><?php echo $v['code']; ?></span>
                                            <span class="text-[11px] text-on-surface-variant line-clamp-1"><?php echo $v['description']; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-[14px] font-bold text-on-surface">
                                        <?php 
                                            if ($v['discount_percentage']) echo $v['discount_percentage'] . '%';
                                            if ($v['discount_percentage'] && $v['discount_amount']) echo ' + ';
                                            if ($v['discount_amount']) echo number_format($v['discount_amount'], 0, ',', '.') . 'đ';
                                        ?>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-[12px] text-on-surface">
                                        <p><span class="text-on-surface-variant">Từ:</span> <?php echo $v['start_date'] ? date('d/m/Y', strtotime($v['start_date'])) : 'N/A'; ?></p>
                                        <p><span class="text-on-surface-variant">Đến:</span> <?php echo $v['end_date'] ? date('d/m/Y', strtotime($v['end_date'])) : 'N/A'; ?></p>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <?php 
                                        $now = date('Y-m-d');
                                        $isActive = ($v['status'] == 1 && (!$v['end_date'] || $v['end_date'] >= $now));
                                    ?>
                                    <?php if($isActive): ?>
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-[11px] font-bold border border-green-200">ĐANG HOẠT ĐỘNG</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-[11px] font-bold border border-red-200">HẾT HẠN/TẮT</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?php echo URLROOT; ?>/admin/distributeVoucher/<?php echo $v['id']; ?>" 
                                           class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all" 
                                           title="Gửi cho người dùng">
                                            <span class="material-symbols-outlined text-[18px]">send</span>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/admin/editPromotion/<?php echo $v['id']; ?>" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-secondary hover:text-white hover:border-secondary transition-all" title="Chỉnh sửa">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <button onclick="confirmDelete(<?php echo $v['id']; ?>)" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-red-500 hover:text-white hover:border-red-500 transition-all" title="Xóa">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
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
