<?php require_once VIEWS . '/layout/header.php'; ?>

<div class="max-w-container-max mx-auto px-gutter py-12 transition-colors duration-300">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-slate-400 dark:text-zinc-500 font-body-md text-sm mb-8">
        <a href="<?php echo URLROOT; ?>" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"><?php echo __('home_breadcrumb', 'Trang chủ'); ?></a>
        <span class="material-symbols-outlined text-[16px] opacity-65">chevron_right</span>
        <span class="text-slate-800 dark:text-zinc-200 font-label-bold"><?php echo __('cart', 'Giỏ hàng'); ?></span>
    </nav>

    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white font-sans mb-10">
        <?php echo __('cart_title', 'Giỏ hàng của bạn'); ?> 
        <span class="text-sm font-semibold ml-2 px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-200/50 dark:border-zinc-700/50 whitespace-nowrap">
            <?php echo count($data['cart_items']); ?> <?php echo __('items_suffix', 'sản phẩm'); ?>
        </span>
    </h1>

    <?php if(empty($data['cart_items'])): ?>
    <div class="relative overflow-hidden bg-white/80 dark:bg-zinc-900/60 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-zinc-800/80 p-16 text-center shadow-lg max-w-2xl mx-auto">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-12 -left-12 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="w-24 h-24 bg-gradient-to-tr from-indigo-50 to-blue-50 dark:from-indigo-950/40 dark:to-blue-950/40 rounded-full border border-indigo-100/50 dark:border-indigo-900/30 flex items-center justify-center mx-auto mb-6 shadow-inner">
            <span class="material-symbols-outlined text-[48px] text-indigo-600 dark:text-indigo-400">shopping_basket</span>
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white font-sans mb-3"><?php echo __('cart_empty', 'Giỏ hàng của bạn đang trống'); ?></h2>
        <p class="text-slate-500 dark:text-zinc-400 mb-8 max-w-md mx-auto text-sm leading-relaxed"><?php echo __('cart_empty_desc', 'Có vẻ như bạn chưa thêm sản phẩm nào vào giỏ hàng. Hãy khám phá các sản phẩm công nghệ mới nhất của chúng tôi!'); ?></p>
        <a href="<?php echo URLROOT; ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-8 py-4 rounded-xl font-bold hover:shadow-lg hover:shadow-indigo-500/20 active:scale-95 transition-all duration-200">
            <span class="material-symbols-outlined">explore</span>
            <?php echo __('explore_products', 'Khám phá sản phẩm ngay'); ?>
        </a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
        <!-- Left: Cart Items List -->
        <div class="lg:col-span-8 space-y-6">
            <?php foreach($data['cart_items'] as $key => $item): ?>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800/80 p-6 flex flex-col sm:flex-row gap-6 hover:shadow-xl hover:shadow-slate-100 dark:hover:shadow-none transition-all duration-300 group relative">
                <!-- Product Image -->
                <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $item['id']; ?>" class="w-full sm:w-40 h-40 bg-slate-50 dark:bg-zinc-900/50 rounded-2xl border border-slate-100 dark:border-zinc-850/60 overflow-hidden flex items-center justify-center p-4 cursor-pointer block shrink-0">
                    <img src="<?php echo get_product_image($item['image']); ?>" alt="<?php echo $item['name']; ?>" class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-105" onerror="this.src='https://placehold.co/300x300?text=Product'">
                </a>

                <!-- Product Details -->
                <div class="flex-1 flex flex-col justify-between" id="item-<?php echo $key; ?>" data-price="<?php echo $item['price']; ?>">
                    <div>
                        <div class="flex justify-between items-start gap-4 mb-2">
                            <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $item['id']; ?>" class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                <h3 class="text-base font-extrabold text-slate-800 dark:text-zinc-100 font-sans leading-tight"><?php echo $item['name']; ?></h3>
                            </a>
                            <button class="text-slate-400 dark:text-zinc-500 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 p-2 rounded-xl transition-all duration-200 shrink-0 remove-item" data-id="<?php echo $key; ?>" title="<?php echo __('delete_item_title', 'Xóa sản phẩm'); ?>">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed max-w-lg font-sans">
                            <?php echo $item['specs']; ?>
                        </p>
                    </div>

                    <div class="flex flex-wrap items-end justify-between gap-4 mt-4">
                        <!-- Quantity Controls -->
                        <div class="flex items-center gap-1 bg-slate-100 dark:bg-zinc-950 rounded-xl p-1 border border-slate-200/50 dark:border-zinc-800/80">
                            <button class="w-8 h-8 flex items-center justify-center text-slate-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-white dark:hover:bg-zinc-900 transition-all rounded-lg decrease-qty" data-id="<?php echo $key; ?>">
                                <span class="material-symbols-outlined text-[18px]">remove</span>
                            </button>
                            <input type="text" value="<?php echo $item['quantity']; ?>" class="w-10 text-center bg-transparent border-none focus:ring-0 text-sm font-bold text-slate-800 dark:text-zinc-200 qty-input" data-id="<?php echo $key; ?>" readonly>
                            <button class="w-8 h-8 flex items-center justify-center text-slate-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-white dark:hover:bg-zinc-900 transition-all rounded-lg increase-qty" data-id="<?php echo $key; ?>">
                                <span class="material-symbols-outlined text-[18px]">add</span>
                            </button>
                        </div>

                        <!-- Price Info -->
                        <div class="text-right">
                            <p class="text-[11px] font-semibold tracking-wider text-slate-400 dark:text-zinc-500 uppercase mb-0.5"><?php echo __('unit_price', 'Đơn giá'); ?>: <?php echo number_format($item['price'], 0, ',', '.'); ?>đ</p>
                            <p class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400 font-price-display item-total" data-id="<?php echo $key; ?>"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Navigation and Actions -->
            <div class="flex justify-between items-center mt-8 pt-4 border-t border-slate-100 dark:border-zinc-800/60">
                <a href="<?php echo URLROOT; ?>" class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-bold hover:gap-3 transition-all text-xs uppercase tracking-widest group">
                    <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                    <?php echo __('continue_shopping', 'Tiếp tục mua sắm'); ?>
                </a>
                
                <a href="<?php echo URLROOT; ?>/cart/emptyCart" 
                   class="inline-flex items-center gap-2 text-rose-600 dark:text-rose-400 bg-rose-500/5 hover:bg-rose-500/10 px-4 py-2.5 rounded-xl border border-rose-500/10 hover:border-rose-500/20 font-bold hover:gap-3 transition-all text-xs uppercase tracking-widest">
                    <span class="material-symbols-outlined text-[18px]">delete_sweep</span>
                    <?php echo __('cart_clear_all', 'Xóa tất cả'); ?>
                </a>
            </div>
        </div>

        <!-- Right: Order Summary Sidebar -->
        <div class="lg:col-span-4 space-y-6 sticky top-24">
            <div class="bg-slate-50 dark:bg-zinc-900/60 backdrop-blur-md rounded-2xl border border-slate-200 dark:border-zinc-800/80 p-8 shadow-sm">
                <h2 class="text-lg font-bold text-slate-800 dark:text-zinc-100 font-sans mb-8 border-b border-slate-200/65 dark:border-zinc-800 pb-4"><?php echo __('order_summary', 'Tóm tắt đơn hàng'); ?></h2>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between text-sm text-slate-500 dark:text-zinc-400">
                        <span><?php echo __('subtotal', 'Tạm tính'); ?></span>
                        <span class="font-bold text-slate-800 dark:text-zinc-200" id="summary-subtotal"><?php echo $data['subtotal']; ?>đ</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500 dark:text-zinc-400">
                        <span><?php echo __('shipping_fee', 'Phí vận chuyển'); ?></span>
                        <span class="text-emerald-600 dark:text-emerald-400 font-bold"><?php echo __('free_shipping', 'Miễn phí'); ?></span>
                    </div>
                    <div id="discount-row" class="hidden justify-between text-sm text-emerald-600 dark:text-emerald-400 font-bold">
                        <span><?php echo __('discount', 'Giảm giá'); ?></span>
                        <span id="discount-amount">-0đ</span>
                    </div>
                </div>

                <!-- Promo Code -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-[11px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider"><?php echo __('promo_code', 'Mã giảm giá'); ?></label>
                        <button type="button" id="open-voucher-modal" class="text-xs text-indigo-600 dark:text-indigo-400 font-bold hover:underline transition-colors"><?php echo __('select_promo', 'Chọn mã'); ?></button>
                    </div>
                    <div class="flex gap-2 items-center">
                        <input type="text" id="promo-input" placeholder="<?php echo __('promo_placeholder', 'Nhập mã...'); ?>" class="flex-1 min-w-0 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none transition-all dark:text-zinc-200">
                        <button id="apply-promo" class="px-5 py-3 btn-premium shrink-0 whitespace-nowrap">
                            <div class="inner-glow-border"></div>
                            <?php echo __('apply_btn', 'Áp dụng'); ?>
                        </button>
                    </div>
                    <p id="promo-feedback" class="text-[12px] mt-2 hidden"></p>
                </div>

                <div class="border-t border-slate-200 dark:border-zinc-800 pt-6 mb-8">
                    <div class="flex flex-col gap-1.5">
                        <span class="text-xs text-slate-500 dark:text-zinc-400 uppercase tracking-wider font-semibold"><?php echo __('total_payment', 'Tổng cộng'); ?></span>
                        <span class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400 leading-none font-price-display" id="summary-total"><?php echo $data['total']; ?>đ</span>
                    </div>
                </div>

                <button onclick="location.href='<?php echo URLROOT; ?>/checkout'" class="w-full py-4 px-6 btn-premium group text-base">
                    <div class="inner-glow-border"></div>
                    <?php echo __('checkout_now', 'Thanh toán ngay'); ?>
                    <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                </button>

                <!-- Payment Methods -->
                <div class="mt-8 text-center border-t border-slate-100 dark:border-zinc-800/60 pt-6">
                    <p class="text-[10px] text-slate-400 dark:text-zinc-500 mb-4 uppercase tracking-wider font-bold"><?php echo __('supported_payment', 'PHƯƠNG THỨC HỖ TRỢ'); ?></p>
                    <div class="flex justify-center gap-3">
                        <div class="w-12 h-8 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700/50 rounded-lg flex items-center justify-center transition-all cursor-pointer shadow-sm hover:border-slate-400 dark:hover:border-zinc-500 text-slate-700 dark:text-zinc-300 font-extrabold text-[10px]">
                            VISA
                        </div>
                        <div class="w-12 h-8 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700/50 rounded-lg flex items-center justify-center transition-all cursor-pointer shadow-sm hover:border-slate-400 dark:hover:border-zinc-500 text-slate-700 dark:text-zinc-300 font-extrabold text-[10px]">
                            MC
                        </div>
                        <div class="w-12 h-8 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700/50 rounded-lg flex items-center justify-center transition-all cursor-pointer shadow-sm hover:border-slate-400 dark:hover:border-zinc-500 text-pink-600 font-extrabold text-[10px]">
                            MOMO
                        </div>
                        <div class="w-12 h-8 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700/50 rounded-lg flex items-center justify-center transition-all cursor-pointer shadow-sm hover:border-slate-400 dark:hover:border-zinc-500 text-slate-700 dark:text-zinc-300 font-extrabold text-[10px]">
                            BANK
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800/80 p-5 flex flex-col gap-4 shadow-sm">
                <div class="flex items-center gap-3 text-slate-600 dark:text-zinc-400">
                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">verified_user</span>
                    <span class="text-xs font-semibold"><?php echo __('secure_payment', 'Bảo mật thanh toán 100%'); ?></span>
                </div>
                <div class="flex items-center gap-3 text-slate-600 dark:text-zinc-400">
                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">local_shipping</span>
                    <span class="text-xs font-semibold"><?php echo __('fast_delivery', 'Giao hàng hỏa tốc trong 2h'); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentLang = '<?php echo $_SESSION['lang'] ?? 'vi'; ?>';

    const updateTotals = () => {
        let subtotal = 0;
        document.querySelectorAll('.item-total').forEach(el => {
            subtotal += parseInt(el.textContent.replace(/\D/g, ''));
        });
        
        const subtotalEl = document.getElementById('summary-subtotal');
        subtotalEl.textContent = subtotal.toLocaleString('vi-VN') + 'đ';
        
        let total = subtotal;
        const discountRow = document.getElementById('discount-row');
        if (!discountRow.classList.contains('hidden')) {
            const discountAmountEl = document.getElementById('discount-amount');
            const discountValue = parseInt(discountAmountEl.textContent.replace(/\D/g, ''));
            total -= discountValue;
        }
        
        document.getElementById('summary-total').textContent = total.toLocaleString('vi-VN') + 'đ';
        
        const itemCount = document.querySelectorAll('.qty-input').length;
        if (itemCount === 0) {
            location.reload();
        }
    };

    const syncQuantity = (id, qty, inputElement, previousValue) => {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('quantity', qty);
        
        fetch('<?php echo URLROOT; ?>/cart/updateQuantity', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'error') {
                showToast(data.message, 'error');
                if (inputElement) {
                    inputElement.value = data.max_qty || previousValue;
                    const itemDetails = document.getElementById(`item-${id}`);
                    const basePrice = parseInt(itemDetails.dataset.price);
                    const totalEl = document.querySelector(`.item-total[data-id="${id}"]`);
                    totalEl.textContent = (basePrice * parseInt(inputElement.value)).toLocaleString('vi-VN') + 'đ';
                    updateTotals();
                }
            }
        });
    };

    // Increase quantity
    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.onclick = function() {
            const id = this.dataset.id;
            const input = document.querySelector(`.qty-input[data-id="${id}"]`);
            const itemDetails = document.getElementById(`item-${id}`);
            const basePrice = parseInt(itemDetails.dataset.price);
            const totalEl = document.querySelector(`.item-total[data-id="${id}"]`);
            let qty = parseInt(input.value) + 1;
            input.value = qty;
            totalEl.textContent = (basePrice * qty).toLocaleString('vi-VN') + 'đ';
            updateTotals();
            syncQuantity(id, qty, input, qty - 1);
        };
    });

    // Decrease quantity
    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.onclick = function() {
            const id = this.dataset.id;
            const input = document.querySelector(`.qty-input[data-id="${id}"]`);
            const itemDetails = document.getElementById(`item-${id}`);
            const basePrice = parseInt(itemDetails.dataset.price);
            let qty = parseInt(input.value);
            if (qty > 1) {
                qty--;
                input.value = qty;
                const totalEl = document.querySelector(`.item-total[data-id="${id}"]`);
                totalEl.textContent = (basePrice * qty).toLocaleString('vi-VN') + 'đ';
                updateTotals();
                syncQuantity(id, qty, input, qty + 1);
            }
        };
    });

    // Remove item
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.onclick = function() {
            const id = this.dataset.id;
            const card = this.closest('.bg-white, .dark\\:bg-zinc-900');
            card.style.opacity = '0.5';
            card.style.pointerEvents = 'none';
            
            const formData = new FormData();
            formData.append('id', id);
            fetch('<?php echo URLROOT; ?>/cart/removeItem', { 
                method: 'POST', 
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    card.remove();
                    updateTotals();
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(data.cart_count);
                    } else if (document.getElementById('header-cart-count')) {
                        document.getElementById('header-cart-count').textContent = data.cart_count;
                    }
                    showToast(currentLang === 'en' ? 'Product removed from cart' : 'Đã xóa sản phẩm khỏi giỏ hàng', 'success');
                } else {
                    card.style.opacity = '1';
                    card.style.pointerEvents = 'auto';
                    showToast(data.message || (currentLang === 'en' ? 'Error removing product' : 'Lỗi khi xóa sản phẩm'), 'error');
                }
            })
            .catch(err => {
                card.style.opacity = '1';
                card.style.pointerEvents = 'auto';
                showToast(currentLang === 'en' ? 'Connection error' : 'Lỗi kết nối', 'error');
            });
        };
    });

    // Initialize Shared Voucher Modal
    VoucherManager.init({
        openBtnId: 'open-voucher-modal',
        inputId: 'promo-input',
        applyBtnId: 'apply-promo'
    });

    const promoInput = document.getElementById('promo-input');
    const applyBtn = document.getElementById('apply-promo');
    const feedback = document.getElementById('promo-feedback');
    const discountRow = document.getElementById('discount-row');
    const discountAmount = document.getElementById('discount-amount');

    applyBtn.onclick = function() {
        const code = promoInput.value.trim();
        if (!code) return;
        const subtotalValue = parseInt(document.getElementById('summary-subtotal').textContent.replace(/\D/g, ''));
        applyBtn.disabled = true;
        fetch('<?php echo URLROOT; ?>/checkout/applyVoucher', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded', 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: `code=${encodeURIComponent(code)}&subtotal=${subtotalValue}`
        })
        .then(response => response.json())
        .then(data => {
            applyBtn.disabled = false;
            feedback.classList.remove('hidden');
            if (data.status === 'success') {
                feedback.textContent = currentLang === 'en' ? 'Promo code applied successfully!' : 'Áp dụng mã thành công!';
                feedback.className = 'text-[12px] mt-2 text-green-600 font-bold block';
                discountRow.classList.remove('hidden');
                discountRow.classList.add('flex');
                discountAmount.textContent = '-' + data.discount_display;
                document.getElementById('summary-total').textContent = data.new_total_display;
                promoInput.readOnly = true;
                applyBtn.classList.add('hidden');
            } else {
                feedback.textContent = data.message;
                feedback.className = 'text-[12px] mt-2 text-error block';
                discountRow.classList.add('hidden');
                updateTotals();
            }
        });
    };
});
</script>

<?php require_once VIEWS . '/layout/voucher_modal.php'; ?>

<?php require_once VIEWS . '/layout/footer.php'; ?>
