<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visitor_id = $_POST['visitor_id'] ?? '';
    $status = $_POST['status'] ?? '';

    if (!empty($visitor_id) && !empty($status)) {
        setVisitorStatus($visitor_id, $status);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>
