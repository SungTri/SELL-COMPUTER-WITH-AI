<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="mt-12 max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    <!-- Back Button & Title -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/user/profile?tab=orders" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
            </a>
            <div>
                <h1 class="text-h2 font-bold text-on-surface"><?php echo __('order_detail_title', 'Chi tiết đơn hàng'); ?> #<?php echo $data['order']['id']; ?></h1>
                <p class="text-outline text-sm"><?php echo __('order_placed_on', 'Đặt ngày'); ?> <?php echo $data['order']['formatted_date']; ?></p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative z-50">
            <?php if(strtolower($data['order']['order_status']) == 'pending'): ?>
            <form id="cancelOrderForm" action="<?php echo URLROOT; ?>/user/cancelOrder/<?php echo $data['order']['id']; ?>" method="POST" class="m-0 p-0">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="px-6 py-2 bg-error text-white rounded-lg text-sm font-label-bold hover:bg-error/90 shadow-lg shadow-error/20 transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined text-lg">cancel</span> <?php echo __('cancel_order_btn', 'Hủy đơn hàng'); ?>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Feedback Messages -->
    <?php if(isset($_GET['success']) && $_GET['success'] == 'cancelled'): ?>
    <div class="p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-700 animate-in fade-in slide-in-from-top-2">
        <span class="material-symbols-outlined">check_circle</span>
        <p class="text-sm font-medium"><?php echo __('cancel_order_success', 'Hủy đơn hàng thành công!'); ?></p>
    </div>
    <?php endif; ?>

    <?php if(isset($_GET['error']) && $_GET['error'] == 'cancel_failed'): ?>
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 text-red-700 animate-in fade-in slide-in-from-top-2">
        <span class="material-symbols-outlined">error</span>
        <p class="text-sm font-medium"><?php echo __('cancel_order_failed', 'Không thể hủy đơn hàng này. Vui lòng kiểm tra lại trạng thái đơn hàng.'); ?></p>
    </div>
    <?php endif; ?>

    <?php if(isset($_GET['error']) && $_GET['error'] == 'payment_failed'): ?>
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 text-red-700 animate-in fade-in slide-in-from-top-2">
        <span class="material-symbols-outlined">error</span>
        <p class="text-sm font-medium"><strong><?php echo __('payment_failed', 'Thanh toán thất bại!'); ?></strong> <?php echo __('payment_failed_detail', 'Giao dịch chuyển khoản chưa được hoàn thành hoặc đã bị hủy.'); ?></p>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Left: Order Items & Summary -->
        <div class="md:col-span-2 space-y-6">
            <!-- Items List -->
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="p-6 border-b border-outline-variant">
                    <h3 class="font-label-bold text-on-surface"><?php echo __('purchased_products', 'Sản phẩm đã mua'); ?></h3>
                </div>
                <div class="divide-y divide-outline-variant">
                    <?php foreach($data['items'] as $item): ?>
                    <div class="p-6 flex gap-6">
                        <div class="w-20 h-20 bg-white rounded-lg border border-outline-variant p-2 flex-shrink-0">
                            <img src="<?php echo get_product_image($item['image']); ?>" alt="<?php echo $item['name']; ?>" class="w-full h-full object-contain" onerror="this.src='https://placehold.co/150x150?text=Product'" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-label-bold text-on-surface line-clamp-1"><?php echo $item['name']; ?></h4>
                            <p class="text-sm text-outline mt-1"><?php echo __('quantity_label', 'Số lượng'); ?>: <?php echo $item['quantity']; ?></p>
                            <p class="font-price-display text-secondary font-bold mt-2"><?php echo number_format($item['price_at_purchase'], 0, ',', '.'); ?>đ</p>
                        </div>
                        <div class="text-right">
                            <p class="font-price-display text-on-surface font-bold"><?php echo number_format($item['price_at_purchase'] * $item['quantity'], 0, ',', '.'); ?>đ</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Subtotal Section -->
                <div class="bg-surface-container-low p-6 space-y-3 border-t border-outline-variant">
                    <div class="flex justify-between text-sm text-on-surface-variant">
                        <span><?php echo __('subtotal', 'Tạm tính'); ?></span>
                        <span><?php echo $data['order']['formatted_subtotal']; ?>đ</span>
                    </div>
                    <div class="flex justify-between text-sm text-on-surface-variant">
                        <span><?php echo __('shipping_fee', 'Phí vận chuyển'); ?></span>
                        <span>
                            <?php 
                                if ($data['order']['shipping_fee'] == 0) {
                                    echo __('free_shipping', 'Miễn phí');
                                } else {
                                    echo number_format($data['order']['shipping_fee'], 0, ',', '.') . 'đ';
                                }
                            ?>
                        </span>
                    </div>
                    <?php if ($data['order']['discount_amount'] > 0): ?>
                    <div class="flex justify-between text-sm text-green-600 font-bold">
                        <span><?php echo __('discount', 'Giảm giá'); ?></span>
                        <span>-<?php echo number_format($data['order']['discount_amount'], 0, ',', '.'); ?>đ</span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-lg font-bold text-on-surface pt-3 border-t border-outline-variant/50">
                        <span><?php echo __('total_payment', 'Tổng thanh toán'); ?></span>
                        <span class="text-secondary"><?php echo $data['order']['formatted_total']; ?>đ</span>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                <h3 class="font-label-bold text-on-surface mb-4"><?php echo __('payment_method', 'Phương thức thanh toán'); ?></h3>
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/10 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-secondary">payments</span>
                        </div>
                        <div>
                            <p class="font-label-bold text-on-surface"><?php echo $data['order']['payment_method']; ?></p>
                            <p class="text-sm text-outline"><?php echo $data['order']['payment_status_text']; ?></p>
                        </div>
                    </div>
                    <?php if (strtoupper($data['order']['payment_method']) === 'BANKING' && strtolower($data['order']['payment_status']) === 'pending' && strtolower($data['order']['order_status']) !== 'cancelled'): ?>
                    <a href="<?php echo URLROOT; ?>/checkout/success?order_id=<?php echo $data['order']['id']; ?>" class="px-5 py-2 bg-secondary text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-colors shadow-md shadow-secondary/15 flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                        <?php echo __('checkout_now', 'Thanh toán ngay'); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right: Status & Shipping -->
        <div class="space-y-6">
            <!-- Order Status -->
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                <h3 class="font-label-bold text-on-surface mb-4"><?php echo __('order_status', 'Trạng thái'); ?></h3>
                <div class="flex items-center gap-3">
                    <?php 
                        $statusClass = 'bg-surface-container text-outline';
                        $lowStatus = strtolower($data['order']['order_status']);
                        if($lowStatus == 'delivered' || $lowStatus == 'completed') $statusClass = 'bg-green-100 text-green-700';
                        if($lowStatus == 'processing' || $lowStatus == 'shipping' || $lowStatus == 'shipped') $statusClass = 'bg-blue-100 text-blue-700';
                        if($lowStatus == 'cancelled') $statusClass = 'bg-error-container text-error';
                        if($lowStatus == 'pending') $statusClass = 'bg-amber-100 text-amber-700';
                    ?>
                    <span class="w-3 h-3 rounded-full <?php echo str_replace('text-', 'bg-', explode(' ', $statusClass)[1]); ?>"></span>
                    <span class="font-label-bold text-on-surface"><?php echo $data['order']['status_text']; ?></span>
                </div>
                
                <!-- Simple Tracker -->
                <div class="mt-8 relative pl-8 space-y-8 before:content-[''] before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[2px] before:bg-outline-variant">
                    <div class="relative">
                        <div class="absolute -left-8 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center ring-4 ring-white z-10">
                            <span class="material-symbols-outlined text-[14px] text-white">check</span>
                        </div>
                        <p class="text-sm font-label-bold text-on-surface"><?php echo __('order_confirmed', 'Đã xác nhận đơn hàng'); ?></p>
                        <p class="text-[12px] text-outline"><?php echo $data['order']['formatted_date']; ?></p>
                    </div>
                    <?php if($lowStatus != 'cancelled'): ?>
                    <div class="relative">
                        <div class="absolute -left-8 w-6 h-6 <?php echo in_array($lowStatus, ['processing', 'shipping', 'shipped', 'delivered', 'completed']) ? 'bg-green-500' : 'bg-outline-variant'; ?> rounded-full flex items-center justify-center ring-4 ring-white z-10">
                            <span class="material-symbols-outlined text-[14px] text-white"><?php echo in_array($lowStatus, ['processing', 'shipping', 'shipped', 'delivered', 'completed']) ? 'check' : 'pending'; ?></span>
                        </div>
                        <p class="text-sm font-label-bold <?php echo in_array($lowStatus, ['processing', 'shipping', 'shipped', 'delivered', 'completed']) ? 'text-on-surface' : 'text-outline'; ?>"><?php echo __('order_shipping_status', 'Đang xử lý & Giao hàng'); ?></p>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-8 w-6 h-6 <?php echo in_array($lowStatus, ['delivered', 'completed']) ? 'bg-green-500' : 'bg-outline-variant'; ?> rounded-full flex items-center justify-center ring-4 ring-white z-10">
                            <span class="material-symbols-outlined text-[14px] text-white"><?php echo in_array($lowStatus, ['delivered', 'completed']) ? 'check' : 'inventory_2'; ?></span>
                        </div>
                        <p class="text-sm font-label-bold <?php echo in_array($lowStatus, ['delivered', 'completed']) ? 'text-on-surface' : 'text-outline'; ?>"><?php echo __('order_delivered_success', 'Đã nhận hàng thành công'); ?></p>
                    </div>
                    <?php else: ?>
                    <div class="relative">
                        <div class="absolute -left-8 w-6 h-6 bg-error rounded-full flex items-center justify-center ring-4 ring-white z-10">
                            <span class="material-symbols-outlined text-[14px] text-white">close</span>
                        </div>
                        <p class="text-sm font-label-bold text-error"><?php echo __('order_cancelled_status', 'Đơn hàng đã bị hủy'); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                <h3 class="font-label-bold text-on-surface mb-4"><?php echo __('receiver_info', 'Thông tin nhận hàng'); ?></h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-outline text-lg">person</span>
                        <div>
                            <p class="text-sm font-label-bold text-on-surface"><?php echo $data['order']['full_name']; ?></p>
                            <p class="text-sm text-outline"><?php echo $data['order']['phone']; ?></p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-outline text-lg">location_on</span>
                        <p class="text-sm text-on-surface-variant leading-relaxed">
                            <?php echo $data['order']['shipping_address']; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .animate-in, .animate-in * { visibility: visible; }
    .animate-in { position: absolute; left: 0; top: 0; width: 100%; }
    .print\:hidden { display: none !important; }
}
</style>

<?php require APPROOT . '/views/layout/footer.php'; ?>
