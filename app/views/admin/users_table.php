<table class="w-full text-left">
    <thead>
        <tr class="text-on-surface-variant text-[12px] font-bold uppercase tracking-wider border-b border-outline-variant bg-[#F8F9FB]">
            <th class="px-8 py-4">NGƯỜI DÙNG</th>
            <th class="px-8 py-4">VAI TRÒ</th>
            <th class="px-8 py-4">NGÀY TẠO</th>
            <th class="px-8 py-4 text-center">TRẠNG THÁI</th>
            <th class="px-8 py-4 text-center">HÀNH ĐỘNG</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-outline-variant">
        <?php if(empty($data['users'])): ?>
        <tr>
            <td colspan="5" class="px-8 py-10 text-center text-on-surface-variant">Không tìm thấy tài khoản nào.</td>
        </tr>
        <?php else: ?>
            <?php foreach($data['users'] as $user): ?>
            <tr class="hover:bg-[#F8F9FB] transition-colors">
                <td class="px-8 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center overflow-hidden border border-outline-variant">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['full_name'] ?: $user['email']); ?>&background=random" alt="">
                        </div>
                        <div>
                            <span class="text-[14px] font-bold text-primary block"><?php echo $user['full_name'] ?: 'Chưa cập nhật'; ?></span>
                            <span class="text-[12px] text-on-surface-variant"><?php echo $user['email']; ?></span>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-5">
                    <?php if ($user['id'] == $_SESSION['user_id']): ?>
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold border bg-purple-50 text-purple-700 border-purple-100 inline-flex items-center gap-1" title="Tài khoản của bạn (Đã khóa vai trò để bảo mật)">
                            <span class="material-symbols-outlined text-[12px]">lock</span>
                            <?php echo strtoupper($user['role_name']); ?>
                        </span>
                    <?php else: ?>
                        <div class="relative inline-block text-left role-dropdown-container">
                            <button type="button" class="px-3 py-1.5 rounded-full text-[11px] font-bold border inline-flex items-center gap-1 transition-all hover:scale-[1.03] active:scale-[0.97] cursor-pointer role-dropdown-btn <?php echo $user['role_id'] == 1 ? 'bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100/40' : 'bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100/40'; ?>" onclick="toggleRoleDropdown(event, <?php echo $user['id']; ?>)">
                                <span><?php echo strtoupper($user['role_name']); ?></span>
                                <span class="material-symbols-outlined text-[14px] opacity-70">arrow_drop_down</span>
                            </button>

                            <div id="role-menu-<?php echo $user['id']; ?>" class="absolute left-0 mt-1.5 w-36 bg-white border border-outline-variant rounded-xl shadow-xl overflow-hidden hidden z-30 transform origin-top-left transition-all duration-150 ease-out opacity-0 scale-95 role-dropdown-menu">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    <?php foreach($data['roles'] as $role): ?>
                                        <button type="button" class="w-full text-left px-3 py-2 text-[12px] font-bold transition-colors flex items-center gap-1.5 <?php echo $role['id'] == 1 ? 'text-purple-700 hover:bg-purple-50/50' : 'text-blue-700 hover:bg-blue-50/50'; ?> <?php echo $user['role_id'] == $role['id'] ? 'bg-surface-container' : ''; ?>" onclick="selectUserRole(<?php echo $user['id']; ?>, <?php echo $role['id']; ?>)" role="menuitem">
                                            <span class="material-symbols-outlined text-[16px]"><?php echo $role['id'] == 1 ? 'admin_panel_settings' : 'person'; ?></span>
                                            <?php echo strtoupper($role['name']); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </td>
                <td class="px-8 py-5">
                    <span class="text-[13px] text-on-surface"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></span>
                </td>
                <td class="px-8 py-5 text-center">
                    <?php if($user['status'] == 1): ?>
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-[11px] font-bold border border-green-200">HOẠT ĐỘNG</span>
                    <?php elseif($user['status'] == -1): ?>
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-[11px] font-bold border border-gray-300">ĐÃ XÓA</span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-[11px] font-bold border border-red-200">BỊ KHÓA</span>
                    <?php endif; ?>

                </td>
                <td class="px-8 py-5 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <?php if($user['status'] == 1): ?>
                            <a href="<?php echo URLROOT; ?>/admin/updateUserStatus/<?php echo $user['id']; ?>?status=0" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-red-500 hover:text-white transition-all" title="Khóa tài khoản">
                                <span class="material-symbols-outlined text-[18px]">block</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/admin/deleteUser/<?php echo $user['id']; ?>" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-red-600 hover:text-white transition-all" title="Xóa tài khoản" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </a>
                        <?php elseif($user['status'] == 0): ?>
                            <a href="<?php echo URLROOT; ?>/admin/updateUserStatus/<?php echo $user['id']; ?>?status=1" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-green-500 hover:text-white transition-all" title="Mở khóa tài khoản">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/admin/deleteUser/<?php echo $user['id']; ?>" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-red-600 hover:text-white transition-all" title="Xóa tài khoản" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo URLROOT; ?>/admin/updateUserStatus/<?php echo $user['id']; ?>?status=1" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-green-500 hover:text-white transition-all" title="Khôi phục tài khoản">
                                <span class="material-symbols-outlined text-[18px]">restore</span>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo URLROOT; ?>/admin/userDetail/<?php echo $user['id']; ?>" class="w-9 h-9 flex items-center justify-center border border-outline-variant rounded-lg text-on-surface-variant hover:bg-secondary hover:text-white transition-all" title="Xem chi tiết">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
