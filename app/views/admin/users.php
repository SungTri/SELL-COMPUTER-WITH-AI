<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB]">
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 sticky top-0 z-10">
        <h1 class="text-h2 font-bold text-primary">Quản lý Tài khoản</h1>
        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-4">
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

    <div class="p-10 space-y-8 w-full">
        <!-- Filters & Search -->
        <section class="bg-white p-6 rounded-2xl border border-outline-variant shadow-sm">
            <form id="searchForm" onsubmit="return false;" class="flex flex-wrap items-center gap-6">
                <div class="flex-1 min-w-[300px]">
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
                        <input type="text" id="searchInput" name="search" value="<?php echo $data['filters']['search']; ?>" placeholder="Tìm theo tên, email hoặc số điện thoại..." class="w-full pl-12 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl outline-none focus:ring-2 focus:ring-secondary/20 transition-all text-[15px] placeholder:text-on-surface-variant/60 shadow-sm group-hover:border-primary/30">
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <select id="roleSelect" name="role" class="px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl outline-none focus:ring-2 focus:ring-secondary/20 text-[14px] appearance-none cursor-pointer pr-10 relative min-w-[150px]">
                        <option value="">Tất cả vai trò</option>
                        <?php foreach($data['roles'] as $role): ?>
                            <option value="<?php echo $role['id']; ?>" <?php echo $data['filters']['role'] == $role['id'] ? 'selected' : ''; ?>>
                                <?php echo $role['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button type="button" onclick="performSearch()" class="px-6 py-3 bg-primary text-white rounded-xl text-[14px] font-bold hover:bg-secondary transition-all shadow-sm">Tìm kiếm</button>
                    
                    <a href="<?php echo URLROOT; ?>/admin/users" class="w-12 h-12 flex items-center justify-center bg-surface-container text-on-surface-variant hover:text-primary hover:bg-primary/10 rounded-xl transition-all" title="Làm mới">
                        <span class="material-symbols-outlined">restart_alt</span>
                    </a>
                </div>
            </form>
        </section>

        <!-- Alerts -->
        <?php if(isset($_GET['msg'])): ?>
            <div class="p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 text-[14px] flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <?php echo $_GET['msg']; ?>
            </div>
        <?php endif; ?>

        <!-- Users Table -->
        <section id="usersTableSection" class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden relative">
            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-20 items-center justify-center hidden">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-[14px] font-bold text-primary">Đang tìm kiếm...</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <?php include VIEWS . '/admin/users_table.php'; ?>
            </div>
        </section>
    </div>
</main>

<script>
const searchInput = document.getElementById('searchInput');
const roleSelect = document.getElementById('roleSelect');
const tableContainer = document.querySelector('#usersTableSection .overflow-x-auto');
const loadingIndicator = document.getElementById('loadingIndicator');
let debounceTimer;

function performSearch() {
    const searchValue = searchInput.value;
    const roleValue = roleSelect.value;
    
    loadingIndicator.classList.remove('hidden');
    loadingIndicator.classList.add('flex');

    // Sử dụng URL hiện tại để tránh lỗi URLROOT
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('ajax', '1');
    currentUrl.searchParams.set('search', searchValue);
    currentUrl.searchParams.set('role', roleValue);

    fetch(currentUrl.toString())
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            tableContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Search error:', error);
            // Nếu fetch thất bại, thử dùng URL dự phòng
            const fallbackUrl = `<?php echo URLROOT; ?>/admin/users?ajax=1&search=${encodeURIComponent(searchValue)}&role=${roleValue}`;
            return fetch(fallbackUrl).then(r => r.text()).then(h => tableContainer.innerHTML = h);
        })
        .finally(() => {
            loadingIndicator.classList.add('hidden');
            loadingIndicator.classList.remove('flex');
        });
}

searchInput.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(performSearch, 500);
});

roleSelect.addEventListener('change', performSearch);

// Xử lý phím Enter
searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        performSearch();
    }
});

// Toast notification helper
function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    const icon = document.getElementById('toast-icon');
    const text = document.getElementById('toast-msg');
    
    if (!toast || !icon || !text) return;
    
    text.innerText = msg;
    toast.classList.remove('bg-green-600', 'bg-red-600');
    
    if (type === 'success') {
        toast.classList.add('bg-green-600');
        icon.innerText = 'check_circle';
    } else {
        toast.classList.add('bg-red-600');
        icon.innerText = 'error';
    }
    
    toast.classList.remove('translate-y-20', 'opacity-0');
    setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 3000);
}

// AJAX function to change user role
function changeUserRole(userId, roleId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    loadingIndicator.classList.remove('hidden');
    loadingIndicator.classList.add('flex');
    
    const params = new URLSearchParams();
    params.append('role_id', roleId);
    params.append('csrf_token', csrfToken);
    
    fetch(`<?php echo URLROOT; ?>/admin/updateUserRole/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw new Error(err.message || 'Lỗi cập nhật vai trò'); });
        }
        return response.json();
    })
    .then(res => {
        showToast(res.message || 'Cập nhật vai trò thành công', 'success');
        performSearch();
    })
    .catch(error => {
        console.error('Role update error:', error);
        showToast(error.message || 'Không thể cập nhật vai trò', 'error');
        performSearch();
    });
}

// Toggle role dropdown menu
function toggleRoleDropdown(event, userId) {
    event.stopPropagation();
    
    // Close other dropdowns
    document.querySelectorAll('.role-dropdown-menu').forEach(menu => {
        if (menu.id !== `role-menu-${userId}`) {
            menu.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                if (menu.classList.contains('opacity-0')) {
                    menu.classList.add('hidden');
                }
            }, 150);
        }
    });

    const menu = document.getElementById(`role-menu-${userId}`);
    if (!menu) return;

    const isHidden = menu.classList.contains('hidden');
    if (isHidden) {
        menu.classList.remove('hidden');
        setTimeout(() => {
            menu.classList.remove('opacity-0', 'scale-95');
            menu.classList.add('opacity-100', 'scale-100');
        }, 20);
    } else {
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            if (menu.classList.contains('opacity-0')) {
                menu.classList.add('hidden');
            }
        }, 150);
    }
}

// Select role and trigger API
function selectUserRole(userId, roleId) {
    const menu = document.getElementById(`role-menu-${userId}`);
    if (menu) {
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            menu.classList.add('hidden');
        }, 150);
    }
    changeUserRole(userId, roleId);
}

// Close role dropdowns when clicking outside
document.addEventListener('click', () => {
    document.querySelectorAll('.role-dropdown-menu').forEach(menu => {
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            if (menu.classList.contains('opacity-0')) {
                menu.classList.add('hidden');
            }
        }, 150);
    });
});
</script>

<!-- Notification Toast -->
<div id="toast" class="fixed bottom-6 right-6 px-6 py-4 rounded-2xl text-white shadow-xl flex items-center gap-3 transform translate-y-20 opacity-0 transition-all duration-300 z-50">
    <span id="toast-icon" class="material-symbols-outlined text-[22px]">info</span>
    <span id="toast-msg" class="text-[14px] font-bold"></span>
</div>

</body>
</html>
