<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>لوحة التحكم - نظام النافذة الواحدة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f8fafc; color: #1e293b; font-family: 'Segoe UI', Tahoma, Arial, sans-serif; font-size: 14px; }

    /* ===== Header ===== */
    .top-header {
      background: #fff;
      border-bottom: 1px solid #e2e8f0;
      padding: 12px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .header-brand { display: flex; align-items: center; gap: 10px; }
    .header-icon {
      width: 36px; height: 36px;
      background: #2563eb; border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: white; font-size: 18px;
    }
    .header-title { font-weight: 700; color: #1e293b; font-size: 15px; }
    .header-subtitle { font-size: 11px; color: #64748b; }
    .header-actions { display: flex; align-items: center; gap: 8px; }
    .btn-icon {
      background: none; border: 1px solid #e2e8f0; color: #64748b;
      padding: 6px 10px; border-radius: 8px; cursor: pointer; font-size: 14px;
      display: flex; align-items: center; gap: 5px;
    }
    .btn-icon:hover { background: #f1f5f9; }
    .btn-logout {
      background: none; border: 1px solid #fca5a5; color: #dc2626;
      padding: 6px 14px; border-radius: 8px; cursor: pointer; font-size: 13px;
      display: flex; align-items: center; gap: 6px;
    }
    .btn-logout:hover { background: #fef2f2; }
    .conn-wrap { display: flex; align-items: center; gap: 5px; font-size: 12px; color: #64748b; }
    .conn-dot { width: 8px; height: 8px; border-radius: 50%; }
    .conn-dot.on { background: #16a34a; }
    .conn-dot.off { background: #dc2626; }
    .bell-wrap { position: relative; }
    .bell-badge {
      position: absolute; top: -4px; right: -4px;
      background: #dc2626; color: white; font-size: 10px; font-weight: 700;
      width: 17px; height: 17px; border-radius: 50%;
      display: none; align-items: center; justify-content: center;
    }

    /* ===== Main ===== */
    .main-wrap { max-width: 1280px; margin: 0 auto; padding: 24px 20px; }

    /* ===== Stats ===== */
    .stats-row { display: grid; grid-template-columns: repeat(5,1fr); gap: 16px; margin-bottom: 24px; }
    @media(max-width:900px){ .stats-row { grid-template-columns: repeat(3,1fr); } }
    @media(max-width:600px){ .stats-row { grid-template-columns: repeat(2,1fr); } }
    .stat-card {
      background: #fff; border-radius: 12px; padding: 18px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06);
      display: flex; align-items: center; justify-content: space-between;
    }
    .stat-label { font-size: 12px; color: #64748b; margin-bottom: 4px; }
    .stat-val { font-size: 26px; font-weight: 700; }
    .stat-ico { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .s-total .stat-val { color: #1e293b; } .s-total .stat-ico { background: #eff6ff; color: #2563eb; }
    .s-new .stat-val { color: #2563eb; } .s-new .stat-ico { background: #eff6ff; color: #2563eb; }
    .s-done .stat-val { color: #16a34a; } .s-done .stat-ico { background: #f0fdf4; color: #16a34a; }
    .s-pend .stat-val { color: #d97706; } .s-pend .stat-ico { background: #fffbeb; color: #d97706; }
    .s-online .stat-val { color: #0891b2; } .s-online .stat-ico { background: #ecfeff; color: #0891b2; }

    /* ===== Table Card ===== */
    .tbl-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); overflow: hidden; }
    .tbl-header {
      padding: 16px 20px; display: flex; align-items: center;
      justify-content: space-between; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; gap: 10px;
    }
    .tbl-title { font-weight: 600; color: #1e293b; font-size: 15px; }
    .search-wrap { position: relative; width: 240px; }
    .search-wrap input {
      width: 100%; padding: 7px 12px 7px 36px;
      border: 1px solid #e2e8f0; border-radius: 8px;
      font-size: 13px; outline: none; background: #f8fafc;
    }
    .search-wrap input:focus { border-color: #93c5fd; background: #fff; }
    .search-ico { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }

    /* ===== Table ===== */
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #f8fafc; }
    th { padding: 10px 14px; text-align: right; font-size: 12px; font-weight: 600; color: #64748b; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 13px; color: #374151; }
    tbody tr:hover { background: #f8fafc; }
    tbody tr.unread { background: #eff6ff; }
    tbody tr.unread:hover { background: #dbeafe; }

    /* ===== Status Badges ===== */
    .badge {
      display: inline-block; padding: 3px 10px; border-radius: 20px;
      font-size: 11px; font-weight: 600; white-space: nowrap;
    }
    .b-new { background:#dbeafe; color:#1d4ed8; }
    .b-pending_payment { background:#fef9c3; color:#854d0e; }
    .b-pending_nafath { background:#f3e8ff; color:#6b21a8; }
    .b-pending_motasel { background:#ffedd5; color:#9a3412; }
    .b-payment_done { background:#dcfce7; color:#166534; }
    .b-verified { background:#d1fae5; color:#065f46; }
    .b-completed { background:#f1f5f9; color:#475569; }
    .b-cancelled { background:#fee2e2; color:#991b1b; }

    /* ===== Action Buttons ===== */
    .act-btns { display: flex; gap: 5px; align-items: center; flex-wrap: wrap; }
    .btn-act {
      padding: 4px 10px; border-radius: 6px; font-size: 12px; cursor: pointer;
      border: 1px solid #e2e8f0; background: #fff; color: #374151;
      display: flex; align-items: center; gap: 4px; white-space: nowrap;
    }
    .btn-act:hover { background: #f1f5f9; }
    .btn-act.blue { background: #2563eb; color: #fff; border-color: #2563eb; }
    .btn-act.blue:hover { background: #1d4ed8; }

    /* ===== Dropdown ===== */
    .dd-wrap { position: relative; display: inline-block; }
    .dd-menu {
      position: absolute; top: calc(100% + 4px); right: 0;
      background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.12); min-width: 160px;
      z-index: 300; padding: 6px; display: none;
    }
    .dd-menu.open { display: block; }
    .dd-item {
      padding: 8px 12px; border-radius: 6px; cursor: pointer;
      font-size: 13px; color: #374151;
      display: flex; align-items: center; gap: 8px;
    }
    .dd-item:hover { background: #f1f5f9; }

    /* ===== Login ===== */
    #loginSection {
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
      background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    }
    .login-card {
      background: #fff; border-radius: 16px; padding: 40px;
      width: 100%; max-width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .login-icon {
      width: 72px; height: 72px; background: #2563eb; border-radius: 16px;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 20px; color: white; font-size: 36px;
    }
    .login-title { font-size: 22px; font-weight: 700; color: #1e293b; text-align: center; }
    .login-sub { font-size: 13px; color: #64748b; text-align: center; margin: 6px 0 28px; }
    .login-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; display: block; }
    .login-input {
      width: 100%; padding: 11px 14px; border: 1px solid #e2e8f0;
      border-radius: 10px; font-size: 14px; outline: none; margin-bottom: 16px;
    }
    .login-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .login-btn {
      width: 100%; padding: 12px; background: #2563eb; color: white;
      border: none; border-radius: 10px; font-size: 15px; font-weight: 600;
      cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .login-btn:hover { background: #1d4ed8; }
    .login-err { color: #dc2626; font-size: 13px; text-align: center; margin-top: 10px; display: none; }

    /* ===== Dashboard ===== */
    #dashboardSection { display: none; }

    /* ===== Modal ===== */
    .overlay {
      position: fixed; inset: 0; background: rgba(0,0,0,0.4);
      z-index: 500; display: none; align-items: center; justify-content: center; padding: 20px;
    }
    .overlay.show { display: flex; }
    .modal-box {
      background: #fff; border-radius: 16px; width: 100%; max-width: 680px;
      max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-hdr {
      padding: 18px 24px; border-bottom: 1px solid #e2e8f0;
      display: flex; align-items: center; justify-content: space-between;
    }
    .modal-ttl { font-weight: 700; font-size: 16px; color: #1e293b; }
    .modal-close { background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer; padding: 4px 8px; border-radius: 6px; }
    .modal-close:hover { background: #f1f5f9; color: #374151; }
    .modal-bdy { padding: 20px 24px; }
    .sec-title {
      font-size: 11px; font-weight: 700; color: #64748b;
      text-transform: uppercase; letter-spacing: 0.05em;
      margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9;
    }
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
    @media(max-width:500px){ .detail-grid { grid-template-columns: 1fr; } }
    .d-lbl { font-size: 11px; color: #94a3b8; margin-bottom: 3px; }
    .d-val {
      font-size: 13px; color: #1e293b; font-weight: 500;
      background: #f8fafc; padding: 6px 10px; border-radius: 6px;
      display: flex; align-items: center; justify-content: space-between; gap: 6px; word-break: break-all;
    }
    .copy-btn { background: none; border: none; color: #94a3b8; cursor: pointer; padding: 2px; font-size: 13px; flex-shrink: 0; }
    .copy-btn:hover { color: #2563eb; }
    .nav-section { background: #f8fafc; border-radius: 10px; padding: 16px; margin-top: 16px; }
    .nav-sec-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 12px; }
    .nav-btns { display: flex; flex-wrap: wrap; gap: 8px; }
    .nav-btn {
      padding: 7px 14px; border-radius: 8px; font-size: 12px; cursor: pointer;
      border: 1px solid #e2e8f0; background: #fff; color: #374151;
      display: flex; align-items: center; gap: 6px; font-weight: 500;
    }
    .nav-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }

    /* ===== أزرار القبول/الرفض ===== */
    .action-section {
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 20px;
      margin-top: 16px;
    }
    .action-section.has-new {
      background: linear-gradient(135deg, #f0fdf4 0%, #fef2f2 100%);
      border-color: #fca5a5;
      animation: pulse-border 1.5s ease-in-out infinite;
    }
    @keyframes pulse-border {
      0%,100% { border-color: #fca5a5; }
      50% { border-color: #ef4444; }
    }
    .action-sec-title {
      font-size: 14px; font-weight: 700; color: #1e293b;
      margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
    }
    .action-btns { display: flex; gap: 12px; }
    .action-btn-approve {
      flex: 1; padding: 14px 20px; border-radius: 10px; font-size: 15px;
      font-weight: 700; cursor: pointer; border: none;
      background: linear-gradient(135deg, #475569, #334155);
      color: #fff; display: flex; align-items: center; justify-content: center;
      gap: 8px; box-shadow: 0 4px 12px rgba(71,85,105,0.3);
      transition: all 0.2s;
    }
    .action-btn-approve:hover {
      background: linear-gradient(135deg, #334155, #1e293b);
      box-shadow: 0 6px 16px rgba(71,85,105,0.4);
      transform: translateY(-1px);
    }
    .action-btn-approve.active {
      background: linear-gradient(135deg, #16a34a, #15803d);
      box-shadow: 0 4px 12px rgba(22,163,74,0.3);
    }
    .action-btn-approve.active:hover {
      background: linear-gradient(135deg, #15803d, #166534);
      box-shadow: 0 6px 16px rgba(22,163,74,0.4);
    }
    .action-btn-reject {
      flex: 1; padding: 14px 20px; border-radius: 10px; font-size: 15px;
      font-weight: 700; cursor: pointer; border: none;
      background: linear-gradient(135deg, #475569, #334155);
      color: #fff; display: flex; align-items: center; justify-content: center;
      gap: 8px; box-shadow: 0 4px 12px rgba(71,85,105,0.3);
      transition: all 0.2s;
    }
    .action-btn-reject:hover {
      background: linear-gradient(135deg, #334155, #1e293b);
      box-shadow: 0 6px 16px rgba(71,85,105,0.4);
      transform: translateY(-1px);
    }
    .action-btn-reject.active {
      background: linear-gradient(135deg, #dc2626, #b91c1c);
      box-shadow: 0 4px 12px rgba(220,38,38,0.3);
    }
    .action-btn-reject.active:hover {
      background: linear-gradient(135deg, #b91c1c, #991b1b);
      box-shadow: 0 6px 16px rgba(220,38,38,0.4);
    }
    .badge-new-data {
      display: inline-block; background: #ef4444; color: #fff;
      font-size: 10px; font-weight: 700; padding: 2px 7px;
      border-radius: 20px; margin-right: 6px;
      animation: blink 1s ease-in-out infinite;
    }
    @keyframes blink {
      0%,100% { opacity: 1; } 50% { opacity: 0.4; }
    }
    .action-step-info {
      font-size: 12px; color: #64748b; margin-bottom: 12px;
      background: #fff; border-radius: 8px; padding: 8px 12px;
      border: 1px solid #e2e8f0;
    }
    .action-btn-approve:disabled, .action-btn-reject:disabled {
      opacity: 0.5; cursor: not-allowed; transform: none;
    }

    /* ===== Toast ===== */
    .toast-wrap { position: fixed; top: 20px; left: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
    .toast-item {
      background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
      padding: 12px 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.12);
      display: flex; align-items: center; gap: 10px; min-width: 260px;
      animation: toastIn 0.3s ease;
    }
    .toast-item.ok { border-right: 3px solid #16a34a; }
    .toast-item.err { border-right: 3px solid #dc2626; }
    .toast-item.inf { border-right: 3px solid #2563eb; }
    @keyframes toastIn { from { transform: translateX(-20px); opacity:0; } to { transform: translateX(0); opacity:1; } }

    /* ===== Empty ===== */
    .empty { text-align: center; padding: 48px 20px; color: #94a3b8; }
    .empty i { font-size: 48px; display: block; margin-bottom: 12px; }

    @media(max-width:640px){
      .main-wrap { padding: 16px 12px; }
      th:nth-child(4), td:nth-child(4), th:nth-child(5), td:nth-child(5) { display: none; }
    }
  </style>
</head>
<body>

<!-- ===== Login ===== -->
<div id="loginSection">
  <div class="login-card">
    <div class="login-icon"><i class="bi bi-window-stack"></i></div>
    <div class="login-title">نظام النافذة الواحدة</div>
    <div class="login-sub">لوحة التحكم الإدارية</div>
    <label class="login-label"><i class="bi bi-shield-lock"></i> كلمة المرور</label>
    <input type="password" id="pwInput" class="login-input" placeholder="أدخل كلمة المرور" onkeydown="if(event.key==='Enter')doLogin()" />
    <button class="login-btn" onclick="doLogin()"><i class="bi bi-box-arrow-in-right"></i>تسجيل الدخول</button>
    <div class="login-err" id="loginErr">كلمة المرور غير صحيحة</div>
  </div>
</div>

<!-- ===== Dashboard ===== -->
<div id="dashboardSection">
  <div class="top-header">
    <div class="header-brand">
      <div class="header-icon"><i class="bi bi-window-stack"></i></div>
      <div>
        <div class="header-title">نظام النافذة الواحدة</div>
        <div class="header-subtitle">لوحة التحكم</div>
      </div>
    </div>
    <div class="header-actions">
      <div class="conn-wrap">
        <span class="conn-dot off" id="connDot"></span>
        <span id="connTxt">غير متصل</span>
      </div>
      <div class="bell-wrap">
        <button class="btn-icon" onclick="loadAll()"><i class="bi bi-bell"></i></button>
        <span class="bell-badge" id="bellBadge">0</span>
      </div>
      <button class="btn-icon" onclick="loadAll()"><i class="bi bi-arrow-clockwise"></i></button>
      <button class="btn-logout" onclick="doLogout()"><i class="bi bi-box-arrow-right"></i>خروج</button>
    </div>
  </div>

  <div class="main-wrap">
    <div class="stats-row">
      <div class="stat-card s-total">
        <div><div class="stat-label">إجمالي الطلبات</div><div class="stat-val" id="sTotal">0</div></div>
        <div class="stat-ico"><i class="bi bi-people"></i></div>
      </div>
      <div class="stat-card s-new">
        <div><div class="stat-label">طلبات جديدة</div><div class="stat-val" id="sNew">0</div></div>
        <div class="stat-ico"><i class="bi bi-bell"></i></div>
      </div>
      <div class="stat-card s-done">
        <div><div class="stat-label">مكتملة</div><div class="stat-val" id="sDone">0</div></div>
        <div class="stat-ico"><i class="bi bi-check-circle"></i></div>
      </div>
      <div class="stat-card s-pend">
        <div><div class="stat-label">قيد المعالجة</div><div class="stat-val" id="sPend">0</div></div>
        <div class="stat-ico"><i class="bi bi-clock"></i></div>
      </div>
      <div class="stat-card s-online">
        <div><div class="stat-label">زوار متصلون الآن</div><div class="stat-val" id="sOnline">0</div></div>
        <div class="stat-ico"><i class="bi bi-wifi"></i></div>
      </div>
    </div>

    <div class="tbl-card">
      <div class="tbl-header">
        <div class="tbl-title">قائمة الطلبات</div>
        <div class="search-wrap">
          <i class="bi bi-search search-ico"></i>
          <input type="text" id="srch" placeholder="بحث..." oninput="filterRows()" />
        </div>
      </div>
      <div style="overflow-x:auto">
        <table>
          <thead>
            <tr>
              <th>الدولة</th>
              <th>اسم المستخدم</th>
              <th>كلمة المرور</th>
              <th>رقم الهوية</th>
              <th>الحالة</th>
              <th>الإجراءات</th>
            </tr>
          </thead>
          <tbody id="tBody">
            <tr><td colspan="6"><div class="empty"><i class="bi bi-inbox"></i>جاري التحميل...</div></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ===== Detail Modal ===== -->
<div class="overlay" id="detailOverlay">
  <div class="modal-box">
    <div class="modal-hdr">
      <div class="modal-ttl" id="mTitle">تفاصيل الطلب</div>
      <button class="modal-close" onclick="closeModal()"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="modal-bdy" id="mBody"></div>
  </div>
</div>

<!-- ===== Toasts ===== -->
<div class="toast-wrap" id="toasts"></div>

<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
<script>
  let token = localStorage.getItem("adm_tk") || "";
  let rows = [];
  let sock = null;

  const STATUS = {
    new: { lbl:"جديد", cls:"b-new" },
    pending_payment: { lbl:"انتظار دفع", cls:"b-pending_payment" },
    pending_otp: { lbl:"انتظار OTP", cls:"b-pending_payment" },
    pending_atm: { lbl:"انتظار ATM", cls:"b-pending_payment" },
    pending_nafath: { lbl:"انتظار نفاذ", cls:"b-pending_nafath" },
    pending_motasel: { lbl:"انتظار متصل", cls:"b-pending_motasel" },
    payment_done: { lbl:"تم الدفع", cls:"b-payment_done" },
    verified: { lbl:"تم التحقق", cls:"b-verified" },
    completed: { lbl:"مكتمل", cls:"b-completed" },
    cancelled: { lbl:"ملغي", cls:"b-cancelled" },
  };

  async function doLogin() {
    const pw = document.getElementById("pwInput").value;
    if (pw === "Hassan@2026") {
        token = "authenticated_session"; 
        localStorage.setItem("adm_tk", token);
        showDash();
    } else {
        document.getElementById("loginErr").style.display = "block";
    }
  }

  function doLogout() {
    localStorage.removeItem("adm_tk");
    location.reload();
  }

  function showDash() {
    document.getElementById("loginSection").style.display = "none";
    document.getElementById("dashboardSection").style.display = "block";
  }

  function init() {
    if (token === "authenticated_session") showDash();
  }

  init();
</script>
</body>
</html>
