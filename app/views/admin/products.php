<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area -->
<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#f8fafc] dark:bg-zinc-950 transition-colors duration-200">
    <!-- Header -->
    <header class="h-20 bg-white dark:bg-zinc-900 border-b border-slate-200 dark:border-zinc-800/80 flex items-center justify-between px-10 sticky top-0 z-40 transition-colors duration-200">
        <h1 class="text-h2 font-bold text-slate-800 dark:text-white">Quản lý Sản phẩm</h1>
        
        <div class="flex items-center gap-6">
            <form method="GET" action="<?php echo URLROOT; ?>/admin/products" class="relative w-96">
                <?php if(!empty($data['filters']['category'])): ?>
                    <input type="hidden" name="category" value="<?php echo $data['filters']['category']; ?>">
                <?php endif; ?>
                <?php if(!empty($data['filters']['brand'])): ?>
                    <input type="hidden" name="brand" value="<?php echo $data['filters']['brand']; ?>">
                <?php endif; ?>
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500">search</span>
                <input name="search" value="<?php echo $data['filters']['search']; ?>" class="w-full pl-12 pr-4 py-2.5 bg-slate-100 dark:bg-zinc-950 border border-slate-200/50 dark:border-zinc-800 rounded-xl text-body-md focus:ring-2 focus:ring-indigo-500/20 dark:focus:ring-indigo-500/20 transition-all outline-none" placeholder="Tìm tên sản phẩm, mã ID..." type="text"/>
            </form>
            <div class="flex items-center gap-4 border-l border-slate-200 dark:border-zinc-850 pl-6">
                <!-- Notifications -->
                <?php require_once VIEWS . '/layout/admin_notification.php'; ?>

                <div class="flex items-center gap-3 pl-6 border-l border-slate-200 dark:border-zinc-850">
                    <div class="text-right">
                        <p class="text-[14px] font-bold text-slate-800 dark:text-white"><?php echo $_SESSION['user_name'] ?? 'Admin TechExpert'; ?></p>
                        <p class="text-[12px] text-slate-400 dark:text-zinc-500 font-medium">Quản trị viên</p>
                    </div>
                    <div class="relative">
                        <img alt="Admin" class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-500/10" src="<?php echo $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff'; ?>"/>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-zinc-900 rounded-full animate-pulse"></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="p-10 space-y-8 w-full">
        <!-- Actions & Filters -->
        <div class="flex flex-col gap-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <form action="<?php echo URLROOT; ?>/admin/products" method="GET" id="filterForm" class="flex items-center gap-4">
                        <!-- Search (Duplicate in form for sync) -->
                        <div class="relative w-72">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 text-[20px]">search</span>
                            <input name="search" value="<?php echo $data['filters']['search']; ?>" class="w-full pl-11 pr-4 py-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl text-[14px] focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all" placeholder="Tìm tên, mã..." type="text"/>
                        </div>

                        <!-- Category Filter -->
                        <div class="w-48">
                            <select name="category" onchange="this.form.submit()" class="w-full px-4 py-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl text-[14px] focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all cursor-pointer">
                                <option value="">Tất cả danh mục</option>
                                <?php foreach($data['categories'] as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo $data['filters']['category'] == $cat['id'] ? 'selected' : ''; ?>>
                                        <?php echo $cat['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Brand Filter -->
                        <div class="w-48">
                            <select name="brand" onchange="this.form.submit()" class="w-full px-4 py-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl text-[14px] focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all cursor-pointer">
                                <option value="">Tất cả thương hiệu</option>
                                <?php foreach($data['brands'] as $brand): ?>
                                    <option value="<?php echo $brand['id']; ?>" <?php echo $data['filters']['brand'] == $brand['id'] ? 'selected' : ''; ?>>
                                        <?php echo $brand['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if(!empty($data['filters']['search']) || !empty($data['filters']['category']) || !empty($data['filters']['brand'])): ?>
                            <a href="<?php echo URLROOT; ?>/admin/products" class="inline-flex items-center gap-2 text-slate-400 dark:text-zinc-500 hover:text-rose-500 dark:hover:text-rose-450 transition-colors text-[13px] font-medium">
                                <span class="material-symbols-outlined text-[18px]">filter_alt_off</span> Xóa lọc
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <a href="<?php echo URLROOT; ?>/admin/addProduct" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white rounded-xl text-[13px] font-bold hover:shadow-lg active:scale-[0.98] transition-all flex items-center gap-2 shadow-md shadow-indigo-600/10">
                    <span class="material-symbols-outlined text-[20px]">add</span> Thêm sản phẩm
                </a>
            </div>
        </div>

        <!-- Product Table -->
        <section class="table-card rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 dark:text-zinc-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/60">
                            <th class="px-8 py-4">SẢN PHẨM</th>
                            <th class="px-8 py-4">DANH MỤC</th>
                            <th class="px-8 py-4 text-right">GIÁ BÁN</th>
                            <th class="px-8 py-4 text-center">TỒN KHO</th>
                            <th class="px-8 py-4 text-center">TRẠNG THÁI</th>
                            <th class="px-8 py-4 text-center">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <?php if(empty($data['products'])): ?>
                        <tr>
                            <td colspan="6" class="px-8 py-10 text-center text-on-surface-variant">Không tìm thấy sản phẩm nào.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($data['products'] as $product): ?>
                            <tr id="product-row-<?php echo $product['id']; ?>" class="hover:bg-slate-50/50 dark:hover:bg-zinc-950/20 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-white dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 p-1 flex-shrink-0 flex items-center justify-center shadow-sm">
                                            <img src="<?php echo get_product_image($product['image']); ?>" alt="" class="w-full h-full object-contain rounded-lg" onerror="this.src='https://placehold.co/150x150?text=Product'" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[14px] font-bold text-slate-800 dark:text-zinc-200 truncate max-w-[300px]" title="<?php echo $product['name']; ?>">
                                                <?php echo $product['name']; ?>
                                            </p>
                                            <p class="text-[11px] text-slate-400 dark:text-zinc-500 mt-0.5">Mã: #<?php echo $product['id']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-[14px] font-semibold text-slate-700 dark:text-zinc-300"><?php echo $product['category']; ?></span>
                                    <p class="text-[12px] text-slate-400 dark:text-zinc-500"><?php echo $product['brand']; ?></p>
                                </td>
                                <td class="px-8 py-5 text-right font-extrabold text-indigo-600 dark:text-indigo-400 text-[14px] whitespace-nowrap">
                                    <?php echo $product['price']; ?> đ
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <?php 
                                    $stock = $product['stock'];
                                    $stockClass = $stock < 5 
                                        ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-100/50 dark:border-rose-900/30 font-bold px-2 py-0.5 rounded-lg text-xs' 
                                        : 'text-slate-700 dark:text-zinc-300 font-medium';
                                    ?>
                                    <span class="text-[14px] <?php echo $stockClass; ?>"><?php echo $stock; ?></span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <?php if($product['status'] == 1): ?>
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-100/50 dark:border-emerald-900/30 whitespace-nowrap uppercase tracking-wider">
                                            Đang bán
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-100/50 dark:border-rose-900/30 whitespace-nowrap uppercase tracking-wider">
                                            Ngừng bán
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?php echo URLROOT; ?>/admin/editProduct/<?php echo $product['id']; ?>" class="w-8 h-8 flex items-center justify-center border border-slate-200 dark:border-zinc-800 rounded-lg text-slate-500 dark:text-zinc-400 hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white dark:hover:text-white hover:border-indigo-600 dark:hover:border-indigo-500 transition-all shadow-sm" title="Chỉnh sửa">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <button onclick="confirmDelete(<?php echo $product['id']; ?>)" class="w-8 h-8 flex items-center justify-center border border-slate-200 dark:border-zinc-800 rounded-lg text-slate-500 dark:text-zinc-400 hover:bg-rose-600 dark:hover:bg-rose-500 hover:text-white dark:hover:text-white hover:border-rose-600 dark:hover:border-rose-500 transition-all shadow-sm" title="Xóa">
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

            <!-- Pagination -->
            <?php if ($data['pagination']['total_pages'] > 1): ?>
            <div class="px-8 py-5 border-t border-outline-variant flex flex-col md:flex-row items-center justify-between gap-4 bg-white">
                <span class="text-[14px] text-on-surface-variant whitespace-nowrap flex-shrink-0">Hiển thị <?php echo $data['pagination']['start_record']; ?> - <?php echo $data['pagination']['end_record']; ?> trên tổng số <?php echo $data['pagination']['total_records']; ?> sản phẩm</span>
                <div class="flex flex-wrap gap-2 items-center justify-center">
                    <?php 
                    $pageParam = '?';
                    if (!empty($data['filters']['category'])) $pageParam .= 'category=' . $data['filters']['category'] . '&';
                    if (!empty($data['filters']['search'])) $pageParam .= 'search=' . $data['filters']['search'] . '&';
                    $pageParam .= 'page=';
                    ?>
                    
                    <?php if($data['pagination']['current_page'] > 1): ?>
                        <a href="<?php echo URLROOT; ?>/admin/products<?php echo $pageParam . ($data['pagination']['current_page'] - 1); ?>" class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center hover:bg-surface-container transition-colors flex-shrink-0">
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
                                echo '<a href="' . URLROOT . '/admin/products' . $pageParam . $i . '" class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center font-bold text-[14px] hover:bg-surface-container transition-colors flex-shrink-0">' . $i . '</a>';
                            }
                        } elseif ($i == 2 && $currentPage - $range > 2) {
                            echo '<span class="w-8 h-8 flex items-center justify-center text-on-surface-variant font-bold text-[14px] flex-shrink-0">...</span>';
                        } elseif ($i == $totalPages - 1 && $currentPage + $range < $totalPages - 1) {
                            echo '<span class="w-8 h-8 flex items-center justify-center text-on-surface-variant font-bold text-[14px] flex-shrink-0">...</span>';
                        }
                    }
                    ?>
                    
                    <?php if($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                        <a href="<?php echo URLROOT; ?>/admin/products<?php echo $pageParam . ($data['pagination']['current_page'] + 1); ?>" class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center hover:bg-surface-container transition-colors flex-shrink-0">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Thao tác này không thể hoàn tác.')) {
        // Find the row immediately
        const row = document.getElementById(`product-row-${id}`);
        
        fetch('<?php echo URLROOT; ?>/admin/deleteProduct/' + id + '?confirm=1', {
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
                // Smoothly remove the row
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                
                setTimeout(() => {
                    row.remove();
                    
                    const totalSpan = document.querySelector('.text-primary.font-bold');
                    if (totalSpan) {
                        const currentCount = parseInt(totalSpan.innerText);
                        if (!isNaN(currentCount)) totalSpan.innerText = currentCount - 1;
                    }
                }, 500);
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa sản phẩm');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi khi xóa. Vui lòng thử lại. (Lưu ý: Không thể xóa sản phẩm đã có trong đơn hàng)');
        });
    }
}
</script>

</body>
</html>
