<?php require_once VIEWS . '/layout/admin_header.php'; ?>
<?php require_once VIEWS . '/layout/admin_sidebar.php'; ?>

<!-- Main Content Area with overflow-hidden for chat application layout -->
<main class="flex-1 w-full flex flex-col h-screen overflow-hidden bg-[#F8F9FB]">
    <!-- Header -->
    <header class="h-20 bg-white border-b border-outline-variant flex items-center justify-between px-10 shrink-0 z-40">
        <h1 class="text-h2 font-bold text-primary">Hỗ trợ trực tuyến</h1>
        
        <div class="flex items-center gap-8">
            <!-- Notifications -->
            <?php require_once VIEWS . '/layout/admin_notification.php'; ?>

            <div class="flex items-center gap-4 pl-6 border-l border-outline-variant">
                <img alt="Admin" class="w-10 h-10 rounded-full object-cover" src="<?php echo $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=Admin&background=0453cd&color=fff'; ?>"/>
                <div class="text-right">
                    <p class="text-[14px] font-bold"><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></p>
                    <p class="text-[12px] text-on-surface-variant">Quản trị viên</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Chat Workspace Layout -->
    <div class="flex-1 flex overflow-hidden w-full">
        <!-- Left Sidebar: Session List -->
        <div class="w-96 border-r border-outline-variant bg-white flex flex-col shrink-0">
            <!-- Filter Tabs -->
            <div class="p-4 border-b border-outline-variant">
                <div class="flex p-1 bg-[#F1F3F5] rounded-xl gap-1">
                    <button onclick="switchTab('pending')" id="tab-pending" class="flex-1 py-2 rounded-lg text-xs font-bold text-primary bg-white shadow-sm transition-all cursor-pointer">
                        Chờ duyệt
                    </button>
                    <button onclick="switchTab('active')" id="tab-active" class="flex-1 py-2 rounded-lg text-xs font-bold text-on-surface-variant hover:bg-white/50 transition-all cursor-pointer">
                        Đang chat
                    </button>
                    <button onclick="switchTab('closed')" id="tab-closed" class="flex-1 py-2 rounded-lg text-xs font-bold text-on-surface-variant hover:bg-white/50 transition-all cursor-pointer">
                        Lịch sử
                    </button>
                </div>
            </div>

            <!-- Search Session -->
            <div class="p-4 border-b border-outline-variant">
                <div class="w-full flex items-center bg-[#F8F9FB] dark:bg-zinc-950 border border-[#E1E3E6] dark:border-zinc-800 rounded-xl px-4 py-2 focus-within:ring-2 focus-within:ring-secondary/20 transition-all">
                    <span class="material-symbols-outlined text-on-surface-variant text-[20px] select-none flex-shrink-0">search</span>
                    <input type="text" id="sessionSearch" oninput="filterSessions()" placeholder="Tìm kiếm khách hàng..." class="w-full bg-transparent border-none focus:ring-0 text-[13px] font-medium text-slate-700 dark:text-zinc-200 placeholder:text-on-surface-variant/60 ml-2 py-0"/>
                </div>
            </div>

            <!-- Session List Scroll Container -->
            <div id="sessionList" class="flex-1 overflow-y-auto divide-y divide-outline-variant">
                <!-- Skeleton Loader / Dynamic sessions go here -->
                <div class="p-8 text-center text-on-surface-variant text-sm">
                    Đang tải danh sách phiên chat...
                </div>
            </div>
        </div>

        <!-- Right Panel: Chat Console -->
        <div class="flex-1 flex flex-col bg-slate-50 dark:bg-zinc-950 overflow-hidden relative">
            <!-- Empty State -->
            <div id="emptyChatState" class="absolute inset-0 bg-white flex flex-col items-center justify-center p-12 z-20">
                <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-6 shadow-sm border border-blue-100 animate-pulse">
                    <span class="material-symbols-outlined text-4xl">chat_bubble</span>
                </div>
                <h3 class="text-lg font-bold text-primary mb-2">Chưa chọn phiên hỗ trợ</h3>
                <p class="text-sm text-on-surface-variant text-center max-w-sm">Chọn một khách hàng ở danh sách bên trái để tiếp nhận yêu cầu hỗ trợ trực tuyến.</p>
            </div>

            <!-- Active Chat Header -->
            <div class="h-20 bg-white border-b border-outline-variant px-8 flex items-center justify-between shrink-0 z-10 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 bg-primary text-white font-bold rounded-xl flex items-center justify-center text-sm shadow-md" id="chatHeaderAvatar">
                        KH
                    </div>
                    <div class="flex flex-col">
                        <h3 class="font-bold text-primary text-[15px]" id="chatHeaderName">Khách hàng</h3>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-2 h-2 rounded-full" id="chatHeaderStatusDot"></span>
                            <span class="text-[11px] font-bold text-on-surface-variant" id="chatHeaderStatusText">Ngoại tuyến</span>
                        </div>
                    </div>
                </div>

                <!-- Chat Actions -->
                <div class="flex items-center gap-3" id="chatActions">
                    <!-- Dynamic action buttons (Accept / Close) will load here -->
                </div>
            </div>

            <!-- Message Area -->
            <div id="chatMessages" class="flex-1 p-8 overflow-y-auto flex flex-col gap-6 scroll-smooth">
                <!-- Messages populate dynamically -->
            </div>

            <!-- Input Console -->
            <div class="p-6 bg-white border-t border-outline-variant shrink-0" id="chatInputConsole">
                <div class="relative flex items-center gap-3">
                    <div class="relative flex-1">
                        <button id="chatUploadBtn" disabled onclick="triggerLiveChatImageUpload()" 
                            class="absolute top-1/2 -translate-y-1/2 w-10 h-10 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-200 transition-all active:scale-95 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" style="right: 52px;">
                            <span class="material-symbols-outlined text-xl">image</span>
                        </button>
                        <input type="file" id="chatImageInput" accept="image/*" class="hidden" onchange="handleLiveChatImageUpload(this)" />
                        <input type="text" id="chatInput" autocomplete="off" disabled placeholder="Vui lòng chọn hoặc tiếp nhận hỗ trợ để bắt đầu chat..." class="w-full pl-5 pr-[96px] py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all placeholder:text-gray-400 disabled:opacity-60 disabled:cursor-not-allowed"/>
                        <button onclick="sendChatMessage()" id="btnSend" disabled class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center hover:bg-secondary hover:shadow-lg transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                            <span class="material-symbols-outlined text-xl">send</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    @keyframes bubbleScaleIn {
        from { opacity: 0; transform: scale(0.96) translateY(8px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .chat-bubble-anim {
        animation: bubbleScaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    #chatMessages::-webkit-scrollbar {
        width: 6px;
    }
    #chatMessages::-webkit-scrollbar-track {
        background: transparent;
    }
    #chatMessages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    #chatMessages::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
    let activeTab = 'pending';
    let allSessions = [];
    let selectedCustomerId = null;
    let selectedSessionStatus = null;
    let selectedAdminId = null;
    let currentAdminId = <?php echo $_SESSION['user_id']; ?>;
    let pollingIntervalSessions = null;
    let pollingIntervalMessages = null;
    let lastMessageCount = 0;
    // CSRF Token for all AJAX POST requests
    const csrfToken = '<?php echo csrf_token(); ?>';

    // Switch session tabs
    function switchTab(tab) {
        activeTab = tab;
        ['pending', 'active', 'closed'].forEach(t => {
            const el = document.getElementById('tab-' + t);
            if (t === tab) {
                el.className = 'flex-1 py-2 rounded-lg text-xs font-bold text-primary bg-white shadow-sm transition-all cursor-pointer';
            } else {
                el.className = 'flex-1 py-2 rounded-lg text-xs font-bold text-on-surface-variant hover:bg-white/50 transition-all cursor-pointer';
            }
        });
        renderSessions();
    }

    // Load sessions from API
    async function loadSessions() {
        try {
            const response = await fetch('<?php echo URLROOT; ?>/admin/getLiveChatSessions');
            const data = await response.json();
            if (data.status === 'success') {
                allSessions = data.sessions;
                renderSessions();
            }
        } catch (error) {
            console.error('Error fetching sessions:', error);
        }
    }

    // Filter sessions based on search query
    function filterSessions() {
        renderSessions();
    }

    // Render list of support sessions
    function renderSessions() {
        const query = document.getElementById('sessionSearch').value.toLowerCase();
        const container = document.getElementById('sessionList');
        
        const filtered = allSessions.filter(s => {
            const matchTab = s.status === activeTab;
            const matchSearch = s.customer_name.toLowerCase().includes(query);
            return matchTab && matchSearch;
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="p-8 text-center text-on-surface-variant text-sm">
                    Không có phiên hỗ trợ nào.
                </div>
            `;
            return;
        }

        container.innerHTML = filtered.map(s => {
            const isSelected = selectedCustomerId == s.customer_id;
            const bgClass = isSelected ? 'bg-blue-50/70 border-l-4 border-l-blue-600' : 'hover:bg-slate-50';
            const textBold = isSelected ? 'font-black' : 'font-bold';
            
            // Generate initials for avatar
            const initials = s.customer_name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

            // Status Indicator
            let statusDot = '';
            if (s.status === 'pending') {
                statusDot = '<span class="w-2.5 h-2.5 bg-yellow-500 rounded-full shrink-0 shadow-[0_0_6px_rgba(234,179,8,0.6)] animate-pulse" title="Đang chờ"></span>';
            } else if (s.status === 'active') {
                statusDot = s.admin_id == currentAdminId 
                    ? '<span class="w-2.5 h-2.5 bg-green-500 rounded-full shrink-0 shadow-[0_0_6px_rgba(34,197,94,0.6)] animate-pulse" title="Đang hỗ trợ"></span>'
                    : '<span class="w-2.5 h-2.5 bg-blue-500 rounded-full shrink-0" title="Admin khác đang chat"></span>';
            } else {
                statusDot = '<span class="w-2.5 h-2.5 bg-gray-400 rounded-full shrink-0" title="Đã đóng"></span>';
            }

            return `
                <div onclick="selectCustomer(${s.customer_id})" class="p-5 flex gap-4 ${bgClass} cursor-pointer transition-all border-b border-outline-variant/65">
                    <div class="w-11 h-11 bg-gradient-to-tr from-slate-200 to-slate-100 text-gray-700 font-bold rounded-xl flex items-center justify-center shrink-0 border border-gray-200">
                        ${initials}
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col gap-0.5">
                        <div class="flex justify-between items-center">
                            <span class="text-[13px] ${textBold} text-primary truncate pr-2">${s.customer_name}</span>
                            <span class="text-[10px] font-semibold text-gray-400 shrink-0">${s.time}</span>
                        </div>
                        <p class="text-[11.5px] text-on-surface-variant truncate pr-2 mt-0.5 font-medium leading-relaxed">${s.last_message}</p>
                    </div>
                    <div class="flex items-center">
                        ${statusDot}
                    </div>
                </div>
            `;
        }).join('');
    }

    // Select customer and open chat console
    function selectCustomer(customerId) {
        selectedCustomerId = customerId;
        document.getElementById('emptyChatState').classList.add('hidden');
        
        // Render sessions list immediately to show selection change
        renderSessions();

        // Clear existing messages list
        document.getElementById('chatMessages').innerHTML = `
            <div class="flex-1 flex items-center justify-center text-sm text-on-surface-variant">
                Đang tải lịch sử tin nhắn...
            </div>
        `;
        
        // Reset last message count
        lastMessageCount = 0;

        // Fetch messages immediately
        loadMessages(true);

        // Reset and start message polling
        if (pollingIntervalMessages) clearInterval(pollingIntervalMessages);
        pollingIntervalMessages = setInterval(() => loadMessages(false), 3000);
    }

    function buildAdminChatBubble(m, withAnim = true) {
        const isMe = m.sender === 'admin';
        const animClass = withAnim ? 'chat-bubble-anim' : '';
        let formattedText = m.message;
        formattedText = formattedText.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
        formattedText = formattedText.replace(/\n/g, '<br>');

        if (isMe) {
            let messageBody = '';
            if (m.message.indexOf('[IMAGE] ') === 0) {
                const imgUrl = m.message.substring(8);
                const absoluteImgUrl = imgUrl.startsWith('http') ? imgUrl : '<?php echo URLROOT; ?>' + imgUrl;
                messageBody = `
                    <div class="rounded-xl overflow-hidden max-w-[320px] cursor-zoom-in border border-white/20 shadow-sm" onclick="window.open('${absoluteImgUrl}', '_blank')">
                        <img src="${absoluteImgUrl}" class="w-full h-auto object-cover max-h-[250px]" alt="Uploaded Image" />
                    </div>
                `;
            } else {
                messageBody = `<p class="text-[13.5px] leading-relaxed" style="overflow-wrap: anywhere; word-break: break-word;">${formattedText}</p>`;
            }
            return `
                <div class="flex justify-end ${animClass}">
                    <div class="bg-primary text-white px-5 py-3.5 rounded-2xl rounded-tr-none shadow-md max-w-[70%]">
                        ${messageBody}
                        <span class="text-[9px] text-white/60 mt-2 block text-right font-medium uppercase">${m.time}</span>
                    </div>
                </div>
            `;
        } else {
            const isSystem = m.message.includes('yêu cầu hỗ trợ') || m.message.includes('Nhân viên hỗ trợ đã tham gia') || m.message.includes('Phiên hỗ trợ trực tuyến đã được đóng');
            if (isSystem) {
                return `
                    <div class="flex justify-center ${animClass} my-2">
                        <div class="bg-gray-200/80 text-gray-600 px-4 py-2 rounded-xl text-xs font-semibold max-w-[80%] border border-gray-300/30 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">info</span>
                            ${m.message}
                        </div>
                    </div>
                `;
            }

            let messageBody = '';
            if (m.message.indexOf('[IMAGE] ') === 0) {
                const imgUrl = m.message.substring(8);
                const absoluteImgUrl = imgUrl.startsWith('http') ? imgUrl : '<?php echo URLROOT; ?>' + imgUrl;
                messageBody = `
                    <div class="rounded-xl overflow-hidden max-w-[320px] cursor-zoom-in border border-outline-variant/20 shadow-sm" onclick="window.open('${absoluteImgUrl}', '_blank')">
                        <img src="${absoluteImgUrl}" class="w-full h-auto object-cover max-h-[250px]" alt="Uploaded Image" />
                    </div>
                `;
            } else {
                messageBody = `<p class="text-[13.5px] text-gray-700 leading-relaxed" style="overflow-wrap: anywhere; word-break: break-word;">${formattedText}</p>`;
            }

            return `
                <div class="flex gap-3 max-w-[70%] ${animClass}">
                    <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex-shrink-0 flex items-center justify-center border border-slate-300 text-xs font-bold">
                        KH
                    </div>
                    <div class="bg-white border border-outline-variant/30 px-5 py-3.5 rounded-2xl rounded-tl-none shadow-sm flex flex-col w-full">
                        ${messageBody}
                        <span class="text-[9px] text-gray-400 mt-2 block font-medium uppercase">${m.time}</span>
                    </div>
                </div>
            `;
        }
    }

    // Load messages for selected customer
    async function loadMessages(forceScroll = false) {
        if (!selectedCustomerId) return;

        try {
            const response = await fetch(`<?php echo URLROOT; ?>/admin/getLiveChatMessages?customer_id=${selectedCustomerId}`);
            const data = await response.json();
            
            if (data.status === 'success') {
                selectedSessionStatus = data.support_status;
                selectedAdminId = data.admin_id;
                
                // Update chat console header info
                document.getElementById('chatHeaderName').innerText = data.customer_name;
                const initials = data.customer_name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                document.getElementById('chatHeaderAvatar').innerText = initials;

                const statusDot = document.getElementById('chatHeaderStatusDot');
                const statusText = document.getElementById('chatHeaderStatusText');

                if (selectedSessionStatus === 'pending') {
                    statusDot.className = 'w-2 h-2 bg-yellow-500 rounded-full animate-pulse';
                    statusText.innerText = 'Đang chờ hỗ trợ';
                } else if (selectedSessionStatus === 'active') {
                    statusDot.className = 'w-2 h-2 bg-green-500 rounded-full animate-pulse';
                    statusText.innerText = data.admin_id == currentAdminId ? 'Bạn đang hỗ trợ' : 'Nhân viên khác đang hỗ trợ';
                } else {
                    statusDot.className = 'w-2 h-2 bg-gray-400 rounded-full';
                    statusText.innerText = 'Phiên hỗ trợ đã đóng';
                }

                // Render Actions Panel
                const actionsContainer = document.getElementById('chatActions');
                if (selectedSessionStatus === 'pending') {
                    actionsContainer.innerHTML = `
                        <button onclick="acceptSession(${selectedCustomerId})" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/10 hover:shadow-blue-500/20 active:scale-95 transition-all flex items-center gap-1.5 cursor-pointer border-0">
                            <span class="material-symbols-outlined text-[16px]">support_agent</span>
                            Tiếp nhận hỗ trợ
                        </button>
                    `;
                } else if (selectedSessionStatus === 'active' && data.admin_id == currentAdminId) {
                    actionsContainer.innerHTML = `
                        <button onclick="closeSession(${selectedCustomerId})" class="px-5 py-2.5 bg-red-500 text-white rounded-xl text-xs font-bold shadow-md shadow-red-500/10 hover:bg-red-600 active:scale-95 transition-all flex items-center gap-1.5 cursor-pointer border-0">
                            <span class="material-symbols-outlined text-[16px]">cancel_schedule_send</span>
                            Đóng hỗ trợ
                        </button>
                    `;
                } else {
                    actionsContainer.innerHTML = '';
                }

                // Enable/Disable Input console
                const chatInput = document.getElementById('chatInput');
                const btnSend = document.getElementById('btnSend');
                const chatUploadBtn = document.getElementById('chatUploadBtn');
                if (selectedSessionStatus === 'active' && data.admin_id == currentAdminId) {
                    chatInput.removeAttribute('disabled');
                    chatInput.placeholder = 'Nhập tin nhắn phản hồi khách hàng...';
                    btnSend.removeAttribute('disabled');
                    if (chatUploadBtn) chatUploadBtn.removeAttribute('disabled');
                } else {
                    chatInput.setAttribute('disabled', 'true');
                    btnSend.setAttribute('disabled', 'true');
                    if (chatUploadBtn) chatUploadBtn.setAttribute('disabled', 'true');
                    if (selectedSessionStatus === 'pending') {
                        chatInput.placeholder = 'Vui lòng bấm "Tiếp nhận hỗ trợ" để bắt đầu trò chuyện...';
                    } else {
                        chatInput.placeholder = 'Phiên hỗ trợ đã đóng. Không thể gửi thêm tin nhắn.';
                    }
                }

                // Render message feed
                const messagesContainer = document.getElementById('chatMessages');
                const messages = data.messages;
                
                if (messages.length === 0) {
                    messagesContainer.innerHTML = `
                        <div class="flex-1 flex items-center justify-center text-sm text-on-surface-variant font-medium">
                            Chưa có tin nhắn trong phiên này.
                        </div>
                    `;
                    lastMessageCount = 0;
                    return;
                }

                // If messages reset or switched
                if (lastMessageCount > messages.length) {
                    lastMessageCount = 0;
                }

                if (lastMessageCount === 0) {
                    // Render all existing messages without slide-in animation to avoid flash
                    let initialHtml = '';
                    messages.forEach(m => {
                        initialHtml += buildAdminChatBubble(m, false);
                    });
                    messagesContainer.innerHTML = initialHtml;
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    lastMessageCount = messages.length;
                } else if (messages.length > lastMessageCount) {
                    // Append only new messages
                    if (messagesContainer.innerHTML.includes('Chưa có tin nhắn trong phiên này') || messagesContainer.innerHTML.includes('Đang tải lịch sử tin nhắn')) {
                        messagesContainer.innerHTML = '';
                    }

                    // Remove slide-in animation from previously rendered elements to prevent reflow issues
                    const existingAnimatedBubbles = messagesContainer.querySelectorAll('.chat-bubble-anim');
                    existingAnimatedBubbles.forEach(el => el.classList.remove('chat-bubble-anim'));

                    const wasAtBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop <= messagesContainer.clientHeight + 100;

                    for (let i = lastMessageCount; i < messages.length; i++) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = buildAdminChatBubble(messages[i], true).trim();
                        while (tempDiv.firstChild) {
                            messagesContainer.appendChild(tempDiv.firstChild);
                        }
                    }

                    lastMessageCount = messages.length;
                    if (wasAtBottom || forceScroll) {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                } else if (forceScroll) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    }

    // Accept support session
    async function acceptSession(customerId) {
        const btn = event?.target?.closest('button');
        if (btn) { btn.disabled = true; btn.innerText = 'Đang xử lý...'; }
        try {
            const formData = new FormData();
            formData.append('customer_id', customerId);
            formData.append('csrf_token', csrfToken);

            const response = await fetch('<?php echo URLROOT; ?>/admin/acceptSupportSession', {
                method: 'POST',
                body: formData
            });
            
            // Check HTTP status first
            if (!response.ok) {
                const text = await response.text();
                console.error('HTTP Error ' + response.status + ':', text.substring(0, 500));
                alert('Lỗi HTTP ' + response.status + ': Không thể tiếp nhận hỗ trợ. Vui lòng thử đăng xuất và đăng nhập lại.');
                return;
            }
            
            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch(parseErr) {
                console.error('Invalid JSON response:', text.substring(0, 500));
                alert('Lỗi phản hồi từ server. Vui lòng đăng xuất và đăng nhập lại rồi thử lại.');
                return;
            }
            
            if (data.status === 'success') {
                switchTab('active'); // Auto-switch to active tab
                loadSessions();
                loadMessages(true);
            } else {
                alert('Lỗi: ' + (data.message || 'Không thể tiếp nhận hỗ trợ'));
            }
        } catch (error) {
            console.error('Error accepting session:', error);
            alert('Lỗi kết nối: ' + error.message + '. Vui lòng thử lại.');
        } finally {
            if (btn) { btn.disabled = false; btn.innerText = 'Tiếp nhận hỗ trợ'; }
        }
    }



    // Close support session
    async function closeSession(customerId) {
        if (!confirm('Bạn có chắc chắn muốn kết thúc hỗ trợ khách hàng này?')) return;
        
        try {
            const formData = new FormData();
            formData.append('customer_id', customerId);
            formData.append('csrf_token', csrfToken);

            const response = await fetch('<?php echo URLROOT; ?>/admin/closeSupportSession', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.status === 'success') {
                loadSessions();
                loadMessages(true);
            }
        } catch (error) {
            console.error('Error closing session:', error);
        }
    }

    // Send chat message
    async function sendChatMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message || !selectedCustomerId) return;

        input.value = '';
        try {
            const formData = new FormData();
            formData.append('customer_id', selectedCustomerId);
            formData.append('message', message);
            formData.append('csrf_token', csrfToken);

            const response = await fetch('<?php echo URLROOT; ?>/admin/sendLiveChatMessage', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.status === 'success') {
                loadMessages(true);
                loadSessions(); // Update last message in session sidebar
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    }

    function triggerLiveChatImageUpload() {
        document.getElementById('chatImageInput').click();
    }

    async function handleLiveChatImageUpload(input) {
        if (!input.files || input.files.length === 0 || !selectedCustomerId) return;
        const file = input.files[0];

        if (file.size > 5 * 1024 * 1024) {
            alert('Dung lượng ảnh tối đa 5MB.');
            input.value = '';
            return;
        }

        const formData = new FormData();
        formData.append('customer_id', selectedCustomerId);
        formData.append('image', file);
        formData.append('csrf_token', csrfToken);

        // Visual indicator in button
        const chatUploadBtn = document.getElementById('chatUploadBtn');
        const uploadIcon = chatUploadBtn.querySelector('.material-symbols-outlined');
        if (uploadIcon) {
            uploadIcon.innerText = 'progress_activity';
            uploadIcon.classList.add('animate-spin');
        }
        chatUploadBtn.setAttribute('disabled', 'true');

        try {
            const response = await fetch('<?php echo URLROOT; ?>/admin/uploadLiveChatImage', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.status === 'success') {
                loadMessages(true);
                loadSessions();
            } else {
                alert(data.message || 'Lỗi upload ảnh.');
            }
        } catch (error) {
            console.error('Error uploading live chat image:', error);
            alert('Lỗi kết nối máy chủ.');
        } finally {
            if (uploadIcon) {
                uploadIcon.innerText = 'image';
                uploadIcon.classList.remove('animate-spin');
            }
            if (selectedSessionStatus === 'active') {
                chatUploadBtn.removeAttribute('disabled');
            }
            input.value = '';
        }
    }

    // Trigger send message on Enter key
    document.getElementById('chatInput')?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendChatMessage();
    });

    // Start Polling Loops
    window.addEventListener('DOMContentLoaded', () => {
        loadSessions();
        pollingIntervalSessions = setInterval(loadSessions, 3000);
    });
</script>
