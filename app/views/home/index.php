<?php require APPROOT . '/views/layout/header.php'; ?>

<!-- External Libraries for UI -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #000000 0%, #356ee7 100%);
        --accent-gradient: linear-gradient(135deg, #356ee7 0%, #0453cd 100%);
    }

    .hero-swiper {
        width: 100%;
        height: 500px;
        border-radius: 32px;
        overflow: hidden;
    }
    @media (max-w: 768px) {
        .hero-swiper {
            height: 320px;
        }
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .tab-active {
        color: #356ee7;
        border-bottom: 3px solid #356ee7;
    }

    .product-card:hover .product-actions {
        transform: translateY(0);
        opacity: 1;
    }

    @keyframes pulse-soft {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    .animate-pulse-soft {
        animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

<div class="mt-8 space-y-16">
    <?php if (isset($_SESSION['order_success_flash'])): ?>
        <!-- Order Success Banner -->
        <div id="order-success-banner" class="max-w-container-max mx-auto px-gutter animate-in fade-in slide-in-from-top-4 duration-700">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-[24px] p-6 text-white shadow-xl relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
                <!-- decorative background shapes -->
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                
                <div class="flex items-center gap-4 relative z-10">
                    <div class="p-3 bg-white/20 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined !text-3xl text-white">check_circle</span>
                    </div>
                    <div class="text-left">
                        <h3 class="font-extrabold text-xl mb-1">Đặt Hàng Thành Công!</h3>
                        <p class="text-sm text-emerald-50/90 leading-relaxed">
                            <?php echo htmlspecialchars($_SESSION['order_success_flash']); ?>
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 relative z-10 shrink-0 w-full sm:w-auto justify-end">
                    <?php if (isset($_SESSION['order_success_id'])): ?>
                        <a href="<?php echo URLROOT; ?>/user/profile?tab=orders" class="px-5 py-2.5 bg-white text-emerald-700 font-extrabold text-sm rounded-xl hover:bg-emerald-50 transition-colors shadow-sm whitespace-nowrap">
                            Xem Đơn Hàng #<?php echo htmlspecialchars($_SESSION['order_success_id']); ?>
                        </a>
                    <?php endif; ?>
                    <button onclick="document.getElementById('order-success-banner').remove()" class="p-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-colors">
                        <span class="material-symbols-outlined !text-sm">close</span>
                    </button>
                </div>
            </div>
        </div>
        <?php 
        // Clear flash sessions
        unset($_SESSION['order_success_flash']);
        unset($_SESSION['order_success_id']);
        ?>
    <?php endif; ?>

    <!-- Hero Slider Section -->
    <section class="relative">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide relative flex items-center">
                    <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&q=80&w=1600" class="absolute inset-0 w-full h-full object-cover" alt="Hero 1">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
                    <div class="relative z-10 p-8 md:p-16 max-w-2xl text-white">
                        <span class="inline-block px-4 py-1 rounded-full bg-secondary text-on-secondary text-xs font-bold uppercase tracking-widest mb-4"><?php echo __('hero_slide_1_tag', 'Sản phẩm mới'); ?></span>
                        <h2 class="text-4xl md:text-5xl font-black font-h1 mb-4 leading-tight"><?php echo __('hero_slide_1_title', 'Next-Gen <br><span class="text-secondary">Workstations</span>'); ?></h2>
                        <p class="text-base text-surface-variant mb-6 opacity-90 leading-relaxed"><?php echo __('hero_slide_1_desc', 'Nâng cao hiệu suất làm việc với dòng máy tính trạm cao cấp nhất. Thiết kế tối giản, sức mạnh tối đa.'); ?></p>
                        <a href="<?php echo URLROOT; ?>/product/category/1" class="px-6 py-3 bg-secondary text-on-secondary rounded-xl font-bold hover:shadow-[0_0_20px_rgba(53,110,231,0.5)] transition-all transform hover:-translate-y-1 inline-block text-sm"><?php echo __('hero_slide_1_btn', 'Khám phá ngay'); ?></a>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide relative flex items-center">
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=1600" class="absolute inset-0 w-full h-full object-cover" alt="Hero 2">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/50 to-transparent"></div>
                    <div class="relative z-10 p-8 md:p-16 max-w-2xl text-white">
                        <span class="inline-block px-4 py-1 rounded-full bg-error text-on-error text-xs font-bold uppercase tracking-widest mb-4"><?php echo __('hero_slide_2_tag', 'Ưu đãi Gaming'); ?></span>
                        <h2 class="text-4xl md:text-5xl font-black font-h1 mb-4 leading-tight"><?php echo __('hero_slide_2_title', 'Ultimate <br><span class="text-error">Gaming Hub</span>'); ?></h2>
                        <p class="text-base text-surface-variant mb-6 opacity-90 leading-relaxed"><?php echo __('hero_slide_2_desc', 'Thống trị mọi trận đấu với các linh kiện RTX 40-Series mới nhất. Giảm giá lên đến 30%.'); ?></p>
                        <a href="<?php echo URLROOT; ?>/product/category/3" class="px-6 py-3 bg-error text-on-error rounded-xl font-bold hover:shadow-[0_0_20px_rgba(186,26,26,0.5)] transition-all transform hover:-translate-y-1 inline-block text-sm"><?php echo __('hero_slide_2_btn', 'Săn ngay'); ?></a>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next !text-white/50 hover:!text-white transition-colors"></div>
            <div class="swiper-button-prev !text-white/50 hover:!text-white transition-colors"></div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="max-w-container-max mx-auto px-gutter">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/60 flex flex-col items-center text-center group hover:border-secondary/50 hover:shadow-lg transition-all duration-300">
                <span class="material-symbols-outlined text-3xl text-secondary mb-2" style="font-variation-settings:'FILL' 1">people</span>
                <span class="text-3xl font-black text-primary tracking-tight">10,000+</span>
                <span class="text-xs text-on-surface-variant font-medium mt-1"><?php echo __('stat_customers', 'Khách hàng tin dùng'); ?></span>
            </div>
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/60 flex flex-col items-center text-center group hover:border-secondary/50 hover:shadow-lg transition-all duration-300">
                <span class="material-symbols-outlined text-3xl text-secondary mb-2" style="font-variation-settings:'FILL' 1">inventory_2</span>
                <span class="text-3xl font-black text-primary tracking-tight"><?php echo number_format($data['stats']['total_products'] ?? 500); ?>+</span>
                <span class="text-xs text-on-surface-variant font-medium mt-1"><?php echo __('stat_products', 'Sản phẩm chính hãng'); ?></span>
            </div>
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/60 flex flex-col items-center text-center group hover:border-secondary/50 hover:shadow-lg transition-all duration-300">
                <span class="material-symbols-outlined text-3xl text-amber-500 mb-2" style="font-variation-settings:'FILL' 1">star</span>
                <span class="text-3xl font-black text-primary tracking-tight">4.9/5</span>
                <span class="text-xs text-on-surface-variant font-medium mt-1"><?php echo __('stat_rating', 'Điểm đánh giá trung bình'); ?></span>
            </div>
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/60 flex flex-col items-center text-center group hover:border-secondary/50 hover:shadow-lg transition-all duration-300">
                <span class="material-symbols-outlined text-3xl text-secondary mb-2" style="font-variation-settings:'FILL' 1">verified_user</span>
                <span class="text-3xl font-black text-primary tracking-tight">24/7</span>
                <span class="text-xs text-on-surface-variant font-medium mt-1"><?php echo __('stat_support', 'Hỗ trợ kỹ thuật'); ?></span>
            </div>
        </div>
    </section>

    <!-- Premium AI Consultant Section -->
    <section class="max-w-container-max mx-auto px-gutter py-12">
        <div class="bg-primary rounded-[40px] p-12 relative overflow-hidden shadow-2xl">
            <!-- Background Elements -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-secondary/20 rounded-full blur-[100px]"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-secondary/10 rounded-full blur-[80px]"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2 text-on-primary space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-secondary/20 rounded-full border border-secondary/30 backdrop-blur-md">
                        <span class="w-2 h-2 bg-secondary rounded-full animate-pulse"></span>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-secondary"><?php echo __('ai_consult_badge', 'TechExpert Intelligence'); ?></span>
                    </div>
                    <h2 class="text-5xl font-black font-h1 leading-[1.1] tracking-tighter italic">
                        <?php echo __('ai_consult_title', 'Cần tư vấn <br> cấu hình <span class="text-secondary">phù hợp?</span>'); ?>
                    </h2>
                    <p class="text-on-primary opacity-70 text-lg leading-relaxed">
                        <?php echo __('ai_consult_desc', 'Hãy để trợ lý AI của chúng tôi giúp bạn tìm ra giải pháp tối ưu nhất dựa trên nhu cầu thực tế và ngân sách của bạn.'); ?>
                    </p>
                    <div class="relative max-w-md group">
                        <textarea id="ai-query" placeholder="<?php echo __('ai_consult_placeholder', 'Ví dụ: Mình cần build PC chơi game 30tr, có màn hình...'); ?>" 
                            class="w-full bg-white/5 border border-white/10 rounded-3xl p-6 text-on-primary placeholder:text-on-primary placeholder:opacity-30 focus:ring-4 focus:ring-secondary/20 focus:border-secondary outline-none transition-all min-h-[120px] text-lg resize-none"></textarea>
                        <button onclick="handleAiSearch()" id="ai-submit-btn" class="absolute bottom-4 right-4 z-20 bg-secondary text-on-secondary px-6 py-3 rounded-2xl font-black hover:scale-105 transition-all shadow-xl shadow-secondary/20 flex items-center gap-2 group-hover:bg-white group-hover:text-primary">
                            <?php echo __('ai_consult_btn', 'HỎI AI NGAY'); ?> <span class="material-symbols-outlined">smart_toy</span>
                        </button>
                    </div>
                </div>

                <div class="lg:w-1/2 w-full text-on-primary">
                    <div id="ai-results-container" class="hidden animate-in fade-in slide-in-from-right-8 duration-700">
                        <div class="bg-white/5 backdrop-blur-xl rounded-[32px] p-8 border border-white/10">
                            <div class="mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 bg-secondary text-on-secondary rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined !text-[18px]">analytics</span>
                                </span>
                                <h3 class="text-sm font-black uppercase tracking-widest text-secondary"><?php echo __('ai_results_analysis', 'Kết quả phân tích'); ?></h3>
                            </div>
                            <p id="ai-analysis" class="text-on-primary opacity-80 italic mb-8 leading-relaxed"></p>
                            <div id="ai-product-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Products -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Default Visualization -->
                    <div id="ai-placeholder" class="grid grid-cols-2 gap-4">
                        <div class="aspect-square bg-white/5 rounded-3xl border border-white/10 flex flex-col items-center justify-center gap-4 group hover:bg-secondary/10 transition-colors">
                            <span class="material-symbols-outlined !text-5xl text-secondary">graphic_eq</span>
                            <span class="text-xs font-bold text-on-primary opacity-40 uppercase tracking-widest"><?php echo __('ai_placeholder_analyze', 'Phân tích nhu cầu'); ?></span>
                        </div>
                        <div class="aspect-square bg-white/5 rounded-3xl border border-white/10 flex flex-col items-center justify-center gap-4 group hover:bg-secondary/10 transition-colors">
                            <span class="material-symbols-outlined !text-5xl text-secondary">inventory_2</span>
                            <span class="text-xs font-bold text-on-primary opacity-40 uppercase tracking-widest"><?php echo __('ai_placeholder_scan', 'Quét kho hàng'); ?></span>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div id="ai-loading" class="hidden text-center py-12">
                        <div class="w-16 h-16 border-4 border-secondary/20 border-t-secondary rounded-full animate-spin mx-auto mb-6"></div>
                        <p class="text-secondary font-black uppercase tracking-widest text-sm animate-pulse"><?php echo __('ai_loading_text', 'AI đang phân tích yêu cầu...'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Marketing & Promotions Section -->
    <?php if(!empty($data['vouchers'])): ?>
    <section class="max-w-container-max mx-auto px-gutter py-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-secondary font-black text-xs uppercase tracking-widest mb-2 block"><?php echo __('voucher_tag', 'Exclusive Offers'); ?></span>
                <h2 class="text-4xl font-black font-h1 tracking-tighter italic text-primary"><?php echo __('voucher_title', 'SĂN MÃ <span class="text-secondary">GIẢM GIÁ</span>'); ?></h2>
            </div>
            <p class="text-on-surface-variant/60 text-sm max-w-[200px] text-right font-medium"><?php echo __('voucher_desc', 'Số lượng có hạn, nhanh tay áp dụng ngay hôm nay!'); ?></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach($data['vouchers'] as $voucher): ?>
                <?php if($voucher['status'] == 1): ?>
                <div class="relative bg-surface-container-lowest rounded-3xl p-6 border border-outline-variant/50 dark:border-outline-variant/10 shadow-lg hover:shadow-xl transition-all group overflow-hidden">
                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-surface rounded-full border border-outline-variant/50"></div>
                    <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-surface rounded-full border border-outline-variant/50"></div>
                    
                    <div class="flex flex-col h-full justify-between gap-4">
                        <div class="flex justify-between items-start">
                            <div class="w-10 h-10 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined">confirmation_number</span>
                            </div>
                            <span class="text-[9px] font-black text-primary/30 uppercase tracking-tighter italic"><?php echo __('voucher_expiry_prefix', 'Hạn:'); ?> <?php echo date('d/m', strtotime($voucher['end_date'])); ?></span>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-black text-primary tracking-tighter mb-1">
                                <?php 
                                if ($_SESSION['lang'] === 'en') {
                                    echo $voucher['discount_percentage'] ? $voucher['discount_percentage'] . '% OFF' : 'SAVE ' . number_format($voucher['discount_amount']/1000) . 'K';
                                } else {
                                    echo $voucher['discount_percentage'] ? 'GIẢM ' . $voucher['discount_percentage'] . '%' : 'GIẢM ' . number_format($voucher['discount_amount']/1000) . 'K';
                                }
                                ?>
                            </h3>
                            <p class="text-[10px] text-on-surface-variant/70 font-medium line-clamp-1"><?php echo $voucher['description']; ?></p>
                        </div>

                        <div class="bg-surface rounded-xl p-3 border border-outline-variant/30 flex items-center justify-between group-hover:border-secondary transition-colors">
                            <code class="text-xs font-black text-primary tracking-widest uppercase"><?php echo $voucher['code']; ?></code>
                            <div class="flex items-center gap-2">
                                <?php 
                                    $isSaved = in_array($voucher['id'], $data['saved_voucher_ids'] ?? []);
                                ?>
                                <button onclick="saveVoucher(this, <?php echo $voucher['id']; ?>)" class="<?php echo $isSaved ? 'text-secondary' : 'text-primary/40'; ?> hover:text-secondary transition-colors" title="<?php echo __('save_to_my_vouchers', 'Lưu vào của tôi'); ?>">
                                    <span class="material-symbols-outlined !text-[18px]" <?php echo $isSaved ? 'style="font-variation-settings: \'FILL\' 1"' : ''; ?>>bookmark</span>
                                </button>
                                <button onclick="copyToClipboard(this, '<?php echo $voucher['code']; ?>')" class="text-secondary hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined !text-[18px]">content_copy</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <script>
    function saveVoucher(btn, voucherId) {
        console.log('Saving voucher:', voucherId);
        fetch('<?php echo URLROOT; ?>/voucher/save/' + voucherId)
            .then(res => res.json())
            .then(data => {
                console.log('Response:', data);
                if (data.status === 'success') {
                    btn.innerHTML = '<span class="material-symbols-outlined !text-[18px]" style="font-variation-settings: \'FILL\' 1">bookmark</span>';
                    btn.classList.add('text-secondary');
                    if (typeof showToast === 'function') {
                        showToast(data.message, 'success');
                    } else {
                        alert(data.message);
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast(data.message, 'error');
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(err => {
                console.error('Fetch error:', err);
                alert('Có lỗi xảy ra khi kết nối máy chủ.');
            });
    }

    function copyToClipboard(btn, text) {
        navigator.clipboard.writeText(text).then(() => {
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined !text-[18px]">check</span>';
            btn.classList.add('text-green-500');
            setTimeout(() => {
                btn.innerHTML = originalIcon;
                btn.classList.remove('text-green-500');
            }, 2000);
        });
    }
    </script>
    <?php endif; ?>

    <!-- Brands Carousel -->
    <section class="py-4 overflow-hidden">
        <div class="flex items-center gap-12 animate-infinite-scroll">
            <?php if(!empty($data['brands'])): ?>
                <?php foreach($data['brands'] as $brand): ?>
                    <div class="flex-shrink-0 flex items-center gap-2 grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100 cursor-pointer">
                        <span class="text-2xl font-bold text-on-surface/80 tracking-tighter uppercase italic"><?php echo $brand['name']; ?></span>
                    </div>
                <?php endforeach; ?>
                <!-- Duplicate for infinite scroll -->
                <?php foreach($data['brands'] as $brand): ?>
                    <div class="flex-shrink-0 flex items-center gap-2 grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100 cursor-pointer">
                        <span class="text-2xl font-bold text-on-surface/80 tracking-tighter uppercase italic"><?php echo $brand['name']; ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <style>
        @keyframes infinite-scroll {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }
        .animate-infinite-scroll {
            display: flex;
            width: max-content;
            animation: infinite-scroll 40s linear infinite;
        }
    </style>

    <!-- Promotional Grid -->
    <section class="grid grid-cols-1 md:grid-cols-12 gap-6 h-[450px]">
        <div class="md:col-span-8 relative rounded-3xl overflow-hidden group">
            <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&q=80&w=1200" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Promo 1">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-10 text-white">
                <h3 class="text-3xl font-bold mb-2"><?php echo __('build_pc_banner_title', 'Build Your Dream Setup'); ?></h3>
                <p class="text-white/80 mb-6 max-w-md"><?php echo __('build_pc_banner_desc', 'Lựa chọn từng linh kiện để tạo nên cỗ máy chiến đấu mạnh mẽ nhất theo cách của bạn.'); ?></p>
                <a href="<?php echo URLROOT; ?>/product/category/2" class="px-6 py-3 bg-white text-black rounded-xl font-bold hover:bg-secondary hover:text-white transition-all inline-block"><?php echo __('build_pc_banner_btn', 'Bắt đầu cấu hình'); ?></a>
            </div>
        </div>
        <div class="md:col-span-4 bg-primary rounded-3xl p-10 text-white relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-secondary/20 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <span class="text-secondary font-bold text-sm tracking-widest uppercase mb-4 block"><?php echo __('flash_sale_tag', 'Flash Sale'); ?></span>
                <h3 class="text-4xl font-bold mb-4"><?php echo __('flash_sale_title', 'Siêu Ưu Đãi Phụ Kiện'); ?></h3>
                <p class="text-white/60 mb-8"><?php echo __('flash_sale_desc', 'Nâng tầm trải nghiệm với bộ gear chuyên nghiệp. Giảm đến 50%.'); ?></p>
                
                <!-- Countdown Timer -->
                <div class="flex gap-4 mb-8" id="countdown">
                    <div class="flex flex-col items-center">
                        <span class="text-2xl font-bold bg-white/10 w-12 h-12 flex items-center justify-center rounded-lg" id="hours">04</span>
                        <span class="text-[10px] uppercase mt-1 opacity-60"><?php echo __('flash_sale_hours', 'Giờ'); ?></span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-2xl font-bold bg-white/10 w-12 h-12 flex items-center justify-center rounded-lg" id="minutes">12</span>
                        <span class="text-[10px] uppercase mt-1 opacity-60"><?php echo __('flash_sale_minutes', 'Phút'); ?></span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-2xl font-bold bg-white/10 w-12 h-12 flex items-center justify-center rounded-lg" id="seconds">45</span>
                        <span class="text-[10px] uppercase mt-1 opacity-60"><?php echo __('flash_sale_seconds', 'Giây'); ?></span>
                    </div>
                </div>
            </div>
            <a href="<?php echo URLROOT; ?>/product?sale=1" class="relative z-10 flex items-center gap-2 text-secondary font-bold group">
                <?php echo __('flash_sale_view_all', 'Xem tất cả ưu đãi'); ?> <span class="material-symbols-outlined group-hover:translate-x-2 transition-transform">arrow_forward</span>
            </a>
        </div>
    </section>

    <!-- Trending Products (Tabs) -->
    <section>
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
            <div>
                <span class="text-secondary font-black text-xs uppercase tracking-widest mb-2 block"><?php echo __('trending_subtitle', 'Được yêu thích nhất'); ?></span>
                <h2 class="text-4xl font-black font-h1 tracking-tighter text-primary"><?php echo __('trending_title', 'Dành <span class="text-secondary">Cho Bạn</span>'); ?></h2>
            </div>
            <div class="flex bg-surface-container rounded-2xl p-1 shadow-inner">
                <button onclick="switchTab('new')" id="tab-new" class="px-6 py-2.5 rounded-xl font-bold transition-all bg-surface-container-lowest shadow-sm text-secondary"><?php echo __('tab_new', 'Mới Nhất'); ?></button>
                <button onclick="switchTab('best')" id="tab-best" class="px-6 py-2.5 rounded-xl font-bold transition-all text-on-surface-variant hover:text-on-surface"><?php echo __('tab_best', 'Bán Chạy'); ?></button>
                <button onclick="switchTab('featured')" id="tab-featured" class="px-6 py-2.5 rounded-xl font-bold transition-all text-on-surface-variant hover:text-on-surface"><?php echo __('tab_featured', 'Nổi Bật'); ?></button>
            </div>
        </div>

        <!-- New Arrivals Grid -->
        <div id="grid-new" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-in fade-in duration-500">
            <?php foreach($data['new_arrivals'] as $product): ?>
                <?php renderProductCard($product, $data['wishlist_ids'] ?? []); ?>
            <?php endforeach; ?>
        </div>

        <!-- Best Sellers Grid (Hidden) -->
        <div id="grid-best" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-in fade-in duration-500">
            <?php foreach($data['best_sellers'] as $product): ?>
                <?php renderProductCard($product, $data['wishlist_ids'] ?? []); ?>
            <?php endforeach; ?>
        </div>

        <!-- Featured Products Grid (Hidden) -->
        <div id="grid-featured" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-in fade-in duration-500">
            <?php foreach(array_slice($data['products'], 0, 4) as $product): ?>
                <?php renderProductCard($product, $data['wishlist_ids'] ?? []); ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Recently Viewed -->
    <?php if(!empty($data['recently_viewed'])): ?>
    <section>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold"><?php echo __('recent_viewed', 'Sản phẩm đã xem'); ?></h2>
            <div class="flex gap-2">
                <button class="swiper-prev-viewed w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center hover:bg-secondary hover:text-white transition-all disabled:opacity-30">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="swiper-next-viewed w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center hover:bg-secondary hover:text-white transition-all disabled:opacity-30">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
        <div class="swiper recently-viewed-swiper">
            <div class="swiper-wrapper">
                <?php foreach($data['recently_viewed'] as $product): ?>
                    <div class="swiper-slide">
                        <?php renderProductCard($product, $data['wishlist_ids'] ?? []); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new Swiper('.recently-viewed-swiper', {
                slidesPerView: 1,
                spaceBetween: 24,
                navigation: {
                    nextEl: '.swiper-next-viewed',
                    prevEl: '.swiper-prev-viewed',
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 4 }
                }
            });
        });
    </script>
    <?php endif; ?>

    <!-- Trust Factors -->
    <section>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="relative overflow-hidden rounded-3xl p-8 group cursor-default" style="background: linear-gradient(135deg, #0453cd 0%, #356ee7 100%)">
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="absolute -left-4 -bottom-4 w-24 h-24 bg-white/5 rounded-full"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-3xl text-white" style="font-variation-settings:'FILL' 1">local_shipping</span>
                    </div>
                    <h4 class="font-black text-xl text-white mb-2"><?php echo __('trust_factor_1_title', 'Giao hàng thần tốc'); ?></h4>
                    <p class="text-white/70 text-sm leading-relaxed"><?php echo __('trust_factor_1_desc', 'Nhận hàng trong vòng 2h tại khu vực nội thành.'); ?></p>
                    <div class="mt-6 inline-flex items-center gap-2 text-white/60 text-xs font-bold uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                        <?php echo __('trust_free_shipping', 'Miễn phí vận chuyển'); ?>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="relative overflow-hidden rounded-3xl p-8 group cursor-default bg-surface-container-lowest border-2 border-outline-variant/40 hover:border-secondary/40 hover:shadow-2xl transition-all duration-500">
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-secondary/5 rounded-full blur-2xl group-hover:bg-secondary/10 transition-colors duration-700"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-secondary group-hover:text-white transition-all duration-300">
                        <span class="material-symbols-outlined text-3xl text-secondary group-hover:text-white transition-colors" style="font-variation-settings:'FILL' 1">verified_user</span>
                    </div>
                    <h4 class="font-black text-xl text-on-surface mb-2"><?php echo __('trust_factor_2_title', 'Bảo hành 24 tháng'); ?></h4>
                    <p class="text-on-surface-variant text-sm leading-relaxed"><?php echo __('trust_factor_2_desc', 'Cam kết hỗ trợ kỹ thuật trọn đời cho mọi sản phẩm.'); ?></p>
                    <div class="mt-6 inline-flex items-center gap-2 text-secondary text-xs font-bold uppercase tracking-widest">
                        <span class="material-symbols-outlined !text-[14px]">check_circle</span>
                        <?php echo __('trust_warranty_badge', 'Bảo hành chính hãng'); ?>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="relative overflow-hidden rounded-3xl p-8 group cursor-default bg-surface-container-lowest border-2 border-outline-variant/40 hover:border-secondary/40 hover:shadow-2xl transition-all duration-500">
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-secondary/5 rounded-full blur-2xl group-hover:bg-secondary/10 transition-colors duration-700"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-secondary group-hover:text-white transition-all duration-300">
                        <span class="material-symbols-outlined text-3xl text-secondary group-hover:text-white transition-colors" style="font-variation-settings:'FILL' 1">support_agent</span>
                    </div>
                    <h4 class="font-black text-xl text-on-surface mb-2"><?php echo __('trust_factor_3_title', 'Tư vấn chuyên sâu'); ?></h4>
                    <p class="text-on-surface-variant text-sm leading-relaxed"><?php echo __('trust_factor_3_desc', 'Đội ngũ kỹ thuật viên giàu kinh nghiệm hỗ trợ 24/7.'); ?></p>
                    <div class="mt-6 inline-flex items-center gap-2 text-secondary text-xs font-bold uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-pulse"></span>
                        <?php echo __('trust_support_badge', 'Hỗ trợ 24/7'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <?php if (!empty($data['top_reviews'])): ?>
    <section>
        <div class="text-center mb-10">
            <span class="text-secondary font-black text-xs uppercase tracking-widest mb-2 block"><?php echo __('testimonial_tag', 'Khách hàng nói gì'); ?></span>
            <h2 class="text-4xl font-black font-h1 tracking-tighter text-primary"><?php echo __('testimonial_title', 'Đánh giá <span class="text-secondary">thực tế</span> từ khách hàng'); ?></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach($data['top_reviews'] as $index => $review):
                // Tên viết tắt avatar
                $nameParts = explode(' ', trim($review['customer_name']));
                $initials = '';
                if (count($nameParts) >= 2) {
                    $initials = mb_strtoupper(mb_substr($nameParts[0], 0, 1) . mb_substr(end($nameParts), 0, 1), 'UTF-8');
                } else {
                    $initials = mb_strtoupper(mb_substr($review['customer_name'], 0, 2), 'UTF-8');
                }
                $isFeatured = ($index === 1); // Card giữa nổi bật
            ?>
            <div class="<?php echo $isFeatured
                ? 'bg-secondary rounded-3xl p-8 hover:shadow-2xl hover:shadow-secondary/20 transition-all duration-300 flex flex-col gap-4 relative overflow-hidden'
                : 'bg-surface-container-lowest rounded-3xl p-8 border border-outline-variant/50 hover:shadow-xl hover:border-secondary/30 transition-all duration-300 flex flex-col gap-4'; ?>">
                <?php if($isFeatured): ?>
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <?php endif; ?>

                <!-- Stars -->
                <div class="flex gap-0.5 relative z-10 <?php echo $isFeatured ? 'text-amber-300' : 'text-amber-400'; ?>">
                    <?php for($s = 0; $s < $review['rating']; $s++): ?>
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">star</span>
                    <?php endfor; ?>
                    <?php for($s = $review['rating']; $s < 5; $s++): ?>
                        <span class="material-symbols-outlined text-[18px] <?php echo $isFeatured ? 'text-white/20' : 'text-outline-variant'; ?>">star</span>
                    <?php endfor; ?>
                </div>

                <!-- Comment -->
                <p class="<?php echo $isFeatured ? 'text-white/90' : 'text-on-surface-variant'; ?> leading-relaxed text-[15px] italic flex-1 relative z-10 line-clamp-4">
                    "<?php echo htmlspecialchars($review['comment']); ?>"
                </p>

                <!-- Product reviewed -->
                <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $review['product_id']; ?>"
                   class="<?php echo $isFeatured ? 'text-white/50 hover:text-white/80' : 'text-secondary/60 hover:text-secondary'; ?> text-[11px] font-bold uppercase tracking-wider flex items-center gap-1 transition-colors relative z-10">
                    <span class="material-symbols-outlined !text-[13px]">inventory_2</span>
                    <?php echo htmlspecialchars(mb_strimwidth($review['product_name'], 0, 40, '...', 'UTF-8')); ?>
                </a>

                <!-- Author -->
                <div class="flex items-center gap-3 pt-4 <?php echo $isFeatured ? 'border-t border-white/20' : 'border-t border-outline-variant/30'; ?> relative z-10">
                    <div class="w-10 h-10 rounded-full <?php echo $isFeatured ? 'bg-white/20 text-white' : 'bg-secondary/20 text-secondary'; ?> flex items-center justify-center font-black text-sm">
                        <?php echo $initials; ?>
                    </div>
                    <div>
                        <p class="font-bold <?php echo $isFeatured ? 'text-white' : 'text-on-surface'; ?> text-sm">
                            <?php echo htmlspecialchars($review['customer_name']); ?>
                        </p>
                        <p class="text-xs <?php echo $isFeatured ? 'text-white/50' : 'text-on-surface-variant'; ?>">
                            <?php echo date('d/m/Y', strtotime($review['created_at'])); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Newsletter Section -->
    <section class="bg-primary rounded-3xl p-12 md:p-20 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
        </div>
        <div class="relative z-10 max-w-2xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6"><?php echo __('newsletter_title', 'Đừng bỏ lỡ các ưu đãi mới nhất'); ?></h2>
            <p class="text-white/60 mb-10 text-lg"><?php echo __('newsletter_desc', 'Đăng ký nhận bản tin để cập nhật sớm nhất các sản phẩm mới và mã giảm giá độc quyền dành riêng cho bạn.'); ?></p>
            <form id="newsletter-form" class="flex flex-col sm:flex-row gap-4 items-center">
                <input id="newsletter-email" type="email" placeholder="<?php echo __('newsletter_placeholder', 'Nhập địa chỉ email của bạn...'); ?>" class="w-full sm:flex-1 min-w-0 bg-white/10 border border-white/20 rounded-2xl px-6 py-4 text-white placeholder:text-white/40 outline-none focus:border-secondary transition-all" required>
                <button id="newsletter-submit-btn" type="submit" class="w-full sm:w-auto px-10 py-4 bg-secondary text-on-secondary rounded-2xl font-bold hover:shadow-[0_0_30px_rgba(53,110,231,0.4)] transition-all shrink-0 whitespace-nowrap"><?php echo __('newsletter_btn', 'Đăng ký ngay'); ?></button>
            </form>
        </div>
    </section>
</div>

<?php 
function renderProductCard($product, $wishlistIds = []) {
    // Badge logic: sản phẩm mới nhất trong 30 ngày
    $isNew = !empty($product['created_at']) && (strtotime($product['created_at']) > strtotime('-30 days'));
?>
    <div class="product-card group bg-surface-container-lowest dark:bg-zinc-900 border border-outline-variant/45 dark:border-outline-variant/10 rounded-[28px] overflow-hidden hover:shadow-[0_20px_50px_rgba(0,0,0,0.04)] dark:hover:shadow-[0_20px_50px_rgba(0,0,0,0.25)] transition-all duration-500 transform hover:-translate-y-2">
        <div class="relative aspect-square bg-slate-50 dark:bg-zinc-950 p-6 overflow-hidden">
            <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $product['id']; ?>" class="absolute inset-0 z-10"></a>
            <?php if($isNew): ?>
            <span class="absolute top-4 left-4 z-20 px-2.5 py-1 bg-secondary text-on-secondary text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg"><?php echo __('badge_new', 'MỚI'); ?></span>
            <?php endif; ?>
            <img src="<?php echo get_product_image($product['main_image']); ?>" alt="<?php echo $product['name']; ?>" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-700" onerror="this.src='https://placehold.co/300x300?text=Product'" />
            
            <!-- Floating Actions -->
            <div class="product-actions absolute top-4 right-4 flex flex-col gap-2 translate-y-4 opacity-0 transition-all duration-300 z-20">
                <button type="button" onclick="toggleWishlist(this, <?php echo $product['id']; ?>)" 
                        class="p-2.5 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md shadow-lg rounded-full <?php echo in_array($product['id'], $wishlistIds) ? 'text-red-500' : 'text-on-surface-variant'; ?> hover:text-red-500 transition-colors wishlist-btn"
                        data-product-id="<?php echo $product['id']; ?>">
                    <span class="material-symbols-outlined text-[20px] <?php echo in_array($product['id'], $wishlistIds) ? 'fill-1' : ''; ?>">favorite</span>
                </button>
                <button type="button" onclick="toggleCompare(<?php echo $product['id']; ?>)" class="p-2.5 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md shadow-lg rounded-full text-on-surface-variant hover:text-secondary transition-colors compare-btn" data-product-id="<?php echo $product['id']; ?>" title="<?php echo __('compare_title', 'So sánh'); ?>">
                    <span class="material-symbols-outlined text-[20px]">compare_arrows</span>
                </button>
                <button type="button" class="p-2.5 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md shadow-lg rounded-full text-on-surface-variant hover:text-secondary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </button>
            </div>

        </div>
        <div class="p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-outline uppercase tracking-widest"><?php echo $product['brand_name']; ?></span>
                <div class="flex items-center gap-1 text-amber-400">
                    <span class="material-symbols-outlined text-[14px] fill-1">star</span>
                    <span class="text-xs font-bold text-on-surface"><?php echo number_format(isset($product['avg_rating']) ? ($product['avg_rating'] ?: 5.0) : 5.0, 1); ?></span>
                </div>
            </div>
            <h3 class="font-bold text-on-surface group-hover:text-secondary transition-colors mb-4 line-clamp-2 min-h-[48px]">
                <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a>
            </h3>
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <span class="text-on-surface font-price-display text-xl font-bold block truncate">
                        <?php 
                        if (($_SESSION['lang'] ?? 'vi') === 'en') {
                            echo number_format($product['price'], 0, '.', ',') . 'đ';
                        } else {
                            echo number_format($product['price'], 0, ',', '.') . 'đ';
                        }
                        ?>
                    </span>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button type="button" onclick="addToCart(<?php echo $product['id']; ?>)" 
                            class="w-10 h-10 rounded-xl bg-surface-container-high dark:bg-zinc-800 text-primary hover:bg-secondary hover:text-on-secondary dark:hover:bg-indigo-600 transition-all flex items-center justify-center group/btn shadow-sm">
                        <span class="material-symbols-outlined text-[20px] group-hover/btn:scale-110 transition-transform">shopping_cart</span>
                    </button>
                    <a href="<?php echo URLROOT; ?>/product/detail/<?php echo $product['id']; ?>" 
                       class="w-10 h-10 rounded-xl bg-on-surface dark:bg-zinc-200 text-surface dark:text-zinc-900 hover:bg-secondary hover:text-on-secondary dark:hover:bg-indigo-600 dark:hover:text-white transition-all flex items-center justify-center shadow-sm"
                       title="Xem chi tiết">
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<script>
    // Initialize Swiper
    const swiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    // Tab Switching Logic
    function switchTab(tab) {
        const tabs = ['new', 'best', 'featured'];
        tabs.forEach(t => {
            const btn = document.getElementById(`tab-${t}`);
            const grid = document.getElementById(`grid-${t}`);
            if (t === tab) {
                btn.classList.add('bg-surface-container-lowest', 'shadow-sm', 'text-secondary');
                btn.classList.remove('text-on-surface-variant');
                grid.classList.remove('hidden');
            } else {
                btn.classList.remove('bg-surface-container-lowest', 'shadow-sm', 'text-secondary');
                btn.classList.add('text-on-surface-variant');
                grid.classList.add('hidden');
            }
        });
    }

    // Flash Sale Countdown
    function startCountdown() {
        let hours = 4, minutes = 12, seconds = 45;
        const hEl = document.getElementById('hours');
        const mEl = document.getElementById('minutes');
        const sEl = document.getElementById('seconds');

        if (!hEl || !mEl || !sEl) return;

        setInterval(() => {
            seconds--;
            if (seconds < 0) {
                seconds = 59;
                minutes--;
                if (minutes < 0) {
                    minutes = 59;
                    hours--;
                }
            }
            hEl.textContent = hours.toString().padStart(2, '0');
            mEl.textContent = minutes.toString().padStart(2, '0');
            sEl.textContent = seconds.toString().padStart(2, '0');
        }, 1000);
    }
    startCountdown();

    // AI Smart Search Logic
    async function handleAiSearch() {
        const query = document.getElementById('ai-query').value.trim();
        if (!query) return;

        const placeholder = document.getElementById('ai-placeholder');
        const loading = document.getElementById('ai-loading');
        const resultsContainer = document.getElementById('ai-results-container');
        const productGrid = document.getElementById('ai-product-grid');
        const analysisText = document.getElementById('ai-analysis');
        const btn = document.getElementById('ai-submit-btn');

        // Show loading
        placeholder.classList.add('hidden');
        resultsContainer.classList.add('hidden');
        loading.classList.remove('hidden');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span>';

        const formData = new FormData();
        formData.append('query', query);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        try {
            const response = await fetch('<?php echo URLROOT; ?>/ai/search', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            loading.classList.add('hidden');
            btn.disabled = false;
            btn.innerHTML = '<?php echo __('ai_consult_btn', 'HỎI AI NGAY'); ?> <span class="material-symbols-outlined">smart_toy</span>';

            if (data.status === 'success') {
                analysisText.textContent = data.data.analysis;
                productGrid.innerHTML = '';
                
                if (data.data.products.length === 0) {
                    productGrid.innerHTML = '<p class="col-span-full text-center text-on-primary opacity-50 text-sm italic py-8"><?php echo __('ai_no_products', 'Xin lỗi, AI không tìm thấy sản phẩm nào khớp hoàn toàn với yêu cầu này.'); ?></p>';
                }

                data.data.products.forEach(p => {
                    const card = `
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 flex gap-4 hover:bg-white/20 transition-all group animate-in fade-in zoom-in duration-500">
                            <img src="${p.main_image}" class="w-14 h-14 object-contain rounded-xl bg-surface-container-lowest p-2 group-hover:scale-110 transition-transform">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-black text-[12px] text-on-primary truncate mb-1">${p.name}</h4>
                                <p class="text-secondary font-black text-[11px] mb-1">${p.formatted_price}</p>
                                <p class="text-[9px] text-on-primary opacity-60 leading-tight line-clamp-2">${p.ai_reason}</p>
                            </div>
                            <a href="<?php echo URLROOT; ?>/product/detail/${p.id}" class="self-center p-2 text-secondary/30 hover:text-secondary transition-colors">
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    `;
                    productGrid.insertAdjacentHTML('beforeend', card);
                });
                
                resultsContainer.classList.remove('hidden');
            } else {
                placeholder.classList.remove('hidden');
                alert(data.message);
            }
        } catch (err) {
            loading.classList.add('hidden');
            placeholder.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '<?php echo __('ai_consult_btn', 'HỎI AI NGAY'); ?> <span class="material-symbols-outlined">smart_toy</span>';
            console.error(err);
            if (typeof showToast === 'function') {
                showToast('<?php echo __('server_error', 'Có lỗi xảy ra khi kết nối máy chủ.'); ?>', 'error');
            } else {
                alert('<?php echo __('server_error', 'Có lỗi xảy ra khi kết nối máy chủ.'); ?>');
            }
        }
    }

    // Newsletter form AJAX submission
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const emailInput = document.getElementById('newsletter-email');
            const submitBtn = document.getElementById('newsletter-submit-btn');
            if (!emailInput || !submitBtn) return;

            const email = emailInput.value.trim();
            if (!email) return;

            submitBtn.disabled = true;
            const originalText = submitBtn.textContent;
            submitBtn.textContent = '...';

            const formData = new FormData();
            formData.append('email', email);
            formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            try {
                const response = await fetch('<?php echo URLROOT; ?>/home/subscribe', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    emailInput.value = '';
                } else {
                    showToast(data.message, 'error');
                }
            } catch (err) {
                console.error('Newsletter error:', err);
                showToast('<?php echo __('server_error', 'Có lỗi xảy ra khi kết nối máy chủ.'); ?>', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }

</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
