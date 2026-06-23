<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area -->
<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <!-- Header -->
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-40">
        <h1 class="text-h2 font-bold text-primary">Quản lý Góp ý & Khiếu nại</h1>
        
        <div class="flex items-center gap-8">
            <form method="GET" action="<?php echo URLROOT; ?>/admin/feedbacks" class="w-96 flex items-center bg-[#F3F4F6] dark:bg-zinc-950 border border-transparent dark:border-zinc-800 rounded-xl px-4 py-2 focus-within:ring-2 focus-within:ring-secondary/20 transition-all">
                <span class="material-symbols-outlined text-on-surface-variant text-[20px] select-none flex-shrink-0">search</span>
                <input name="search" value="<?php echo $data['search']; ?>" class="w-full bg-transparent border-none focus:ring-0 text-[13px] font-medium text-slate-700 dark:text-zinc-200 placeholder:text-on-surface-variant/60 ml-2 py-0" placeholder="Tìm tên, email, tiêu đề góp ý..." type="text"/>
            </form>
            <div class="flex items-center gap-5 border-l border-outline-variant pl-8 ml-2">
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
        </div>
    </header>

    <!-- Content -->
    <div class="p-10 space-y-8 w-full">
        <?php if(isset($_GET['msg'])): ?>
            <div class="p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 animate-in fade-in slide-in-from-top-2 duration-300">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="p-4 bg-red-100 text-red-700 rounded-xl border border-red-200 animate-in fade-in slide-in-from-top-2 duration-300">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Feedbacks Table -->
        <section class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-on-surface-variant text-[12px] font-bold uppercase tracking-wider border-b border-outline-variant bg-[#F8F9FB]">
                            <th class="px-8 py-4">KHÁCH HÀNG</th>
                            <th class="px-8 py-4">TIÊU ĐỀ</th>
                            <th class="px-8 py-4">NỘI DUNG</th>
                            <th class="px-8 py-4">NGÀY GỬI</th>
                            <th class="px-8 py-4 text-center">TRẠNG THÁI</th>
                            <th class="px-8 py-4 text-center">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <?php if(empty($data['feedbacks'])): ?>
                        <tr>
                            <td colspan="6" class="px-8 py-10 text-center text-on-surface-variant">Không tìm thấy góp ý nào.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($data['feedbacks'] as $feedback): ?>
                            <tr class="hover:bg-[#F8F9FB] transition-colors group">
                                <td class="px-8 py-5">
                                    <p class="text-[14px] font-bold text-primary"><?php echo htmlspecialchars($feedback['customer_name']); ?></p>
                                    <p class="text-[12px] text-on-surface-variant"><?php echo htmlspecialchars($feedback['customer_email']); ?></p>
                                    <p class="text-[10px] text-outline">Customer ID: #<?php echo $feedback['customer_id']; ?></p>
                                </td>
                                <td class="px-8 py-5 font-semibold text-primary max-w-[200px] truncate" title="<?php echo htmlspecialchars($feedback['title']); ?>">
                                    <?php echo htmlspecialchars($feedback['title']); ?>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-[14px] text-on-surface leading-relaxed max-w-[350px] whitespace-pre-line">
                                        <?php echo htmlspecialchars($feedback['content']); ?>
                                    </p>
                                </td>
                                <td class="px-8 py-5 text-on-surface-variant text-sm">
                                    <?php echo date('d/m/Y H:i', strtotime($feedback['submitted_at'])); ?>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <?php 
                                        $statusClass = "bg-amber-100 text-amber-700 border-amber-200";
                                        $statusText = "Chưa xử lý";
                                        if ($feedback['status'] == 1) {
                                            $statusClass = "bg-blue-100 text-blue-700 border-blue-200";
                                            $statusText = "Đang xử lý";
                                        } elseif ($feedback['status'] == 2) {
                                            $statusClass = "bg-green-100 text-green-700 border-green-200";
                                            $statusText = "Đã giải quyết";
                                        }
                                    ?>
                                    <span class="px-3 py-1 border rounded-full text-[12px] font-bold <?php echo $statusClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick='openStatusModal(<?php echo json_encode($feedback); ?>)' class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-secondary hover:bg-secondary hover:text-white transition-all" title="Cập nhật trạng thái">
                                            <span class="material-symbols-outlined text-[18px]">edit_square</span>
                                        </button>
                                        <button onclick="confirmDelete(<?php echo $feedback['id']; ?>)" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-red-500 hover:text-white hover:border-red-500 transition-all" title="Xóa">
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

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden animate-in zoom-in duration-300">
        <div class="p-6 border-b border-outline-variant flex items-center justify-between bg-[#F8F9FB]">
            <h3 class="text-h3 font-bold text-primary">Cập nhật trạng thái Góp ý</h3>
            <button onclick="closeStatusModal()" class="p-2 hover:bg-surface-container rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="statusForm" method="POST" class="p-6 space-y-6">
            <?php echo csrf_field(); ?>
            <div class="space-y-2">
                <label class="text-[14px] font-bold text-primary">Trạng thái góp ý</label>
                <select name="status" id="feedbackStatusSelect" class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:border-secondary focus:ring-4 focus:ring-secondary/10 transition-all outline-none text-body-md bg-white">
                    <option value="0">Chưa xử lý</option>
                    <option value="1">Đang xử lý</option>
                    <option value="2">Đã giải quyết</option>
                </select>
            </div>
            
            <div class="flex gap-3 justify-end pt-4">
                <button type="button" onclick="closeStatusModal()" class="px-6 py-2.5 rounded-xl text-on-surface-variant font-bold hover:bg-surface-container transition-all">Hủy</button>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-secondary text-white font-bold hover:bg-blue-700 transition-all shadow-lg shadow-secondary/20">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa góp ý này? Thao tác này không thể hoàn tác.')) {
        window.location.href = '<?php echo URLROOT; ?>/admin/deleteFeedback/' + id;
    }
}

function openStatusModal(feedback) {
    const modal = document.getElementById('statusModal');
    const form = document.getElementById('statusForm');
    const select = document.getElementById('feedbackStatusSelect');
    
    select.value = feedback.status;
    form.action = '<?php echo URLROOT; ?>/admin/updateFeedbackStatus/' + feedback.id;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.body.style.overflow = '';
}

window.onclick = function(event) {
    const modal = document.getElementById('statusModal');
    if (event.target == modal) {
        closeStatusModal();
    }
}
</script>

</body>
</html>
