<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <!-- Header -->
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-40">
        <h1 class="text-h2 font-bold text-primary">Cài đặt hệ thống</h1>
        
        <div class="flex items-center gap-8">
            <!-- Notifications -->
            <?php require_once VIEWS . '/layout/admin_notification.php'; ?>

            <div class="flex items-center gap-4 pl-6 border-l border-outline-variant">
                <img alt="Admin" class="w-10 h-10 rounded-full object-cover" src="<?php echo $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=Admin&background=0453cd&color=fff'; ?>"/>
                <div class="text-right">
                    <p class="text-[14px] font-bold"><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></p>
                    <p class="text-[12px] text-on-surface-variant">Quản trị viên</p>
                </div>
            </div>
        </div>
    </header>

    <div class="p-10">

    <?php if (isset($_SESSION['admin_success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 relative animate-in fade-in slide-in-from-top-2">
            <span class="block sm:inline"><?php echo $_SESSION['admin_success']; ?></span>
            <?php unset($_SESSION['admin_success']); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo URLROOT; ?>/admin/updateSettings" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- General Settings -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">settings</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Thông tin chung</h2>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tên cửa hàng</label>
                            <input type="text" name="store_name" value="<?php echo $data['settings']['store_name'] ?? ''; ?>" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email liên hệ</label>
                                <input type="email" name="store_email" value="<?php echo $data['settings']['store_email'] ?? ''; ?>" 
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại</label>
                                <input type="text" name="store_phone" value="<?php echo $data['settings']['store_phone'] ?? ''; ?>" 
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ cửa hàng</label>
                            <textarea name="store_address" rows="3" 
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all resize-none"><?php echo $data['settings']['store_address'] ?? ''; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-500/10 rounded-xl flex items-center justify-center text-green-600">
                            <span class="material-symbols-outlined">search</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Cấu hình SEO</h2>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3" 
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all resize-none" placeholder="Mô tả ngắn về cửa hàng cho công cụ tìm kiếm..."><?php echo $data['settings']['meta_description'] ?? ''; ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="<?php echo $data['settings']['meta_keywords'] ?? ''; ?>" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all" placeholder="laptop, pc, linh kien may tinh...">
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-secondary/10 rounded-xl flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined">share</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Mạng xã hội</h2>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fab fa-facebook-f text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Facebook URL</label>
                                <input type="text" name="facebook_url" value="<?php echo $data['settings']['facebook_url'] ?? ''; ?>" 
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all" placeholder="https://...">
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-tr from-yellow-400 via-red-500 to-purple-600 text-white rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fab fa-instagram text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Instagram URL</label>
                                <input type="text" name="instagram_url" value="<?php echo $data['settings']['instagram_url'] ?? ''; ?>" 
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all" placeholder="https://...">
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-red-600 text-white rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fab fa-youtube text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">YouTube URL</label>
                                <input type="text" name="youtube_url" value="<?php echo $data['settings']['youtube_url'] ?? ''; ?>" 
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all" placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Logo Upload -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-accent/10 rounded-xl flex items-center justify-center text-accent">
                            <span class="material-symbols-outlined">image</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Logo</h2>
                    </div>
                    <div class="p-8 text-center">
                        <div class="w-full aspect-video bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center mb-6 overflow-hidden group relative">
                            <?php if (!empty($data['settings']['store_logo'])): ?>
                                <img src="<?php echo $data['settings']['store_logo']; ?>" alt="Logo" class="max-w-full max-h-full object-contain">
                            <?php else: ?>
                                <div class="text-gray-400">
                                    <span class="material-symbols-outlined text-4xl mb-2">add_photo_alternate</span>
                                    <p class="text-sm">Chưa có logo</p>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <label for="logo_input" class="bg-white text-gray-800 px-4 py-2 rounded-lg font-bold text-sm cursor-pointer hover:bg-gray-100 transition-colors">Thay đổi</label>
                            </div>
                        </div>
                        <input type="file" id="logo_input" name="store_logo" class="hidden" accept="image/*">
                        <p class="text-xs text-gray-500">Khuyến nghị: 400x120px, định dạng PNG hoặc SVG trong suốt.</p>
                    </div>
                </div>

                <!-- Save Card -->
                <div class="bg-primary rounded-3xl shadow-lg shadow-primary/20 p-8 text-white">
                    <h3 class="text-xl font-bold mb-4">Lưu thay đổi</h3>
                    <p class="text-white/80 text-sm mb-8">Đảm bảo bạn đã kiểm tra kỹ các thông tin trước khi áp dụng thay đổi toàn hệ thống.</p>
                    <button type="submit" class="w-full bg-white text-primary py-4 rounded-2xl font-bold hover:bg-gray-100 transition-all shadow-md active:scale-95">
                        Lưu cấu hình
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

    </div>
</main>
</body>
</html>
