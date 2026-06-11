<?php require APPROOT . '/views/layout/header.php'; ?>

<!-- Page Wrapper -->
<div id="mainConfiguratorLayout" class="max-w-container-max mx-auto py-12 px-4 sm:px-6 lg:px-8 mt-6">
    <!-- Premium Header Banner -->
    <div class="relative rounded-3xl overflow-hidden mb-12 shadow-xl border border-outline-variant/20">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/95 via-primary/80 to-secondary/80 z-10"></div>
        <div class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] bg-[size:24px_24px] z-0"></div>
        
        <div class="relative z-20 py-16 px-8 md:px-16 text-center md:text-left max-w-3xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-secondary/20 text-secondary-container font-semibold text-xs uppercase tracking-wider mb-4 border border-secondary/30">
                <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-pulse"></span>
                PC Configurator
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight leading-none">
                Tự Dựng Cấu Hình PC
            </h1>
            <p class="text-sm md:text-base text-white/80 leading-relaxed max-w-xl">
                Chọn lựa linh kiện bạn muốn, hệ thống sẽ tự động kiểm tra độ tương thích phần cứng và đề xuất cấu hình tối ưu.
            </p>
        </div>
    </div>

    <!-- Main Configurator Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Configuration Slots (Width 2/3) -->
        <div class="lg:col-span-2 space-y-6" id="slotsContainer">
            <!-- Slots will populate here dynamically -->
        </div>

        <!-- Right: Config summary panel (Width 1/3) -->
        <div class="lg:col-span-1 sticky top-24">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-outline-variant/30 dark:border-outline-variant/10 shadow-lg overflow-hidden p-6 space-y-6">
                <h3 class="text-lg font-black text-primary border-b border-outline-variant/20 pb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">desktop_windows</span>
                    Tóm Tắt Cấu Hình
                </h3>

                <!-- Selected Parts Checklist -->
                <div class="space-y-3" id="summaryChecklist">
                    <!-- Dynamic checklist -->
                </div>

                <!-- Compatibility Badge -->
                <div id="compatibilityStatus" class="p-4 rounded-2xl border transition-all duration-300">
                    <!-- Status text -->
                </div>

                <!-- Price and Checkout -->
                <div class="pt-4 border-t border-outline-variant/20 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-on-surface-variant">Tổng chi phí dự tính:</span>
                        <span class="text-2xl font-black text-secondary" id="totalPrice">0 VNĐ</span>
                    </div>

                    <button onclick="addAllToCart()" id="btnCheckout" disabled class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-black py-4 rounded-xl shadow-lg hover:shadow-blue-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer border-0 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined">shopping_cart_checkout</span>
                        THÊM CẤU HÌNH VÀO GIỎ HÀNG
                    </button>

                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="printQuote()" class="py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-on-surface-variant font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all border-0 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">print</span>
                            In báo giá
                        </button>
                        <button onclick="clearConfiguration()" class="py-2.5 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/40 text-red-600 dark:text-red-400 font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all border-0 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">delete</span>
                            Xóa hết
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modular Parts Selection Modal -->
<div id="selectorModal" class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div onclick="closeSelectorModal()" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
    
    <!-- Modal Card -->
    <div class="relative bg-white dark:bg-zinc-900 w-full max-w-4xl h-[85vh] max-h-[800px] rounded-3xl shadow-2xl border border-outline-variant/30 dark:border-outline-variant/10 flex flex-col overflow-hidden z-10 mx-4 transform transition-all translate-y-4 opacity-0 scale-95 duration-300" id="modalCard">
        <!-- Modal Header -->
        <div class="p-6 border-b border-outline-variant/20 dark:border-outline-variant/10 flex items-center justify-between bg-slate-50 dark:bg-zinc-950/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center shadow-md" id="modalCategoryIcon">
                    <span class="material-symbols-outlined">memory</span>
                </div>
                <div class="flex flex-col">
                    <h3 class="font-black text-primary text-lg" id="modalTitle">Chọn linh kiện</h3>
                    <p class="text-xs text-on-surface-variant" id="modalSub">Vui lòng chọn sản phẩm phù hợp</p>
                </div>
            </div>
            <button onclick="closeSelectorModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-full transition-all text-on-surface-variant flex items-center justify-center border-0 cursor-pointer">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Filter Bar -->
        <div class="p-4 border-b border-outline-variant/15 flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input type="text" id="partSearch" oninput="debounceSearch()" placeholder="Tìm kiếm linh kiện..." class="w-full pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800/40 border border-gray-200 dark:border-zinc-700/60 rounded-xl text-sm outline-none focus:border-primary transition-all"/>
            </div>
            <div class="w-full md:w-48">
                <select id="brandFilter" onchange="loadCategoryProducts()" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800/40 border border-gray-200 dark:border-zinc-700/60 rounded-xl text-sm outline-none focus:border-primary transition-all text-on-surface-variant">
                    <option value="">Tất cả thương hiệu</option>
                </select>
            </div>
        </div>

        <!-- Products List Area -->
        <div id="modalProducts" class="flex-1 overflow-y-auto p-6 divide-y divide-outline-variant/10 bg-slate-50/20 dark:bg-zinc-950/20">
            <!-- Dynamic products populate here -->
        </div>
    </div>
</div>

<!-- Print-only View Layout Stylesheet -->
<style>
    @media print {
        /* Hide all main layout parts entirely to avoid empty spaces/pages */
        header, footer, #mainConfiguratorLayout, #compare-tray, #chatbot-bubble, #chatbot-window {
            display: none !important;
        }
        
        /* Reset body background for printing */
        body {
            background: white !important;
            color: black !important;
        }

        /* Show the print quote container and override Tailwind's display:none (.hidden) */
        #printQuoteSection {
            display: block !important;
            visibility: visible !important;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white !important;
            color: black !important;
        }
        #printQuoteSection * {
            visibility: visible !important;
            color: black !important;
        }
    }
</style>

<!-- Hidden print configuration details container -->
<div id="printQuoteSection" class="hidden p-10 bg-white text-black font-sans">
    <div style="border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="margin: 0; font-size: 28px; font-weight: bold; color: #0453cd;">TechExpert Store</h1>
            <p style="margin: 5px 0 0 0; font-size: 12px; color: #555;">Địa chỉ: 123 Đường Ba Tháng Hai, Phường 11, Quận 10, TP. Hồ Chí Minh</p>
            <p style="margin: 2px 0 0 0; font-size: 12px; color: #555;">Hotline: 1900-8888 | Email: contact@techexpert.vn</p>
        </div>
        <div style="text-align: right;">
            <h2 style="margin: 0; font-size: 20px; font-weight: bold;">BẢNG BÁO GIÁ CẤU HÌNH PC</h2>
            <p style="margin: 5px 0 0 0; font-size: 11px;" id="printDate">Ngày tạo: --/--/----</p>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <thead>
            <tr style="background-color: #f1f3f5; border-bottom: 1px solid #ddd; text-align: left;">
                <th style="padding: 10px; font-size: 13px;">STT</th>
                <th style="padding: 10px; font-size: 13px;">Loại linh kiện</th>
                <th style="padding: 10px; font-size: 13px;">Tên sản phẩm</th>
                <th style="padding: 10px; font-size: 13px; text-align: right;">Đơn giá</th>
                <th style="padding: 10px; font-size: 13px; text-align: center;">SL</th>
                <th style="padding: 10px; font-size: 13px; text-align: right;">Thành tiền</th>
            </tr>
        </thead>
        <tbody id="printTableBody">
            <!-- Populated via JS -->
        </tbody>
    </table>

    <div style="text-align: right; border-top: 1px solid #000; padding-top: 15px;">
        <h3 style="margin: 0; font-size: 18px; font-weight: bold;">Tổng tiền thanh toán: <span id="printTotal" style="color: #0453cd;">0 VNĐ</span></h3>
        <p style="margin: 5px 0 0 0; font-size: 11px; font-style: italic;">* Giá trên đã bao gồm VAT và chi phí bảo hành chính hãng theo từng linh kiện.</p>
    </div>
</div>

<script>
    // Configuration Slots Metadata
    const SLOTS = [
        { key: 'cpu', name: 'Bộ vi xử lý (CPU)', categoryId: 5, icon: 'memory' },
        { key: 'mb', name: 'Bo mạch chủ (Mainboard)', categoryId: 9, icon: 'developer_board' },
        { key: 'ram', name: 'Bộ nhớ trong (RAM)', categoryId: 6, icon: 'hardware', multiQty: true },
        { key: 'vga', name: 'Card màn hình (VGA)', categoryId: 7, icon: 'layers' },
        { key: 'ssd', name: 'Ổ cứng SSD/HDD', categoryId: 10, icon: 'storage', multiQty: true },
        { key: 'psu', name: 'Nguồn máy tính (PSU)', categoryId: 13, icon: 'power' },
        { key: 'case', name: 'Vỏ máy tính (Case)', categoryId: 12, icon: 'inventory_2' },
        { key: 'cooler', name: 'Tản nhiệt CPU (Cooler)', categoryId: 11, icon: 'toys' }
    ];

    // Current State
    let configState = {
        cpu: null,
        mb: null,
        ram: null,
        vga: null,
        ssd: null,
        psu: null,
        case: null,
        cooler: null
    };

    let activeSelectKey = null;
    let searchDebounceTimer = null;

    // Initialize Page
    window.addEventListener('DOMContentLoaded', () => {
        // Load existing configuration from localStorage if present
        const savedConfig = localStorage.getItem('pc_builder_config');
        if (savedConfig) {
            try {
                configState = JSON.parse(savedConfig);
            } catch (e) {
                console.error("Error loading config:", e);
            }
        }
        renderSlots();
        updateSummary();
    });

    // Render configuration slots in DOM
    function renderSlots() {
        const container = document.getElementById('slotsContainer');
        if (!container) return;

        container.innerHTML = SLOTS.map((s, idx) => {
            const item = configState[s.key];
            const isSelected = item !== null;
            
            let html = '';
            if (isSelected) {
                const subtotal = item.price * (item.quantity || 1);
                const subtotalFormatted = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(subtotal).replace('₫', 'VNĐ');
                const qtySelector = s.multiQty 
                    ? `
                        <div class="flex items-center border border-gray-200 dark:border-zinc-700 rounded-xl overflow-hidden bg-white dark:bg-zinc-800">
                            <button onclick="changeQty('${s.key}', -1)" class="w-9 h-9 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-zinc-700 font-bold border-0 cursor-pointer text-sm">-</button>
                            <span class="px-3 text-xs font-bold text-gray-800 dark:text-zinc-200">${item.quantity || 1}</span>
                            <button onclick="changeQty('${s.key}', 1)" class="w-9 h-9 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-zinc-700 font-bold border-0 cursor-pointer text-sm">+</button>
                        </div>
                    ` 
                    : '';

                html = `
                    <div class="bg-white dark:bg-zinc-900 border border-outline-variant/30 dark:border-outline-variant/10 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row items-center gap-5 group/card">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-zinc-800 border border-outline-variant/20 dark:border-outline-variant/10 rounded-xl flex items-center justify-center p-1 shrink-0 overflow-hidden">
                            <img src="${item.image}" class="w-full h-full object-contain group-hover/card:scale-105 transition-transform" onerror="this.src='https://placehold.co/100x100?text=Part'">
                        </div>
                        <div class="flex-1 min-w-0 text-center md:text-left">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">${s.name}</span>
                            <span class="text-sm font-bold text-primary block truncate mt-1">${item.name}</span>
                            <span class="text-[11px] font-semibold text-on-surface-variant block mt-0.5">${item.brand_name} | Bảo hành chính hãng</span>
                        </div>
                        <div class="flex flex-col md:flex-row items-center gap-4 shrink-0 mt-4 md:mt-0">
                            ${qtySelector}
                            <div class="text-right">
                                <span class="text-[11px] text-gray-400 block font-medium">Thành tiền</span>
                                <span class="text-sm font-black text-secondary">${subtotalFormatted}</span>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="openSelectorModal('${s.key}')" class="px-3.5 py-2 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:text-primary transition-all cursor-pointer">Thay đổi</button>
                                <button onclick="removePart('${s.key}')" class="w-9 h-9 bg-red-50 hover:bg-red-100 text-red-500 rounded-xl flex items-center justify-center transition-all border-0 cursor-pointer" title="Gỡ bỏ">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                html = `
                    <div class="bg-white dark:bg-zinc-900 border-2 border-dashed border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm hover:border-primary/50 transition-all flex flex-col md:flex-row items-center justify-between gap-5">
                        <div class="flex items-center gap-4 text-center md:text-left flex-col md:flex-row">
                            <div class="w-12 h-12 bg-blue-50 dark:bg-zinc-800 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">${s.icon}</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-primary text-sm">${s.name}</h4>
                                <p class="text-xs text-on-surface-variant mt-0.5">Vui lòng chọn bộ phận cấu hình này</p>
                            </div>
                        </div>
                        <button onclick="openSelectorModal('${s.key}')" class="px-6 py-2.5 bg-primary text-white hover:bg-secondary text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-1.5 border-0 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span>
                            Chọn linh kiện
                        </button>
                    </div>
                `;
            }

            return html;
        }).join('');
    }

    // Change Qty for RAM / SSD
    function changeQty(key, offset) {
        if (!configState[key]) return;
        let qty = (configState[key].quantity || 1) + offset;
        if (qty < 1) qty = 1;
        if (qty > 4) qty = 4; // cap at 4 slots
        configState[key].quantity = qty;
        
        saveState();
        renderSlots();
        updateSummary();
    }

    // Remove selected item
    function removePart(key) {
        configState[key] = null;
        saveState();
        renderSlots();
        updateSummary();
    }

    // Reset Configuration
    function clearConfiguration() {
        if (!confirm("Bạn có chắc chắn muốn xóa toàn bộ linh kiện đã chọn?")) return;
        SLOTS.forEach(s => configState[s.key] = null);
        saveState();
        renderSlots();
        updateSummary();
    }

    // Save State to LocalStorage
    function saveState() {
        localStorage.setItem('pc_builder_config', JSON.stringify(configState));
    }

    // Update prices, checklist, and hardware compatibility checks
    function updateSummary() {
        let totalPrice = 0;
        let selectedCount = 0;
        const checklist = document.getElementById('summaryChecklist');
        const totalPriceEl = document.getElementById('totalPrice');
        const btnCheckout = document.getElementById('btnCheckout');
        
        const checklistHtml = SLOTS.map(s => {
            const item = configState[s.key];
            if (item) {
                totalPrice += item.price * (item.quantity || 1);
                selectedCount++;
                return `
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500 font-medium">${s.name}:</span>
                        <span class="text-primary font-bold truncate max-w-[200px]" title="${item.name}">${item.name}</span>
                    </div>
                `;
            } else {
                return `
                    <div class="flex justify-between items-center text-xs opacity-50">
                        <span class="text-gray-400 font-medium">${s.name}:</span>
                        <span class="text-gray-400 italic font-medium">Chưa chọn</span>
                    </div>
                `;
            }
        }).join('');
        
        checklist.innerHTML = checklistHtml;
        totalPriceEl.innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalPrice).replace('₫', 'VNĐ');
        
        // Checkout state validation (require at least one component selected to allow checkout)
        const hasSelectedParts = Object.values(configState).some(item => item !== null);
        if (hasSelectedParts) {
            btnCheckout.removeAttribute('disabled');
            btnCheckout.disabled = false;
        } else {
            btnCheckout.setAttribute('disabled', 'true');
            btnCheckout.disabled = true;
        }
        
        // Compatibility checks
        runCompatibilityAudit();
    }

    // Heuristics Compatibility Audit
    function runCompatibilityAudit() {
        const statusBox = document.getElementById('compatibilityStatus');
        if (!statusBox) return;

        let selectedKeys = Object.keys(configState).filter(k => configState[k] !== null);
        if (selectedKeys.length === 0) {
            statusBox.className = "p-4 rounded-2xl border border-gray-200 bg-gray-50 text-gray-500 text-xs font-semibold flex items-center gap-2";
            statusBox.innerHTML = `
                <span class="material-symbols-outlined text-[18px]">info</span>
                Chưa chọn linh kiện nào.
            `;
            return;
        }

        let warnings = [];

        // 1. Socket Check (CPU vs Mainboard)
        if (configState.cpu && configState.mb) {
            const cpuName = configState.cpu.name.toLowerCase();
            const cpuDesc = configState.cpu.short_description.toLowerCase();
            const mbName = configState.mb.name.toLowerCase();
            const mbDesc = configState.mb.short_description.toLowerCase();

            // Guess CPU Socket
            let cpuSocket = null;
            if (cpuName.includes('lga1700') || cpuDesc.includes('lga1700') || cpuName.includes('lga 1700') || /i[3579]-(12|13|14)/.test(cpuName)) {
                cpuSocket = 'LGA1700';
            } else if (cpuName.includes('am5') || cpuDesc.includes('am5') || /ryzen [3579] (7|8|9)/.test(cpuName)) {
                cpuSocket = 'AM5';
            } else if (cpuName.includes('am4') || cpuDesc.includes('am4') || /ryzen [3579] (3|4|5)/.test(cpuName)) {
                cpuSocket = 'AM4';
            } else if (cpuName.includes('lga1200') || cpuDesc.includes('lga1200') || /i[3579]-(10|11)/.test(cpuName)) {
                cpuSocket = 'LGA1200';
            }

            // Guess MB Socket
            let mbSocket = null;
            if (mbName.includes('h610') || mbName.includes('b760') || mbName.includes('z790') || mbName.includes('lga1700') || mbDesc.includes('lga1700')) {
                mbSocket = 'LGA1700';
            } else if (mbName.includes('a620') || mbName.includes('b650') || mbName.includes('x670') || mbName.includes('am5') || mbDesc.includes('am5')) {
                mbSocket = 'AM5';
            } else if (mbName.includes('b450') || mbName.includes('b550') || mbName.includes('x570') || mbName.includes('a320') || mbName.includes('a520') || mbName.includes('am4') || mbDesc.includes('am4')) {
                mbSocket = 'AM4';
            } else if (mbName.includes('h410') || mbName.includes('h510') || mbName.includes('b460') || mbName.includes('b560') || mbName.includes('z490') || mbName.includes('z590') || mbName.includes('lga1200') || mbDesc.includes('lga1200')) {
                mbSocket = 'LGA1200';
            }

            if (cpuSocket && mbSocket && cpuSocket !== mbSocket) {
                warnings.push(`Vi xử lý (CPU) và Bo mạch chủ (Mainboard) không tương thích socket (CPU: Socket ${cpuSocket}, Mainboard: Socket ${mbSocket}).`);
            }
        }

        // 2. RAM Standard Check (Mainboard vs RAM)
        if (configState.mb && configState.ram) {
            const mbName = configState.mb.name.toLowerCase();
            const mbDesc = configState.mb.short_description.toLowerCase();
            const ramName = configState.ram.name.toLowerCase();
            const ramDesc = configState.ram.short_description.toLowerCase();

            let mbRamType = mbName.includes('ddr5') || mbDesc.includes('ddr5') ? 'DDR5' : 'DDR4';
            let ramType = ramName.includes('ddr5') || ramDesc.includes('ddr5') ? 'DDR5' : 'DDR4';

            if (mbRamType !== ramType) {
                warnings.push(`RAM và Mainboard không tương thích khe cắm bộ nhớ (RAM: ${ramType}, Bo mạch chủ yêu cầu: ${mbRamType}).`);
            }
        }

        // 3. Power Supply Check (VGA vs PSU Wattage)
        if (configState.vga && configState.psu) {
            const vgaName = configState.vga.name.toLowerCase();
            const psuName = configState.psu.name.toLowerCase();

            // Extract PSU Wattage
            let psuWatt = 500;
            const wattMatch = psuName.match(/(\d+)\s*w/);
            if (wattMatch) {
                psuWatt = parseInt(wattMatch[1]);
            }

            // VGA Recommended Wattage
            let reqWatt = 500;
            if (vgaName.includes('4090') || vgaName.includes('7900 xtx') || vgaName.includes('3090 ti')) {
                reqWatt = 850;
            } else if (vgaName.includes('4080') || vgaName.includes('7900 xt') || vgaName.includes('3080 ti') || vgaName.includes('3090')) {
                reqWatt = 750;
            } else if (vgaName.includes('4070') || vgaName.includes('3070 ti') || vgaName.includes('3080') || vgaName.includes('7800') || vgaName.includes('6800')) {
                reqWatt = 650;
            } else if (vgaName.includes('4060') || vgaName.includes('3060') || vgaName.includes('3070') || vgaName.includes('7600') || vgaName.includes('6700')) {
                reqWatt = 550;
            }

            if (psuWatt < reqWatt) {
                warnings.push(`Bộ nguồn PSU (${psuWatt}W) có công suất thấp hơn yêu cầu tối thiểu khuyên dùng cho card đồ họa VGA này (Yêu cầu khuyến nghị: ${reqWatt}W).`);
            }
        }

        // Render warnings
        if (warnings.length > 0) {
            statusBox.className = "p-4 rounded-2xl border border-yellow-200 bg-yellow-50 text-yellow-800 text-xs font-semibold flex flex-col gap-2";
            statusBox.innerHTML = `
                <div class="flex items-center gap-2 font-black border-b border-yellow-200 pb-1.5">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    Cảnh Báo Tương Thích Phần Cứng
                </div>
                <ul class="list-disc list-inside space-y-1 font-medium leading-relaxed">
                    ${warnings.map(w => `<li>${w}</li>`).join('')}
                </ul>
            `;
        } else {
            statusBox.className = "p-4 rounded-2xl border border-green-200 bg-green-50 text-green-700 text-xs font-semibold flex items-center gap-2";
            statusBox.innerHTML = `
                <span class="material-symbols-outlined text-[18px]">verified</span>
                Cấu hình hoàn toàn tương thích! Sẵn sàng mua hàng.
            `;
        }
    }

    // Modal Operations
    function openSelectorModal(key) {
        activeSelectKey = key;
        const slot = SLOTS.find(s => s.key === key);
        if (!slot) return;

        // Reset filter search inputs
        document.getElementById('partSearch').value = '';
        document.getElementById('brandFilter').value = '';

        // Configure Modal Details
        document.getElementById('modalTitle').innerText = `Chọn ${slot.name}`;
        
        // Show Modal
        const modal = document.getElementById('selectorModal');
        const modalCard = document.getElementById('modalCard');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalCard.classList.remove('translate-y-4', 'opacity-0', 'scale-95');
        }, 10);

        // Fetch products via AJAX
        loadCategoryProducts();
    }

    function closeSelectorModal() {
        const modal = document.getElementById('selectorModal');
        const modalCard = document.getElementById('modalCard');
        modalCard.classList.add('translate-y-4', 'opacity-0', 'scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Debounce product searches
    function debounceSearch() {
        if (searchDebounceTimer) clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(loadCategoryProducts, 400);
    }

    // Fetch and render products in selector modal
    async function loadCategoryProducts() {
        const slot = SLOTS.find(s => s.key === activeSelectKey);
        if (!slot) return;

        const search = document.getElementById('partSearch').value;
        const brandId = document.getElementById('brandFilter').value;
        const productsContainer = document.getElementById('modalProducts');

        productsContainer.innerHTML = `
            <div class="p-10 text-center text-on-surface-variant text-sm italic">
                Đang tải danh sách linh kiện...
            </div>
        `;

        try {
            const url = `<?php echo URLROOT; ?>/buildpc/getProductsByCategory?category_id=${slot.categoryId}&search=${encodeURIComponent(search)}&brand_id=${brandId}`;
            const response = await fetch(url);
            const data = await response.json();

            if (data.status === 'success') {
                // Populate brand filters
                const brandSelect = document.getElementById('brandFilter');
                const savedBrandVal = brandSelect.value;
                brandSelect.innerHTML = '<option value="">Tất cả thương hiệu</option>' + 
                    data.brands.map(b => `<option value="${b.id}" ${b.id == savedBrandVal ? 'selected' : ''}>${b.name}</option>`).join('');

                const products = data.products;
                if (products.length === 0) {
                    productsContainer.innerHTML = `
                        <div class="p-10 text-center text-on-surface-variant text-sm font-semibold">
                            Không tìm thấy linh kiện nào khớp với tìm kiếm.
                        </div>
                    `;
                    return;
                }

                productsContainer.innerHTML = products.map(p => `
                    <div class="py-4 flex gap-4 items-center hover:bg-slate-50/50 dark:hover:bg-zinc-800/10 px-4 rounded-xl transition-all">
                        <div class="w-14 h-14 bg-white dark:bg-zinc-800 border border-outline-variant/20 rounded-lg p-1 shrink-0 overflow-hidden">
                            <img src="${p.image}" class="w-full h-full object-contain" onerror="this.src='https://placehold.co/100x100?text=PC+Part'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-bold text-primary truncate leading-tight">${p.name}</h4>
                            <p class="text-[10px] text-gray-400 mt-1 font-semibold uppercase tracking-wider">${p.brand_name} | Kho hàng: ${p.stock > 0 ? p.stock + ' sản phẩm' : 'Hết hàng'}</p>
                            <p class="text-[11px] text-on-surface-variant line-clamp-1 mt-1 font-medium">${p.short_description}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-sm font-black text-secondary block">${p.price_formatted}</span>
                            <button onclick="selectProductForSlot(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.price}, '${p.image}', '${p.brand_name.replace(/'/g, "\\'")}', '${p.short_description.replace(/'/g, "\\'")}')" 
                                ${p.stock <= 0 ? 'disabled' : ''} 
                                class="mt-2 px-4 py-1.5 bg-primary text-white hover:bg-secondary disabled:bg-gray-300 disabled:cursor-not-allowed font-bold text-[10px] rounded-lg border-0 cursor-pointer transition-all shadow-sm">
                                ${p.stock > 0 ? 'CHỌN' : 'HẾT HÀNG'}
                            </button>
                        </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error("Error loading products:", error);
            productsContainer.innerHTML = `<div class="p-10 text-center text-red-500 font-bold">Lỗi kết nối máy chủ.</div>`;
        }
    }

    // Bind product to active configurator slot
    function selectProductForSlot(id, name, price, image, brandName, shortDescription) {
        configState[activeSelectKey] = {
            id: id,
            name: name,
            price: price,
            image: image,
            brand_name: brandName,
            short_description: shortDescription,
            quantity: 1
        };

        saveState();
        renderSlots();
        updateSummary();
        closeSelectorModal();
        showToast("Đã thêm linh kiện vào cấu hình", "success");
    }

    // Checkout: add all configuration items to cart via bulk API
    async function addAllToCart() {
        const productIds = [];
        SLOTS.forEach(s => {
            const item = configState[s.key];
            if (item) {
                // Add product ID based on its selected quantity
                const qty = item.quantity || 1;
                for (let i = 0; i < qty; i++) {
                    productIds.push(item.id);
                }
            }
        });

        if (productIds.length === 0) return;

        try {
            showToast("Đang chuẩn bị thêm cấu hình vào giỏ hàng...", "success");
            const response = await fetch('<?php echo URLROOT; ?>/cart/bulkAdd', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_ids: productIds })
            });
            const data = await response.json();
            if (data.status === 'success') {
                showToast("Đã thêm toàn bộ cấu hình PC thành công!", "success");
                setTimeout(() => {
                    window.location.href = '<?php echo URLROOT; ?>/cart';
                }, 1000);
            } else {
                showToast(data.message || "Lỗi khi thêm cấu hình", "error");
            }
        } catch (error) {
            console.error("Error checkout:", error);
            showToast("Lỗi kết nối máy chủ", "error");
        }
    }

    // Print Quote Setup
    function printQuote() {
        const printBody = document.getElementById('printTableBody');
        const printTotal = document.getElementById('printTotal');
        const printDate = document.getElementById('printDate');
        
        let total = 0;
        let count = 1;
        let rowsHtml = '';

        SLOTS.forEach(s => {
            const item = configState[s.key];
            if (item) {
                const subtotal = item.price * (item.quantity || 1);
                total += subtotal;
                const priceFormatted = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.price).replace('₫', 'VNĐ');
                const subtotalFormatted = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(subtotal).replace('₫', 'VNĐ');
                
                rowsHtml += `
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px; font-size: 12px;">${count++}</td>
                        <td style="padding: 10px; font-size: 12px; font-weight: bold; color: #555;">${s.name}</td>
                        <td style="padding: 10px; font-size: 12px;">${item.name}</td>
                        <td style="padding: 10px; font-size: 12px; text-align: right;">${priceFormatted}</td>
                        <td style="padding: 10px; font-size: 12px; text-align: center;">${item.quantity || 1}</td>
                        <td style="padding: 10px; font-size: 12px; text-align: right; font-weight: bold;">${subtotalFormatted}</td>
                    </tr>
                `;
            }
        });

        if (count === 1) {
            alert("Vui lòng chọn ít nhất một linh kiện để xuất báo giá.");
            return;
        }

        printBody.innerHTML = rowsHtml;
        printTotal.innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total).replace('₫', 'VNĐ');
        
        const now = new Date();
        printDate.innerText = `Ngày tạo: ${now.getDate().toString().padStart(2, '0')}/${(now.getMonth()+1).toString().padStart(2, '0')}/${now.getFullYear()} ${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;

        // Trigger browser print dialog
        window.print();
    }
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
