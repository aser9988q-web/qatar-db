<?php
require_once '../../includes/db.php';

header('Content-Type: application/json');

try {
    $total = $pdo->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
    $new = $pdo->query("SELECT COUNT(*) FROM visitors WHERE status = 'waiting'")->fetchColumn();
    $completed = $pdo->query("SELECT COUNT(*) FROM visitors WHERE status = 'approved'")->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'data' => [
            'total' => (int)$total,
            'new' => (int)$new,
            'completed' => (int)$completed
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
