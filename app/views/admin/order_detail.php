<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area -->
<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <!-- Header -->
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-40 print:hidden">
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/orders" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
            </a>
            <h1 class="text-h2 font-bold text-primary">Chi tiết Đơn hàng #<?php echo $data['order']['id']; ?></h1>
        </div>
        
        <div class="flex items-center gap-8">
            <div class="flex items-center gap-4">
                <button onclick="exportInvoicePDF()" class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg text-[14px] font-bold hover:bg-red-600 hover:text-white transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span> Xuất PDF
                </button>
                <button onclick="window.print()" class="px-4 py-2 bg-white border border-outline-variant rounded-lg text-[14px] font-bold hover:bg-surface-container transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">print</span> In đơn hàng
                </button>
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

    <!-- Content -->
    <div class="p-10 space-y-8 w-full max-w-7xl mx-auto print:p-0 print:m-0 print:max-w-none">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 print:block">
            <!-- Left Column: Order details & Items -->
            <div class="lg:col-span-2 space-y-8 print:space-y-4">
                <!-- Order General Info -->
                <div class="bg-white p-8 rounded-2xl border border-outline-variant shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 print:border-0 print:shadow-none print:p-0">
                    <div class="space-y-1">
                        <p class="text-on-surface-variant text-[14px]">ID Đơn hàng</p>
                        <p class="text-[16px] font-bold text-primary">#<?php echo $data['order']['id']; ?></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-on-surface-variant text-[14px]">Mã Giao dịch (Casso)</p>
                        <div class="flex items-center gap-2">
                            <span class="text-[16px] font-bold text-primary font-mono bg-[#F3F4F6] px-2.5 py-1 rounded"><?php echo $data['order']['transaction_code']; ?></span>
                            <button onclick="copyToClipboard('<?php echo $data['order']['transaction_code']; ?>', this)" class="text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center p-1 rounded hover:bg-surface-container" title="Sao chép">
                                <span class="material-symbols-outlined text-[18px]">content_copy</span>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-on-surface-variant text-[14px]">Ngày đặt hàng</p>
                        <p class="text-[16px] font-bold text-primary"><?php echo $data['order']['formatted_date']; ?></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-on-surface-variant text-[14px]">Thanh toán</p>
                        <p class="text-[16px] font-bold text-primary"><?php echo $data['order']['payment_method']; ?></p>
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase border <?php echo $data['order']['payment_status'] === 'Paid' ? 'bg-green-50 text-green-600 border-green-200' : 'bg-red-50 text-red-600 border-red-200'; ?>">
                            <?php echo $data['order']['payment_status']; ?>
                        </span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-on-surface-variant text-[14px]">Trạng thái đơn hàng</p>
                        <?php 
                        $statusClasses = [
                            'delivered' => 'bg-green-100 text-green-700 border-green-200',
                            'pending' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'shipping' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'cancelled' => 'bg-red-100 text-red-700 border-red-200'
                        ];
                        $class = $statusClasses[$data['order']['order_status']] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                        ?>
                        <span class="inline-block px-3 py-1 rounded-full text-[12px] font-bold border <?php echo $class; ?>">
                            ● <?php echo $data['order']['status_text']; ?>
                        </span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden print:border-0 print:shadow-none">
                    <div class="px-8 py-6 border-b border-outline-variant print:px-0">
                        <h2 class="text-h3 font-bold">Sản phẩm trong đơn</h2>
                    </div>
                    <div class="p-8 space-y-6 print:px-0">
                        <?php foreach($data['items'] as $item): ?>
                        <div class="flex items-center justify-between gap-6 pb-6 border-b border-outline-variant last:border-0 last:pb-0">
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 bg-surface-container rounded-lg overflow-hidden flex-shrink-0 print:hidden">
                                    <img src="<?php echo get_product_image($item['image']); ?>" alt="<?php echo $item['name']; ?>" class="w-full h-full object-contain p-1" onerror="this.src='https://placehold.co/150x150?text=Product'">
                                </div>
                                <div>
                                    <h3 class="font-bold text-[16px] text-primary line-clamp-1"><?php echo $item['name']; ?></h3>
                                    <p class="text-on-surface-variant text-[14px] mt-1">Đơn giá: <?php echo number_format($item['price_at_purchase'], 0, ',', '.'); ?> đ</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[14px] text-on-surface-variant">SL: x<?php echo $item['quantity']; ?></p>
                                <p class="font-bold text-secondary mt-1"><?php echo number_format($item['price_at_purchase'] * $item['quantity'], 0, ',', '.'); ?> đ</p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Totals -->
                    <div class="bg-[#F8F9FB] p-8 border-t border-outline-variant space-y-3 print:bg-transparent print:px-0">
                        <div class="flex justify-between text-[14px] text-on-surface-variant">
                            <span>Tạm tính</span>
                            <span><?php echo $data['order']['formatted_subtotal']; ?> đ</span>
                        </div>
                        <div class="flex justify-between text-[14px] text-on-surface-variant">
                            <span>Phí vận chuyển</span>
                            <span>
                                <?php 
                                    if ($data['order']['shipping_fee'] == 0) {
                                        echo 'Miễn phí';
                                    } else {
                                        echo number_format($data['order']['shipping_fee'], 0, ',', '.') . ' đ';
                                    }
                                ?>
                            </span>
                        </div>
                        <?php if(!empty($data['order']['voucher_code'])): ?>
                        <div class="flex justify-between text-[14px] text-green-600 font-bold">
                            <span>Giảm giá (Mã: <?php echo $data['order']['voucher_code']; ?>)</span>
                            <span>-<?php echo number_format($data['order']['discount_amount'], 0, ',', '.'); ?> đ</span>
                        </div>
                        <?php endif; ?>
                        <div class="pt-3 mt-3 border-t border-outline-variant flex justify-between items-center">
                            <span class="font-bold text-[16px]">Tổng thanh toán</span>
                            <span class="font-bold text-[24px] text-error"><?php echo $data['order']['formatted_total']; ?> đ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Customer Info & Status Update -->
            <div class="space-y-8 print:space-y-4">
                <!-- Customer Info -->
                <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden print:border-0 print:shadow-none">
                    <div class="px-6 py-5 border-b border-outline-variant print:px-0">
                        <h2 class="text-h3 font-bold">Khách hàng</h2>
                    </div>
                    <div class="p-6 space-y-6 print:px-0">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center print:hidden">
                                <span class="material-symbols-outlined text-secondary">person</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-bold text-[16px]"><?php echo $data['order']['full_name']; ?></p>
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-secondary/10 text-secondary border border-secondary/20">U<?php echo $data['order']['user_id']; ?></span>
                                </div>
                                <p class="text-[14px] text-on-surface-variant"><?php echo $data['order']['email']; ?></p>
                            </div>
                        </div>
                        <div class="space-y-3 pt-6 border-t border-outline-variant print:pt-4">
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-on-surface-variant text-[20px] print:hidden">call</span>
                                <p class="text-[14px] font-medium"><?php echo $data['order']['phone']; ?></p>
                            </div>
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-on-surface-variant text-[20px] print:hidden">location_on</span>
                                <p class="text-[14px] font-medium leading-relaxed"><?php echo $data['order']['shipping_address']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Status Forms -->
                <div class="space-y-4 print:hidden">
                    <!-- Order Status -->
                    <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
                            <h2 class="text-[14px] font-bold">Trạng thái đơn hàng</h2>
                        </div>
                        <form action="<?php echo URLROOT; ?>/admin/updateOrderStatus/<?php echo $data['order']['id']; ?>" method="POST" class="p-6 space-y-4">
                            <?php echo csrf_field(); ?>
                            <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-white text-[14px] font-medium outline-none focus:ring-2 focus:ring-secondary/20 transition-all">
                                <option value="pending" <?php echo $data['order']['order_status'] === 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                                <option value="shipping" <?php echo $data['order']['order_status'] === 'shipping' ? 'selected' : ''; ?>>Đang giao hàng</option>
                                <option value="delivered" <?php echo $data['order']['order_status'] === 'delivered' ? 'selected' : ''; ?>>Đã giao thành công</option>
                                <option value="cancelled" <?php echo $data['order']['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                            </select>
                            <button type="submit" class="w-full bg-secondary hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span> Cập nhật đơn hàng
                            </button>
                        </form>
                    </div>

                    <!-- Payment Status -->
                    <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
                            <h2 class="text-[14px] font-bold">Trạng thái thanh toán</h2>
                        </div>
                        <form action="<?php echo URLROOT; ?>/admin/updatePaymentStatus/<?php echo $data['order']['id']; ?>" method="POST" class="p-6 space-y-4">
                            <?php echo csrf_field(); ?>
                            <select name="payment_status" class="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-white text-[14px] font-medium outline-none focus:ring-2 focus:ring-secondary/20 transition-all">
                                <option value="Pending" <?php echo $data['order']['payment_status'] === 'Pending' ? 'selected' : ''; ?>>Chưa thanh toán</option>
                                <option value="Paid" <?php echo $data['order']['payment_status'] === 'Paid' ? 'selected' : ''; ?>>Đã thanh toán</option>
                                <option value="Refunded" <?php echo $data['order']['payment_status'] === 'Refunded' ? 'selected' : ''; ?>>Đã hoàn tiền</option>
                            </select>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">payments</span> Cập nhật thanh toán
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function exportInvoicePDF() {
    const orderId = '<?php echo $data['order']['id']; ?>';
    window.open('<?php echo URLROOT; ?>/admin/orderPDF/' + orderId, '_blank');
}

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

<style type="text/css">
@media print {
    aside, header.print\:hidden, nav, .print\:hidden {
        display: none !important;
    }
    main {
        padding: 0 !important;
        margin: 0 !important;
        background: white !important;
    }
    .print\:p-0 {
        padding: 0 !important;
    }
    .print\:m-0 {
        margin: 0 !important;
    }
    .print\:border-0 {
        border: 0 !important;
    }
    .print\:shadow-none {
        shadow: none !important;
    }
}
</style>
</body>
</html>
