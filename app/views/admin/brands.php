<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-40">
        <h1 class="text-h2 font-bold text-primary">Quản lý thương hiệu</h1>
        
        <div class="flex items-center gap-6">
            <form action="<?php echo URLROOT; ?>/admin/brands" method="GET" class="w-80 md:w-96 flex items-center bg-[#F3F4F6] dark:bg-zinc-950 border border-transparent dark:border-zinc-800 rounded-xl px-4 py-2 focus-within:ring-2 focus-within:ring-secondary/20 transition-all relative">
                <span class="material-symbols-outlined text-on-surface-variant text-[20px] select-none flex-shrink-0">search</span>
                <input id="brand-search-input" name="search" value="<?php echo htmlspecialchars($data['filters']['search'] ?? ''); ?>" class="w-full bg-transparent border-none focus:ring-0 text-[13px] font-medium text-slate-700 dark:text-zinc-200 placeholder:text-on-surface-variant/60 ml-2 pr-6 py-0" placeholder="Tìm tên thương hiệu, mô tả..." type="text"/>
                <?php if(!empty($data['filters']['search'])): ?>
                    <a href="<?php echo URLROOT; ?>/admin/brands" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-error transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </a>
                <?php endif; ?>
            </form>
            <a href="<?php echo URLROOT; ?>/admin/addBrand" class="px-6 py-2.5 bg-primary text-white rounded-lg text-[14px] font-bold hover:bg-secondary transition-all flex items-center gap-2 shadow-md">
                <span class="material-symbols-outlined text-[20px]">add</span> Thêm thương hiệu
            </a>
        </div>
    </header>

    <div class="p-10">
        <?php if(isset($_GET['msg'])): ?>
            <div class="mb-6 p-4 bg-green-50 text-green-600 rounded-xl border border-green-100 text-[14px] flex items-center gap-2 animate-fade-in">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <?php echo $_GET['msg']; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-xl border border-red-100 text-[14px] flex items-center gap-2 animate-fade-in">
                <span class="material-symbols-outlined text-[20px]">error</span>
                <?php echo $_GET['error']; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-low text-on-surface-variant text-[12px] uppercase tracking-wider">
                    <tr>
                        <th class="px-8 py-4">Thương hiệu</th>
                        <th class="px-8 py-4">Mô tả</th>
                        <th class="px-8 py-4 text-center">Số sản phẩm</th>
                        <th class="px-8 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    <?php if(empty($data['brands'])): ?>
                    <tr>
                        <td colspan="4" class="px-8 py-10 text-center text-on-surface-variant">Không tìm thấy thương hiệu nào.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($data['brands'] as $brand): ?>
                        <tr id="brand-row-<?php echo $brand['id']; ?>" class="hover:bg-surface-container-lowest transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-surface-container-low border border-outline-variant flex items-center justify-center overflow-hidden">
                                        <?php if($brand['logo']): ?>
                                            <img src="<?php echo $brand['logo']; ?>" alt="<?php echo $brand['name']; ?>" class="w-full h-full object-contain p-1">
                                        <?php else: ?>
                                            <span class="material-symbols-outlined text-on-surface-variant">branding_watermark</span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-[14px] font-bold text-primary"><?php echo $brand['name']; ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-[13px] text-on-surface-variant line-clamp-1 max-w-md">
                                    <?php echo $brand['description'] ?: 'Không có mô tả'; ?>
                                </p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-surface-container text-on-surface text-[12px] font-bold">
                                    <?php echo $brand['product_count']; ?> sản phẩm
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo URLROOT; ?>/admin/editBrand/<?php echo $brand['id']; ?>" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-secondary/10 text-secondary transition-all" title="Chỉnh sửa">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <button onclick="confirmDelete(<?php echo $brand['id']; ?>, '<?php echo $brand['name']; ?>')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-error/10 text-error transition-all" title="Xóa">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($data['pagination']['total_pages'] > 1): ?>
            <div class="px-8 py-5 border-t border-outline-variant flex flex-col md:flex-row items-center justify-between gap-4 bg-white">
                <span class="text-[14px] text-on-surface-variant whitespace-nowrap flex-shrink-0">Hiển thị <?php echo $data['pagination']['start_record']; ?> - <?php echo $data['pagination']['end_record']; ?> trên tổng số <?php echo $data['pagination']['total_records']; ?> thương hiệu</span>
                <div class="flex flex-wrap gap-2 items-center justify-center">
                    <?php 
                    $pageParam = '?';
                    if (!empty($data['filters']['search'])) $pageParam .= 'search=' . urlencode($data['filters']['search']) . '&';
                    $pageParam .= 'page=';
                    ?>
                    
                    <?php if($data['pagination']['current_page'] > 1): ?>
                        <a href="<?php echo URLROOT; ?>/admin/brands<?php echo $pageParam . ($data['pagination']['current_page'] - 1); ?>" class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center hover:bg-surface-container transition-colors flex-shrink-0">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php 
                    $totalPages = $data['pagination']['total_pages'];
                    $currentPage = $data['pagination']['current_page'];
                    $range = 2; // Số trang hiển thị xung quanh trang hiện tại
                    
                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $range && $i <= $currentPage + $range)) {
                            if ($i == $currentPage) {
                                echo '<button class="w-8 h-8 rounded bg-primary text-white font-bold text-[14px] flex-shrink-0">' . $i . '</button>';
                            } else {
                                echo '<a href="' . URLROOT . '/admin/brands' . $pageParam . $i . '" class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center font-bold text-[14px] hover:bg-surface-container transition-colors flex-shrink-0">' . $i . '</a>';
                            }
                        } elseif ($i == 2 && $currentPage - $range > 2) {
                            echo '<span class="w-8 h-8 flex items-center justify-center text-on-surface-variant font-bold text-[14px] flex-shrink-0">...</span>';
                        } elseif ($i == $totalPages - 1 && $currentPage + $range < $totalPages - 1) {
                            echo '<span class="w-8 h-8 flex items-center justify-center text-on-surface-variant font-bold text-[14px] flex-shrink-0">...</span>';
                        }
                    }
                    ?>
                    
                    <?php if($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                        <a href="<?php echo URLROOT; ?>/admin/brands<?php echo $pageParam . ($data['pagination']['current_page'] + 1); ?>" class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center hover:bg-surface-container transition-colors flex-shrink-0">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
function confirmDelete(id, name) {
    if (confirm(`Bạn có chắc chắn muốn xóa thương hiệu "${name}"? Thao tác này không thể hoàn tác.`)) {
        const row = document.getElementById(`brand-row-${id}`);
        
        fetch('<?php echo URLROOT; ?>/admin/deleteBrand/' + id, {
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
                alert(data.message || 'Có lỗi xảy ra khi xóa thương hiệu');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi khi xóa. Vui lòng thử lại. (Lưu ý: Không thể xóa thương hiệu đang có sản phẩm)');
        });
    }
}

// Search Form Auto-submit when cleared
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('brand-search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (this.value.trim() === '') {
                this.form.submit();
            }
        });
    }
});
</script>

</body>
</html>
