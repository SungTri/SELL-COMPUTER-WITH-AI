<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="mt-12 flex flex-col md:flex-row gap-8">
    <!-- Sidebar -->
    <aside class="w-full md:w-64 space-y-2">
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm mb-6">
            <div class="flex items-center gap-4 mb-4">
                <img src="<?php echo $data['user']['avatar']; ?>" alt="Avatar" class="w-12 h-12 rounded-full border-2 border-secondary" />
                <div>
                    <h3 class="font-label-bold text-label-bold text-on-surface line-clamp-1"><?php echo $data['user']['full_name']; ?></h3>
                    <p class="text-xs text-outline"><?php echo __('silver_member', 'Thành viên bạc'); ?></p>
                </div>
            </div>
        </div>
        
        <nav class="space-y-1">
            <a href="?tab=profile" class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold transition-all <?php echo $data['tab'] == 'profile' ? 'bg-secondary text-on-secondary shadow-md' : 'text-on-surface-variant hover:bg-surface-container'; ?>">
                <span class="material-symbols-outlined">person</span>
                <span><?php echo __('tab_profile', 'Hồ sơ cá nhân'); ?></span>
            </a>
            <a href="?tab=orders" class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold transition-all <?php echo $data['tab'] == 'orders' ? 'bg-secondary text-on-secondary shadow-md' : 'text-on-surface-variant hover:bg-surface-container'; ?>">
                <span class="material-symbols-outlined">shopping_bag</span>
                <span><?php echo __('tab_orders', 'Lịch sử đơn hàng'); ?></span>
            </a>
            <a href="?tab=wishlist" class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold transition-all <?php echo $data['tab'] == 'wishlist' ? 'bg-secondary text-on-secondary shadow-md' : 'text-on-surface-variant hover:bg-surface-container'; ?>">
                <span class="material-symbols-outlined">favorite</span>
                <span><?php echo __('tab_wishlist', 'Sản phẩm yêu thích'); ?></span>
            </a>
            <a href="?tab=promotions" class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold transition-all <?php echo $data['tab'] == 'promotions' ? 'bg-secondary text-on-secondary shadow-md' : 'text-on-surface-variant hover:bg-surface-container'; ?>">
                <span class="material-symbols-outlined">confirmation_number</span>
                <span><?php echo __('tab_vouchers', 'Khuyến mãi của tôi'); ?></span>
            </a>
            <a href="?tab=addresses" class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold transition-all <?php echo $data['tab'] == 'addresses' ? 'bg-secondary text-on-secondary shadow-md' : 'text-on-surface-variant hover:bg-surface-container'; ?>">
                <span class="material-symbols-outlined">location_on</span>
                <span><?php echo __('tab_addresses', 'Sổ địa chỉ'); ?></span>
            </a>
            <a href="?tab=security" class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold transition-all <?php echo $data['tab'] == 'security' ? 'bg-secondary text-on-secondary shadow-md' : 'text-on-surface-variant hover:bg-surface-container'; ?>">
                <span class="material-symbols-outlined">security</span>
                <span><?php echo __('tab_security', 'Bảo mật'); ?></span>
            </a>
            <a href="?tab=notifications" class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold transition-all <?php echo $data['tab'] == 'notifications' ? 'bg-secondary text-on-secondary shadow-md' : 'text-on-surface-variant hover:bg-surface-container'; ?>">
                <span class="material-symbols-outlined">notifications</span>
                <span><?php echo __('tab_notifications', 'Thông báo'); ?></span>
            </a>
            <hr class="my-4 border-outline-variant" />
            <a href="<?php echo URLROOT; ?>/auth/logout" onclick="sessionStorage.clear()" class="flex items-center gap-3 px-4 py-3 text-error hover:bg-error-container/10 rounded-lg font-label-bold transition-all">
                <span class="material-symbols-outlined">logout</span>
                <span><?php echo __('logout', 'Đăng xuất'); ?></span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1">
        <?php if($data['tab'] == 'profile'): ?>
        <!-- Personal Info Card -->
        <section class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
            <?php if(isset($_GET['success'])): ?>
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg flex items-center gap-2 font-label-bold">
                <span class="material-symbols-outlined">check_circle</span>
                <?php echo __('profile_success', 'Cập nhật thông tin thành công!'); ?>
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['error'])): ?>
            <div class="mb-6 p-4 bg-rose-100 text-rose-700 rounded-lg flex items-center gap-2 font-label-bold">
                <span class="material-symbols-outlined">error</span>
                <?php 
                    if($_GET['error'] == 'invalid_phone') {
                        echo __('profile_error_phone', 'Số điện thoại không hợp lệ. Vui lòng nhập từ 9 đến 15 chữ số.');
                    } else {
                        echo __('profile_error_general', 'Cập nhật thông tin thất bại. Vui lòng thử lại.');
                    }
                ?>
            </div>
            <?php endif; ?>

            <div id="display-section">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-h3 text-h3 text-on-surface"><?php echo __('account_info_title', 'Thông tin tài khoản'); ?></h2>
                    <button onclick="toggleEdit(true)" class="text-secondary font-label-bold flex items-center gap-2 hover:underline">
                        <span class="material-symbols-outlined text-sm">edit</span> <?php echo __('edit_btn', 'Chỉnh sửa'); ?>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-1">
                        <label class="text-xs text-outline uppercase tracking-wider font-label-bold"><?php echo __('fullname_label', 'Họ và tên'); ?></label>
                        <p class="font-body-lg text-on-surface"><?php echo $data['user']['full_name']; ?></p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs text-outline uppercase tracking-wider font-label-bold"><?php echo __('email_label', 'Địa chỉ Email'); ?></label>
                        <p class="font-body-lg text-on-surface"><?php echo $data['user']['email']; ?></p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs text-outline uppercase tracking-wider font-label-bold"><?php echo __('phone_label', 'Số điện thoại'); ?></label>
                        <p class="font-body-lg text-on-surface"><?php echo $data['user']['phone'] ?: __('not_updated', 'Chưa cập nhật'); ?></p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs text-outline uppercase tracking-wider font-label-bold"><?php echo __('gender_label', 'Giới tính'); ?></label>
                        <p class="font-body-lg text-on-surface"><?php 
                            $g = $data['user']['gender'];
                            if ($g == 'Nam') echo __('gender_male', 'Nam');
                            elseif ($g == 'Nữ') echo __('gender_female', 'Nữ');
                            elseif ($g == 'Khác') echo __('gender_other', 'Khác');
                            else echo htmlspecialchars($g);
                        ?></p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs text-outline uppercase tracking-wider font-label-bold"><?php echo __('default_address_label', 'Địa chỉ mặc định'); ?></label>
                        <p class="font-body-lg text-on-surface"><?php echo $data['user']['address'] ?: __('not_updated', 'Chưa cập nhật'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Edit Form Section -->
            <div id="edit-section" class="hidden">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-h3 text-h3 text-on-surface"><?php echo __('edit_profile_title', 'Chỉnh sửa hồ sơ'); ?></h2>
                    <button onclick="toggleEdit(false)" class="text-outline font-label-bold flex items-center gap-2 hover:text-on-surface">
                        <?php echo __('cancel_btn', 'Hủy'); ?>
                    </button>
                </div>

                <form action="<?php echo URLROOT; ?>/user/update" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-label-bold text-on-surface"><?php echo __('fullname_label', 'Họ và tên'); ?></label>
                            <input type="text" name="full_name" value="<?php echo $data['user']['full_name']; ?>" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none transition-all" required />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-label-bold text-on-surface"><?php echo __('phone_label', 'Số điện thoại'); ?></label>
                            <input type="text" name="phone" value="<?php echo $data['user']['phone']; ?>" pattern="^\+?[0-9\s\-]{9,15}$" maxlength="15" title="<?php echo __('profile_error_phone', 'Số điện thoại không hợp lệ. Vui lòng nhập từ 9 đến 15 chữ số.'); ?>" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none transition-all" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-label-bold text-on-surface"><?php echo __('gender_label', 'Giới tính'); ?></label>
                            <select name="gender" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none transition-all">
                                <option value="Nam" <?php echo $data['user']['gender'] == 'Nam' ? 'selected' : ''; ?>><?php echo __('gender_male', 'Nam'); ?></option>
                                <option value="Nữ" <?php echo $data['user']['gender'] == 'Nữ' ? 'selected' : ''; ?>><?php echo __('gender_female', 'Nữ'); ?></option>
                                <option value="Khác" <?php echo $data['user']['gender'] == 'Khác' ? 'selected' : ''; ?>><?php echo __('gender_other', 'Khác'); ?></option>
                            </select>
                        </div>
                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="text-sm font-label-bold text-on-surface"><?php echo __('address_label', 'Địa chỉ'); ?></label>
                            <textarea name="address" rows="3" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none transition-all"><?php echo $data['user']['address']; ?></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="btn-premium px-8 py-3 rounded-xl normal-case tracking-normal relative text-sm">
                            <?php echo __('save_changes_btn', 'Lưu thay đổi'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <script>
            function toggleEdit(show) {
                const displaySection = document.getElementById('display-section');
                const editSection = document.getElementById('edit-section');
                if (show) {
                    displaySection.classList.add('hidden');
                    editSection.classList.remove('hidden');
                } else {
                    displaySection.classList.remove('hidden');
                    editSection.classList.add('hidden');
                }
            }
        </script>


        <?php elseif($data['tab'] == 'wishlist'): ?>
        <!-- Wishlist Card -->
        <section class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="mb-8">
                <h2 class="font-h3 text-h3 text-on-surface"><?php echo __('tab_wishlist', 'Sản phẩm yêu thích'); ?></h2>
                <p class="text-outline text-sm mt-1"><?php echo __('wishlist_desc', 'Danh sách các sản phẩm bạn đã lưu để mua sau.'); ?></p>
            </div>

            <?php if(!empty($data['wishlist'])): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($data['wishlist'] as $item): ?>
                <div class="group bg-surface rounded-xl border border-outline-variant overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="relative aspect-square overflow-hidden bg-white">
                        <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $item['id']; ?>" class="absolute inset-0 z-10"></a>
                        <img src="<?php echo get_product_image($item['main_image']); ?>" alt="<?php echo $item['name']; ?>" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500" onerror="this.src='https://placehold.co/200x200?text=Product'" />
                        <a href="<?php echo URLROOT; ?>/wishlist/remove/<?php echo $item['id']; ?>" title="<?php echo __('delete_btn', 'Xóa'); ?>" class="absolute top-2 right-2 p-2 bg-white/80 backdrop-blur-sm text-error rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white z-20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </a>
                    </div>
                    <div class="p-4 space-y-2">
                        <h3 class="font-label-bold text-on-surface line-clamp-2 min-h-[40px]"><?php echo $item['name']; ?></h3>
                        <p class="font-price-display text-secondary text-lg font-bold"><?php echo number_format($item['price']); ?>đ</p>
                        <form action="<?php echo URLROOT; ?>/cart/add" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full bg-surface-container-high text-primary py-2 rounded-lg font-label-bold hover:bg-secondary hover:text-on-secondary transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">shopping_cart</span>
                                <?php echo __('add_to_cart_btn', 'Thêm vào giỏ'); ?>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-[64px] text-outline mb-4">heart_broken</span>
                <p class="text-outline font-label-bold"><?php echo __('wishlist_empty', 'Danh sách yêu thích trống'); ?></p>
                <a href="<?php echo URLROOT; ?>" class="text-secondary hover:underline mt-2 block"><?php echo __('explore_products', 'Khám phá sản phẩm ngay'); ?></a>
            </div>
            <?php endif; ?>
        </section>

        <?php elseif($data['tab'] == 'orders'): ?>
        <!-- Recent Orders Card -->
        <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="p-8 border-b border-outline-variant flex justify-between items-center">
                <h2 class="font-h3 text-h3 text-on-surface"><?php echo __('order_history_title', 'Lịch sử mua hàng'); ?></h2>
            </div>
            
            <div class="overflow-x-auto">
                <?php if(!empty($data['orders'])): ?>
                <table class="w-full text-left">
                    <thead class="bg-surface-container text-outline uppercase text-[12px] font-label-bold">
                        <tr>
                            <th class="px-8 py-4"><?php echo __('order_id', 'Mã đơn'); ?></th>
                            <th class="px-8 py-4"><?php echo __('order_date', 'Ngày đặt'); ?></th>
                            <th class="px-8 py-4"><?php echo __('total_value', 'Tổng tiền'); ?></th>
                            <th class="px-8 py-4"><?php echo __('order_status', 'Trạng thái'); ?></th>
                            <th class="px-8 py-4 text-right"><?php echo __('action_label', 'Hành động'); ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <?php foreach($data['orders'] as $order): ?>
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-8 py-6 font-label-bold text-on-surface">#<?php echo $order['id']; ?></td>
                            <td class="px-8 py-6 text-on-surface-variant"><?php echo date('d/m/Y', strtotime($order['date'])); ?></td>
                            <td class="px-8 py-6 font-price-display text-secondary font-bold"><?php echo number_format($order['total']); ?>đ</td>
                            <td class="px-8 py-6">
                                <?php 
                                    $statusClass = 'bg-surface-container text-outline';
                                    $lowStatus = strtolower($order['status']);
                                    if($lowStatus == 'delivered' || $lowStatus == 'completed' || $lowStatus == 'hoàn tất' || $lowStatus == 'hoàn thành') $statusClass = 'bg-green-100 text-green-700';
                                    if($lowStatus == 'processing' || $lowStatus == 'shipping' || $lowStatus == 'shipped' || $lowStatus == 'đang xử lý' || $lowStatus == 'đang giao') $statusClass = 'bg-blue-100 text-blue-700';
                                    if($lowStatus == 'cancelled' || $lowStatus == 'đã hủy') $statusClass = 'bg-error-container text-error';
                                    if($lowStatus == 'pending' || $lowStatus == 'chờ xử lý') $statusClass = 'bg-amber-100 text-amber-700';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-label-bold <?php echo $statusClass; ?>">
                                    <?php echo $order['status_text']; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="<?php echo URLROOT; ?>/user/orderDetail/<?php echo $order['id']; ?>" title="<?php echo __('view_details_btn', 'Xem chi tiết'); ?>" class="text-secondary hover:text-primary transition-colors p-2 rounded-full hover:bg-secondary/10 inline-block">
                                    <span class="material-symbols-outlined">visibility</span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="p-12 text-center">
                    <span class="material-symbols-outlined text-[64px] text-outline mb-4">shopping_cart_off</span>
                    <p class="text-outline font-label-bold"><?php echo __('orders_empty', 'Bạn chưa có đơn hàng nào.'); ?></p>
                    <a href="<?php echo URLROOT; ?>" class="text-secondary hover:underline mt-2 block"><?php echo __('continue_shopping', 'Tiếp tục mua sắm'); ?></a>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <?php elseif($data['tab'] == 'addresses'): ?>
        <!-- Address Book Card -->
        <section class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="font-h3 text-h3 text-on-surface"><?php echo __('addresses_title', 'Sổ địa chỉ nhận hàng'); ?></h2>
                    <p class="text-outline text-sm mt-1"><?php echo __('addresses_desc', 'Quản lý các địa chỉ nhận hàng của bạn.'); ?></p>
                </div>
                <button onclick="toggleAddressModal(true)" class="bg-secondary text-on-secondary px-6 py-2 rounded-lg font-label-bold hover:shadow-lg transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span> <?php echo __('add_new_address_btn', 'Thêm địa chỉ mới'); ?>
                </button>
            </div>

            <?php if(isset($_GET['success'])): ?>
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg flex items-center gap-2 font-label-bold text-sm">
                <span class="material-symbols-outlined">check_circle</span>
                <?php 
                    if($_GET['success'] == 'added') echo __('address_added_success', 'Đã thêm địa chỉ mới thành công!');
                    elseif($_GET['success'] == 'default') echo __('address_default_success', 'Đã cập nhật địa chỉ mặc định!');
                    elseif($_GET['success'] == 'deleted') echo __('address_deleted_success', 'Đã xóa địa chỉ thành công!');
                ?>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php if(!empty($data['addresses'])): ?>
                    <?php foreach($data['addresses'] as $addr): ?>
                    <div class="p-6 rounded-xl border <?php echo $addr['is_default'] ? 'border-secondary bg-secondary/5' : 'border-outline-variant'; ?> relative group">
                        <?php if($addr['is_default']): ?>
                        <span class="absolute top-4 right-4 bg-secondary text-on-secondary text-[10px] px-2 py-0.5 rounded-full font-label-bold uppercase"><?php echo __('default_address_badge', 'Mặc định'); ?></span>
                        <?php endif; ?>
                        
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <h3 class="font-label-bold text-on-surface"><?php echo $addr['receiver_name']; ?></h3>
                                <span class="text-outline">|</span>
                                <span class="text-on-surface-variant"><?php echo $addr['receiver_phone']; ?></span>
                            </div>
                            <p class="text-sm text-on-surface-variant leading-relaxed">
                                <?php echo $addr['address_detail']; ?><br>
                                <?php echo $addr['ward']; ?>, <?php echo $addr['district']; ?>, <?php echo $addr['province']; ?>
                            </p>
                            <div class="flex gap-4 pt-2">
                                <button class="text-secondary text-sm font-label-bold hover:underline"><?php echo __('edit_btn', 'Chỉnh sửa'); ?></button>
                                <?php if(!$addr['is_default']): ?>
                                <a href="<?php echo URLROOT; ?>/user/setDefaultAddress/<?php echo $addr['id']; ?>" class="text-outline text-sm font-label-bold hover:text-on-surface"><?php echo __('set_default_btn', 'Đặt mặc định'); ?></a>
                                <a href="<?php echo URLROOT; ?>/user/deleteAddress/<?php echo $addr['id']; ?>" class="text-error text-sm font-label-bold hover:underline" onclick="return confirm('<?php echo __('confirm_delete_address', 'Bạn có chắc chắn muốn xóa địa chỉ này?'); ?>')"><?php echo __('delete_btn', 'Xóa'); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-2 py-12 text-center bg-surface rounded-xl border border-dashed border-outline">
                        <span class="material-symbols-outlined text-[48px] text-outline mb-2">home_pin</span>
                        <p class="text-outline font-label-bold"><?php echo __('addresses_empty', 'Bạn chưa lưu địa chỉ nào.'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Add Address Modal -->
        <div id="address-modal" class="fixed inset-0 bg-on-surface/50 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
            <div class="bg-surface-container-lowest w-full max-w-lg rounded-2xl shadow-2xl animate-in fade-in zoom-in-95 duration-300 overflow-hidden">
                <div class="p-6 border-b border-outline-variant flex justify-between items-center">
                    <h2 class="font-h3 text-h3 text-on-surface"><?php echo __('add_new_address_btn', 'Thêm địa chỉ mới'); ?></h2>
                    <button onclick="toggleAddressModal(false)" class="text-outline hover:text-on-surface">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form action="<?php echo URLROOT; ?>/user/addAddress" method="POST" class="p-6 space-y-4">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-sm font-label-bold text-on-surface"><?php echo __('receiver_name', 'Họ và tên người nhận'); ?></label>
                            <input type="text" name="receiver_name" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:border-secondary outline-none" required />
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-label-bold text-on-surface"><?php echo __('phone_label', 'Số điện thoại'); ?></label>
                            <input type="text" name="receiver_phone" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:border-secondary outline-none" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="text-sm font-label-bold text-on-surface"><?php echo __('province', 'Tỉnh / Thành phố'); ?></label>
                            <input type="text" name="province" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:border-secondary outline-none" required />
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-label-bold text-on-surface"><?php echo __('district', 'Quận / Huyện'); ?></label>
                            <input type="text" name="district" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:border-secondary outline-none" required />
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-label-bold text-on-surface"><?php echo __('ward', 'Phường / Xã'); ?></label>
                            <input type="text" name="ward" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:border-secondary outline-none" required />
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-label-bold text-on-surface"><?php echo __('detail_address', 'Địa chỉ chi tiết'); ?></label>
                        <textarea name="address_detail" rows="2" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:border-secondary outline-none" placeholder="<?php echo __('detail_address_placeholder', 'Ví dụ: 123 Đường Lê Lợi...'); ?>" required></textarea>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_default" class="w-5 h-5 accent-secondary" />
                        <span class="text-sm text-on-surface-variant"><?php echo __('set_as_default', 'Đặt làm địa chỉ mặc định'); ?></span>
                    </label>
                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="toggleAddressModal(false)" class="flex-1 px-6 py-3 border border-outline rounded-lg font-label-bold hover:bg-surface-container transition-all"><?php echo __('cancel_btn', 'Hủy'); ?></button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-secondary text-on-secondary rounded-lg font-label-bold hover:shadow-lg hover:bg-secondary/90 transition-all"><?php echo __('save_address_btn', 'Lưu địa chỉ'); ?></button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function toggleAddressModal(show) {
                const modal = document.getElementById('address-modal');
                if (show) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                } else {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }
        </script>

        <?php elseif($data['tab'] == 'security'): ?>
        <!-- Security / Change Password Card -->
        <section class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="mb-8">
                <h2 class="font-h3 text-h3 text-on-surface"><?php echo __('security_title_profile', 'Bảo mật tài khoản'); ?></h2>
                <p class="text-outline text-sm mt-1"><?php echo __('security_desc', 'Quản lý mật khẩu và các thiết lập bảo mật của bạn.'); ?></p>
            </div>

            <?php if(isset($_GET['success']) && $_GET['success'] == 'password'): ?>
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg flex items-center gap-2 font-label-bold">
                <span class="material-symbols-outlined">check_circle</span>
                <?php echo __('password_success', 'Đổi mật khẩu thành công!'); ?>
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['error'])): ?>
            <div class="mb-6 p-4 bg-error-container text-error rounded-lg flex items-center gap-2 font-label-bold">
                <span class="material-symbols-outlined">error</span>
                <?php 
                    if($_GET['error'] == 'current') echo __('password_error_current', 'Mật khẩu hiện tại không chính xác.');
                    elseif($_GET['error'] == 'match') echo __('password_error_match', 'Mật khẩu mới không khớp.');
                    else echo __('password_error_general', 'Đã có lỗi xảy ra. Vui lòng thử lại.');
                ?>
            </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/user/changePassword" method="POST" class="max-w-md space-y-6">
                <?php echo csrf_field(); ?>
                <div class="space-y-2">
                    <label class="text-sm font-label-bold text-on-surface"><?php echo __('current_password', 'Mật khẩu hiện tại'); ?></label>
                    <input type="password" name="current_password" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none transition-all" required />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-label-bold text-on-surface"><?php echo __('new_password', 'Mật khẩu mới'); ?></label>
                    <input type="password" name="new_password" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none transition-all" required />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-label-bold text-on-surface"><?php echo __('confirm_new_password', 'Xác nhận mật khẩu mới'); ?></label>
                    <input type="password" name="confirm_password" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none transition-all" required />
                </div>
                <div class="pt-4">
                    <button type="submit" class="bg-secondary text-on-secondary px-8 py-3 rounded-lg font-label-bold hover:shadow-lg hover:bg-secondary/90 transition-all">
                        <?php echo __('update_password_btn', 'Cập nhật mật khẩu'); ?>
                    </button>
                </div>
            </form>
        </section>

        <?php elseif($data['tab'] == 'promotions'): ?>
        <section class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div>
                <h2 class="font-h3 text-h3 text-on-surface mb-2">Mã giảm giá đã lưu</h2>
                <p class="text-outline text-sm">Các mã giảm giá bạn đã thu thập từ cửa hàng.</p>
            </div>
            
            <?php if(empty($data['saved_vouchers'])): ?>
                <div class="bg-surface-container-lowest p-12 rounded-3xl border border-outline-variant/50 shadow-sm text-center">
                    <span class="material-symbols-outlined text-[48px] text-outline/30 mb-4">confirmation_number</span>
                    <h3 class="font-bold text-on-surface mb-2">Chưa có mã giảm giá nào</h3>
                    <p class="text-outline text-sm mb-6">Hãy quay lại trang chủ để săn các mã giảm giá hấp dẫn!</p>
                    <a href="<?php echo URLROOT; ?>" class="inline-block px-6 py-3 bg-primary text-on-primary rounded-xl font-bold hover:bg-secondary transition-all">SĂN MÃ NGAY</a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach($data['saved_vouchers'] as $voucher): ?>
                    <div class="relative bg-white rounded-3xl p-6 border border-outline-variant/50 shadow-sm hover:shadow-md transition-all flex items-center gap-6 overflow-hidden group">
                        <div class="w-16 h-16 bg-secondary/10 text-secondary rounded-2xl flex-shrink-0 flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-[24px]">confirmation_number</span>
                            <span class="text-[9px] font-black uppercase"><?php echo $voucher['discount_percentage'] ? $voucher['discount_percentage'] . '%' : number_format($voucher['discount_amount']/1000) . 'K'; ?></span>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-bold text-primary truncate text-sm uppercase tracking-wider"><?php echo $voucher['code']; ?></h3>
                                <span class="text-[10px] font-bold text-on-surface-variant/40">Hạn: <?php echo date('d/m/y', strtotime($voucher['end_date'])); ?></span>
                            </div>
                            <p class="text-[11px] text-on-surface-variant/60 line-clamp-1 mb-3"><?php echo $voucher['description']; ?></p>
                            
                            <div class="flex items-center justify-between">
                                <button onclick="copyVoucherToProfile(this, '<?php echo $voucher['code']; ?>')" class="text-[10px] font-bold text-secondary hover:underline flex items-center gap-1 uppercase">
                                    <span class="material-symbols-outlined !text-[12px]">content_copy</span> Sao chép
                                </button>
                                <?php if($voucher['usage_status'] == 0): ?>
                                    <span class="px-2 py-1 bg-surface-container text-[8px] font-black text-primary/30 rounded-lg uppercase">Đã dùng</span>
                                <?php else: ?>
                                    <a href="<?php echo URLROOT; ?>" class="px-3 py-1.5 bg-primary text-on-primary rounded-lg text-[9px] font-bold hover:bg-secondary transition-all">DÙNG NGAY</a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Ticket Punch Effect -->
                        <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-surface rounded-full border border-outline-variant/50"></div>
                        <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-surface rounded-full border border-outline-variant/50"></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="pt-8 border-t border-outline-variant/20">
                <h2 class="font-h4 text-h4 text-on-surface mb-6 italic uppercase tracking-tighter">Thông báo khuyến mãi khác</h2>
                <div class="space-y-4">
                    <?php 
                    $hasPromoNotis = false;
                    foreach($data['notifications'] as $noti): 
                        if ($noti['type'] !== 'promotion') continue;
                        $hasPromoNotis = true;
                    ?>
                        <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/30 flex items-start gap-4">
                            <div class="bg-primary/5 p-2 rounded-lg text-primary">
                                <span class="material-symbols-outlined text-[20px]">campaign</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-primary"><?php echo $noti['title']; ?></h4>
                                <p class="text-xs text-outline mt-1"><?php echo $noti['content']; ?></p>
                                <span class="text-[10px] text-outline/50 mt-2 block"><?php echo date('d/m/Y', strtotime($noti['created_at'])); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (!$hasPromoNotis): ?>
                        <p class="text-xs text-outline italic">Không có thông báo khuyến mãi mới.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <script>
        function copyVoucherToProfile(btn, code) {
            navigator.clipboard.writeText(code).then(() => {
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<span class="material-symbols-outlined !text-[12px]">check</span> ĐÃ CHÉP';
                btn.classList.add('text-green-500');
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.classList.remove('text-green-500');
                }, 2000);
            });
        }
        </script>

        <?php elseif($data['tab'] == 'notifications'): ?>
        <!-- Full Notifications List -->
        <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="p-8 border-b border-outline-variant flex justify-between items-center bg-surface-container/10">
                <div>
                    <h2 class="font-h3 text-h3 text-on-surface"><?php echo __('your_notifications_title', 'Thông báo của bạn'); ?></h2>
                    <p class="text-outline text-sm mt-1"><?php echo __('your_notifications_desc', 'Cập nhật những tin tức mới nhất về đơn hàng và khuyến mãi.'); ?></p>
                </div>
                <button onclick="markAllAsRead()" class="text-secondary font-label-bold text-sm hover:underline flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">done_all</span> <?php echo __('mark_all_read', 'Đánh dấu đã đọc'); ?>
                </button>
            </div>

            <div class="divide-y divide-outline-variant">
                <?php if(!empty($data['notifications_paginated'])): ?>
                    <?php foreach($data['notifications_paginated'] as $noti): ?>
                        <div class="p-6 hover:bg-surface-container/30 transition-all cursor-pointer relative group <?php echo $noti['is_read'] == 0 ? 'bg-secondary/5' : ''; ?>" onclick="handleNotificationClick(this, <?php echo $noti['id']; ?>)">
                            <div class="flex gap-4">
                                <div class="w-12 h-12 rounded-2xl <?php 
                                    echo $noti['type'] === 'order' ? 'bg-blue-100 text-blue-600' : 
                                        ($noti['type'] === 'promotion' ? 'bg-amber-100 text-amber-600' : 'bg-secondary/10 text-secondary'); 
                                ?> flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined">
                                        <?php echo $noti['type'] === 'order' ? 'package_2' : ($noti['type'] === 'promotion' ? 'sell' : 'info'); ?>
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-1">
                                        <h3 class="font-label-bold text-on-surface <?php echo $noti['is_read'] == 0 ? 'text-primary' : 'text-on-surface-variant'; ?>">
                                            <?php echo $noti['title']; ?>
                                        </h3>
                                        <span class="text-[10px] text-outline font-medium whitespace-nowrap bg-surface px-2 py-0.5 rounded-full border border-outline-variant/30">
                                            <?php 
                                                echo date('d/m/Y H:i', strtotime($noti['created_at'])); 
                                            ?>
                                        </span>
                                    </div>
                                    <p class="text-sm text-on-surface-variant line-clamp-2 leading-relaxed <?php echo $noti['is_read'] == 0 ? 'font-medium' : ''; ?>">
                                        <?php echo $noti['content']; ?>
                                    </p>
                                    
                                    <?php if($noti['is_read'] == 0): ?>
                                    <div class="mt-3 flex items-center gap-2">
                                        <span class="w-2 h-2 bg-secondary rounded-full animate-pulse"></span>
                                        <span class="text-[10px] font-bold text-secondary uppercase tracking-widest"><?php echo __('new_badge', 'Mới'); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Hover Action -->
                            <div class="absolute right-6 bottom-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-secondary text-xs font-bold flex items-center gap-1">
                                    <?php echo __('view_details_btn', 'Xem chi tiết'); ?> <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Pagination Controls -->
                    <?php if($data['noti_total_pages'] > 1): ?>
                    <div class="p-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-surface-container/5">
                        <p class="text-xs text-outline">
                            <?php echo __('showing_page', 'Trang'); ?> <span class="font-bold text-on-surface"><?php echo $data['noti_page']; ?></span> / <span class="font-bold text-on-surface"><?php echo $data['noti_total_pages']; ?></span>
                            <span class="ml-2 text-outline/60">(<?php echo $data['noti_total_count']; ?> <?php echo __('notifications_unit', 'thông báo'); ?>)</span>
                        </p>
                        <div class="flex items-center gap-1.5">
                            <!-- Prev -->
                            <?php if($data['noti_page'] > 1): ?>
                                <a href="?tab=notifications&noti_page=<?php echo $data['noti_page'] - 1; ?>" class="px-3 py-2 rounded-xl text-xs font-bold text-on-surface-variant bg-surface-container hover:bg-secondary hover:text-on-secondary transition-all flex items-center gap-1 border border-outline-variant/30">
                                    <span class="material-symbols-outlined !text-[14px]">chevron_left</span> <?php echo __('prev_page', 'Trước'); ?>
                                </a>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php 
                                $startPage = max(1, $data['noti_page'] - 2);
                                $endPage = min($data['noti_total_pages'], $data['noti_page'] + 2);
                                if ($startPage > 1): 
                            ?>
                                <a href="?tab=notifications&noti_page=1" class="w-9 h-9 rounded-xl text-xs font-bold flex items-center justify-center transition-all border border-outline-variant/30 text-on-surface-variant hover:bg-secondary hover:text-on-secondary">1</a>
                                <?php if ($startPage > 2): ?>
                                    <span class="text-outline text-xs px-1">…</span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for($i = $startPage; $i <= $endPage; $i++): ?>
                                <?php if($i == $data['noti_page']): ?>
                                    <span class="w-9 h-9 rounded-xl text-xs font-bold flex items-center justify-center bg-secondary text-on-secondary shadow-md"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?tab=notifications&noti_page=<?php echo $i; ?>" class="w-9 h-9 rounded-xl text-xs font-bold flex items-center justify-center transition-all border border-outline-variant/30 text-on-surface-variant hover:bg-secondary hover:text-on-secondary"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($endPage < $data['noti_total_pages']): ?>
                                <?php if ($endPage < $data['noti_total_pages'] - 1): ?>
                                    <span class="text-outline text-xs px-1">…</span>
                                <?php endif; ?>
                                <a href="?tab=notifications&noti_page=<?php echo $data['noti_total_pages']; ?>" class="w-9 h-9 rounded-xl text-xs font-bold flex items-center justify-center transition-all border border-outline-variant/30 text-on-surface-variant hover:bg-secondary hover:text-on-secondary"><?php echo $data['noti_total_pages']; ?></a>
                            <?php endif; ?>

                            <!-- Next -->
                            <?php if($data['noti_page'] < $data['noti_total_pages']): ?>
                                <a href="?tab=notifications&noti_page=<?php echo $data['noti_page'] + 1; ?>" class="px-3 py-2 rounded-xl text-xs font-bold text-on-surface-variant bg-surface-container hover:bg-secondary hover:text-on-secondary transition-all flex items-center gap-1 border border-outline-variant/30">
                                    <?php echo __('next_page', 'Sau'); ?> <span class="material-symbols-outlined !text-[14px]">chevron_right</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="p-20 text-center">
                        <div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="material-symbols-outlined text-[40px] text-outline">notifications_off</span>
                        </div>
                        <h3 class="font-h4 text-h4 text-on-surface mb-2"><?php echo __('no_notifications', 'Không có thông báo'); ?></h3>
                        <p class="text-outline max-w-xs mx-auto"><?php echo __('no_notifications_desc', 'Bạn chưa nhận được thông báo nào. Mọi tin tức quan trọng sẽ xuất hiện ở đây!'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <script>
        async function handleNotificationClick(element, id) {
            try {
                const formData = new FormData();
                formData.append('id', id);
                
                const response = await fetch('<?php echo URLROOT; ?>/user/markNotificationsRead', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                const data = await response.json();
                if (data.status === 'success') {
                    // Update UI immediately
                    element.classList.remove('bg-secondary/5');
                    const badge = element.querySelector('.bg-secondary.rounded-full');
                    if (badge) badge.remove();
                    const title = element.querySelector('h3');
                    if (title) title.classList.remove('text-primary');
                    title.classList.add('text-on-surface-variant');
                    
                    // Refresh header badge if function exists
                    if (typeof fetchNotifications === 'function') {
                        fetchNotifications();
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
        </script>

        <?php else: ?>
        <section class="bg-surface-container-lowest p-12 rounded-2xl border border-outline-variant shadow-sm text-center">
            <span class="material-symbols-outlined text-[64px] text-outline mb-4">construction</span>
            <h2 class="font-h3 text-h3 text-on-surface mb-2"><?php echo __('under_development_title', 'Tính năng đang phát triển'); ?></h2>
            <p class="text-outline"><?php echo __('under_development_desc', 'Chúng tôi đang hoàn thiện chức năng này. Vui lòng quay lại sau!'); ?></p>
        </section>
        <?php endif; ?>
    </div>
</div>


<?php require APPROOT . '/views/layout/footer.php'; ?>
