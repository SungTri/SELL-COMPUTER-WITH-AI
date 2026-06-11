<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="bg-surface-container-low min-h-screen pt-24 pb-12">
    <div class="container mx-auto px-4">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-outline mb-8">
            <a href="<?php echo URLROOT; ?>" class="hover:text-on-surface transition-colors"><?php echo __('home_breadcrumb', 'Trang chủ'); ?></a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-on-surface font-medium"><?php echo __('wishlist', 'Yêu thích'); ?></span>
        </nav>

        <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant shadow-sm mb-12">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-2xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px] fill-1">favorite</span>
                </div>
                <h1 class="text-3xl font-h3 text-on-surface"><?php echo __('tab_wishlist', 'Sản phẩm yêu thích'); ?></h1>
            </div>
            <p class="text-on-surface-variant"><?php echo __('wishlist_desc', 'Quản lý các sản phẩm bạn đã lưu để mua sau.'); ?></p>
        </div>

        <?php if(!empty($data['products'])): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach($data['products'] as $product): ?>
                <div class="group bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col wishlist-item-<?php echo $product['id']; ?>">
                    <!-- Image -->
                    <div class="relative aspect-square overflow-hidden bg-white">
                        <img src="<?php echo get_product_image($product['main_image']); ?>" alt="<?php echo $product['name']; ?>" class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-700" onerror="this.src='https://placehold.co/300x300?text=Product'" />
                        
                        <!-- Quick Actions -->
                        <div class="absolute top-4 right-4 flex flex-col gap-2">
                            <button type="button" onclick="removeFromWishlist(this, <?php echo $product['id']; ?>)" 
                                    class="p-3 bg-white text-red-500 rounded-xl shadow-lg hover:bg-red-50 transition-all flex items-center justify-center" title="<?php echo __('remove_btn', 'Xóa'); ?>">
                                <span class="material-symbols-outlined text-[20px] fill-1">favorite</span>
                            </button>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-6 flex-1 flex flex-col">
                        <p class="text-xs font-label-bold text-outline uppercase tracking-wider mb-2"><?php echo $product['brand_name'] ?? __('brand_label', 'Thương hiệu'); ?></p>
                        <h3 class="font-h3 text-lg text-on-surface mb-2 line-clamp-2 hover:text-secondary transition-colors">
                            <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a>
                        </h3>
                        
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-outline-variant">
                            <span class="text-xl font-h3 text-primary"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                            <button type="button" onclick="addToCart(<?php echo $product['id']; ?>)" 
                                    class="btn-premium px-4 py-2 text-xs normal-case tracking-normal rounded-xl relative">
                                <div class="inner-glow-border"></div>
                                <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                                <?php echo __('add_to_cart_btn', 'Thêm vào giỏ'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="py-24 text-center bg-surface-container-lowest rounded-3xl border-2 border-dashed border-outline">
                <span class="material-symbols-outlined text-[64px] text-outline mb-4">favorite</span>
                <h3 class="text-2xl font-h3 text-on-surface mb-2"><?php echo __('wishlist_empty', 'Danh sách trống'); ?></h3>
                <p class="text-on-surface-variant mb-8"><?php echo __('wishlist_empty_desc', 'Bạn chưa lưu sản phẩm nào vào danh sách yêu thích.'); ?></p>
                <a href="<?php echo URLROOT; ?>" class="btn-premium px-8 py-3 rounded-2xl group relative text-base">
                    <div class="inner-glow-border"></div>
                    <?php echo __('continue_shopping', 'Tiếp tục mua sắm'); ?>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                </a>
            </div>
        <?php endif; ?>

        <script>
            async function removeFromWishlist(btn, productId) {
                if (!confirm('<?php echo addslashes(__('confirm_remove_wishlist', 'Bạn có muốn xóa sản phẩm này khỏi danh sách yêu thích?')); ?>')) return;
                
                try {
                    const response = await fetch(`<?php echo URLROOT; ?>/wishlist/toggle/${productId}`);
                    const data = await response.json();

                    if (data.status === 'success' && data.action === 'removed') {
                        const item = document.querySelector(`.wishlist-item-${productId}`);
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            item.remove();
                            if (document.querySelectorAll('[class^="wishlist-item-"]').length === 0) {
                                window.location.reload();
                            }
                        }, 300);
                    }
                } catch (error) {
                    console.error('Error removing from wishlist:', error);
                }
            }

        </script>
    </div>
</main>

<?php require APPROOT . '/views/layout/footer.php'; ?>
