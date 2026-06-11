<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="min-h-screen bg-surface-container-lowest flex items-center justify-center py-24 px-6 relative overflow-hidden">
    <!-- Abstract background elements -->
    <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-primary/5 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-secondary/5 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/4"></div>

    <div class="max-w-xl w-full text-center relative z-10">
        <!-- Animated 404 text -->
        <div class="relative inline-block mb-12">
            <h1 class="text-[180px] font-black text-primary/10 leading-none select-none">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="material-symbols-outlined !text-[120px] text-secondary animate-bounce">device_unknown</span>
            </div>
        </div>

        <h2 class="text-4xl font-black text-primary mb-6 tracking-tight">Opps! Trang không tồn tại</h2>
        <p class="text-on-surface-variant text-lg mb-12 leading-relaxed">
            Có vẻ như trang bạn đang tìm kiếm đã bị di chuyển hoặc không còn tồn tại trong hệ thống của TechExpert.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?php echo URLROOT; ?>" class="w-full sm:w-auto px-10 py-4 bg-primary text-white rounded-2xl font-bold hover:shadow-2xl hover:shadow-primary/30 transition-all flex items-center justify-center gap-2 group">
                <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">arrow_back</span>
                Về Trang Chủ
            </a>
            <a href="<?php echo URLROOT; ?>/product/search" class="w-full sm:w-auto px-10 py-4 bg-surface-container-highest text-primary rounded-2xl font-bold hover:bg-white border-2 border-transparent hover:border-primary/10 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">search</span>
                Tìm Sản Phẩm
            </a>
        </div>

        <!-- Suggestion section -->
        <div class="mt-20 pt-12 border-t border-outline-variant/30">
            <p class="text-xs font-black text-outline uppercase tracking-[0.3em] mb-8">Có thể bạn đang tìm kiếm</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="<?php echo URLROOT; ?>/product/category/2" class="p-4 bg-white rounded-2xl border border-outline-variant/30 hover:border-secondary hover:shadow-lg transition-all group">
                    <span class="material-symbols-outlined text-secondary mb-2">laptop_mac</span>
                    <p class="text-[11px] font-bold text-primary uppercase">Laptop</p>
                </a>
                <a href="<?php echo URLROOT; ?>/product/category/1" class="p-4 bg-white rounded-2xl border border-outline-variant/30 hover:border-secondary hover:shadow-lg transition-all group">
                    <span class="material-symbols-outlined text-secondary mb-2">desktop_windows</span>
                    <p class="text-[11px] font-bold text-primary uppercase">Máy Bộ</p>
                </a>
                <a href="<?php echo URLROOT; ?>/product/category/3" class="p-4 bg-white rounded-2xl border border-outline-variant/30 hover:border-secondary hover:shadow-lg transition-all group">
                    <span class="material-symbols-outlined text-secondary mb-2">videogame_asset</span>
                    <p class="text-[11px] font-bold text-primary uppercase">Linh Kiện</p>
                </a>
                <a href="<?php echo URLROOT; ?>/user/profile?tab=promotions" class="p-4 bg-white rounded-2xl border border-outline-variant/30 hover:border-secondary hover:shadow-lg transition-all group">
                    <span class="material-symbols-outlined text-secondary mb-2">sell</span>
                    <p class="text-[11px] font-bold text-primary uppercase">Voucher</p>
                </a>
            </div>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/layout/footer.php'; ?>
