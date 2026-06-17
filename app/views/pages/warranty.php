<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="max-w-5xl mx-auto py-16 px-4 sm:px-6 lg:px-8 mt-6">
    <!-- Header Page -->
    <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary/10 text-primary font-bold text-xs uppercase tracking-wider mb-4">
            <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
            <?php echo __('warr_title_tag', 'Cam Kết Dịch Vụ'); ?>
        </span>
        <h1 class="text-4xl md:text-5xl font-black text-primary mb-4 tracking-tight leading-none"><?php echo __('warr_title', 'Bảo Hành & Đổi Trả'); ?></h1>
        <p class="text-on-surface-variant text-base"><?php echo __('warr_subtitle', 'Hỗ trợ chăm sóc chuyên nghiệp, thủ tục đơn giản và tối ưu hóa thời gian chờ đợi của bạn.'); ?></p>
    </div>

    <!-- Key Policy Highlight Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/30 text-center hover:shadow-lg transition-all duration-300 group">
            <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary mb-6 mx-auto group-hover:bg-primary group-hover:text-white transition-all">
                <span class="material-symbols-outlined">workspace_premium</span>
            </div>
            <h3 class="text-lg font-bold text-primary mb-2"><?php echo __('warr_card_1_title', 'Bảo Hành 24 Tháng'); ?></h3>
            <p class="text-on-surface-variant text-xs leading-relaxed">
                <?php echo __('warr_card_1_desc', 'Áp dụng bảo hành chính hãng phần cứng lên đến 24 tháng đối với toàn bộ các dòng máy bộ và Workstation lắp đặt bởi TechExpert.'); ?>
            </p>
        </div>

        <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/30 text-center hover:shadow-lg transition-all duration-300 group">
            <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary mb-6 mx-auto group-hover:bg-primary group-hover:text-white transition-all">
                <span class="material-symbols-outlined">history</span>
            </div>
            <h3 class="text-lg font-bold text-primary mb-2"><?php echo __('warr_card_2_title', 'Đổi Mới Trong 7 Ngày'); ?></h3>
            <p class="text-on-surface-variant text-xs leading-relaxed">
                <?php echo __('warr_card_2_desc', 'Đổi sản phẩm mới 1-đổi-1 hoàn toàn miễn phí trong vòng 7 ngày đầu kể từ lúc mua nếu phát hiện lỗi phần cứng từ nhà sản xuất.'); ?>
            </p>
        </div>

        <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/30 text-center hover:shadow-lg transition-all duration-300 group">
            <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary mb-6 mx-auto group-hover:bg-primary group-hover:text-white transition-all">
                <span class="material-symbols-outlined">support_agent</span>
            </div>
            <h3 class="text-lg font-bold text-primary mb-2"><?php echo __('warr_card_3_title', 'Hỗ Trợ Kỹ Thuật Trọn Đời'); ?></h3>
            <p class="text-on-surface-variant text-xs leading-relaxed">
                <?php echo __('warr_card_3_desc', 'Hệ thống hỗ trợ kỹ thuật cài đặt driver, phần mềm điều khiển phần cứng từ xa hoàn toàn miễn phí cho khách hàng suốt đời máy.'); ?>
            </p>
        </div>
    </div>

    <!-- Detailed Policy Sections -->
    <div class="space-y-12 mb-16">
        <!-- Section 1: Conditions -->
        <div>
            <h2 class="text-xl font-black text-primary mb-6 flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                <span class="material-symbols-outlined text-secondary">task_alt</span>
                <?php echo __('warr_sec_1_title', 'Điều Kiện Được Bảo Hành'); ?>
            </h2>
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 text-sm text-on-surface-variant space-y-4">
                <p><?php echo __('warr_sec_1_desc', 'TechExpert tiếp nhận bảo hành sản phẩm đáp ứng đầy đủ các tiêu chuẩn sau:'); ?></p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><?php echo __('warr_sec_1_li1', 'Sản phẩm còn trong thời hạn bảo hành được ghi nhận dựa trên Hóa đơn mua hàng hoặc Hệ thống serial-number trực tuyến.'); ?></li>
                    <li><?php echo __('warr_sec_1_li2', 'Tem bảo hành của nhà phân phối, hãng sản xuất và tem niêm phong của TechExpert phải còn nguyên vẹn, không có dấu hiệu bị rách, tẩy xóa hoặc đè chèn tem khác.'); ?></li>
                    <li><?php echo __('warr_sec_1_li3', 'Mã Serial Number/IMEI phải còn nguyên và rõ ràng, không bị cạo xước làm mờ hoặc tháo gỡ.'); ?></li>
                    <li><?php echo __('warr_sec_1_li4', 'Sản phẩm bị lỗi hư hỏng xuất phát từ lỗi kỹ thuật của nhà sản xuất cấu thành linh kiện.'); ?></li>
                </ul>
            </div>
        </div>

        <!-- Section 2: Exceptions -->
        <div>
            <h2 class="text-xl font-black text-primary mb-6 flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                <span class="material-symbols-outlined text-error">cancel</span>
                <?php echo __('warr_sec_2_title', 'Trường Hợp Bị Từ Chối Bảo Hành'); ?>
            </h2>
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 text-sm text-on-surface-variant space-y-4">
                <p><?php echo __('warr_sec_2_desc', 'TechExpert rất tiếc phải từ chối bảo hành đối với các trường hợp vi phạm quy định sử dụng:'); ?></p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><?php echo __('warr_sec_2_li1', 'Sản phẩm bị hư hỏng cơ học do rơi vỡ, nứt, móp méo, trầy xước biến dạng nặng trong quá trình người dùng vận hành.'); ?></li>
                    <li><?php echo __('warr_sec_2_li2', 'Sản phẩm bị hư hại do thiên tai, hỏa hoạn, lũ lụt hoặc bị rỉ sét, oxy hóa, ố vàng do ẩm ướt hoặc tiếp xúc với hóa chất.'); ?></li>
                    <li><?php echo __('warr_sec_2_li3', 'Sản phẩm bị chập cháy mạch, nổ chip, phồng tụ điện hoặc cháy IC do người dùng cấp sai nguồn điện áp định mức hoặc ép xung quá tải quá nóng.'); ?></li>
                    <li><?php echo __('warr_sec_2_li4', 'Khách hàng tự ý tháo mở thiết bị, can thiệp sửa đổi cấu trúc linh kiện bởi các cá nhân hoặc cửa hàng không thuộc ủy quyền của TechExpert.'); ?></li>
                </ul>
            </div>
        </div>

        <!-- Section 3: Process -->
        <div>
            <h2 class="text-xl font-black text-primary mb-6 flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                <span class="material-symbols-outlined text-secondary">assignment_turned_in</span>
                <?php echo __('warr_sec_3_title', 'Quy Trình Tiếp Nhận Bảo Hành 3 Bước'); ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 relative">
                    <div class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-secondary text-white font-bold flex items-center justify-center shadow-md">1</div>
                    <h4 class="font-bold text-primary mb-2 mt-2"><?php echo __('warr_step_1_title', 'Đăng Ký Yêu Cầu'); ?></h4>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        <?php echo __('warr_step_1_desc', 'Liên hệ trực tiếp qua số Hotline 1900 1234, gửi email hỗ trợ hoặc chat trực tiếp với trợ lý của shop để báo cáo lỗi sản phẩm.'); ?>
                    </p>
                </div>
                
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 relative">
                    <div class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-secondary text-white font-bold flex items-center justify-center shadow-md">2</div>
                    <h4 class="font-bold text-primary mb-2 mt-2"><?php echo __('warr_step_2_title', 'Gửi Nhận Thiết Bị'); ?></h4>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        <?php echo __('warr_step_2_desc', 'Mang linh kiện trực tiếp qua Trung tâm bảo hành hoặc liên hệ gửi chuyển phát qua bưu cục. (Kỹ thuật viên sẽ hỗ trợ đến nhà tháo linh kiện lỗi đối với PC mua nguyên bộ).'); ?>
                    </p>
                </div>
                
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 relative">
                    <div class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-secondary text-white font-bold flex items-center justify-center shadow-md">3</div>
                    <h4 class="font-bold text-primary mb-2 mt-2"><?php echo __('warr_step_3_title', 'Kiểm Tra & Hoàn Trả'); ?></h4>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        <?php echo __('warr_step_3_desc', 'Phòng kỹ thuật tiến hành đo đạc, kiểm tra lỗi và gửi hãng bảo hành (hoặc đổi mới ngay nếu còn trong 7 ngày đầu). Sản phẩm hoàn trả được giao lại miễn phí tận tay khách hàng.'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
