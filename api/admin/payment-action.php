<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$visitor_id = $input['reference'] ?? '';
$action = $input['action'] ?? '';

if (!$visitor_id) {
    echo json_encode(['success' => false, 'error' => 'Missing reference']);
    exit;
}

try {
    $status = ($action === 'pass') ? 'approved' : 'rejected';
    setVisitorStatus($visitor_id, $status);
    
    echo json_encode([
        'success' => true, 
        'currentStep' => 1, 
        'targetPage' => $status,
        'status' => $status
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
