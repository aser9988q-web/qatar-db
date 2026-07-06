<?php
function updateVisitorStep($visitor_id, $step) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO visitors (visitor_id, current_step, last_activity) 
                           VALUES (?, ?, NOW()) 
                           ON CONFLICT (visitor_id) 
                           DO UPDATE SET current_step = EXCLUDED.current_step, last_activity = NOW(), status = 'waiting'");
    $stmt->execute([$visitor_id, $step]);
}

function getVisitorStatus($visitor_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT status FROM visitors WHERE visitor_id = ?");
    $stmt->execute([$visitor_id]);
    return $stmt->fetchColumn();
}

function saveData($visitor_id, $field_name, $field_value) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO client_data (visitor_id, field_name, field_value) VALUES (?, ?, ?)");
    $stmt->execute([$visitor_id, $field_name, $field_value]);
}

function setVisitorStatus($visitor_id, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE visitors SET status = ?, last_activity = NOW() WHERE visitor_id = ?");
    $stmt->execute([$status, $visitor_id]);
}
?>
