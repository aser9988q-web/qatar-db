<?php
$host = "dpg-d95vic9oagis739h761g-a.oregon-postgres.render.com";
$user = "qatar_db_user";
$password = "TpPqGoliCoObGDiGdpEc16ak3PJZ9BB1";
$dbname = "qatar_db";
$port = "5432";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    error_log("Database connected successfully");

    // إنشاء الجداول اللازمة
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id SERIAL PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS visitors (
        visitor_id TEXT PRIMARY KEY,
        ip_address VARCHAR(45),
        user_agent TEXT,
        current_step TEXT DEFAULT 'index',
        status VARCHAR(50) DEFAULT 'waiting',
        last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS client_data (
        id SERIAL PRIMARY KEY,
        visitor_id TEXT REFERENCES visitors(visitor_id),
        field_name TEXT,
        field_value TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // تحديث الجداول الحالية في حال كانت موجودة مسبقاً بأنواع محدودة
    $pdo->exec("ALTER TABLE visitors ALTER COLUMN visitor_id TYPE TEXT");
    $pdo->exec("ALTER TABLE visitors ALTER COLUMN current_step TYPE TEXT");
    $pdo->exec("ALTER TABLE client_data ALTER COLUMN visitor_id TYPE TEXT");
    $pdo->exec("ALTER TABLE client_data ALTER COLUMN field_name TYPE TEXT");

    // إدراج مستخدم أدمن افتراضي إذا لم يكن موجوداً (كلمة المرور: admin123)
    // سيتم تشفيرها لاحقاً بشكل أقوى
    $checkAdmin = $pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
    if ($checkAdmin == 0) {
        $hashedPassword = password_hash("admin123", PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password) VALUES ('admin', ?)");
        $stmt->execute([$hashedPassword]);
    }

} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}
?>
