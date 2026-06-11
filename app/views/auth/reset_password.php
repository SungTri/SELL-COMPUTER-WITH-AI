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
                        "outline-variant": "#c7c6ca",
                        "background": "#f8f9fb",
                        "error": "#ba1a1a",
                        "on-error": "#ffffff"
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col antialiased">
    <main class="flex-grow flex min-h-screen">
        <div class="hidden lg:block lg:w-1/2 relative bg-surface-variant overflow-hidden">
            <img alt="High-performance PC components" class="absolute inset-0 w-full h-full object-cover" src="https://images.unsplash.com/photo-1591488320449-011701bb6704?auto=format&fit=crop&q=80&w=1200"/>
            <div class="absolute inset-0 bg-primary/10"></div>
        </div>
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-surface">
            <div class="w-full max-w-md bg-surface-container-lowest p-8 rounded-xl shadow-lg border border-outline-variant/30 flex flex-col items-center">
                <div class="mb-8 text-center">
                    <a href="<?php echo URLROOT; ?>" class="inline-block">
                        <span class="text-2xl font-bold text-primary block mb-2 tracking-tight">TechExpert</span>
                    </a>
                    <p class="text-sm text-on-surface-variant"><?php echo __('set_new_password', 'Thiết lập mật khẩu mới'); ?></p>
                </div>
                
                <?php if(isset($_GET['error']) && $_GET['error'] == 'mismatch'): ?>
                <div class="w-full p-4 mb-6 text-sm text-on-error bg-error rounded-lg shadow-sm font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined">error</span>
                    <?php echo __('password_error_match', 'Mật khẩu xác nhận không khớp!'); ?>
                </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/auth/reset_password_process" class="w-full space-y-6" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($data['token']); ?>">
                    <?php echo csrf_field(); ?>
                    

                    <div class="relative">
                        <label class="block font-bold text-on-surface mb-2" for="password"><?php echo __('new_password', 'Mật khẩu mới'); ?></label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3 text-on-surface-variant/70">lock</span>
                            <input class="w-full pl-10 pr-4 py-3 rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary transition-colors" id="password" name="password" placeholder="••••••••" required="" type="password" minlength="6"/>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <label class="block font-bold text-on-surface mb-2" for="confirm_password"><?php echo __('confirm_new_password', 'Xác nhận mật khẩu mới'); ?></label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3 text-on-surface-variant/70">lock</span>
                            <input class="w-full pl-10 pr-4 py-3 rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary transition-colors" id="confirm_password" name="confirm_password" placeholder="••••••••" required="" type="password" minlength="6"/>
                        </div>
                    </div>

                    <button class="w-full bg-secondary text-on-secondary font-bold py-3 px-6 rounded-lg shadow-sm hover:bg-secondary/90 hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2" type="submit">
                        <?php echo __('save_new_password', 'Lưu mật khẩu mới'); ?>
                        <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
