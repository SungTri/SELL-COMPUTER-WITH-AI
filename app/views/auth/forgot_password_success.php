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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col antialiased">
    <main class="flex-grow flex min-h-screen items-center justify-center p-6 bg-surface">
        <div class="w-full max-w-md bg-surface-container-lowest p-8 rounded-xl shadow-lg border border-outline-variant/30 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6">
                <span class="material-symbols-outlined !text-4xl">check_circle</span>
            </div>
            <h1 class="text-2xl font-bold text-primary mb-2"><?php echo __('password_reset_success_title', 'Cấp lại mật khẩu thành công!'); ?></h1>
            <p class="text-on-surface-variant mb-6 text-sm leading-relaxed">
                Chúng tôi đã cấp mật khẩu mới và gửi tới địa chỉ email:<br><strong class="text-primary font-bold break-all"><?php echo htmlspecialchars($data['email']); ?></strong>.<br>Vui lòng kiểm tra hộp thư của bạn (và cả mục thư rác/spam).
            </p>

            <a class="w-full py-3 bg-primary hover:bg-secondary text-white font-bold rounded-xl text-center shadow-sm flex items-center justify-center gap-2 transition-all" href="<?php echo URLROOT; ?>/auth/login">
                <span><?php echo __('login', 'Đăng nhập ngay'); ?></span>
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </a>
        </div>
    </main>
</body>
</html>
