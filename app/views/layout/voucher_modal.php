<!-- Voucher Modal Partial -->
<div id="voucher-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 border border-slate-200 dark:border-zinc-800" id="voucher-modal-content">
        <div class="p-6 border-b border-slate-100 dark:border-zinc-800/80 flex justify-between items-center bg-slate-50/50 dark:bg-zinc-900/60">
            <h3 class="text-lg font-bold text-slate-800 dark:text-zinc-100 flex items-center gap-2 font-sans">
                <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">confirmation_number</span>
                <?php echo ($_SESSION['lang'] ?? 'vi') === 'en' ? 'Select Discount Code' : 'Chọn mã giảm giá'; ?>
            </h3>
            <button id="close-voucher-modal" class="text-slate-400 dark:text-zinc-500 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-zinc-800 p-1.5 rounded-lg transition-all">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6 max-h-[380px] overflow-y-auto space-y-4 scrollbar-thin" id="voucher-list">
            <div class="text-center py-8 text-slate-400 dark:text-zinc-500"><?php echo ($_SESSION['lang'] ?? 'vi') === 'en' ? 'Loading...' : 'Đang tải...'; ?></div>
        </div>
        <div class="p-4 bg-slate-50/40 dark:bg-zinc-900/30 border-t border-slate-100 dark:border-zinc-800/80 text-center">
            <p class="text-[11px] font-semibold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">
                <?php echo ($_SESSION['lang'] ?? 'vi') === 'en' ? 'Select the discount code that best fits your order' : 'Chọn mã giảm giá phù hợp nhất với đơn hàng của bạn'; ?>
            </p>
        </div>
    </div>
</div>

<script>
/**
 * VoucherManager - Handles shared voucher modal logic
 */
const VoucherManager = {
    init: function(options) {
        this.options = Object.assign({
            openBtnId: 'open-voucher-modal',
            closeBtnId: 'close-voucher-modal',
            modalId: 'voucher-modal',
            modalContentId: 'voucher-modal-content',
            listId: 'voucher-list',
            inputId: 'voucher_input',
            applyBtnId: 'apply_voucher_btn',
            onSelect: null
        }, options);

        this.modal = document.getElementById(this.options.modalId);
        this.modalContent = document.getElementById(this.options.modalContentId);
        this.list = document.getElementById(this.options.listId);
        this.openBtn = document.getElementById(this.options.openBtnId);
        this.closeBtn = document.getElementById(this.options.closeBtnId);
        this.input = document.getElementById(this.options.inputId);
        this.applyBtn = document.getElementById(this.options.applyBtnId);

        if (this.openBtn) {
            this.openBtn.onclick = () => this.open();
        }
        if (this.closeBtn) {
            this.closeBtn.onclick = () => this.close();
        }
        window.onclick = (e) => { if (e.target == this.modal) this.close(); };
    },

    open: function() {
        if (!this.modal) return;
        this.modal.classList.remove('hidden');
        this.modal.classList.add('flex');
        setTimeout(() => { 
            this.modalContent.classList.remove('scale-95', 'opacity-0'); 
            this.modalContent.classList.add('scale-100', 'opacity-100'); 
        }, 10);
        this.loadVouchers();
    },

    close: function() {
        if (!this.modalContent) return;
        this.modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { 
            this.modal.classList.add('hidden'); 
            this.modal.classList.remove('flex'); 
        }, 300);
    },

    loadVouchers: function() {
        const currentLang = '<?php echo $_SESSION['lang'] ?? 'vi'; ?>';
        this.list.innerHTML = `<div class="text-center py-8 text-slate-400 dark:text-zinc-500">${currentLang === 'en' ? 'Loading...' : 'Đang tải...'}</div>`;
        
        fetch('<?php echo URLROOT; ?>/checkout/getAvailableVouchers')
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) { 
                    this.list.innerHTML = `<p class="text-center py-8 text-slate-400 dark:text-zinc-500">${currentLang === 'en' ? 'No promo codes available.' : 'Không có mã giảm giá nào.'}</p>`; 
                    return; 
                }
                this.list.innerHTML = '';
                data.forEach(v => {
                    let discountLabel = '';
                    if (v.is_freeship == 1) {
                        discountLabel = currentLang === 'en' ? 'Free Shipping' : 'Miễn phí vận chuyển';
                    } else if (v.discount_percentage > 0) {
                        discountLabel = currentLang === 'en' ? `Discount ${v.discount_percentage}%` : `Giảm ${v.discount_percentage}%`;
                    } else {
                        discountLabel = currentLang === 'en' ? `Discount ${parseInt(v.discount_amount).toLocaleString('en-US')}đ` : `Giảm ${parseInt(v.discount_amount).toLocaleString('vi-VN')}đ`;
                    }
                    const useText = currentLang === 'en' ? 'Apply' : 'Dùng';
                    const div = document.createElement('div');
                    div.className = 'p-4 bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200/60 dark:border-zinc-800 rounded-2xl hover:bg-indigo-50/25 dark:hover:bg-indigo-950/15 cursor-pointer flex justify-between items-center transition-all duration-200 group relative pl-6 overflow-hidden';
                    div.innerHTML = `
                        <!-- Left color strip -->
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-indigo-500 to-blue-500"></div>
                        <div class="flex-1 min-w-0 pr-4">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-extrabold text-slate-800 dark:text-zinc-100 uppercase font-sans tracking-wide px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100/50 dark:border-indigo-900/30">${v.code}</span>
                            </div>
                            <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400">${discountLabel}</p>
                            <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1 truncate max-w-[240px]">${v.description}</p>
                        </div>
                        <button class="btn-premium px-4 py-2 text-xs normal-case tracking-normal rounded-xl relative">
                            <div class="inner-glow-border"></div>
                            ${useText}
                        </button>
                    `;
                    div.onclick = () => {
                        if (this.input) {
                            this.input.value = v.code;
                        }
                        this.close();
                        if (this.options.onSelect) {
                            this.options.onSelect(v.code);
                        } else if (this.applyBtn) {
                            this.applyBtn.click();
                        }
                    };
                    this.list.appendChild(div);
                });
            });
    }
};
</script>

