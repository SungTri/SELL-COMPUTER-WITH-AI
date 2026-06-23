<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-40">
        <h1 class="text-h2 font-bold text-primary">Giao dịch ngân hàng (Casso)</h1>
        
        <div class="flex items-center gap-8">
            <div class="px-4 py-2 bg-secondary/10 text-secondary rounded-lg text-[13px] font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] animate-spin">sync</span>
                Đang kết nối Real-time
            </div>
            
            <div class="flex items-center gap-5 border-l border-outline-variant pl-8 ml-2">
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
        </div>
    </header>

    <div class="p-10">
        <div class="bg-white rounded-3xl shadow-sm border border-outline-variant overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low border-b border-outline-variant">
                        <tr>
                            <th class="px-6 py-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Thời gian</th>
                            <th class="px-6 py-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Mã giao dịch</th>
                            <th class="px-6 py-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Số tiền</th>
                            <th class="px-6 py-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Nội dung</th>
                            <th class="px-6 py-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Đơn hàng</th>
                            <th class="px-6 py-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <?php if (empty($data['transactions'])): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl mb-2 block">history</span>
                                    Chưa có giao dịch nào được ghi nhận.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['transactions'] as $t): ?>
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="px-6 py-4 text-[14px]">
                                        <?php echo date('d/m/Y H:i:s', strtotime($t['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-[13px] font-mono text-on-surface-variant">
                                        <?php echo $t['transaction_id']; ?>
                                    </td>
                                    <td class="px-6 py-4 text-[14px] font-bold text-green-600">
                                        +<?php echo number_format($t['amount'], 0, ',', '.'); ?>đ
                                    </td>
                                    <td class="px-6 py-4 text-[13px] max-w-xs truncate">
                                        <?php echo htmlspecialchars($t['description']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($t['order_id']): ?>
                                            <a href="<?php echo URLROOT; ?>/admin/orderDetail/<?php echo $t['order_id']; ?>" class="px-3 py-1 bg-primary/10 text-primary rounded-full text-[12px] font-bold hover:bg-primary/20 transition-all">
                                                #<?php echo $t['order_id']; ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-on-surface-variant text-[12px]">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php 
                                            $statusClass = '';
                                            switch($t['status']) {
                                                case 'Success': $statusClass = 'bg-green-100 text-green-700'; break;
                                                case 'Order Not Found': $statusClass = 'bg-yellow-100 text-yellow-700'; break;
                                                default: $statusClass = 'bg-red-100 text-red-700';
                                            }
                                        ?>
                                        <span class="px-3 py-1 rounded-full text-[11px] font-bold <?php echo $statusClass; ?>">
                                            <?php echo $t['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

</body>
</html>
