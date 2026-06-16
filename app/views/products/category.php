<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="bg-surface-container-low min-h-screen pt-24 pb-12">
    <div class="container mx-auto px-4">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-outline mb-8">
            <a href="<?php echo URLROOT; ?>" class="hover:text-on-surface transition-colors"><?php echo __('home_breadcrumb', 'Trang chủ'); ?></a>
            <?php if(!empty($data['category']['parent_id'])): ?>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <a href="<?php echo URLROOT; ?>/product/category/<?php echo $data['category']['parent_id']; ?>" class="hover:text-on-surface transition-colors"><?php echo __($data['category']['parent_name']); ?></a>
            <?php endif; ?>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-on-surface font-medium"><?php echo __($data['category']['name']); ?></span>
        </nav>

        <form id="filterForm" action="<?php echo URLROOT; ?>/product/category/<?php echo $data['category_id']; ?>" method="GET">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-72 space-y-6">
                <!-- Filter Card -->
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-h3 text-xl text-on-surface"><?php echo __('filter_title', 'Bộ lọc'); ?></h3>
                        <span class="material-symbols-outlined text-outline">tune</span>
                    </div>

                    <!-- Subcategories (Only show if current category has children) -->
                    <?php if(!empty($data['subcategories'])): ?>
                    <div class="space-y-4 mb-8">
                        <h4 class="font-label-bold text-on-surface"><?php echo __('filter_product_type', 'Loại sản phẩm'); ?></h4>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach($data['subcategories'] as $subcat): ?>
                            <a href="<?php echo URLROOT; ?>/product/category/<?php echo $subcat['id']; ?>" 
                               class="px-3 py-1.5 bg-surface border border-outline-variant rounded-full text-[12px] font-bold text-on-surface-variant hover:bg-secondary hover:text-white hover:border-secondary transition-all">
                                <?php echo __($subcat['name']); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Brands -->
                    <div class="space-y-4 mb-8">
                        <h4 class="font-label-bold text-on-surface"><?php echo __('filter_brand', 'Thương hiệu'); ?></h4>
                        <div class="space-y-2">
                            <?php 
                            $selectedBrands = isset($data['filters']['brand']) ? (is_array($data['filters']['brand']) ? $data['filters']['brand'] : [$data['filters']['brand']]) : [];
                            foreach($data['brands'] as $brand): 
                            ?>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="brand[]" value="<?php echo $brand['id']; ?>" class="w-5 h-5 accent-secondary rounded" <?php echo in_array($brand['id'], $selectedBrands) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                                <span class="text-on-surface-variant group-hover:text-on-surface transition-colors"><?php echo $brand['name']; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="space-y-4 mb-8">
                        <h4 class="font-label-bold text-on-surface"><?php echo __('filter_price_range', 'Khoảng giá'); ?></h4>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <input type="number" name="price_min" value="<?php echo $data['filters']['price_min'] ?? ''; ?>" placeholder="<?php echo __('filter_price_from', 'Từ'); ?>" class="w-full bg-surface border border-outline-variant rounded-lg px-3 py-2 text-sm outline-none focus:border-secondary" />
                                <span class="text-outline">-</span>
                                <input type="number" name="price_max" value="<?php echo $data['filters']['price_max'] ?? ''; ?>" placeholder="<?php echo __('filter_price_to', 'Đến'); ?>" class="w-full bg-surface border border-outline-variant rounded-lg px-3 py-2 text-sm outline-none focus:border-secondary" />
                            </div>
                            <button type="submit" class="w-full py-2 bg-secondary text-on-secondary rounded-lg text-sm font-label-bold hover:shadow-lg transition-all"><?php echo __('filter_apply', 'Áp dụng'); ?></button>
                        </div>
                    </div>

                    <!-- Clear All -->
                    <a href="<?php echo URLROOT; ?>/product/category/<?php echo $data['category_id']; ?>" class="block w-full py-3 border border-outline rounded-lg text-center text-sm font-label-bold text-on-surface-variant hover:bg-surface-container transition-all"><?php echo __('filter_clear_all', 'Xóa tất cả bộ lọc'); ?></a>
                </div>

                <!-- Promo Banner -->
                <div class="bg-primary rounded-2xl p-6 text-on-primary overflow-hidden relative group">
                    <div class="relative z-10">
                        <p class="text-xs font-label-bold uppercase tracking-widest mb-2 opacity-80"><?php echo __('filter_promo_badge', 'Ưu đãi độc quyền'); ?></p>
                        <h4 class="text-xl font-h3 mb-4"><?php echo __('filter_promo_title', 'Trả góp 0% cho Laptop Gaming'); ?></h4>
                        <button type="button" class="bg-white text-primary px-4 py-2 rounded-lg text-xs font-label-bold hover:shadow-lg transition-all"><?php echo __('filter_promo_btn', 'Xem ngay'); ?></button>
                    </div>
                    <span class="material-symbols-outlined absolute -bottom-4 -right-4 text-[120px] opacity-10 group-hover:scale-110 transition-transform duration-700">laptop_mac</span>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Toolbar -->
                <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant shadow-sm mb-8 flex flex-wrap items-center justify-between gap-4">
                    <p class="text-on-surface-variant text-sm font-medium"><?php echo __('show_products_count', 'Hiển thị'); ?> <span class="text-on-surface font-bold"><?php echo count($data['products']); ?></span> <?php echo __('show_products_suffix', 'sản phẩm'); ?></p>
                    
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-outline font-medium"><?php echo __('sort_by', 'Sắp xếp theo:'); ?></span>
                        <input type="hidden" name="sort" id="sort-value" value="<?php echo $data['filters']['sort'] ?? 'newest'; ?>">
                        
                        <div class="relative inline-block text-left" id="custom-sort-dropdown">
                            <button type="button" onclick="toggleCustomDropdown()" class="flex items-center justify-between gap-3 px-5 py-2.5 bg-white dark:bg-zinc-900 border border-outline-variant dark:border-zinc-700/60 rounded-xl text-sm font-semibold text-on-surface hover:border-secondary hover:text-secondary transition-all shadow-sm cursor-pointer min-w-[200px] select-none">
                                <span id="selected-sort-label">
                                    <?php 
                                    $currentSort = $data['filters']['sort'] ?? 'newest';
                                    if ($currentSort === 'price_low' || $currentSort === 'price_asc') {
                                        echo __('sort_price_low', 'Từ rẻ nhất đến đắt nhất');
                                    } elseif ($currentSort === 'price_high' || $currentSort === 'price_desc') {
                                        echo __('sort_price_high', 'Từ đắt nhất đến rẻ nhất');
                                    } else {
                                        echo __('sort_newest', 'Mới nhất');
                                    }
                                    ?>
                                </span>
                                <span class="material-symbols-outlined text-outline transition-transform duration-300" id="dropdown-chevron">keyboard_arrow_down</span>
                            </button>
                            
                            <div id="dropdown-menu-list" class="hidden absolute right-0 mt-2 w-56 rounded-2xl bg-white dark:bg-zinc-900 border border-outline-variant dark:border-zinc-800 shadow-2xl z-50 p-1.5 focus:outline-none animate-in fade-in slide-in-from-top-2 duration-200">
                                <div class="py-1" role="none">
                                    <button type="button" onclick="selectSortOption('newest')" class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all flex items-center justify-between cursor-pointer <?php echo ($data['filters']['sort'] ?? 'newest') == 'newest' ? 'bg-secondary/10 text-secondary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'; ?>">
                                        <span><?php echo __('sort_newest', 'Mới nhất'); ?></span>
                                        <?php if (($data['filters']['sort'] ?? 'newest') == 'newest'): ?>
                                            <span class="material-symbols-outlined text-[18px]">check</span>
                                        <?php endif; ?>
                                    </button>
                                    <button type="button" onclick="selectSortOption('price_low')" class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all flex items-center justify-between cursor-pointer <?php echo (($data['filters']['sort'] ?? 'newest') == 'price_low' || ($data['filters']['sort'] ?? 'newest') == 'price_asc') ? 'bg-secondary/10 text-secondary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'; ?>">
                                        <span><?php echo __('sort_price_low', 'Từ rẻ nhất đến đắt nhất'); ?></span>
                                        <?php if (($data['filters']['sort'] ?? 'newest') == 'price_low' || ($data['filters']['sort'] ?? 'newest') == 'price_asc'): ?>
                                            <span class="material-symbols-outlined text-[18px]">check</span>
                                        <?php endif; ?>
                                    </button>
                                    <button type="button" onclick="selectSortOption('price_high')" class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all flex items-center justify-between cursor-pointer <?php echo (($data['filters']['sort'] ?? 'newest') == 'price_high' || ($data['filters']['sort'] ?? 'newest') == 'price_desc') ? 'bg-secondary/10 text-secondary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'; ?>">
                                        <span><?php echo __('sort_price_high', 'Từ đắt nhất đến rẻ nhất'); ?></span>
                                        <?php if (($data['filters']['sort'] ?? 'newest') == 'price_high' || ($data['filters']['sort'] ?? 'newest') == 'price_desc'): ?>
                                            <span class="material-symbols-outlined text-[18px]">check</span>
                                        <?php endif; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php if(!empty($data['products'])): ?>
                        <?php foreach($data['products'] as $product): ?>
                        <div class="group bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                            <!-- Image -->
                            <div class="relative aspect-[4/3] overflow-hidden bg-white">
                                <img src="<?php echo get_product_image($product['main_image']); ?>" alt="<?php echo $product['name']; ?>" class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-700" onerror="this.src='https://placehold.co/400x400?text=Product'" />
                            </div>

                            <!-- Info -->
                            <div class="p-6 flex-1 flex flex-col">
                                <p class="text-xs font-label-bold text-outline uppercase tracking-wider mb-2"><?php echo $product['brand_name']; ?></p>
                                <h3 class="font-h3 text-lg text-on-surface mb-2 line-clamp-2 hover:text-secondary transition-colors">
                                    <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a>
                                </h3>
                                <p class="text-sm text-on-surface-variant line-clamp-2 mb-4 flex-1"><?php echo $product['short_description']; ?></p>
                                
                                <div class="flex items-center justify-between mt-auto pt-4 border-t border-outline-variant">
                                    <div class="min-w-0 flex-1">
                                        <span class="text-xl font-h3 text-primary block truncate">
                                            <?php 
                                            if (($_SESSION['lang'] ?? 'vi') === 'en') {
                                                echo number_format($product['price'], 0, '.', ',') . 'đ';
                                            } else {
                                                echo number_format($product['price'], 0, ',', '.') . 'đ';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <button type="button" onclick="toggleCompare(<?php echo $product['id']; ?>)" 
                                                class="w-10 h-10 rounded-xl bg-surface-container-high text-on-surface-variant hover:bg-secondary hover:text-white transition-all flex items-center justify-center compare-btn"
                                                data-id="<?php echo $product['id']; ?>">
                                            <span class="material-symbols-outlined text-[20px]">compare_arrows</span>
                                        </button>
                                        <button type="button" onclick="addToCart(<?php echo $product['id']; ?>)" 
                                                class="w-10 h-10 rounded-xl bg-primary text-white hover:bg-secondary transition-all flex items-center justify-center group/btn">
                                            <span class="material-symbols-outlined text-[20px] group-hover/btn:scale-110 transition-transform">shopping_cart</span>
                                        </button>
                                        <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $product['id']; ?>"
                                           class="w-10 h-10 rounded-xl bg-on-surface text-surface hover:bg-secondary hover:text-on-secondary transition-all flex items-center justify-center"
                                           title="Xem chi tiết">
                                            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full py-24 text-center bg-surface-container-lowest rounded-3xl border-2 border-dashed border-outline">
                            <span class="material-symbols-outlined text-[64px] text-outline mb-4">search_off</span>
                            <h3 class="text-xl font-h3 text-on-surface mb-2"><?php echo __('no_products_found', 'Không tìm thấy sản phẩm nào'); ?></h3>
                            <p class="text-outline"><?php echo __('no_products_found_desc', 'Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm của bạn.'); ?></p>
                            <a href="<?php echo URLROOT; ?>/product/category/<?php echo $data['category_id']; ?>" class="inline-block mt-4 text-secondary font-bold hover:underline"><?php echo __('filter_clear_all', 'Xóa tất cả bộ lọc'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if($data['pagination']['total_pages'] > 1): ?>
                <div class="mt-12 flex justify-center items-center gap-2">
                    <input type="hidden" name="page" id="pageInput" value="<?php echo $data['pagination']['current_page']; ?>">
                    
                    <?php if($data['pagination']['current_page'] > 1): ?>
                    <button type="button" onclick="changePage(<?php echo $data['pagination']['current_page'] - 1; ?>)" class="w-10 h-10 flex items-center justify-center rounded-xl border border-outline-variant text-outline hover:border-secondary hover:text-secondary transition-all">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                        <button type="button" onclick="changePage(<?php echo $i; ?>)" 
                                class="w-10 h-10 flex items-center justify-center rounded-xl <?php echo $i == $data['pagination']['current_page'] ? 'bg-secondary text-on-secondary shadow-md' : 'border border-outline-variant text-on-surface hover:border-secondary hover:text-secondary'; ?> transition-all font-label-bold">
                            <?php echo $i; ?>
                        </button>
                    <?php endfor; ?>

                    <?php if($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                    <button type="button" onclick="changePage(<?php echo $data['pagination']['current_page'] + 1; ?>)" class="w-10 h-10 flex items-center justify-center rounded-xl border border-outline-variant text-outline hover:border-secondary hover:text-secondary transition-all">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        </form>

        <script>
            function changePage(page) {
                document.getElementById('pageInput').value = page;
                document.getElementById('filterForm').submit();
            }

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
    </div>
</main>

<?php require APPROOT . '/views/layout/footer.php'; ?>
