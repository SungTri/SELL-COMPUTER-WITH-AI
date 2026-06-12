    </main>
    <!-- Footer -->
    <footer class="w-full bg-tertiary pt-section-padding pb-gutter px-gutter mt-auto">
        <div class="max-w-container-max mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12 border-b border-outline/30 pb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="font-h3 text-h3 font-bold text-on-tertiary mb-4"><?php echo $data['app_settings']['store_name'] ?? 'TechExpert'; ?></div>
                    <p class="text-on-tertiary-container mb-6 max-w-sm"><?php echo __('footer_desc', 'Chuyên cung cấp các thiết bị công nghệ cao cấp, linh kiện PC và giải pháp cho các chuyên gia, doanh nghiệp.'); ?></p>
                </div>
                <div>
                    <h4 class="font-label-bold text-label-bold text-on-tertiary mb-4 uppercase tracking-wider"><?php echo __('footer_explore', 'Khám Phá'); ?></h4>
                    <ul class="flex flex-col gap-3">
                        <li><a class="text-on-tertiary-container hover:text-on-tertiary transition-colors duration-200" href="<?php echo URLROOT; ?>/page/about"><?php echo __('footer_about', 'Về chúng tôi'); ?></a></li>
                        <li><a class="text-on-tertiary-container hover:text-on-tertiary transition-colors duration-200" href="<?php echo URLROOT; ?>/product"><?php echo __('footer_new_products', 'Sản phẩm mới'); ?></a></li>
                        <li><a class="text-on-tertiary-container hover:text-on-tertiary transition-colors duration-200" href="<?php echo URLROOT; ?>/product?sale=1"><?php echo __('footer_promotions', 'Khuyến mãi'); ?></a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-label-bold text-label-bold text-on-tertiary mb-4 uppercase tracking-wider"><?php echo __('footer_support', 'Hỗ Trợ'); ?></h4>
                    <ul class="flex flex-col gap-3">
                        <li><a class="text-on-tertiary-container hover:text-on-tertiary transition-colors duration-200" href="<?php echo URLROOT; ?>/page/help"><?php echo __('footer_help_center', 'Trung tâm trợ giúp'); ?></a></li>
                        <li><a class="text-on-tertiary-container hover:text-on-tertiary transition-colors duration-200" href="<?php echo URLROOT; ?>/page/shipping"><?php echo __('footer_shipping_policy', 'Chính sách vận chuyển'); ?></a></li>
                        <li><a class="text-on-tertiary-container hover:text-on-tertiary transition-colors duration-200" href="<?php echo URLROOT; ?>/page/warranty"><?php echo __('footer_warranty_policy', 'Bảo hành & Đổi trả'); ?></a></li>
                        <li><a class="text-on-tertiary-container hover:text-on-tertiary transition-colors duration-200" href="<?php echo URLROOT; ?>/page/contact"><?php echo __('footer_contact', 'Liên hệ'); ?></a></li>
                    </ul>

                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center text-on-tertiary-container text-sm">
                <p>© <?php echo date('Y'); ?> <?php echo $data['app_settings']['store_name'] ?? 'TechExpert'; ?>. <?php echo __('footer_rights', 'Bảo lưu mọi quyền.'); ?></p>
                <div class="flex gap-4 mt-4 md:mt-0">
                    <?php if(!empty($data['app_settings']['facebook_url'])): ?>
                        <a href="<?php echo $data['app_settings']['facebook_url']; ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 hover:border-[#1877F2] hover:bg-[#1877F2] text-on-tertiary transition-all duration-300 flex items-center justify-center group" title="Facebook">
                            <svg class="w-4 h-4 fill-current text-on-tertiary-container group-hover:text-white transition-colors" viewBox="0 0 24 24">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($data['app_settings']['instagram_url'])): ?>
                        <a href="<?php echo $data['app_settings']['instagram_url']; ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 hover:border-[#ee2a7b] hover:bg-gradient-to-tr hover:from-[#f9ce34] hover:via-[#ee2a7b] hover:to-[#6228d7] text-on-tertiary transition-all duration-300 flex items-center justify-center group" title="Instagram">
                            <svg class="w-4 h-4 fill-current text-on-tertiary-container group-hover:text-white transition-colors" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0 3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($data['app_settings']['youtube_url'])): ?>
                        <a href="<?php echo $data['app_settings']['youtube_url']; ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 hover:border-[#FF0000] hover:bg-[#FF0000] text-on-tertiary transition-all duration-300 flex items-center justify-center group" title="YouTube">
                            <svg class="w-4 h-4 fill-current text-on-tertiary-container group-hover:text-white transition-colors" viewBox="0 0 24 24">
                                <path d="M23.498 6.163c-.272-1.023-1.078-1.83-2.101-2.102C19.537 3.5 12 3.5 12 3.5s-7.537 0-9.397.561C1.579 4.333.773 5.14.502 6.163.003 8.01 0 12 0 12s.003 3.99.502 5.837c.271 1.022 1.077 1.829 2.101 2.101C4.463 20.5 12 20.5 12 20.5s7.537 0 9.397-.562c1.023-.272 1.829-1.079 2.101-2.102.5-1.847.498-5.838.498-5.838s.003-3.99-.498-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>

    <!-- Chatbot Widget Modern UI -->
    <div id="chatbot-widget" class="fixed bottom-6 right-6 z-[200]">
        <!-- Chat Window -->
        <div id="chatWindow" class="fixed bottom-24 right-6 w-[460px] max-w-[calc(100vw-48px)] h-[680px] max-h-[calc(100vh-120px)] bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl rounded-[32px] shadow-[0_20px_50px_rgba(0,0,0,0.18)] border border-outline-variant/30 dark:border-outline-variant/15 flex flex-col overflow-hidden hidden transition-all duration-300 transform translate-y-4 opacity-0 z-[200]">
            <!-- Premium Glass Gradient Header with Two Tabs -->
            <div class="bg-gradient-to-r from-blue-600/90 to-blue-500/90 dark:from-blue-700/90 dark:to-blue-600/90 p-6 pb-4 border-b border-white/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center border border-white/30 backdrop-blur-md">
                            <span class="material-symbols-outlined text-white text-xl">forum</span>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="text-white font-bold text-lg leading-tight tracking-wide"><?php echo __('chatbot_title', 'TechExpert Chat'); ?></h3>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse shadow-[0_0_8px_#4ade80]"></span>
                                <span class="text-white/80 text-[10px] font-black uppercase tracking-widest leading-none"><?php echo __('chatbot_status', 'Đang hoạt động'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button onclick="toggleChat()" class="p-2 hover:bg-white/10 active:scale-95 rounded-lg transition-all text-white flex items-center justify-center" title="Minimize">
                            <span class="material-symbols-outlined text-xl">stat_minus_1</span>
                        </button>
                        <button onclick="toggleChat()" class="p-2 hover:bg-white/10 active:scale-95 rounded-lg transition-all text-white flex items-center justify-center" title="Close">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>
                </div>
                
                <!-- Function Tabs (Glassmorphic) -->
                <div class="flex p-1 bg-black/15 rounded-xl border border-white/5 backdrop-blur-sm">
                    <button onclick="switchChatMode('shop')" id="btn-chat-shop" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-[11px] font-bold transition-all text-white hover:bg-white/5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">support_agent</span>
                        <?php echo __('chatbot_mode_shop', 'CHAT VỚI SHOP'); ?>
                    </button>
                    <button onclick="switchChatMode('ai')" id="btn-chat-ai" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-[11px] font-bold transition-all bg-white dark:bg-zinc-800 text-blue-600 dark:text-blue-400 shadow-sm cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">smart_toy</span>
                        <?php echo __('chatbot_mode_ai', 'AI TƯ VẤN'); ?>
                    </button>
                </div>
            </div>

            <!-- Live Chat Banner -->
            <div id="liveChatBanner" class="bg-blue-50 dark:bg-zinc-800/80 px-5 py-3 border-b border-blue-100 dark:border-zinc-700/60 flex items-center justify-between gap-3 shrink-0 hidden">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">support_agent</span>
                    <span class="text-[11px] font-semibold text-gray-700 dark:text-zinc-200">Bạn muốn chat trực tiếp với nhân viên hỗ trợ?</span>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button id="btnRequestLiveSupport" onclick="requestLiveSupport()" class="bg-blue-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-blue-700 cursor-pointer border-0">Gặp nhân viên</button>
                <?php else: ?>
                    <a href="<?php echo URLROOT; ?>/auth/login" class="bg-yellow-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-yellow-700"><?php echo __('login', 'Đăng nhập'); ?></a>
                <?php endif; ?>
            </div>

            <!-- Chat Area -->
            <div id="chatMessages" class="flex-1 p-5 overflow-y-auto flex flex-col gap-5 bg-slate-50/50 dark:bg-zinc-950/40 scroll-smooth">
                <!-- Welcome bubble -->
                <div class="flex gap-3 max-w-[85%] chatbot-bubble-in">
                    <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-zinc-800 flex-shrink-0 flex items-center justify-center border border-blue-100 dark:border-zinc-700">
                        <span class="material-symbols-outlined text-sm text-[#356ee7] dark:text-blue-400">smart_toy</span>
                    </div>
                    <div class="bg-white dark:bg-zinc-850 p-5 rounded-2xl rounded-tl-none shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-outline-variant/20 dark:border-outline-variant/10">
                        <p class="text-sm text-gray-700 dark:text-zinc-200 leading-relaxed"><?php echo __('chatbot_welcome', 'Xin chào! Tôi là chuyên gia <strong>Tư vấn Build PC</strong> của TechExpert. 🛠️'); ?></p>
                        <p class="text-sm text-gray-700 dark:text-zinc-200 mt-2"><?php echo __('chatbot_intro', 'Hãy cho tôi biết ngân sách của bạn (Ví dụ: <strong>20 triệu</strong>), tôi sẽ giúp bạn chọn những linh kiện tốt nhất và tương thích nhất!'); ?></p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[9px] text-gray-400 dark:text-zinc-500 font-medium uppercase"><?php echo __('just_now', 'Vừa xong'); ?></span>
                            <button onclick="speakText(this)" class="w-6 h-6 rounded-full flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all border-0 bg-transparent cursor-pointer" title="Đọc câu trả lời">
                                <span class="material-symbols-outlined text-[14px]">volume_up</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typing Indicator (Enhanced) -->
            <div id="typingIndicator" class="px-5 py-2 hidden chatbot-bubble-in flex gap-3 max-w-[85%]">
                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-zinc-800 flex-shrink-0 flex items-center justify-center border border-blue-100 dark:border-zinc-700">
                    <span class="material-symbols-outlined text-sm text-[#356ee7] dark:text-blue-400" id="typing-icon">smart_toy</span>
                </div>
                <div class="bg-white dark:bg-zinc-850 px-4 py-3 rounded-2xl rounded-tl-none shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-outline-variant/20 dark:border-outline-variant/10 flex items-center gap-2">
                    <div class="flex gap-1 items-center">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-zinc-400 font-medium ml-1" id="typing-text"><?php echo __('chatbot_typing', 'TechExpert đang trả lời...'); ?></span>
                </div>
            </div>

            <!-- Horizontal Suggestion Chips Container (Modern Floating UI) -->
            <div id="chatSuggestions" class="px-5 py-3 flex gap-2 overflow-x-auto bg-slate-50/50 dark:bg-zinc-950/20 border-t border-outline-variant/10">
                <!-- Dynamic suggestion chips go here -->
            </div>

            <!-- Input Area -->
            <div class="p-5 bg-white dark:bg-zinc-900 border-t border-outline-variant/20 dark:border-outline-variant/10">
                <div class="relative group flex items-center">
                    <button onclick="toggleVoiceInput()" id="btnVoiceInput"
                        class="absolute left-2.5 top-1/2 -translate-y-1/2 w-9 h-9 text-gray-400 dark:text-zinc-400 rounded-xl flex items-center justify-center hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all active:scale-95 cursor-pointer border-0 bg-transparent z-10"
                        title="Nói câu hỏi của bạn">
                        <span class="material-symbols-outlined text-xl">mic</span>
                    </button>
                    <input id="chatInput" 
                        class="w-full pl-12 pr-14 py-3.5 bg-gray-50 dark:bg-zinc-800/50 border border-gray-200 dark:border-zinc-700/60 rounded-2xl text-sm dark:text-zinc-100 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-zinc-500" 
                        placeholder="<?php echo __('chatbot_placeholder', 'Nhập câu hỏi của bạn...'); ?>" 
                        type="text"
                        autocomplete="off"/>
                    <button onclick="sendMessage()" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/20 transition-all active:scale-95 cursor-pointer">
                        <span class="material-symbols-outlined text-xl">send</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Premium FAB with Pulsing Ring -->
        <button onclick="toggleChat()" 
            class="w-16 h-16 bg-gradient-to-tr from-blue-600 to-blue-500 text-white rounded-full shadow-[0_8px_25px_rgba(37,99,235,0.45)] flex items-center justify-center hover:scale-110 hover:shadow-[0_12px_30px_rgba(37,99,235,0.55)] active:scale-95 transition-all duration-300 relative group cursor-pointer">
            <span class="absolute inset-0 rounded-full bg-blue-500/20 animate-ping group-hover:duration-1000"></span>
            <span class="material-symbols-outlined text-3xl group-hover:rotate-12 transition-transform duration-300">chat_bubble</span>
        </button>
    </div>

    <style>
        @keyframes bubbleScaleIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .chatbot-bubble-in { 
            animation: bubbleScaleIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; 
        }
        
        @keyframes chipFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #chatMessages::-webkit-scrollbar { width: 4px; }
        #chatMessages::-webkit-scrollbar-track { background: transparent; }
        #chatMessages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        #chatMessages { overflow-x: hidden; padding: 20px 24px !important; }
        #chatWindow.show { display: flex; opacity: 1; transform: translateY(0); }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
        
        #chatSuggestions::-webkit-scrollbar { height: 4px; }
        #chatSuggestions::-webkit-scrollbar-track { background: transparent; }
        #chatSuggestions::-webkit-scrollbar-thumb { background: rgba(59, 130, 246, 0.2); border-radius: 10px; }
        #chatSuggestions::-webkit-scrollbar-thumb:hover { background: rgba(59, 130, 246, 0.4); }
        #chatSuggestions {
            scrollbar-width: thin;
            scrollbar-color: rgba(59, 130, 246, 0.2) transparent;
        }
    </style>

    <!-- Compare Bar -->
    <div id="compare-bar" class="fixed bottom-0 left-0 w-full bg-surface-container-lowest/90 backdrop-blur-xl border-t border-outline-variant/30 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] z-[150] transition-all duration-500 transform translate-y-full hidden">
        <div class="max-w-container-max mx-auto px-gutter py-4 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-outline uppercase tracking-widest"><?php echo __('compare_title', 'So sánh sản phẩm'); ?></span>
                    <span id="compare-count" class="text-sm font-black text-secondary">0/4 <?php echo __('compare_selected', 'sản phẩm'); ?></span>
                </div>
                <div id="compare-items" class="flex items-center gap-3">
                    <!-- Compare items will be injected here -->
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="clearCompare()" class="text-sm font-bold text-on-surface-variant hover:text-error transition-colors"><?php echo __('clear_all', 'Xóa tất cả'); ?></button>
                <a href="<?php echo URLROOT; ?>/compare" class="px-8 py-3 bg-secondary text-white rounded-xl font-bold hover:shadow-[0_0_20px_rgba(53,110,231,0.4)] transition-all"><?php echo __('compare_now', 'So sánh ngay'); ?></a>
            </div>
        </div>
    </div>

    <script>
        const currentLang = '<?php echo $_SESSION['lang'] ?? 'vi'; ?>';
        const currentUserId = '<?php echo $_SESSION['user_id'] ?? ''; ?>';
        function toggleChat() {
            const chatWindow = document.getElementById('chatWindow');
            if (chatWindow.classList.contains('hidden')) {
                chatWindow.classList.remove('hidden');
                setTimeout(() => chatWindow.classList.add('show'), 10);
                sessionStorage.setItem('chat_open', 'true');
            } else {
                chatWindow.classList.remove('show');
                setTimeout(() => chatWindow.classList.add('hidden'), 300);
                sessionStorage.setItem('chat_open', 'false');
            }
        }

        let currentMode = 'ai';
        function switchChatMode(mode) {
            const oldMode = currentMode;
            currentMode = mode;
            const btnShop = document.getElementById('btn-chat-shop');
            const btnAi = document.getElementById('btn-chat-ai');
            const messages = document.getElementById('chatMessages');
            const liveChatBanner = document.getElementById('liveChatBanner');

            if (messages && oldMode) {
                // Save current content of the old mode
                sessionStorage.setItem('chat_history_' + oldMode, messages.innerHTML);
            }

            if (mode === 'shop') {
                btnShop.className = 'flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-[11px] font-bold transition-all bg-white dark:bg-zinc-800 text-blue-600 dark:text-blue-400 shadow-sm cursor-pointer';
                btnAi.className = 'flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-[11px] font-bold transition-all text-white hover:bg-white/5 cursor-pointer';
                
                // Show live chat banner if not already in active session
                if (liveChatPollInterval) {
                    if (liveChatBanner) liveChatBanner.classList.add('hidden');
                } else {
                    if (liveChatBanner) liveChatBanner.classList.remove('hidden');
                }

                // If polling is running, let the poller do the rendering
                if (liveChatPollInterval) {
                    syncLiveChat(true);
                } else {
                    const savedShopHistory = sessionStorage.getItem('chat_history_shop');
                    if (savedShopHistory) {
                        messages.innerHTML = savedShopHistory;
                    } else {
                        messages.innerHTML = `
                            <div class="flex gap-3 max-w-[85%] chatbot-bubble-in">
                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-zinc-850 flex-shrink-0 flex items-center justify-center border border-blue-100 dark:border-zinc-700">
                                    <span class="material-symbols-outlined text-sm text-[#356ee7] dark:text-blue-400">support_agent</span>
                                </div>
                                <div class="bg-white dark:bg-zinc-850 p-5 rounded-2xl rounded-tl-none shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-outline-variant/20 dark:border-outline-variant/10">
                                    <p class="text-sm text-gray-700 dark:text-zinc-200 leading-relaxed"><?php echo __('chatbot_shop_welcome', 'Xin chào! Tôi là <strong>An Bá Tử Khang</strong>, trợ lý tư vấn chăm sóc khách hàng của TechExpert. 🌸'); ?></p>
                                    <p class="text-sm text-gray-700 dark:text-zinc-200 mt-2"><?php echo __('chatbot_shop_intro', 'Em có thể giúp gì cho anh/chị về thời gian làm việc, chính sách giao hàng, bảo hành hay kiểm tra tình trạng đơn hàng ạ?'); ?></p>
                                    <span class="text-[9px] text-gray-400 dark:text-zinc-500 mt-3 block font-medium uppercase"><?php echo __('just_now', 'Vừa xong'); ?></span>
                                </div>
                            </div>
                        `;
                    }
                }
            } else {
                btnAi.className = 'flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-[11px] font-bold transition-all bg-white dark:bg-zinc-800 text-blue-600 dark:text-blue-400 shadow-sm cursor-pointer';
                btnShop.className = 'flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg text-[11px] font-bold transition-all text-white hover:bg-white/5 cursor-pointer';
                
                if (liveChatBanner) liveChatBanner.classList.add('hidden');

                const savedAiHistory = sessionStorage.getItem('chat_history_ai');
                if (savedAiHistory) {
                    messages.innerHTML = savedAiHistory;
                } else {
                    messages.innerHTML = `
                        <div class="flex gap-3 max-w-[85%] chatbot-bubble-in">
                            <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-zinc-850 flex-shrink-0 flex items-center justify-center border border-blue-100 dark:border-zinc-700">
                                <span class="material-symbols-outlined text-sm text-[#356ee7] dark:text-blue-400">smart_toy</span>
                            </div>
                            <div class="bg-white dark:bg-zinc-850 p-5 rounded-2xl rounded-tl-none shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-outline-variant/20 dark:border-outline-variant/10">
                                <p class="text-sm text-gray-700 dark:text-zinc-200 leading-relaxed"><?php echo __('chatbot_welcome', 'Xin chào! Tôi là chuyên gia <strong>Tư vấn Build PC</strong> của TechExpert. 🛠️'); ?></p>
                                <p class="text-sm text-gray-700 dark:text-zinc-200 mt-2"><?php echo __('chatbot_intro', 'Hãy cho tôi biết ngân sách của bạn (Ví dụ: <strong>20 triệu</strong>), tôi sẽ giúp bạn chọn những linh kiện tốt nhất và tương thích nhất!'); ?></p>
                                <span class="text-[9px] text-gray-400 dark:text-zinc-500 mt-3 block font-medium uppercase"><?php echo __('just_now', 'Vừa xong'); ?></span>
                            </div>
                        </div>
                    `;
                }
            }
            renderSuggestionChips(mode);
            messages.scrollTop = messages.scrollHeight;
            sessionStorage.setItem('chat_mode', mode);
        }

        function renderSuggestionChips(mode) {
            const container = document.getElementById('chatSuggestions');
            if (!container) return;
            
            let chips = [];
            if (mode === 'ai') {
                chips = [
                    { text: currentLang === 'vi' ? 'Build PC Gaming 15 triệu' : 'Build 15M Gaming PC', icon: 'sports_esports' },
                    { text: currentLang === 'vi' ? 'PC làm đồ họa 25 triệu' : '25M Design PC build', icon: 'palette' },
                    { text: currentLang === 'vi' ? 'Chọn CPU Intel hay AMD?' : 'Intel or AMD CPU?', icon: 'memory' },
                    { text: currentLang === 'vi' ? 'Tư vấn chọn cấu hình RAM' : 'RAM upgrade advice', icon: 'hardware' }
                ];
            } else {
                chips = [
                    { text: currentLang === 'vi' ? 'Chính sách bảo hành' : 'Warranty policy', icon: 'verified_user' },
                    { text: currentLang === 'vi' ? 'Giờ mở cửa & Địa chỉ' : 'Opening hours & Address', icon: 'schedule' },
                    { text: currentLang === 'vi' ? 'Phí giao hàng theo tỉnh thành' : 'Shipping fees by province', icon: 'local_shipping' },
                    { text: currentLang === 'vi' ? 'Thông tin chuyển khoản' : 'Bank transfer details', icon: 'account_balance' }
                ];
            }
            
            container.innerHTML = chips.map((c, index) => `
                <button onclick="sendQuickQuestion('${c.text}')" 
                    class="suggestion-chip shrink-0 px-3 py-1.5 bg-white dark:bg-zinc-800 text-gray-700 dark:text-zinc-200 text-[11px] font-bold rounded-full border border-gray-200 dark:border-zinc-700 hover:border-blue-500 hover:text-blue-600 dark:hover:border-blue-400 dark:hover:text-blue-300 hover:shadow-sm transition-all duration-300 flex items-center gap-1.5 transform translate-y-2 opacity-0 active:scale-95 cursor-pointer whitespace-nowrap"
                    style="animation: chipFadeIn 0.3s ease forwards ${index * 0.05}s">
                    <span class="material-symbols-outlined text-[14px]">${c.icon}</span>
                    ${c.text}
                </button>
            `).join('');
        }

        function sendQuickQuestion(text) {
            const input = document.getElementById('chatInput');
            if (input) {
                input.value = text;
                sendMessage();
            }
        }

        async function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            const typing = document.getElementById('typingIndicator');

            if (!message) return;

            if (currentMode !== 'shop') {
                appendMessage('user', message);
            }
            input.value = '';
            
            // Set dynamic typing icon and text
            const typingIcon = document.getElementById('typing-icon');
            const typingText = document.getElementById('typing-text');
            if (typingIcon) {
                typingIcon.innerText = currentMode === 'shop' ? 'support_agent' : 'smart_toy';
            }
            if (typingText) {
                typingText.innerText = currentMode === 'shop' 
                    ? (currentLang === 'vi' ? 'An Bá Tử Khang đang trả lời...' : 'An Ba Tu Khang is typing...')
                    : (currentLang === 'vi' ? 'TechExpert đang trả lời...' : 'TechExpert is typing...');
            }
            
            typing.classList.remove('hidden');

            try {
                const formData = new FormData();
                formData.append('message', message);
                formData.append('mode', currentMode);

                const response = await fetch('<?php echo URLROOT; ?>/chatbot/sendMessage', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                const text = await response.text();
                typing.classList.add('hidden');
                
                let data;
                try {
                    data = JSON.parse(text.trim());
                } catch (e) {
                    appendMessage('bot', '<?php echo __('server_error', 'Có lỗi xảy ra khi kết nối máy chủ.'); ?>');
                    return;
                }

                if (data.status === 'success') {
                    if (data.live_chat) {
                        syncLiveChat(true);
                    } else {
                        appendMessage('bot', data.bot_response, data.time);
                    }
                } else {
                    appendMessage('bot', 'Lỗi: ' + (data.message || 'Không xác định'));
                }
            } catch (error) {
                typing.classList.add('hidden');
                console.error('Error:', error);
            }
        }

        function appendMessage(sender, text, time = '<?php echo __('just_now', 'Vừa xong'); ?>') {
            const messages = document.getElementById('chatMessages');
            const msgDiv = document.createElement('div');
            
            let formattedText = text;
            if (!(text.includes('<a') || text.includes('<strong>'))) {
                formattedText = formattedText.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
                formattedText = formattedText.replace(/\[([^\]\n]+)\]\((https?:\/\/[^\s)]+)\)/g, '<a href="$2" class="text-blue-600 dark:text-blue-400 font-bold underline hover:text-blue-800">$1</a>');
                formattedText = formattedText.replace(/(?<!href=")(https?:\/\/[^\s\]\)\>]+)/g, '<a href="$1" class="text-blue-600 dark:text-blue-400 font-bold underline hover:text-blue-800">$1</a>');
                formattedText = formattedText.replace(/\n/g, '<br>');
            }
            
            if (sender === 'user') {
                msgDiv.className = 'flex justify-end chatbot-bubble-in';
                msgDiv.innerHTML = `
                    <div class="bg-blue-600 dark:bg-blue-700 text-white p-5 rounded-2xl rounded-tr-none shadow-md max-w-[80%]">
                        <p class="text-sm leading-relaxed" style="overflow-wrap: anywhere; word-break: break-word;">${formattedText}</p>
                        <span class="text-[9px] text-white/60 mt-2 block text-right font-medium uppercase">${time}</span>
                    </div>
                `;
            } else {
                // Parse Product Tags
                const productRegex = /\[PRODUCT:(\d+)\|([^|]+)\|([^|]+)\|([^|]+)\|([^\]]+)\]/g;
                const products = [];
                let match;
                
                while ((match = productRegex.exec(text)) !== null) {
                    products.push({
                        id: match[1],
                        name: match[2],
                        price: match[3],
                        image: match[4],
                        link: match[5]
                    });
                }

                // Remove tags from text for cleaner display
                const cleanText = formattedText.replace(/\[PRODUCT:[^\]]+\]/g, '');

                msgDiv.className = 'flex gap-3 max-w-[90%] chatbot-bubble-in';
                let productsHtml = '';
                
                if (products.length > 0) {
                    productsHtml = `
                        <div class="flex flex-col gap-3 mt-4 w-full">
                            ${products.map(p => `
                                <div class="bg-blue-50/50 dark:bg-zinc-800/30 border border-blue-100 dark:border-zinc-700/60 rounded-2xl p-3 flex gap-4 hover:shadow-md transition-all group/card">
                                    <div class="w-16 h-16 bg-white dark:bg-zinc-800 rounded-xl flex-shrink-0 border border-outline-variant/20 dark:border-outline-variant/10 overflow-hidden">
                                        <img src="${p.image}" class="w-full h-full object-contain p-1 group-hover/card:scale-110 transition-transform" onerror="this.src='https://placehold.co/400x400?text=No+Image'">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[12px] font-bold text-gray-800 dark:text-zinc-200 line-clamp-1 mb-0.5">${p.name}</p>
                                        <p class="text-[11px] font-black text-blue-600 dark:text-blue-400">${p.price}</p>
                                        <div class="flex gap-2 mt-2">
                                            <button onclick="addToCart(${p.id})" class="flex-1 bg-blue-600 text-white text-[10px] font-bold py-1.5 rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">${currentLang === 'vi' ? 'THÊM GIỎ HÀNG' : 'ADD TO CART'}</button>
                                            <a href="${p.link}" class="w-8 h-8 bg-white dark:bg-zinc-800 border border-outline-variant/20 dark:border-outline-variant/10 text-gray-400 rounded-lg flex items-center justify-center hover:text-blue-600 hover:border-blue-600 dark:hover:text-blue-400 dark:hover:border-blue-400 transition-all">
                                                <span class="material-symbols-outlined text-[16px]">visibility</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                            ${products.length > 1 ? `
                                <button onclick="addAllToCart(${JSON.stringify(products.map(p => p.id)).replace(/"/g, "'")})" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white text-[11px] font-black py-3 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all active:scale-[0.98] mt-1 flex items-center justify-center gap-2 cursor-pointer border-0">
                                    <span class="material-symbols-outlined text-[18px]">shopping_cart_checkout</span>
                                    <?php echo __('add_all_to_cart', 'THÊM TẤT CẢ VÀO GIỎ HÀNG'); ?>
                                </button>
                            ` : ''}
                        </div>
                    `;
                }

                const iconName = currentMode === 'shop' ? 'support_agent' : 'smart_toy';

                msgDiv.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-zinc-850 flex-shrink-0 flex items-center justify-center border border-blue-100 dark:border-zinc-700">
                        <span class="material-symbols-outlined text-sm text-[#356ee7] dark:text-blue-400">${iconName}</span>
                    </div>
                    <div class="flex flex-col gap-1 w-full">
                        <div class="bg-white dark:bg-zinc-850 p-5 rounded-2xl rounded-tl-none shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-outline-variant/20 dark:border-outline-variant/10">
                            <p class="text-sm text-gray-700 dark:text-zinc-200 leading-relaxed" style="overflow-wrap: anywhere; word-break: break-word;">${cleanText}</p>
                            ${productsHtml}
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-[9px] text-gray-400 dark:text-zinc-500 font-medium uppercase">${time}</span>
                                <button onclick="speakText(this)" class="w-6 h-6 rounded-full flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all border-0 bg-transparent cursor-pointer" title="Đọc câu trả lời">
                                    <span class="material-symbols-outlined text-[14px]">volume_up</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            messages.appendChild(msgDiv);
            messages.scrollTop = messages.scrollHeight;
            sessionStorage.setItem('chat_history_' + currentMode, messages.innerHTML);
            sessionStorage.setItem('chat_user_id', '<?php echo $_SESSION['user_id'] ?? ''; ?>');
        }

        async function addAllToCart(productIds) {
            try {
                showToast(currentLang === 'vi' ? 'Đang thêm trọn bộ cấu hình...' : 'Adding full configuration...', 'success');
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
                    updateCartCount(data.cart_count);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Lỗi khi thêm cấu hình', 'error');
                }
            } catch (error) {
                console.error('Error in bulkAdd:', error);
                showToast('Lỗi kết nối máy chủ', 'error');
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const savedUserId = sessionStorage.getItem('chat_user_id');
            const isOpen = sessionStorage.getItem('chat_open');
            const savedMode = sessionStorage.getItem('chat_mode') || 'ai';
            const chatWindow = document.getElementById('chatWindow');
            const savedLang = sessionStorage.getItem('chat_lang');

            // Reset chatbot if language has changed
            if (savedLang !== currentLang) {
                sessionStorage.removeItem('chat_history_ai');
                sessionStorage.removeItem('chat_history_shop');
                sessionStorage.setItem('chat_lang', currentLang);
            }

            // Reset chatbot if user has changed (logged out/switched account)
            if (savedUserId !== currentUserId) {
                sessionStorage.removeItem('chat_history_ai');
                sessionStorage.removeItem('chat_history_shop');
                sessionStorage.removeItem('chat_user_id');
                sessionStorage.removeItem('chat_open');
                sessionStorage.removeItem('chat_mode');
                switchChatMode('ai');
            } else {
                switchChatMode(savedMode);
            }

            if (isOpen === 'true' && chatWindow) {
                toggleChat();
            }

            // check active session
            checkActiveLiveChatOnLoad();

            // Drag-to-scroll for suggestions
            const suggestionsContainer = document.getElementById('chatSuggestions');
            if (suggestionsContainer) {
                let isDown = false;
                let startX;
                let scrollLeft;
                let moved = false;
                let startY;

                suggestionsContainer.addEventListener('mousedown', (e) => {
                    isDown = true;
                    moved = false;
                    startX = e.pageX - suggestionsContainer.offsetLeft;
                    startY = e.pageY - suggestionsContainer.offsetTop;
                    scrollLeft = suggestionsContainer.scrollLeft;
                    suggestionsContainer.style.scrollBehavior = 'auto';
                });

                suggestionsContainer.addEventListener('mouseleave', () => {
                    isDown = false;
                });

                suggestionsContainer.addEventListener('mouseup', () => {
                    isDown = false;
                    suggestionsContainer.style.scrollBehavior = 'smooth';
                });

                suggestionsContainer.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    const x = e.pageX - suggestionsContainer.offsetLeft;
                    const y = e.pageY - suggestionsContainer.offsetTop;
                    const walkX = x - startX;
                    const walkY = y - startY;
                    if (Math.abs(walkX) > 5 || Math.abs(walkY) > 5) {
                        moved = true;
                        e.preventDefault();
                        suggestionsContainer.scrollLeft = scrollLeft - walkX * 1.2;
                    }
                });

                suggestionsContainer.addEventListener('click', (e) => {
                    if (moved) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                }, true);
            }
        });

        document.getElementById('chatInput')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        let liveChatPollInterval = null;

        function startLiveChatPolling() {
            if (liveChatPollInterval) clearInterval(liveChatPollInterval);
            liveChatLastMsgCount = 0;
            sessionStorage.setItem('chat_last_msg_count', '0');
            liveChatPollInterval = setInterval(syncLiveChat, 3000);
            syncLiveChat();
        }

        function stopLiveChatPolling() {
            if (liveChatPollInterval) {
                clearInterval(liveChatPollInterval);
                liveChatPollInterval = null;
            }
            liveChatLastMsgCount = 0;
            sessionStorage.removeItem('chat_last_msg_count');
        }

        async function requestLiveSupport() {
            const btn = document.getElementById('btnRequestLiveSupport');
            if (btn) {
                btn.setAttribute('disabled', 'true');
                btn.innerText = 'Đang kết nối...';
            }
            try {
                const response = await fetch('<?php echo URLROOT; ?>/chatbot/requestSupport', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const data = await response.json();
                if (data.status === 'success') {
                    startLiveChatPolling();
                    const liveChatBanner = document.getElementById('liveChatBanner');
                    if (liveChatBanner) liveChatBanner.classList.add('hidden');
                } else {
                    alert(data.message || 'Có lỗi xảy ra.');
                    if (btn) {
                        btn.removeAttribute('disabled');
                        btn.innerText = 'GẶP NHÂN VIÊN HỖ TRỢ';
                    }
                }
            } catch (e) {
                console.error(e);
                if (btn) {
                    btn.removeAttribute('disabled');
                    btn.innerText = 'GẶP NHÂN VIÊN HỖ TRỢ';
                }
            }
        }

        // Track how many messages have already been rendered — avoids full re-render flicker
        let liveChatLastMsgCount = parseInt(sessionStorage.getItem('chat_last_msg_count') || '0', 10);

        function buildLiveChatBubble(m, withAnim = true) {
            const isMe = m.sender === 'user';
            let formattedText = m.message;
            formattedText = formattedText.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
            formattedText = formattedText.replace(/\n/g, '<br>');
            const animClass = withAnim ? 'chatbot-bubble-in' : '';

            if (isMe) {
                return `
                    <div class="flex justify-end ${animClass}">
                        <div class="bg-blue-600 dark:bg-blue-700 text-white p-5 rounded-2xl rounded-tr-none shadow-md max-w-[80%]">
                            <p class="text-sm leading-relaxed" style="overflow-wrap: anywhere; word-break: break-word;">${formattedText}</p>
                            <span class="text-[9px] text-white/60 mt-2 block text-right font-medium uppercase">${m.time}</span>
                        </div>
                    </div>
                `;
            } else {
                const isSystem = m.message.includes('yêu cầu hỗ trợ') || m.message.includes('Nhân viên hỗ trợ đã tham gia') || m.message.includes('Phiên hỗ trợ trực tuyến đã được đóng');
                if (isSystem) {
                    return `
                        <div class="flex justify-center ${animClass} my-2">
                            <div class="bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 px-4 py-2 rounded-xl text-xs font-semibold max-w-[90%] text-center border border-outline-variant/10">
                                ${formattedText}
                            </div>
                        </div>
                    `;
                }
                return `
                    <div class="flex gap-3 max-w-[90%] ${animClass}">
                        <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-zinc-850 flex-shrink-0 flex items-center justify-center border border-blue-100 dark:border-zinc-700">
                            <span class="material-symbols-outlined text-sm text-[#356ee7] dark:text-blue-400">support_agent</span>
                        </div>
                        <div class="flex flex-col gap-1 w-full">
                            <div class="bg-white dark:bg-zinc-850 p-5 rounded-2xl rounded-tl-none shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-outline-variant/20 dark:border-outline-variant/10">
                                <p class="text-sm text-gray-700 dark:text-zinc-200 leading-relaxed" style="overflow-wrap: anywhere; word-break: break-word;">${formattedText}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-[9px] text-gray-400 dark:text-zinc-500 font-medium uppercase">${m.time}</span>
                                    <button onclick="speakText(this)" class="w-6 h-6 rounded-full flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all border-0 bg-transparent cursor-pointer" title="Đọc câu trả lời">
                                        <span class="material-symbols-outlined text-[14px]">volume_up</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        async function syncLiveChat(forceScroll = false) {
            if (currentMode !== 'shop') {
                stopLiveChatPolling();
                return;
            }
            try {
                const response = await fetch('<?php echo URLROOT; ?>/chatbot/getHistory');
                const data = await response.json();
                if (data.status === 'success') {
                    const messagesContainer = document.getElementById('chatMessages');
                    const supportStatus = data.support_status;
                    
                    if (supportStatus === 'closed') {
                        stopLiveChatPolling();
                        const btnAi = document.getElementById('btn-chat-ai');
                        if (btnAi) {
                            btnAi.style.pointerEvents = 'auto';
                            btnAi.style.opacity = '1';
                        }
                        const statusText = document.querySelector('.tracking-widest.leading-none');
                        if (statusText) {
                            statusText.innerText = '<?php echo __('chatbot_status', 'Đang hoạt động'); ?>';
                        }
                        sessionStorage.removeItem('chat_history_shop');
                        sessionStorage.removeItem('chat_last_msg_count');
                        switchChatMode('shop');
                        return;
                    }
                    
                    const btnAi = document.getElementById('btn-chat-ai');
                    if (btnAi) {
                        btnAi.style.pointerEvents = 'none';
                        btnAi.style.opacity = '0.5';
                    }
                    
                    const suggestions = document.getElementById('chatSuggestions');
                    if (suggestions) suggestions.innerHTML = '';
                    
                    const statusText = document.querySelector('.tracking-widest.leading-none');
                    if (statusText) {
                        if (supportStatus === 'pending') {
                            statusText.innerText = 'ĐANG KẾT NỐI...';
                        } else if (supportStatus === 'active') {
                            statusText.innerText = 'NV ' + data.admin_name.toUpperCase() + ' ĐANG HỖ TRỢ';
                        }
                    }

                    const messages = data.messages;
                    const newCount = messages.length;

                    // If count is out of sync or reset, clear
                    if (liveChatLastMsgCount > newCount) {
                        liveChatLastMsgCount = 0;
                    }

                    // First load / initial render
                    if (liveChatLastMsgCount === 0) {
                        messagesContainer.innerHTML = '';
                        if (newCount === 0) {
                            messagesContainer.innerHTML = `
                                <div class="flex gap-3 max-w-[85%]">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-zinc-850 flex-shrink-0 flex items-center justify-center border border-blue-100 dark:border-zinc-700">
                                        <span class="material-symbols-outlined text-sm text-[#356ee7] dark:text-blue-400">support_agent</span>
                                    </div>
                                    <div class="bg-white dark:bg-zinc-850 p-5 rounded-2xl rounded-tl-none shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-outline-variant/20 dark:border-outline-variant/10">
                                        <p class="text-sm text-gray-700 dark:text-zinc-200">Đang kết nối với nhân viên hỗ trợ. Vui lòng chờ giây lát...</p>
                                    </div>
                                </div>
                            `;
                        } else {
                            // Render all existing messages without slide-in animation to avoid flash
                            let initialHtml = '';
                            messages.forEach(m => {
                                initialHtml += buildLiveChatBubble(m, false);
                            });
                            messagesContainer.innerHTML = initialHtml;
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }
                        liveChatLastMsgCount = newCount;
                    } else if (newCount > liveChatLastMsgCount) {
                        // Append new messages
                        if (messagesContainer.innerHTML.includes('Đang kết nối với nhân viên hỗ trợ')) {
                            messagesContainer.innerHTML = '';
                        }

                        // Remove active slide-in classes from previous bubbles
                        const existingAnimatedBubbles = messagesContainer.querySelectorAll('.chatbot-bubble-in');
                        existingAnimatedBubbles.forEach(el => el.classList.remove('chatbot-bubble-in'));

                        const wasAtBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop <= messagesContainer.clientHeight + 100;
                        
                        for (let i = liveChatLastMsgCount; i < newCount; i++) {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = buildLiveChatBubble(messages[i], true).trim();
                            while (tempDiv.firstChild) {
                                messagesContainer.appendChild(tempDiv.firstChild);
                            }
                        }

                        liveChatLastMsgCount = newCount;
                        if (wasAtBottom || forceScroll) {
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }
                    }

                    sessionStorage.setItem('chat_history_shop', messagesContainer.innerHTML);
                    sessionStorage.setItem('chat_last_msg_count', liveChatLastMsgCount.toString());
                }
            } catch (e) {
                console.error("Error syncing live chat:", e);
            }
        }

        async function checkActiveLiveChatOnLoad() {
            if (!currentUserId) return;
            try {
                const response = await fetch('<?php echo URLROOT; ?>/chatbot/getHistory');
                const data = await response.json();
                if (data.status === 'success' && (data.support_status === 'pending' || data.support_status === 'active')) {
                    startLiveChatPolling();
                    if (sessionStorage.getItem('chat_mode') === 'shop') {
                        switchChatMode('shop');
                    }
                }
            } catch (e) {
                console.error(e);
            }
        }

        // Global Wishlist Toggle Logic
        async function toggleWishlist(btn, productId) {
            try {
                const response = await fetch(`<?php echo URLROOT; ?>/wishlist/toggle/${productId}`);
                const data = await response.json();

                if (data.status === 'success') {
                    // Find all buttons for this product on the page
                    const buttons = document.querySelectorAll(`.wishlist-btn[data-product-id="${productId}"]`);
                    
                    buttons.forEach(b => {
                        const icon = b.querySelector('.material-symbols-outlined');
                        if (data.action === 'added') {
                            b.classList.add('text-red-500');
                            if (icon) icon.classList.add('fill-1');
                        } else {
                            b.classList.remove('text-red-500');
                            if (icon) icon.classList.remove('fill-1');
                        }
                    });
                } else if (data.code === 'unauthorized') {
                    window.location.href = '<?php echo URLROOT; ?>/auth/login';
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                console.error('Error toggling wishlist:', error);
            }
        }

        // Global Compare Logic
        async function toggleCompare(productId) {
            try {
                const response = await fetch(`<?php echo URLROOT; ?>/compare/toggle/${productId}`);
                const data = await response.json();

                if (data.status === 'success') {
                    updateCompareBar();
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                console.error('Error toggling compare:', error);
            }
        }

        async function updateCompareBar() {
            try {
                const response = await fetch('<?php echo URLROOT; ?>/compare/getList');
                const data = await response.json();
                
                const bar = document.getElementById('compare-bar');
                const count = document.getElementById('compare-count');
                const itemsList = document.getElementById('compare-items');
                
                if (data.status === 'success' && data.products.length > 0) {
                    bar.classList.remove('hidden', 'translate-y-full');
                    count.textContent = `${data.products.length}/4 ${currentLang === 'vi' ? 'sản phẩm' : 'items'}`;
                    
                    itemsList.innerHTML = data.products.map(p => `
                        <div class="relative group/compare w-12 h-12 bg-surface-container-lowest rounded-lg border border-outline-variant/20 dark:border-outline-variant/10 p-1">
                            <img src="${p.main_image}" class="w-full h-full object-contain">
                            <button onclick="toggleCompare(${p.id})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center opacity-0 group-hover/compare:opacity-100 transition-opacity shadow-sm">
                                <span class="material-symbols-outlined text-[10px]">close</span>
                            </button>
                        </div>
                    `).join('');

                    // Update button states on page
                    const compareBtns = document.querySelectorAll('.compare-btn');
                    compareBtns.forEach(btn => {
                        const pid = btn.dataset.productId;
                        if (data.products.some(p => p.id == pid)) {
                            btn.classList.add('bg-secondary', 'text-white');
                            btn.classList.remove('bg-white', 'text-secondary');
                        } else {
                            btn.classList.remove('bg-secondary', 'text-white');
                            btn.classList.add('bg-white', 'text-secondary');
                        }
                    });
                } else {
                    bar.classList.add('translate-y-full');
                    setTimeout(() => bar.classList.add('hidden'), 500);
                    
                    const compareBtns = document.querySelectorAll('.compare-btn');
                    compareBtns.forEach(btn => {
                        btn.classList.remove('bg-secondary', 'text-white');
                        btn.classList.add('bg-white', 'text-secondary');
                    });
                }
            } catch (error) {
                console.error('Error updating compare bar:', error);
            }
        }

        // Initialize compare bar
        updateCompareBar();

        // Global Add to Cart Logic
        async function addToCart(productId, quantity = 1) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            try {
                const response = await fetch('<?php echo URLROOT; ?>/cart/add', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const data = await response.json();

                if (data.status === 'success') {
                    // Update cart count in header
                    updateCartCount(data.cart_count);
                    
                    // Optional: Show a beautiful toast notification
                    showToast(data.message || 'Đã thêm vào giỏ hàng', 'success');
                } else {
                    showToast(data.message || 'Lỗi khi thêm vào giỏ hàng', 'error');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
            }
        }

        function updateCartCount(count) {
            const cartBadges = document.querySelectorAll('.cart-count-badge');
            cartBadges.forEach(badge => {
                if (count > 0) {
                    badge.textContent = count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            });
            
            // If badge doesn't exist, we might need to create it (rare case)
            if (cartBadges.length === 0 && count > 0) {
                // This logic depends on where your cart icon is
            }
        }

        function showToast(message, type = 'success') {
            // Simple toast implementation
            const toast = document.createElement('div');
            toast.className = `fixed bottom-24 left-1/2 -translate-x-1/2 px-6 py-3 rounded-2xl shadow-2xl z-[200] transition-all duration-500 transform translate-y-10 opacity-0 ${
                type === 'success' ? 'bg-secondary text-white' : 'bg-error text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">${type === 'success' ? 'check_circle' : 'error'}</span>
                    <span class="font-bold text-sm">${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
            }, 10);
            
            // Remove after 3s
            setTimeout(() => {
                toast.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        // Notification Logic
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

            // Update badge
            if (unreadCount > 0) {
                badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }

            // Update list
            if (notifications.length === 0) {
                list.innerHTML = '<div class="p-10 text-center text-on-surface-variant/40 italic text-xs">Không có thông báo nào</div>';
                return;
            }

            list.innerHTML = notifications.map(notif => `
                <div class="p-4 hover:bg-surface-container-low transition-colors cursor-pointer ${notif.is_read == 0 ? 'bg-secondary/5' : ''}" onclick="markAsRead(${notif.id})">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-secondary/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-secondary text-[18px]">
                                ${notif.type === 'order' ? 'package_2' : (notif.type === 'promotion' ? 'sell' : 'info')}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-bold text-primary mb-0.5 ${notif.is_read == 0 ? '' : 'font-medium'}">${notif.title}</p>
                            <p class="text-[11px] text-on-surface-variant line-clamp-2">${notif.content}</p>
                            <p class="text-[9px] text-outline mt-1 italic">${formatTimeAgo(notif.created_at)}</p>
                        </div>
                        ${notif.is_read == 0 ? '<div class="w-2 h-2 bg-secondary rounded-full mt-2"></div>' : ''}
                    </div>
                </div>
            `).join('');
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
                    if (window.location.search.includes('tab=notifications')) {
                        window.location.reload();
                    }
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
            
            if (diffInSeconds < 60) return currentLang === 'vi' ? 'Vừa xong' : 'Just now';
            if (diffInSeconds < 3600) return currentLang === 'vi' ? `${Math.floor(diffInSeconds / 60)} phút trước` : `${Math.floor(diffInSeconds / 60)} minutes ago`;
            if (diffInSeconds < 86400) return currentLang === 'vi' ? `${Math.floor(diffInSeconds / 3600)} giờ trước` : `${Math.floor(diffInSeconds / 3600)} hours ago`;
            return date.toLocaleDateString(currentLang === 'vi' ? 'vi-VN' : 'en-US');
        }

        // Check for session errors/success on load
        window.addEventListener('DOMContentLoaded', () => {
            <?php if (isset($_SESSION['error'])): ?>
            showToast("<?php echo addslashes($_SESSION['error']); ?>", 'error');
            <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
            showToast("<?php echo addslashes($_SESSION['success']); ?>", 'success');
            <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
        });

        // Initialize notifications if logged in
        <?php if(isset($_SESSION['user_id'])): ?>
        fetchNotifications();
        // Refresh every 1 minute
        setInterval(fetchNotifications, 60000);
        <?php endif; ?>

        // --- Voice Input (Speech to Text) & Audio Output (Text to Speech) for Chatbot ---
        let recognition = null;
        let isListening = false;

        function toggleVoiceInput() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) {
                showToast(currentLang === 'vi' ? 'Trình duyệt không hỗ trợ nhận diện giọng nói.' : 'Speech recognition not supported in this browser.', 'error');
                return;
            }

            const btn = document.getElementById('btnVoiceInput');
            const input = document.getElementById('chatInput');

            if (isListening) {
                if (recognition) recognition.stop();
                return;
            }

            if (!recognition) {
                recognition = new SpeechRecognition();
                recognition.continuous = false;
                recognition.interimResults = false;
                recognition.lang = currentLang === 'vi' ? 'vi-VN' : 'en-US';

                recognition.onstart = () => {
                    isListening = true;
                    btn.classList.add('text-red-500', 'animate-pulse');
                    input.placeholder = currentLang === 'vi' ? 'Đang lắng nghe...' : 'Listening...';
                };

                recognition.onresult = (event) => {
                    const result = event.results[0][0].transcript;
                    if (input) {
                        input.value = result;
                    }
                };

                recognition.onerror = (event) => {
                    console.error('Speech recognition error:', event.error);
                    stopVoiceListening();
                };

                recognition.onend = () => {
                    stopVoiceListening();
                };
            }

            recognition.start();
        }

        function stopVoiceListening() {
            isListening = false;
            const btn = document.getElementById('btnVoiceInput');
            const input = document.getElementById('chatInput');
            if (btn) {
                btn.classList.remove('text-red-500', 'animate-pulse');
            }
            if (input) {
                input.placeholder = currentLang === 'vi' ? 'Nhập câu hỏi của bạn...' : 'Type your question...';
            }
        }

        let currentUtterance = null;
        function speakText(btn) {
            if (!('speechSynthesis' in window)) {
                showToast(currentLang === 'vi' ? 'Trình duyệt không hỗ trợ đọc văn bản.' : 'Text-to-speech not supported in this browser.', 'error');
                return;
            }

            if (window.speechSynthesis.speaking) {
                window.speechSynthesis.cancel();
                if (currentUtterance && currentUtterance.btn === btn) {
                    btn.querySelector('.material-symbols-outlined').innerText = 'volume_up';
                    btn.classList.remove('text-blue-600');
                    currentUtterance = null;
                    return;
                }
            }

            document.querySelectorAll('button[onclick="speakText(this)"]').forEach(b => {
                b.querySelector('.material-symbols-outlined').innerText = 'volume_up';
                b.classList.remove('text-blue-600');
            });

            const parent = btn.closest('.rounded-2xl');
            if (!parent) return;

            const paragraphs = parent.querySelectorAll('p');
            let textToSpeak = "";
            paragraphs.forEach(p => {
                textToSpeak += p.innerText + " ";
            });

            textToSpeak = textToSpeak.trim();
            if (!textToSpeak) return;

            const utterance = new SpeechSynthesisUtterance(textToSpeak);
            utterance.lang = currentLang === 'vi' ? 'vi-VN' : 'en-US';
            utterance.btn = btn;

            utterance.rate = 1.05;
            utterance.pitch = 1.0;

            utterance.onstart = () => {
                btn.querySelector('.material-symbols-outlined').innerText = 'volume_mute';
                btn.classList.add('text-blue-600');
            };

            utterance.onend = () => {
                btn.querySelector('.material-symbols-outlined').innerText = 'volume_up';
                btn.classList.remove('text-blue-600');
                if (currentUtterance === utterance) currentUtterance = null;
            };

            utterance.onerror = () => {
                btn.querySelector('.material-symbols-outlined').innerText = 'volume_up';
                btn.classList.remove('text-blue-600');
                if (currentUtterance === utterance) currentUtterance = null;
            };

            currentUtterance = utterance;
            window.speechSynthesis.speak(utterance);
        }
    </script>
</body>
</html>
