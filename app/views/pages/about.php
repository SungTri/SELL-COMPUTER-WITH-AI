<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="max-w-6xl mx-auto py-16 px-4 sm:px-6 lg:px-8 mt-6">
    <!-- Hero Banner with Modern Gradient -->
    <div class="relative rounded-3xl overflow-hidden mb-16 shadow-2xl border border-outline-variant/20">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/95 via-primary/80 to-secondary/80 z-10"></div>
        <!-- Decorative subtle grid background pattern -->
        <div class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] bg-[size:24px_24px] z-0"></div>
        
        <div class="relative z-20 py-20 px-8 md:px-16 text-center md:text-left max-w-3xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-secondary/20 text-secondary-container font-semibold text-xs uppercase tracking-wider mb-6 border border-secondary/30">
                <span class="w-1.5 h-1.5 bg-secondary rounded-full"></span>
                <?php echo __('about_us_tag', 'Về Chúng Tôi'); ?>
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight leading-none">
                <?php echo __('about_banner_title', 'Định Hình Tương Lai <br> <span class="text-secondary-container bg-clip-text">Công Nghệ Việt Nam</span>'); ?>
            </h1>
            <p class="text-lg text-white/80 leading-relaxed font-medium">
                <?php echo __('about_banner_desc', 'TechExpert tự hào là hệ thống bán lẻ và tư vấn lắp đặt linh kiện máy tính, Workstation, PC Gaming cao cấp hàng đầu. Chúng tôi kiến tạo các giải pháp phần cứng tối ưu nhất cho nhu cầu của bạn.'); ?>
            </p>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
        <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 text-center hover:shadow-lg transition-all duration-300 group">
            <span class="material-symbols-outlined text-4xl text-primary mb-3 group-hover:scale-110 transition-transform">verified</span>
            <div class="text-3xl font-black text-primary mb-1">100%</div>
            <div class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider"><?php echo __('stat_genuine', 'Chính Hãng'); ?></div>
        </div>
        <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 text-center hover:shadow-lg transition-all duration-300 group">
            <span class="material-symbols-outlined text-4xl text-primary mb-3 group-hover:scale-110 transition-transform">groups</span>
            <div class="text-3xl font-black text-primary mb-1">10,000+</div>
            <div class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider"><?php echo __('stat_customers', 'Khách Hàng Tin Dùng'); ?></div>
        </div>
        <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 text-center hover:shadow-lg transition-all duration-300 group">
            <span class="material-symbols-outlined text-4xl text-primary mb-3 group-hover:scale-110 transition-transform">tools_installation_kit</span>
            <div class="text-3xl font-black text-primary mb-1">5,000+</div>
            <div class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider"><?php echo __('stat_pc_built', 'Bộ PC Đã Build'); ?></div>
        </div>
        <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 text-center hover:shadow-lg transition-all duration-300 group">
            <span class="material-symbols-outlined text-4xl text-primary mb-3 group-hover:scale-110 transition-transform">support_agent</span>
            <div class="text-3xl font-black text-primary mb-1">24/7</div>
            <div class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider"><?php echo __('stat_support', 'Hỗ Trợ Kỹ Thuật'); ?></div>
        </div>
    </div>

    <!-- Core Values Section -->
    <div class="mb-16">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h2 class="text-3xl font-black text-primary tracking-tight mb-4"><?php echo __('core_values_title', 'Giá Trị Cốt Lõi Tại TechExpert'); ?></h2>
            <p class="text-on-surface-variant"><?php echo __('core_values_desc', 'Triết lý vận hành giúp chúng tôi luôn duy trì vị trí dẫn đầu và niềm tin từ phía khách hàng.'); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Value 1 -->
            <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/30 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-white transition-all">
                    <span class="material-symbols-outlined">workspace_premium</span>
                </div>
                <h3 class="text-xl font-bold text-primary mb-3"><?php echo __('about_value_1_title', 'Chất Lượng Tối Thượng'); ?></h3>
                <p class="text-on-surface-variant text-sm leading-relaxed">
                    <?php echo __('about_value_1_desc', 'Mỗi linh kiện, sản phẩm bán ra đều trải qua quy trình kiểm thử kỹ lưỡng. Chúng tôi nói KHÔNG với hàng giả, hàng kém chất lượng.'); ?>
                </p>
            </div>
            <!-- Value 2 -->
            <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/30 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-white transition-all">
                    <span class="material-symbols-outlined">psychology</span>
                </div>
                <h3 class="text-xl font-bold text-primary mb-3"><?php echo __('about_value_2_title', 'Tư Vấn Chuyên Sâu'); ?></h3>
                <p class="text-on-surface-variant text-sm leading-relaxed">
                    <?php echo __('about_value_2_desc', 'Không chỉ bán hàng, chúng tôi đồng hành cùng khách hàng để đưa ra giải pháp cấu hình phần cứng tối ưu nhất dựa trên nhu cầu sử dụng thực tế.'); ?>
                </p>
            </div>
            <!-- Value 3 -->
            <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/30 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-white transition-all">
                    <span class="material-symbols-outlined">volunteer_activism</span>
                </div>
                <h3 class="text-xl font-bold text-primary mb-3"><?php echo __('about_value_3_title', 'Dịch Vụ Tận Tâm'); ?></h3>
                <p class="text-on-surface-variant text-sm leading-relaxed">
                    <?php echo __('about_value_3_desc', 'Hỗ trợ kỹ thuật trọn đời, chính sách đổi trả nhanh chóng và thời gian xử lý bảo hành tối ưu giúp quý khách tuyệt đối an tâm khi mua sắm.'); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Vision & Mission Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-gradient-to-br from-surface-container-lowest to-surface-container-low p-10 rounded-3xl border border-outline-variant/30">
            <h3 class="text-2xl font-black text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">visibility</span>
                <?php echo __('vision_title', 'Tầm Nhìn'); ?>
            </h3>
            <p class="text-on-surface-variant leading-relaxed">
                <?php echo __('about_vision_desc', 'Trở thành biểu tượng uy tín hàng đầu trong lĩnh vực phân phối linh kiện phần cứng và thiết bị công nghệ cao tại Việt Nam. Đồng thời, xây dựng một hệ sinh thái hỗ trợ tối đa cho các nhà phát triển phần mềm, thiết kế đồ họa, người dùng chuyên nghiệp và cộng đồng game thủ.'); ?>
            </p>
        </div>
        
        <div class="bg-gradient-to-br from-surface-container-lowest to-surface-container-low p-10 rounded-3xl border border-outline-variant/30">
            <h3 class="text-2xl font-black text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">rocket_launch</span>
                <?php echo __('mission_title', 'Sứ Mệnh'); ?>
            </h3>
            <p class="text-on-surface-variant leading-relaxed">
                <?php echo __('about_mission_desc', 'Mang những công nghệ và phần cứng máy tính hiện đại nhất thế giới tiếp cận người dùng Việt Nam với chi phí hợp lý. Chúng tôi không ngừng nâng cao trải nghiệm khách hàng bằng sự tận tụy, chân thành và chất lượng sản phẩm chuẩn mực.'); ?>
            </p>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
