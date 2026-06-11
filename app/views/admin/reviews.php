<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area -->
<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <!-- Header -->
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-10">
        <h1 class="text-h2 font-bold text-primary">Quản lý Đánh giá</h1>
        
        <div class="flex items-center gap-6">
            <form method="GET" action="<?php echo URLROOT; ?>/admin/reviews" class="relative w-96">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input name="search" value="<?php echo $data['search']; ?>" class="w-full pl-12 pr-4 py-2.5 bg-[#F3F4F6] rounded-full border-none text-body-md focus:ring-2 focus:ring-secondary/20 transition-all" placeholder="Tìm tên khách hàng, sản phẩm..." type="text"/>
            </form>
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
        <?php if(isset($_GET['msg'])): ?>
            <div class="p-4 bg-green-100 text-green-700 rounded-lg border border-green-200 animate-in fade-in slide-in-from-top-2 duration-300">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="p-4 bg-red-100 text-red-700 rounded-lg border border-red-200 animate-in fade-in slide-in-from-top-2 duration-300">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Reviews Table -->
        <section class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-on-surface-variant text-[12px] font-bold uppercase tracking-wider border-b border-outline-variant bg-[#F8F9FB]">
                            <th class="px-8 py-4">KHÁCH HÀNG</th>
                            <th class="px-8 py-4">SẢN PHẨM</th>
                            <th class="px-8 py-4 text-center">ĐÁNH GIÁ</th>
                            <th class="px-8 py-4">NỘI DUNG</th>
                            <th class="px-8 py-4">NGÀY ĐĂNG</th>
                            <th class="px-8 py-4 text-center">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <?php if(empty($data['reviews'])): ?>
                        <tr>
                            <td colspan="6" class="px-8 py-10 text-center text-on-surface-variant">Không tìm thấy đánh giá nào.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($data['reviews'] as $review): ?>
                            <tr class="hover:bg-[#F8F9FB] transition-colors group">
                                <td class="px-8 py-5">
                                    <p class="text-[14px] font-bold text-primary"><?php echo $review['customer_name']; ?></p>
                                    <p class="text-[12px] text-on-surface-variant">ID: #<?php echo $review['customer_id']; ?></p>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-white border border-outline-variant p-1 flex-shrink-0">
                                            <img src="<?php echo get_product_image($review['product_image']); ?>" alt="" class="w-full h-full object-contain" onerror="this.src='https://placehold.co/150x150?text=Product'" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[14px] font-medium text-primary truncate max-w-[200px]" title="<?php echo $review['product_name']; ?>">
                                                <?php echo $review['product_name']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center gap-0.5">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <span class="material-symbols-outlined text-[16px] <?php echo $i <= $review['rating'] ? 'text-amber-400 fill-1' : 'text-outline-variant'; ?>">
                                                star
                                            </span>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-[12px] text-on-surface-variant mt-1"><?php echo $review['rating']; ?>/5</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="space-y-3">
                                        <p class="text-[14px] text-on-surface leading-relaxed max-w-[400px]">
                                            <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                                        </p>
                                        <?php if(!empty($review['admin_reply'])): ?>
                                        <div class="bg-surface-container rounded-lg p-3 border-l-4 border-secondary">
                                            <p class="text-[11px] font-bold text-secondary uppercase mb-1">Cửa hàng phản hồi:</p>
                                            <p class="text-[13px] text-on-surface italic"><?php echo htmlspecialchars($review['admin_reply']); ?></p>
                                            <p class="text-[10px] text-on-surface-variant mt-1 text-right italic"><?php echo date('d/m/Y H:i', strtotime($review['replied_at'])); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-[14px] text-on-surface-variant">
                                        <?php echo date('d/m/Y', strtotime($review['created_at'])); ?>
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick='openReplyModal(<?php echo json_encode($review); ?>)' class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-secondary hover:bg-secondary hover:text-white transition-all" title="Phản hồi">
                                            <span class="material-symbols-outlined text-[18px]">reply</span>
                                        </button>
                                        <button onclick="confirmDelete(<?php echo $review['id']; ?>)" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-red-500 hover:text-white hover:border-red-500 transition-all" title="Xóa">
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
        </section>
    </div>
</main>

<!-- Reply Modal -->
<div id="replyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden animate-in zoom-in duration-300">
        <div class="p-6 border-b border-outline-variant flex items-center justify-between bg-[#F8F9FB]">
            <h3 class="text-h3 font-bold text-primary">Phản hồi đánh giá</h3>
            <button onclick="closeReplyModal()" class="p-2 hover:bg-surface-container rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="replyForm" method="POST" class="p-6 space-y-6">
            <?php echo csrf_field(); ?>
            <div class="bg-surface-container p-4 rounded-xl border border-outline-variant/30 italic text-sm text-on-surface-variant" id="customerComment">
                <!-- Comment text will be here -->
            </div>
            
            <div class="space-y-2">
                <label class="text-[14px] font-bold text-primary">Nội dung phản hồi</label>
                <textarea name="reply" id="replyText" rows="5" class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:border-secondary focus:ring-4 focus:ring-secondary/10 transition-all outline-none text-body-md" placeholder="Nhập nội dung phản hồi cho khách hàng..."></textarea>
            </div>
            
            <div class="flex gap-3 justify-end pt-4">
                <button type="button" onclick="closeReplyModal()" class="px-6 py-2.5 rounded-xl text-on-surface-variant font-bold hover:bg-surface-container transition-all">Hủy</button>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-secondary text-white font-bold hover:bg-blue-700 transition-all shadow-lg shadow-secondary/20">Gửi phản hồi</button>
            </div>
        </form>
    </div>
</div>

<style>
.fill-1 { font-variation-settings: 'FILL' 1; }
</style>

<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa đánh giá này? Thao tác này không thể hoàn tác.')) {
        window.location.href = '<?php echo URLROOT; ?>/admin/deleteReview/' + id;
    }
}

function openReplyModal(review) {
    const modal = document.getElementById('replyModal');
    const form = document.getElementById('replyForm');
    const commentBox = document.getElementById('customerComment');
    const replyText = document.getElementById('replyText');
    
    commentBox.innerHTML = `<strong>${review.customer_name}:</strong> "${review.comment}"`;
    replyText.value = review.admin_reply || '';
    form.action = '<?php echo URLROOT; ?>/admin/replyReview/' + review.id;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReplyModal() {
    document.getElementById('replyModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('replyModal');
    if (event.target == modal) {
        closeReplyModal();
    }
}
</script>

</body>
</html>
