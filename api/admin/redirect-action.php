<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$visitor_id = $input['reference'] ?? '';
$target_page = $input['target_page'] ?? '';

if (!$visitor_id || !$target_page) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

try {
    setTargetPage($visitor_id, $target_page);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
