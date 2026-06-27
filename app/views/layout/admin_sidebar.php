<!-- Sidebar Overlay for mobile -->
<div id="admin-sidebar-overlay" class="fixed inset-0 bg-black/50 dark:bg-black/80 z-40 hidden transition-opacity duration-300 opacity-0 lg:hidden" onclick="toggleAdminSidebar()"></div>

<!-- Sidebar Navigation -->
<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-zinc-950 text-zinc-100 border-r border-zinc-900 h-screen flex flex-col shrink-0 select-none transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto">
    <div class="px-8 py-8 flex items-center justify-between border-b border-zinc-900/60">
        <div class="flex flex-col gap-1">
            <a href="<?php echo URLROOT; ?>/admin" class="font-h1 text-2xl font-extrabold text-white tracking-tight flex items-center gap-1.5">
                <span>TechExpert</span><span class="text-indigo-500 font-black text-3xl leading-none">.</span>
            </a>
            <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Bảng điều hành</span>
        </div>
        <!-- Close button for mobile -->
        <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden w-8 h-8 rounded-lg bg-white/5 text-zinc-400 hover:bg-white/10 hover:text-white flex items-center justify-center transition-all">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>
    
    <?php
    $current_uri = $_SERVER['REQUEST_URI'];
    $activeClass = "bg-gradient-to-r from-indigo-600/15 to-blue-600/5 text-white font-bold border-l-4 border-indigo-500 shadow-inner";
    $inactiveClass = "text-zinc-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-zinc-800";
    
    function isActive($path, $current) {
        if ($path === 'admin') {
            return preg_match('/\/admin\/?$/', $current) || preg_match('/\/admin\?/', $current);
        }
        return strpos($current, '/' . $path) !== false;
    }
    ?>
    <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto pb-4 custom-sidebar-nav">
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>analytics</span>
            <span class="text-[13px]">Thống kê và báo cáo</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/orders', $current_uri) || isActive('admin/orderDetail', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/orders">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/orders', $current_uri) || isActive('admin/orderDetail', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>shopping_cart</span>
            <span class="text-[13px]">Quản lý đơn hàng</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/products', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/products">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/products', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>inventory_2</span>
            <span class="text-[13px]">Quản lý sản phẩm</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/media', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/media">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/media', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>photo_library</span>
            <span class="text-[13px]">Thư viện ảnh</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/categories', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/categories">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/categories', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>category</span>
            <span class="text-[13px]">Quản lý danh mục</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/brands', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/brands">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/brands', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>branding_watermark</span>
            <span class="text-[13px]">Quản lý thương hiệu</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/promotions', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/promotions">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/promotions', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>sell</span>
            <span class="text-[13px]">Quản lý khuyến mãi</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/reviews', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/reviews">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/reviews', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>rate_review</span>
            <span class="text-[13px]">Quản lý đánh giá</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/feedbacks', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/feedbacks">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/feedbacks', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>feedback</span>
            <span class="text-[13px]">Quản lý góp ý</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/users', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/users">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/users', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>person</span>
            <span class="text-[13px]">Quản lý tài khoản</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/chatbot', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/chatbot">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/chatbot', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>smart_toy</span>
            <span class="text-[13px]">Quản lý chatbot</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/livechat', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/livechat">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/livechat', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>support_agent</span>
            <span class="text-[13px]">Hỗ trợ trực tuyến</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/transactions', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/transactions">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/transactions', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>account_balance</span>
            <span class="text-[13px]">Giao dịch ngân hàng</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo isActive('admin/settings', $current_uri) ? $activeClass : $inactiveClass; ?>" href="<?php echo URLROOT; ?>/admin/settings">
            <span class="material-symbols-outlined text-[20px]" <?php echo isActive('admin/settings', $current_uri) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>settings</span>
            <span class="text-[13px]">Cài đặt hệ thống</span>
        </a>
        <div class="pt-4 mt-4 border-t border-zinc-900">
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-indigo-400 font-bold hover:bg-indigo-550/10 transition-all" href="<?php echo URLROOT; ?>">
                <span class="material-symbols-outlined text-[20px]">storefront</span>
                <span class="text-[13px]">Quay lại cửa hàng</span>
            </a>
        </div>
    </nav>

    <div class="px-4 py-6 border-t border-zinc-900/60 space-y-4">
        <a href="<?php echo URLROOT; ?>/buildpc" target="_blank" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-3 px-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:from-indigo-500 hover:to-blue-500 transition-all active:scale-[0.98] shadow-lg shadow-indigo-600/15">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            <span class="text-[13px]">Tự dựng cấu hình PC</span>
        </a>

        <div class="flex items-center gap-3.5 p-3.5 bg-white/5 border border-white/10 rounded-2xl shadow-sm group">
            <div class="relative">
                <img alt="Admin Profile" class="w-10 h-10 rounded-full object-cover ring-2 ring-white/10" src="<?php echo $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff'; ?>"/>
                <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-zinc-950 rounded-full animate-pulse"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-bold text-white truncate"><?php echo $_SESSION['user_name'] ?? 'Admin TechExpert'; ?></p>
                <p class="text-[11px] text-zinc-500 font-medium">Quản trị viên</p>
            </div>
            <a href="<?php echo URLROOT; ?>/auth/logout" onclick="sessionStorage.clear()" class="w-8 h-8 flex items-center justify-center rounded-lg text-zinc-400 hover:bg-red-500/10 hover:text-red-400 transition-all" title="Đăng xuất">
                <span class="material-symbols-outlined text-[18px]">logout</span>
            </a>
        </div>
    </div>
</aside>
