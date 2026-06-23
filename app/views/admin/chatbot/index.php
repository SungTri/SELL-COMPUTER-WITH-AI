<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area -->
<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <!-- Header -->
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-40">
        <h1 class="text-h2 font-bold text-primary">Quản lý Chatbot</h1>
        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-4 border-l border-outline-variant pl-6">
                <!-- Notifications -->
                <?php require_once VIEWS . '/layout/admin_notification.php'; ?>

                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                    <img alt="Admin" class="w-10 h-10 rounded-full object-cover" src="<?php echo $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=Admin&background=0453cd&color=fff'; ?>"/>
                    <div class="text-right">
                        <p class="text-[14px] font-bold"><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></p>
                        <p class="text-[12px] text-on-surface-variant">Quản trị viên</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="p-10 space-y-8 w-full">
        <div class="flex items-center justify-between">
            <div class="flex gap-4">
                <a href="<?php echo URLROOT; ?>/admin/chatbot" class="px-6 py-2 bg-primary text-white rounded-lg text-[14px] font-bold">Dữ liệu huấn luyện</a>
                <a href="<?php echo URLROOT; ?>/admin/chatbotHistory" class="px-6 py-2 bg-white border border-outline-variant text-on-surface-variant rounded-lg text-[14px] font-bold hover:bg-surface-container transition-all">Lịch sử trò chuyện</a>
            </div>
            
            <a href="<?php echo URLROOT; ?>/admin/addChatbotData" class="px-6 py-2.5 bg-primary text-white rounded-lg text-[14px] font-bold hover:bg-secondary transition-all flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">add</span> Thêm câu hỏi
            </a>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div class="p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
                <?php 
                    if($_GET['success'] == 'added') echo 'Thêm dữ liệu thành công!';
                    if($_GET['success'] == 'updated') echo 'Cập nhật dữ liệu thành công!';
                    if($_GET['success'] == 'deleted') echo 'Xóa dữ liệu thành công!';
                ?>
            </div>
        <?php endif; ?>

        <!-- Search Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-outline-variant shadow-sm">
            <form action="<?php echo URLROOT; ?>/admin/chatbot" method="GET" class="flex-1 max-w-lg flex gap-3">
                <div class="flex-1 flex items-center bg-gray-50 border border-outline-variant rounded-xl px-4 py-2 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10 transition-all">
                    <span class="material-symbols-outlined text-[20px] text-on-surface-variant select-none flex-shrink-0">search</span>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($data['search']); ?>" placeholder="Tìm kiếm câu hỏi, câu trả lời, từ khóa..." class="w-full bg-transparent border-none focus:ring-0 text-sm font-medium text-slate-700 dark:text-zinc-200 placeholder:text-on-surface-variant/60 ml-2 py-0"/>
                </div>
                <button type="submit" class="px-5 py-2 bg-primary text-white text-sm font-bold rounded-xl hover:bg-secondary transition-all">Tìm kiếm</button>
                <?php if(!empty($data['search'])): ?>
                    <a href="<?php echo URLROOT; ?>/admin/chatbot" class="px-4 py-2 border border-outline-variant text-sm font-bold text-on-surface-variant rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center">Xóa lọc</a>
                <?php endif; ?>
            </form>
            <div class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">
                Tổng số: <span class="text-primary font-black"><?php echo $data['pagination']['total_items']; ?></span> bản ghi
            </div>
        </div>

        <section class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-on-surface-variant text-[12px] font-bold uppercase tracking-wider border-b border-outline-variant bg-[#F8F9FB]">
                            <th class="px-8 py-4">ID</th>
                            <th class="px-8 py-4">CÂU HỎI</th>
                            <th class="px-8 py-4">CÂU TRẢ LỜI</th>
                            <th class="px-8 py-4">TỪ KHÓA</th>
                            <th class="px-8 py-4 text-center">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <?php if(empty($data['faqs'])): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-on-surface-variant">Chưa có dữ liệu huấn luyện nào.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($data['faqs'] as $faq): ?>
                            <tr class="hover:bg-[#F8F9FB] transition-colors group">
                                <td class="px-8 py-5 text-[14px] text-on-surface-variant">#<?php echo $faq['id']; ?></td>
                                <td class="px-8 py-5">
                                    <p class="text-[14px] font-bold text-primary"><?php echo $faq['question']; ?></p>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-[14px] text-on-surface-variant line-clamp-2" title="<?php echo $faq['answer']; ?>">
                                        <?php echo $faq['answer']; ?>
                                    </p>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-wrap gap-1">
                                        <?php 
                                            $keywords = explode(',', $faq['keywords']);
                                            foreach($keywords as $kw):
                                                if(empty(trim($kw))) continue;
                                        ?>
                                            <span class="px-2 py-0.5 bg-surface-container rounded text-[11px] text-on-surface-variant border border-outline-variant/30">
                                                <?php echo trim($kw); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?php echo URLROOT; ?>/admin/editChatbotData/<?php echo $faq['id']; ?>" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-secondary hover:text-white hover:border-secondary transition-all" title="Chỉnh sửa">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <button onclick="confirmDelete(<?php echo $faq['id']; ?>)" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-red-500 hover:text-white hover:border-red-500 transition-all" title="Xóa">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <?php if ($data['pagination']['total_pages'] > 1): ?>
            <div class="px-8 py-5 bg-[#F8F9FB] border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-on-surface-variant font-bold">
                    Hiển thị trang <span class="text-primary font-black"><?php echo $data['pagination']['current_page']; ?></span> / <span class="text-primary font-black"><?php echo $data['pagination']['total_pages']; ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <?php 
                    $searchQuery = !empty($data['search']) ? '&search=' . urlencode($data['search']) : '';
                    $currPage = $data['pagination']['current_page'];
                    $totalP = $data['pagination']['total_pages'];
                    ?>
                    
                    <!-- Prev Page Button -->
                    <?php if ($currPage > 1): ?>
                        <a href="<?php echo URLROOT; ?>/admin/chatbot?page=<?php echo $currPage - 1; ?><?php echo $searchQuery; ?>" class="px-3 py-1.5 border border-outline-variant bg-white text-on-surface-variant rounded-lg text-xs font-bold hover:bg-gray-50 hover:text-primary transition-all flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">chevron_left</span> Trang trước
                        </a>
                    <?php else: ?>
                        <span class="px-3 py-1.5 border border-outline-variant bg-gray-100 text-gray-400 rounded-lg text-xs font-bold cursor-not-allowed flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">chevron_left</span> Trang trước
                        </span>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <div class="flex items-center gap-1">
                        <?php 
                        $startPage = max(1, $currPage - 2);
                        $endPage = min($totalP, $currPage + 2);
                        for ($p = $startPage; $p <= $endPage; $p++):
                        ?>
                            <a href="<?php echo URLROOT; ?>/admin/chatbot?page=<?php echo $p; ?><?php echo $searchQuery; ?>" class="w-8 h-8 flex items-center justify-center border <?php echo $p === $currPage ? 'border-primary bg-primary text-white shadow-sm' : 'border-outline-variant bg-white text-on-surface-variant hover:bg-gray-50 hover:text-primary'; ?> rounded-lg text-xs font-bold transition-all">
                                <?php echo $p; ?>
                            </a>
                        <?php endfor; ?>
                    </div>

                    <!-- Next Page Button -->
                    <?php if ($currPage < $totalP): ?>
                        <a href="<?php echo URLROOT; ?>/admin/chatbot?page=<?php echo $currPage + 1; ?><?php echo $searchQuery; ?>" class="px-3 py-1.5 border border-outline-variant bg-white text-on-surface-variant rounded-lg text-xs font-bold hover:bg-gray-50 hover:text-primary transition-all flex items-center gap-1">
                            Trang sau <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </a>
                    <?php else: ?>
                        <span class="px-3 py-1.5 border border-outline-variant bg-gray-100 text-gray-400 rounded-lg text-xs font-bold cursor-not-allowed flex items-center gap-1">
                            Trang sau <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa dữ liệu này?')) {
        window.location.href = '<?php echo URLROOT; ?>/admin/deleteChatbotData/' + id;
    }
}
</script>

</body>
</html>
