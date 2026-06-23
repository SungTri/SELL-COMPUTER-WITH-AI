<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    
    <?php
    $storeName = $data['app_settings']['store_name'] ?? 'TechExpert';
    $pageTitle = isset($data['title']) ? $data['title'] : $storeName . ' - Precision Engineering';
    $metaDesc = $data['meta_description'] ?? ($data['app_settings']['meta_description'] ?? 'Chuyên cung cấp linh kiện máy tính cao cấp');
    $metaKeys = $data['meta_keywords'] ?? ($data['app_settings']['meta_keywords'] ?? 'pc, laptop, linh kien');
    
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $requestUri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
    $canonicalUrl = $protocol . $host . $requestUri;
    ?>
    
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDesc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($metaKeys); ?>">
    
    <link rel="canonical" href="<?php echo htmlspecialchars($canonicalUrl); ?>">
    
    <?php if (isset($data['noindex']) && $data['noindex'] === true): ?>
    <meta name="robots" content="noindex, nofollow">
    <?php else: ?>
    <meta name="robots" content="index, follow">
    <?php endif; ?>
    
    <!-- Theme Detection & Toggle Script (Prevents FOUC) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            const icon = document.getElementById('theme-toggle-icon');
            if (icon) {
                icon.textContent = isDark ? 'light_mode' : 'dark_mode';
            }
        });

        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-toggle-icon');
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                if (icon) icon.textContent = 'dark_mode';
                if (typeof showToast === 'function') showToast('Đã chuyển sang giao diện sáng', 'success');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if (icon) icon.textContent = 'light_mode';
                if (typeof showToast === 'function') showToast('Đã chuyển sang giao diện tối', 'success');
            }
        }
    </script>

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDesc); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($protocol . $host . ($_SERVER['REQUEST_URI'] ?? '')); ?>">
    <?php 
    $ogImage = '';
    if (!empty($data['og_image'])) {
        $ogImage = $data['og_image'];
        if (strpos($ogImage, 'http') !== 0) {
            $ogImage = URLROOT . '/' . ltrim($ogImage, '/');
        }
    } elseif (!empty($data['app_settings']['store_logo'])) {
        $ogImage = $data['app_settings']['store_logo'];
    }
    if (!empty($ogImage)): ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    <?php endif; ?>

    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&amp;family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "surface-dim": "var(--color-surface-dim)",
                      "on-secondary": "var(--color-on-secondary)",
                      "inverse-surface": "var(--color-inverse-surface)",
                      "on-tertiary": "var(--color-on-tertiary)",
                      "inverse-on-surface": "var(--color-inverse-on-surface)",
                      "surface-tint": "var(--color-surface-tint)",
                      "outline": "var(--color-outline)",
                      "secondary-fixed": "var(--color-secondary-fixed)",
                      "tertiary-fixed": "var(--color-tertiary-fixed)",
                      "outline-variant": "var(--color-outline-variant)",
                      "surface-container-highest": "var(--color-surface-container-highest)",
                      "surface-container-lowest": "var(--color-surface-container-lowest)",
                      "on-error-container": "var(--color-on-error-container)",
                      "on-error": "var(--color-on-error)",
                      "on-primary-fixed": "var(--color-on-primary-fixed)",
                      "primary-fixed-dim": "var(--color-primary-fixed-dim)",
                      "tertiary-container": "var(--color-tertiary-container)",
                      "surface-container-high": "var(--color-surface-container-high)",
                      "on-primary-container": "var(--color-on-primary-container)",
                      "error": "var(--color-error)",
                      "tertiary-fixed-dim": "var(--color-tertiary-fixed-dim)",
                      "surface-container-low": "var(--color-surface-container-low)",
                      "surface": "var(--color-surface)",
                      "secondary-container": "var(--color-secondary-container)",
                      "tertiary": "var(--color-tertiary)",
                      "on-tertiary-fixed": "var(--color-on-tertiary-fixed)",
                      "surface-container": "var(--color-surface-container)",
                      "on-surface-variant": "var(--color-on-surface-variant)",
                      "on-primary-fixed-variant": "var(--color-on-primary-fixed-variant)",
                      "primary": "var(--color-primary)",
                      "primary-fixed": "var(--color-primary-fixed)",
                      "error-container": "var(--color-error-container)",
                      "secondary-fixed-dim": "var(--color-secondary-fixed-dim)",
                      "surface-bright": "var(--color-surface-bright)",
                      "on-tertiary-fixed-variant": "var(--color-on-tertiary-fixed-variant)",
                      "on-background": "var(--color-on-background)",
                      "inverse-primary": "var(--color-inverse-primary)",
                      "surface-variant": "var(--color-surface-variant)",
                      "primary-container": "var(--color-primary-container)",
                      "on-tertiary-container": "var(--color-on-tertiary-container)",
                      "on-secondary-fixed": "var(--color-on-secondary-fixed)",
                      "on-primary": "var(--color-on-primary)",
                      "on-surface": "var(--color-on-surface)",
                      "background": "var(--color-background)",
                      "on-secondary-container": "var(--color-on-secondary-container)",
                      "on-secondary-fixed-variant": "var(--color-on-secondary-fixed-variant)",
                      "secondary": "var(--color-secondary)"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "card-gap": "32px",
                      "section-padding": "80px",
                      "container-max": "1280px",
                      "unit": "8px",
                      "gutter": "24px"
              },
              "fontFamily": {
                      "body-md": ["Inter"],
                      "price-display": ["Hanken Grotesk"],
                      "label-bold": ["Inter"],
                      "h3": ["Hanken Grotesk"],
                      "h1": ["Hanken Grotesk"],
                      "h2": ["Hanken Grotesk"],
                      "body-lg": ["Inter"]
              },
              "fontSize": {
                      "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                      "price-display": ["20px", {"lineHeight": "1", "fontWeight": "700"}],
                      "label-bold": ["14px", {"lineHeight": "1.2", "fontWeight": "600"}],
                      "h3": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                      "h1": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "h2": ["32px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                      "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}]
              }
            },
          },
        }
    </script>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
</head>
<body class="bg-surface text-on-surface font-body-md text-body-md antialiased pt-[140px]">
    <!-- Top Promo Bar -->
    <div class="fixed top-0 left-0 w-full z-[60] bg-gradient-to-r from-[#0a0f1d] via-[#0453cd] to-[#0a0f1d] text-white py-2.5 text-center text-[12px] font-bold tracking-[0.2em] uppercase overflow-hidden whitespace-nowrap shadow-md">
        <div class="animate-marquee inline-block px-10">
            <?php echo __('promo_bar_marquee', '⚡️ Miễn phí vận chuyển cho đơn hàng từ 5.000.000đ • Giảm 10% cho khách hàng mới • Bảo hành chính hãng 24 tháng • Hỗ trợ kỹ thuật 24/7 ⚡️'); ?>
        </div>
        <div class="animate-marquee inline-block px-10">
            <?php echo __('promo_bar_marquee', '⚡️ Miễn phí vận chuyển cho đơn hàng từ 5.000.000đ • Giảm 10% cho khách hàng mới • Bảo hành chính hãng 24 tháng • Hỗ trợ kỹ thuật 24/7 ⚡️'); ?>
        </div>
    </div>

    <!-- Main Header -->
    <header class="fixed top-[37px] left-0 w-full z-50 bg-surface-container-lowest/90 backdrop-blur-2xl border-b border-outline-variant/20 dark:border-outline-variant/10 shadow-[0_4px_30px_rgba(0,0,0,0.03)] transition-all duration-300">
        <!-- Row 1: Logo, Search, Actions -->
        <div class="max-w-container-max mx-auto px-gutter py-3 flex items-center justify-between gap-4">
            
            <!-- Left: Brand Logo & Name -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="<?php echo URLROOT; ?>" class="group flex items-center gap-3">
                    <div class="relative">
                        <?php if(!empty($data['app_settings']['store_logo'])): ?>
                            <img src="<?php echo $data['app_settings']['store_logo']; ?>" alt="Logo" class="w-10 h-10 rounded-xl object-cover group-hover:scale-110 transition-transform duration-500 shadow-md">
                        <?php else: ?>
                            <div class="w-10 h-10 bg-primary text-on-primary rounded-xl flex items-center justify-center font-bold text-xl group-hover:rotate-[10deg] transition-transform duration-500 shadow-lg shadow-primary/20">
                                <?php echo substr($data['app_settings']['store_name'] ?? 'M', 0, 1); ?>
                            </div>
                        <?php endif; ?>
                        <div class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-secondary rounded-full border border-white"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black tracking-tighter text-primary leading-none">
                            <?php 
                            $name = $data['app_settings']['store_name'] ?? 'MTS.SHOP';
                            echo mb_strtoupper($name, 'UTF-8');
                            ?>
                        </span>
                        <span class="text-[8px] font-bold text-outline tracking-[0.2em] uppercase opacity-50">Expert Curated</span>
                    </div>
                </a>
            </div>

            <!-- Center: Compact Search Bar (Dynamic width: hidden on mobile, visible on tablet & desktop) -->
            <div class="flex-1 max-w-[420px] lg:max-w-[500px] xl:max-w-[580px] hidden md:block mx-auto">
                <?php 
                    $cartCount = isset($data['cart_count']) ? $data['cart_count'] : (isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0);
                ?>
                <form action="<?php echo URLROOT; ?>/product/search" id="main-search-form" method="GET" class="w-full flex items-center bg-surface-container/30 hover:bg-surface-container/60 rounded-xl px-4 py-2 border border-outline-variant/10 focus-within:border-secondary/40 focus-within:bg-surface-container-lowest focus-within:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 group">
                    <span aria-hidden="true" class="material-symbols-outlined text-outline/40 group-focus-within:text-secondary transition-colors !text-[20px]">search</span>
                    <input name="q" id="main-search-input" class="bg-transparent border-none focus:ring-0 text-[13px] font-medium text-on-surface placeholder:text-outline/30 w-full min-w-0 ml-2 py-0" placeholder="<?php echo __('search_placeholder', 'Tìm kiếm...'); ?>" type="text" value="<?php echo $_GET['q'] ?? ''; ?>"/>
                    
                    <!-- AI Search Toggle -->
                    <button type="button" onclick="toggleAISearch()" class="ml-1 p-1 text-secondary hover:bg-secondary/10 rounded-lg transition-all tooltip" title="<?php echo __('chatbot_mode_ai', 'Tìm kiếm AI'); ?>">
                        <span class="material-symbols-outlined !text-[18px] animate-pulse">magic_button</span>
                    </button>
                </form>
            </div>

            <!-- Right: Actions & User Account -->
            <div class="flex items-center gap-1.5 shrink-0">
                <!-- Mobile Search Trigger (visible only on mobile) -->
                <a href="<?php echo URLROOT; ?>/product/search" class="p-2 md:hidden text-on-surface/50 hover:text-secondary hover:bg-secondary/5 rounded-xl transition-all" title="Tìm kiếm">
                    <span class="material-symbols-outlined !text-[24px]">search</span>
                </a>

                <!-- Language Switcher -->
                <div class="relative group">
                    <button class="p-2 text-on-surface/50 hover:text-secondary hover:bg-secondary/5 rounded-xl transition-all flex items-center gap-1 font-bold text-[11px]" title="Chọn ngôn ngữ">
                        <span class="material-symbols-outlined !text-[24px]">language</span>
                        <span class="uppercase hidden sm:inline"><?php echo $_SESSION['lang'] ?? 'vi'; ?></span>
                    </button>
                    <!-- Dropdown -->
                    <div class="absolute right-0 top-full pt-2 w-32 opacity-0 invisible translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 z-[100]">
                        <div class="bg-surface-container-lowest border border-outline-variant/30 dark:border-outline-variant/10 rounded-2xl shadow-2xl overflow-hidden p-1">
                            <a href="<?php echo URLROOT; ?>/language/change/vi" class="flex items-center gap-2 px-3 py-2 text-xs text-on-surface-variant hover:bg-secondary/10 hover:text-secondary rounded-xl transition-all font-semibold <?php echo ($_SESSION['lang'] ?? 'vi') === 'vi' ? 'text-secondary bg-secondary/5' : ''; ?>">
                                🇻🇳 Tiếng Việt
                            </a>
                            <a href="<?php echo URLROOT; ?>/language/change/en" class="flex items-center gap-2 px-3 py-2 text-xs text-on-surface-variant hover:bg-secondary/10 hover:text-secondary rounded-xl transition-all font-semibold <?php echo ($_SESSION['lang'] ?? 'vi') === 'en' ? 'text-secondary bg-secondary/5' : ''; ?>">
                                🇺🇸 English
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Theme Toggle Button -->
                <button id="theme-toggle-btn" onclick="toggleTheme()" class="p-2 text-on-surface/50 hover:text-secondary hover:bg-secondary/5 rounded-xl transition-all" title="<?php echo __('theme_toggle_title', 'Đổi giao diện'); ?>">
                    <span class="material-symbols-outlined !text-[24px]" id="theme-toggle-icon">dark_mode</span>
                </button>

                <a href="<?php echo URLROOT; ?>/user/profile?tab=wishlist" class="p-2 text-on-surface/50 hover:text-secondary hover:bg-secondary/5 rounded-xl transition-all" title="<?php echo __('wishlist', 'Yêu thích'); ?>">
                    <span class="material-symbols-outlined !text-[24px]">favorite</span>
                </a>

                <a href="<?php echo URLROOT; ?>/cart" class="relative p-2 text-on-surface/50 hover:text-secondary hover:bg-secondary/5 rounded-xl transition-all" title="<?php echo __('cart', 'Giỏ hàng'); ?>">
                    <span class="material-symbols-outlined !text-[24px]">shopping_cart</span>
                    <span id="header-cart-count" class="cart-count-badge absolute top-0.5 right-0.5 bg-secondary text-on-secondary text-[7px] font-black rounded-full min-w-[14px] h-[14px] flex items-center justify-center border-2 border-white <?php echo $cartCount == 0 ? 'hidden' : ''; ?>">
                        <?php echo $cartCount; ?>
                    </span>
                </a>

                <?php if(isset($_SESSION['user_id'])): ?>
                <div class="relative group" id="notification-area">
                    <button class="relative p-2 text-on-surface/50 hover:text-secondary hover:bg-secondary/5 rounded-xl transition-all" title="<?php echo __('notifications', 'Thông báo'); ?>" id="notification-btn">
                        <span class="material-symbols-outlined !text-[24px]">notifications</span>
                        <span id="notification-badge" class="absolute top-1 right-1 bg-red-500 text-white text-[8px] font-black rounded-full min-w-[16px] h-[16px] flex items-center justify-center border-2 border-white hidden">0</span>
                    </button>
                    <!-- Notification Dropdown -->
                    <div class="absolute right-0 top-full pt-3 w-80 opacity-0 invisible translate-y-4 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 z-[100]">
                        <div class="bg-surface-container-lowest border border-outline-variant/30 dark:border-outline-variant/10 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/5">
                            <div class="p-4 border-b border-outline-variant/30 flex items-center justify-between bg-surface-container/30">
                                <h4 class="text-sm font-bold text-primary uppercase tracking-wider"><?php echo __('notifications', 'Thông báo'); ?></h4>
                                <button onclick="markAllAsRead()" class="text-[10px] font-bold text-secondary hover:underline uppercase tracking-widest"><?php echo __('mark_all_read', 'Đánh dấu đã đọc'); ?></button>
                            </div>
                            <div id="notification-list" class="max-h-96 overflow-y-auto divide-y divide-outline-variant/10">
                                <div class="p-10 text-center text-on-surface-variant/40 italic text-xs"><?php echo __('loading_notifications', 'Đang tải thông báo...'); ?></div>
                            </div>
                            <div class="p-3 bg-surface-container/10 text-center border-t border-outline-variant/20">
                                <a href="<?php echo URLROOT; ?>/user/profile?tab=notifications" class="text-[11px] font-bold text-on-surface-variant hover:text-secondary transition-colors"><?php echo __('view_all_notifications', 'Xem tất cả thông báo'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- User Profile / Login -->
                <div class="h-6 w-[1px] bg-outline-variant/20 mx-1"></div> <!-- Vertical Separator -->

                <?php if(isset($_SESSION['user_id'])): ?>
                <div class="relative group">
                    <button class="relative w-9 h-9 flex items-center justify-center bg-surface-container-lowest hover:bg-surface-container rounded-full border border-outline-variant/30 shadow-sm transition-all group-hover:ring-4 group-hover:ring-primary/5">
                        <div class="relative">
                            <img src="<?php echo $_SESSION['user_avatar']; ?>" alt="Avatar" class="w-7 h-7 rounded-full object-cover shadow-sm" />
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                        </div>
                    </button>
                    <!-- Premium Dropdown -->
                    <div class="absolute right-0 top-full pt-3 w-64 opacity-0 invisible translate-y-4 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 z-[100]">
                        <div class="bg-surface-container-lowest border border-outline-variant/30 dark:border-outline-variant/10 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/5">
                            <div class="p-5 bg-surface-container/30 border-b border-outline-variant/30 flex items-center gap-3">
                                <img src="<?php echo $_SESSION['user_avatar']; ?>" alt="Avatar" class="w-10 h-10 rounded-full border border-white shadow-sm" />
                                <div class="overflow-hidden">
                                    <p class="text-xs font-bold text-outline uppercase tracking-wider mb-0.5"><?php echo __('greeting', 'Xin chào,'); ?></p>
                                    <p class="text-sm font-bold text-primary truncate"><?php echo $_SESSION['user_name']; ?></p>
                                </div>
                            </div>
                            <div class="p-2">
                                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1): ?>
                                <a href="<?php echo URLROOT; ?>/admin" class="flex items-center gap-3 px-4 py-3 text-sm text-secondary font-bold hover:bg-secondary/5 rounded-xl transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">dashboard_customize</span> <?php echo __('admin_panel', 'Quản trị hệ thống'); ?>
                                </a>
                                <div class="h-px bg-outline-variant/30 my-1 mx-4"></div>
                                <?php endif; ?>
                                <a href="<?php echo URLROOT; ?>/user/profile" class="flex items-center gap-3 px-4 py-3 text-sm text-on-surface-variant font-medium hover:bg-surface-container hover:text-primary rounded-xl transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">person</span> <?php echo __('profile', 'Hồ sơ cá nhân'); ?>
                                </a>
                                <a href="<?php echo URLROOT; ?>/user/profile?tab=orders" class="flex items-center gap-3 px-4 py-3 text-sm text-on-surface-variant font-medium hover:bg-surface-container hover:text-primary rounded-xl transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">package_2</span> <?php echo __('orders', 'Đơn hàng của tôi'); ?>
                                </a>
                                <a href="<?php echo URLROOT; ?>/user/profile?tab=promotions" class="flex items-center gap-3 px-4 py-3 text-sm text-on-surface-variant font-medium hover:bg-surface-container hover:text-primary rounded-xl transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">confirmation_number</span> <?php echo __('vouchers', 'Mã giảm giá'); ?>
                                </a>
                                <div class="h-px bg-outline-variant/30 my-1 mx-4"></div>
                                <a href="<?php echo URLROOT; ?>/auth/logout" onclick="sessionStorage.clear()" class="flex items-center gap-3 px-4 py-3 text-sm text-error font-bold hover:bg-error/5 rounded-xl transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">logout</span> <?php echo __('logout', 'Đăng xuất'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?php echo URLROOT; ?>/auth/login" class="btn-premium px-4 py-2 text-xs normal-case tracking-normal rounded-xl relative shadow-sm hover:shadow-indigo-500/20 whitespace-nowrap">
                    <?php echo __('login', 'Đăng nhập'); ?>
                </a>
                <?php endif; ?>
            </div>
            
        </div>

        <!-- Row 2: Category Navigation Menu -->
        <div class="border-t border-outline-variant/10 dark:border-outline-variant/5 bg-surface-container-lowest/80 backdrop-blur-md">
            <div class="max-w-container-max mx-auto px-gutter py-2.5 flex items-center justify-center">
                <nav class="hidden lg:flex items-center gap-8 xl:gap-10">
                    <?php 
                        $currentUrl = trim($_GET['url'] ?? '', '/');
                        $isHome = empty($currentUrl) || $currentUrl == 'home';
                    ?>
                    <a class="relative py-2.5 text-[13.5px] tracking-wide <?php echo $isHome ? 'font-black text-secondary after:w-full' : 'font-bold text-on-surface/50 hover:text-secondary'; ?> transition-all whitespace-nowrap after:content-[''] after:absolute after:bottom-[-2px] after:left-0 <?php echo $isHome ? 'after:w-full' : 'after:w-0'; ?> after:h-[2px] after:bg-secondary after:rounded-full hover:after:w-full after:transition-all after:duration-500" href="<?php echo URLROOT; ?>"><?php echo __('home', 'TRANG CHỦ'); ?></a>
                    <a class="relative py-2.5 text-[13.5px] tracking-wide <?php echo ($currentUrl === 'buildpc') ? 'font-black text-secondary after:w-full' : 'font-bold text-on-surface/50 hover:text-secondary'; ?> transition-all whitespace-nowrap after:content-[''] after:absolute after:bottom-[-2px] after:left-0 <?php echo ($currentUrl === 'buildpc') ? 'after:w-full' : 'after:w-0'; ?> after:h-[2px] after:bg-secondary after:rounded-full hover:after:w-full after:transition-all after:duration-500" href="<?php echo URLROOT; ?>/buildpc"><?php echo __('buildpc', 'BUILD PC'); ?></a>
                    
                    <?php if(isset($data['categories'])): ?>
                        <?php foreach($data['categories'] as $index => $cat): ?>
                            <?php $isActive = isset($data['category_id']) && $data['category_id'] == $cat['id']; ?>
                            <div class="relative group nav-item py-2.5">
                                <a class="relative text-[13.5px] tracking-wide <?php echo $isActive ? 'font-black text-secondary' : 'font-bold text-on-surface/50'; ?> group-hover:text-secondary transition-all whitespace-nowrap after:content-[''] after:absolute after:bottom-[-2px] after:left-0 <?php echo $isActive ? 'after:w-full' : 'after:w-0'; ?> after:h-[2px] after:bg-secondary after:rounded-full group-hover:after:w-full after:transition-all after:duration-500" href="<?php echo URLROOT; ?>/product/category/<?php echo $cat['id']; ?>">
                                    <?php echo mb_strtoupper(__($cat['name']), 'UTF-8'); ?>
                                </a>
                                
                                <!-- Premium Mega Menu Dropdown -->
                                <?php if(!empty($cat['subcategories']) || !empty($cat['brands'])): ?>
                                <div class="absolute left-1/2 -translate-x-1/2 top-full pt-0 w-[400px] hidden group-hover:block nav-dropdown z-[110]">
                                    <div class="mt-2 bg-surface-container-lowest border border-outline-variant/30 dark:border-outline-variant/10 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/5 p-6 grid grid-cols-2 gap-8">
                                        <!-- Column 1: Subcategories -->
                                        <?php if(!empty($cat['subcategories'])): ?>
                                        <div>
                                            <p class="text-[11px] font-black text-primary/40 uppercase tracking-widest mb-4 border-b border-outline-variant/10 pb-2"><?php echo __('subcategory_title', 'Danh mục con'); ?></p>
                                            <div class="flex flex-col gap-2">
                                                <?php foreach($cat['subcategories'] as $sub): ?>
                                                <a href="<?php echo URLROOT; ?>/product/category/<?php echo $sub['id']; ?>" class="text-[13px] font-semibold text-on-surface-variant hover:text-secondary transition-colors flex items-center gap-2 group/sub">
                                                    <span class="w-1.5 h-1.5 bg-outline-variant/30 rounded-full group-hover/sub:bg-secondary transition-colors"></span>
                                                    <?php echo __($sub['name']); ?>
                                                </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <!-- Column 2: Brands -->
                                        <?php if(!empty($cat['brands'])): ?>
                                        <div>
                                            <p class="text-[11px] font-black text-primary/40 uppercase tracking-widest mb-4 border-b border-outline-variant/10 pb-2"><?php echo __('brand_title', 'Thương hiệu'); ?></p>
                                            <div class="flex flex-col gap-2">
                                                <?php foreach($cat['brands'] as $brand): ?>
                                                <a href="<?php echo URLROOT; ?>/product/category/<?php echo $cat['id']; ?>?brand=<?php echo $brand['id']; ?>" class="text-[13px] font-semibold text-on-surface-variant hover:text-secondary transition-colors flex items-center gap-2 group/brand">
                                                    <span class="w-1.5 h-1.5 bg-outline-variant/30 rounded-full group-hover/brand:bg-secondary transition-colors"></span>
                                                    <?php echo $brand['name']; ?>
                                                </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <!-- Bottom Action -->
                                        <div class="col-span-2 pt-4 border-t border-outline-variant/10">
                                            <a href="<?php echo URLROOT; ?>/product/category/<?php echo $cat['id']; ?>" class="text-[12px] font-black text-secondary hover:underline flex items-center justify-center gap-2">
                                                <?php echo __('view_all_products', 'XEM TẤT CẢ SẢN PHẨM'); ?> <span class="material-symbols-outlined !text-[16px]">arrow_forward</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php $isTrack = $currentUrl == 'order/track'; ?>
                    <a class="relative py-2.5 text-[13.5px] tracking-wide <?php echo $isTrack ? 'font-black text-secondary after:w-full' : 'font-bold text-on-surface/50 hover:text-secondary'; ?> transition-all whitespace-nowrap after:content-[''] after:absolute after:bottom-[-2px] after:left-0 <?php echo $isTrack ? 'after:w-full' : 'after:w-0'; ?> after:h-[2px] after:bg-secondary after:rounded-full hover:after:w-full after:transition-all after:duration-500" href="<?php echo URLROOT; ?>/order/track"><?php echo __('track_order', 'TRA CỨU'); ?></a>
                </nav>
            </div>
        </div>
    </header>
    
    <!-- Product Comparison Tray -->
    <div id="compare-tray" class="fixed bottom-0 left-0 right-0 z-[100] translate-y-full transition-transform duration-500">
        <div class="max-w-container-max mx-auto px-gutter mb-8">
            <div class="bg-primary/95 backdrop-blur-xl border border-white/10 rounded-[32px] p-6 shadow-2xl shadow-primary/20">
                <div class="flex items-center justify-between gap-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary text-on-secondary rounded-2xl flex items-center justify-center shadow-lg shadow-secondary/20">
                            <span class="material-symbols-outlined !text-[24px]">compare_arrows</span>
                        </div>
                        <div>
                            <h4 class="text-white font-black tracking-tighter text-lg leading-none mb-1"><?php echo __('compare_title', 'So sánh sản phẩm'); ?></h4>
                            <p class="text-white/50 text-xs font-medium"><span id="compare-count">0</span> <?php echo __('compare_selected', 'sản phẩm đã chọn'); ?></p>
                        </div>
                    </div>
                    
                    <div id="compare-items" class="flex-1 flex gap-4 overflow-x-auto py-2">
                        <!-- Comparison items will be injected here -->
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button onclick="clearCompare()" class="px-6 py-3 text-white/50 hover:text-white text-xs font-black uppercase tracking-widest transition-colors"><?php echo __('clear_all', 'XÓA TẤT CẢ'); ?></button>
                        <a id="compare-link" href="<?php echo URLROOT; ?>/product/compare" class="px-8 py-4 bg-secondary text-on-secondary rounded-2xl font-black hover:scale-105 transition-all shadow-xl shadow-secondary/20">
                            <?php echo __('compare_now', 'SO SÁNH NGAY'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const compareTray = document.getElementById('compare-tray');
    const compareCount = document.getElementById('compare-count');
    const compareItems = document.getElementById('compare-items');
    const compareLink = document.getElementById('compare-link');

    function updateCompareTray() {
        const ids = JSON.parse(localStorage.getItem('compare_ids') || '[]');
        compareCount.textContent = ids.length;
        
        if (ids.length > 0) {
            compareTray.classList.remove('translate-y-full');
            compareTray.classList.add('translate-y-0');
            compareLink.href = '<?php echo URLROOT; ?>/product/compare?ids=' + ids.join(',');
            
            // In a real app, we might fetch item info via AJAX. 
            // For now, we'll just show placeholders or use data stored in localStorage if we had it.
            // Let's just update the UI list.
            compareItems.innerHTML = ids.map(id => `
                <div class="relative w-14 h-14 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center group overflow-hidden">
                    <span class="text-[10px] text-white/20 font-black">ID: ${id}</span>
                    <button onclick="toggleCompare(${id})" class="absolute inset-0 bg-red-500/80 text-white opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="material-symbols-outlined !text-[18px]">close</span>
                    </button>
                </div>
            `).join('');
        } else {
            compareTray.classList.add('translate-y-full');
            compareTray.classList.remove('translate-y-0');
        }
    }

    function toggleCompare(id) {
        let ids = JSON.parse(localStorage.getItem('compare_ids') || '[]');
        const index = ids.indexOf(id);
        if (index > -1) {
            ids.splice(index, 1);
        } else {
            if (ids.length >= 4) {
                alert('<?php echo ($_SESSION['lang'] ?? 'vi') === 'en' ? "You can only compare a maximum of 4 products at the same time." : "Bạn chỉ có thể so sánh tối đa 4 sản phẩm cùng lúc."; ?>');
                return;
            }
            ids.push(id);
        }
        localStorage.setItem('compare_ids', JSON.stringify(ids));
        updateCompareTray();
        
        // Update all buttons on page
        document.querySelectorAll(`.compare-btn[data-id="${id}"]`).forEach(btn => {
            btn.classList.toggle('bg-secondary', ids.includes(id));
            btn.classList.toggle('text-on-secondary', ids.includes(id));
        });
    }

    function clearCompare() {
        localStorage.removeItem('compare_ids');
        updateCompareTray();
        document.querySelectorAll('.compare-btn').forEach(btn => {
            btn.classList.remove('bg-secondary', 'text-on-secondary');
        });
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', () => {
        updateCompareTray();
        const ids = JSON.parse(localStorage.getItem('compare_ids') || '[]');
        ids.forEach(id => {
            document.querySelectorAll(`.compare-btn[data-id="${id}"]`).forEach(btn => {
                btn.classList.add('bg-secondary', 'text-on-secondary');
            });
        });
    });
    </script>

    <main class="max-w-container-max mx-auto px-gutter pb-section-padding pt-[70px]">
