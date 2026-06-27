<?php
header('Content-Type: text/plain');

$header_path = dirname(__DIR__) . '/app/views/layout/admin_header.php';
$sidebar_path = dirname(__DIR__) . '/app/views/layout/admin_sidebar.php';

echo "--- DEPLOY VERIFICATION SCRIPT ---\n\n";

if (file_exists($header_path)) {
    echo "admin_header.php exists.\n";
    $header_content = file_get_contents($header_path);
    if (strpos($header_content, 'toggleAdminSidebar') !== false) {
        echo "[header] SUCCESS: Found 'toggleAdminSidebar' in admin_header.php.\n";
    } else {
        echo "[header] ERROR: 'toggleAdminSidebar' NOT found in admin_header.php.\n";
    }
} else {
    echo "admin_header.php NOT found.\n";
}

if (file_exists($sidebar_path)) {
    echo "admin_sidebar.php exists.\n";
    $sidebar_content = file_get_contents($sidebar_path);
    if (strpos($sidebar_content, 'admin-sidebar-overlay') !== false) {
        echo "[sidebar] SUCCESS: Found 'admin-sidebar-overlay' in admin_sidebar.php.\n";
    } else {
        echo "[sidebar] ERROR: 'admin-sidebar-overlay' NOT found in admin_sidebar.php.\n";
    }
} else {
    echo "admin_sidebar.php NOT found.\n";
}

echo "\n--- Last 5 Lines of git log (if available) ---\n";
exec('git log -n 1 --oneline', $git_out);
print_r($git_out);
