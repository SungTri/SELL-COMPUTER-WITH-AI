<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="max-w-5xl mx-auto py-16 px-4 sm:px-6 lg:px-8 mt-6">
    <!-- Header Page -->
    <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary/10 text-primary font-bold text-xs uppercase tracking-wider mb-4">
            <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
            Dịch vụ Giao Hàng
        </span>
        <h1 class="text-4xl md:text-5xl font-black text-primary mb-4 tracking-tight leading-none">Chính Sách Vận Chuyển</h1>
        <p class="text-on-surface-variant text-base">Cam kết giao nhận an toàn, nhanh chóng và bảo hiểm 100% giá trị linh kiện phần cứng công nghệ.</p>
    </div>

    <!-- Shipping Options Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        <!-- Express Shipping Option -->
        <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/30 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 bg-secondary text-white font-bold text-[10px] uppercase px-4 py-1.5 rounded-bl-2xl tracking-widest">
                SIÊU TỐC
            </div>
            
            <div class="w-12 h-12 bg-[#356ee7]/5 rounded-2xl flex items-center justify-center text-secondary mb-6 group-hover:bg-[#356ee7] group-hover:text-white transition-all">
                <span class="material-symbols-outlined">bolt</span>
            </div>
            
            <h3 class="text-2xl font-bold text-primary mb-4">Giao Hàng Hỏa Tốc TechExpress</h3>
            <p class="text-on-surface-variant text-sm leading-relaxed mb-6">
                Dịch vụ chuyển phát hỏa tốc áp dụng riêng cho các đơn hàng linh kiện và máy tính lắp đặt nguyên bộ cần giao gấp.
            </p>
            
            <ul class="space-y-3 text-sm text-on-surface-variant">
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
                    <span><strong>Thời gian giao:</strong> Trong vòng 2 giờ kể từ khi duyệt đơn.</span>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
                    <span><strong>Phạm vi hỗ trợ:</strong> Nội thành Hà Nội & TP. Hồ Chí Minh.</span>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
                    <span><strong>Chi phí:</strong> **Miễn phí** cho các đơn hàng từ 5.000.000đ trở lên. (Dưới 5 triệu đồng, phụ phí 50.000đ/đơn hàng).</span>
                </li>
            </ul>
        </div>

        <!-- Standard Shipping Option -->
        <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/30 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 bg-primary/20 text-primary font-bold text-[10px] uppercase px-4 py-1.5 rounded-bl-2xl tracking-widest">
                TOÀN QUỐC
            </div>
            
            <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-white transition-all">
                <span class="material-symbols-outlined">local_shipping</span>
            </div>
            
            <h3 class="text-2xl font-bold text-primary mb-4">Giao Hàng Tiêu Chuẩn Quốc Gia</h3>
            <p class="text-on-surface-variant text-sm leading-relaxed mb-6">
                Chuyển phát tiêu chuẩn phối hợp chặt chẽ cùng các đơn vị vận chuyển hàng đầu Việt Nam để đưa linh kiện đến mọi miền.
            </p>
            
            <ul class="space-y-3 text-sm text-on-surface-variant">
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
                    <span><strong>Thời gian giao:</strong> 2 - 4 ngày làm việc (không tính CN & ngày lễ).</span>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
                    <span><strong>Phạm vi hỗ trợ:</strong> Toàn bộ 63 tỉnh thành trên cả nước.</span>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
                    <span><strong>Chi phí:</strong> **Miễn phí** cho các đơn hàng từ 2.000.000đ trở lên. (Dưới 2 triệu đồng, đồng giá phí vận chuyển 30.000đ).</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Packaging Quality Section -->
    <div class="bg-gradient-to-br from-surface-container-lowest to-surface-container-low p-8 md:p-12 rounded-3xl border border-outline-variant/30 mb-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
            <div class="lg:col-span-2 space-y-4">
                <h3 class="text-2xl font-black text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">package_2</span>
                    Quy Chuẩn Đóng Gói Chống Va Đập 5 Lớp
                </h3>
                <p class="text-on-surface-variant text-sm leading-relaxed">
                    Linh kiện máy tính là thiết bị nhạy cảm với các rung lắc cơ học. Tại TechExpert, chúng tôi áp dụng quy chuẩn đóng gói chuyên nghiệp khắt khe nhất để bảo vệ tuyệt đối hàng hóa của bạn trong suốt hành trình:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-primary text-xl">layers</span>
                        <div class="text-xs text-on-surface-variant">
                            <strong>Hộp carton 5 lớp:</strong> Chịu lực ép cao từ bên ngoài cực tốt.
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-primary text-xl">bubble_chart</span>
                        <div class="text-xs text-on-surface-variant">
                            <strong>Xốp hơi chống sốc:</strong> Quấn tối thiểu 4 lớp khí nén quanh linh kiện.
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-primary text-xl">lock</span>
                        <div class="text-xs text-on-surface-variant">
                            <strong>Tem niêm phong:</strong> Bảo vệ hộp không bị bóc mở trái phép.
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-primary text-xl">photo_camera</span>
                        <div class="text-xs text-on-surface-variant">
                            <strong>Video đóng gói:</strong> Ghi hình lưu giữ quá trình đóng hàng để đối soát.
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 text-center flex flex-col items-center justify-center">
                <span class="material-symbols-outlined text-5xl text-secondary mb-3">shield_with_heart</span>
                <h4 class="text-base font-bold text-primary mb-1">Bảo Hiểm 100%</h4>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    Mọi đơn hàng vận chuyển đi xa đều được bảo hiểm toàn phần. Nếu xảy ra thất lạc hoặc hư hỏng do lỗi vận chuyển, TechExpert cam kết gửi bù mới hoặc hoàn tiền ngay 100%.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
