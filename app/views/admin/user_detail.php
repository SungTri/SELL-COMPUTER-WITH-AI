<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area -->
<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <!-- Header -->
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-10 print:hidden">
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/users" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
            </a>
            <h1 class="text-h2 font-bold text-primary">Chi tiết Người dùng</h1>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                <img alt="Admin" class="w-10 h-10 rounded-full object-cover" src="<?php echo $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=Admin&background=0453cd&color=fff'; ?>"/>
                <div class="text-right">
                    <p class="text-[14px] font-bold"><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></p>
                    <p class="text-[12px] text-on-surface-variant">Quản trị viên</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="p-10 space-y-8 w-full max-w-7xl mx-auto">
        <!-- Stats Summary Section -->
        <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Contribution Card -->
            <div class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                    <span class="material-symbols-outlined text-[28px]">payments</span>
                </div>
                <div>
                    <span class="text-[12px] text-on-surface-variant font-bold uppercase tracking-wider block">Tổng chi tiêu</span>
                    <span class="text-[20px] font-extrabold text-primary block mt-1"><?php echo number_format($data['totalSpent'], 0, ',', '.'); ?> đ</span>
                </div>
            </div>

            <!-- Orders Count Card -->
            <div class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <span class="material-symbols-outlined text-[28px]">shopping_bag</span>
                </div>
                <div>
                    <span class="text-[12px] text-on-surface-variant font-bold uppercase tracking-wider block">Đơn mua hàng</span>
                    <span class="text-[20px] font-extrabold text-primary block mt-1"><?php echo count($data['orders']); ?> đơn</span>
                </div>
            </div>

            <!-- Reviews Count Card -->
            <div class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <span class="material-symbols-outlined text-[28px]">rate_review</span>
                </div>
                <div>
                    <span class="text-[12px] text-on-surface-variant font-bold uppercase tracking-wider block">Số lượng đánh giá</span>
                    <span class="text-[20px] font-extrabold text-primary block mt-1"><?php echo count($data['reviews']); ?> đánh giá</span>
                </div>
            </div>

            <!-- Average Order Value Card -->
            <div class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-600">
                    <span class="material-symbols-outlined text-[28px]">calculate</span>
                </div>
                <div>
                    <span class="text-[12px] text-on-surface-variant font-bold uppercase tracking-wider block">Giá trị ĐH TB</span>
                    <?php 
                    $avgOrder = count($data['orders']) > 0 ? $data['totalSpent'] / count($data['orders']) : 0;
                    ?>
                    <span class="text-[20px] font-extrabold text-primary block mt-1"><?php echo number_format($avgOrder, 0, ',', '.'); ?> đ</span>
                </div>
            </div>
        </section>

        <!-- Profile Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: User details -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="p-8 text-center border-b border-outline-variant bg-[#F8F9FB]">
                        <div class="w-24 h-24 rounded-full bg-surface-container flex items-center justify-center overflow-hidden border border-outline-variant mx-auto mb-4">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data['user']['full_name'] ?: $data['user']['email']); ?>&background=random&size=128" alt="" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-h3 font-bold text-primary"><?php echo $data['user']['full_name'] ?: 'Chưa cập nhật'; ?></h2>
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold border inline-block mt-2 <?php echo $data['user']['role_id'] == 1 ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-blue-50 text-blue-700 border-blue-100'; ?>">
                            <?php echo strtoupper($data['user']['role_name']); ?>
                        </span>
                    </div>

                    <div class="p-8 space-y-6">
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <span class="material-symbols-outlined text-on-surface-variant mt-0.5">mail</span>
                                <div class="flex-1">
                                    <span class="text-[12px] text-on-surface-variant block uppercase tracking-wider font-semibold">Email</span>
                                    <span class="text-[14px] text-primary font-bold break-all block"><?php echo $data['user']['email']; ?></span>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="material-symbols-outlined text-on-surface-variant mt-0.5">phone</span>
                                <div class="flex-1">
                                    <span class="text-[12px] text-on-surface-variant block uppercase tracking-wider font-semibold">Số điện thoại</span>
                                    <span class="text-[14px] text-primary font-bold block"><?php echo $data['user']['phone'] ?: 'Chưa cập nhật'; ?></span>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="material-symbols-outlined text-on-surface-variant mt-0.5">home</span>
                                <div class="flex-1">
                                    <span class="text-[12px] text-on-surface-variant block uppercase tracking-wider font-semibold">Địa chỉ</span>
                                    <span class="text-[14px] text-primary font-medium leading-relaxed block"><?php echo $data['user']['address'] ?: 'Chưa cập nhật'; ?></span>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="material-symbols-outlined text-on-surface-variant mt-0.5">wc</span>
                                <div class="flex-1">
                                    <span class="text-[12px] text-on-surface-variant block uppercase tracking-wider font-semibold">Giới tính</span>
                                    <?php 
                                    $gender = $data['user']['gender'];
                                    if ($gender === 'Kh?c' || strpos($gender, 'Kh?') !== false) {
                                        $gender = 'Khác';
                                    }
                                    ?>
                                    <span class="text-[14px] text-primary font-bold block"><?php echo $gender ?: 'Chưa cập nhật'; ?></span>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="material-symbols-outlined text-on-surface-variant mt-0.5">calendar_month</span>
                                <div class="flex-1">
                                    <span class="text-[12px] text-on-surface-variant block uppercase tracking-wider font-semibold">Ngày tham gia</span>
                                    <span class="text-[14px] text-primary font-bold block"><?php echo date('d/m/Y H:i', strtotime($data['user']['created_at'])); ?></span>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="material-symbols-outlined text-on-surface-variant mt-0.5">shield_person</span>
                                <div class="flex-1">
                                    <span class="text-[12px] text-on-surface-variant block uppercase tracking-wider font-semibold">Trạng thái</span>
                                    <div class="mt-1">
                                        <?php if($data['user']['status'] == 1): ?>
                                            <span class="px-2.5 py-0.5 rounded bg-green-100 text-green-700 text-[11px] font-bold border border-green-200 uppercase">Hoạt động</span>
                                        <?php elseif($data['user']['status'] == -1): ?>
                                            <span class="px-2.5 py-0.5 rounded bg-gray-100 text-gray-700 text-[11px] font-bold border border-gray-300 uppercase">Đã xóa mềm</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-0.5 rounded bg-red-100 text-red-700 text-[11px] font-bold border border-red-200 uppercase">Bị khóa</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action buttons for user status -->
                        <div class="pt-6 border-t border-outline-variant flex flex-col gap-3">
                            <?php if($data['user']['status'] == 1): ?>
                                <a href="<?php echo URLROOT; ?>/admin/updateUserStatus/<?php echo $data['user']['id']; ?>?status=0" class="w-full py-2.5 border border-outline-variant rounded-xl text-[14px] font-bold text-center text-on-surface-variant hover:bg-red-500 hover:text-white transition-all flex items-center justify-center gap-2" title="Khóa tài khoản">
                                    <span class="material-symbols-outlined text-[18px]">block</span> Khóa tài khoản
                                </a>
                                <a href="<?php echo URLROOT; ?>/admin/deleteUser/<?php echo $data['user']['id']; ?>" class="w-full py-2.5 border border-outline-variant rounded-xl text-[14px] font-bold text-center text-on-surface-variant hover:bg-red-600 hover:text-white transition-all flex items-center justify-center gap-2" title="Xóa tài khoản" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                                    <span class="material-symbols-outlined text-[18px]">delete</span> Xóa tài khoản
                                </a>
                            <?php elseif($data['user']['status'] == 0): ?>
                                <a href="<?php echo URLROOT; ?>/admin/updateUserStatus/<?php echo $data['user']['id']; ?>?status=1" class="w-full py-2.5 border border-outline-variant rounded-xl text-[14px] font-bold text-center text-on-surface-variant hover:bg-green-500 hover:text-white transition-all flex items-center justify-center gap-2" title="Mở khóa tài khoản">
                                    <span class="material-symbols-outlined text-[18px]">check_circle</span> Kích hoạt tài khoản
                                </a>
                                <a href="<?php echo URLROOT; ?>/admin/deleteUser/<?php echo $data['user']['id']; ?>" class="w-full py-2.5 border border-outline-variant rounded-xl text-[14px] font-bold text-center text-on-surface-variant hover:bg-red-600 hover:text-white transition-all flex items-center justify-center gap-2" title="Xóa tài khoản" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                                    <span class="material-symbols-outlined text-[18px]">delete</span> Xóa tài khoản
                                </a>
                            <?php else: ?>
                                <a href="<?php echo URLROOT; ?>/admin/updateUserStatus/<?php echo $data['user']['id']; ?>?status=1" class="w-full py-2.5 border border-outline-variant rounded-xl text-[14px] font-bold text-center text-on-surface-variant hover:bg-green-500 hover:text-white transition-all flex items-center justify-center gap-2" title="Khôi phục tài khoản">
                                    <span class="material-symbols-outlined text-[18px]">restore</span> Khôi phục tài khoản
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Orders & Reviews -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Orders History Section -->
                <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-outline-variant flex items-center justify-between bg-[#F8F9FB]">
                        <h2 class="text-h3 font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">shopping_cart</span> Lịch sử mua hàng
                        </h2>
                        <span class="px-3 py-1 bg-surface-container rounded-full text-[12px] font-bold text-on-surface"><?php echo count($data['orders']); ?> Đơn</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider border-b border-outline-variant bg-[#F8F9FB]/50">
                                    <th class="px-8 py-4">Mã ĐH</th>
                                    <th class="px-8 py-4">Ngày đặt</th>
                                    <th class="px-8 py-4">Thanh toán</th>
                                    <th class="px-8 py-4 text-center">Trạng thái</th>
                                    <th class="px-8 py-4 text-right">Tổng tiền</th>
                                    <th class="px-8 py-4 text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                <?php if(empty($data['orders'])): ?>
                                <tr>
                                    <td colspan="6" class="px-8 py-10 text-center text-on-surface-variant">Không tìm thấy đơn hàng nào.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($data['orders'] as $order): ?>
                                    <tr class="hover:bg-[#F8F9FB] transition-colors">
                                        <td class="px-8 py-5 font-bold text-primary">#<?php echo $order['id']; ?></td>
                                        <td class="px-8 py-5 text-[13px] text-on-surface-variant">
                                            <?php echo date('d/m/Y H:i', strtotime($order['ordered_at'])); ?>
                                        </td>
                                        <td class="px-8 py-5">
                                            <span class="text-[13px] block text-on-surface font-semibold"><?php echo $order['payment_method']; ?></span>
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold border uppercase mt-1 <?php echo strtolower($order['payment_status']) === 'paid' ? 'bg-green-50 text-green-600 border-green-200' : 'bg-red-50 text-red-600 border-red-200'; ?>">
                                                <?php echo $order['payment_status']; ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <?php 
                                            $statusClasses = [
                                                'delivered' => 'bg-green-100 text-green-700 border-green-200',
                                                'completed' => 'bg-green-100 text-green-700 border-green-200',
                                                'pending' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'shipping' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                'processing' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                'cancelled' => 'bg-red-100 text-red-700 border-red-200'
                                            ];
                                            $statusText = [
                                                'pending' => 'Chờ xử lý',
                                                'processing' => 'Đóng gói',
                                                'shipping' => 'Đang giao',
                                                'delivered' => 'Đã giao',
                                                'completed' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            $lowerStatus = strtolower($order['order_status']);
                                            $class = $statusClasses[$lowerStatus] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                            $text = $statusText[$lowerStatus] ?? $order['order_status'];
                                            ?>
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border inline-block <?php echo $class; ?>">
                                                ● <?php echo $text; ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-right font-extrabold text-secondary">
                                            <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <a href="<?php echo URLROOT; ?>/admin/orderDetail/<?php echo $order['id']; ?>" class="inline-flex items-center gap-1 text-[13px] font-bold text-secondary hover:text-blue-700 transition-colors" title="Xem chi tiết đơn">
                                                Xem <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Reviews History Section -->
                <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-outline-variant flex items-center justify-between bg-[#F8F9FB]">
                        <h2 class="text-h3 font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">rate_review</span> Lịch sử đánh giá
                        </h2>
                        <span class="px-3 py-1 bg-surface-container rounded-full text-[12px] font-bold text-on-surface"><?php echo count($data['reviews']); ?> Đánh giá</span>
                    </div>

                    <div class="p-8 space-y-6">
                        <?php if(empty($data['reviews'])): ?>
                            <p class="text-center text-on-surface-variant py-6">Người dùng này chưa có đánh giá nào.</p>
                        <?php else: ?>
                            <?php foreach($data['reviews'] as $review): ?>
                            <div class="pb-6 border-b border-outline-variant last:border-0 last:pb-0 flex items-start gap-4">
                                <div class="w-14 h-14 bg-surface-container rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant">
                                    <img src="<?php echo get_product_image($review['product_image']); ?>" alt="" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/150x150?text=Product'">
                                </div>
                                <div class="flex-1 space-y-2">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h4 class="font-bold text-[14px] text-primary"><?php echo $review['product_name']; ?></h4>
                                        <span class="text-[12px] text-on-surface-variant"><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <span class="material-symbols-outlined text-[16px] <?php echo $i <= $review['rating'] ? 'text-amber-400 fill-amber-400' : 'text-gray-300'; ?>">star</span>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="text-[13px] text-on-surface leading-relaxed mt-1 italic">"<?php echo htmlspecialchars($review['comment']); ?>"</p>
                                    
                                    <?php if(!empty($review['review_image'])): ?>
                                        <div class="mt-2 w-24 h-24 rounded-lg overflow-hidden border border-outline-variant">
                                            <img src="<?php echo get_product_image($review['review_image']); ?>" alt="Review image" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/150x150?text=Review'">
                                        </div>
                                    <?php endif; ?>

                                    <?php if(!empty($review['admin_reply'])): ?>
                                        <div class="mt-3 p-4 bg-[#F8F9FB] rounded-xl border border-outline-variant space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[16px] text-secondary">forum</span>
                                                <span class="text-[12px] font-bold text-primary">Cửa hàng phản hồi</span>
                                                <span class="text-[10px] text-on-surface-variant"><?php echo date('d/m/Y H:i', strtotime($review['replied_at'])); ?></span>
                                            </div>
                                            <p class="text-[12px] text-on-surface leading-relaxed italic">"<?php echo htmlspecialchars($review['admin_reply']); ?>"</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
