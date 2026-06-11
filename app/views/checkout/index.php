<?php require_once APPROOT . '/views/layout/header.php'; ?>

<main class="bg-slate-50/30 dark:bg-zinc-950/20 min-h-screen pb-24 transition-colors duration-300">
    <!-- Header Section -->
    <section class="relative overflow-hidden bg-slate-50 dark:bg-zinc-950 py-12 border-b border-slate-200/50 dark:border-zinc-900/60 transition-colors duration-300">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-12 -left-12 w-96 h-96 bg-indigo-500/5 dark:bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -right-12 w-96 h-96 bg-blue-500/5 dark:bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-container-max mx-auto px-gutter relative z-10">
            <nav class="flex items-center gap-2 text-slate-400 dark:text-zinc-500 font-body-md text-sm mb-6">
                <a class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" href="<?php echo URLROOT; ?>"><?php echo __('home_breadcrumb', 'Trang chủ'); ?></a>
                <span class="material-symbols-outlined text-[16px] opacity-65">chevron_right</span>
                <a class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" href="<?php echo URLROOT; ?>/cart"><?php echo __('cart', 'Giỏ hàng'); ?></a>
                <span class="material-symbols-outlined text-[16px] opacity-65">chevron_right</span>
                <span class="text-slate-800 dark:text-zinc-200 font-label-bold"><?php echo __('checkout_title', 'Thanh toán'); ?></span>
            </nav>
            
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white font-sans leading-tight"><?php echo __('checkout_header', 'Hoàn tất đơn hàng'); ?></h1>
            <p class="text-slate-500 dark:text-zinc-400 text-sm mt-2 font-medium"><?php echo __('checkout_subtitle', 'Vui lòng kiểm tra kỹ thông tin trước khi nhấn đặt hàng.'); ?></p>
        </div>
    </section>

    <div class="max-w-container-max mx-auto px-gutter py-12">
        <!-- Error Messages (Stock) -->
        <?php if (isset($_SESSION['checkout_error'])): ?>
        <div class="mb-12 p-6 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 rounded-2xl flex items-start gap-5 shadow-sm animate-in slide-in-from-top-4 duration-500">
            <div class="w-12 h-12 bg-rose-100 dark:bg-rose-950/50 rounded-xl flex items-center justify-center text-rose-600 dark:text-rose-400 flex-shrink-0">
                <span class="material-symbols-outlined text-[28px]">warning</span>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-bold text-rose-800 dark:text-rose-400 mb-1"><?php echo __('stock_warning', 'Cảnh báo tồn kho'); ?></h3>
                <p class="text-sm text-rose-700 dark:text-rose-300 font-semibold leading-relaxed"><?php echo $_SESSION['checkout_error']; ?></p>
                <a href="<?php echo URLROOT; ?>/cart" class="mt-3 inline-flex items-center gap-1.5 text-xs text-rose-800 dark:text-rose-400 font-bold uppercase tracking-wider hover:underline">
                    <span><?php echo __('adjust_cart', 'Quay lại điều chỉnh giỏ hàng'); ?></span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </div>
        <?php unset($_SESSION['checkout_error']); ?>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/checkout/process" method="POST" class="flex flex-col lg:grid lg:grid-cols-12 gap-8 lg:gap-12">
            <?php echo csrf_field(); ?>
            <!-- Left Column: Shipping & Payment -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-10">
                
                <!-- Shipping Form -->
                <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-slate-200 dark:border-zinc-800/80 p-8 md:p-10 shadow-sm relative">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950 border border-indigo-100 dark:border-indigo-900 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xl">1</div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800 dark:text-zinc-100 font-sans"><?php echo __('shipping_info', 'Thông tin giao hàng'); ?></h2>
                            <p class="text-xs text-slate-400 dark:text-zinc-500 font-medium mt-0.5"><?php echo __('shipping_info_desc', 'Chúng tôi sẽ giao hàng đến địa chỉ này'); ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider mb-2.5"><?php echo __('receiver_name', 'Họ và tên người nhận'); ?></label>
                            <input type="text" name="fullName" required 
                                   class="w-full px-5 py-3.5 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all text-slate-800 dark:text-zinc-200 font-semibold text-sm placeholder:text-slate-400 dark:placeholder:text-zinc-500"
                                   placeholder="<?php echo __('receiver_name_placeholder', 'Nhập đầy đủ họ tên'); ?>" value="<?php echo htmlspecialchars($data['defaultAddress']['receiver_name'] ?? $data['userProfile']['full_name'] ?? ''); ?>">
                        </div>
                        
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider mb-2.5"><?php echo __('receiver_phone', 'Số điện thoại liên hệ'); ?></label>
                            <input type="tel" name="phone" required 
                                   class="w-full px-5 py-3.5 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all text-slate-800 dark:text-zinc-200 font-semibold text-sm placeholder:text-slate-400 dark:placeholder:text-zinc-500"
                                   placeholder="<?php echo __('receiver_phone_placeholder', 'Ví dụ: 0912345678'); ?>" value="<?php echo htmlspecialchars($data['defaultAddress']['receiver_phone'] ?? $data['userProfile']['phone'] ?? ''); ?>">
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider mb-2.5"><?php echo __('province', 'Tỉnh / Thành phố'); ?></label>
                            <div class="relative">
                                <select id="province" name="province" required class="w-full px-5 py-3.5 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all text-slate-800 dark:text-zinc-200 font-semibold text-sm appearance-none cursor-pointer bg-none">
                                    <option value=""><?php echo __('select_province', 'Chọn Tỉnh/Thành'); ?></option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider mb-2.5"><?php echo __('district', 'Quận / Huyện'); ?></label>
                            <div class="relative">
                                <select id="district" name="district" required disabled class="w-full px-5 py-3.5 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all text-slate-800 dark:text-zinc-200 font-semibold text-sm appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed bg-none">
                                    <option value=""><?php echo __('select_district', 'Chọn Quận/Huyện'); ?></option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider mb-2.5"><?php echo __('ward', 'Phường / Xã'); ?></label>
                            <div class="relative">
                                <select id="ward" name="ward" required disabled class="w-full px-5 py-3.5 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all text-slate-800 dark:text-zinc-200 font-semibold text-sm appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed bg-none">
                                    <option value=""><?php echo __('select_ward', 'Chọn Phường/Xã'); ?></option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider mb-2.5"><?php echo __('detail_address', 'Địa chỉ chi tiết (Số nhà, Tên đường)'); ?></label>
                            <textarea name="address" required rows="3"
                                      class="w-full px-5 py-3.5 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all text-slate-800 dark:text-zinc-200 font-semibold text-sm placeholder:text-slate-400 dark:placeholder:text-zinc-500 resize-none"
                                      placeholder="<?php echo __('detail_address_placeholder', 'Ví dụ: 123 Đường Lê Lợi, Tòa nhà TechExpert...'); ?>"><?php echo htmlspecialchars($data['defaultAddress']['address_detail'] ?? $data['userProfile']['address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-slate-200 dark:border-zinc-800/80 p-8 md:p-10 shadow-sm">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950 border border-indigo-100 dark:border-indigo-900 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xl">2</div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800 dark:text-zinc-100 font-sans"><?php echo __('payment_method', 'Phương thức thanh toán'); ?></h2>
                            <p class="text-xs text-slate-400 dark:text-zinc-500 font-medium mt-0.5"><?php echo __('payment_method_desc', 'Chọn cách bạn muốn thanh toán đơn hàng'); ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- COD -->
                        <label class="group relative flex items-center p-6 border-2 border-slate-200 dark:border-zinc-800/80 rounded-2xl cursor-pointer bg-slate-50/30 dark:bg-zinc-950/20 hover:bg-slate-50 dark:hover:bg-zinc-900/60 transition-all duration-200 has-[:checked]:border-indigo-600 dark:has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/15 dark:has-[:checked]:bg-indigo-950/15">
                            <input type="radio" name="payment_method" value="COD" class="w-5 h-5 text-indigo-600 dark:text-indigo-500 border-slate-200 dark:border-zinc-800 focus:ring-indigo-500/20" checked>
                            <div class="ml-5 flex-1 flex items-center justify-between">
                                <div class="pr-4">
                                    <span class="block font-bold text-slate-800 dark:text-zinc-100 text-base"><?php echo __('payment_cod', 'Tiền mặt khi nhận hàng (COD)'); ?></span>
                                    <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1"><?php echo __('payment_cod_desc', 'Giao dịch trực tiếp với nhân viên bưu tá'); ?></p>
                                </div>
                                <span class="material-symbols-outlined text-[36px] text-slate-300 dark:text-zinc-700 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">payments</span>
                            </div>
                        </label>

                        <!-- Banking -->
                        <label class="group relative flex items-center p-6 border-2 border-slate-200 dark:border-zinc-800/80 rounded-2xl cursor-pointer bg-slate-50/30 dark:bg-zinc-950/20 hover:bg-slate-50 dark:hover:bg-zinc-900/60 transition-all duration-200 has-[:checked]:border-indigo-600 dark:has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/15 dark:has-[:checked]:bg-indigo-950/15">
                            <input type="radio" name="payment_method" value="BANKING" class="w-5 h-5 text-indigo-600 dark:text-indigo-500 border-slate-200 dark:border-zinc-800 focus:ring-indigo-500/20">
                            <div class="ml-5 flex-1 flex items-center justify-between">
                                <div class="pr-4">
                                    <span class="block font-bold text-slate-800 dark:text-zinc-100 text-base"><?php echo __('payment_banking', 'Chuyển khoản ngân hàng (VietQR)'); ?></span>
                                    <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1"><?php echo __('payment_banking_desc', 'Tự động duyệt đơn ngay sau khi nhận tiền'); ?></p>
                                </div>
                                <span class="material-symbols-outlined text-[36px] text-slate-300 dark:text-zinc-700 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">account_balance</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="lg:col-span-5 xl:col-span-4">
                <div class="bg-white dark:bg-zinc-900 rounded-[32px] border border-slate-200 dark:border-zinc-800/80 p-8 shadow-xl shadow-slate-100 dark:shadow-none sticky top-32">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-zinc-100 font-sans mb-8 pb-6 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between gap-4">
                        <?php echo __('products_in_order', 'Đơn hàng của bạn'); ?>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-200/50 dark:border-zinc-750/50 whitespace-nowrap shrink-0"><?php echo count($data['cartItems']); ?> <?php echo __('items_suffix', 'sản phẩm'); ?></span>
                    </h2>

                    <!-- Items List -->
                    <div class="space-y-6 mb-8 max-h-[320px] overflow-y-auto pr-2 scrollbar-thin">
                        <?php foreach ($data['cartItems'] as $item): ?>
                        <div class="flex gap-5 items-center">
                            <div class="w-16 h-16 bg-slate-50 dark:bg-zinc-950 rounded-2xl border border-slate-100 dark:border-zinc-850/60 flex items-center justify-center p-2 relative flex-shrink-0 group">
                                <img src="<?php echo get_product_image($item['image']); ?>" 
                                     alt="Product" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500" onerror="this.src='https://placehold.co/100x100?text=PC'">
                                <span class="absolute -top-1.5 -right-1.5 bg-indigo-600 text-white text-[9px] font-black w-5.5 h-5.5 rounded-full flex items-center justify-center border-2 border-white dark:border-zinc-900 shadow-md">
                                    <?php echo $item['quantity']; ?>
                                </span>
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <h3 class="text-xs font-bold text-slate-700 dark:text-zinc-300 line-clamp-2 leading-snug hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="text-sm font-extrabold text-indigo-600 dark:text-indigo-400 mt-1.5"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Discount Section -->
                    <div class="mb-8 pt-8 border-t border-slate-100 dark:border-zinc-800/80">
                        <div class="flex justify-between items-center mb-4">
                            <label class="text-[11px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider"><?php echo __('promo_code', 'Mã giảm giá'); ?></label>
                            <button type="button" id="open-voucher-modal" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline transition-colors"><?php echo __('tab_vouchers', 'Ưu đãi của tôi'); ?></button>
                        </div>
                        <div class="flex gap-3 items-center">
                            <input type="text" id="voucher_input" 
                                   class="flex-1 min-w-0 px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-slate-800 dark:text-zinc-200 font-bold text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400/50" 
                                   placeholder="<?php echo __('promo_placeholder', 'Nhập mã khuyến mãi'); ?>" value="<?php echo $data['appliedVoucher'] ?? ''; ?>" <?php echo isset($data['appliedVoucher']) ? 'readonly' : ''; ?>>
                            <button type="button" id="apply_voucher_btn" 
                                     class="px-5 py-3 btn-premium shrink-0 whitespace-nowrap <?php echo isset($data['appliedVoucher']) ? 'hidden' : ''; ?>">
                                <div class="inner-glow-border"></div>
                                <?php echo __('apply_btn', 'Áp dụng'); ?>
                            </button>
                        </div>
                        <input type="hidden" name="voucher_code" id="hidden_voucher_code" value="<?php echo $data['appliedVoucher'] ?? ''; ?>">
                        <p id="voucher_message" class="text-xs mt-3 px-3 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 font-bold <?php echo isset($data['appliedVoucher']) ? 'block' : 'hidden'; ?>">
                            <span class="material-symbols-outlined text-[16px] align-middle mr-1.5">
                                <?php echo ($data['isFreeship'] ?? false) ? 'local_shipping' : 'check_circle'; ?>
                            </span>
                            <?php 
                                if (isset($data['appliedVoucher'])) {
                                    if ($data['isFreeship'] ?? false) {
                                        echo ($_SESSION['lang'] ?? 'vi') === 'en' ? 'Free shipping coupon applied!' : 'Đã áp dụng mã Miễn phí vận chuyển!';
                                    } else {
                                        echo ($_SESSION['lang'] ?? 'vi') === 'en' ? 'Promo code applied successfully!' : 'Áp dụng mã giảm giá thành công!';
                                    }
                                }
                            ?>
                        </p>
                    </div>

                    <!-- Calculations -->
                    <div class="space-y-4 pt-6 border-t border-slate-100 dark:border-zinc-800/80 mb-8">
                        <div class="flex justify-between items-center text-sm text-slate-500 dark:text-zinc-400">
                            <span><?php echo __('subtotal', 'Tạm tính'); ?></span>
                            <span class="font-bold text-slate-800 dark:text-zinc-200"><?php echo number_format($data['subTotal'], 0, ',', '.'); ?>đ</span>
                        </div>
                        <div class="flex justify-between items-center text-sm text-slate-500 dark:text-zinc-400">
                            <span><?php echo __('shipping_fee', 'Phí vận chuyển'); ?></span>
                            <span id="shipping_fee_display" class="font-bold <?php echo ($data['isFreeship'] ?? false) ? 'text-green-600' : 'text-slate-800 dark:text-zinc-200'; ?>">
                                <?php 
                                    if ($data['isFreeship'] ?? false) {
                                        echo __('free_shipping', 'Miễn phí');
                                    } else {
                                        echo number_format($data['shippingFee'], 0, ',', '.') . 'đ';
                                    }
                                ?>
                            </span>
                        </div>
                        <div id="discount_row" class="justify-between items-center text-sm <?php echo (isset($data['appliedVoucher']) && !($data['isFreeship'] ?? false)) ? 'flex' : 'hidden'; ?>">
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold"><?php echo __('discount', 'Giảm giá'); ?></span>
                            <span id="discount_amount" class="font-black text-indigo-600 dark:text-indigo-400">-<?php echo number_format($data['discountAmount'], 0, ',', '.'); ?>đ</span>
                        </div>
                        
                        <div class="pt-6 border-t border-slate-200 dark:border-zinc-850 mt-4">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-xs text-slate-500 dark:text-zinc-400 uppercase tracking-wider font-semibold"><?php echo __('total_payment', 'Tổng cộng'); ?></span>
                                <span id="final_total" class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400 tracking-tight font-price-display">
                                    <?php echo number_format($data['total'], 0, ',', '.'); ?>đ
                                </span>
                            </div>
                            <p class="text-right text-[10px] text-slate-400 dark:text-zinc-500 uppercase tracking-widest font-bold"><?php echo __('vat_included', 'Bao gồm thuế VAT'); ?></p>
                        </div>
                    </div>

                    <!-- Place Order -->
                    <button type="submit" class="w-full py-[22px] btn-premium group text-lg tracking-wider font-extrabold">
                        <div class="inner-glow-border"></div>
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <?php echo __('place_order', 'Đặt hàng ngay'); ?>
                            <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                        </span>
                    </button>
                    
                    <div class="mt-8 flex items-center justify-center gap-4 py-3 bg-slate-50 dark:bg-zinc-950 rounded-xl border border-slate-100 dark:border-zinc-850 text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-emerald-500">verified_user</span> <?php echo __('secure_badge', 'Bảo mật'); ?></span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-emerald-500">check_circle</span> <?php echo __('genuine_badge', 'Chính hãng'); ?></span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-emerald-500">local_shipping</span> <?php echo __('fast_ship_badge', 'Hỏa tốc'); ?></span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<?php require_once VIEWS . '/layout/voucher_modal.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Address Selection Logic
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    // Gán thông tin địa chỉ mặc định từ PHP sang JS
    window.defaultAddress = <?php echo json_encode($data['defaultAddress'] ?? null); ?>;

    // Gán danh sách phí ship của các tỉnh từ PHP sang JS
    <?php
        $feesMap = [];
        if (!empty($data['shippingFees'])) {
            foreach ($data['shippingFees'] as $fee) {
                $feesMap[$fee['province_name']] = (float)$fee['shipping_fee'];
            }
        }
    ?>
    window.provinceShippingFees = <?php echo json_encode($feesMap); ?>;

    const currentLang = '<?php echo $_SESSION['lang'] ?? 'vi'; ?>';

    function getShippingFeeByProvince(provinceName) {
        if (!provinceName) {
            return 30000;
        }

        const name = provinceName.toLowerCase()
            .replace('thành phố ', '')
            .replace('tỉnh ', '')
            .trim();

        for (let prov in window.provinceShippingFees) {
            const normalizedProv = prov.toLowerCase()
                .replace('thành phố ', '')
                .replace('tỉnh ', '')
                .trim();
            if (normalizedProv === name) {
                return window.provinceShippingFees[prov];
            }
        }

        return 30000; // Mặc định nếu không tìm thấy
    }

    async function fetchProvinces() {
        try {
            const res = await fetch('https://provinces.open-api.vn/api/p/');
            const provinces = await res.json();
            provinces.forEach(p => {
                const option = document.createElement('option');
                option.value = p.name;
                option.textContent = p.name;
                option.dataset.id = p.code;
                provinceSelect.appendChild(option);
            });

            // Tự động chọn Tỉnh/Thành phố nếu có địa chỉ mặc định
            if (window.defaultAddress && window.defaultAddress.province) {
                const provName = window.defaultAddress.province;
                for (let i = 0; i < provinceSelect.options.length; i++) {
                    if (provinceSelect.options[i].value === provName) {
                        provinceSelect.selectedIndex = i;
                        provinceSelect.dispatchEvent(new Event('change'));
                        break;
                    }
                }
            }
        } catch (error) {
            console.error('Error fetching provinces:', error);
            // Fallback
            provinceSelect.innerHTML += '<option value="Hà Nội">Hà Nội</option><option value="Hồ Chí Minh">TP. Hồ Chí Minh</option>';
        }
    }

    provinceSelect.addEventListener('change', async function() {
        const provinceId = this.options[this.selectedIndex].dataset.id;
        districtSelect.innerHTML = `<option value="">${currentLang === 'en' ? 'Select District' : 'Chọn Quận/Huyện'}</option>`;
        wardSelect.innerHTML = `<option value="">${currentLang === 'en' ? 'Select Ward' : 'Chọn Phường/Xã'}</option>`;
        wardSelect.disabled = true;

        if (!provinceId) {
            districtSelect.disabled = true;
            updateShippingAndTotals();
            return;
        }

        try {
            const res = await fetch(`https://provinces.open-api.vn/api/p/${provinceId}?depth=2`);
            const data = await res.json();
            data.districts.forEach(d => {
                const option = document.createElement('option');
                option.value = d.name;
                option.textContent = d.name;
                option.dataset.id = d.code;
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;

            // Tự động chọn Quận/Huyện nếu có địa chỉ mặc định
            if (window.defaultAddress && window.defaultAddress.district) {
                const distName = window.defaultAddress.district;
                for (let i = 0; i < districtSelect.options.length; i++) {
                    if (districtSelect.options[i].value === distName) {
                        districtSelect.selectedIndex = i;
                        districtSelect.dispatchEvent(new Event('change'));
                        break;
                    }
                }
            }
        } catch (error) {
            console.error('Error fetching districts:', error);
        }

        updateShippingAndTotals();
    });

    districtSelect.addEventListener('change', async function() {
        const districtId = this.options[this.selectedIndex].dataset.id;
        wardSelect.innerHTML = `<option value="">${currentLang === 'en' ? 'Select Ward' : 'Chọn Phường/Xã'}</option>`;

        if (!districtId) {
            wardSelect.disabled = true;
            return;
        }

        try {
            const res = await fetch(`https://provinces.open-api.vn/api/d/${districtId}?depth=2`);
            const data = await res.json();
            data.wards.forEach(w => {
                const option = document.createElement('option');
                option.value = w.name;
                option.textContent = w.name;
                wardSelect.appendChild(option);
            });
            wardSelect.disabled = false;

            // Tự động chọn Phường/Xã nếu có địa chỉ mặc định
            if (window.defaultAddress && window.defaultAddress.ward) {
                const wardName = window.defaultAddress.ward;
                for (let i = 0; i < wardSelect.options.length; i++) {
                    if (wardSelect.options[i].value === wardName) {
                        wardSelect.selectedIndex = i;
                        // Xóa sạch thông tin địa chỉ mặc định để không cản trở việc thay đổi tay sau này
                        window.defaultAddress = null;
                        break;
                    }
                }
            }
        } catch (error) {
            console.error('Error fetching wards:', error);
        }
    });

    fetchProvinces();

    // Voucher Logic
    if (typeof VoucherManager !== 'undefined') {
        VoucherManager.init({
            openBtnId: 'open-voucher-modal',
            inputId: 'voucher_input',
            applyBtnId: 'apply_voucher_btn'
        });
    }

    const applyBtn = document.getElementById('apply_voucher_btn');
    const voucherInput = document.getElementById('voucher_input');
    const hiddenVoucherCode = document.getElementById('hidden_voucher_code');
    const voucherMessage = document.getElementById('voucher_message');
    const discountRow = document.getElementById('discount_row');
    const discountAmount = document.getElementById('discount_amount');
    const finalTotal = document.getElementById('final_total');
    const openModalBtn = document.getElementById('open-voucher-modal');
    
    const subtotal = <?php echo $data['subTotal']; ?>;

    function updateShippingAndTotals() {
        const provinceVal = provinceSelect.value;
        const fee = getShippingFeeByProvince(provinceVal);

        const activeCode = hiddenVoucherCode.value.trim() || voucherInput.value.trim();
        // Check if there is an active valid voucher displayed
        const hasActiveVoucher = activeCode && voucherMessage && voucherMessage.classList.contains('block') && !voucherMessage.classList.contains('bg-red-50') && !voucherMessage.textContent.includes('không tồn tại');

        if (hasActiveVoucher) {
            reapplyVoucher(activeCode);
        } else {
            const shippingDisplay = document.getElementById('shipping_fee_display');
            if (shippingDisplay) {
                shippingDisplay.innerText = fee.toLocaleString('vi-VN') + 'đ';
                shippingDisplay.classList.remove('text-green-600');
                shippingDisplay.classList.add('text-slate-800', 'dark:text-zinc-200');
            }

            if (discountRow) {
                discountRow.classList.add('hidden');
                discountRow.classList.remove('flex');
            }

            const finalTotalEl = document.getElementById('final_total');
            if (finalTotalEl) {
                const newTotal = subtotal + fee;
                finalTotalEl.innerText = newTotal.toLocaleString('vi-VN') + 'đ';
            }
        }
    }

    function reapplyVoucher(code) {
        if (!code) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const provinceVal = provinceSelect.value;

        fetch('<?php echo URLROOT; ?>/checkout/applyVoucher', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: `code=${encodeURIComponent(code)}&subtotal=${subtotal}&province=${encodeURIComponent(provinceVal)}&csrf_token=${encodeURIComponent(csrfToken)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const shippingDisplay = document.getElementById('shipping_fee_display');
                if (data.is_freeship) {
                    if (voucherMessage) {
                        voucherMessage.innerHTML = `<span class="material-symbols-outlined text-[14px] align-middle mr-1">local_shipping</span> ${currentLang === 'en' ? 'Free shipping coupon applied!' : 'Đã áp dụng mã Miễn phí vận chuyển!'}`;
                        voucherMessage.classList.remove('hidden');
                        voucherMessage.classList.add('block');
                    }
                    if (shippingDisplay) {
                        shippingDisplay.innerText = currentLang === 'en' ? 'Free' : 'Miễn phí';
                        shippingDisplay.classList.add('text-green-600');
                        shippingDisplay.classList.remove('text-slate-800', 'dark:text-zinc-200');
                    }
                    if (discountRow) {
                        discountRow.classList.add('hidden');
                        discountRow.classList.remove('flex');
                    }
                } else {
                    if (voucherMessage) {
                        voucherMessage.innerHTML = `<span class="material-symbols-outlined text-[14px] align-middle mr-1">check_circle</span> ${currentLang === 'en' ? 'Promo code applied successfully!' : 'Áp dụng mã giảm giá thành công!'}`;
                        voucherMessage.classList.remove('hidden');
                        voucherMessage.classList.add('block');
                    }
                    if (discountRow) {
                        discountRow.classList.remove('hidden');
                        discountRow.classList.add('flex');
                    }
                    if (discountAmount) discountAmount.innerText = '-' + data.discount_display;
                    
                    if (shippingDisplay) {
                        shippingDisplay.innerText = data.shipping_fee_display;
                        shippingDisplay.classList.remove('text-green-600');
                        shippingDisplay.classList.add('text-slate-800', 'dark:text-zinc-200');
                    }
                }

                if (voucherMessage) {
                    voucherMessage.style.backgroundColor = ''; 
                    voucherMessage.className = 'text-xs mt-3 px-3 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 font-bold block';
                }

                const finalTotalEl = document.getElementById('final_total');
                if (finalTotalEl) {
                    finalTotalEl.innerText = data.new_total_display;
                }
            }
        })
        .catch(error => console.error('Recalculation error:', error));
    }
  
    applyBtn.addEventListener('click', function() {
        const code = voucherInput.value.trim();
        if (!code) return;

        applyBtn.disabled = true;
        applyBtn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[16px]">refresh</span>';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const provinceVal = provinceSelect.value;

        fetch('<?php echo URLROOT; ?>/checkout/applyVoucher', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: `code=${encodeURIComponent(code)}&subtotal=${subtotal}&province=${encodeURIComponent(provinceVal)}&csrf_token=${encodeURIComponent(csrfToken)}`
        })
        .then(response => {
            if (!response.ok) throw new Error(currentLang === 'en' ? 'Server error or unstable network' : 'Mạng không ổn định hoặc lỗi máy chủ');
            return response.json();
        })
        .then(data => {
            if (applyBtn) {
                applyBtn.disabled = false;
                applyBtn.innerText = currentLang === 'en' ? 'Apply' : 'Áp dụng';
            }
            
            if (data.status === 'success') {
                const shippingDisplay = document.getElementById('shipping_fee_display');
                if (data.is_freeship) {
                    if (voucherMessage) {
                        voucherMessage.innerHTML = `<span class="material-symbols-outlined text-[14px] align-middle mr-1">local_shipping</span> ${currentLang === 'en' ? 'Free shipping coupon applied!' : 'Đã áp dụng mã Miễn phí vận chuyển!'}`;
                        voucherMessage.classList.remove('hidden');
                        voucherMessage.classList.add('block');
                    }
                    if (shippingDisplay) {
                        shippingDisplay.innerText = currentLang === 'en' ? 'Free' : 'Miễn phí';
                        shippingDisplay.classList.add('text-green-600');
                        shippingDisplay.classList.remove('text-slate-800', 'dark:text-zinc-200');
                    }
                    if (discountRow) {
                        discountRow.classList.add('hidden');
                        discountRow.classList.remove('flex');
                    }
                } else {
                    if (voucherMessage) {
                        voucherMessage.innerHTML = `<span class="material-symbols-outlined text-[14px] align-middle mr-1">check_circle</span> ${currentLang === 'en' ? 'Promo code applied successfully!' : 'Áp dụng mã giảm giá thành công!'}`;
                        voucherMessage.classList.remove('hidden');
                        voucherMessage.classList.add('block');
                    }
                    if (discountRow) {
                        discountRow.classList.remove('hidden');
                        discountRow.classList.add('flex');
                    }
                    if (discountAmount) discountAmount.innerText = '-' + data.discount_display;
                    
                    if (shippingDisplay) {
                        shippingDisplay.innerText = data.shipping_fee_display;
                        shippingDisplay.classList.remove('text-green-600');
                        shippingDisplay.classList.add('text-slate-800', 'dark:text-zinc-200');
                    }
                }

                if (voucherMessage) {
                    voucherMessage.className = 'text-xs mt-3 px-3 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 font-bold block';
                }
                
                const finalTotalEl = document.getElementById('final_total');
                if (finalTotalEl) {
                    finalTotalEl.innerText = data.new_total_display;
                }
                
                if (hiddenVoucherCode) hiddenVoucherCode.value = code;
                
                voucherInput.readOnly = true;
                if (applyBtn) applyBtn.style.display = 'none';
                if (openModalBtn) openModalBtn.style.display = 'none';
                
                if (typeof showToast === 'function') showToast(currentLang === 'en' ? 'Promo code applied successfully!' : 'Áp dụng mã giảm giá thành công!', 'success');
            } else {
                if (voucherMessage) {
                    voucherMessage.innerText = data.message;
                    voucherMessage.classList.remove('hidden');
                    voucherMessage.classList.add('block');
                    voucherMessage.className = 'text-xs mt-3 px-3 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30 font-bold block';
                }
                if (typeof showToast === 'function') showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            if (applyBtn) {
                applyBtn.disabled = false;
                applyBtn.innerText = currentLang === 'en' ? 'Apply' : 'Áp dụng';
            }
            if (typeof showToast === 'function') showToast(currentLang === 'en' ? 'Connection error. Please try again!' : 'Lỗi kết nối máy chủ. Vui lòng thử lại!', 'error');
        });
    });

    // --- Custom Dropdown Implementation for Address Selection ---
    
    // Intercept disabled property setter to capture select.disabled = true/false
    if (!HTMLSelectElement.prototype.__disabledSetIntercepted) {
        const originalDisabledDesc = Object.getOwnPropertyDescriptor(HTMLSelectElement.prototype, 'disabled');
        Object.defineProperty(HTMLSelectElement.prototype, 'disabled', {
            get: function() {
                return originalDisabledDesc.get.call(this);
            },
            set: function(val) {
                originalDisabledDesc.set.call(this, val);
                this.dispatchEvent(new CustomEvent('disabled-change', { detail: { disabled: val } }));
            },
            configurable: true
        });
        HTMLSelectElement.prototype.__disabledSetIntercepted = true;
    }

    function normalizeVietnamese(text) {
        if (!text) return '';
        return text.toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d")
            .replace(/Đ/g, "d");
    }

    function initCustomDropdown(selectId, placeholder) {
        const select = document.getElementById(selectId);
        if (!select) return;

        const wrapper = select.parentElement;
        
        // Hide raw select & arrow icon
        select.classList.add('hidden');
        const rawIcon = wrapper.querySelector('span.material-symbols-outlined');
        if (rawIcon) {
            rawIcon.classList.add('hidden');
        }

        // Custom Trigger Button
        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'custom-dropdown-trigger w-full px-5 py-3.5 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all text-slate-800 dark:text-zinc-200 font-semibold text-left flex items-center justify-between cursor-pointer text-sm shadow-sm';
        
        const selectedText = document.createElement('span');
        selectedText.className = 'truncate pr-4';
        selectedText.textContent = placeholder;
        
        const arrow = document.createElement('span');
        arrow.className = 'material-symbols-outlined text-slate-400 transition-transform duration-300 pointer-events-none text-[20px]';
        arrow.textContent = 'expand_more';
        
        trigger.appendChild(selectedText);
        trigger.appendChild(arrow);
        wrapper.appendChild(trigger);
        
        // Custom Dropdown Panel
        const panel = document.createElement('div');
        panel.className = 'custom-dropdown-panel absolute z-50 left-0 right-0 mt-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-xl overflow-hidden hidden transform origin-top scale-95 opacity-0 transition-all duration-200 ease-out flex flex-col';
        
        // Search area
        const searchDiv = document.createElement('div');
        searchDiv.className = 'p-3 border-b border-slate-100 dark:border-zinc-800/80 sticky top-0 bg-white dark:bg-zinc-900 z-10';
        searchDiv.innerHTML = `
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-slate-400 text-lg pointer-events-none">search</span>
                <input type="text" placeholder="${selectId === 'province' ? 'Tìm tỉnh/thành...' : (selectId === 'district' ? 'Tìm quận/huyện...' : 'Tìm phường/xã...')}" class="w-full pl-9 pr-9 py-2.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none transition-all text-slate-800 dark:text-zinc-200 placeholder:text-slate-450">
                <button type="button" class="clear-search-btn absolute right-3 text-slate-400 hover:text-slate-600 dark:hover:text-zinc-350 transition-colors hidden">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
        `;
        const searchInput = searchDiv.querySelector('input');
        const clearSearchBtn = searchDiv.querySelector('.clear-search-btn');
        panel.appendChild(searchDiv);
        
        // Options list container
        const optionsContainer = document.createElement('div');
        optionsContainer.className = 'options-list max-h-60 overflow-y-auto p-2 space-y-1 scrollbar-thin';
        panel.appendChild(optionsContainer);
        wrapper.appendChild(panel);
        
        function openDropdown() {
            // Close other dropdowns
            document.querySelectorAll('.custom-dropdown-panel').forEach(p => {
                if (p !== panel) {
                    p.classList.add('hidden');
                    p.classList.remove('scale-100', 'opacity-100');
                    p.classList.add('scale-95', 'opacity-0');
                }
            });
            document.querySelectorAll('.custom-dropdown-trigger span.material-symbols-outlined').forEach(a => {
                if (a !== arrow) a.classList.remove('rotate-180');
            });

            panel.classList.remove('hidden');
            panel.offsetHeight; // force reflow
            panel.classList.remove('scale-95', 'opacity-0');
            panel.classList.add('scale-100', 'opacity-100');
            arrow.classList.add('rotate-180');
            
            setTimeout(() => searchInput.focus(), 50);
        }
        
        function closeDropdown() {
            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
            arrow.classList.remove('rotate-180');
            setTimeout(() => {
                if (panel.classList.contains('opacity-0')) {
                    panel.classList.add('hidden');
                }
            }, 200);
            searchInput.value = '';
            clearSearchBtn.classList.add('hidden');
            filterOptions('');
        }
        
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            if (panel.classList.contains('hidden')) {
                openDropdown();
            } else {
                closeDropdown();
            }
        });
        
        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                closeDropdown();
            }
        });
        
        clearSearchBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearSearchBtn.classList.add('hidden');
            filterOptions('');
            searchInput.focus();
        });
        
        function filterOptions(term) {
            const normalizedTerm = normalizeVietnamese(term);
            const items = optionsContainer.querySelectorAll('.option-item');
            items.forEach(item => {
                const text = normalizeVietnamese(item.textContent);
                if (text.includes(normalizedTerm)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }
        
        searchInput.addEventListener('input', (e) => {
            const val = e.target.value;
            if (val) {
                clearSearchBtn.classList.remove('hidden');
            } else {
                clearSearchBtn.classList.add('hidden');
            }
            filterOptions(val);
        });

        function populateOptions() {
            optionsContainer.innerHTML = '';
            
            if (select.disabled) {
                trigger.disabled = true;
                trigger.classList.add('opacity-50', 'cursor-not-allowed');
                selectedText.textContent = placeholder;
                return;
            } else {
                trigger.disabled = false;
                trigger.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            const options = Array.from(select.options);
            options.forEach(opt => {
                const item = document.createElement('div');
                item.className = 'option-item flex items-center justify-between px-4 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl text-sm font-medium cursor-pointer transition-all duration-150 text-slate-700 dark:text-zinc-300';
                
                const labelSpan = document.createElement('span');
                labelSpan.textContent = opt.textContent;
                item.appendChild(labelSpan);

                const checkIcon = document.createElement('span');
                checkIcon.className = 'material-symbols-outlined text-[16px] text-indigo-600 dark:text-indigo-400 font-bold selected-check hidden';
                checkIcon.textContent = 'check';
                item.appendChild(checkIcon);

                if (opt.value === select.value) {
                    item.classList.add('bg-indigo-50/60', 'dark:bg-indigo-950/20', 'text-indigo-600', 'dark:text-indigo-400', 'font-bold');
                    checkIcon.classList.remove('hidden');
                    selectedText.textContent = opt.textContent;
                }
                
                item.dataset.value = opt.value;
                
                item.addEventListener('click', () => {
                    select.value = opt.value;
                    select.dispatchEvent(new Event('change'));
                    closeDropdown();
                });
                
                optionsContainer.appendChild(item);
            });

            if (!select.value) {
                selectedText.textContent = placeholder;
            }
        }
        
        populateOptions();
        
        // Listen to dynamic API modifications via MutationObserver
        const observer = new MutationObserver(() => {
            populateOptions();
        });
        observer.observe(select, { childList: true });
        
        // Listen to programmatic property changes to select.disabled
        select.addEventListener('disabled-change', () => {
            populateOptions();
        });
        
        // Sync custom dropdown values when select elements trigger programmatically (e.g. initial loads)
        select.addEventListener('change', () => {
            const selectedOpt = select.options[select.selectedIndex];
            selectedText.textContent = selectedOpt ? selectedOpt.textContent : placeholder;
            
            optionsContainer.querySelectorAll('.option-item').forEach(item => {
                const checkIcon = item.querySelector('.selected-check');
                if (item.dataset.value === select.value) {
                    item.classList.add('bg-indigo-50/60', 'dark:bg-indigo-950/20', 'text-indigo-600', 'dark:text-indigo-400', 'font-bold');
                    if (checkIcon) checkIcon.classList.remove('hidden');
                } else {
                    item.classList.remove('bg-indigo-50/60', 'dark:bg-indigo-950/20', 'text-indigo-600', 'dark:text-indigo-400', 'font-bold');
                    if (checkIcon) checkIcon.classList.add('hidden');
                }
            });
        });
    }

    initCustomDropdown('province', '<?php echo __('select_province', 'Chọn Tỉnh/Thành'); ?>');
    initCustomDropdown('district', '<?php echo __('select_district', 'Chọn Quận/Huyện'); ?>');
    initCustomDropdown('ward', '<?php echo __('select_ward', 'Chọn Phường/Xã'); ?>');
});
</script>

<style>
    .scrollbar-thin::-webkit-scrollbar { width: 5px; height: 5px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.15); border-radius: 10px; }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: rgba(99, 102, 241, 0.3); }
</style>

<?php require_once APPROOT . '/views/layout/footer.php'; ?>
