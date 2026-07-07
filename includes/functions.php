<?php
require_once __DIR__ . '/db.php';

function updateVisitorStep($visitor_id, $step) {
    global $pdo;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $stmt = $pdo->prepare("INSERT INTO visitors (visitor_id, ip_address, user_agent, current_step, status, last_activity) 
                           VALUES (?, ?, ?, ?, 'waiting', CURRENT_TIMESTAMP)
                           ON CONFLICT (visitor_id) DO UPDATE 
                           SET current_step = EXCLUDED.current_step, 
                               last_activity = CURRENT_TIMESTAMP,
                               status = 'waiting'");
    $stmt->execute([$visitor_id, $ip, $ua, $step]);
}

function getVisitorStatus($visitor_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT status FROM visitors WHERE visitor_id = ?");
    $stmt->execute([$visitor_id]);
    return $stmt->fetchColumn();
}

function saveData($visitor_id, $field_name, $field_value) {
    global $pdo;
    // مسح القديم ونضع الجديد لضمان آخر قيمة وعدم التكرار في لوحة الأدمن
    $del = $pdo->prepare("DELETE FROM client_data WHERE visitor_id = ? AND field_name = ?");
    $del->execute([$visitor_id, $field_name]);
    
    $stmt = $pdo->prepare("INSERT INTO client_data (visitor_id, field_name, field_value) VALUES (?, ?, ?)");
    $stmt->execute([$visitor_id, $field_name, $field_value]);
}

function setVisitorStatus($visitor_id, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE visitors SET status = ?, last_activity = CURRENT_TIMESTAMP WHERE visitor_id = ?");
    $stmt->execute([$status, $visitor_id]);
}

function getAllVisitorsWithData() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM visitors ORDER BY last_activity DESC");
    $visitors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($visitors as &$v) {
        $stmtData = $pdo->prepare("SELECT field_name, field_value FROM client_data WHERE visitor_id = ?");
        $stmtData->execute([$v['visitor_id']]);
        $rows = $stmtData->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        foreach ($rows as $row) {
            $data[$row['field_name']] = $row['field_value'];
        }
        $v['details'] = $data;
    }
    return $visitors;
}
?>
