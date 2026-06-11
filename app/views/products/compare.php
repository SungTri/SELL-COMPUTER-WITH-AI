<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="max-w-container-max mx-auto px-gutter py-12">
    <div class="mb-12">
        <h1 class="text-5xl font-black font-h1 tracking-tighter italic text-primary mb-4"><?php echo __('compare_title', 'SO SÁNH SẢN PHẨM'); ?></h1>
        <p class="text-on-surface-variant/60 text-lg"><?php echo __('compare_desc', 'Phân tích chi tiết các thông số để tìm ra sự lựa chọn phù hợp nhất cho bạn.'); ?></p>
    </div>

    <?php if(empty($data['products'])): ?>
        <div class="bg-surface border border-outline-variant/50 rounded-[40px] p-24 text-center">
            <span class="material-symbols-outlined !text-[80px] text-outline-variant mb-6">compare_arrows</span>
            <h2 class="text-2xl font-bold text-primary mb-4"><?php echo __('no_products_to_compare', 'Chưa có sản phẩm nào để so sánh'); ?></h2>
            <p class="text-on-surface-variant/60 mb-8 max-w-md mx-auto"><?php echo __('no_products_to_compare_desc', 'Vui lòng quay lại trang cửa hàng và chọn ít nhất 2 sản phẩm để bắt đầu so sánh.'); ?></p>
            <a href="<?php echo URLROOT; ?>/product/category/1" class="px-8 py-4 bg-primary text-on-primary rounded-2xl font-black hover:bg-secondary transition-all"><?php echo __('explore_store', 'KHÁM PHÁ CỬA HÀNG'); ?></a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto bg-white rounded-[40px] border border-outline-variant/50 shadow-2xl">
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="p-10 text-left bg-surface/50 border-r border-outline-variant/30 min-w-[200px]">
                            <span class="text-[10px] font-black text-secondary uppercase tracking-[0.2em]"><?php echo __('features_label', 'Tính năng'); ?></span>
                        </th>
                        <?php foreach($data['products'] as $product): ?>
                        <th class="p-10 min-w-[300px] border-r border-outline-variant/30 last:border-r-0">
                            <div class="relative group">
                                <button onclick="removeFromCompare(<?php echo $product['id']; ?>)" class="absolute -top-4 -right-4 w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-red-500 hover:text-white transition-all shadow-lg" title="<?php echo __('remove_from_compare', 'Xóa'); ?>">
                                    <span class="material-symbols-outlined !text-[18px]">close</span>
                                </button>
                                <div class="mb-6 aspect-square bg-surface rounded-3xl p-4 flex items-center justify-center overflow-hidden">
                                    <img src="<?php echo get_product_image($product['main_image']); ?>" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500" onerror="this.src='https://placehold.co/400x400?text=Product'">
                                </div>
                                <h3 class="text-lg font-black text-primary tracking-tighter mb-2 line-clamp-2"><?php echo $product['name']; ?></h3>
                                <p class="text-2xl font-black text-secondary mb-6"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                                <a href="<?php echo URLROOT; ?>/cart/add/<?php echo $product['id']; ?>" class="w-full py-4 bg-primary text-on-primary rounded-2xl font-black text-sm hover:bg-secondary transition-all flex items-center justify-center gap-2">
                                    <?php echo __('add_to_cart', 'THÊM VÀO GIỎ'); ?> <span class="material-symbols-outlined !text-[18px]">shopping_cart</span>
                                </a>
                            </div>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="text-[14px]">
                    <!-- Row: Brand -->
                    <tr class="border-t border-outline-variant/30 hover:bg-surface/30 transition-colors">
                        <td class="p-8 font-black text-primary uppercase tracking-widest bg-surface/20 border-r border-outline-variant/30"><?php echo __('brand_label', 'Thương hiệu'); ?></td>
                        <?php foreach($data['products'] as $product): ?>
                        <td class="p-8 font-bold text-on-surface-variant border-r border-outline-variant/30 last:border-r-0"><?php echo $product['brand_name']; ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <!-- Row: Category -->
                    <tr class="border-t border-outline-variant/30 hover:bg-surface/30 transition-colors">
                        <td class="p-8 font-black text-primary uppercase tracking-widest bg-surface/20 border-r border-outline-variant/30"><?php echo __('category_label', 'Danh mục'); ?></td>
                        <?php foreach($data['products'] as $product): ?>
                        <td class="p-8 font-bold text-on-surface-variant border-r border-outline-variant/30 last:border-r-0"><?php echo __($product['category_name']); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <!-- Row: Availability -->
                    <tr class="border-t border-outline-variant/30 hover:bg-surface/30 transition-colors">
                        <td class="p-8 font-black text-primary uppercase tracking-widest bg-surface/20 border-r border-outline-variant/30"><?php echo __('condition_label', 'Tình trạng'); ?></td>
                        <?php foreach($data['products'] as $product): ?>
                        <td class="p-8 border-r border-outline-variant/30 last:border-r-0">
                            <?php if($product['stock'] > 0): ?>
                                <span class="text-green-600 font-bold flex items-center gap-1">
                                    <span class="w-2 h-2 bg-green-600 rounded-full animate-pulse"></span> <?php echo __('in_stock', 'Còn hàng'); ?> (<?php echo $product['stock']; ?>)
                                </span>
                            <?php else: ?>
                                <span class="text-red-500 font-bold italic"><?php echo __('out_of_stock', 'Hết hàng'); ?></span>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <!-- Row: Summary -->
                    <tr class="border-t border-outline-variant/30 hover:bg-surface/30 transition-colors">
                        <td class="p-8 font-black text-primary uppercase tracking-widest bg-surface/20 border-r border-outline-variant/30"><?php echo __('short_description_label', 'Mô tả nhanh'); ?></td>
                        <?php foreach($data['products'] as $product): ?>
                        <td class="p-8 text-on-surface-variant leading-relaxed border-r border-outline-variant/30 last:border-r-0">
                            <div class="line-clamp-6"><?php echo $product['short_description']; ?></div>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<script>
function removeFromCompare(id) {
    let compareIds = JSON.parse(localStorage.getItem('compare_ids') || '[]');
    compareIds = compareIds.filter(cid => cid !== id);
    localStorage.setItem('compare_ids', JSON.stringify(compareIds));
    window.location.href = '<?php echo URLROOT; ?>/product/compare?ids=' + compareIds.join(',');
}
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
