<!DOCTYPE html>
<html lang="<?php echo (isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') ? 'en' : 'vi'; ?>">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $data['title']; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary": "#0453cd",
                        "primary": "#000000",
                        "on-secondary": "#ffffff",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#46474a",
                        "surface": "#f8f9fb",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f3f4f6",
                        "surface-variant": "#e1e2e4",
                        "outline-variant": "#c7c6ca",
                        "background": "#f8f9fb",
                        "on-primary": "#ffffff",
                        "inverse-surface": "#2e3132",
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "3xl": "1.5rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "unit": "8px",
                        "section-padding": "80px",
                        "gutter": "24px",
                        "card-gap": "32px",
                        "container-max": "1280px"
                    },
                    "fontFamily": {
                        "h1": ["Outfit", "sans-serif"],
                        "h2": ["Outfit", "sans-serif"],
                        "h3": ["Outfit", "sans-serif"],
                        "body-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "body-md": ["Plus Jakarta Sans", "sans-serif"],
                        "label-bold": ["Plus Jakarta Sans", "sans-serif"],
                        "price-display": ["Outfit", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    <!-- Google Identity Services SDK -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body class="bg-slate-50 dark:bg-zinc-950 text-on-background min-h-screen flex font-body-md antialiased transition-colors duration-300">
    
    <!-- Cột trái: Dynamic Product Showcase -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center bg-zinc-950 select-none">
        <!-- Background Image with dynamic scale -->
        <img alt="<?php echo __('login_title', 'Đăng nhập vào hệ thống'); ?>" class="absolute inset-0 w-full h-full object-cover opacity-35 scale-105 hover:scale-100 transition-transform duration-10000 ease-out" src="https://images.unsplash.com/photo-1587620962725-abab7fe55159?auto=format&fit=crop&q=80&w=1200"/>
        
        <!-- Gradient Overlays for depth and high-end feel -->
        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/10 via-transparent to-zinc-950"></div>
        
        <!-- Showcase Content Card -->
        <div class="relative z-10 max-w-lg p-12 space-y-6 text-left">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold tracking-wider uppercase animate-pulse">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                TechExpert Store
            </div>
            <h2 class="text-4xl font-extrabold text-white tracking-tight leading-tight font-h1">
                <?php echo __('login_showcase_title', 'Kiến tạo không gian làm việc chuyên nghiệp'); ?>
            </h2>
            <p class="text-zinc-400 leading-relaxed font-body-lg">
                <?php echo __('login_showcase_desc', 'Đăng nhập để tiếp tục khám phá thế giới linh kiện máy tính cao cấp, nhận các đặc quyền thành viên và dịch vụ tư vấn cấu hình tự động bằng trí tuệ nhân tạo (AI).'); ?>
            </p>
            
            <div class="pt-6 grid grid-cols-2 gap-6 border-t border-white/10">
                <div>
                    <h4 class="text-lg font-bold text-white font-h2"><?php echo __('login_showcase_badge_1_title', '100% Chính Hãng'); ?></h4>
                    <p class="text-zinc-500 text-sm mt-1"><?php echo __('login_showcase_badge_1_desc', 'Linh kiện kiểm định kỹ càng'); ?></p>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white font-h2"><?php echo __('login_showcase_badge_2_title', 'Hỗ Trợ AI'); ?></h4>
                    <p class="text-zinc-500 text-sm mt-1"><?php echo __('login_showcase_badge_2_desc', 'Tư vấn cấu hình tối ưu'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cột phải: Form Đăng nhập kính mờ (Glassmorphism) -->
    <main class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-slate-50 dark:bg-zinc-950 relative overflow-hidden min-h-screen">
        
        <!-- Vòng tròn phát sáng nền (Ambient Glows) -->
        <div class="absolute top-[-10%] left-[-10%] w-[350px] md:w-[500px] h-[350px] md:h-[500px] rounded-full bg-blue-500/5 dark:bg-blue-500/10 blur-[80px] md:blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[350px] md:w-[500px] h-[350px] md:h-[500px] rounded-full bg-indigo-500/5 dark:bg-indigo-500/10 blur-[80px] md:blur-[120px] pointer-events-none"></div>

        <!-- Form Card -->
        <div class="w-full max-w-[460px] relative z-10">
            <div class="bg-white/90 dark:bg-zinc-900/60 backdrop-blur-xl rounded-3xl border border-white/20 dark:border-zinc-800/80 p-8 md:p-10 shadow-2xl shadow-slate-200/50 dark:shadow-black/50 transition-all duration-300">
                
                <!-- Logo & Header -->
                <div class="text-center mb-8">
                    <a class="inline-flex items-center justify-center mb-6" href="<?php echo URLROOT; ?>">
                        <span class="font-h1 text-3xl font-extrabold text-primary dark:text-white tracking-tighter">TechExpert<span class="text-blue-600 dark:text-blue-500">.</span></span>
                    </a>
                    <h1 class="font-h2 text-2xl font-bold text-slate-800 dark:text-zinc-100 mb-2"><?php echo __('login_subtitle', 'Chào mừng trở lại'); ?></h1>
                    <p class="font-body-md text-sm text-slate-500 dark:text-zinc-400"><?php echo __('login_title_desc', 'Đăng nhập vào tài khoản của bạn để tiếp tục.'); ?></p>
                </div>

                <!-- Alert báo thành công -->
                <?php if(isset($_GET['success'])): ?>
                <div class="mb-6 p-4 rounded-2xl flex items-start gap-3 animate-in fade-in slide-in-from-top-2 duration-300 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200/50 dark:border-emerald-900/50 text-emerald-800 dark:text-emerald-300">
                    <span class="material-symbols-outlined text-[20px] text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5 animate-bounce">
                        check_circle
                    </span>
                    <div class="text-sm font-medium">
                        <?php 
                            if($_GET['success'] == 'activated') {
                                echo __('account_activated_success', 'Tài khoản của bạn đã được kích hoạt thành công! Giờ đây bạn có thể đăng nhập.');
                            } elseif($_GET['success'] == 'password_reset') {
                                echo __('password_reset_success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập bằng mật khẩu mới.');
                            } else {
                                echo __('action_success', 'Thực hiện thành công!');
                            }
                        ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Alert báo lỗi -->
                <?php if(isset($_GET['error'])): ?>
                <div class="mb-6 p-4 rounded-2xl flex items-start gap-3 animate-in fade-in slide-in-from-top-2 duration-300 <?php 
                    echo in_array($_GET['error'], ['invalid', 'activation_failed', 'invalid_token']) 
                        ? 'bg-rose-50 dark:bg-rose-950/20 border border-rose-200/50 dark:border-rose-900/50 text-rose-800 dark:text-rose-300' 
                        : 'bg-amber-50 dark:bg-amber-950/20 border border-amber-200/50 dark:border-amber-900/50 text-amber-800 dark:text-amber-300'; 
                ?>">
                    <span class="material-symbols-outlined text-[20px] <?php 
                        echo in_array($_GET['error'], ['invalid', 'activation_failed', 'invalid_token']) ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400'; 
                    ?> shrink-0 mt-0.5">
                        <?php 
                            echo in_array($_GET['error'], ['invalid', 'activation_failed', 'invalid_token']) ? 'error' : 'warning'; 
                        ?>
                    </span>
                    <div class="text-sm font-medium">
                        <?php 
                            if($_GET['error'] == 'invalid') {
                                echo __('error_invalid_credentials', 'Email hoặc mật khẩu không chính xác.');
                            } elseif($_GET['error'] == 'inactive') {
                                echo __('error_inactive_account', 'Tài khoản chưa được kích hoạt qua email. Vui lòng kiểm tra hộp thư (bao gồm cả thư rác/spam) hoặc đăng ký lại để gửi lại email kích hoạt.');
                            } elseif($_GET['error'] == 'activation_expired') {
                                echo __('error_activation_expired', 'Liên kết kích hoạt đã hết hạn (chỉ có hiệu lực trong 24 giờ). Vui lòng đăng ký lại tài khoản để nhận liên kết mới.');
                            } elseif($_GET['error'] == 'activation_failed') {
                                echo __('error_activation_failed', 'Liên kết kích hoạt không hợp lệ hoặc tài khoản đã được kích hoạt từ trước.');
                            } elseif($_GET['error'] == 'invalid_token') {
                                echo __('error_invalid_token', 'Mã xác thực không hợp lệ hoặc đã hết hạn.');
                            } else {
                                echo __('error_occurred', 'Đã có lỗi xảy ra. Vui lòng thử lại.');
                            }
                        ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Form đăng nhập -->
                <form action="<?php echo URLROOT; ?>/auth/auth" class="space-y-5" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Email -->
                    <div>
                        <label class="block font-label-bold text-xs font-semibold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2" for="email"><?php echo __('email_address', 'Địa chỉ Email'); ?></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 pointer-events-none text-[20px]">mail</span>
                            <input class="w-full pl-11 pr-4 py-3 bg-white/50 dark:bg-zinc-950/50 border border-slate-200 dark:border-zinc-800/80 rounded-xl font-body-md text-sm text-slate-800 dark:text-zinc-100 placeholder:text-slate-400 dark:placeholder:text-zinc-600 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/10 transition-all outline-none" id="email" name="email" placeholder="name@company.com" required="" type="email" value="<?php echo $_GET['email'] ?? ''; ?>"/>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block font-label-bold text-xs font-semibold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2" for="password"><?php echo __('password', 'Mật khẩu'); ?></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 pointer-events-none text-[20px]">lock</span>
                            <input class="w-full pl-11 pr-11 py-3 bg-white/50 dark:bg-zinc-950/50 border border-slate-200 dark:border-zinc-800/80 rounded-xl font-body-md text-sm text-slate-800 dark:text-zinc-100 placeholder:text-slate-400 dark:placeholder:text-zinc-600 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/10 transition-all outline-none" id="password" name="password" placeholder="••••••••" required="" type="password" autocomplete="new-password"/>
                            <button aria-label="Toggle password visibility" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 transition-colors" type="button" onclick="togglePassword()">
                                <span id="passwordIcon" class="material-symbols-outlined text-[20px]">visibility_off</span>
                            </button>
                        </div>
                    </div>

                    <!-- Ghi nhớ & Quên mật khẩu -->
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center">
                            <input class="h-4 w-4 rounded border-slate-200 dark:border-zinc-800 text-blue-600 focus:ring-blue-500/20 bg-white/50 dark:bg-zinc-950/50 cursor-pointer transition-all" id="remember-me" name="remember-me" type="checkbox"/>
                            <label class="ml-2 block font-body-md text-sm text-slate-600 dark:text-zinc-400 cursor-pointer select-none hover:text-slate-800 dark:hover:text-zinc-200 transition-colors" for="remember-me">
                                <?php echo __('remember_me', 'Ghi nhớ đăng nhập'); ?>
                            </label>
                        </div>
                        <div class="text-sm">
                            <a class="font-label-bold text-sm text-blue-600 dark:text-blue-500 hover:text-blue-500 dark:hover:text-blue-400 transition-colors" href="<?php echo URLROOT; ?>/auth/forgot_password" onclick="openForgotPasswordModal(event)">
                                <?php echo __('forgot_password_link', 'Quên mật khẩu?'); ?>
                            </a>
                        </div>
                    </div>

                    <!-- Nút đăng nhập -->
                    <button class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-label-bold text-sm py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-blue-500/25 active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 group cursor-pointer border-0 mt-2" type="submit">
                        <span><?php echo __('login', 'Đăng nhập'); ?></span>
                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </form>

                <!-- Ngăn cách -->
                <div class="mt-8 relative">
                    <div aria-hidden="true" class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200 dark:border-zinc-800/80"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase tracking-wider font-semibold">
                        <span class="px-3 bg-white dark:bg-zinc-900/60 text-slate-400 dark:text-zinc-500 rounded-full backdrop-blur-xl"><?php echo __('or_continue_with', 'Hoặc tiếp tục với'); ?></span>
                    </div>
                </div>

                <!-- Đăng nhập mạng xã hội -->
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="relative w-full h-[46px] overflow-hidden rounded-xl">
                        <!-- Visible Custom styled button -->
                        <button id="custom-google-btn" class="flex items-center justify-center gap-2.5 w-full h-full border border-slate-200 dark:border-zinc-800 rounded-xl bg-white dark:bg-zinc-950/40 transition-colors text-slate-700 dark:text-zinc-300 font-label-bold text-sm cursor-pointer" type="button">
                            <svg class="h-5 w-5 shrink-0" style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                            </svg>
                            <span>Google</span>
                        </button>
                        <!-- Invisible official Google GSI button overlay -->
                        <div id="google-btn-container" class="absolute inset-0 w-full h-full opacity-[0.01] overflow-hidden cursor-pointer"></div>
                    </div>
                    <button class="flex items-center justify-center gap-2.5 w-full px-4 py-3 border border-slate-200 dark:border-zinc-800 rounded-xl bg-white dark:bg-zinc-950/40 hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors text-slate-700 dark:text-zinc-300 font-label-bold text-sm cursor-pointer" type="button">
                        <svg class="h-5 w-5 shrink-0" style="width: 20px; height: 20px;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="12" fill="#ffffff"/>
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="#1877F2"/>
                        </svg>
                        <span>Facebook</span>
                    </button>
                </div>

                <!-- Đăng ký ngay -->
                <p class="mt-8 text-center font-body-md text-sm text-slate-500 dark:text-zinc-400">
                    <?php echo __('dont_have_account', 'Chưa có tài khoản?'); ?> 
                    <a class="font-label-bold text-blue-600 dark:text-blue-500 hover:text-blue-550 dark:hover:text-blue-400 transition-colors font-bold ml-1" href="<?php echo URLROOT; ?>/auth/register"><?php echo __('register_now', 'Đăng ký ngay'); ?></a>
                </p>
            </div>
        </div>
    </main>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 dark:bg-black/85 backdrop-blur-sm transition-all duration-300" onclick="closeForgotPasswordModal()"></div>
        
        <!-- Modal content -->
        <div class="relative w-full max-w-md bg-white/95 dark:bg-zinc-900/90 backdrop-blur-xl p-8 rounded-3xl shadow-2xl border border-white/20 dark:border-zinc-800/80 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="modalContainer">
            <!-- Close button -->
            <button onclick="closeForgotPasswordModal()" class="absolute right-5 top-5 text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-300 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
            
            <div id="modalFormContent">
                <div class="text-center mb-6">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 dark:text-blue-400 animate-bounce">
                        <span class="material-symbols-outlined text-[28px]">lock_reset</span>
                    </div>
                    <h2 class="font-h2 text-xl font-bold text-slate-800 dark:text-zinc-100"><?php echo __('forgot_password_link', 'Quên mật khẩu?'); ?></h2>
                    <p class="font-body-md text-sm text-slate-500 dark:text-zinc-400 mt-2">
                        Nhập địa chỉ email liên kết với tài khoản. Chúng tôi sẽ cấp mật khẩu mới qua email.
                    </p>
                </div>
                
                <!-- Error Alert Inside Modal -->
                <div id="modalErrorAlert" class="hidden mb-4 p-3 bg-rose-50 dark:bg-rose-950/20 border border-rose-200/50 dark:border-rose-900/50 text-rose-800 dark:text-rose-300 rounded-xl text-sm flex items-start gap-2">
                    <span class="material-symbols-outlined text-[18px] text-rose-600 dark:text-rose-400 shrink-0">error</span>
                    <span id="modalErrorMessage" class="font-body-md"></span>
                </div>
                
                <form id="forgotPasswordForm" onsubmit="handleForgotPasswordSubmit(event)" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block font-label-bold text-xs font-semibold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2" for="modal_email">Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 pointer-events-none text-[20px]">mail</span>
                            <input class="w-full pl-11 pr-4 py-2.5 bg-white/50 dark:bg-zinc-950/50 border border-slate-200 dark:border-zinc-800/80 rounded-xl text-sm text-slate-800 dark:text-zinc-100 placeholder:text-slate-400 dark:placeholder:text-zinc-600 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/10 transition-all outline-none" id="modal_email" name="email" placeholder="nhap@email.com" required type="email"/>
                        </div>
                    </div>
                    <button type="submit" id="modalSubmitBtn" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-label-bold py-2.5 rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-blue-500/25 active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 border-0 cursor-pointer">
                        <span id="modalBtnText">Gửi mật khẩu mới</span>
                        <span id="modalBtnSpinner" class="hidden animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                    </button>
                </form>
            </div>
            
            <!-- Success State -->
            <div id="modalSuccessContent" class="hidden text-center py-4">
                <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm animate-pulse">
                    <span class="material-symbols-outlined !text-4xl">check_circle</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-zinc-100 mb-2">Cấp lại mật khẩu thành công!</h3>
                <p class="text-slate-500 dark:text-zinc-400 text-sm leading-relaxed mb-6 font-body-md" id="modalSuccessMessage"></p>
                <button onclick="closeForgotPasswordModal()" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-2.5 rounded-xl transition-all shadow-md cursor-pointer border-0">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.innerText = 'visibility';
            } else {
                passwordInput.type = 'password';
                passwordIcon.innerText = 'visibility_off';
            }
        }

        // Tự động xóa và focus vào ô mật khẩu khi có lỗi
        window.addEventListener('DOMContentLoaded', (event) => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error')) {
                const passwordInput = document.getElementById('password');
                if (passwordInput) {
                    passwordInput.value = '';
                    passwordInput.focus();
                }
            }
        });

        // Forgot Password Modal Functions
        function openForgotPasswordModal(e) {
            if (e) e.preventDefault();
            const modal = document.getElementById('forgotPasswordModal');
            const container = document.getElementById('modalContainer');
            
            // Reset states
            document.getElementById('modalFormContent').classList.remove('hidden');
            document.getElementById('modalSuccessContent').classList.add('hidden');
            document.getElementById('modalErrorAlert').classList.add('hidden');
            document.getElementById('modal_email').value = '';
            
            modal.classList.remove('hidden');
            // For smooth entrance transition
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeForgotPasswordModal() {
            const modal = document.getElementById('forgotPasswordModal');
            const container = document.getElementById('modalContainer');
            
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Handle AJAX Forgot Password Submit
        function handleForgotPasswordSubmit(e) {
            e.preventDefault();
            const email = document.getElementById('modal_email').value;
            const submitBtn = document.getElementById('modalSubmitBtn');
            const btnText = document.getElementById('modalBtnText');
            const spinner = document.getElementById('modalBtnSpinner');
            const errorAlert = document.getElementById('modalErrorAlert');
            
            // Show spinner and disable button
            submitBtn.disabled = true;
            btnText.classList.add('opacity-50');
            spinner.classList.remove('hidden');
            errorAlert.classList.add('hidden');
            
            // Prepare form data
            const formData = new FormData(document.getElementById('forgotPasswordForm'));
            formData.append('ajax', '1');
            
            fetch('<?php echo URLROOT; ?>/auth/forgot_password_process', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Reset button state
                submitBtn.disabled = false;
                btnText.classList.remove('opacity-50');
                spinner.classList.add('hidden');
                
                if (data.success) {
                    document.getElementById('modalFormContent').classList.add('hidden');
                    const successContent = document.getElementById('modalSuccessContent');
                    const successMsg = document.getElementById('modalSuccessMessage');
                    
                    successMsg.innerHTML = data.message + '.<br>Vui lòng kiểm tra hộp thư của bạn (và cả thư mục rác/spam).';
                    successContent.classList.remove('hidden');
                } else {
                    errorAlert.classList.remove('hidden');
                    document.getElementById('modalErrorMessage').innerText = data.message;
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                btnText.classList.remove('opacity-50');
                spinner.classList.add('hidden');
                
                errorAlert.classList.remove('hidden');
                document.getElementById('modalErrorMessage').innerText = 'Đã xảy ra lỗi kết nối. Vui lòng thử lại sau.';
                console.error('Error:', error);
            });
        }

        // Initialize Google Sign-In GSI SDK
        window.addEventListener('load', function() {
            const googleClientId = '<?php echo $_ENV['GOOGLE_CLIENT_ID'] ?? ''; ?>';
            if (googleClientId && googleClientId !== 'YOUR_GOOGLE_CLIENT_ID_HERE') {
                google.accounts.id.initialize({
                    client_id: googleClientId,
                    callback: handleCredentialResponse
                });
                
                google.accounts.id.renderButton(
                    document.getElementById("google-btn-container"),
                    { 
                        theme: "outline", 
                        size: "large", 
                        width: 320, // Render a wide button so it fully covers our button area
                        text: "continue_with"
                    }
                );
                
                // Hover state synchronization
                const googleContainer = document.getElementById("google-btn-container");
                const customGoogleBtn = document.getElementById("custom-google-btn");
                if (googleContainer && customGoogleBtn) {
                    googleContainer.addEventListener('mouseenter', () => {
                        customGoogleBtn.classList.add('bg-slate-50', 'dark:bg-zinc-800/50');
                    });
                    googleContainer.addEventListener('mouseleave', () => {
                        customGoogleBtn.classList.remove('bg-slate-50', 'dark:bg-zinc-800/50');
                    });
                }
                
                // Show One Tap prompt
                google.accounts.id.prompt();
            } else {
                // Fallback when Client ID is not configured
                const container = document.getElementById("google-btn-container");
                if (container) {
                    container.classList.remove('opacity-[0.01]');
                    container.innerHTML = `
                        <button onclick="alert('Vui lòng cấu hình GOOGLE_CLIENT_ID trong file .env để sử dụng chức năng đăng nhập Google.')" class="flex items-center justify-center gap-2 w-full h-full border border-slate-200 dark:border-zinc-800 rounded-xl bg-slate-100 dark:bg-zinc-800/40 hover:bg-slate-200 dark:hover:bg-zinc-800/60 transition-colors text-slate-400 dark:text-zinc-500 font-label-bold text-xs" type="button">
                            <span>Google (Chưa cấu hình)</span>
                        </button>
                    `;
                }
            }
        });

        function handleCredentialResponse(response) {
            const idToken = response.credential;
            
            fetch('<?php echo URLROOT; ?>/auth/google_login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: 'id_token=' + encodeURIComponent(idToken)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Đăng nhập Google thất bại.');
                }
            })
            .catch(err => {
                console.error('Error during Google login:', err);
                alert('Có lỗi xảy ra trong quá trình đăng nhập bằng Google.');
            });
        }
    </script>
</body>
</html>
