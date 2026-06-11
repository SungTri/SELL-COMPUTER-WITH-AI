<?php 
require_once APPROOT . '/views/layout/header.php'; 

// Generate concise transfer description containing Order ID, User ID, Product ID & Quantity, and month-day-hour-minute timestamp.
// Format: DH[OrderID]-U[UserID]-P[ProdID]x[Qty][+]-time
$userId = $data['order']['user_id'] ?? $data['order']['customer_id'] ?? 0;
$firstItem = !empty($data['items']) ? $data['items'][0] : null;
$prodInfo = "";
if ($firstItem) {
    $productId = isset($firstItem['product_id']) ? $firstItem['product_id'] : $firstItem['id'];
    $prodInfo = "P" . $productId . "x" . $firstItem['quantity'];
    if (count($data['items']) > 1) {
        $prodInfo .= "+";
    }
}
$orderTime = !empty($data['order']['ordered_at']) ? strtotime($data['order']['ordered_at']) : time();
$timeStr = date('mdHi', $orderTime); // MMDDHHMM

$description = "DH" . $data['orderId'];
if ($userId) {
    $description .= "-U" . $userId;
}
if ($prodInfo) {
    $description .= "-" . $prodInfo;
}
$description .= "-" . $timeStr;

$bankId = "OCB"; 
$accountNo = "CASS020204000817"; 
$amount = $data['order']['total_amount'];
$accountName = "MA TRI SUNG";
$qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$amount}&addInfo={$description}&accountName={$accountName}";
$isPaid = strtolower($data['order']['payment_status'] ?? '') === 'paid';
?>

<main class="min-h-screen bg-slate-50/50 dark:bg-zinc-950/20 py-16 px-4 flex items-center justify-center transition-colors duration-300">
    <?php if (isset($data['order']['payment_method']) && $data['order']['payment_method'] === 'BANKING'): ?>
        <?php if ($isPaid): ?>
            <!-- Success State Rendered Directly -->
            <div id="main-payment-card" class="max-w-xl w-full bg-white dark:bg-zinc-900 rounded-[32px] border border-slate-200 dark:border-zinc-800/80 shadow-2xl p-10 relative overflow-hidden transition-all duration-300 animate-in fade-in zoom-in duration-500">
                <div class="w-full py-6 flex flex-col items-center justify-center text-center space-y-6">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-5xl select-none">check</span>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-800 dark:text-zinc-100 font-sans">
                        <?php echo __('payment_success', 'Thanh Toán Đơn Hàng Thành Công!'); ?>
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-zinc-400 font-sans max-w-md">
                        <?php echo __('payment_success_subtitle', 'Cảm ơn bạn đã hoàn tất thanh toán. Đơn hàng của bạn đang được xử lý.'); ?>
                    </p>
                    <div class="mt-4 p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200/50 dark:border-zinc-850/80 rounded-2xl w-full">
                        <span class="text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider block"><?php echo __('order_code_label', 'Mã đơn hàng'); ?></span>
                        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400 font-mono block mt-1">#<?php echo htmlspecialchars($data['orderId']); ?></span>
                    </div>
                    <div class="w-full space-y-3 pt-4">
                        <a href="<?php echo URLROOT; ?>/user/profile?tab=orders" class="w-full py-4 btn-premium relative text-base">
                            <div class="inner-glow-border"></div>
                            <?php echo __('view_order_history', 'Xem Lịch Sử Đơn Hàng'); ?>
                        </a>
                        <a href="<?php echo URLROOT; ?>" class="w-full flex justify-center py-4 px-4 border border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold text-slate-700 dark:text-zinc-300 bg-slate-50 dark:bg-zinc-900/60 hover:bg-slate-100 dark:hover:bg-zinc-850 transition-all">
                            <?php echo __('back_to_home', 'Quay Lại Trang Chủ'); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Dual-Column QR Payment screen matching design exactly -->
            <div id="main-payment-card" class="max-w-4xl w-full bg-white dark:bg-zinc-900 rounded-[32px] border border-slate-200 dark:border-zinc-800/80 shadow-2xl p-8 relative flex flex-col md:flex-row gap-8 transition-all duration-300">
                <!-- Close Button -->
                <a href="<?php echo URLROOT; ?>/user/orderDetail/<?php echo $data['orderId']; ?>?error=payment_failed" class="absolute top-5 right-5 text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-400 transition-colors flex items-center justify-center p-2 rounded-full hover:bg-slate-50 dark:hover:bg-zinc-850" title="<?php echo __('close', 'Đóng'); ?>">
                    <span class="material-symbols-outlined text-2xl select-none">close</span>
                </a>

                <!-- Left Column: QR Code side -->
                <div class="flex-1 flex flex-col items-center justify-center text-center space-y-6">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-800 dark:text-zinc-100 font-sans"><?php echo __('payment_qr_title', 'Thanh toán QR'); ?></h2>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest"><?php echo __('amount_label', 'Số tiền'); ?></span>
                        <span class="text-3xl font-extrabold text-rose-600 dark:text-rose-450 mt-1"><?php echo number_format($amount, 0, ',', '.'); ?>đ</span>
                    </div>

                    <div class="w-full max-w-sm bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800/60 rounded-2xl p-4 flex items-center justify-between shadow-sm">
                        <div class="text-left">
                            <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest block"><?php echo __('content_label', 'Nội dung chuyển khoản'); ?></span>
                            <span id="transfer-desc-text" class="text-sm font-extrabold text-slate-800 dark:text-zinc-200 tracking-wider block mt-1"><?php echo htmlspecialchars($description); ?></span>
                        </div>
                        <button onclick="copyToClipboard('<?php echo htmlspecialchars($description); ?>', this)" class="text-indigo-600 dark:text-indigo-400 hover:bg-slate-100 dark:hover:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-2.5 rounded-xl transition-all shadow-sm flex items-center cursor-pointer" title="<?php echo __('copy', 'Sao chép'); ?>">
                            <span class="material-symbols-outlined text-[20px] select-none">content_copy</span>
                        </button>
                    </div>

                    <div id="payment-status-badge" class="inline-flex items-center gap-2 px-3.5 py-2 bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100/30 dark:border-indigo-900/30 rounded-full text-xs font-bold text-indigo-600 dark:text-indigo-400">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        <?php echo __('checking_payment', 'Đang kiểm tra thanh toán...'); ?>
                    </div>

                    <div class="bg-white p-4 rounded-[28px] shadow-md border border-slate-100 dark:border-zinc-800 hover:scale-[1.02] transition-transform duration-300">
                        <img src="<?php echo $qrUrl; ?>" alt="VietQR OCB" class="w-52 h-52 object-contain">
                    </div>

                    <div class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 rounded-full text-xs font-bold font-mono">
                        <span class="material-symbols-outlined text-[16px] text-amber-500 select-none font-bold">schedule</span>
                        MÃ QR HẾT HẠN SAU: <span id="countdown-timer">10:00</span>
                    </div>
                </div>

                <!-- Divider line for large screens -->
                <div class="hidden md:block w-px bg-slate-100 dark:bg-zinc-800/80 self-stretch"></div>

                <!-- Right Column: Information side -->
                <div class="flex-1 flex flex-col space-y-5">
                    <h3 class="text-base font-bold text-slate-800 dark:text-zinc-100 flex items-center gap-2 font-sans">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 select-none">account_balance</span>
                        <?php echo __('transfer_info', 'Thông tin chuyển khoản'); ?>
                    </h3>
                    
                    <div class="bg-slate-50 dark:bg-zinc-950 border border-slate-200/50 dark:border-zinc-800/60 rounded-2xl p-4 flex flex-col justify-start">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest block"><?php echo __('bank_label', 'Ngân hàng'); ?></span>
                        <span class="text-sm font-bold text-slate-800 dark:text-zinc-200 mt-1 block">OCB - Ngân hàng Phương Đông</span>
                    </div>

                    <div class="bg-slate-50 dark:bg-zinc-950 border border-slate-200/50 dark:border-zinc-800/60 rounded-2xl p-4 flex items-center justify-between">
                        <div class="text-left">
                            <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest block"><?php echo __('account_no_label', 'Số tài khoản'); ?></span>
                            <span class="text-sm font-bold text-slate-800 dark:text-zinc-200 tracking-wider mt-1 block"><?php echo $accountNo; ?></span>
                        </div>
                        <button onclick="copyToClipboard('<?php echo $accountNo; ?>', this)" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-2.5 rounded-xl transition-all shadow-sm flex items-center cursor-pointer" title="<?php echo __('copy', 'Sao chép'); ?>">
                            <span class="material-symbols-outlined text-[18px] select-none">content_copy</span>
                        </button>
                    </div>

                    <div class="bg-slate-50 dark:bg-zinc-950 border border-slate-200/50 dark:border-zinc-800/60 rounded-2xl p-4 flex flex-col justify-start">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest block"><?php echo __('account_owner_label', 'Chủ tài khoản'); ?></span>
                        <span class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase mt-1 block"><?php echo $accountName; ?></span>
                    </div>

                    <div class="bg-indigo-500/5 border border-indigo-500/10 rounded-2xl p-5 space-y-3">
                        <h4 class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider"><?php echo __('payment_instructions_title', 'Hướng dẫn thanh toán'); ?></h4>
                        <ul class="space-y-2.5 text-xs text-slate-500 dark:text-zinc-400 font-semibold">
                            <li class="flex gap-2.5 items-start">
                                <span class="flex items-center justify-center shrink-0 w-5.5 h-5.5 rounded-full bg-indigo-650 text-white font-bold text-[10px]">1</span>
                                <span>Mở ứng dụng ngân hàng hoặc ví điện tử để bắt đầu.</span>
                            </li>
                            <li class="flex gap-2.5 items-start">
                                <span class="flex items-center justify-center shrink-0 w-5.5 h-5.5 rounded-full bg-indigo-650 text-white font-bold text-[10px]">2</span>
                                <span>Quét mã QR đối diện hoặc nhập thông tin chuyển khoản thủ công.</span>
                            </li>
                            <li class="flex gap-2.5 items-start">
                                <span class="flex items-center justify-center shrink-0 w-5.5 h-5.5 rounded-full bg-indigo-650 text-white font-bold text-[10px]">3</span>
                                <span>Kiểm tra kỹ Số tiền chuyển khoản và Nội dung chuyển khoản.</span>
                            </li>
                            <li class="flex gap-2.5 items-start">
                                <span class="flex items-center justify-center shrink-0 w-5.5 h-5.5 rounded-full bg-indigo-650 text-white font-bold text-[10px]">4</span>
                                <span>Bấm xác nhận chuyển khoản và hoàn tất giao dịch.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-4 flex gap-3 text-amber-600 dark:text-amber-400 text-xs font-semibold leading-relaxed">
                        <span class="material-symbols-outlined text-amber-500 select-none text-[20px] shrink-0">warning</span>
                        <span><?php echo __('payment_warning_note', 'Lưu ý: Vui lòng giữ lại biên lai thanh toán cho đến khi đơn hàng được duyệt hoàn tất. Không nên đóng cửa sổ này khi hệ thống đang kiểm tra tự động.'); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <!-- COD order success screen styled premium -->
        <div class="max-w-xl w-full bg-white dark:bg-zinc-900 rounded-[32px] border border-slate-200 dark:border-zinc-800/80 shadow-2xl p-10 relative transition-all duration-300">
            <div class="w-full py-6 flex flex-col items-center justify-center text-center space-y-6">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                    <span class="material-symbols-outlined text-5xl select-none">check</span>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-zinc-100 font-sans">
                    <?php echo __('order_success', 'Đặt Hàng Thành Công!'); ?>
                </h2>
                <p class="text-sm text-slate-500 dark:text-zinc-400 font-sans max-w-md">
                    <?php echo __('payment_instruction_general', 'Cảm ơn bạn đã mua sắm tại TechExpert. Mã đơn hàng của bạn là:'); ?>
                </p>
                <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200/50 dark:border-zinc-850/80 rounded-2xl w-full">
                    <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400 font-mono">#<?php echo htmlspecialchars($data['orderId']); ?></span>
                </div>
                <p class="text-sm text-slate-400 dark:text-zinc-500 italic font-semibold">
                    <?php echo __('cod_success_note', 'Chúng tôi sẽ sớm liên hệ với bạn để xác nhận đơn hàng và tiến hành giao hàng.'); ?>
                </p>
                <div class="w-full space-y-3 pt-4">
                    <a href="<?php echo URLROOT; ?>/user/profile?tab=orders" class="w-full py-4 btn-premium relative text-base">
                        <div class="inner-glow-border"></div>
                        <?php echo __('view_order_history', 'Xem Lịch Sử Đơn Hàng'); ?>
                    </a>
                    <a href="<?php echo URLROOT; ?>" class="w-full flex justify-center py-4 px-4 border border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold text-slate-700 dark:text-zinc-300 bg-slate-50 dark:bg-zinc-900/60 hover:bg-slate-100 dark:hover:bg-zinc-850 transition-all">
                        <?php echo __('back_to_home', 'Quay Lại Trang Chủ'); ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<script>
// Clipboard Copy function
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-[20px] text-emerald-500 dark:text-emerald-400 select-none">check</span>';
        if (typeof showToast === 'function') {
            showToast('Đã sao chép thành công!', 'success');
        }
        setTimeout(() => {
            btn.innerHTML = originalIcon;
        }, 1500);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

<?php if (isset($data['order']['payment_method']) && $data['order']['payment_method'] === 'BANKING' && !$isPaid): ?>
// Countdown Timer starting from 10 minutes
(function() {
    let timeLeft = 600; // 10 minutes in seconds
    const countdownEl = document.getElementById('countdown-timer');
    if (!countdownEl) return;
    
    const timer = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(timer);
            countdownEl.innerText = "ĐÃ HẾT HẠN";
            countdownEl.parentElement.className = "inline-flex items-center gap-1.5 px-4 py-2 bg-red-500/10 text-red-655 border border-red-500/20 rounded-full text-xs font-bold font-mono";
            return;
        }
        
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }, 1000);
})();

// Polling for payment status verification
(function() {
    const orderId = <?php echo (int)$data['orderId']; ?>;
    const checkUrl = '<?php echo URLROOT; ?>/checkout/checkPaymentStatus?order_id=' + orderId;
    let pollCount = 0;
    const maxPolls = 150; // 10 minutes max (150 * 4s)

    function checkPayment() {
        if (pollCount >= maxPolls) return;
        pollCount++;

        fetch(checkUrl)
            .then(r => r.json())
            .then(data => {
                if (data.is_paid) {
                    // Trigger dynamic success toast notification
                    if (typeof showToast === 'function') {
                        showToast('Thanh toán đơn hàng thành công!', 'success');
                    }

                    // Dynamically transition the entire card content to success state
                    const mainCard = document.getElementById('main-payment-card');
                    if (mainCard) {
                        mainCard.className = "max-w-xl w-full bg-white dark:bg-zinc-900 rounded-[32px] border border-slate-200 dark:border-zinc-800 shadow-xl p-10 relative animate-in fade-in zoom-in duration-500";
                        mainCard.innerHTML = `
                            <div class="w-full py-6 flex flex-col items-center justify-center text-center space-y-6">
                                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                                    <span class="material-symbols-outlined text-emerald-500 text-5xl select-none">check</span>
                                </div>
                                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-zinc-100 font-sans">
                                    Thanh toán đơn hàng thành công!
                                </h2>
                                <p class="text-sm text-slate-500 dark:text-zinc-400 font-sans max-w-md">
                                    Cảm ơn bạn đã hoàn tất thanh toán. Đơn hàng của bạn đang được xử lý.
                                </p>
                                <div class="mt-4 p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200/50 dark:border-zinc-850/80 rounded-2xl w-full flex flex-col items-center gap-2">
                                    <span class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest block">Đang chuyển hướng về lịch sử đơn hàng</span>
                                    <div class="w-32 bg-slate-200 dark:bg-zinc-800 rounded-full h-1 overflow-hidden">
                                        <div class="bg-emerald-500 h-full animate-[progress_3s_linear]"></div>
                                    </div>
                                </div>
                            </div>
                            <style>
                                @keyframes progress {
                                    from { width: 0%; }
                                    to { width: 100%; }
                                }
                            </style>
                        `;
                    }
                    
                    document.title = 'Thanh toán đơn hàng thành công - TechExpert';
                    
                    setTimeout(() => {
                        window.location.href = '<?php echo URLROOT; ?>/user/profile?tab=orders';
                    }, 3000);
                } else {
                    setTimeout(checkPayment, 4000);
                }
            })
            .catch(() => {
                setTimeout(checkPayment, 5000);
            });
    }

    // Start polling after 3s delay
    setTimeout(checkPayment, 3000);
})();
<?php endif; ?>
</script>

<?php require_once APPROOT . '/views/layout/footer.php'; ?>
