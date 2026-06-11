<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="max-w-4xl mx-auto py-16 px-4 sm:px-6 lg:px-8 mt-6">
    <!-- Header Page -->
    <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary/10 text-primary font-bold text-xs uppercase tracking-wider mb-4">
            <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
            Hỗ Trợ Khách Hàng
        </span>
        <h1 class="text-4xl md:text-5xl font-black text-primary mb-4 tracking-tight leading-none">Trung Tâm Trợ Giúp</h1>
        <p class="text-on-surface-variant text-base">Tìm kiếm câu trả lời nhanh cho các câu hỏi thường gặp về dịch vụ và mua sắm tại TechExpert.</p>
    </div>

    <!-- FAQ Categories & Items -->
    <div class="space-y-12">
        <!-- Category 1: Mua Hàng & Thanh Toán -->
        <div>
            <h2 class="text-xl font-black text-primary mb-6 flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                <span class="material-symbols-outlined text-secondary">payments</span>
                Mua Hàng & Thanh Toán
            </h2>
            
            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 overflow-hidden">
                    <button onclick="toggleFaq(1)" class="w-full px-6 py-5 flex items-center justify-between text-left font-bold text-primary hover:bg-surface-container-low transition-colors duration-200 focus:outline-none">
                        <span>Làm cách nào để tôi đặt hàng trực tuyến?</span>
                        <span id="faq-icon-1" class="material-symbols-outlined text-on-surface-variant/70 transition-transform duration-300">keyboard_arrow_down</span>
                    </button>
                    <div id="faq-content-1" class="hidden px-6 pb-6 text-on-surface-variant text-sm leading-relaxed border-t border-outline-variant/10 pt-4 bg-[#F8F9FB]/50">
                        Quy trình mua hàng trực tuyến gồm 4 bước cực kỳ đơn giản:<br>
                        1. **Tìm kiếm sản phẩm:** Duyệt qua các danh mục hoặc sử dụng thanh tìm kiếm để tìm sản phẩm mong muốn.<br>
                        2. **Chọn cấu hình (nếu có):** Đối với các sản phẩm có phiên bản (RAM, SSD, màu sắc), bạn vui lòng chọn tùy chọn cấu hình yêu thích của mình.<br>
                        3. **Thêm vào giỏ hàng:** Nhấn "Thêm vào giỏ hàng" hoặc "Mua ngay" để chuyển đến trang giỏ hàng.<br>
                        4. **Thanh toán:** Nhập thông tin giao hàng, chọn phương thức thanh toán (COD hoặc chuyển khoản qua mã VietQR) và hoàn tất đặt hàng.
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 overflow-hidden">
                    <button onclick="toggleFaq(2)" class="w-full px-6 py-5 flex items-center justify-between text-left font-bold text-primary hover:bg-surface-container-low transition-colors duration-200 focus:outline-none">
                        <span>TechExpert hỗ trợ những phương thức thanh toán nào?</span>
                        <span id="faq-icon-2" class="material-symbols-outlined text-on-surface-variant/70 transition-transform duration-300">keyboard_arrow_down</span>
                    </button>
                    <div id="faq-content-2" class="hidden px-6 pb-6 text-on-surface-variant text-sm leading-relaxed border-t border-outline-variant/10 pt-4 bg-[#F8F9FB]/50">
                        Chúng tôi hỗ trợ các phương thức thanh toán linh hoạt sau:<br>
                        - **Thanh toán tiền mặt khi nhận hàng (COD):** Bạn kiểm tra sản phẩm trước khi thanh toán cho nhân viên giao hàng.<br>
                        - **Chuyển khoản Ngân hàng qua VietQR (Tự động):** Hệ thống sẽ tạo mã QR động chứa sẵn số tiền và nội dung chuyển khoản. Khi bạn thanh toán thành công, đơn hàng sẽ lập tức chuyển sang trạng thái "Đã thanh toán" một cách tự động.
                    </div>
                </div>
            </div>
        </div>

        <!-- Category 2: Tư Vấn Lắp Đặt & Chatbot AI -->
        <div>
            <h2 class="text-xl font-black text-primary mb-6 flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                <span class="material-symbols-outlined text-secondary">smart_toy</span>
                Tư Vấn Lắp Đặt & Chatbot AI
            </h2>
            
            <div class="space-y-4">
                <!-- FAQ Item 3 -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 overflow-hidden">
                    <button onclick="toggleFaq(3)" class="w-full px-6 py-5 flex items-center justify-between text-left font-bold text-primary hover:bg-surface-container-low transition-colors duration-200 focus:outline-none">
                        <span>Làm sao để tôi sử dụng AI hỗ trợ tự dựng cấu hình PC?</span>
                        <span id="faq-icon-3" class="material-symbols-outlined text-on-surface-variant/70 transition-transform duration-300">keyboard_arrow_down</span>
                    </button>
                    <div id="faq-content-3" class="hidden px-6 pb-6 text-on-surface-variant text-sm leading-relaxed border-t border-outline-variant/10 pt-4 bg-[#F8F9FB]/50">
                        TechExpert cung cấp trợ lý AI chuyên biệt để giúp bạn build PC. Hãy nhấn vào nút bong bóng Chat ở góc phải màn hình, chọn tab **"AI TƯ VẤN"**. Bạn chỉ cần nhập yêu cầu tự do như: *"Build cho tôi một bộ PC khoảng 20 triệu chơi mượt game FIFA và đồ họa nhẹ"*. AI sẽ ngay lập tức tính toán, tư vấn sự tương thích của các linh kiện và hiển thị trực quan các cấu hình được đề xuất kèm nút bấm "Thêm tất cả vào giỏ hàng" để bạn tiến hành thanh toán nhanh gọn.
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 overflow-hidden">
                    <button onclick="toggleFaq(4)" class="w-full px-6 py-5 flex items-center justify-between text-left font-bold text-primary hover:bg-surface-container-low transition-colors duration-200 focus:outline-none">
                        <span>Các linh kiện trong bộ PC có được đảm bảo tương thích không?</span>
                        <span id="faq-icon-4" class="material-symbols-outlined text-on-surface-variant/70 transition-transform duration-300">keyboard_arrow_down</span>
                    </button>
                    <div id="faq-content-4" class="hidden px-6 pb-6 text-on-surface-variant text-sm leading-relaxed border-t border-outline-variant/10 pt-4 bg-[#F8F9FB]/50">
                        Có. Thuật toán gợi ý của hệ thống và trợ lý AI tại TechExpert đã được huấn luyện kỹ lưỡng theo các chuẩn phần cứng tương thích (loại socket CPU phải khớp Mainboard, chuẩn RAM phải cùng thế hệ DDR, công suất Nguồn phải đủ cho card đồ họa VGA...). Trước khi giao PC nguyên bộ đến tay bạn, đội ngũ kỹ thuật viên của chúng tôi sẽ lắp ráp thử nghiệm và kiểm tra hiệu năng tổng thể của máy một lần nữa.
                    </div>
                </div>
            </div>
        </div>

        <!-- Category 3: Tài Khoản & Khuyến Mãi -->
        <div>
            <h2 class="text-xl font-black text-primary mb-6 flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                <span class="material-symbols-outlined text-secondary">account_circle</span>
                Tài Khoản & Khuyến Mãi
            </h2>
            
            <div class="space-y-4">
                <!-- FAQ Item 5 -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 overflow-hidden">
                    <button onclick="toggleFaq(5)" class="w-full px-6 py-5 flex items-center justify-between text-left font-bold text-primary hover:bg-surface-container-low transition-colors duration-200 focus:outline-none">
                        <span>Tôi có được giảm giá khi mua hàng lần đầu không?</span>
                        <span id="faq-icon-5" class="material-symbols-outlined text-on-surface-variant/70 transition-transform duration-300">keyboard_arrow_down</span>
                    </button>
                    <div id="faq-content-5" class="hidden px-6 pb-6 text-on-surface-variant text-sm leading-relaxed border-t border-outline-variant/10 pt-4 bg-[#F8F9FB]/50">
                        Có. Mọi khách hàng mới khi đăng ký tài khoản tại hệ thống đều có thể thu thập các mã giảm giá (voucher) độc quyền ngay trên trang chủ. Bạn chỉ cần bấm "Lưu vào ví", các mã này sẽ xuất hiện tại phần "Khuyến mãi của tôi" trong hồ sơ cá nhân và tự động gợi ý áp dụng khi bạn thực hiện thanh toán.
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 overflow-hidden">
                    <button onclick="toggleFaq(6)" class="w-full px-6 py-5 flex items-center justify-between text-left font-bold text-primary hover:bg-surface-container-low transition-colors duration-200 focus:outline-none">
                        <span>Tại sao tôi không nhận được mã xác thực kích hoạt tài khoản?</span>
                        <span id="faq-icon-6" class="material-symbols-outlined text-on-surface-variant/70 transition-transform duration-300">keyboard_arrow_down</span>
                    </button>
                    <div id="faq-content-6" class="hidden px-6 pb-6 text-on-surface-variant text-sm leading-relaxed border-t border-outline-variant/10 pt-4 bg-[#F8F9FB]/50">
                        Vui lòng kiểm tra kỹ cả mục **Thư rác (Spam)** hoặc **Quảng cáo (Promotions)** trong hộp thư điện tử của bạn. Nếu bạn đang chạy hệ thống ở môi trường kiểm thử nhà phát triển (Developer Mode), do không gửi email thực tế, liên kết kích hoạt tài khoản đã được hệ thống tự động lưu vào tệp tin có tên là `storage/email_log.txt` trong thư mục dự án của bạn để bạn sao chép trực tiếp vào trình duyệt nhằm hoàn tất việc kiểm thử.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFaq(id) {
    const content = document.getElementById('faq-content-' + id);
    const icon = document.getElementById('faq-icon-' + id);
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}
</script>
<?php require APPROOT . '/views/layout/footer.php'; ?>
