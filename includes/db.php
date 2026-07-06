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

    // إنشاء الجداول اللازمة
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id SERIAL PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS visitors (
        visitor_id VARCHAR(50) PRIMARY KEY,
        ip_address VARCHAR(45),
        user_agent TEXT,
        current_step VARCHAR(50) DEFAULT 'index',
        status VARCHAR(20) DEFAULT 'waiting', -- waiting, approved, rejected
        last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS client_data (
        id SERIAL PRIMARY KEY,
        visitor_id VARCHAR(50) REFERENCES visitors(visitor_id),
        field_name VARCHAR(50),
        field_value TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

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
