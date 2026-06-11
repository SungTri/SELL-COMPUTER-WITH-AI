<div class="relative group" id="admin-notification-area">
    <button class="relative p-2 text-on-surface-variant hover:text-secondary hover:bg-secondary/5 rounded-lg transition-all" title="Thông báo" id="notification-btn">
        <span class="material-symbols-outlined !text-[24px]">notifications</span>
        <span id="notification-badge" class="absolute top-1 right-1 bg-red-500 text-white text-[9px] font-black rounded-full min-w-[16px] h-[16px] flex items-center justify-center border-2 border-white hidden">0</span>
    </button>
    
    <!-- Notification Dropdown -->
    <div class="absolute right-0 top-full pt-3 w-80 opacity-0 invisible translate-y-4 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 z-[100]">
        <div class="bg-white border border-outline-variant rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/5">
            <div class="p-4 border-b border-outline-variant flex items-center justify-between bg-surface-container-low">
                <h4 class="text-sm font-bold text-primary uppercase tracking-wider">Thông báo hệ thống</h4>
                <button onclick="markAllAsRead()" class="text-[10px] font-bold text-secondary hover:underline uppercase tracking-widest">Đánh dấu đã đọc</button>
            </div>
            <div id="notification-list" class="max-h-96 overflow-y-auto divide-y divide-outline-variant/10">
                <div class="p-10 text-center text-on-surface-variant/40 italic text-xs">Đang tải thông báo...</div>
            </div>
            <div class="p-3 bg-surface-container-low/50 text-center border-t border-outline-variant">
                <a href="<?php echo URLROOT; ?>/admin/notifications" class="text-[11px] font-bold text-on-surface-variant hover:text-secondary transition-colors">Xem tất cả thông báo</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Reuse the same logic but adapted for Admin if needed
    async function fetchNotifications() {
        try {
            const response = await fetch('<?php echo URLROOT; ?>/user/getNotifications');
            const data = await response.json();
            
            if (data.status === 'success') {
                updateNotificationUI(data.notifications, data.unreadCount);
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }

    function updateNotificationUI(notifications, unreadCount) {
        const badge = document.getElementById('notification-badge');
        const list = document.getElementById('notification-list');
        
        if (!badge || !list) return;

        if (unreadCount > 0) {
            badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        if (notifications.length === 0) {
            list.innerHTML = '<div class="p-10 text-center text-on-surface-variant/40 italic text-xs">Không có thông báo mới</div>';
            return;
        }

        list.innerHTML = notifications.map(notif => `
            <div class="p-4 hover:bg-surface-container-low transition-colors cursor-pointer ${notif.is_read == 0 ? 'bg-secondary/5 border-l-2 border-secondary' : ''}" onclick="markAsRead(${notif.id})">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full ${notif.is_read == 0 ? 'bg-secondary/10' : 'bg-surface-container'} flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined ${notif.is_read == 0 ? 'text-secondary' : 'text-on-surface-variant'} text-[18px]">
                            ${getIcon(notif.type)}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] ${notif.is_read == 0 ? 'font-bold text-primary' : 'font-medium text-on-surface-variant'} mb-0.5">${notif.title}</p>
                        <p class="text-[11px] text-on-surface-variant line-clamp-2">${notif.content}</p>
                        <p class="text-[9px] text-outline mt-1 italic">${formatTimeAgo(notif.created_at)}</p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function getIcon(type) {
        switch(type) {
            case 'order': return 'package_2';
            case 'promotion': return 'sell';
            case 'user': return 'person';
            default: return 'notifications';
        }
    }

    async function markAsRead(id = null) {
        try {
            const formData = new FormData();
            if (id) formData.append('id', id);

            const response = await fetch('<?php echo URLROOT; ?>/user/markNotificationsRead', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });
            const data = await response.json();
            
            if (data.status === 'success') {
                fetchNotifications();
            }
        } catch (error) {
            console.error('Error marking notifications as read:', error);
        }
    }

    function markAllAsRead() {
        markAsRead();
    }

    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Vừa xong';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} phút trước`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} giờ trước`;
        return date.toLocaleDateString('vi-VN');
    }

    // Initial fetch and set interval
    fetchNotifications();
    setInterval(fetchNotifications, 30000); // 30 seconds for admin
</script>
