<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="max-w-4xl mx-auto py-12 px-4">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold font-h1 mb-4 text-primary"><?php echo __('track_title', 'Tra cứu đơn hàng'); ?></h1>
        <p class="text-on-surface-variant/70"><?php echo __('track_desc', 'Theo dõi hành trình đơn hàng của bạn chỉ với vài bước đơn giản.'); ?></p>
    </div>

    <!-- Tracking Form -->
    <div class="bg-white rounded-3xl shadow-xl p-8 mb-12 border border-outline-variant/10">
        <form action="<?php echo URLROOT; ?>/order/track" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-[11px] font-black text-primary/50 uppercase tracking-widest mb-2 ml-1"><?php echo __('order_id', 'Mã đơn hàng'); ?></label>
                <input type="text" name="order_id" placeholder="<?php echo __('track_order_placeholder', 'Ví dụ: 123'); ?>" class="w-full bg-surface border-none rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-secondary/20 transition-all font-medium" required value="<?php echo $_POST['order_id'] ?? ''; ?>">
            </div>
            <div>
                <label class="block text-[11px] font-black text-primary/50 uppercase tracking-widest mb-2 ml-1"><?php echo __('phone_label', 'Số điện thoại'); ?></label>
                <input type="text" name="phone" placeholder="<?php echo __('track_phone_placeholder', 'Số điện thoại nhận hàng'); ?>" class="w-full bg-surface border-none rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-secondary/20 transition-all font-medium" required value="<?php echo $_POST['phone'] ?? ''; ?>">
            </div>
            <button type="submit" class="w-full btn-premium rounded-xl px-6 py-3.5 normal-case tracking-normal relative flex items-center justify-center gap-2 shadow-lg hover:shadow-indigo-500/20">
                <span class="material-symbols-outlined">analytics</span> <?php echo __('track_submit', 'Kiểm tra ngay'); ?>
            </button>
        </form>

        <?php if(!empty($data['error'])): ?>
            <div class="mt-6 p-4 bg-error/5 border border-error/20 rounded-xl flex items-center gap-3 text-error text-sm font-medium">
                <span class="material-symbols-outlined">error</span>
                <?php echo $data['error']; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if($data['order']): ?>
        <?php 
            $status = strtolower($data['order']['order_status']);
            $steps = [
                'pending' => ['label' => __('order_status_pending', 'Đã tiếp nhận'), 'icon' => 'assignment_turned_in', 'color' => 'secondary'],
                'confirmed' => ['label' => __('order_confirmed', 'Đã xác nhận'), 'icon' => 'verified', 'color' => 'secondary'],
                'processing' => ['label' => __('order_status_processing', 'Đang đóng gói'), 'icon' => 'package_2', 'color' => 'secondary'],
                'shipped' => ['label' => __('order_status_shipping', 'Đang giao hàng'), 'icon' => 'local_shipping', 'color' => 'secondary'],
                'delivered' => ['label' => __('order_status_completed', 'Thành công'), 'icon' => 'check_circle', 'color' => 'secondary']
            ];

            // Define order of steps
            $orderSteps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
            
            // Handle cancelled status separately or map it
            if ($status == 'cancelled') {
                $steps['cancelled'] = ['label' => __('order_status_cancelled', 'Đã hủy'), 'icon' => 'cancel', 'color' => 'error'];
                $orderSteps = ['pending', 'cancelled'];
            }

            $currentIndex = array_search($status, $orderSteps);
        ?>

        <!-- Order Info Card -->
        <div class="bg-primary text-on-primary rounded-3xl p-8 mb-12 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-secondary/20 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex flex-wrap justify-between items-center gap-8">
                <div>
                    <span class="text-on-primary opacity-60 text-xs font-bold uppercase tracking-widest mb-1 block"><?php echo __('order_id', 'Mã đơn hàng'); ?></span>
                    <h2 class="text-3xl font-black italic tracking-tighter">#<?php echo $data['order']['id']; ?></h2>
                </div>
                <div>
                    <span class="text-on-primary opacity-60 text-xs font-bold uppercase tracking-widest mb-1 block"><?php echo __('order_date', 'Ngày đặt hàng'); ?></span>
                    <p class="font-bold"><?php echo date('d/m/Y H:i', strtotime($data['order']['ordered_at'])); ?></p>
                </div>
                <div>
                    <span class="text-on-primary opacity-60 text-xs font-bold uppercase tracking-widest mb-1 block"><?php echo __('receiver_info', 'Người nhận'); ?></span>
                    <p class="font-bold"><?php echo $data['order']['full_name']; ?></p>
                </div>
                <div>
                    <span class="text-on-primary opacity-60 text-xs font-bold uppercase tracking-widest mb-1 block"><?php echo __('total_payment', 'Tổng thanh toán'); ?></span>
                    <p class="text-2xl font-black text-secondary"><?php echo number_format($data['order']['total_amount'], 0, ',', '.'); ?>đ</p>
                </div>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Visual Timeline -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-xl p-8 border border-outline-variant/10">
                <h3 class="text-xl font-bold mb-10 flex items-center gap-3">
                    <span class="w-8 h-8 bg-secondary/10 text-secondary rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined !text-[20px]">route</span>
                    </span>
                    <?php echo __('track_timeline', 'Tiến độ đơn hàng'); ?>
                </h3>

                <div class="relative px-4">
                    <!-- Progress Line -->
                    <div class="absolute left-9 top-0 bottom-0 w-1 bg-surface-container-high md:left-0 md:right-0 md:top-6 md:h-1 md:w-auto">
                        <div class="h-full bg-secondary transition-all duration-1000 md:h-full md:w-[<?php echo ($currentIndex / (count($orderSteps) - 1)) * 100; ?>%]"></div>
                    </div>
                    
                    <div class="flex flex-col gap-12 md:flex-row md:justify-between relative z-10">
                        <?php foreach($orderSteps as $index => $stepKey): ?>
                            <?php 
                                $step = $steps[$stepKey];
                                $isCompleted = $index <= $currentIndex;
                                $isActive = $index == $currentIndex;
                            ?>
                            <div class="flex items-center gap-6 md:flex-col md:gap-4 md:flex-1 group">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-500 <?php echo $isCompleted ? 'bg-secondary text-on-secondary shadow-lg shadow-secondary/30 scale-110' : 'bg-surface-container-high text-on-surface/20'; ?> group-hover:scale-125">
                                    <span class="material-symbols-outlined !text-[24px] <?php echo $isActive ? 'animate-pulse' : ''; ?>"><?php echo $step['icon']; ?></span>
                                </div>
                                <div class="text-left md:text-center">
                                    <p class="text-xs font-black uppercase tracking-wider <?php echo $isCompleted ? 'text-primary' : 'text-on-surface/30'; ?>"><?php echo $step['label']; ?></p>
                                    <?php if($isActive): ?>
                                        <div class="mt-1 flex items-center gap-1 justify-start md:justify-center">
                                            <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-ping"></span>
                                            <span class="text-[9px] font-bold text-secondary uppercase tracking-widest"><?php echo __('active_step', 'Đang thực hiện'); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Detailed Logs -->
            <div class="bg-white rounded-3xl shadow-xl p-8 border border-outline-variant/10">
                <h3 class="text-lg font-bold mb-6 italic"><?php echo __('detailed_history', 'Lịch sử chi tiết'); ?></h3>
                <div class="space-y-6 relative before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-px before:bg-outline-variant/30">
                    <?php if(!empty($data['logs'])): ?>
                        <?php foreach($data['logs'] as $log): ?>
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-1.5 w-6 h-6 bg-white border-2 border-secondary rounded-full flex items-center justify-center z-10">
                                <div class="w-2 h-2 bg-secondary rounded-full"></div>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-secondary uppercase tracking-widest mb-1"><?php echo date('H:i - d/m/Y', strtotime($log['created_at'])); ?></p>
                                <h4 class="text-sm font-bold text-primary">
                                    <?php 
                                        $logStatus = $log['status'];
                                        $logStatusLower = strtolower($logStatus);
                                        if ($logStatusLower == 'chờ xử lý' || $logStatusLower == 'pending') {
                                            echo __('order_status_pending', 'Chờ xử lý');
                                        } elseif ($logStatusLower == 'đã xác nhận' || $logStatusLower == 'confirmed') {
                                            echo __('order_confirmed', 'Đã xác nhận');
                                        } elseif ($logStatusLower == 'đang đóng gói' || $logStatusLower == 'đang xử lý' || $logStatusLower == 'processing') {
                                            echo __('order_status_processing', 'Đang đóng gói');
                                        } elseif ($logStatusLower == 'đang giao hàng' || $logStatusLower == 'đang giao' || $logStatusLower == 'shipping' || $logStatusLower == 'shipped') {
                                            echo __('order_status_shipping', 'Đang giao hàng');
                                        } elseif ($logStatusLower == 'thành công' || $logStatusLower == 'đã giao' || $logStatusLower == 'completed' || $logStatusLower == 'delivered') {
                                            echo __('order_status_completed', 'Thành công');
                                        } elseif ($logStatusLower == 'đã hủy' || $logStatusLower == 'cancelled') {
                                            echo __('order_status_cancelled', 'Đã hủy');
                                        } elseif (strpos($logStatusLower, 'thanh toán:') !== false) {
                                            $paymentPart = trim(str_replace('thanh toán:', '', $logStatusLower));
                                            $translatedPaymentPart = ($paymentPart == 'đã thanh toán' || $paymentPart == 'paid') ? __('payment_status_paid', 'Đã thanh toán') : __('payment_status_unpaid', 'Chưa thanh toán');
                                            echo __('payment_info', 'Thanh toán') . ': ' . $translatedPaymentPart;
                                        } else {
                                            echo $logStatus; 
                                        }
                                    ?>
                                </h4>
                                <p class="text-xs text-outline leading-relaxed mt-1">
                                    <?php 
                                        $logDesc = $log['description'];
                                        if ($logDesc == 'Trạng thái đơn hàng đã được cập nhật bởi hệ thống.') {
                                            echo __('log_desc_status_updated', 'Trạng thái đơn hàng đã được cập nhật bởi hệ thống.');
                                        } elseif ($logDesc == 'Hệ thống xác nhận trạng thái thanh toán của đơn hàng.') {
                                            echo __('log_desc_payment_confirmed', 'Hệ thống xác nhận trạng thái thanh toán của đơn hàng.');
                                        } elseif ($logDesc == 'Hệ thống đã tự động xác nhận thanh toán thành công qua ngân hàng.') {
                                            echo __('system_auto_confirm_payment', 'Hệ thống đã tự động xác nhận thanh toán thành công qua ngân hàng.');
                                        } else {
                                            echo $logDesc;
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-xs text-outline italic"><?php echo __('updating_data', 'Đang cập nhật dữ liệu...'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Order Items Detail -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-outline-variant/10">
            <div class="p-6 border-b border-outline-variant/10 bg-surface-container/30">
                <h3 class="text-lg font-bold"><?php echo __('product_list', 'Danh sách sản phẩm'); ?></h3>
            </div>
            <div class="divide-y divide-outline-variant/10">
                <?php foreach($data['items'] as $item): ?>
                    <div class="p-6 flex items-center gap-6">
                        <img src="<?php echo get_product_image($item['image']); ?>" class="w-16 h-16 object-contain rounded-xl bg-surface p-2" alt="" onerror="this.src='https://placehold.co/150x150?text=Product'">
                        <div class="flex-1">
                            <h4 class="font-bold text-primary mb-1"><?php echo $item['name']; ?></h4>
                            <p class="text-xs text-on-surface-variant/60 uppercase font-black tracking-widest"><?php echo __('quantity_label', 'Số lượng'); ?>: <?php echo $item['quantity']; ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-primary"><?php echo number_format($item['price_at_purchase'], 0, ',', '.'); ?>đ</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="p-8 bg-surface-container/10">
                <?php 
                    $subtotal = 0;
                    foreach($data['items'] as $item) {
                        $subtotal += $item['price_at_purchase'] * $item['quantity'];
                    }
                ?>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-on-surface-variant font-medium"><?php echo __('subtotal', 'Tạm tính'); ?></span>
                    <span class="font-bold text-primary"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-on-surface-variant font-medium"><?php echo __('shipping_fee', 'Phí vận chuyển'); ?></span>
                    <span class="font-bold text-primary">
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
                <div class="flex justify-between items-center mb-2 text-green-600 font-bold">
                    <span><?php echo __('discount', 'Giảm giá'); ?></span>
                    <span>-<?php echo number_format($data['order']['discount_amount'], 0, ',', '.'); ?>đ</span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between items-center pt-4 border-t border-outline-variant/20">
                    <span class="text-lg font-bold text-primary"><?php echo __('total_payment', 'Tổng cộng'); ?></span>
                    <span class="text-2xl font-black text-secondary"><?php echo number_format($data['order']['total_amount'], 0, ',', '.'); ?>đ</span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
