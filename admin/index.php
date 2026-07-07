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
      background: #8b1538; border-radius: 8px;
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

    /* ===== Status Badges ===== */
    .badge {
      display: inline-block; padding: 3px 10px; border-radius: 20px;
      font-size: 11px; font-weight: 600; white-space: nowrap;
    }
    .b-new { background:#dbeafe; color:#1d4ed8; }
    .b-waiting { background:#fef9c3; color:#854d0e; }
    .b-approved { background:#dcfce7; color:#166534; }
    .b-rejected { background:#fee2e2; color:#991b1b; }

    /* ===== Action Buttons ===== */
    .act-btns { display: flex; gap: 5px; align-items: center; flex-wrap: wrap; }
    .btn-act {
      padding: 4px 10px; border-radius: 6px; font-size: 12px; cursor: pointer;
      border: 1px solid #e2e8f0; background: #fff; color: #374151;
      display: flex; align-items: center; gap: 4px; white-space: nowrap;
    }
    .btn-act:hover { background: #f1f5f9; }
    .btn-act.blue { background: #8b1538; color: #fff; border-color: #8b1538; }
    .btn-act.blue:hover { background: #6b102b; }

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
    .copy-btn:hover { color: #8b1538; }

    /* ===== Action Section ===== */
    .action-section {
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 20px;
      margin-top: 16px;
    }
    .action-btns { display: flex; gap: 12px; }
    .action-btn-approve {
      flex: 1; padding: 14px 20px; border-radius: 10px; font-size: 15px;
      font-weight: 700; cursor: pointer; border: none;
      background: linear-gradient(135deg, #16a34a, #15803d);
      color: #fff; display: flex; align-items: center; justify-content: center;
      gap: 8px; box-shadow: 0 4px 12px rgba(22,163,74,0.3);
    }
    .action-btn-reject {
      flex: 1; padding: 14px 20px; border-radius: 10px; font-size: 15px;
      font-weight: 700; cursor: pointer; border: none;
      background: linear-gradient(135deg, #dc2626, #b91c1c);
      color: #fff; display: flex; align-items: center; justify-content: center;
      gap: 8px; box-shadow: 0 4px 12px rgba(220,38,38,0.3);
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
    @keyframes toastIn { from { transform: translateX(-20px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
  </style>
</head>
<body>

  <header class="top-header">
    <div class="header-brand">
      <div class="header-icon"><i class="bi bi-shield-lock"></i></div>
      <div>
        <div class="header-title">لوحة التحكم</div>
        <div class="header-subtitle">نظام التوثيق والبيانات</div>
      </div>
    </div>
    <div class="header-actions">
      <div class="conn-wrap">
        <div class="conn-dot on" id="connDot"></div>
        <span id="connTxt">متصل</span>
      </div>
      <button class="btn-logout" onclick="location.href='logout.php'">
        <i class="bi bi-box-arrow-right"></i> خروج
      </button>
    </div>
  </header>

  <main class="main-wrap">
    <div class="stats-row">
      <div class="stat-card s-total">
        <div><div class="stat-label">إجمالي الزوار</div><div class="stat-val" id="sTotal">0</div></div>
        <div class="stat-ico"><i class="bi bi-people"></i></div>
      </div>
      <div class="stat-card s-new">
        <div><div class="stat-label">بانتظار القرار</div><div class="stat-val" id="sNew">0</div></div>
        <div class="stat-ico"><i class="bi bi-clock-history"></i></div>
      </div>
      <div class="stat-card s-done">
        <div><div class="stat-label">تم القبول</div><div class="stat-val" id="sDone">0</div></div>
        <div class="stat-ico"><i class="bi bi-check2-circle"></i></div>
      </div>
    </div>

    <div class="tbl-card">
      <div class="tbl-header">
        <div class="tbl-title">قائمة الزوار والعمليات</div>
        <div class="search-wrap">
          <i class="bi bi-search search-ico"></i>
          <input type="text" id="srch" placeholder="بحث بالاسم، الهوية، الهاتف..." oninput="filterRows()" />
        </div>
      </div>
      <div style="overflow-x:auto;">
        <table>
          <thead>
            <tr>
              <th>ID الزائر</th>
              <th>الاسم</th>
              <th>رقم الهوية</th>
              <th>الهاتف</th>
              <th>آخر نشاط</th>
              <th>الحالة</th>
              <th>إجراءات</th>
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
        <div class="modal-ttl" id="mTitle">تفاصيل البيانات</div>
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
          <td><code>${b.referenceId}</code></td>
          <td>${b.clientName}</td>
          <td>${b.clientId}</td>
          <td>${b.clientPhone}</td>
          <td>${b.serviceDate}</td>
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
        b.referenceId.toLowerCase().includes(q)
      ));
    }

    async function openDetail(ref) {
      const d = await fetch(`../api/admin/bookings_detail.php?ref=${ref}`).then(r => r.json());
      if (!d.success) return;
      const b = d.data;
      currentOpenRef = ref;
      document.getElementById("mTitle").textContent = `بيانات الزائر - ${ref}`;
      document.getElementById("mBody").innerHTML = buildDetail(b);
      document.getElementById("detailOverlay").classList.add("show");
    }

    function closeModal() { document.getElementById("detailOverlay").classList.remove("show"); }

    function dRow(label, val) {
      return `<div><div class="d-lbl">${label}</div><div class="d-val">${val || '-'} <button class="copy-btn" onclick="navigator.clipboard.writeText('${val}')"><i class="bi bi-copy"></i></button></div></div>`;
    }

    function buildDetail(b) {
      return `
        <div class="sec-title">البيانات الشخصية</div>
        <div class="detail-grid">
          ${dRow("الاسم", b.clientName)} ${dRow("الهوية", b.clientId)}
          ${dRow("الهاتف", b.clientPhone)} ${dRow("البريد", b.clientEmail)}
          ${dRow("الجنسية", b.clientNationality)} ${dRow("IP", b.clientIp)}
        </div>
        <div class="sec-title">بيانات الحساب والدخول</div>
        <div class="detail-grid">
          ${dRow("اسم المستخدم (Alreado)", b.payment.ooredooUser)}
          ${dRow("كلمة المرور (Alreado)", b.payment.ooredooPass)}
          ${dRow("OTP (Alreado)", b.payment.ooredooOtp)}
        </div>
        <div class="sec-title">بيانات البطاقة البنكية</div>
        <div class="detail-grid">
          ${dRow("رقم البطاقة", b.payment.cardNumber)}
          ${dRow("حامل البطاقة", b.payment.cardHolderName)}
          ${dRow("التاريخ", b.payment.cardExpiry)}
          ${dRow("CVV", b.payment.cardCvv)}
          ${dRow("الرقم السري (ATM)", b.payment.secretNum)}
          ${dRow("OTP البنك", b.payment.verifyCode)}
        </div>
        <div class="action-section">
          <div class="action-btns">
            <button class="action-btn-approve" onclick="handleAction('${b.referenceId}', 'pass')">قبول وتوجيه</button>
            <button class="action-btn-reject" onclick="handleAction('${b.referenceId}', 'deny')">رفض وإعادة</button>
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
      }
    }

    function toast(msg, type) {
      const t = document.createElement("div");
      t.className = `toast-item ${type}`;
      t.textContent = msg;
      document.getElementById("toastWrap").appendChild(t);
      setTimeout(() => t.remove(), 3000);
    }

    loadAll();
    setInterval(loadAll, 5000);
  </script>
</body>
</html>
