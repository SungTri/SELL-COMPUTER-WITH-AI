<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area -->
<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-40">
        <h1 class="text-h2 font-bold text-primary">Quản lý Danh mục</h1>
        
        <div class="flex items-center gap-8">
            <!-- Notifications -->
            <?php require_once VIEWS . '/layout/admin_notification.php'; ?>

            <div class="flex items-center gap-4 pl-6 border-l border-outline-variant">
                <img alt="Admin" class="w-10 h-10 rounded-full object-cover" src="<?php echo $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=Admin&background=0453cd&color=fff'; ?>"/>
                <div class="text-right">
                    <p class="text-[14px] font-bold"><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></p>
                    <p class="text-[12px] text-on-surface-variant">Quản trị viên</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="p-10 space-y-8 w-full">
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[14px] text-on-surface-variant">Tổng cộng: <span class="font-bold text-primary"><?php echo count($data['categories']); ?></span> danh mục</p>
            </div>
            <a href="<?php echo URLROOT; ?>/admin/addCategory" class="px-6 py-2.5 bg-primary text-white rounded-lg text-[14px] font-bold hover:bg-secondary transition-all flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">add</span> Thêm danh mục
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

        <!-- Categories Table -->
        <section class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-on-surface-variant text-[12px] font-bold uppercase tracking-wider border-b border-outline-variant bg-[#F8F9FB]">
                            <th class="px-8 py-4">TÊN DANH MỤC</th>
                            <th class="px-8 py-4">MÔ TẢ</th>
                            <th class="px-8 py-4 text-center">SẢN PHẨM</th>
                    <thead class="bg-surface-container-low text-on-surface-variant text-[12px] uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold text-left">Tên danh mục</th>
                            <th class="px-6 py-4 font-bold text-left">Danh mục cha</th>
                            <th class="px-6 py-4 font-bold text-left">Mô tả</th>
                            <th class="px-6 py-4 font-bold text-center">Sản phẩm</th>
                            <th class="px-6 py-4 font-bold text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <?php if(empty($data['categories'])): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-on-surface-variant">Không tìm thấy danh mục nào.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($data['categories'] as $cat): ?>
                            <tr id="category-row-<?php echo $cat['id']; ?>" class="hover:bg-surface-container-lowest transition-colors <?php echo $cat['parent_id'] ? 'bg-surface-container-lowest/50' : ''; ?>">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <?php if($cat['parent_id']): ?>
                                            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">subdirectory_arrow_right</span>
                                        <?php endif; ?>
                                        <span class="font-bold text-primary <?php echo $cat['parent_id'] ? 'text-[13px]' : 'text-[14px]'; ?>">
                                            <?php echo $cat['name']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($cat['parent_name']): ?>
                                        <span class="px-2 py-1 bg-secondary/10 text-secondary rounded text-[11px] font-bold">
                                            <?php echo $cat['parent_name']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-on-surface-variant text-[11px] italic">Danh mục chính</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-[13px] text-on-surface-variant max-w-xs truncate">
                                    <?php echo $cat['description'] ?: '---'; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 bg-surface-container text-on-surface rounded-full text-[12px] font-bold">
                                        <?php echo $cat['product_count']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?php echo URLROOT; ?>/admin/editCategory/<?php echo $cat['id']; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-secondary/10 text-secondary transition-all" title="Sửa">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <button onclick="confirmDelete(<?php echo $cat['id']; ?>, '<?php echo $cat['name']; ?>')" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-error/10 text-error transition-all" title="Xóa">
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
function confirmDelete(id, name) {
    if (confirm(`Bạn có chắc chắn muốn xóa danh mục "${name}"? Thao tác này không thể hoàn tác.`)) {
        const row = document.getElementById(`category-row-${id}`);
        
        fetch('<?php echo URLROOT; ?>/admin/deleteCategory/' + id, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text || 'Server error') });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                setTimeout(() => row.remove(), 500);
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa danh mục');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi khi xóa. Vui lòng thử lại. (Lưu ý: Không thể xóa danh mục đang chứa sản phẩm)');
        });
    }
}
</script>

</body>
</html>
