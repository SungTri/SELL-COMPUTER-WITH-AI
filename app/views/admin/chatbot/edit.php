<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area -->
<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <!-- Header -->
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-40">
        <div class="flex items-center gap-4">
            <a href="<?php echo URLROOT; ?>/admin/chatbot" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-h2 font-bold text-primary">Sửa dữ liệu Chatbot</h1>
        </div>
        
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

    <!-- Content -->
    <div class="p-10 w-full max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
            <form action="<?php echo URLROOT; ?>/admin/updateChatbotData/<?php echo $data['faq']['id']; ?>" method="POST" class="p-8 space-y-6">
                <?php echo csrf_field(); ?>
                <div class="space-y-2">
                    <label class="text-[14px] font-bold text-primary">Câu hỏi khách hàng thường gặp</label>
                    <input name="question" required value="<?php echo htmlspecialchars($data['faq']['question']); ?>" class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:border-secondary focus:ring-4 focus:ring-secondary/10 transition-all outline-none text-body-md" placeholder="Ví dụ: Shop có ship COD không?" type="text"/>
                </div>

                <div class="space-y-2">
                    <label class="text-[14px] font-bold text-primary">Câu trả lời của Bot</label>
                    <textarea name="answer" required rows="5" class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:border-secondary focus:ring-4 focus:ring-secondary/10 transition-all outline-none text-body-md" placeholder="Nhập câu trả lời mà Bot sẽ phản hồi..."><?php echo htmlspecialchars($data['faq']['answer']); ?></textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-[14px] font-bold text-primary">Từ khóa (Ngăn cách bởi dấu phẩy)</label>
                    <input name="keywords" value="<?php echo htmlspecialchars($data['faq']['keywords']); ?>" class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:border-secondary focus:ring-4 focus:ring-secondary/10 transition-all outline-none text-body-md" placeholder="Ví dụ: ship, cod, thanh toán, nhận hàng" type="text"/>
                    <p class="text-[12px] text-on-surface-variant italic">Giúp Bot nhận diện câu hỏi chính xác hơn dựa trên các từ khóa này.</p>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" class="flex-1 bg-secondary text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-secondary/20">Cập nhật dữ liệu</button>
                    <a href="<?php echo URLROOT; ?>/admin/chatbot" class="flex-1 bg-white border border-outline-variant text-on-surface-variant py-3 rounded-xl font-bold text-center hover:bg-surface-container transition-all">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
</main>

</body>
</html>
