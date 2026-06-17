<div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
    <!-- Review List (Left) -->
    <div class="lg:col-span-7 space-y-8">
        <!-- Rating Summary Analysis -->
        <?php if(!empty($data['rating_analysis']) && $data['rating_analysis']['total'] > 0): ?>
        <div class="bg-gray-50 rounded-[2rem] p-10 border border-gray-100 shadow-sm flex flex-col md:flex-row gap-12 items-center mb-12">
            <div class="text-center md:border-r border-gray-200/50 md:pr-12">
                <div class="text-6xl font-black text-gray-900 mb-2"><?php echo $data['rating_analysis']['average']; ?></div>
                <div class="flex justify-center gap-1 text-amber-400 mb-2">
                    <?php 
                    $avg = $data['rating_analysis']['average'];
                    for($i=1; $i<=5; $i++) {
                        echo '<span class="material-symbols-outlined '.($i <= round($avg) ? 'fill-1' : '').' !text-[20px]">star</span>';
                    }
                    ?>
                </div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]"><?php echo $data['rating_analysis']['total']; ?><?php echo __('reviews_count_suffix', ' ĐÁNH GIÁ'); ?></div>
            </div>
            <div class="flex-1 w-full space-y-3">
                <?php foreach([5,4,3,2,1] as $star): 
                    $count = $data['rating_analysis'][$star];
                    $percent = $data['rating_analysis']['total'] > 0 ? ($count / $data['rating_analysis']['total']) * 100 : 0;
                ?>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1 w-12 shrink-0">
                        <span class="text-xs font-black text-gray-900"><?php echo $star; ?></span>
                        <span class="material-symbols-outlined !text-[14px] text-amber-400 fill-1">star</span>
                    </div>
                    <div class="flex-1 h-2 bg-white rounded-full overflow-hidden border border-gray-100">
                        <div class="h-full bg-amber-400 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(251,191,36,0.3)]" style="width: <?php echo $percent; ?>%"></div>
                    </div>
                    <div class="w-10 text-right shrink-0">
                        <span class="text-[10px] font-bold text-gray-400"><?php echo $count; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if(empty($data['reviews'])): ?>
            <div class="bg-gray-50 rounded-[2rem] p-16 text-center border border-dashed border-gray-200">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <span class="material-symbols-outlined text-4xl text-gray-300">rate_review</span>
                </div>
                <p class="text-gray-900 font-bold text-lg"><?php echo __('no_reviews', 'Chưa có đánh giá nào'); ?></p>
                <p class="text-sm text-gray-400 mt-2"><?php echo __('be_first_review', 'Hãy là người đầu tiên chia sẻ cảm nhận về sản phẩm!'); ?></p>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach($data['reviews'] as $review): ?>
                    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#356ee7] to-[#5a8df3] text-white flex items-center justify-center font-black text-lg shadow-lg">
                                    <?php echo strtoupper(substr($review['customer_name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900"><?php echo $review['customer_name']; ?></p>
                                    <div class="flex items-center gap-2">
                                        <div class="flex text-amber-400">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <span class="material-symbols-outlined text-[16px] <?php echo $i <= $review['rating'] ? 'fill-1' : ''; ?>">star</span>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">• <?php echo date('d/m/Y', strtotime($review['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-gray-600 leading-relaxed text-sm"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                        
                        <?php if(!empty($review['review_image'])): ?>
                        <div class="mt-6 inline-block group relative">
                            <img src="<?php echo $review['review_image']; ?>" alt="Review image" 
                                class="w-40 h-40 object-cover rounded-2xl border border-gray-100 shadow-sm transition-transform duration-500 group-hover:scale-105 cursor-pointer" 
                                onclick="openImageLightbox(this.src)">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center pointer-events-none">
                                <span class="material-symbols-outlined text-white">zoom_in</span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if(!empty($review['admin_reply'])): ?>
                        <div class="mt-8 bg-blue-50/50 rounded-2xl p-6 border-l-4 border-[#356ee7]">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-full bg-[#356ee7] text-white flex items-center justify-center shadow-md">
                                    <span class="material-symbols-outlined text-[16px]">support_agent</span>
                                </div>
                                <span class="text-[10px] font-black text-[#356ee7] uppercase tracking-widest"><?php echo __('reply_from_store', 'Phản hồi từ TechExpert'); ?></span>
                            </div>
                            <p class="text-sm text-gray-700 italic leading-relaxed">
                                "<?php echo htmlspecialchars($review['admin_reply']); ?>"
                            </p>
                            <div class="mt-3 flex justify-end">
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                                    <?php echo __('replied_on', 'Đã phản hồi vào'); ?> <?php echo date('d/m/Y', strtotime($review['replied_at'])); ?>
                                </span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Review Form (Right) -->
    <div class="lg:col-span-5">
        <div class="bg-white rounded-[2.5rem] border border-gray-100 p-10 shadow-[0_20px_50px_rgba(0,0,0,0.03)] sticky top-32">
            <h3 class="text-2xl font-bold text-gray-900 mb-2"><?php echo __('write_review', 'Viết đánh giá'); ?></h3>
            <p class="text-xs text-gray-400 mb-8 uppercase tracking-widest font-bold"><?php echo __('share_your_experience', 'Chia sẻ trải nghiệm của bạn'); ?></p>

            <?php if(!isset($_SESSION['customer_id'])): ?>
                <div class="text-center py-10">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-300">lock</span>
                    </div>
                    <p class="text-gray-500 text-sm mb-6"><?php echo __('login_to_review', 'Vui lòng đăng nhập để đánh giá sản phẩm này.'); ?></p>
                    <a href="<?php echo URLROOT; ?>/auth/login" class="inline-block w-full py-4 bg-black text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-800 transition-all shadow-xl"><?php echo __('login_now_btn', 'Đăng nhập ngay'); ?></a>
                </div>
            <?php elseif(!$data['can_review']): ?>
                <div class="text-center py-12 bg-gray-50 rounded-[2rem] border border-dashed border-gray-200 px-6">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <span class="material-symbols-outlined text-4xl text-[#356ee7]">shopping_basket</span>
                    </div>
                    <p class="text-gray-900 font-bold mb-3"><?php echo __('not_purchased_error', 'Bạn chưa mua sản phẩm này'); ?></p>
                    <p class="text-[11px] text-gray-400 leading-relaxed uppercase font-bold tracking-wider"><?php echo __('only_purchased_can_review', 'Chỉ khách hàng đã mua mới có thể đánh giá. Mua ngay để chia sẻ trải nghiệm nhé!'); ?></p>
                </div>
            <?php else: ?>
                <form action="<?php echo URLROOT; ?>/product/addReview" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo $data['product']['id']; ?>">
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 text-center"><?php echo __('your_rating', 'Đánh giá của bạn'); ?></label>
                        <div class="flex justify-center gap-2 rating-input text-gray-200">
                            <?php for($i=5; $i>=1; $i--): ?>
                                <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" class="hidden peer" required>
                                <label for="star<?php echo $i; ?>" class="material-symbols-outlined text-3xl cursor-pointer hover:text-amber-400 peer-checked:text-amber-400 peer-checked:fill-1 transition-all">star</label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3"><?php echo __('review_image_label', 'Ảnh thực tế (Tùy chọn)'); ?></label>
                        <div class="relative group">
                            <input type="file" name="review_image" id="review_image" class="hidden" accept="image/*" onchange="previewReviewImage(this)">
                            <label for="review_image" class="w-full flex flex-col items-center justify-center gap-2 py-8 rounded-[1.5rem] border-2 border-dashed border-gray-100 group-hover:border-[#356ee7] group-hover:bg-blue-50/30 cursor-pointer transition-all">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-gray-400 group-hover:text-[#356ee7]">add_a_photo</span>
                                </div>
                                <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest" id="upload-label"><?php echo __('select_photo_btn', 'Chọn ảnh'); ?></span>
                            </label>
                            <div id="image-preview" class="hidden mt-4 relative inline-block">
                                <img src="" alt="Preview" class="w-24 h-24 object-cover rounded-2xl border border-gray-100 shadow-md">
                                <button type="button" onclick="removeReviewImage()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3"><?php echo __('detailed_review_label', 'Nhận xét chi tiết'); ?></label>
                        <textarea name="comment" rows="4" 
                            class="w-full px-6 py-4 rounded-[1.5rem] bg-gray-50 border-transparent focus:bg-white focus:border-[#356ee7] focus:ring-4 focus:ring-blue-50 outline-none transition-all resize-none text-sm text-gray-700" 
                            placeholder="<?php echo __('review_placeholder', 'Chia sẻ cảm nhận của bạn về chất lượng sản phẩm...'); ?>" required></textarea>
                    </div>

                    <button type="submit" class="w-full py-5 bg-[#356ee7] hover:bg-[#2859c5] text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] transition-all shadow-[0_15px_30px_rgba(53,110,231,0.2)] active:scale-[0.98]">
                        <?php echo __('submit_review_btn', 'GỬI ĐÁNH GIÁ'); ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function previewReviewImage(input) {
    const preview = document.getElementById('image-preview');
    const img = preview.querySelector('img');
    const label = document.getElementById('upload-label');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
            label.textContent = '<?php echo __('file_selected_label', 'Đã chọn: '); ?>' + (input.files[0].name.length > 15 ? input.files[0].name.substring(0, 12) + '...' : input.files[0].name);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeReviewImage() {
    const input = document.getElementById('review_image');
    const preview = document.getElementById('image-preview');
    const label = document.getElementById('upload-label');
    
    input.value = '';
    preview.classList.add('hidden');
    label.textContent = '<?php echo __('select_photo_btn', 'Chọn ảnh'); ?>';
}

function openImageLightbox(src) {
    const lightbox = document.createElement('div');
    lightbox.className = 'fixed inset-0 z-[200] bg-black/95 flex items-center justify-center p-10 cursor-zoom-out animate-in fade-in duration-300';
    lightbox.innerHTML = `<img src="${src}" class="max-w-full max-h-full rounded-3xl shadow-2xl animate-in zoom-in-95 duration-300">`;
    lightbox.onclick = () => {
        lightbox.classList.add('animate-out', 'fade-out', 'duration-300');
        setTimeout(() => lightbox.remove(), 300);
    };
    document.body.appendChild(lightbox);
}
</script>

<style>
.rating-input { flex-direction: row-reverse; }
.rating-input label:hover ~ label { color: #fbbf24; font-variation-settings: 'FILL' 1; }
</style>
