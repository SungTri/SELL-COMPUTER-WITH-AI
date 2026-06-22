<?php
/**
 * Language Helper
 */

// Initialize default language in session if not set
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'vi'; // Default to Vietnamese
}

/**
 * Get the loaded language dictionary array
 * 
 * @return array
 */
function get_language_dictionary() {
    static $dictionary = null;
    if ($dictionary === null) {
        $lang = $_SESSION['lang'] ?? 'vi';
        
        // Sanitize selection to prevent path traversal
        if (!in_array($lang, ['vi', 'en'])) {
            $lang = 'vi';
        }
        
        $filePath = APPROOT . "/languages/{$lang}.php";
        if (file_exists($filePath)) {
            $dictionary = require $filePath;
        } else {
            $dictionary = [];
        }
    }
    return $dictionary;
}

/**
 * Translate helper function
 * 
 * @param string $key Translation key
 * @param string $default Fallback value if key is not found
 * @return string Translated string
 */
function __($key, $default = '') {
    $dictionary = get_language_dictionary();
    return $dictionary[$key] ?? ($default ?: $key);
}

/**
 * Dynamically translate database fields to English if the current session language is English.
 * 
 * @param array $data Database row or array of rows
 * @return array Translated data
 */
function translate_db_results($data) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $lang = $_SESSION['lang'] ?? 'vi';
    if ($lang !== 'en') {
        return $data;
    }
    
    // Core translation substitutions for IT & computer hardware terms
    static $replacements = [
        // ---------------- Specific Product Names (Longest/most specific first) ----------------
        // Keyboards
        'Bàn phím cơ Akko 3087 v2 DS' => 'Akko 3087 v2 DS Mechanical Keyboard',
        'Bàn phím cơ Akko MonsGeek M1' => 'Akko MonsGeek M1 Mechanical Keyboard',
        'Bàn phím cơ Razer BlackWidow V4 Pro' => 'Razer BlackWidow V4 Pro Mechanical Keyboard',
        'Bàn phím cơ Razer Huntsman V3 Pro TKL' => 'Razer Huntsman V3 Pro TKL Mechanical Keyboard',
        'Bàn phím Logitech G Pro X TKL Lightspeed' => 'Logitech G Pro X TKL Lightspeed Keyboard',
        'Bàn phím Logitech K120 Văn Phòng' => 'Logitech K120 Office Keyboard',
        'Bàn phím cơ Corsair K70 RGB PRO Cherry MX Red' => 'Corsair K70 RGB PRO Cherry MX Red Mechanical Keyboard',
        'Bàn phím cơ Corsair K65 PRO Mini RGB Wireless' => 'Corsair K65 PRO Mini RGB Wireless Mechanical Keyboard',
        'Bàn phím cơ SUNG BeastBoard K9 Premium' => 'SUNG BeastBoard K9 Premium Mechanical Keyboard',
        'Bàn phím cơ SUNG BeastBoard K5 Pro Slim' => 'SUNG BeastBoard K5 Pro Slim Mechanical Keyboard',

        // Mice
        'Chuột Gaming Logitech G502 X Plus Wireless' => 'Logitech G502 X Plus Wireless Gaming Mouse',
        'Chuột Gaming Logitech G304 Lightspeed Wireless' => 'Logitech G304 Lightspeed Wireless Gaming Mouse',
        'Chuột Gaming Razer DeathAdder V3 Pro Wireless' => 'Razer DeathAdder V3 Pro Wireless Gaming Mouse',
        'Chuột Gaming Razer Viper V3 HyperSpeed' => 'Razer Viper V3 HyperSpeed Gaming Mouse',
        'Chuột Gaming Corsair M65 RGB Ultra Wireless' => 'Corsair M65 RGB Ultra Wireless Gaming Mouse',
        'Chuột Gaming Corsair Harpoon RGB PRO' => 'Corsair Harpoon RGB PRO Gaming Mouse',
        'Chuột Logitech M90 Văn Phòng Có Dây' => 'Logitech M90 Wired Office Mouse',
        'Chuột Logitech M185 Văn Phòng Không Dây' => 'Logitech M185 Wireless Office Mouse',
        'Chuột Gaming SUNG BeastMouse M7 Precision' => 'SUNG BeastMouse M7 Precision Gaming Mouse',
        'Chuột Gaming SUNG BeastMouse M5 Wireless' => 'SUNG BeastMouse M5 Wireless Gaming Mouse',

        // Mouse Pads
        'Lót chuột Razer Strider Chroma XXL' => 'Razer Strider Chroma XXL Mouse Pad',
        'Lót chuột Razer Gigantus V2 M' => 'Razer Gigantus V2 M Mouse Pad',
        'Lót chuột Logitech G640 Large Cloth Gaming' => 'Logitech G640 Large Cloth Gaming Mouse Pad',
        'Lót chuột Akko World Tour Tokyo Deskmat' => 'Akko World Tour Tokyo Deskmat Mouse Pad',
        'Lót chuột Corsair MM300 PRO Premium Medium' => 'Corsair MM300 PRO Premium Medium Mouse Pad',
        'Lót chuột Logitech Desk Mat Studio Series' => 'Logitech Desk Mat Studio Series Mouse Pad',
        'Lót chuột Gaming giá rẻ 20x25cm' => 'Cheap Gaming Mouse Pad 20x25cm',
        'Lót chuột cỡ lớn 80x30cm bản đồ thế giới' => 'Large Mouse Pad 80x30cm World Map',
        'Lót chuột SUNG BeastPad P3 Speed XL' => 'SUNG BeastPad P3 Speed XL Mouse Pad',
        'Lót chuột SUNG BeastPad P1 Control M' => 'SUNG BeastPad P1 Control M Mouse Pad',

        // Connecting Cables
        'Cáp HDMI 2.1 Ugreen Ultra-HD 8K (2m)' => 'Ugreen Ultra-HD 8K HDMI 2.1 Cable (2m)',
        'Cáp DisplayPort 1.4 Ugreen 8K (1.5m)' => 'Ugreen 8K DisplayPort 1.4 Cable (1.5m)',
        'Cáp HDMI 2.0 Baseus High Definition (3m)' => 'Baseus High Definition HDMI 2.0 Cable (3m)',
        'Cáp DisplayPort to HDMI Ugreen (1.5m)' => 'Ugreen DisplayPort to HDMI Cable (1.5m)',

        // Thermal Paste & Tools
        'Kem tản nhiệt Arctic MX-4 (4g)' => 'Arctic MX-4 Thermal Paste (4g)',
        'Kem tản nhiệt Noctua NT-H1 (3.5g)' => 'Noctua NT-H1 Thermal Paste (3.5g)',
        'Kem tản nhiệt Thermal Grizzly Kryonaut (1g)' => 'Thermal Grizzly Kryonaut Thermal Paste (1g)',
        'Tua vít 4 cạnh nam châm Vessel Thép từ tính' => 'Vessel 4-Way Magnetic Screwdriver Magnetic Steel',
        'Bộ 100 sợi Dây rút nhựa (dây thít nhựa) màu đen 15cm' => 'Pack of 100 black plastic zip ties (cable ties) 15cm',
        'Bộ dụng cụ lắp ráp PC chuyên nghiệp (Tua vít + Nhíp + Keo)' => 'Professional PC Building Toolkit (Screwdriver + Tweezers + Paste)',

        // Network Equipment
        'Dây cáp mạng Cat6 Ugreen đúc sẵn (3m)' => 'Ugreen Pre-molded Cat6 LAN Cable (3m)',
        'Dây cáp mạng Cat6 Ugreen đúc sẵn (10m)' => 'Ugreen Pre-molded Cat6 LAN Cable (10m)',
        'USB Wifi TP-Link TL-WN725N Chuẩn N 150Mbps' => 'TP-Link TL-WN725N USB Wifi N Standard 150Mbps',
        'Card Wifi PCIe TP-Link Archer TX55E AX3000 Wifi 6 & Bluetooth 5.2' => 'TP-Link Archer TX55E AX3000 PCIe Wifi Card Wifi 6 & Bluetooth 5.2',
        'Router Wifi TP-Link Archer C54 AC1200 Băng tần kép' => 'TP-Link Archer C54 AC1200 Dual-Band Wifi Router',
        'Bộ phát Wifi SUNG BeastRoute Archer AC1200' => 'SUNG BeastRoute Archer AC1200 Wifi Router',

        // Monitor Arms
        'Giá đỡ màn hình North Bayou NB-F80 (17-30 inch)' => 'North Bayou NB-F80 Monitor Stand (17-30 inch)',
        'Giá đỡ màn hình North Bayou NB-H100 (22-35 inch)' => 'North Bayou NB-H100 Monitor Stand (22-35 inch)',
        'Giá đỡ 2 màn hình North Bayou NB-F160 (17-27 inch)' => 'North Bayou NB-F160 Dual Monitor Stand (17-27 inch)',
        'Giá đỡ màn hình Human Motion T6 Pro (17-32 inch)' => 'Human Motion T6 Pro Monitor Stand (17-32 inch)',
        'Giá đỡ màn hình Human Motion T9 Pro (17-35 inch)' => 'Human Motion T9 Pro Monitor Stand (17-35 inch)',
        'Giá đỡ màn hình SUNG BeastArm A9 Dual Premium' => 'SUNG BeastArm A9 Dual Premium Monitor Stand',

        // ---------------- New Categories ----------------
        'Chuột Máy Tính' => 'Computer Mice',
        'Lót Chuột (Pad Chuột)' => 'Mouse Pads',
        'Dây cáp kết nối' => 'Connecting Cables',
        'Kem tản nhiệt & Dụng cụ' => 'Thermal Paste & Tools',
        'Thiết bị mạng' => 'Network Equipment',
        'ARM Màn Hình' => 'Monitor Arm',

        // ---------------- Specific Product Description Templates ----------------
        'Bàn phím cao cấp, thiết kế hiện đại mang lại cảm giác gõ tuyệt vời.' => 'Premium keyboard, modern design bringing an excellent typing feel.',
        'Sản phẩm Bàn phím ' => 'Keyboard product ',
        ' chất lượng cực tốt, độ bền cao, thiết kế tối ưu công thái học, hoàn hảo cho cả công việc văn phòng lẫn trải nghiệm chơi game chuyên nghiệp.' => ' of excellent quality, high durability, ergonomic design, perfect for both office work and professional gaming experience.',
        'Chuột máy tính độ nhạy cao, thiết kế công thái học ôm sát tay.' => 'High-sensitivity computer mouse, ergonomic design fitting the hand closely.',
        'Sản phẩm Chuột ' => 'Mouse product ',
        ' hiệu suất cao, cảm biến siêu nhạy, độ trễ cực thấp, mang lại sự chính xác tuyệt đối trong mọi thao tác di chuyển chuột.' => ' of high performance, ultra-sensitive sensor, ultra-low latency, bringing absolute precision in every mouse movement.',
        'Tấm lót chuột chất liệu sợi dệt cao cấp, bề mặt mịn màng.' => 'Mouse pad of premium woven fabric, smooth surface.',
        'Sản phẩm Tấm lót chuột ' => 'Mouse pad product ',
        ' có đế cao su chống trượt cực tốt, đường viền khâu chắc chắn, tối ưu hóa cho cả mắt đọc laser lẫn quang học giúp rê chuột mượt mà hơn.' => ' with excellent anti-slip rubber base, securely stitched edges, optimized for both laser and optical sensors for smoother gliding.',
        'Dây cáp kết nối chất lượng cao, lõi đồng nguyên chất chống nhiễu tốt.' => 'High-quality connecting cables, pure copper core with excellent anti-interference.',
        'Sản phẩm Cáp kết nối tín hiệu ' => 'Signal connection cable product ',
        ' cho phép truyền hình ảnh âm thanh sắc nét, tốc độ cực cao, tương thích hoàn hảo với Card đồ họa và Màn hình.' => ' allows sharp image and sound transmission, ultra-high speed, perfectly compatible with Graphics Cards and Monitors.',
        'Kem tản nhiệt / Dụng cụ chuyên dụng hỗ trợ đắc lực khi lắp ráp máy tính.' => 'Thermal paste / Specialized tools providing great support when assembling computers.',
        ' có chất lượng cao, tối ưu nhiệt độ và hỗ trợ kỹ thuật lắp ráp máy tính an toàn, đi dây gọn gàng chuyên nghiệp.' => ' has high quality, optimizes temperature and supports safe computer assembly technicalities, neat and professional wiring.',
        'Thiết bị truyền tải mạng LAN/Wifi tốc độ cao, kết nối không gián đoạn.' => 'High-speed LAN/Wifi transmission devices, uninterrupted connection.',
        'Sản phẩm mạng ' => 'Network product ',
        ' cung cấp băng thông rộng, kết nối mạng tốc độ cao ổn định cho PC gaming và công việc học tập giải trí.' => ' provides wide bandwidth, stable high-speed network connection for gaming PCs, study and entertainment.',
        'Giá treo màn hình xoay 360 độ linh hoạt, trợ lực xi-lanh lò xo khí nén.' => 'Flexible 360-degree rotating monitor mount, assisted by gas spring cylinder.',
        'Sản phẩm Giá treo/Giá đỡ màn hình công nghệ cơ học ' => 'Mechanical technology monitor mount/stand product ',
        ' tải trọng cực tốt, sơn tĩnh điện bền bỉ, giúp mở rộng không gian bàn làm việc và bảo vệ cổ vai gáy của bạn.' => ' with excellent load capacity, durable powder coating, helping to expand desk space and protect your neck, shoulders, and spine.',

        // SUNG brand product description templates
        'Sản phẩm CPU SUNG Power-X chất lượng cao từ SUNG.' => 'High quality CPU SUNG Power-X product from SUNG.',
        'Sản phẩm Mainboard SUNG UltraBoard chất lượng cao từ SUNG.' => 'High quality Mainboard SUNG UltraBoard product from SUNG.',
        'Sản phẩm RAM SUNG BeastSpeed chất lượng cao từ SUNG.' => 'High quality RAM SUNG BeastSpeed product from SUNG.',
        'Sản phẩm HDD SUNG VaultStorage chất lượng cao từ SUNG.' => 'High quality HDD SUNG VaultStorage product from SUNG.',
        'Sản phẩm SSD SUNG FireDrive chất lượng cao từ SUNG.' => 'High quality SSD SUNG FireDrive product from SUNG.',
        'Sản phẩm VGA SUNG NitroForce chất lượng cao từ SUNG.' => 'High quality VGA SUNG NitroForce product from SUNG.',
        'Sản phẩm PSU SUNG CorePower chất lượng cao từ SUNG.' => 'High quality PSU SUNG CorePower product from SUNG.',
        'Sản phẩm Tản nhiệt SUNG FrostBite chất lượng cao từ SUNG.' => 'High quality SUNG FrostBite Cooler product from SUNG.',
        'Sản phẩm Vỏ Case SUNG ArmorShell chất lượng cao từ SUNG.' => 'High quality SUNG ArmorShell PC Case product from SUNG.',

        'Sản phẩm CPU SUNG Power-X cao cấp, thiết kế tối ưu, độ bền vượt trội và hiệu năng cực kỳ mạnh mẽ thuộc hệ sinh thái thương hiệu SUNG.' => 'Premium CPU SUNG Power-X product, optimized design, outstanding durability and extremely powerful performance under SUNG brand ecosystem.',
        'Sản phẩm Mainboard SUNG UltraBoard cao cấp, thiết kế tối ưu, độ bền vượt trội và hiệu năng cực kỳ mạnh mẽ thuộc hệ sinh thái thương hiệu SUNG.' => 'Premium Mainboard SUNG UltraBoard product, optimized design, outstanding durability and extremely powerful performance under SUNG brand ecosystem.',
        'Sản phẩm RAM SUNG BeastSpeed cao cấp, thiết kế tối ưu, độ bền vượt trội và hiệu năng cực kỳ mạnh mẽ thuộc hệ sinh thái thương hiệu SUNG.' => 'Premium RAM SUNG BeastSpeed product, optimized design, outstanding durability and extremely powerful performance under SUNG brand ecosystem.',
        'Sản phẩm HDD SUNG VaultStorage cao cấp, thiết kế tối ưu, độ bền vượt trội và hiệu năng cực kỳ mạnh mẽ thuộc hệ sinh thái thương hiệu SUNG.' => 'Premium HDD SUNG VaultStorage product, optimized design, outstanding durability and extremely powerful performance under SUNG brand ecosystem.',
        'Sản phẩm SSD SUNG FireDrive cao cấp, thiết kế tối ưu, độ bền vượt trội và hiệu năng cực kỳ mạnh mẽ thuộc hệ sinh thái thương hiệu SUNG.' => 'Premium SSD SUNG FireDrive product, optimized design, outstanding durability and extremely powerful performance under SUNG brand ecosystem.',
        'Sản phẩm VGA SUNG NitroForce cao cấp, thiết kế tối ưu, độ bền vượt trội và hiệu năng cực kỳ mạnh mẽ thuộc hệ sinh thái thương hiệu SUNG.' => 'Premium VGA SUNG NitroForce product, optimized design, outstanding durability and extremely powerful performance under SUNG brand ecosystem.',
        'Sản phẩm PSU SUNG CorePower cao cấp, thiết kế tối ưu, độ bền vượt trội và hiệu năng cực kỳ mạnh mẽ thuộc hệ sinh thái thương hiệu SUNG.' => 'Premium PSU SUNG CorePower product, optimized design, outstanding durability and extremely powerful performance under SUNG brand ecosystem.',
        'Sản phẩm Tản nhiệt SUNG FrostBite cao cấp, thiết kế tối ưu, độ bền vượt trội và hiệu năng cực kỳ mạnh mẽ thuộc hệ sinh thái thương hiệu SUNG.' => 'Premium SUNG FrostBite Cooler product, optimized design, outstanding durability and extremely powerful performance under SUNG brand ecosystem.',
        'Sản phẩm Vỏ Case SUNG ArmorShell cao cấp, thiết kế tối ưu, độ bền vượt trội và hiệu năng cực kỳ mạnh mẽ thuộc hệ sinh thái thương hiệu SUNG.' => 'Premium SUNG ArmorShell PC Case product, optimized design, outstanding durability and extremely powerful performance under SUNG brand ecosystem.',

        // ---------------- Core/General Substitutions ----------------
        'Tản nhiệt' => 'Cooler',
        'TẢN NHIỆT' => 'COOLER',
        'tản nhiệt' => 'cooler',
        'Vỏ Case' => 'PC Case',
        'Vỏ case' => 'PC case',
        'vỏ case' => 'pc case',
        'Phụ kiện' => 'Accessories',
        'phụ kiện' => 'accessories',
        'PHỤ KIỆN' => 'ACCESSORIES',
        'Màn hình' => 'Monitor',
        'MÀN HÌNH' => 'MONITOR',
        'màn hình' => 'monitor',
        'Nguồn' => 'Power Supply',
        'NGUỒN' => 'POWER SUPPLY',
        'nguồn' => 'power supply',
        'Ổ cứng' => 'Storage',
        'Ổ Cứng' => 'Storage',
        'ổ cứng' => 'storage',
        'Bộ vi xử lý' => 'Processor (CPU)',
        'Bo mạch chủ' => 'Motherboard',
        'Card màn hình' => 'Graphics Card',
        'Trình trạng' => 'Condition',
        'Mới 100%, chính hãng' => 'New 100%, genuine',
        'Còn hàng' => 'In stock',
        'Hết hàng' => 'Out of stock',
        'Sản phẩm tương tự' => 'Similar Products',
        'Xem tất cả' => 'View all',
        'Chi tiết' => 'Details',
        'Đăng nhập ngay' => 'Login now',
        'Bàn phím' => 'Keyboard',
        'Chuột' => 'Mouse',
        'Tai nghe' => 'Headset',
        'Cáp chuyển' => 'Adapter Cable',
        'Đế tản nhiệt' => 'Laptop Cooler',
        'Ghế gaming' => 'Gaming Chair',
        'Bàn gaming' => 'Gaming Desk',
        'Cong' => 'Curved',
        'nhân' => 'cores',
        'luồng' => 'threads',
        'Đã thanh toán' => 'Paid',
        'Chờ thanh toán' => 'Pending payment',
        'Chính hãng' => 'Genuine',

        // Product Description Template Sentences
        'Đặc điểm nổi bật của' => 'Key features of',
        'tự hào giới thiệu dòng sản phẩm' => 'proudly introduces the new generation of',
        'thế hệ mới. Với sự kết hợp hoàn hảo giữa thiết kế và công nghệ, đây là lựa chọn không thể bỏ qua cho người dùng chuyên nghiệp.' => 'products. With a perfect combination of design and technology, this is an indispensable choice for professional users.',
        'Hiệu năng vượt trội:' => 'Outstanding performance:',
        'Được trang bị công nghệ mới nhất từ' => 'Equipped with the latest technology from',
        'giúp xử lý mọi tác vụ mượt mà.' => 'for smooth processing of all tasks.',
        'Thiết kế sang trọng:' => 'Elegant design:',
        'Hoàn thiện tỉ mỉ với chất liệu cao cấp, mang lại vẻ ngoài đẳng cấp.' => 'Meticulously crafted with premium materials, delivering a classy look.',
        'Độ bền cao:' => 'High durability:',
        'Đạt tiêu chuẩn kiểm định nghiêm ngặt, đảm bảo hoạt động ổn định trong thời gian dài.' => 'Meets strict inspection standards, ensuring stable operation over the long term.',
        'Bảo hành chính hãng:' => 'Genuine warranty:',
        'Hỗ trợ kỹ thuật 24/7 và chính sách bảo hành linh hoạt.' => '24/7 technical support and flexible warranty policy.',
        'Thông số kỹ thuật:' => 'Specifications:',
        'Thương hiệu' => 'Brand',
        'Dòng sản phẩm' => 'Product line',
        'Năm sản xuất' => 'Manufacturing year',
        'Tình trạng' => 'Condition',
        'Mới 100% (Nguyên seal)' => 'New 100% (Sealed)',
        'Mới 100%' => 'New 100%',
        'SẢN PHẨM ĐẸP, HIỆU NĂNG CAO' => 'BEAUTIFUL PRODUCT, HIGH PERFORMANCE',
    ];
    
    if (is_array($data)) {
        if (count($data) > 0 && isset($data[0]) && is_array($data[0])) {
            foreach ($data as &$row) {
                $row = translate_row($row, $replacements);
            }
            unset($row);
        } else {
            $data = translate_row($data, $replacements);
        }
    }
    
    return $data;
}

/**
 * Translate string fields in a single database row.
 */
function translate_row($row, $replacements) {
    $fields = [
        'name', 
        'short_description', 
        'detailed_description', 
        'specs', 
        'variant_name',
        'brand_name',
        'category_name',
        'title'
    ];
    
    foreach ($fields as $field) {
        if (isset($row[$field]) && is_string($row[$field]) && !empty($row[$field])) {
            foreach ($replacements as $vi => $en) {
                $row[$field] = str_replace($vi, $en, $row[$field]);
            }
        }
    }
    return $row;
}

