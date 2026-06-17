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
</head>
<body class="bg-slate-50 dark:bg-zinc-950 text-on-background min-h-screen flex font-body-md antialiased transition-colors duration-300">

    <!-- Cột trái: Form Đăng ký kính mờ (Glassmorphism) -->
    <main class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-slate-50 dark:bg-zinc-950 relative overflow-hidden min-h-screen">
        
        <!-- Vòng tròn phát sáng nền (Ambient Glows) -->
        <div class="absolute top-[-10%] left-[-10%] w-[350px] md:w-[500px] h-[350px] md:h-[500px] rounded-full bg-blue-500/5 dark:bg-blue-500/10 blur-[80px] md:blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[350px] md:w-[500px] h-[350px] md:h-[500px] rounded-full bg-indigo-500/5 dark:bg-indigo-500/10 blur-[80px] md:blur-[120px] pointer-events-none"></div>

        <!-- Form Card -->
        <div class="w-full max-w-[460px] relative z-10">
            <div class="bg-white/90 dark:bg-zinc-900/60 backdrop-blur-xl rounded-3xl border border-white/20 dark:border-zinc-800/80 p-8 md:p-10 shadow-2xl shadow-slate-200/50 dark:shadow-black/50 transition-all duration-300">
                
                <!-- Header -->
                <div class="text-center mb-8">
                    <a class="inline-flex items-center justify-center mb-6" href="<?php echo URLROOT; ?>">
                        <span class="font-h1 text-3xl font-extrabold text-primary dark:text-white tracking-tighter">TechExpert<span class="text-blue-600 dark:text-blue-500">.</span></span>
                    </a>
                    <p class="font-body-md text-sm text-slate-500 dark:text-zinc-400 mb-2"><?php echo __('precision_engineering', 'Kỹ thuật chính xác cho các chuyên gia.'); ?></p>
                    <h2 class="font-h2 text-2xl font-bold text-slate-800 dark:text-zinc-100"><?php echo __('register_title', 'Tạo tài khoản'); ?></h2>
                </div>

                <!-- Alert báo lỗi -->
                <?php if(isset($_GET['error'])): ?>
                <div class="mb-6 p-4 rounded-2xl flex items-start gap-3 animate-in fade-in slide-in-from-top-2 duration-300 bg-rose-50 dark:bg-rose-950/20 border border-rose-200/50 dark:border-rose-900/50 text-rose-800 dark:text-rose-300">
                    <span class="material-symbols-outlined text-[20px] text-rose-600 dark:text-rose-400 shrink-0 mt-0.5 animate-bounce">
                        error
                    </span>
                    <div class="text-sm font-medium text-left">
                        <?php 
                            if($_GET['error'] == 'email_exists') {
                                echo __('error_email_exists', 'Email này đã được đăng ký tài khoản. Vui lòng sử dụng email khác hoặc Đăng nhập.');
                            } elseif($_GET['error'] == 'mismatch') {
                                echo __('password_error_match', 'Mật khẩu xác nhận không trùng khớp.');
                            } elseif($_GET['error'] == 'invalid_phone') {
                                echo __('profile_error_phone', 'Số điện thoại không hợp lệ. Vui lòng nhập từ 9 đến 15 chữ số.');
                            } else {
                                echo __('error_registration_failed', 'Đăng ký tài khoản không thành công. Vui lòng thử lại.');
                            }
                        ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Form đăng ký -->
                <form action="<?php echo URLROOT; ?>/auth/register_process" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    
                    <div class="space-y-4">
                        <!-- Họ và tên -->
                        <div>
                            <label class="block font-label-bold text-xs font-semibold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2" for="fullName"><?php echo __('fullname_label', 'Họ và tên'); ?></label>
                            <div class="relative">
                                <span aria-hidden="true" class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 pointer-events-none text-[20px]">person</span>
                                <input class="w-full pl-11 pr-4 py-2.5 bg-white/50 dark:bg-zinc-950/50 border border-slate-200 dark:border-zinc-800/80 rounded-xl font-body-md text-sm text-slate-800 dark:text-zinc-100 placeholder:text-slate-400 dark:placeholder:text-zinc-600 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/10 transition-all outline-none" id="fullName" name="fullName" placeholder="Nguyễn Văn A" required="" type="text" value="<?php echo htmlspecialchars($_GET['fullName'] ?? ''); ?>"/>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block font-label-bold text-xs font-semibold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2" for="email">Email</label>
                            <div class="relative">
                                <span aria-hidden="true" class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 pointer-events-none text-[20px]">mail</span>
                                <input class="w-full pl-11 pr-4 py-2.5 bg-white/50 dark:bg-zinc-950/50 border border-slate-200 dark:border-zinc-800/80 rounded-xl font-body-md text-sm text-slate-800 dark:text-zinc-100 placeholder:text-slate-400 dark:placeholder:text-zinc-600 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/10 transition-all outline-none" id="email" name="email" placeholder="nguyenvana@example.com" required="" type="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>"/>
                            </div>
                        </div>

                        <!-- Số điện thoại -->
                        <div>
                            <label class="block font-label-bold text-xs font-semibold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2" for="phone"><?php echo __('phone_label', 'Số điện thoại'); ?></label>
                            <div class="relative">
                                <span aria-hidden="true" class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 pointer-events-none text-[20px]">phone</span>
                                <input class="w-full pl-11 pr-4 py-2.5 bg-white/50 dark:bg-zinc-950/50 border border-slate-200 dark:border-zinc-800/80 rounded-xl font-body-md text-sm text-slate-800 dark:text-zinc-100 placeholder:text-slate-400 dark:placeholder:text-zinc-600 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/10 transition-all outline-none" id="phone" name="phone" placeholder="0901234567" required="" type="tel" pattern="^\+?[0-9\s\-]{9,15}$" maxlength="15" title="<?php echo __('profile_error_phone', 'Số điện thoại không hợp lệ. Vui lòng nhập từ 9 đến 15 chữ số.'); ?>" value="<?php echo htmlspecialchars($_GET['phone'] ?? ''); ?>"/>
                            </div>
                        </div>

                        <!-- Mật khẩu -->
                        <div>
                            <label class="block font-label-bold text-xs font-semibold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2" for="password"><?php echo __('password', 'Mật khẩu'); ?></label>
                            <div class="relative">
                                <span aria-hidden="true" class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 pointer-events-none text-[20px]">lock</span>
                                <input class="w-full pl-11 pr-11 py-2.5 bg-white/50 dark:bg-zinc-950/50 border border-slate-200 dark:border-zinc-800/80 rounded-xl font-body-md text-sm text-slate-800 dark:text-zinc-100 placeholder:text-slate-400 dark:placeholder:text-zinc-600 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/10 transition-all outline-none" id="password" name="password" placeholder="••••••••" required="" type="password"/>
                                <button aria-label="Toggle password visibility" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 transition-colors" type="button" onclick="togglePassword('password', 'passwordIcon')">
                                    <span id="passwordIcon" class="material-symbols-outlined text-[20px]">visibility_off</span>
                                </button>
                            </div>
                        </div>

                        <!-- Xác nhận mật khẩu -->
                        <div>
                            <label class="block font-label-bold text-xs font-semibold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2" for="confirmPassword"><?php echo __('confirm_password', 'Xác nhận mật khẩu'); ?></label>
                            <div class="relative">
                                <span aria-hidden="true" class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 pointer-events-none text-[20px]">lock_reset</span>
                                <input class="w-full pl-11 pr-11 py-2.5 bg-white/50 dark:bg-zinc-950/50 border border-slate-200 dark:border-zinc-800/80 rounded-xl font-body-md text-sm text-slate-800 dark:text-zinc-100 placeholder:text-slate-400 dark:placeholder:text-zinc-600 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/10 transition-all outline-none" id="confirmPassword" name="confirmPassword" placeholder="••••••••" required="" type="password"/>
                                <button aria-label="Toggle password visibility" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 transition-colors" type="button" onclick="togglePassword('confirmPassword', 'confirmPasswordIcon')">
                                    <span id="confirmPasswordIcon" class="material-symbols-outlined text-[20px]">visibility_off</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Nút Đăng ký -->
                    <button class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-label-bold text-sm py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-blue-500/25 active:scale-[0.98] transition-all duration-300 flex justify-center items-center gap-2 border-0 cursor-pointer mt-6" type="submit">
                        <span><?php echo __('register_now', 'Đăng ký ngay'); ?></span>
                        <span aria-hidden="true" class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </form>

                <!-- Footer chuyển sang Đăng nhập -->
                <div class="mt-8 pt-6 border-t border-slate-200 dark:border-zinc-800/80 text-center">
                    <p class="font-body-md text-sm text-slate-500 dark:text-zinc-400">
                        <?php echo __('already_have_account', 'Đã có tài khoản?'); ?> 
                        <a class="font-label-bold text-blue-600 dark:text-blue-500 hover:text-blue-550 dark:hover:text-blue-400 transition-colors font-bold ml-1" href="<?php echo URLROOT; ?>/auth/login"><?php echo __('login_now', 'Đăng nhập ngay'); ?></a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Cột phải: Showcase đối xứng (Mirrored Showcase) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center bg-zinc-950 select-none">
        <!-- Background Image with dynamic scale -->
        <img alt="<?php echo __('register_title', 'Tạo tài khoản'); ?>" class="absolute inset-0 w-full h-full object-cover opacity-35 scale-105 hover:scale-100 transition-transform duration-10000 ease-out" src="https://images.unsplash.com/photo-1587620962725-abab7fe55159?auto=format&fit=crop&q=80&w=1200"/>
        
        <!-- Gradient Overlays -->
        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-l from-blue-900/10 via-transparent to-zinc-950"></div>
        
        <!-- Showcase Content -->
        <div class="relative z-10 max-w-lg p-12 space-y-6 text-left">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold tracking-wider uppercase animate-pulse">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                <?php echo __('register_showcase_tag', 'Đặc quyền Thành viên'); ?>
            </div>
            <h2 class="text-4xl font-extrabold text-white tracking-tight leading-tight font-h1">
                <?php echo __('register_showcase_title', 'Gia nhập cộng đồng chuyên gia công nghệ'); ?>
            </h2>
            <p class="text-zinc-400 leading-relaxed font-body-lg">
                <?php echo __('register_showcase_desc', 'Đăng ký tài khoản để bắt đầu tích lũy điểm thưởng, lưu cấu hình PC yêu thích, nhận thông báo khuyến mãi độc quyền và được hỗ trợ kỹ thuật trọn đời bởi các chuyên gia TechExpert.'); ?>
            </p>
            
            <div class="pt-6 grid grid-cols-2 gap-6 border-t border-white/10">
                <div>
                    <h4 class="text-lg font-bold text-white font-h2"><?php echo __('register_showcase_badge_1_title', 'Ưu Đãi Đặc Biệt'); ?></h4>
                    <p class="text-zinc-500 text-sm mt-1"><?php echo __('register_showcase_badge_1_desc', 'Giảm giá cho các đơn build PC'); ?></p>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white font-h2"><?php echo __('register_showcase_badge_2_title', 'Hỗ Trợ 24/7'); ?></h4>
                    <p class="text-zinc-500 text-sm mt-1"><?php echo __('register_showcase_badge_2_desc', 'Đội ngũ kỹ thuật túc trực'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.innerText = 'visibility';
            } else {
                passwordInput.type = 'password';
                passwordIcon.innerText = 'visibility_off';
            }
        }
    </script>
</body>
</html>
