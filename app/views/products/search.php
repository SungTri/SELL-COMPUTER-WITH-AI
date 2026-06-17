<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="bg-surface-container-low min-h-screen pb-24">
    <!-- Search Banner Section -->
    <section class="bg-white border-b border-outline-variant pt-12 pb-16 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-secondary/5 to-transparent"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
        
        <div class="max-w-container-max mx-auto px-gutter relative z-10">
            <nav class="flex items-center gap-2 text-on-surface-variant font-body-md text-sm mb-6">
                <a class="hover:text-primary transition-colors" href="<?php echo URLROOT; ?>"><?php echo __('home_breadcrumb', 'Trang chủ'); ?></a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-primary font-label-bold"><?php echo __('search_title', 'Tìm kiếm'); ?></span>
            </nav>
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <?php if (isset($data['ai_prompt'])): ?>
                        <div class="inline-flex items-center gap-2 bg-secondary/10 text-secondary px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-widest mb-4">
                            <span class="material-symbols-outlined !text-[16px] animate-pulse">magic_button</span>
                            AI Interpretation
                        </div>
                        <h1 class="font-h1 text-h2 md:text-h1 font-bold text-primary mb-2">
                            <?php echo __('search_interpreting', 'Bạn đang tìm:'); ?> <span class="text-secondary">"<?php echo htmlspecialchars($data['ai_prompt']); ?>"</span>
                        </h1>
                        <p class="text-on-surface-variant text-lg"><?php echo __('search_ai_desc', 'AI đã phân tích và tìm thấy'); ?> <span class="font-bold text-primary"><?php echo count($data['products']); ?></span> <?php echo __('search_products_matched', 'sản phẩm phù hợp.'); ?></p>
                    <?php elseif (!empty($data['is_sale'])): ?>
                        <h1 class="font-h1 text-h2 md:text-h1 font-bold text-primary mb-2">
                            <?php echo __('search_sales_title', 'Chương trình <span class="text-secondary">Siêu Ưu Đãi & Khuyến Mãi</span>'); ?>
                        </h1>
                        <p class="text-on-surface-variant text-lg"><?php echo __('search_sales_desc', 'Tìm thấy'); ?> <span class="font-bold text-primary"><?php echo count($data['products']); ?></span> <?php echo __('search_sales_matched', 'sản phẩm đang có giá cực tốt.'); ?></p>
                    <?php elseif (empty($data['keyword'])): ?>
                        <h1 class="font-h1 text-h2 md:text-h1 font-bold text-primary mb-2">
                            <?php echo __('all_products', 'Tất cả sản phẩm'); ?>
                        </h1>
                        <p class="text-on-surface-variant text-lg"><?php echo __('all_products_desc', 'Khám phá'); ?> <span class="font-bold text-primary"><?php echo count($data['products']); ?></span> <?php echo __('all_products_suffix', 'sản phẩm chất lượng cao tại cửa hàng.'); ?></p>
                    <?php else: ?>
                        <h1 class="font-h1 text-h2 md:text-h1 font-bold text-primary mb-2">
                            <?php echo __('search_results_for', 'Kết quả cho:'); ?> <span class="text-secondary">"<?php echo htmlspecialchars($data['keyword']); ?>"</span>
                        </h1>
                        <p class="text-on-surface-variant text-lg"><?php echo __('search_found_desc', 'Tìm thấy'); ?> <span class="font-bold text-primary"><?php echo count($data['products']); ?></span> <?php echo __('search_found_suffix', 'sản phẩm phù hợp.'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-container-max mx-auto px-gutter py-12">
        <form id="filterForm" action="<?php echo URLROOT; ?>/product/<?php echo !empty($data['is_sale']) ? 'index' : (empty($data['keyword']) ? 'index' : 'search'); ?>" method="GET" class="flex flex-col lg:flex-row gap-8">
            <?php if(!empty($data['is_sale'])): ?>
                <input type="hidden" name="sale" value="1">
            <?php endif; ?>
            <input type="hidden" name="q" value="<?php echo htmlspecialchars($data['keyword'] ?? ''); ?>">
            
            <!-- Sidebar: Filters -->
            <aside class="w-full lg:w-[280px] flex-shrink-0 space-y-8">
                <div class="space-y-8 sticky top-32">
                    <!-- Categories Filter (Static Links) -->
                    <div class="bg-white rounded-2xl border border-outline-variant p-6 shadow-sm">
                        <h3 class="font-label-bold text-sm uppercase tracking-widest text-primary mb-6 flex items-center justify-between">
                            <?php echo __('footer_explore', 'Danh mục'); ?>
                            <span class="material-symbols-outlined text-[18px] text-outline">category</span>
                        </h3>
                        <ul class="space-y-4">
                            <?php foreach($data['categories'] as $cat): ?>
                            <li>
                                <a href="<?php echo URLROOT; ?>/product/category/<?php echo $cat['id']; ?>" 
                                   class="flex items-center justify-between text-on-surface-variant hover:text-secondary group transition-all">
                                    <span class="text-[14px] group-hover:translate-x-1 transition-transform"><?php echo __($cat['name']); ?></span>
                                    <span class="bg-surface-container text-[10px] px-2 py-0.5 rounded-full group-hover:bg-secondary group-hover:text-white transition-colors">
                                        <?php echo count($cat['brands'] ?? []); ?>+
                                    </span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Brands Filter -->
                    <div class="bg-white rounded-2xl border border-outline-variant p-6 shadow-sm">
                        <h3 class="font-label-bold text-sm uppercase tracking-widest text-primary mb-6 flex items-center justify-between">
                            <?php echo __('filter_brand', 'Thương hiệu'); ?>
                            <span class="material-symbols-outlined text-[18px] text-outline">verified</span>
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach($data['brands'] as $brand): ?>
                            <label class="cursor-pointer">
                                <input type="checkbox" name="brand[]" value="<?php echo $brand['id']; ?>" 
                                       class="hidden peer" onchange="this.form.submit()"
                                       <?php echo (isset($data['filters']['brand']) && (is_array($data['filters']['brand']) ? in_array($brand['id'], $data['filters']['brand']) : $data['filters']['brand'] == $brand['id'])) ? 'checked' : ''; ?>>
                                <div class="px-4 py-2 bg-surface-container-low peer-checked:bg-secondary peer-checked:text-white hover:bg-secondary/10 hover:text-secondary border border-outline-variant/30 rounded-lg text-[13px] font-medium transition-all">
                                    <?php echo $brand['name']; ?>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="bg-white rounded-2xl border border-outline-variant p-6 shadow-sm">
                        <h3 class="font-label-bold text-sm uppercase tracking-widest text-primary mb-6"><?php echo __('filter_price_range', 'Khoảng giá'); ?></h3>
                        <div class="space-y-4">
                            <div class="flex gap-2">
                                <input type="number" name="price_min" placeholder="<?php echo __('filter_price_from', 'Từ'); ?>" 
                                       value="<?php echo $data['filters']['price_min']; ?>"
                                       class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none transition-all">
                                <input type="number" name="price_max" placeholder="<?php echo __('filter_price_to', 'Đến'); ?>" 
                                       value="<?php echo $data['filters']['price_max']; ?>"
                                       class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none transition-all">
                            </div>
                            <button type="submit" class="w-full py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-secondary transition-all"><?php echo __('filter_apply', 'Áp dụng'); ?></button>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Results Grid -->
            <div class="flex-1">
                <?php if(!empty($data['products'])): ?>
                    <!-- Sorting Bar -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-outline-variant dark:border-zinc-700/60 p-4 mb-8 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3 text-sm text-on-surface-variant">
                            <span class="text-sm text-outline font-medium"><?php echo __('sort_by', 'Sắp xếp:'); ?></span>
                            <input type="hidden" name="sort" id="sort-value" value="<?php echo $data['filters']['sort'] ?? 'newest'; ?>">
                            
                            <div class="relative inline-block text-left" id="custom-sort-dropdown">
                                <button type="button" onclick="toggleCustomDropdown()" class="flex items-center justify-between gap-3 px-4 py-2 bg-surface-container-lowest border border-outline-variant dark:border-zinc-700/50 rounded-xl text-sm font-bold text-primary dark:text-zinc-100 hover:border-secondary hover:text-secondary transition-all shadow-sm cursor-pointer min-w-[190px] select-none">
                                    <span id="selected-sort-label">
                                        <?php 
                                        $currentSort = $data['filters']['sort'] ?? 'newest';
                                        if ($currentSort === 'price_low' || $currentSort === 'price_asc') {
                                            echo __('sort_price_low', 'Từ rẻ nhất đến đắt nhất');
                                        } elseif ($currentSort === 'price_high' || $currentSort === 'price_desc') {
                                            echo __('sort_price_high', 'Từ đắt nhất đến rẻ nhất');
                                        } elseif ($currentSort === 'popular') {
                                            echo __('sort_popular', 'Phổ biến nhất');
                                        } else {
                                            echo __('sort_newest', 'Mới nhất');
                                        }
                                        ?>
                                    </span>
                                    <span class="material-symbols-outlined text-outline transition-transform duration-300" id="dropdown-chevron">keyboard_arrow_down</span>
                                </button>
                                
                                <div id="dropdown-menu-list" class="hidden absolute left-0 mt-2 w-56 rounded-2xl bg-white dark:bg-zinc-900 border border-outline-variant dark:border-zinc-800 shadow-2xl z-50 p-1.5 focus:outline-none animate-in fade-in slide-in-from-top-2 duration-200">
                                    <div class="py-1" role="none">
                                        <button type="button" onclick="selectSortOption('newest')" class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all flex items-center justify-between cursor-pointer <?php echo ($data['filters']['sort'] ?? 'newest') == 'newest' ? 'bg-secondary/10 text-secondary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'; ?>">
                                            <span><?php echo __('sort_newest', 'Mới nhất'); ?></span>
                                            <?php if (($data['filters']['sort'] ?? 'newest') == 'newest'): ?>
                                                <span class="material-symbols-outlined text-[18px]">check</span>
                                            <?php endif; ?>
                                        </button>
                                        <button type="button" onclick="selectSortOption('price_asc')" class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all flex items-center justify-between cursor-pointer <?php echo (($data['filters']['sort'] ?? 'newest') == 'price_low' || ($data['filters']['sort'] ?? 'newest') == 'price_asc') ? 'bg-secondary/10 text-secondary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'; ?>">
                                            <span><?php echo __('sort_price_low', 'Từ rẻ nhất đến đắt nhất'); ?></span>
                                            <?php if (($data['filters']['sort'] ?? 'newest') == 'price_low' || ($data['filters']['sort'] ?? 'newest') == 'price_asc'): ?>
                                                <span class="material-symbols-outlined text-[18px]">check</span>
                                            <?php endif; ?>
                                        </button>
                                        <button type="button" onclick="selectSortOption('price_desc')" class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all flex items-center justify-between cursor-pointer <?php echo (($data['filters']['sort'] ?? 'newest') == 'price_high' || ($data['filters']['sort'] ?? 'newest') == 'price_desc') ? 'bg-secondary/10 text-secondary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'; ?>">
                                            <span><?php echo __('sort_price_high', 'Từ đắt nhất đến rẻ nhất'); ?></span>
                                            <?php if (($data['filters']['sort'] ?? 'newest') == 'price_high' || ($data['filters']['sort'] ?? 'newest') == 'price_desc'): ?>
                                                <span class="material-symbols-outlined text-[18px]">check</span>
                                            <?php endif; ?>
                                        </button>
                                        <button type="button" onclick="selectSortOption('popular')" class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all flex items-center justify-between cursor-pointer <?php echo ($data['filters']['sort'] ?? 'newest') == 'popular' ? 'bg-secondary/10 text-secondary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'; ?>">
                                            <span><?php echo __('sort_popular', 'Phổ biến nhất'); ?></span>
                                            <?php if (($data['filters']['sort'] ?? 'newest') == 'popular'): ?>
                                                <span class="material-symbols-outlined text-[18px]">check</span>
                                            <?php endif; ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-[12px] text-outline font-medium">
                            <?php echo __('show_products_count', 'Đang hiển thị'); ?> <?php echo count($data['products']); ?> <?php echo __('show_products_suffix', 'sản phẩm'); ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-card-gap">
                        <?php foreach($data['products'] as $product): ?>
                        <div class="group bg-white rounded-2xl border border-outline-variant overflow-hidden hover:shadow-2xl hover:border-secondary/30 transition-all duration-500 flex flex-col h-full animate-in fade-in slide-in-from-bottom-4 duration-700">
                            <!-- Image Container -->
                            <div class="relative aspect-square overflow-hidden bg-white p-8">
                                <img src="<?php echo get_product_image($product['main_image']); ?>" 
                                     alt="<?php echo $product['name']; ?>" 
                                     class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700" onerror="this.src='https://placehold.co/400x400?text=Product'" />
                                
                                <!-- Hover Actions -->
                                <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                                    <button type="button" onclick="addToCart(<?php echo $product['id']; ?>)" 
                                            class="w-12 h-12 bg-white text-primary rounded-xl flex items-center justify-center shadow-xl hover:bg-secondary hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-300">
                                        <span class="material-symbols-outlined">add_shopping_cart</span>
                                    </button>
                                </div>
                            </div>

                            <div class="p-6 flex flex-col flex-1 border-t border-outline-variant/50">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-[10px] font-black text-outline uppercase tracking-widest"><?php echo $product['brand_name']; ?></p>
                                    <div class="flex text-amber-400">
                                        <span class="material-symbols-outlined text-[14px] fill-1">star</span>
                                        <span class="text-[10px] font-bold text-on-surface-variant ml-1">5.0</span>
                                    </div>
                                </div>
                                
                                <h3 class="font-h3 text-lg text-primary mb-4 line-clamp-2 hover:text-secondary transition-colors">
                                    <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a>
                                </h3>
                                
                                <div class="mt-auto pt-4 border-t border-outline-variant/50 flex flex-col gap-3">
                                    <span class="text-2xl font-black text-secondary block">
                                        <?php 
                                        if (($_SESSION['lang'] ?? 'vi') === 'en') {
                                            echo number_format($product['price'], 0, '.', ',') . 'đ';
                                        } else {
                                            echo number_format($product['price'], 0, ',', '.') . 'đ';
                                        }
                                        ?>
                                    </span>
                                    
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex gap-2">
                                            <button type="button" onclick="toggleCompare(<?php echo $product['id']; ?>)" 
                                                    class="w-10 h-10 bg-surface-container-low rounded-xl border border-outline-variant/50 flex items-center justify-center transition-all text-on-surface-variant hover:text-secondary compare-btn"
                                                    data-id="<?php echo $product['id']; ?>" title="<?php echo __('compare_title', 'So sánh'); ?>">
                                                <span class="material-symbols-outlined text-[20px]">compare_arrows</span>
                                            </button>
                                            <button type="button" onclick="toggleWishlist(this, <?php echo $product['id']; ?>)" 
                                                    class="w-10 h-10 bg-surface-container-low rounded-xl border border-outline-variant/50 flex items-center justify-center transition-all wishlist-btn <?php echo in_array($product['id'], $data['wishlist_ids'] ?? []) ? 'text-red-500 bg-red-50 border-red-200' : 'text-on-surface-variant hover:text-red-500'; ?>"
                                                    data-product-id="<?php echo $product['id']; ?>">
                                                <span class="material-symbols-outlined text-[20px] <?php echo in_array($product['id'], $data['wishlist_ids'] ?? []) ? 'fill-1' : ''; ?>">favorite</span>
                                            </button>
                                        </div>
                                        <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $product['id']; ?>" 
                                           class="w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center hover:bg-secondary transition-all shadow-lg shadow-primary/10 flex-shrink-0">
                                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="py-24 text-center bg-white rounded-[32px] border-2 border-dashed border-outline-variant shadow-sm animate-in zoom-in-95 duration-500">
                        <div class="w-32 h-32 bg-surface-container-low rounded-full flex items-center justify-center mx-auto mb-8">
                            <span class="material-symbols-outlined text-[64px] text-outline-variant animate-pulse">search_off</span>
                        </div>
                        <h3 class="text-2xl font-h2 text-primary mb-3"><?php echo __('no_products_found', 'Không tìm thấy sản phẩm nào'); ?></h3>
                        <p class="text-on-surface-variant max-w-md mx-auto mb-8"><?php echo __('no_products_found_desc_search', 'Chúng tôi đã tìm kiếm kỹ nhưng không thấy kết quả phù hợp với bộ lọc của bạn.'); ?></p>
                        <a href="<?php echo URLROOT; ?>/product/search?q=<?php echo urlencode($data['keyword']); ?>" class="inline-flex items-center gap-2 bg-primary text-white px-10 py-4 rounded-xl font-bold hover:bg-secondary transition-all shadow-xl shadow-primary/20 transform active:scale-95">
                            <?php echo __('filter_clear_all', 'Xóa bộ lọc'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

        <script>
            function toggleCustomDropdown() {
                const menu = document.getElementById('dropdown-menu-list');
                const chevron = document.getElementById('dropdown-chevron');
                if (menu.classList.contains('hidden')) {
                    menu.classList.remove('hidden');
                    chevron.classList.add('rotate-180');
                } else {
                    menu.classList.add('hidden');
                    chevron.classList.remove('rotate-180');
                }
            }

            function selectSortOption(val) {
                document.getElementById('sort-value').value = val;
                document.getElementById('filterForm').submit();
            }

            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('custom-sort-dropdown');
                if (dropdown && !dropdown.contains(e.target)) {
                    const menu = document.getElementById('dropdown-menu-list');
                    const chevron = document.getElementById('dropdown-chevron');
                    if (menu) menu.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                }
            });
        </script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .grid > div {
        animation: fadeIn 0.5s ease backwards;
    }
    <?php foreach($data['products'] as $i => $p): ?>
    .grid > div:nth-child(<?php echo $i+1; ?>) {
        animation-delay: <?php echo $i * 0.05; ?>s;
    }
    <?php endforeach; ?>
    
    .fill-1 { font-variation-settings: 'FILL' 1; }
</style>

<?php require APPROOT . '/views/layout/footer.php'; ?>
