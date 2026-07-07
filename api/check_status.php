<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

$visitor_id = $_GET['visitor_id'] ?? '';

if (empty($visitor_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Visitor ID missing']);
    exit;
}

$status = getVisitorStatus($visitor_id);
$target = getTargetPage($visitor_id);

// إذا كان هناك صفحة مستهدفة، نقوم بمسحها بعد إرسالها لمرة واحدة لمنع التكرار اللانهائي
if ($target) {
    setTargetPage($visitor_id, null);
}

echo json_encode(['status' => $status, 'redirect' => $target]);
?>
