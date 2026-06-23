<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area -->
<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#f8fafc] dark:bg-zinc-950 transition-colors duration-200">
    <!-- Header -->
    <header class="h-20 bg-white dark:bg-zinc-900 border-b border-slate-200 dark:border-zinc-800/80 flex items-center justify-between px-10 sticky top-0 z-40 transition-colors duration-200">
        <h1 class="text-h2 font-bold text-slate-800 dark:text-white">Quản lý Đơn hàng</h1>
        
        <div class="flex items-center gap-8">
            <form method="GET" action="<?php echo URLROOT; ?>/admin/orders" class="w-96 flex items-center bg-slate-100 dark:bg-zinc-950 border border-slate-200/50 dark:border-zinc-800/80 rounded-xl px-4 py-2 focus-within:ring-2 focus-within:ring-indigo-500/20 transition-all">
                <?php if(!empty($data['filters']['status'])): ?>
                    <input type="hidden" name="status" value="<?php echo $data['filters']['status']; ?>">
                <?php endif; ?>
                <span class="material-symbols-outlined text-slate-400 dark:text-zinc-500 text-[20px] select-none flex-shrink-0">search</span>
                <input name="search" value="<?php echo $data['filters']['search']; ?>" class="w-full bg-transparent border-none focus:ring-0 text-[13px] font-medium text-slate-700 dark:text-zinc-200 placeholder:text-slate-400/70 ml-2 py-0" placeholder="Tìm tên khách, mã đơn..." type="text"/>
            </form>
            <div class="flex items-center gap-5 border-l border-slate-200 dark:border-zinc-850 pl-8 ml-2">
                <!-- Notifications -->
                <?php require_once VIEWS . '/layout/admin_notification.php'; ?>
                
                <div class="flex items-center gap-4 pl-8 border-l border-slate-200 dark:border-zinc-850">
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
        
        <!-- Filters -->
        <div class="flex items-center justify-between">
            <div class="flex gap-1.5 bg-slate-100 dark:bg-zinc-950 p-1 rounded-xl border border-slate-200 dark:border-zinc-800/80">
                <?php 
                $currentStatus = $data['filters']['status'];
                $searchQuery = !empty($data['filters']['search']) ? '&search=' . $data['filters']['search'] : '';
                
                $filters = [
                    '' => 'Tất cả',
                    'pending' => 'Chờ xử lý',
                    'shipping' => 'Đang giao',
                    'delivered' => 'Hoàn thành',
                    'cancelled' => 'Đã hủy'
                ];
                
                foreach ($filters as $val => $label):
                    $isActive = $currentStatus === $val;
                    $classes = $isActive 
                        ? 'bg-white dark:bg-zinc-900 text-indigo-600 dark:text-indigo-400 shadow-sm border border-slate-200/20 dark:border-zinc-800/60 font-bold' 
                        : 'text-slate-500 dark:text-zinc-400 hover:text-slate-800 dark:hover:text-zinc-200 font-semibold';
                ?>
                <a href="<?php echo URLROOT; ?>/admin/orders?status=<?php echo $val . $searchQuery; ?>" class="px-4 py-2 rounded-lg text-[13px] transition-all <?php echo $classes; ?>">
                    <?php echo $label; ?>
                </a>
                <?php endforeach; ?>
            </div>
            <a href="<?php echo URLROOT; ?>/admin/exportOrders?status=<?php echo $data['filters']['status']; ?>&search=<?php echo urlencode($data['filters']['search']); ?>" class="px-4 py-2 bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-900/50 hover:bg-green-600 hover:text-white dark:hover:bg-green-500 dark:hover:text-white rounded-xl text-[13px] font-bold transition-all flex items-center gap-2 shadow-sm active:scale-[0.98]">
                <span class="material-symbols-outlined text-[18px]">download</span>
                <span>Xuất danh sách</span>
            </a>
        </div>

        <!-- Orders Table -->
        <section class="table-card rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 dark:text-zinc-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/60">
                            <th class="px-8 py-4">ID</th>
                            <th class="px-8 py-4">MÃ NGƯỜI DÙNG</th>
                            <th class="px-8 py-4">MÃ GIAO DỊCH</th>
                            <th class="px-8 py-4">KHÁCH HÀNG</th>
                            <th class="px-8 py-4">NGÀY ĐẶT</th>
                            <th class="px-8 py-4">TỔNG TIỀN</th>
                            <th class="px-8 py-4">THANH TOÁN</th>
                            <th class="px-8 py-4">TRẠNG THÁI</th>
                            <th class="px-8 py-4 text-center">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        <?php if(empty($data['orders'])): ?>
                        <tr>
                            <td colspan="9" class="px-8 py-10 text-center text-slate-400 dark:text-zinc-500">Không tìm thấy đơn hàng nào phù hợp.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($data['orders'] as $order): ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-950/20 transition-colors group">
                                <td class="px-8 py-5 font-bold text-slate-800 dark:text-white">#<?php echo $order['raw_id']; ?></td>
                                <td class="px-8 py-5 font-bold text-indigo-600 dark:text-indigo-400 font-mono text-[13px]">U<?php echo $order['user_id']; ?></td>
                                <td class="px-8 py-5 font-mono text-[13px] text-slate-600 dark:text-zinc-300">
                                    <div class="flex items-center gap-2">
                                        <span><?php echo $order['transaction_code']; ?></span>
                                        <button onclick="copyToClipboard('<?php echo $order['transaction_code']; ?>', this)" class="text-slate-400 hover:text-slate-700 dark:text-zinc-500 dark:hover:text-zinc-300 transition-colors flex items-center justify-center p-1 rounded hover:bg-slate-100 dark:hover:bg-zinc-800 print:hidden" title="Sao chép">
                                            <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100/30 dark:border-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-[11px] shrink-0">
                                            <?php 
                                            $parts = explode(' ', $order['customer']);
                                            echo mb_substr($parts[0], 0, 1) . (count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '');
                                            ?>
                                        </div>
                                        <span class="text-[14px] font-semibold text-slate-700 dark:text-zinc-300"><?php echo $order['customer']; ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-slate-400 dark:text-zinc-500 text-[13px]"><?php echo $order['date']; ?></td>
                                <td class="px-8 py-5 font-extrabold text-indigo-600 dark:text-indigo-400 text-[14px] whitespace-nowrap"><?php echo $order['total']; ?> đ</td>
                                <td class="px-8 py-5 text-slate-500 dark:text-zinc-300 text-[13px] font-medium"><?php echo $order['payment_method']; ?></td>
                                <td class="px-8 py-5">
                                    <?php 
                                    $badgeClass = 'badge-pending';
                                    if ($order['status'] === 'delivered') $badgeClass = 'badge-delivered';
                                    elseif ($order['status'] === 'shipping') $badgeClass = 'badge-shipping';
                                    elseif ($order['status'] === 'cancelled') $badgeClass = 'badge-cancelled';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold border uppercase tracking-wider <?php echo $badgeClass; ?>">
                                        <?php echo $order['status_text']; ?>
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <a href="<?php echo URLROOT; ?>/admin/orderDetail/<?php echo $order['raw_id']; ?>" class="inline-flex items-center justify-center px-4 py-2 border border-slate-200 dark:border-zinc-800 rounded-xl text-[13px] font-bold text-slate-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white dark:hover:text-white hover:border-indigo-600 dark:hover:border-indigo-500 transition-all shadow-sm">
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($data['pagination']['total_pages'] > 1): ?>
            <div class="px-8 py-5 border-t border-outline-variant flex items-center justify-between bg-white">
                <span class="text-[14px] text-on-surface-variant">Hiển thị <?php echo $data['pagination']['start_record']; ?> - <?php echo $data['pagination']['end_record']; ?> trên tổng số <?php echo $data['pagination']['total_records']; ?> đơn hàng</span>
                <div class="flex gap-2">
                    <?php 
                    $pageParam = '?';
                    if (!empty($data['filters']['status'])) $pageParam .= 'status=' . $data['filters']['status'] . '&';
                    if (!empty($data['filters']['search'])) $pageParam .= 'search=' . $data['filters']['search'] . '&';
                    $pageParam .= 'page=';
                    ?>
                    
                    <!-- Prev Button -->
                    <?php if($data['pagination']['current_page'] > 1): ?>
                        <a href="<?php echo URLROOT; ?>/admin/orders<?php echo $pageParam . ($data['pagination']['current_page'] - 1); ?>" class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </a>
                    <?php else: ?>
                        <button disabled class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center opacity-50 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </button>
                    <?php endif; ?>
                    
                    <!-- Pages -->
                    <?php for($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                        <?php if($i == $data['pagination']['current_page']): ?>
                            <button class="w-8 h-8 rounded bg-primary text-white font-bold text-[14px]"><?php echo $i; ?></button>
                        <?php else: ?>
                            <a href="<?php echo URLROOT; ?>/admin/orders<?php echo $pageParam . $i; ?>" class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center font-bold text-[14px] hover:bg-surface-container transition-colors">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <!-- Next Button -->
                    <?php if($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                        <a href="<?php echo URLROOT; ?>/admin/orders<?php echo $pageParam . ($data['pagination']['current_page'] + 1); ?>" class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                    <?php else: ?>
                        <button disabled class="w-8 h-8 rounded border border-outline-variant flex items-center justify-center opacity-50 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
                <?php if ($data['pagination']['total_records'] > 0): ?>
                <div class="px-8 py-5 border-t border-outline-variant flex items-center justify-between bg-white">
                    <span class="text-[14px] text-on-surface-variant">Hiển thị <?php echo $data['pagination']['start_record']; ?> - <?php echo $data['pagination']['end_record']; ?> trên tổng số <?php echo $data['pagination']['total_records']; ?> đơn hàng</span>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</main>
<script>
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const icon = btn.querySelector('.material-symbols-outlined');
        const originalText = icon.textContent;
        icon.textContent = 'done';
        icon.classList.add('text-green-600');
        setTimeout(() => {
            icon.textContent = originalText;
            icon.classList.remove('text-green-600');
        }, 1500);
    }).catch(err => {
        console.error('Could not copy text: ', err);
    });
}
</script>
</body>
</html>
