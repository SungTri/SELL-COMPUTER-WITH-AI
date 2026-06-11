<!DOCTYPE html>
<html lang="<?php echo (isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') ? 'en' : 'vi'; ?>">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $data['title']; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Inter:wght@400;600&amp;display=swap" rel="stylesheet"/>
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
                    "fontFamily": {
                        "h3": ["Hanken Grotesk"],
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "h1": ["Hanken Grotesk"],
                        "label-bold": ["Inter"],
                        "h2": ["Hanken Grotesk"]
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
<body class="bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col lg:flex-row">
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center py-12 px-6 min-h-screen">
        <div class="w-full max-w-md bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-surface-variant overflow-hidden">
            <div class="p-8 text-center border-b border-surface-variant">
                <a href="<?php echo URLROOT; ?>" class="inline-block">
                    <h1 class="font-h2 text-h2 text-primary mb-2">TechExpert</h1>
                </a>
                <p class="font-body-md text-body-md text-on-surface-variant"><?php echo __('system_description', 'Hệ thống bán lẻ máy tính & linh kiện công nghệ.'); ?></p>
            </div>
            
            <div class="p-8 text-center space-y-6">
                <!-- Mailbox animated icon -->
                <div class="w-20 h-20 bg-blue-50 text-secondary rounded-full flex items-center justify-center mx-auto shadow-sm ring-4 ring-blue-50/50 animate-bounce">
                    <span class="material-symbols-outlined text-[44px]">mark_email_read</span>
                </div>
                
                <div class="space-y-2">
                    <h2 class="font-h3 text-h3 text-primary font-bold"><?php echo __('confirm_your_email', 'Xác nhận email của bạn'); ?></h2>
                    <p class="text-[14px] text-on-surface-variant">
                        <?php echo __('activation_email_sent_to', 'Chúng tôi đã gửi một email kích hoạt tài khoản đến địa chỉ:'); ?>
                    </p>
                    <p class="font-bold text-[15px] text-secondary break-all">
                        <?php echo htmlspecialchars($data['email']); ?>
                    </p>
                </div>

                <div class="p-4 bg-surface-container-low rounded-xl border border-surface-variant text-[13px] text-left text-on-surface-variant space-y-2 leading-relaxed">
                    <p class="font-bold text-on-surface flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-secondary">info</span>
                        <?php echo __('activation_instructions', 'Hướng cá nhân kích hoạt:'); ?>
                    </p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><?php echo __('activation_check_inbox', 'Kiểm tra hộp thư đến (Inbox) của bạn.'); ?></li>
                        <li><?php echo __('activation_check_spam', 'Kiểm tra cả thư mục Thư rác (Spam) hoặc Quảng cáo (Promotions) nếu không thấy ở hộp thư chính.'); ?></li>
                        <li><?php echo __('activation_click_link', 'Nhấp vào liên kết kích hoạt bên trong email để hoàn tất.'); ?></li>
                    </ul>
                </div>

                <?php if (defined('BREVO_API_KEY') && strpos(BREVO_API_KEY, '-testmode') !== false): ?>
                    <!-- Test Mode Helper Alert -->
                    <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-[13px] text-left leading-relaxed space-y-1">
                        <p class="font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">terminal</span>
                            <?php echo __('dev_test_mode', 'Developer Test Mode:'); ?>
                        </p>
                        <p><?php echo __('dev_test_mode_desc', 'Hệ thống đang chạy giả lập. Liên kết kích hoạt đã được ghi vào tệp sau trong thư mục dự án của bạn:'); ?></p>
                        <p class="font-mono bg-amber-100/60 p-1.5 rounded text-[11.5px] border border-amber-200 break-all select-all">
                            computer-shop/storage/email_log.txt
                        </p>
                        <p><?php echo __('dev_test_mode_instruction', 'Bạn có thể mở tệp này ra để sao chép liên kết kích hoạt tài khoản và dán trực tiếp vào trình duyệt để test!'); ?></p>
                    </div>
                <?php endif; ?>

                <a href="<?php echo URLROOT; ?>/auth/login" class="w-full bg-secondary hover:bg-secondary/90 text-on-secondary font-label-bold text-label-bold py-3 px-4 rounded transition-colors duration-200 shadow-sm hover:shadow flex justify-center items-center gap-2">
                    <?php echo __('go_to_login', 'Đi tới đăng nhập'); ?>
                    <span class="material-symbols-outlined text-[20px]">login</span>
                </a>
            </div>
            
            <div class="p-6 bg-surface-container-low text-center border-t border-surface-variant">
                <p class="font-body-md text-body-md text-on-surface-variant">
                    <?php echo __('having_issues', 'Gặp vấn đề?'); ?> 
                    <a class="font-label-bold text-label-bold text-secondary hover:text-secondary/80 hover:underline transition-colors" href="<?php echo URLROOT; ?>/page/contact"><?php echo __('contact_support', 'Liên hệ hỗ trợ'); ?></a>
                </p>
            </div>
        </div>
    </div>
    <div class="hidden lg:block lg:w-1/2 relative bg-surface-variant">
        <img alt="Minimalist professional workstation PC" class="absolute inset-0 w-full h-full object-cover" src="https://images.unsplash.com/photo-1587620962725-abab7fe55159?auto=format&fit=crop&q=80&w=1200"/>
    </div>
</body>
</html>
