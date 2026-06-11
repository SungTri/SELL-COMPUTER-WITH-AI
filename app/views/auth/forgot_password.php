<!DOCTYPE html>
<html lang="<?php echo (isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') ? 'en' : 'vi'; ?>">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $data['title']; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700&amp;family=Inter:wght@400;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                        "h2": ["Hanken Grotesk"],
                        "h3": ["Hanken Grotesk"],
                        "h1": ["Hanken Grotesk"],
                        "body-md": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-bold": ["Inter"]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col antialiased">
    <main class="flex-grow flex min-h-screen">
        <div class="hidden lg:block lg:w-1/2 relative bg-surface-variant overflow-hidden">
            <img alt="<?php echo __('forgot_password_link', 'Quên mật khẩu?'); ?>" class="absolute inset-0 w-full h-full object-cover" src="https://images.unsplash.com/photo-1591488320449-011701bb6704?auto=format&fit=crop&q=80&w=1200"/>
            <div class="absolute inset-0 bg-primary/10"></div>
        </div>
        <div class="w-full lg:w-1/2 flex items-center justify-center p-gutter bg-surface">
            <div class="w-full max-w-md bg-surface-container-lowest p-card-gap rounded-xl shadow-lg border border-outline-variant/30 flex flex-col items-center">
                <div class="mb-8 text-center">
                    <a href="<?php echo URLROOT; ?>" class="inline-block">
                        <span class="font-h2 text-h2 font-bold text-primary block mb-2 tracking-tight">TechExpert</span>
                    </a>
                    <p class="font-body-md text-body-md text-on-surface-variant"><?php echo __('restore_access', 'Khôi phục quyền truy cập'); ?></p>
                </div>
                <div class="w-full mb-6">
                    <h1 class="font-h3 text-h3 text-on-surface mb-2"><?php echo __('forgot_password_link', 'Quên mật khẩu?'); ?></h1>
                    <p class="font-body-md text-body-md text-on-surface-variant">
                        <?php echo __('forgot_password_desc', 'Nhập địa chỉ email liên kết với tài khoản của bạn. Chúng tôi sẽ gửi một liên kết để thiết lập lại mật khẩu.'); ?>
                    </p>
                </div>
                <form action="<?php echo URLROOT; ?>/auth/forgot_password_process" class="w-full space-y-6" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="relative">
                        <label class="block font-label-bold text-label-bold text-on-surface mb-2" for="email">Email</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3 text-on-surface-variant/70">mail</span>
                            <input class="w-full pl-10 pr-4 py-3 rounded-lg border border-outline-variant bg-surface-container-lowest font-body-md text-body-md text-on-surface focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary transition-colors" id="email" name="email" placeholder="nhap@email.com" required="" type="email"/>
                        </div>
                    </div>
                    <button class="w-full bg-secondary text-on-secondary font-label-bold text-label-bold py-3 px-6 rounded-lg shadow-sm hover:bg-secondary/90 hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2" type="submit">
                        <?php echo __('send_reset_link', 'Gửi liên kết khôi phục'); ?>
                        <span class="material-symbols-outlined" style="font-size: 18px;">arrow_forward</span>
                    </button>
                </form>
                <div class="mt-8 text-center w-full pt-6 border-t border-outline-variant/30">
                    <a class="inline-flex items-center gap-2 font-label-bold text-label-bold text-on-surface-variant hover:text-primary transition-colors" href="<?php echo URLROOT; ?>/auth/login">
                        <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span>
                        <?php echo __('back_to_login', 'Quay lại Đăng nhập'); ?>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
