<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="flex-1 w-full flex flex-col h-screen overflow-y-auto bg-[#F8F9FB] dark:bg-neutral-950 transition-colors duration-200">
    <!-- Header -->
    <header class="h-20 bg-white dark:bg-neutral-900 border-b border-outline-variant dark:border-neutral-800 flex items-center justify-between px-10 sticky top-0 z-10 transition-colors duration-200">
        <h1 class="text-h2 font-bold text-primary dark:text-white">Báo cáo & Thống kê</h1>
        
        <div class="flex items-center gap-6 header-actions">
            <div class="flex items-center gap-3 relative">
                <!-- Custom Premium Dropdown Select -->
                <div class="relative inline-block text-left" id="customExportDropdown">
                    <button type="button" id="dropdownTrigger" class="px-4 py-2.5 bg-white dark:bg-neutral-900 border border-outline-variant dark:border-neutral-700 rounded-xl text-[13px] font-bold text-on-surface-variant dark:text-neutral-300 flex items-center justify-between gap-3 hover:bg-surface-container-low dark:hover:bg-neutral-800 hover:border-primary/30 transition-all min-w-[220px] shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <span class="flex items-center gap-2" id="selectedLabel">
                            <span class="material-symbols-outlined text-[18px] text-primary dark:text-indigo-400">grid_view</span>
                            <span>Tất cả báo cáo</span>
                        </span>
                        <span class="material-symbols-outlined text-[16px] text-on-surface-variant/70 dark:text-neutral-400 transition-transform duration-200" id="dropdownArrow">keyboard_arrow_down</span>
                    </button>
                    
                    <!-- Hidden input to store value, keeping compatibility with existing functions -->
                    <input type="hidden" id="exportTypeSelect" value="all">

                    <!-- Dropdown Menu Options -->
                    <div id="dropdownMenu" class="absolute right-0 mt-2 w-64 bg-white dark:bg-neutral-850 border border-outline-variant dark:border-neutral-700 rounded-xl shadow-xl overflow-hidden hidden z-50 transform origin-top-right transition-all duration-150 ease-out opacity-0 scale-95">
                        <div class="py-1.5 bg-white dark:bg-neutral-800" role="menu" aria-orientation="vertical">
                            <button type="button" class="w-full text-left px-4 py-2.5 text-[13px] font-semibold text-on-surface-variant dark:text-neutral-300 hover:bg-primary/5 dark:hover:bg-neutral-700 hover:text-primary dark:hover:text-white transition-colors flex items-center gap-2.5" data-value="all" role="menuitem">
                                <span class="material-symbols-outlined text-[18px] opacity-70">grid_view</span> Tất cả báo cáo
                            </button>
                            <div class="h-px bg-outline-variant/60 dark:bg-neutral-700 my-1"></div>
                            <button type="button" class="w-full text-left px-4 py-2.5 text-[13px] font-semibold text-on-surface-variant dark:text-neutral-300 hover:bg-primary/5 dark:hover:bg-neutral-700 hover:text-primary dark:hover:text-white transition-colors flex items-center gap-2.5" data-value="brand" role="menuitem">
                                <span class="material-symbols-outlined text-[18px] opacity-70">sell</span> Doanh thu theo Hãng
                            </button>
                            <button type="button" class="w-full text-left px-4 py-2.5 text-[13px] font-semibold text-on-surface-variant dark:text-neutral-300 hover:bg-primary/5 dark:hover:bg-neutral-700 hover:text-primary dark:hover:text-white transition-colors flex items-center gap-2.5" data-value="week" role="menuitem">
                                <span class="material-symbols-outlined text-[18px] opacity-70">calendar_view_week</span> Doanh thu theo Tuần
                            </button>
                            <button type="button" class="w-full text-left px-4 py-2.5 text-[13px] font-semibold text-on-surface-variant dark:text-neutral-300 hover:bg-primary/5 dark:hover:bg-neutral-700 hover:text-primary dark:hover:text-white transition-colors flex items-center gap-2.5" data-value="month" role="menuitem">
                                <span class="material-symbols-outlined text-[18px] opacity-70">calendar_month</span> Doanh thu theo Tháng
                            </button>
                            <button type="button" class="w-full text-left px-4 py-2.5 text-[13px] font-semibold text-on-surface-variant dark:text-neutral-300 hover:bg-primary/5 dark:hover:bg-neutral-700 hover:text-primary dark:hover:text-white transition-colors flex items-center gap-2.5" data-value="year" role="menuitem">
                                <span class="material-symbols-outlined text-[18px] opacity-70">calendar_today</span> Doanh thu theo Năm
                            </button>
                            <div class="h-px bg-outline-variant/60 dark:bg-neutral-700 my-1"></div>
                            <button type="button" class="w-full text-left px-4 py-2.5 text-[13px] font-semibold text-on-surface-variant dark:text-neutral-300 hover:bg-primary/5 dark:hover:bg-neutral-700 hover:text-primary dark:hover:text-white transition-colors flex items-center gap-2.5" data-value="product" role="menuitem">
                                <span class="material-symbols-outlined text-[18px] opacity-70">shopping_bag</span> Sản phẩm bán chạy
                            </button>
                            <button type="button" class="w-full text-left px-4 py-2.5 text-[13px] font-semibold text-on-surface-variant dark:text-neutral-300 hover:bg-primary/5 dark:hover:bg-neutral-700 hover:text-primary dark:hover:text-white transition-colors flex items-center gap-2.5" data-value="customer" role="menuitem">
                                <span class="material-symbols-outlined text-[18px] opacity-70">group</span> Khách hàng tiêu biểu
                            </button>
                            <button type="button" class="w-full text-left px-4 py-2.5 text-[13px] font-semibold text-on-surface-variant dark:text-neutral-300 hover:bg-primary/5 dark:hover:bg-neutral-700 hover:text-primary dark:hover:text-white transition-colors flex items-center gap-2.5" data-value="low_stock" role="menuitem">
                                <span class="material-symbols-outlined text-[18px] opacity-70">warning</span> Sản phẩm sắp hết hàng
                            </button>
                        </div>
                    </div>
                </div>

                <button onclick="exportToPDF()" class="px-4 py-2.5 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/50 rounded-xl text-[13px] font-bold flex items-center gap-2 hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-red-100 active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span> Xuất PDF
                </button>
                <a id="exportExcelBtn" href="<?php echo URLROOT; ?>/admin/exportStats?type=all" class="px-4 py-2.5 bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-900/50 rounded-xl text-[13px] font-bold flex items-center gap-2 hover:bg-green-600 hover:text-white transition-all shadow-sm hover:shadow-green-100 active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[18px]">table_view</span> Xuất Excel
                </a>
            </div>

            <script>
            function updateExportLinks() {
                const type = document.getElementById('exportTypeSelect').value;
                const excelBtn = document.getElementById('exportExcelBtn');
                if (excelBtn) {
                    excelBtn.href = '<?php echo URLROOT; ?>/admin/exportStats?type=' + type;
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const trigger = document.getElementById('dropdownTrigger');
                const menu = document.getElementById('dropdownMenu');
                const arrow = document.getElementById('dropdownArrow');
                const hiddenInput = document.getElementById('exportTypeSelect');
                const selectedLabel = document.getElementById('selectedLabel');
                const options = menu.querySelectorAll('[data-value]');

                // Toggle dropdown function
                const toggleDropdown = (show) => {
                    if (show) {
                        menu.classList.remove('hidden');
                        // Microtask delay to trigger opacity and transform animations
                        setTimeout(() => {
                            menu.classList.remove('opacity-0', 'scale-95');
                            menu.classList.add('opacity-100', 'scale-100');
                            arrow.classList.add('rotate-180');
                        }, 20);
                    } else {
                        menu.classList.remove('opacity-100', 'scale-100');
                        menu.classList.add('opacity-0', 'scale-95');
                        arrow.classList.remove('rotate-180');
                        setTimeout(() => {
                            if (menu.classList.contains('opacity-0')) {
                                menu.classList.add('hidden');
                            }
                        }, 150);
                    }
                };

                // Click event on trigger
                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isHidden = menu.classList.contains('hidden');
                    toggleDropdown(isHidden);
                });

                // Click outside closes dropdown
                document.addEventListener('click', () => {
                    toggleDropdown(false);
                });

                // Option selection
                options.forEach(opt => {
                    opt.addEventListener('click', () => {
                        const val = opt.getAttribute('data-value');
                        
                        // Set input value
                        hiddenInput.value = val;
                        
                        // Update trigger UI (both icon and text)
                        selectedLabel.innerHTML = opt.innerHTML;
                        
                        // Trigger update of excel href link
                        updateExportLinks();
                        
                        // Close dropdown
                        toggleDropdown(false);
                    });
                });
            });
            </script>
            <div class="flex items-center gap-4 border-l border-outline-variant dark:border-neutral-800 pl-6">
                <!-- Theme Switcher -->
                <button type="button" onclick="toggleTheme()" class="w-10 h-10 rounded-xl bg-surface-container-low dark:bg-neutral-800 border border-outline-variant dark:border-neutral-700 flex items-center justify-center text-on-surface-variant dark:text-neutral-300 hover:bg-primary/5 dark:hover:bg-neutral-700/60 transition-all shadow-sm">
                    <span id="themeToggleIcon" class="material-symbols-outlined text-[20px]">dark_mode</span>
                </button>

                <!-- Notifications -->
                <?php require_once VIEWS . '/layout/admin_notification.php'; ?>

                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant dark:border-neutral-800">
                    <img alt="Admin" class="w-10 h-10 rounded-full object-cover" src="<?php echo $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=Admin&background=0453cd&color=fff'; ?>"/>
                    <div class="text-right">
                        <p class="text-[14px] font-bold dark:text-white"><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></p>
                        <p class="text-[12px] text-on-surface-variant dark:text-neutral-400">Quản trị viên</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div id="dashboard-main-content" class="p-10 space-y-8 w-full transition-colors duration-200">
        <!-- KPI Cards -->
        <section id="kpis-section" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php 
            $kpis = [
                'revenue' => ['label' => 'Tổng doanh thu', 'icon' => 'payments', 'unit' => 'đ', 'gradient' => 'from-blue-600 to-indigo-600'],
                'avg_order' => ['label' => 'Giá trị TB đơn', 'icon' => 'analytics', 'unit' => 'đ', 'gradient' => 'from-indigo-600 to-violet-600'],
                'orders' => ['label' => 'Tổng đơn hàng', 'icon' => 'shopping_bag', 'unit' => '', 'gradient' => 'from-emerald-500 to-teal-500'],
                'inventory' => ['label' => 'Sản phẩm', 'icon' => 'inventory_2', 'unit' => '', 'gradient' => 'from-amber-500 to-orange-500'],
                'users' => ['label' => 'Khách hàng mới', 'icon' => 'group', 'unit' => '', 'gradient' => 'from-purple-500 to-fuchsia-500']
            ];
            foreach($kpis as $key => $kpi): 
                if ($key == 'avg_order') {
                    $value = number_format($data['avg_order_value'], 0, ',', '.');
                    $growth = 'Mới';
                    $growthClass = 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 border border-indigo-100/50 dark:border-indigo-900/30';
                } else if ($key == 'users') {
                    $value = $data['stats']['users']['value'];
                    $growth = $data['stats']['users']['growth'];
                    $status = $data['stats']['users']['status'];
                    $growthClass = $status === 'up' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100/50 dark:border-emerald-900/30' : ($status === 'down' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-100/50 dark:border-rose-900/30' : 'bg-slate-50 text-slate-600 dark:bg-zinc-800 dark:text-zinc-400 border border-slate-200/50 dark:border-zinc-700/50');
                } else {
                    $value = $data['stats'][$key]['value'];
                    $growth = $data['stats'][$key]['growth'];
                    $status = $data['stats'][$key]['status'];
                    $growthClass = $status === 'up' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100/50 dark:border-emerald-900/30' : ($status === 'down' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-100/50 dark:border-rose-900/30' : 'bg-slate-50 text-slate-600 dark:bg-zinc-800 dark:text-zinc-400 border border-slate-200/50 dark:border-zinc-700/50');
                }
            ?>
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-slate-200/80 dark:border-zinc-800/80 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <!-- Decorative Ambient Gradient Glow inside the Card -->
                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-gradient-to-br <?php echo $kpi['gradient']; ?> opacity-[0.03] dark:opacity-[0.06] blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br <?php echo $kpi['gradient']; ?> p-2.5 flex items-center justify-center shadow-md shadow-indigo-500/10">
                        <span class="material-symbols-outlined text-white text-[20px] font-medium"><?php echo $kpi['icon']; ?></span>
                    </div>
                    <span id="kpi-growth-<?php echo $key; ?>" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase <?php echo $growthClass; ?>">
                        <?php echo $growth; ?>
                    </span>
                </div>
                <div class="relative z-10">
                    <p class="text-slate-400 dark:text-zinc-500 text-[11px] font-bold uppercase tracking-wider mb-1"><?php echo $kpi['label']; ?></p>
                    <p class="text-[22px] font-extrabold text-slate-800 dark:text-zinc-100 tracking-tight font-h2 truncate">
                        <span id="kpi-value-<?php echo $key; ?>"><?php echo $value; ?></span> 
                        <span class="text-[13px] font-normal text-slate-400 dark:text-zinc-500 ml-0.5"><?php echo $kpi['unit']; ?></span>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </section>        <!-- Charts Grid -->
        <section id="charts-section" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Revenue Chart -->
            <?php 
            $initialPeriodTotal = array_sum(array_column($data['charts']['revenue'], 'revenue'));
            ?>
            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 p-8 rounded-2xl border border-slate-200 dark:border-zinc-800/80 shadow-sm flex flex-col justify-between transition-colors duration-200">
                <div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-h3 font-bold text-slate-800 dark:text-white">Biểu đồ Doanh thu</h2>
                            <p class="text-slate-400 dark:text-zinc-500 text-[14px]">Dữ liệu doanh thu theo thời gian</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-zinc-950 p-1 rounded-xl border border-slate-200 dark:border-zinc-800 mr-2">
                                <button type="button" onclick="setQuickFilter('today')" class="filter-btn px-3 py-1.5 text-[11px] font-bold rounded-lg hover:bg-white dark:hover:bg-zinc-900 transition-all active:scale-[0.98]">Hôm nay</button>
                                <button type="button" onclick="setQuickFilter('7days')" class="filter-btn px-3 py-1.5 text-[11px] font-bold rounded-lg hover:bg-white dark:hover:bg-zinc-900 transition-all active:scale-[0.98]">7 ngày</button>
                                <button type="button" onclick="setQuickFilter('month')" class="filter-btn px-3 py-1.5 bg-white dark:bg-zinc-900 text-indigo-600 dark:text-indigo-400 text-[11px] font-bold rounded-lg border border-slate-200/50 dark:border-zinc-800 shadow-sm transition-all active:scale-[0.98]">Tháng này</button>
                                <button type="button" onclick="setQuickFilter('year')" class="filter-btn px-3 py-1.5 text-[11px] font-bold rounded-lg hover:bg-white dark:hover:bg-zinc-900 transition-all active:scale-[0.98]">Năm nay</button>
                            </div>
                            <div class="flex items-center gap-3 bg-slate-100 dark:bg-zinc-950 p-2 rounded-xl border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Từ</span>
                                    <input type="date" id="startDate" class="bg-transparent border-none p-0 text-[12px] font-bold outline-none focus:ring-0 dark:text-white cursor-pointer" value="<?php echo date('Y-m-d', strtotime('-6 months')); ?>">
                                </div>
                                <div class="w-px h-4 bg-slate-200 dark:bg-zinc-800"></div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Đến</span>
                                    <input type="date" id="endDate" class="bg-transparent border-none p-0 text-[12px] font-bold outline-none focus:ring-0 dark:text-white cursor-pointer" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <button onclick="updateDashboardData()" class="w-7 h-7 flex items-center justify-center bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-all shadow-md shadow-indigo-500/10 active:scale-95">
                                    <span class="material-symbols-outlined text-[16px]">refresh</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Today's and Selected Period's Revenue Stats -->
                    <div class="flex flex-wrap items-center gap-8 mt-6 pb-6 border-b border-dashed border-outline-variant/60">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[22px]">today</span>
                            </div>
                            <div>
                                <p class="text-[11px] text-on-surface-variant uppercase tracking-wider font-semibold">Doanh thu hôm nay</p>
                                <p class="text-[22px] font-extrabold text-emerald-600">
                                    <?php echo number_format($data['today_revenue'], 0, ',', '.'); ?> <span class="text-[12px] font-normal text-on-surface-variant">đ</span>
                                </p>
                            </div>
                        </div>
                        <div class="w-px h-8 bg-outline-variant/60 hidden sm:block"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-secondary flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[22px]">payments</span>
                            </div>
                            <div>
                                <p class="text-[11px] text-on-surface-variant uppercase tracking-wider font-semibold">Tổng doanh thu chu kỳ</p>
                                <p class="text-[22px] font-extrabold text-secondary" id="periodRevenueText">
                                    <?php echo number_format($initialPeriodTotal, 0, ',', '.'); ?> <span class="text-[12px] font-normal text-on-surface-variant">đ</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-80 mt-6">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Category Distribution -->
            <div class="bg-white dark:bg-neutral-900 p-8 rounded-2xl border border-outline-variant dark:border-neutral-800 shadow-sm transition-colors duration-200">
                <h2 class="text-h3 font-bold text-primary dark:text-white mb-2">Doanh thu theo Danh mục</h2>
                <p class="text-on-surface-variant dark:text-neutral-400 text-[14px] mb-8">Tỷ lệ đóng góp doanh số</p>
                <div class="h-64 relative">
                    <canvas id="categoryChart"></canvas>
                </div>
                <div id="categoryLegendList" class="mt-8 space-y-3 max-h-40 overflow-y-auto pr-2">
                    <?php 
                    $catColors = ['#0453cd', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1'];
                    foreach($data['charts']['category_revenue'] as $index => $cat): 
                        $color = $catColors[$index % count($catColors)];
                    ?>
                    <div class="flex items-center justify-between text-[13px]">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full" style="background-color: <?php echo $color; ?>"></span>
                            <span class="font-medium text-on-surface-variant dark:text-neutral-300"><?php echo $cat['label']; ?></span>
                        </div>
                        <span class="font-bold dark:text-white"><?php echo number_format($cat['value'], 0, ',', '.'); ?> đ</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Weekly Growth Analysis -->
        <section id="weekly-chart-section" class="bg-white dark:bg-neutral-900 p-8 rounded-2xl border border-outline-variant dark:border-neutral-800 shadow-sm transition-colors duration-200">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-h3 font-bold text-primary dark:text-white">Phân tích Tăng trưởng Hàng tuần</h2>
                    <p class="text-on-surface-variant dark:text-neutral-400 text-[14px]">Biến động doanh thu theo từng tuần (8 tuần gần nhất)</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl border border-indigo-100 dark:border-indigo-900/50">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                        <span class="text-[11px] font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-widest">Real-time Data</span>
                    </div>
                </div>
            </div>
            <div class="h-64">
                <canvas id="weeklyRevenueChart"></canvas>
            </div>
        </section>

        <section id="details-grid-section" class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Top Selling Products -->
            <div id="top-products-column" class="bg-white dark:bg-zinc-900 p-8 rounded-2xl border border-slate-200 dark:border-zinc-800/80 shadow-md hover:shadow-lg transition-all duration-300">
                <h2 class="text-h3 font-bold text-slate-800 dark:text-white mb-6">Sản phẩm bán chạy</h2>
                <div id="topProductsList" class="space-y-4">
                    <?php if(empty($data['top_products'])): ?>
                        <p class="text-slate-400 dark:text-zinc-500 text-center py-10 italic">Chưa có dữ liệu.</p>
                    <?php else: ?>
                        <?php foreach($data['top_products'] as $product): ?>
                        <div class="flex items-center justify-between gap-3 p-3 bg-slate-50 dark:bg-zinc-950/40 rounded-xl border border-slate-100 dark:border-zinc-800/60 hover:scale-[1.01] hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <?php 
                                    $imagePath = get_product_image($product['image']);
                                ?>
                                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-10 h-10 rounded-lg object-cover flex-shrink-0 bg-gray-100 dark:bg-neutral-800 ring-2 ring-slate-100 dark:ring-zinc-800" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 40 40\'%3E%3Crect width=\'40\' height=\'40\' rx=\'8\' fill=\'%23f3f4f6\'/%3E%3Cpath d=\'M12 28l7-9 5 6 3-4 6 7z\' fill=\'%23d1d5db\'/%3E%3Ccircle cx=\'16\' cy=\'16\' r=\'3\' fill=\'%23d1d5db\'/%3E%3C/svg%3E'">
                                <div class="min-w-0">
                                    <p class="font-bold text-[13px] text-slate-800 dark:text-zinc-200 truncate"><?php echo $product['name']; ?></p>
                                    <p class="text-[11px] text-slate-400 dark:text-zinc-500"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 pl-2">
                                <p class="font-bold text-indigo-600 dark:text-indigo-400 text-[14px]"><?php echo $product['sold_count']; ?></p>
                                <p class="text-[9px] text-slate-400 dark:text-zinc-500 uppercase font-bold tracking-wider">Đã bán</p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Top Customers -->
            <div id="top-customers-column" class="bg-white dark:bg-zinc-900 p-8 rounded-2xl border border-slate-200 dark:border-zinc-800/80 shadow-md hover:shadow-lg transition-all duration-300">
                <h2 class="text-h3 font-bold text-slate-800 dark:text-white mb-6">Khách hàng tiêu biểu</h2>
                <div id="topCustomersList" class="space-y-4">
                    <?php if(empty($data['top_customers'])): ?>
                        <p class="text-slate-400 dark:text-zinc-500 text-center py-10 italic">Chưa có dữ liệu.</p>
                    <?php else: ?>
                        <?php foreach($data['top_customers'] as $customer): ?>
                        <div class="flex items-center justify-between p-3 bg-indigo-50/20 dark:bg-zinc-950/20 rounded-xl border border-indigo-100/20 dark:border-zinc-800/60 hover:scale-[1.01] hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <img src="<?php echo ($customer['avatar'] ?? null) ?: 'https://ui-avatars.com/api/?name=' . urlencode($customer['name']) . '&background=random'; ?>" alt="" class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-500/10">
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-[13px] text-slate-800 dark:text-zinc-200 truncate"><?php echo $customer['name']; ?></p>
                                    <p class="text-[11px] text-slate-400 dark:text-zinc-500"><?php echo $customer['order_count']; ?> đơn hàng</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="font-bold text-indigo-600 dark:text-indigo-400 text-[14px]"><?php echo number_format($customer['total_spent'], 0, ',', '.'); ?> đ</p>
                                <p class="text-[9px] text-slate-400 dark:text-zinc-500 uppercase font-bold tracking-wider">Tổng chi</p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div id="low-stock-column" class="bg-white dark:bg-zinc-900 p-8 rounded-2xl border border-slate-200 dark:border-zinc-800/80 shadow-md hover:shadow-lg transition-all duration-300">
                <h2 class="text-h3 font-bold text-rose-600 dark:text-rose-400 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[22px]">warning</span> Sắp hết hàng
                </h2>
                <div id="lowStockList" class="space-y-4">
                    <?php if(empty($data['low_stock'])): ?>
                        <p class="text-emerald-600 dark:text-emerald-400 text-center py-10 italic font-medium">Kho hàng đang ổn định.</p>
                    <?php else: ?>
                        <?php foreach($data['low_stock'] as $product): ?>
                        <div class="flex items-center gap-3 p-3 bg-rose-50/30 dark:bg-rose-950/10 rounded-xl border border-rose-100/30 dark:border-zinc-800/60 hover:scale-[1.01] hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <?php 
                                    $imagePath = get_product_image($product['image']);
                                ?>
                                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-10 h-10 rounded-lg object-cover bg-gray-100 dark:bg-neutral-800 flex-shrink-0 ring-2 ring-rose-100 dark:ring-rose-950" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 40 40\'%3E%3Crect width=\'40\' height=\'40\' rx=\'8\' fill=\'%23fff0f0\'/%3E%3Cpath d=\'M12 28l7-9 5 6 3-4 6 7z\' fill=\'%23fca5a5\'/%3E%3Ccircle cx=\'16\' cy=\'16\' r=\'3\' fill=\'%23fca5a5\'/%3E%3C/svg%3E'">
                                <div class="min-w-0">
                                    <p class="font-bold text-[13px] text-slate-800 dark:text-zinc-200 truncate"><?php echo $product['name']; ?></p>
                                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-bold">Chỉ còn <?php echo $product['stock']; ?> sp</p>
                                </div>
                            </div>
                            <a href="<?php echo URLROOT; ?>/admin/editProduct/<?php echo $product['id'] ?? ''; ?>" class="p-2 bg-white dark:bg-zinc-800 text-rose-600 dark:text-rose-450 border border-slate-200 dark:border-zinc-700 rounded-lg hover:bg-rose-600 dark:hover:bg-rose-500 hover:text-white hover:border-rose-600 transition-all shadow-sm flex-shrink-0">
                                <span class="material-symbols-outlined !text-[18px]">edit</span>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Brand Revenue Distribution -->
            <div id="brand-revenue-column" class="bg-white dark:bg-zinc-900 p-8 rounded-2xl border border-slate-200 dark:border-zinc-800/80 shadow-md hover:shadow-lg transition-all duration-300">
                <h2 class="text-h3 font-bold text-slate-800 dark:text-white mb-2">Doanh thu theo Hãng</h2>
                <p class="text-slate-400 dark:text-zinc-500 text-[14px] mb-6">Thị phần thương hiệu</p>
                <div class="h-48 relative mb-6">
                    <canvas id="brandChart"></canvas>
                </div>
                <div id="brandLegendList" class="space-y-2 max-h-40 overflow-y-auto">
                    <?php 
                    $brandColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#3b82f6'];
                    foreach($data['charts']['brand_revenue'] as $index => $brand): 
                        $color = $brandColors[$index % count($brandColors)];
                    ?>
                    <div class="flex items-center justify-between text-[11px]">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: <?php echo $color; ?>"></span>
                            <span class="font-medium text-slate-600 dark:text-zinc-300 truncate max-w-[120px]"><?php echo $brand['label']; ?></span>
                        </div>
                        <span class="font-bold ml-auto dark:text-white text-slate-800"><?php echo number_format($brand['value']/1000000, 1, ',', '.'); ?>M</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Recent Orders -->
        <section id="recent-orders-section" class="bg-white dark:bg-zinc-900 p-8 rounded-2xl border border-slate-200 dark:border-zinc-800/80 shadow-md hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-h3 font-bold text-slate-800 dark:text-white">Đơn hàng mới nhất</h2>
                    <p class="text-slate-400 dark:text-zinc-500 text-sm">Theo dõi các giao dịch gần đây</p>
                </div>
                <a href="<?php echo URLROOT; ?>/admin/orders" class="px-4 py-2 bg-slate-100 dark:bg-zinc-850 text-slate-700 dark:text-zinc-300 rounded-xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-zinc-800 transition-all">Xem tất cả đơn hàng</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 dark:text-zinc-500 text-[11px] uppercase tracking-wider font-bold border-b border-slate-100 dark:border-zinc-800 pb-4">
                            <th class="pb-4">Mã đơn</th>
                            <th class="pb-4">Khách hàng</th>
                            <th class="pb-4">Ngày đặt</th>
                            <th class="pb-4">Tổng tiền</th>
                            <th class="pb-4 text-center">Trạng thái</th>
                            <th class="pb-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px]">
                        <?php foreach($data['recent_orders'] as $order): ?>
                        <tr class="border-t border-slate-100 dark:border-zinc-800 hover:bg-slate-50/50 dark:hover:bg-zinc-950/20 transition-colors group">
                            <td class="py-4 font-bold text-slate-800 dark:text-white">
                                #<?php echo $order['id']; ?>
                            </td>
                            <td class="py-4 font-bold text-slate-700 dark:text-zinc-300">
                                <?php echo $order['customer']; ?>
                            </td>
                            <td class="py-4 text-slate-400 dark:text-zinc-500">
                                <?php echo $order['date']; ?>
                            </td>
                            <td class="py-4 font-bold text-indigo-600 dark:text-indigo-400">
                                <?php echo $order['total']; ?> đ
                            </td>
                            <td class="py-4">
                                <div class="flex justify-center">
                                    <?php 
                                        $badgeClass = 'badge-pending';
                                        if ($order['status'] === 'completed' || $order['status'] === 'delivered') $badgeClass = 'badge-delivered';
                                        elseif ($order['status'] === 'cancelled') $badgeClass = 'badge-cancelled';
                                        elseif ($order['status'] === 'shipping') $badgeClass = 'badge-shipping';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider <?php echo $badgeClass; ?>">
                                        <?php echo $order['status_text']; ?>
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 text-right">
                                <a href="<?php echo URLROOT; ?>/admin/orderDetail/<?php echo $order['raw_id']; ?>" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 hover:bg-indigo-600 dark:hover:bg-indigo-500 dark:hover:text-white hover:text-white transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<script>
function exportToPDF() {
    const exportType = document.getElementById('exportTypeSelect').value;
    
    // Trích xuất ảnh biểu đồ dạng base64 từ Chart.js
    const chartImages = {};
    if (window.revenueChart) chartImages.revenue = window.revenueChart.toBase64Image();
    if (window.weeklyRevenueChart) chartImages.weekly = window.weeklyRevenueChart.toBase64Image();
    if (window.categoryChart) chartImages.category = window.categoryChart.toBase64Image();
    if (window.brandChart) chartImages.brand = window.brandChart.toBase64Image();

    // Tạo form ẩn để gửi yêu cầu POST xuất PDF
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo URLROOT; ?>/admin/dashboardPDF';
    form.target = '_blank';

    // CSRF Token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = 'csrf_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfInput);

    // Export Type
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'export_type';
    typeInput.value = exportType;
    form.appendChild(typeInput);

    // Thêm các ảnh biểu đồ
    for (const [name, imgData] of Object.entries(chartImages)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `charts[${name}]`;
        input.value = imgData;
        form.appendChild(input);
    }

    // Thời gian lọc hiện tại
    const startInput = document.createElement('input');
    startInput.type = 'hidden';
    startInput.name = 'start_date';
    startInput.value = document.getElementById('startDate').value;
    form.appendChild(startInput);

    const endInput = document.createElement('input');
    endInput.type = 'hidden';
    endInput.name = 'end_date';
    endInput.value = document.getElementById('endDate').value;
    form.appendChild(endInput);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>

<script>
// Theme Management functions
function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    if (isDark) {
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
        localStorage.setItem('theme', 'light');
        document.getElementById('themeToggleIcon').innerText = 'dark_mode';
    } else {
        document.documentElement.classList.remove('light');
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        document.getElementById('themeToggleIcon').innerText = 'light_mode';
    }
    updateChartsTheme();
}

function updateChartsTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? '#2e2e30' : '#e1e2e4';
    const tickColor = isDark ? '#a3a3a3' : '#46474a';
    const tooltipBg = isDark ? '#262626' : '#191c1e';

    if (window.revenueChart) {
        window.revenueChart.options.scales.y.grid.color = gridColor;
        window.revenueChart.options.scales.y.ticks.color = tickColor;
        window.revenueChart.options.scales.x.ticks.color = tickColor;
        window.revenueChart.options.plugins.tooltip.backgroundColor = tooltipBg;
        window.revenueChart.update();
    }
    if (window.weeklyRevenueChart) {
        window.weeklyRevenueChart.options.scales.y.grid.color = gridColor;
        window.weeklyRevenueChart.options.scales.y.ticks.color = tickColor;
        window.weeklyRevenueChart.options.scales.x.ticks.color = tickColor;
        window.weeklyRevenueChart.options.plugins.tooltip.backgroundColor = tooltipBg;
        window.weeklyRevenueChart.update();
    }
    if (window.categoryChart) {
        window.categoryChart.options.plugins.tooltip.backgroundColor = tooltipBg;
        window.categoryChart.update();
    }
    if (window.brandChart) {
        window.brandChart.options.plugins.tooltip.backgroundColor = tooltipBg;
        window.brandChart.update();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const isDark = document.documentElement.classList.contains('dark');
    const toggleIcon = document.getElementById('themeToggleIcon');
    if (toggleIcon) {
        toggleIcon.innerText = isDark ? 'light_mode' : 'dark_mode';
    }
    updateChartsTheme();
});

// Helper to format currency
const formatVND = (value) => new Intl.NumberFormat('vi-VN').format(value) + ' đ';

// Revenue Chart Initialization
const ctxRev = document.getElementById('revenueChart').getContext('2d');
const gradient = ctxRev.createLinearGradient(0, 0, 0, 320);
gradient.addColorStop(0, 'rgba(4, 83, 205, 0.3)');
gradient.addColorStop(0.5, 'rgba(4, 83, 205, 0.1)');
gradient.addColorStop(1, 'rgba(4, 83, 205, 0.0)');

window.revenueChart = new Chart(ctxRev, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_map(fn($d) => $d['month'], $data['charts']['revenue'])); ?>,
        datasets: [{
            label: 'Doanh thu',
            data: <?php echo json_encode(array_map(fn($d) => $d['revenue'], $data['charts']['revenue'])); ?>,
            borderColor: '#0453cd',
            backgroundColor: gradient,
            fill: true,
            tension: 0.4,
            borderWidth: 3,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#0453cd',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: '#0453cd',
            pointHoverBorderColor: '#ffffff',
            pointHoverBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#191c1e',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                bodyFont: { family: 'Inter', size: 13, weight: '600' },
                padding: 12,
                cornerRadius: 8,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return 'Doanh thu: ' + formatVND(context.raw);
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { borderDash: [5, 5], color: '#e1e2e4' },
                ticks: {
                    font: { family: 'Inter', size: 11 },
                    color: '#46474a',
                    callback: (value) => formatVND(value)
                }
            },
            x: {
                grid: { display: false },
                ticks: {
                    font: { family: 'Inter', size: 11 },
                    color: '#46474a'
                }
            }
        }
    }
});

// Weekly Revenue Chart Initialization
const ctxWeekly = document.getElementById('weeklyRevenueChart').getContext('2d');
window.weeklyRevenueChart = new Chart(ctxWeekly, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_map(fn($d) => $d['label'], $data['charts']['revenue_weekly'])); ?>,
        datasets: [{
            label: 'Doanh thu Tuần',
            data: <?php echo json_encode(array_map(fn($d) => $d['revenue'], $data['charts']['revenue_weekly'])); ?>,
            backgroundColor: 'rgba(99, 102, 241, 0.15)',
            borderColor: '#6366f1',
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: '#6366f1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#191c1e',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                bodyFont: { family: 'Inter', size: 13, weight: '600' },
                padding: 12,
                cornerRadius: 8,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return formatVND(context.raw);
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { borderDash: [5, 5], color: '#e1e2e4' },
                ticks: {
                    font: { family: 'Inter', size: 11 },
                    color: '#46474a',
                    callback: (value) => new Intl.NumberFormat('vi-VN').format(value/1000000) + 'M'
                }
            },
            x: {
                grid: { display: false },
                ticks: {
                    font: { family: 'Inter', size: 11 },
                    color: '#46474a'
                }
            }
        }
    }
});

// Category Distribution Chart
const ctxCat = document.getElementById('categoryChart').getContext('2d');
const catData = <?php echo json_encode($data['charts']['category_revenue']); ?>;
const catColors = ['#0453cd', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1'];

window.categoryChart = new Chart(ctxCat, {
    type: 'pie',
    data: {
        labels: catData.map(d => d.label),
        datasets: [{
            data: catData.map(d => d.value),
            backgroundColor: catColors,
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return (context.label || '') + ': ' + formatVND(context.raw);
                    }
                }
            }
        }
    }
});

// Brand Distribution Chart
const ctxBrand = document.getElementById('brandChart').getContext('2d');
const brandData = <?php echo json_encode($data['charts']['brand_revenue']); ?>;
const brandColors = ['#0453cd', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1'];

window.brandChart = new Chart(ctxBrand, {
    type: 'doughnut',
    data: {
        labels: brandData.map(d => d.label),
        datasets: [{
            data: brandData.map(d => d.value),
            backgroundColor: brandColors,
            borderWidth: 0,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return (context.label || '') + ': ' + formatVND(context.raw);
                    }
                }
            }
        }
    }
});

// AJAX Dynamic Dashboard Update
async function updateDashboardData() {
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    
    // Find refresh button
    const btn = document.querySelector('button[onclick="updateDashboardData()"]');
    let originalHTML = '';
    if (btn) {
        originalHTML = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">sync</span>';
        btn.disabled = true;
    }
    
    try {
        const response = await fetch(`<?php echo URLROOT; ?>/admin/getDashboardStatsData?start=${start}&end=${end}`);
        const data = await response.json();
        
        // 1. Update KPI values
        for (const [key, item] of Object.entries(data.stats)) {
            const valEl = document.getElementById(`kpi-value-${key}`);
            if (valEl) valEl.innerText = item.value;
            
            const growthEl = document.getElementById(`kpi-growth-${key}`);
            if (growthEl && item.growth !== undefined) {
                growthEl.innerText = item.growth;
                // Update growth classes
                growthEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold ';
                if (item.status === 'up') {
                    growthEl.className += 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300';
                } else if (item.status === 'down') {
                    growthEl.className += 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300';
                } else {
                    growthEl.className += 'bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-neutral-300';
                }
            }
        }
        
        // Update selected period total text
        const periodText = document.getElementById('periodRevenueText');
        if (periodText) {
            periodText.innerHTML = data.stats.revenue.value + ' <span class="text-[12px] font-normal text-on-surface-variant dark:text-neutral-400">đ</span>';
        }
        
        // 2. Update Revenue Chart
        if (window.revenueChart) {
            window.revenueChart.data.labels = data.charts.revenue.map(d => d.label);
            window.revenueChart.data.datasets[0].data = data.charts.revenue.map(d => d.revenue);
            window.revenueChart.update();
        }
        
        // 3. Update Category Chart & Legend
        if (window.categoryChart) {
            window.categoryChart.data.labels = data.charts.category_revenue.map(d => d.label);
            window.categoryChart.data.datasets[0].data = data.charts.category_revenue.map(d => d.value);
            window.categoryChart.update();
        }
        
        const catLegendEl = document.getElementById('categoryLegendList');
        if (catLegendEl) {
            let catHTML = '';
            data.charts.category_revenue.forEach((cat, index) => {
                const color = catColors[index % catColors.length];
                catHTML += `
                <div class="flex items-center justify-between text-[13px]">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background-color: ${color}"></span>
                        <span class="font-medium text-on-surface-variant dark:text-neutral-300">${cat.label}</span>
                    </div>
                    <span class="font-bold dark:text-white">${new Intl.NumberFormat('vi-VN').format(cat.value)} đ</span>
                </div>`;
            });
            catLegendEl.innerHTML = catHTML;
        }

        // 4. Update Brand Chart & Legend
        if (window.brandChart) {
            window.brandChart.data.labels = data.charts.brand_revenue.map(d => d.label);
            window.brandChart.data.datasets[0].data = data.charts.brand_revenue.map(d => d.value);
            window.brandChart.update();
        }
        
        const brandLegendEl = document.getElementById('brandLegendList');
        if (brandLegendEl) {
            let brandHTML = '';
            data.charts.brand_revenue.forEach((brand, index) => {
                const color = brandColors[index % brandColors.length];
                brandHTML += `
                <div class="flex items-center justify-between text-[11px]">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full" style="background-color: ${color}"></span>
                        <span class="font-medium text-on-surface-variant dark:text-neutral-300 truncate">${brand.label}</span>
                    </div>
                    <span class="font-bold ml-auto dark:text-white">${(brand.value/1000000).toFixed(1)}M</span>
                </div>`;
            });
            brandLegendEl.innerHTML = brandHTML;
        }

        // 5. Update Top Products List
        const topProdListEl = document.getElementById('topProductsList');
        if (topProdListEl) {
            if (data.top_products.length === 0) {
                topProdListEl.innerHTML = '<p class="text-on-surface-variant dark:text-neutral-400 text-center py-10 italic">Chưa có dữ liệu.</p>';
            } else {
                let prodHTML = '';
                data.top_products.forEach(p => {
                    prodHTML += `
                    <div class="flex items-center justify-between gap-2 p-3 bg-surface-container-low dark:bg-neutral-800/40 rounded-xl border border-outline-variant dark:border-neutral-800">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <img src="${p.image}" alt="${p.name}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0 bg-gray-100 dark:bg-neutral-800" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' width=\\'40\\' height=\\'40\\' viewBox=\\'0 0 40 40\\'%3E%3Crect width=\'40\' height=\'40\' rx=\'8\' fill=\'%23f3f4f6\'/%3E%3Cpath d=\\'M12 28l7-9 5 6 3-4 6 7z\\' fill=\'%23d1d5db\\'/%3E%3Ccircle cx=\'16\' cy=\'16\' r=\'3\' fill=\'%23d1d5db\'/%3E%3C/svg%3E'">
                            <div class="min-w-0">
                                <p class="font-bold text-[13px] text-primary dark:text-white truncate">${p.name}</p>
                                <p class="text-[11px] text-on-surface-variant dark:text-neutral-400">${p.price_formatted} đ</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 pl-2">
                            <p class="font-bold text-secondary dark:text-indigo-400 text-[14px]">${p.sold_count}</p>
                            <p class="text-[9px] text-on-surface-variant dark:text-neutral-500 uppercase font-bold">ĐÃ BÁN</p>
                        </div>
                    </div>`;
                });
                topProdListEl.innerHTML = prodHTML;
            }
        }

        // 6. Update Top Customers List
        const topCustListEl = document.getElementById('topCustomersList');
        if (topCustListEl) {
            if (data.top_customers.length === 0) {
                topCustListEl.innerHTML = '<p class="text-on-surface-variant dark:text-neutral-400 text-center py-10 italic">Chưa có dữ liệu.</p>';
            } else {
                let custHTML = '';
                data.top_customers.forEach(c => {
                    custHTML += `
                    <div class="flex items-center justify-between p-3 bg-blue-50/30 dark:bg-neutral-800/20 rounded-xl border border-blue-100 dark:border-neutral-800/80">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <img src="${c.avatar_url}" alt="" class="w-10 h-10 rounded-full object-cover">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-[13px] text-primary dark:text-white truncate">${c.name}</p>
                                <p class="text-[11px] text-on-surface-variant dark:text-neutral-400">${c.order_count} đơn hàng</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-secondary dark:text-indigo-400 text-[14px]">${c.total_spent_formatted} đ</p>
                            <p class="text-[9px] text-on-surface-variant dark:text-neutral-500 uppercase font-bold">TỔNG CHI</p>
                        </div>
                    </div>`;
                });
                topCustListEl.innerHTML = custHTML;
            }
        }
        
        // Trigger theme color refresh on charts (ensures they are updated to match current dark/light mode state)
        updateChartsTheme();
        
    } catch (error) {
        console.error('Error fetching dashboard statistics:', error);
        alert('Không thể tải dữ liệu thống kê mới. Vui lòng thử lại.');
    } finally {
        if (btn) {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        }
    }
}

// Quick Date Range Filter Selection
function setQuickFilter(type) {
    const today = new Date();
    let start, end;
    
    const formatLocalDate = (date) => {
        const offset = date.getTimezoneOffset();
        const localDate = new Date(date.getTime() - (offset*60*1000));
        return localDate.toISOString().split('T')[0];
    };
    
    end = formatLocalDate(today);
    
    if (type === 'today') {
        start = end;
    } else if (type === '7days') {
        const d = new Date();
        d.setDate(today.getDate() - 6);
        start = formatLocalDate(d);
    } else if (type === 'month') {
        const d = new Date(today.getFullYear(), today.getMonth(), 1);
        start = formatLocalDate(d);
    } else if (type === 'year') {
        const d = new Date(today.getFullYear(), 0, 1);
        start = formatLocalDate(d);
    }
    
    document.getElementById('startDate').value = start;
    document.getElementById('endDate').value = end;
    
    // Highlight active button style
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'dark:bg-neutral-700', 'border-outline-variant', 'dark:border-neutral-600', 'text-white');
        btn.classList.add('bg-surface-container-low', 'dark:bg-neutral-800', 'border-transparent', 'dark:text-neutral-300');
    });
    
    // Highlight clicked button
    const event = window.event;
    if (event && event.currentTarget) {
        event.currentTarget.classList.remove('bg-surface-container-low', 'dark:bg-neutral-800', 'border-transparent', 'dark:text-neutral-300');
        event.currentTarget.classList.add('bg-white', 'dark:bg-neutral-700', 'border-outline-variant', 'dark:border-neutral-600');
    }
    
    updateDashboardData();
}
</script>

</body>
</html>
