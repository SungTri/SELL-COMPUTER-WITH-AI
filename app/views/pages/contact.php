<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 mt-8">
    
    <!-- Toast Messages -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="mb-8 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 animate-in fade-in slide-in-from-top-2 duration-300 flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span class="font-medium"><?php echo htmlspecialchars($_GET['msg']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="mb-8 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 animate-in fade-in slide-in-from-top-2 duration-300 flex items-center gap-3">
            <span class="material-symbols-outlined text-red-600">error</span>
            <span class="font-medium"><?php echo htmlspecialchars($_GET['error']); ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Side: Store Information Card -->
        <div class="lg:col-span-5 bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant shadow-sm space-y-8 animate-in fade-in duration-500">
            <div>
                <h1 class="text-3xl font-bold text-primary tracking-tight"><?php echo __('contact_title', 'Liên hệ với chúng tôi'); ?></h1>
                <p class="text-on-surface-variant text-sm mt-2 leading-relaxed">
                    <?php echo __('contact_subtitle', 'TechExpert luôn sẵn sàng hỗ trợ giải đáp mọi thắc mắc và tiếp nhận ý kiến đóng góp từ khách hàng.'); ?>
                </p>
            </div>

            <!-- Address and Details -->
            <div class="space-y-6">
                <!-- Address -->
                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-xl">location_on</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-primary text-sm uppercase tracking-wider"><?php echo __('contact_address_label', 'Địa chỉ'); ?></h3>
                        <p class="text-on-surface-variant text-sm mt-1">
                            <?php echo $data['app_settings']['store_address'] ?? '123 Đường Ba Tháng Hai, Quận 10, TP. Hồ Chí Minh'; ?>
                        </p>
                    </div>
                </div>

                <!-- Hotline -->
                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-xl">call</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-secondary text-sm uppercase tracking-wider"><?php echo __('contact_phone_label', 'Hotline hỗ trợ'); ?></h3>
                        <p class="text-on-surface-variant text-sm mt-1 font-semibold">
                            <?php echo $data['app_settings']['store_phone'] ?? '1900 1234'; ?>
                        </p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-green-500/10 text-green-600 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-xl">mail</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-green-600 text-sm uppercase tracking-wider"><?php echo __('contact_email_label', 'Email liên hệ'); ?></h3>
                        <p class="text-on-surface-variant text-sm mt-1">
                            <?php echo $data['app_settings']['store_email'] ?? 'support@techexpert.vn'; ?>
                        </p>
                    </div>
                </div>

                <!-- Hours -->
                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-xl">schedule</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-amber-600 text-sm uppercase tracking-wider"><?php echo __('contact_hours_label', 'Giờ hoạt động'); ?></h3>
                        <p class="text-on-surface-variant text-sm mt-1">
                            <?php echo __('contact_hours_value', '8:00 - 21:00 hàng ngày (kể cả chủ nhật và ngày lễ)'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Feedback Form -->
        <div class="lg:col-span-7 bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant shadow-sm animate-in fade-in duration-500 delay-100">
            <h2 class="text-2xl font-bold text-primary mb-6 flex items-center gap-3">
                <span class="material-symbols-outlined text-[28px] text-secondary">rate_review</span>
                <?php echo __('feedback_form_title', 'Gửi góp ý của bạn'); ?>
            </h2>

            <?php if (isset($_SESSION['customer_id'])): ?>
                <!-- Form for Logged-in Customers -->
                <form action="<?php echo URLROOT; ?>/feedback/submit" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">title</span>
                            <?php echo __('feedback_title_label', 'Tiêu đề góp ý'); ?>
                        </label>
                        <input type="text" name="title" required
                            class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:ring-4 focus:ring-secondary/10 focus:border-secondary outline-none transition-all text-body-md" 
                            placeholder="<?php echo __('feedback_title_placeholder', 'Nhập tiêu đề hoặc vấn đề bạn muốn góp ý...'); ?>" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">subject</span>
                            <?php echo __('feedback_content_label', 'Nội dung chi tiết'); ?>
                        </label>
                        <textarea name="content" rows="6" required
                            class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:ring-4 focus:ring-secondary/10 focus:border-secondary outline-none transition-all text-body-md"
                            placeholder="<?php echo __('feedback_content_placeholder', 'Mô tả chi tiết ý kiến, khiếu nại hoặc đóng góp của bạn tại đây để cửa hàng hỗ trợ tốt nhất...'); ?>"></textarea>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-secondary text-white px-8 py-3.5 rounded-xl font-bold hover:shadow-lg hover:bg-secondary/90 transition-all flex items-center gap-2 cursor-pointer border-0">
                            <span class="material-symbols-outlined text-[20px]">send</span>
                            <?php echo __('feedback_submit_btn', 'Gửi góp ý'); ?>
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <!-- Not Logged In Placeholder -->
                <div class="py-12 px-6 border border-dashed border-outline-variant rounded-2xl text-center space-y-6 bg-surface/50">
                    <div class="w-16 h-16 bg-outline-variant/20 text-on-surface-variant rounded-full flex items-center justify-center mx-auto">
                        <span class="material-symbols-outlined text-3xl">lock</span>
                    </div>
                    <div class="space-y-2 max-w-sm mx-auto">
                        <h3 class="font-bold text-primary text-lg"><?php echo __('feedback_login_required_title', 'Đăng nhập để gửi góp ý'); ?></h3>
                        <p class="text-on-surface-variant text-sm leading-relaxed">
                            <?php echo __('feedback_login_required_desc', 'Để bảo mật thông tin và nhận được hỗ trợ trực tiếp từ ban quản lý, bạn vui lòng đăng nhập tài khoản khách hàng.'); ?>
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="<?php echo URLROOT; ?>/auth/login" class="inline-flex items-center gap-2 bg-secondary text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg hover:bg-secondary/90 transition-all">
                            <span class="material-symbols-outlined text-[18px]">login</span>
                            <?php echo __('login', 'Đăng nhập ngay'); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
