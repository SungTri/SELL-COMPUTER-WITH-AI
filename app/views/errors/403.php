<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="min-h-screen bg-surface-container-lowest flex items-center justify-center py-24 px-6 relative overflow-hidden">
    <!-- Abstract background elements -->
    <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-error/5 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-primary/5 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/4"></div>

    <div class="max-w-xl w-full text-center relative z-10">
        <!-- Animated 403 text -->
        <div class="relative inline-block mb-12">
            <h1 class="text-[180px] font-black text-error/10 leading-none select-none">403</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="material-symbols-outlined !text-[120px] text-error animate-pulse">lock</span>
            </div>
        </div>

        <h2 class="text-4xl font-black text-primary mb-6 tracking-tight">Truy cập bị từ chối</h2>
        <p class="text-on-surface-variant text-lg mb-12 leading-relaxed">
            Bạn không có quyền truy cập vào trang này. Vui lòng kiểm tra lại quyền hạn của tài khoản hoặc liên hệ quản trị viên.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?php echo URLROOT; ?>" class="w-full sm:w-auto px-10 py-4 bg-primary text-white rounded-2xl font-bold hover:shadow-2xl hover:shadow-primary/30 transition-all flex items-center justify-center gap-2 group">
                <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">home</span>
                Về Trang Chủ
            </a>
            <a href="<?php echo URLROOT; ?>/auth/login" class="w-full sm:w-auto px-10 py-4 bg-error text-white rounded-2xl font-bold hover:shadow-2xl hover:shadow-error/30 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">login</span>
                Đăng nhập lại
            </a>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/layout/footer.php'; ?>
