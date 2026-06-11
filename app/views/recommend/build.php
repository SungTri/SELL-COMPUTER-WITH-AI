<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="bg-surface-container-low min-h-screen pt-24 pb-12">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12">
            <div>
                <h1 class="text-4xl font-black text-primary mb-2"><?php echo __('build_pc_title', 'Build Your Dream Setup'); ?></h1>
                <p class="text-on-surface-variant"><?php echo __('build_pc_desc', 'Tự tay lựa chọn từng linh kiện để tạo nên cỗ máy hoàn hảo nhất.'); ?></p>
            </div>
            <div class="flex gap-4">
                <button id="reset-btn" class="px-6 py-3 bg-surface-container border border-outline-variant rounded-xl font-bold hover:bg-white hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">restart_alt</span> <?php echo __('reset_build', 'Làm mới'); ?>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left: Component Selection -->
            <div class="lg:col-span-8 space-y-4">
                <?php foreach($data['components'] as $comp): ?>
                <div id="comp-row-<?php echo $comp['id']; ?>" class="bg-white rounded-2xl border border-outline-variant p-6 hover:shadow-md transition-all group">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-3xl"><?php 
                                $icons = [
                                    'cpu' => 'memory',
                                    'mainboard' => 'developer_board',
                                    'ram' => 'memory_alt',
                                    'vga' => 'videogame_asset',
                                    'storage' => 'storage',
                                    'psu' => 'settings_input_component',
                                    'case' => 'desktop_windows',
                                    'cooler' => 'ac_unit',
                                    'monitor' => 'monitor'
                                ];
                                echo $icons[$comp['id']] ?? 'settings';
                            ?></span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg mb-1"><?php echo __($comp['name']); ?></h3>
                            <div id="selected-<?php echo $comp['id']; ?>" class="text-on-surface-variant text-sm">
                                <span class="opacity-50"><?php echo __('not_selected', 'Chưa chọn sản phẩm'); ?></span>
                            </div>
                        </div>
                        <button onclick="openSelector('<?php echo $comp['id']; ?>', <?php echo $comp['category_id']; ?>)" 
                                class="btn-premium px-6 py-2.5 text-xs normal-case tracking-normal rounded-xl relative">
                            <?php echo __('select_component', 'Chọn linh kiện'); ?>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Right: Summary & Total -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl border border-outline-variant p-8 sticky top-24 shadow-xl">
                    <h2 class="text-2xl font-bold mb-8"><?php echo __('config_summary', 'Tóm tắt cấu hình'); ?></h2>
                    <div id="summary-list" class="space-y-4 mb-6">
                        <div class="text-center py-10 text-on-surface-variant opacity-50 italic">
                            <?php echo __('no_components_selected', 'Chưa có linh kiện nào được chọn'); ?>
                        </div>
                    </div>

                    <!-- Compatibility Status Card -->
                    <div id="compatibility-card" class="mb-6 p-4 rounded-2xl bg-surface-container-low border border-outline-variant/40 hidden animate-in fade-in duration-300">
                        <div class="flex items-start gap-3">
                            <span id="compatibility-icon" class="material-symbols-outlined text-[24px]"></span>
                            <div class="flex-1">
                                <h4 id="compatibility-title" class="font-bold text-sm leading-tight"></h4>
                                <ul id="compatibility-messages" class="mt-2 text-[11px] text-on-surface-variant space-y-1 list-disc pl-4 leading-normal"></ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-outline-variant pt-8 space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="text-on-surface-variant font-medium"><?php echo __('grand_total_label', 'Tổng giá trị:'); ?></span>
                            <span id="grand-total" class="text-3xl font-black text-primary">0đ</span>
                        </div>
                        <button id="add-to-cart-btn" class="w-full py-4 btn-premium-orange rounded-2xl group text-base relative flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">shopping_cart_checkout</span>
                            <?php echo __('add_all_to_cart_btn', 'Thêm tất cả vào giỏ'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Product Selector Modal -->
<div id="selector-modal" class="fixed inset-0 z-[1000] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeSelector()"></div>
    <div class="bg-white w-full max-w-5xl h-[85vh] rounded-[32px] shadow-2xl relative z-10 flex flex-col overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-8 border-b border-outline-variant bg-surface-container-low flex flex-col gap-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 id="modal-title" class="text-2xl font-bold text-primary"><?php echo __('select_component', 'Chọn linh kiện'); ?></h2>
                    <p id="modal-subtitle" class="text-on-surface-variant text-sm"><?php echo __('search_ai_placeholder', 'Tìm kiếm và lọc sản phẩm phù hợp nhất'); ?></p>
                </div>
                <button onclick="closeSelector()" class="w-12 h-12 rounded-full hover:bg-surface-container flex items-center justify-center transition-all">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
                    <input type="text" id="modal-search" placeholder="<?php echo __('search_product_placeholder', 'Tìm tên sản phẩm...'); ?>" 
                           class="w-full pl-12 pr-4 py-3 bg-white border border-outline-variant rounded-xl focus:border-secondary outline-none transition-all">
                </div>
                <select id="modal-brand-filter" class="px-6 py-3 bg-white border border-outline-variant rounded-xl focus:border-secondary outline-none transition-all">
                    <option value=""><?php echo __('all_brands_option', 'Tất cả thương hiệu'); ?></option>
                </select>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto p-8" id="modal-product-list"></div>

        <div class="p-6 border-t border-outline-variant bg-surface-container-lowest text-center text-xs text-on-surface-variant">
            <?php echo __('price_warning_note', 'Lưu ý: Giá sản phẩm có thể thay đổi tùy theo thời điểm.'); ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentBuild = {};
    let allModalProducts = [];
    const components = <?php 
        $translatedComponents = $data['components'];
        foreach ($translatedComponents as &$c) {
            $c['name'] = __($c['name']);
        }
        echo json_encode($translatedComponents); 
    ?>;
    let currentCompId = '';

    // UI Elements
    const modal = document.getElementById('selector-modal');
    const modalList = document.getElementById('modal-product-list');
    const searchInput = document.getElementById('modal-search');
    const brandFilter = document.getElementById('modal-brand-filter');
    const grandTotalEl = document.getElementById('grand-total');
    const summaryList = document.getElementById('summary-list');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const resetBtn = document.getElementById('reset-btn');

    // Functions
    window.openSelector = async function(compId, categoryId) {
        currentCompId = compId;
        searchInput.value = '';
        brandFilter.value = '';
        
        const comp = components.find(c => c.id === compId);
        document.getElementById('modal-title').innerText = 'Chọn ' + comp.name;
        
        modal.classList.remove('hidden');
        modalList.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full py-20 text-on-surface-variant">
                <div class="w-12 h-12 border-4 border-secondary border-t-transparent rounded-full animate-spin mb-4"></div>
                <p><?php echo __('loading_products', 'Đang tải danh sách sản phẩm...'); ?></p>
            </div>
        `;

        try {
            const response = await fetch('<?php echo URLROOT; ?>/recommend/getProductsByCategory/' + categoryId);
            allModalProducts = await response.json();
            
            const brands = [...new Set(allModalProducts.map(p => p.brand_name))].sort();
            brandFilter.innerHTML = '<option value="">' + <?php echo json_encode(__('all_brands_option', 'Tất cả thương hiệu')); ?> + '</option>' + 
                                   brands.map(b => `<option value="${b}">${b}</option>`).join('');

            renderProducts(allModalProducts);
        } catch (error) {
            modalList.innerHTML = '<div class="text-center py-20 text-error"><?php echo __('load_data_error', 'Có lỗi xảy ra khi tải dữ liệu.'); ?></div>';
        }
    };

    window.closeSelector = function() {
        modal.classList.add('hidden');
    };

    function renderProducts(products) {
        if (products.length === 0) {
            modalList.innerHTML = '<div class="text-center py-20 opacity-50"><p><?php echo __('no_products_found_build', 'Không tìm thấy sản phẩm nào.'); ?></p></div>';
            return;
        }

        modalList.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                ${products.map(p => `
                    <div class="bg-white border border-outline-variant rounded-2xl p-4 hover:shadow-xl hover:border-secondary transition-all group flex flex-col h-full">
                        <div class="relative w-full aspect-square bg-surface-container-lowest rounded-xl mb-4 overflow-hidden p-4">
                            <img src="${p.main_image}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-sm mb-2 line-clamp-2 min-h-[40px] group-hover:text-secondary transition-colors">${p.name}</h4>
                            <p class="text-secondary text-lg font-black">${new Intl.NumberFormat('vi-VN').format(p.price)}đ</p>
                        </div>
                        <div class="flex gap-2 mt-4">
                            <a href="<?php echo URLROOT; ?>/product/detail/${p.id}" target="_blank" class="flex-1 py-2 text-xs font-bold text-outline hover:text-secondary transition-colors text-center border border-outline-variant rounded-lg"><?php echo __('details_btn', 'Chi tiết'); ?></a>
                            <button onclick='selectProduct(${JSON.stringify(p).replace(/'/g, "&apos;")})' class="flex-[2] py-2 bg-secondary text-white rounded-lg font-bold text-xs hover:bg-primary transition-all"><?php echo __('select_now_btn', 'Chọn ngay'); ?></button>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    window.selectProduct = function(product) {
        currentBuild[currentCompId] = product;
        updateUI();
        closeSelector();
    };

    window.removeComponent = function(compId) {
        delete currentBuild[compId];
        updateUI();
    };

    function getProductSpecs(p) {
        if (!p) return null;
        const name = p.name.toUpperCase();
        const shortDesc = (p.short_description || '').toUpperCase();
        const detailedDesc = (p.detailed_description || '').toUpperCase();
        const fullText = name + ' ' + shortDesc + ' ' + detailedDesc;

        let socket = null;
        let ramType = null;
        let psuWattage = null;
        let recommendedPsu = null;

        // 1. Determine Socket
        if (fullText.includes('LGA1700') || fullText.includes('LGA 1700') || fullText.includes('INTEL CPU SERIES A1') || fullText.includes('INTEL CPU SERIES B2') || fullText.includes('INTEL CPU SERIES C3') || fullText.includes('ASUS MAINBOARD SERIES A1') || fullText.includes('ASUS MAINBOARD SERIES B2') || fullText.includes('ASUS MAINBOARD SERIES C3') || fullText.includes('MSI MAINBOARD SERIES A1') || fullText.includes('MSI MAINBOARD SERIES B2') || fullText.includes('MSI MAINBOARD SERIES C3')) {
            socket = 'LGA1700';
        } else if (fullText.includes('AM5') || fullText.includes('AMD CPU SERIES') || fullText.includes('ASUS MAINBOARD SERIES D4') || fullText.includes('ASUS MAINBOARD SERIES E5') || fullText.includes('MSI MAINBOARD SERIES D4') || fullText.includes('MSI MAINBOARD SERIES E5')) {
            socket = 'AM5';
        } else if (fullText.includes('AM4') || fullText.includes('LGA1200') || fullText.includes('LGA1151')) {
            if (fullText.includes('AM4')) socket = 'AM4';
            if (fullText.includes('LGA1200')) socket = 'LGA1200';
            if (fullText.includes('LGA1151')) socket = 'LGA1151';
        }

        // 2. Determine RAM DDR Type
        if (fullText.includes('DDR5') || fullText.includes('RAM SERIES A1') || fullText.includes('RAM SERIES B2') || fullText.includes('RAM SERIES C3')) {
            ramType = 'DDR5';
        } else if (fullText.includes('DDR4') || fullText.includes('RAM SERIES D4') || fullText.includes('RAM SERIES E5')) {
            ramType = 'DDR4';
        } else {
            if (socket === 'LGA1700' || socket === 'AM5') {
                ramType = 'DDR5';
            } else {
                ramType = 'DDR4';
            }
        }

        // 3. Determine PSU Wattage
        const wattMatch = name.match(/(\d+)\s*W/);
        if (wattMatch) {
            psuWattage = parseInt(wattMatch[1]);
        } else {
            const descWattMatch = fullText.match(/(\d+)\s*W/);
            if (descWattMatch) psuWattage = parseInt(descWattMatch[1]);
        }

        // 4. Determine VGA Recommended PSU Wattage
        if (fullText.includes('RTX 4090') || fullText.includes('RX 7950') || fullText.includes('VGA SERIES A1')) {
            recommendedPsu = 850;
        } else if (fullText.includes('RTX 4080') || fullText.includes('VGA SERIES B2')) {
            recommendedPsu = 750;
        } else if (fullText.includes('RTX 4070') || fullText.includes('VGA SERIES C3') || fullText.includes('RTX 3080')) {
            recommendedPsu = 650;
        } else if (fullText.includes('RTX 4060') || fullText.includes('VGA SERIES D4') || fullText.includes('VGA SERIES E5') || fullText.includes('RTX 3060')) {
            recommendedPsu = 550;
        } else {
            if (p.category_id === 7) recommendedPsu = 500;
        }

        return { socket, ramType, psuWattage, recommendedPsu };
    }

    function checkCompatibility() {
        const cpu = currentBuild['cpu'];
        const mainboard = currentBuild['mainboard'];
        const ram = currentBuild['ram'];
        const vga = currentBuild['vga'];
        const psu = currentBuild['psu'];

        const cpuSpecs = cpu ? getProductSpecs(cpu) : null;
        const mainboardSpecs = mainboard ? getProductSpecs(mainboard) : null;
        const ramSpecs = ram ? getProductSpecs(ram) : null;
        const vgaSpecs = vga ? getProductSpecs(vga) : null;
        const psuSpecs = psu ? getProductSpecs(psu) : null;

        const warnings = [];
        let hasConflict = false;

        // 1. CPU vs Mainboard Socket Check
        if (cpuSpecs && mainboardSpecs) {
            if (cpuSpecs.socket && mainboardSpecs.socket && cpuSpecs.socket !== mainboardSpecs.socket) {
                warnings.push(`<strong>CPU & Mainboard lệch socket:</strong> CPU dùng <strong>${cpuSpecs.socket}</strong> nhưng Mainboard dùng <strong>${mainboardSpecs.socket}</strong>.`);
                hasConflict = true;
            }
        }

        // 2. Mainboard vs RAM Type Check
        if (mainboardSpecs && ramSpecs) {
            if (mainboardSpecs.ramType && ramSpecs.ramType && mainboardSpecs.ramType !== ramSpecs.ramType) {
                warnings.push(`<strong>Lệch chuẩn RAM:</strong> Mainboard hỗ trợ <strong>${mainboardSpecs.ramType}</strong> nhưng RAM chọn là <strong>${ramSpecs.ramType}</strong>.`);
                hasConflict = true;
            }
        }

        // 3. VGA vs PSU Wattage Check
        if (vgaSpecs && psuSpecs) {
            if (vgaSpecs.recommendedPsu && psuSpecs.psuWattage && psuSpecs.psuWattage < vgaSpecs.recommendedPsu) {
                warnings.push(`<strong>Nguồn yếu (PSU):</strong> VGA khuyến nghị nguồn tối thiểu <strong>${vgaSpecs.recommendedPsu}W</strong> nhưng PSU chọn là <strong>${psuSpecs.psuWattage}W</strong>.`);
                hasConflict = true;
            }
        }

        const compCard = document.getElementById('compatibility-card');
        const compIcon = document.getElementById('compatibility-icon');
        const compTitle = document.getElementById('compatibility-title');
        const compMessages = document.getElementById('compatibility-messages');

        const activeChecksCount = (cpu && mainboard ? 1 : 0) + (mainboard && ram ? 1 : 0) + (vga && psu ? 1 : 0);

        if (activeChecksCount === 0) {
            compCard.classList.add('hidden');
            return false;
        }

        compCard.classList.remove('hidden');
        compMessages.innerHTML = '';

        if (hasConflict) {
            compCard.className = 'mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 animate-in fade-in duration-300';
            compIcon.className = 'material-symbols-outlined text-[24px] text-amber-600';
            compIcon.textContent = 'warning';
            compTitle.textContent = 'Xung đột tương thích!';
            
            warnings.forEach(msg => {
                const li = document.createElement('li');
                li.innerHTML = msg;
                compMessages.appendChild(li);
            });
            return true;
        } else {
            compCard.className = 'mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-950 animate-in fade-in duration-300';
            compIcon.className = 'material-symbols-outlined text-[24px] text-green-600';
            compIcon.textContent = 'check_circle';
            compTitle.textContent = 'Cấu hình tương thích tốt!';
            
            const li = document.createElement('li');
            li.textContent = 'Tất cả linh kiện đã chọn tương thích và hoạt động ổn định với nhau.';
            compMessages.appendChild(li);
            return false;
        }
    }

    function updateUI() {
        let total = 0;
        summaryList.innerHTML = '';

        components.forEach(comp => {
            const product = currentBuild[comp.id];
            const display = document.getElementById('selected-' + comp.id);
            
            if (product) {
                total += parseFloat(product.price);
                display.innerHTML = `
                    <div class="flex items-center gap-2 text-primary font-bold">
                        <span class="line-clamp-1">${product.name}</span>
                        <button onclick="removeComponent('${comp.id}')" class="text-outline hover:text-error transition-colors" title="<?php echo __('remove_btn', 'Xóa'); ?>">
                            <span class="material-symbols-outlined text-lg">cancel</span>
                        </button>
                    </div>
                `;
                summaryList.innerHTML += `
                    <div class="flex justify-between items-start gap-4 text-sm p-3 bg-surface-container-lowest rounded-xl border border-outline-variant">
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-outline uppercase mb-1">${comp.name}</p>
                            <p class="font-medium text-on-surface line-clamp-2">${product.name}</p>
                        </div>
                        <span class="font-black text-secondary shrink-0">${new Intl.NumberFormat('vi-VN').format(product.price)}đ</span>
                    </div>
                `;
            } else {
                display.innerHTML = '<span class="opacity-50"><?php echo __('not_selected', 'Chưa chọn sản phẩm'); ?></span>';
            }
        });

        if (Object.keys(currentBuild).length === 0) {
            summaryList.innerHTML = '<div class="text-center py-10 opacity-30 italic"><p><?php echo __('no_components_selected', 'Chưa có linh kiện nào được chọn'); ?></p></div>';
        }
        grandTotalEl.innerText = new Intl.NumberFormat('vi-VN').format(total) + 'đ';

        checkCompatibility();
    }

    // Event Listeners
    searchInput.oninput = () => {
        const term = searchInput.value.toLowerCase();
        const brand = brandFilter.value;
        const filtered = allModalProducts.filter(p => p.name.toLowerCase().includes(term) && (brand === '' || p.brand_name === brand));
        renderProducts(filtered);
    };

    brandFilter.onchange = searchInput.oninput;

    resetBtn.onclick = () => {
        if (confirm(<?php echo json_encode(__('confirm_clear_build', 'Bạn có chắc muốn xóa toàn bộ cấu hình?')); ?>)) {
            currentBuild = {};
            updateUI();
        }
    };

    addToCartBtn.onclick = async () => {
        const ids = Object.values(currentBuild).map(p => p.id);
        if (ids.length === 0) {
            showToast('<?php echo __('select_at_least_one', 'Vui lòng chọn ít nhất một linh kiện!'); ?>', 'error');
            return;
        }

        const hasConflicts = checkCompatibility();
        if (hasConflicts) {
            const proceed = confirm('Cấu hình linh kiện PC hiện tại có xung đột tương thích. Bạn có chắc chắn vẫn muốn thêm tất cả các sản phẩm này vào giỏ hàng không?');
            if (!proceed) return;
        }

        addToCartBtn.disabled = true;
        const originalHtml = addToCartBtn.innerHTML;
        addToCartBtn.innerHTML = '<?php echo __('processing', 'Đang xử lý...'); ?>';

        try {
            const response = await fetch('<?php echo URLROOT; ?>/cart/bulkAdd', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_ids: ids })
            });
            const res = await response.json();
            if (res.status === 'success') {
                showToast(res.message);
                setTimeout(() => window.location.href = '<?php echo URLROOT; ?>/cart', 800);
            } else {
                showToast(res.message, 'error');
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = originalHtml;
            }
        } catch (e) {
            showToast('<?php echo __('error_occurred', 'Có lỗi xảy ra!'); ?>', 'error');
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = originalHtml;
        }
    };

    function showToast(msg, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-8 left-1/2 -translate-x-1/2 px-8 py-4 rounded-2xl text-white font-bold shadow-2xl z-[2000] ${type === 'success' ? 'bg-secondary' : 'bg-error'}`;
        toast.innerText = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
});
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
