<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area -->
<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <!-- Header -->
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-10">
        <h1 class="text-h2 font-bold text-primary">Thông báo hệ thống</h1>
        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-4 border-l border-outline-variant pl-6">
                <!-- Notifications Bell -->
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
    <div class="p-10 space-y-8 max-w-5xl">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-primary">Tất cả thông báo</h3>
            <button onclick="markAllAsRead()" class="px-4 py-2 bg-secondary/10 text-secondary rounded-lg font-bold text-sm hover:bg-secondary/20 transition-all">Đánh dấu tất cả đã đọc</button>
        </div>

        <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="divide-y divide-outline-variant">
                <?php if(empty($data['notifications'])): ?>
                    <div class="p-20 text-center text-on-surface-variant italic">Không có thông báo nào.</div>
                <?php else: ?>
                    <?php foreach($data['notifications'] as $notif): ?>
                        <div class="p-6 hover:bg-[#F8F9FB] transition-all <?php echo $notif['is_read'] == 0 ? 'bg-secondary/5 border-l-4 border-secondary' : ''; ?>">
                            <div class="flex gap-6">
                                <div class="w-12 h-12 rounded-2xl <?php echo $notif['is_read'] == 0 ? 'bg-secondary/10 text-secondary' : 'bg-surface-container text-on-surface-variant'; ?> flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined !text-[28px]">
                                        <?php 
                                        switch($notif['type']) {
                                            case 'order': echo 'package_2'; break;
                                            case 'promotion': echo 'sell'; break;
                                            case 'user': echo 'person'; break;
                                            default: echo 'notifications'; break;
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="text-lg <?php echo $notif['is_read'] == 0 ? 'font-black text-primary' : 'font-bold text-on-surface'; ?>">
                                                <?php echo $notif['title']; ?>
                                            </h4>
                                            <p class="text-[12px] text-outline italic">
                                                <?php echo date('d/m/Y H:i', strtotime($notif['created_at'])); ?>
                                            </p>
                                        </div>
                                        <?php if($notif['is_read'] == 0): ?>
                                            <span class="px-3 py-1 bg-secondary text-white text-[10px] font-black rounded-full uppercase tracking-widest">Mới</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-on-surface-variant leading-relaxed">
                                        <?php echo $notif['content']; ?>
                                    </p>
                                    
                                    <div class="mt-4 flex gap-3">
                                        <?php if($notif['type'] == 'order'): ?>
                                            <a href="<?php echo URLROOT; ?>/admin/orders" class="text-sm font-bold text-secondary hover:underline flex items-center gap-1">
                                                Xem đơn hàng <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                            </a>
                                        <?php endif; ?>
                                        <?php if($notif['is_read'] == 0): ?>
                                            <button onclick="markAsRead(<?php echo $notif['id']; ?>)" class="text-xs font-bold text-on-surface-variant hover:text-secondary">Đánh dấu đã đọc</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
async function markAsRead(id) {
    const formData = new FormData();
    formData.append('id', id);
    try {
        const response = await fetch('<?php echo URLROOT; ?>/user/markNotificationsRead', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        const data = await response.json();
        if (data.status === 'success') {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function markAllAsRead() {
    try {
        const response = await fetch('<?php echo URLROOT; ?>/user/markNotificationsRead', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        if (data.status === 'success') {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>

</body>
</html>
