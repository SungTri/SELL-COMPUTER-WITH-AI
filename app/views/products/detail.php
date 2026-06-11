<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="max-w-container-max mx-auto px-gutter pb-section-padding transition-colors duration-300">
    <!-- Breadcrumb (Clean & Simple) -->
    <nav class="py-6 flex items-center gap-2 text-slate-400 dark:text-zinc-500 font-body-md text-sm">
        <a class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" href="<?php echo URLROOT; ?>"><?php echo __('home_breadcrumb', 'Trang chủ'); ?></a>
        <span class="material-symbols-outlined text-[16px] opacity-65">chevron_right</span>
        <a class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" href="<?php echo URLROOT; ?>/product/category/<?php echo $data['product']['category_id']; ?>"><?php echo __($data['product']['category_name']); ?></a>
        <span class="material-symbols-outlined text-[16px] opacity-65">chevron_right</span>
        <span class="text-slate-800 dark:text-zinc-200 font-label-bold truncate max-w-[200px] sm:max-w-[400px]"><?php echo $data['product']['name']; ?></span>
    </nav>

    <!-- Product Overview Section -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-card-gap mb-section-padding">
        <!-- Gallery (Left) -->
        <div class="col-span-1 lg:col-span-7 flex flex-col gap-5">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800/80 p-8 aspect-[4/3] flex items-center justify-center relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                <img id="main-product-image" alt="<?php echo $data['product']['name']; ?>" 
                    class="object-contain w-full h-full transition-all duration-300 hover:scale-105" 
                    src="<?php echo get_product_image($data['product']['main_image']); ?>" onerror="this.src='https://placehold.co/600x450?text=Product'" />
            </div>
            
            <!-- Thumbnails Slider -->
            <div class="flex gap-4 overflow-x-auto pb-3 scrollbar-thin">
                <?php $mainImgUrl = get_product_image($data['product']['main_image']); ?>
                <button onclick="changeMainImage('<?php echo $mainImgUrl; ?>', this)" 
                        class="thumbnail-btn bg-white dark:bg-zinc-900 rounded-xl border-2 border-indigo-600 dark:border-indigo-500 p-2 aspect-square w-24 flex-shrink-0 shadow-md transition-all transform hover:scale-95 active:scale-90">
                    <img class="object-contain w-full h-full rounded-lg" src="<?php echo $mainImgUrl; ?>" onerror="this.src='https://placehold.co/100x100?text=Product'" />
                </button>
                <?php foreach($data['images'] as $img): ?>
                <?php $imgPath = get_product_image($img['image_path']); ?>
                <button onclick="changeMainImage('<?php echo $imgPath; ?>', this)" 
                        class="thumbnail-btn bg-white dark:bg-zinc-900 rounded-xl border-2 border-transparent p-2 aspect-square w-24 flex-shrink-0 shadow-sm hover:border-slate-300 dark:hover:border-zinc-700 transition-all transform hover:scale-95 active:scale-90">
                    <img class="object-contain w-full h-full rounded-lg" src="<?php echo $imgPath; ?>" onerror="this.src='https://placehold.co/100x100?text=Product'" />
                </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Product Info (Right) -->
        <div class="col-span-1 lg:col-span-5 flex flex-col gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <?php if($data['product']['stock'] > 0 && $data['product']['status'] == 1): ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <?php echo __('in_stock', 'Còn hàng'); ?> (<?php echo $data['product']['stock']; ?>)
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200/50 dark:border-rose-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            <?php echo __('out_of_stock', 'Hết hàng'); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="flex items-start justify-between gap-4 mb-2">
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white font-sans leading-tight"><?php echo $data['product']['name']; ?></h1>
                    <button type="button" onclick="toggleWishlist(this, <?php echo $data['product']['id']; ?>)" 
                            class="w-11 h-11 rounded-full border border-slate-200 dark:border-zinc-800 flex items-center justify-center transition-all duration-300 wishlist-btn shadow-sm hover:shadow-md hover:scale-105 active:scale-95 <?php echo $data['is_in_wishlist'] ? 'text-rose-500 border-rose-200 bg-rose-50 dark:bg-rose-500/15 dark:border-rose-500/30' : 'bg-white dark:bg-zinc-900 text-slate-500 dark:text-zinc-400 hover:text-rose-500 hover:border-rose-200 dark:hover:text-rose-400'; ?>" 
                            data-product-id="<?php echo $data['product']['id']; ?>" title="Thêm vào yêu thích">
                        <span class="material-symbols-outlined text-[24px] <?php echo $data['is_in_wishlist'] ? 'fill-1' : ''; ?>">favorite</span>
                    </button>
                </div>

                <div class="flex items-center gap-3 mb-4">
                    <div class="flex items-center text-amber-400 bg-amber-500/5 dark:bg-amber-400/5 px-2 py-0.5 rounded-lg border border-amber-500/10">
                        <?php 
                        $rating = $data['product']['avg_rating'] ?? 5;
                        for($i=1; $i<=5; $i++) {
                            echo '<span class="material-symbols-outlined text-[18px] ' . ($i <= floor($rating) ? 'fill-1' : '') . '">star</span>';
                        }
                        ?>
                        <span class="text-xs font-bold text-amber-600 dark:text-amber-400 ml-1.5"><?php echo number_format($rating, 1); ?></span>
                    </div>
                    <button onclick="document.getElementById('reviews-tab').click(); document.getElementById('reviews-tab').scrollIntoView({behavior: 'smooth'});" class="text-xs font-semibold text-slate-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors uppercase tracking-wider"><?php echo ($data['product']['review_count'] ?? 0) . ' ' . __('reviews_tab', 'đánh giá'); ?></button>
                </div>

                <div class="mb-6">
                    <span class="font-price-display text-4xl font-extrabold tracking-tight text-indigo-600 dark:text-indigo-400 bg-gradient-to-r from-indigo-600 to-blue-600 dark:from-indigo-400 dark:to-blue-400 bg-clip-text text-transparent"><?php echo number_format($data['product']['price'], 0, ',', '.'); ?>₫</span>
                </div>
                
                <p class="text-slate-600 dark:text-zinc-400 leading-relaxed text-sm border-b border-slate-200 dark:border-zinc-800/80 pb-6 mb-6">
                    <?php echo $data['product']['short_description']; ?>
                </p>
            </div>

            <!-- Action Buttons (Orange & Black like in image) -->
            <?php
            // Extract attributes and group them
            $groupedAttributes = [];
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $v) {
                    if (!empty($v['options'])) {
                        foreach ($v['options'] as $opt) {
                            $groupedAttributes[$opt['attribute_name']][] = $opt['attribute_value'];
                        }
                    }
                }
                foreach ($groupedAttributes as $name => $values) {
                    $groupedAttributes[$name] = array_values(array_unique($values));
                }
            }
            ?>
            <form action="<?php echo URLROOT; ?>/cart/add" method="POST" class="flex flex-col gap-3">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="product_id" value="<?php echo $data['product']['id']; ?>">
                <input type="hidden" name="variant_id" id="selected-variant-id" value="">
                
                <?php if (!empty($groupedAttributes)): ?>
                    <div class="flex flex-col gap-4 border-b border-slate-200 dark:border-zinc-800/80 pb-6 mb-6">
                        <?php foreach ($groupedAttributes as $name => $values): ?>
                            <div class="space-y-2.5 variant-attribute-row" data-attribute="<?php echo htmlspecialchars($name); ?>">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400"><?php echo htmlspecialchars($name); ?>:</span>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($values as $val): ?>
                                        <button type="button" 
                                                class="px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900 text-slate-700 dark:text-zinc-300 hover:border-slate-300 dark:hover:border-zinc-700 hover:bg-slate-100 dark:hover:bg-zinc-800/60 transition-all duration-200 variant-option-btn" 
                                                data-value="<?php echo htmlspecialchars($val); ?>">
                                            <?php echo htmlspecialchars($val); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div id="product-action-buttons" class="flex flex-col gap-3">
                    <?php if($data['product']['stock'] > 0 && $data['product']['status'] == 1): ?>
                        <button type="submit" name="action" value="add" 
                                class="w-full py-4 btn-premium-orange group text-base">
                            <div class="inner-glow-border"></div>
                            <span class="material-symbols-outlined">shopping_cart</span>
                            <?php echo __('add_to_cart_btn', 'Thêm vào giỏ hàng'); ?>
                        </button>
                        
                        <button type="submit" name="action" value="buy" 
                                class="w-full py-4 btn-premium group text-base">
                            <div class="inner-glow-border"></div>
                            <?php echo __('buy_now', 'Mua ngay'); ?>
                        </button>
                    <?php else: ?>
                        <button type="button" disabled
                                class="w-full bg-slate-100 dark:bg-zinc-800 text-slate-400 dark:text-zinc-500 font-semibold py-4 rounded-xl flex items-center justify-center gap-2 border border-slate-200/50 dark:border-zinc-700/50 cursor-not-allowed">
                            <span class="material-symbols-outlined">block</span>
                            <?php echo __('product_out_of_stock_btn', 'Sản phẩm tạm thời hết hàng'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Trust Badges -->
            <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-slate-200 dark:border-zinc-800/80">
                <div class="flex items-center gap-3 bg-slate-50 dark:bg-zinc-900/60 p-3 rounded-2xl border border-slate-100 dark:border-zinc-800/50">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <span class="material-symbols-outlined text-[20px]">local_shipping</span>
                    </div>
                    <span class="font-bold text-xs text-slate-600 dark:text-zinc-400 uppercase tracking-wider"><?php echo __('trust_factor_1_title', 'Miễn phí giao hàng'); ?></span>
                </div>
                <div class="flex items-center gap-3 bg-slate-50 dark:bg-zinc-900/60 p-3 rounded-2xl border border-slate-100 dark:border-zinc-800/50">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-[20px]">verified_user</span>
                    </div>
                    <span class="font-bold text-xs text-slate-600 dark:text-zinc-400 uppercase tracking-wider"><?php echo __('trust_factor_2_title', 'Bảo hành 24 tháng'); ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Tabs Section -->
    <section class="mb-section-padding scroll-mt-24" id="product-details">
        <div class="bg-slate-100 dark:bg-zinc-950 p-1.5 rounded-2xl border border-slate-200/60 dark:border-zinc-800/80 flex gap-1 mb-8 overflow-x-auto whitespace-nowrap scrollbar-hide max-w-lg">
            <button data-target="description" class="tab-btn flex-1 py-3 px-6 rounded-xl font-bold text-xs uppercase tracking-wider transition-all duration-300 bg-white dark:bg-zinc-900 text-indigo-600 dark:text-indigo-400 shadow-sm border border-slate-200/40 dark:border-zinc-800/60"><?php echo __('product_description_tab', 'Mô tả sản phẩm'); ?></button>
            <button id="reviews-tab" data-target="reviews" class="tab-btn flex-1 py-3 px-6 rounded-xl font-semibold text-xs uppercase tracking-wider transition-all duration-300 text-slate-500 dark:text-zinc-400 hover:text-slate-800 dark:hover:text-zinc-200"><?php echo __('reviews_tab', 'Đánh giá'); ?> (<?php echo $data['product']['review_count'] ?? 0; ?>)</button>
            <button data-target="specs" class="tab-btn flex-1 py-3 px-6 rounded-xl font-semibold text-xs uppercase tracking-wider transition-all duration-300 text-slate-500 dark:text-zinc-400 hover:text-slate-800 dark:hover:text-zinc-200"><?php echo __('specs_tab', 'Thông số kỹ thuật'); ?></button>
        </div>

        <!-- Description Tab -->
        <div id="description" class="tab-content prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-zinc-300 leading-relaxed text-sm animate-in fade-in duration-300">
            <?php echo $data['product']['detailed_description']; ?>
        </div>

        <!-- Specs Tab -->
        <div id="specs" class="tab-content hidden animate-in fade-in duration-300">
            <div class="bg-white dark:bg-zinc-900/60 rounded-2xl border border-slate-200 dark:border-zinc-800/80 overflow-hidden shadow-sm max-w-2xl">
                <table class="w-full text-left border-collapse">
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/60">
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4.5 font-bold text-slate-800 dark:text-zinc-200 w-1/3 bg-slate-50/60 dark:bg-zinc-900/80 text-xs uppercase tracking-wider"><?php echo __('brand_label', 'Thương hiệu'); ?></td>
                            <td class="px-6 py-4.5 text-sm text-slate-600 dark:text-zinc-300"><?php echo $data['product']['brand_name']; ?></td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4.5 font-bold text-slate-800 dark:text-zinc-200 w-1/3 bg-slate-50/60 dark:bg-zinc-900/80 text-xs uppercase tracking-wider"><?php echo __('category_label', 'Danh mục'); ?></td>
                            <td class="px-6 py-4.5 text-sm text-slate-600 dark:text-zinc-300"><?php echo __($data['product']['category_name']); ?></td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4.5 font-bold text-slate-800 dark:text-zinc-200 w-1/3 bg-slate-50/60 dark:bg-zinc-900/80 text-xs uppercase tracking-wider"><?php echo __('condition_label', 'Trình trạng'); ?></td>
                            <td class="px-6 py-4.5 text-sm text-slate-600 dark:text-zinc-300"><?php echo __('condition_new', 'Mới 100%, chính hãng'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reviews Tab -->
        <div id="reviews" class="tab-content hidden animate-in fade-in duration-300">
            <?php include APPROOT . '/views/products/reviews_section.php'; ?>
        </div>
    </section>

    <!-- Related Products -->
    <?php if(!empty($data['related_products'])): ?>
    <section class="mb-section-padding">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl md:text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white font-sans"><?php echo __('similar_products', 'Sản phẩm tương tự'); ?></h2>
            <a href="<?php echo URLROOT; ?>/product/category/<?php echo $data['product']['category_id']; ?>" class="text-indigo-600 dark:text-indigo-400 font-bold flex items-center gap-1 hover:gap-2 transition-all uppercase text-xs tracking-widest">
                <?php echo __('view_all_label', 'Xem tất cả'); ?> <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-card-gap">
            <?php foreach($data['related_products'] as $item): ?>
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800/80 overflow-hidden hover:shadow-2xl hover:shadow-indigo-500/5 hover:-translate-y-1.5 transition-all duration-300 group relative flex flex-col h-full">
                    <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $item['id']; ?>" class="p-6 aspect-square flex items-center justify-center relative overflow-hidden bg-slate-50/50 dark:bg-zinc-900/40">
                        <img alt="<?php echo $item['name']; ?>" class="object-contain w-full h-full group-hover:scale-105 transition-transform duration-500" src="<?php echo get_product_image($item['main_image']); ?>" onerror="this.src='https://placehold.co/400x400?text=Product'" />
                    </a>
                    <div class="p-6 flex flex-col flex-1 gap-3">
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500 font-bold uppercase tracking-widest"><?php echo $item['brand_name']; ?></p>
                        <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $item['id']; ?>" class="font-bold text-slate-800 dark:text-zinc-200 text-base line-clamp-2 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors h-12">
                            <?php echo $item['name']; ?>
                        </a>
                        <div class="mt-auto pt-2 border-t border-slate-100 dark:border-zinc-800/60">
                            <div class="text-xl font-extrabold text-indigo-600 dark:text-indigo-400 mb-4"><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</div>
                            <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $item['id']; ?>" class="w-full py-2.5 rounded-xl border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 font-bold text-xs uppercase tracking-wider hover:bg-indigo-600 hover:text-white hover:border-indigo-600 dark:hover:bg-indigo-500 dark:hover:text-white dark:hover:border-indigo-500 transition-all duration-200 text-center block">
                                <?php echo __('details_btn', 'Chi tiết'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<script>
function changeMainImage(src, btn) {
    document.getElementById('main-product-image').src = src;
    
    // Update thumbnail styles
    document.querySelectorAll('.thumbnail-btn').forEach(b => {
        b.classList.remove('border-indigo-600', 'dark:border-indigo-500');
        b.classList.add('border-transparent');
    });
    
    btn.classList.remove('border-transparent');
    btn.classList.add('border-indigo-600', 'dark:border-indigo-500');
}

document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.target;
            
            tabs.forEach(t => {
                t.classList.remove('bg-white', 'dark:bg-zinc-900', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-sm', 'border', 'border-slate-200/40', 'dark:border-zinc-800/60', 'font-bold');
                t.classList.add('text-slate-500', 'dark:text-zinc-400', 'font-semibold');
            });
            
            tab.classList.add('bg-white', 'dark:bg-zinc-900', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-sm', 'border', 'border-slate-200/40', 'dark:border-zinc-800/60', 'font-bold');
            tab.classList.remove('text-slate-500', 'dark:text-zinc-400', 'font-semibold');

            contents.forEach(c => c.classList.add('hidden'));
            const contentEl = document.getElementById(target);
            if (contentEl) {
                contentEl.classList.remove('hidden');
            }
        });
    });

    // Product Variants Selection JS Logic
    const variants = <?php 
        $formattedVariants = [];
        if (!empty($data['variants'])) {
            foreach ($data['variants'] as $v) {
                $optsAssoc = [];
                if (!empty($v['options'])) {
                    foreach ($v['options'] as $opt) {
                        $optsAssoc[$opt['attribute_name']] = $opt['attribute_value'];
                    }
                }
                $formattedVariants[] = [
                    'id' => $v['id'],
                    'sku' => $v['sku'],
                    'price' => (float)$v['price'],
                    'stock' => (int)$v['stock'],
                    'image' => get_product_image($v['image']),
                    'options' => $optsAssoc
                ];
            }
        }
        echo json_encode($formattedVariants); 
    ?>;

    if (variants.length > 0) {
        const optionButtons = document.querySelectorAll('.variant-option-btn');
        const priceDisplay = document.querySelector('.font-price-display');
        const variantIdInput = document.getElementById('selected-variant-id');

        function formatCurrency(num) {
            return num.toLocaleString('vi-VN');
        }

        function getSelectedOptions() {
            const selected = {};
            document.querySelectorAll('.variant-attribute-row').forEach(row => {
                const attrName = row.dataset.attribute;
                const activeBtn = row.querySelector('.variant-option-btn.active');
                if (activeBtn) {
                    selected[attrName] = activeBtn.dataset.value;
                }
            });
            return selected;
        }

        function selectVariant(variant) {
            if (!variant) return;

            variantIdInput.value = variant.id;
            priceDisplay.textContent = formatCurrency(variant.price) + '₫';

            if (variant.image) {
                const mainImg = document.getElementById('main-product-image');
                if (mainImg) mainImg.src = variant.image;
            }

            const stockLabelContainer = document.querySelector('.col-span-1.lg\\:col-span-5 .flex.items-center.gap-2.mb-3') || document.querySelector('.col-span-1.lg\\:col-span-5 div div.mb-3');
            if (stockLabelContainer) {
                if (variant.stock > 0) {
                    stockLabelContainer.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Còn hàng (${variant.stock})
                        </span>
                    `;
                    replaceFormButtons(true);
                } else {
                    stockLabelContainer.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200/50 dark:border-rose-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Hết hàng
                        </span>
                    `;
                    replaceFormButtons(false);
                }
            }
        }
        
        function replaceFormButtons(inStock) {
            const container = document.getElementById('product-action-buttons');
            if (!container) return;
            
            if (inStock) {
                container.innerHTML = `
                    <button type="submit" name="action" value="add" 
                            class="w-full py-4 btn-premium-orange group text-base">
                        <div class="inner-glow-border"></div>
                        <span class="material-symbols-outlined">shopping_cart</span>
                        <?php echo __('add_to_cart_btn', 'Thêm vào giỏ hàng'); ?>
                    </button>
                    
                    <button type="submit" name="action" value="buy" 
                            class="w-full py-4 btn-premium group text-base">
                        <div class="inner-glow-border"></div>
                        <?php echo __('buy_now', 'Mua ngay'); ?>
                    </button>
                `;
            } else {
                container.innerHTML = `
                    <button type="button" disabled
                            class="w-full bg-slate-100 dark:bg-zinc-800 text-slate-400 dark:text-zinc-500 font-semibold py-4 rounded-xl flex items-center justify-center gap-2 border border-slate-200/50 dark:border-zinc-700/50 cursor-not-allowed">
                        <span class="material-symbols-outlined">block</span>
                        <?php echo __('variant_out_of_stock_btn', 'Phiên bản này đã hết hàng'); ?>
                    </button>
                `;
            }
        }


        function updateSelection() {
            const selected = getSelectedOptions();
            const keys = Object.keys(selected);
            const totalAttributes = document.querySelectorAll('.variant-attribute-row').length;

            if (keys.length === totalAttributes) {
                const matched = variants.find(v => {
                    return keys.every(k => v.options[k] === selected[k]);
                });
                if (matched) {
                    selectVariant(matched);
                }
            }
        }

        optionButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('.variant-attribute-row');
                row.querySelectorAll('.variant-option-btn').forEach(b => {
                    b.classList.remove('bg-indigo-600', 'dark:bg-indigo-500', 'text-white', 'border-indigo-600', 'dark:border-indigo-500', 'shadow-md', 'shadow-indigo-500/20', 'active');
                    b.classList.add('bg-slate-50', 'dark:bg-zinc-900', 'border-slate-200', 'dark:border-zinc-800', 'text-slate-700', 'dark:text-zinc-300');
                });
                
                btn.classList.add('bg-indigo-600', 'dark:bg-indigo-500', 'text-white', 'border-indigo-600', 'dark:border-indigo-500', 'shadow-md', 'shadow-indigo-500/20', 'active');
                btn.classList.remove('bg-slate-50', 'dark:bg-zinc-900', 'border-slate-200', 'dark:border-zinc-800', 'text-slate-700', 'dark:text-zinc-300');
                
                updateSelection();
            });
        });

        // Pre-select first variant
        const firstVariant = variants[0];
        if (firstVariant) {
            Object.keys(firstVariant.options).forEach(attrName => {
                const val = firstVariant.options[attrName];
                const row = document.querySelector(`.variant-attribute-row[data-attribute="${attrName}"]`);
                if (row) {
                    const btn = row.querySelector(`.variant-option-btn[data-value="${val}"]`);
                    if (btn) {
                        btn.classList.add('bg-indigo-600', 'dark:bg-indigo-500', 'text-white', 'border-indigo-600', 'dark:border-indigo-500', 'shadow-md', 'shadow-indigo-500/20', 'active');
                        btn.classList.remove('bg-slate-50', 'dark:bg-zinc-900', 'border-slate-200', 'dark:border-zinc-800', 'text-slate-700', 'dark:text-zinc-300');
                    }
                }
            });
            selectVariant(firstVariant);
        }
    }
});
</script>

<style>
.fill-1 { font-variation-settings: 'FILL' 1; }
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<?php require APPROOT . '/views/layout/footer.php'; ?>
