<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Pegawai — WellnessApp</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Sora:wght@700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --teal: #0d9488;
    --teal-light: #ccfbf1;
    --teal-dark: #0f766e;
    --bg: #f0f9f8;
    --card: #ffffff;
    --border: #e2e8f0;
    --text: #0f172a;
    --muted: #64748b;
    --danger: #ef4444;
    --input-bg: #f8fafc;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    background-image:
      radial-gradient(ellipse 70% 50% at 10% 0%, rgba(13,148,136,0.08) 0%, transparent 60%),
      radial-gradient(ellipse 50% 40% at 90% 100%, rgba(20,184,166,0.06) 0%, transparent 50%);
    min-height: 100vh;
    color: var(--text);
  }

  /* ── SIDEBAR ── */
  .sidebar {
    position: fixed; top: 0; left: 0; bottom: 0;
    width: 240px;
    background: #fff;
    border-right: 1px solid var(--border);
    display: flex; flex-direction: column;
    z-index: 50;
    padding: 0;
  }
  .sidebar-logo {
    display: flex; align-items: center; gap: 10px;
    padding: 22px 20px 20px;
    border-bottom: 1px solid var(--border);
  }
  .logo-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--teal), #14b8a6);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(13,148,136,0.3);
    flex-shrink: 0;
  }
  .logo-text { font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 800; color: var(--text); }
  .logo-sub { font-size: 10px; color: var(--muted); font-weight: 500; letter-spacing: 0.5px; }

  .nav-section { padding: 18px 12px 6px; }
  .nav-label { font-size: 10px; font-weight: 700; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase; padding: 0 8px; margin-bottom: 6px; }
  .nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 12px; border-radius: 10px;
    font-size: 13px; font-weight: 500; color: var(--muted);
    cursor: pointer; transition: all 0.15s; margin-bottom: 2px;
    text-decoration: none;
  }
  .nav-item:hover { background: #f1f5f9; color: var(--text); }
  .nav-item.active { background: var(--teal-light); color: var(--teal-dark); font-weight: 600; }
  .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }

  .sidebar-footer {
    margin-top: auto; padding: 16px 12px;
    border-top: 1px solid var(--border);
  }
  .user-pill {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 12px; background: #f8fafc;
    cursor: pointer; transition: background 0.15s;
  }
  .user-pill:hover { background: #f1f5f9; }
  .avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: linear-gradient(135deg, var(--teal), #14b8a6);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
  }

  /* ── MAIN CONTENT ── */
  .main { margin-left: 240px; padding: 28px 36px; min-height: 100vh; }

  .topbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 28px;
  }
  .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); }
  .breadcrumb .sep { color: #cbd5e1; }
  .breadcrumb .current { color: var(--text); font-weight: 600; }
  .page-title { font-family: 'Sora', sans-serif; font-size: 22px; font-weight: 800; color: var(--text); margin-top: 2px; }

  .card {
    background: #fff; border-radius: 20px;
    border: 1px solid var(--border);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    padding: 28px; margin-bottom: 20px;
    transition: box-shadow 0.2s;
  }
  .card:hover { box-shadow: 0 4px 16px rgba(13,148,136,0.08); }
  .card-header {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 22px; padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
  }
  .card-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .card-title { font-size: 13px; font-weight: 700; color: var(--text); }
  .card-sub { font-size: 11px; color: var(--muted); margin-top: 1px; }

  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .col-span-2 { grid-column: span 2; }

  .field { display: flex; flex-direction: column; gap: 6px; }
  .field label { font-size: 12px; font-weight: 600; color: #374151; display: flex; align-items: center; gap: 4px; }
  .field label .req { color: var(--danger); }
  .field label .badge-opt {
    background: #f1f5f9; color: #94a3b8;
    font-size: 9px; font-weight: 700; letter-spacing: 0.5px;
    padding: 1px 6px; border-radius: 4px; text-transform: uppercase;
  }

  .inp {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--border);
    border-radius: 12px; background: var(--input-bg);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13.5px; color: var(--text);
    transition: all 0.2s; outline: none;
  }
  .inp::placeholder { color: #94a3b8; }
  .inp:focus { border-color: var(--teal); background: #fff; box-shadow: 0 0 0 4px rgba(13,148,136,0.1); }
  .inp.has-suffix { padding-right: 52px; }
  .inp.is-invalid { border-color: var(--danger); background: #fff5f5; }

  .inp-wrap { position: relative; }
  .inp-suffix { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #94a3b8; pointer-events: none; font-weight: 500; }
  .inp-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
  .inp.has-icon { padding-left: 40px; }

  select.inp {
    cursor: pointer; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center;
    padding-right: 36px;
  }
  textarea.inp { resize: none; }

  /* Unit Kerja dropdown custom */
  .unit-dropdown { position: relative; }
  .unit-dropdown-menu {
    position: absolute; top: calc(100% + 6px); left: 0; right: 0;
    background: #fff; border: 1.5px solid var(--border);
    border-radius: 14px; z-index: 100;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    max-height: 260px; overflow-y: auto;
    display: none;
  }
  .unit-dropdown-menu.open { display: block; }
  .unit-search {
    padding: 10px 12px; border-bottom: 1px solid #f1f5f9; position: sticky; top: 0; background: #fff;
  }
  .unit-search input {
    width: 100%; padding: 8px 12px;
    border: 1.5px solid var(--border); border-radius: 9px;
    font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12.5px;
    outline: none; background: var(--input-bg);
  }
  .unit-search input:focus { border-color: var(--teal); }
  .unit-option {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; cursor: pointer; font-size: 13px; color: var(--text);
    transition: background 0.12s;
  }
  .unit-option:hover { background: #f8fafc; }
  .unit-option.selected { background: var(--teal-light); color: var(--teal-dark); font-weight: 600; }
  .unit-option .unit-badge {
    font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 5px;
    background: #f1f5f9; color: #64748b; margin-left: auto; white-space: nowrap;
  }
  .unit-option.selected .unit-badge { background: rgba(13,148,136,0.15); color: var(--teal-dark); }
  .unit-empty { padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }

  .radio-cards { display: flex; gap: 10px; }
  .radio-card { flex: 1; position: relative; cursor: pointer; }
  .radio-card input { position: absolute; opacity: 0; width: 0; height: 0; }
  .radio-card-body {
    padding: 12px 16px; border-radius: 12px;
    border: 1.5px solid var(--border); background: var(--input-bg);
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; font-weight: 600; color: var(--muted);
    transition: all 0.15s; cursor: pointer;
  }
  .radio-card-body .rc-dot {
    width: 18px; height: 18px; border-radius: 50%;
    border: 2px solid #cbd5e1; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.15s;
  }
  .radio-card input:checked + .radio-card-body { border-color: var(--teal); background: var(--teal-light); color: var(--teal-dark); }
  .radio-card input:checked + .radio-card-body .rc-dot { border-color: var(--teal); background: var(--teal); }
  .radio-card input:checked + .radio-card-body .rc-dot::after { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff; }

  .gender-cards { display: flex; gap: 10px; }
  .gender-card { flex: 1; position: relative; cursor: pointer; }
  .gender-card input { position: absolute; opacity: 0; width: 0; height: 0; }
  .gender-card-body {
    padding: 14px 16px; border-radius: 12px;
    border: 1.5px solid var(--border); background: var(--input-bg);
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: var(--muted);
    transition: all 0.15s; cursor: pointer; text-align: center;
  }
  .gender-card input:checked + .gender-card-body {
    border-color: var(--teal); background: var(--teal-light); color: var(--teal-dark);
    box-shadow: 0 0 0 4px rgba(13,148,136,0.1);
  }

  .pwd-strength { display: flex; gap: 4px; margin-top: 8px; }
  .pwd-bar { height: 3px; flex: 1; border-radius: 99px; background: #e2e8f0; transition: background 0.3s; }
  .pwd-bar.weak { background: #ef4444; }
  .pwd-bar.ok { background: #f59e0b; }
  .pwd-bar.strong { background: var(--teal); }
  .pwd-hint { font-size: 11px; color: var(--muted); margin-top: 5px; }

  .action-bar {
    display: flex; align-items: center; justify-content: space-between;
    background: #fff; border: 1px solid var(--border);
    border-radius: 16px; padding: 16px 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    position: sticky; bottom: 28px;
  }
  .btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 20px; border-radius: 12px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px; font-weight: 600; cursor: pointer;
    border: none; transition: all 0.2s; text-decoration: none;
  }
  .btn-ghost { background: transparent; color: var(--muted); border: 1.5px solid var(--border); }
  .btn-ghost:hover { background: #f8fafc; color: var(--text); border-color: #94a3b8; }
  .btn-danger {
    background: #fef2f2; color: #ef4444;
    border: 1.5px solid #fecaca;
  }
  .btn-danger:hover { background: #fee2e2; }
  .btn-primary {
    background: linear-gradient(135deg, var(--teal) 0%, #14b8a6 100%);
    color: #fff; box-shadow: 0 4px 14px rgba(13,148,136,0.35);
  }
  .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(13,148,136,0.4); }
  .btn-primary:active { transform: translateY(0); }

  .helper { font-size: 11px; color: #94a3b8; margin-top: 4px; }
  .error-msg { font-size: 11px; color: var(--danger); margin-top: 4px; display: flex; align-items: center; gap: 4px; }

  #health-detail { display: none; }
  #health-detail.show { display: flex; flex-direction: column; gap: 6px; }

  .split-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

  .alert-error {
    background: #fef2f2; border: 1px solid #fecaca;
    border-radius: 14px; padding: 14px 18px;
    margin-bottom: 20px; display: flex; align-items: flex-start; gap: 12px;
  }
  .alert-error-icon { color: #ef4444; flex-shrink: 0; margin-top: 1px; }
  .alert-error-title { font-size: 13px; font-weight: 700; color: #991b1b; }
  .alert-error-list { margin-top: 6px; padding-left: 16px; }
  .alert-error-list li { font-size: 12px; color: #b91c1c; margin-bottom: 2px; }

  .alert-success {
    background: #f0fdf4; border: 1px solid #bbf7d0;
    border-radius: 14px; padding: 14px 18px;
    margin-bottom: 20px; display: flex; align-items: center; gap: 12px;
    font-size: 13px; font-weight: 600; color: #166534;
  }

  /* Password toggle section */
  .pwd-toggle-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 0;
  }
  .pwd-toggle-btn {
    font-size: 12px; font-weight: 600; color: var(--teal);
    cursor: pointer; background: none; border: none;
    padding: 4px 10px; border-radius: 8px;
    transition: background 0.15s; font-family: inherit;
  }
  .pwd-toggle-btn:hover { background: var(--teal-light); }
  #pwd-section { display: none; margin-top: 18px; }
  #pwd-section.show { display: block; }

  @keyframes fadeUp { from{opacity:0;transform:translateY(14px);} to{opacity:1;transform:translateY(0);} }
  .card { animation: fadeUp 0.4s ease both; }
  .card:nth-child(1) { animation-delay: 0.05s; }
  .card:nth-child(2) { animation-delay: 0.1s; }
  .card:nth-child(3) { animation-delay: 0.15s; }
  .card:nth-child(4) { animation-delay: 0.2s; }

  .req-note { font-size: 11px; color: #94a3b8; display: flex; align-items: center; gap: 4px; }
  .count-tag { display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; color: #64748b; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 999px; margin-left: 6px; }

  /* Edit badge on topbar */
  .edit-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #fffbeb; border: 1px solid #fde68a;
    color: #92400e; font-size: 11px; font-weight: 700;
    padding: 5px 12px; border-radius: 999px;
  }
</style>
</head>
<body>

<!-- ── SIDEBAR ── -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="#fff"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402C1 3.05 3.027 1 5.5 1c1.71 0 3.252.862 4.5 2.2C11.248 1.862 12.79 1 14.5 1 16.973 1 19 3.05 19 7.19c0 4.105-5.37 8.863-11 14.402z"/></svg>
    </div>
    <div>
      <div class="logo-text">WellnessApp</div>
      <div class="logo-sub">Mental Health Platform</div>
    </div>
  </div>

  <div class="nav-section">
    <div class="nav-label">Utama</div>
    <a class="nav-item" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
      Dashboard
    </a>
    <a class="nav-item active" href="{{ route('admin.employees.index') }}">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      Data Pegawai
      {{-- <span class="count-tag ml-auto">248</span> --}}
    </a>
    <a class="nav-item" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      Skrining
    </a>
    <a class="nav-item" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      Kasus Aktif
      {{-- <span class="count-tag ml-auto" style="background:#fef2f2;color:#ef4444;">12</span> --}}
    </a>
    <a class="nav-item" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
      Laporan
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Pengaturan</div>
    <a class="nav-item" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
      Pengaturan
    </a>
  </div>

  <div class="sidebar-footer">
    <div class="user-pill">
      <div class="avatar">A</div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:12px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Admin RSCM</div>
        <div style="font-size:10px;color:#94a3b8;">admin@wellness.id</div>
      </div>
      <svg width="14" height="14" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
    </div>
  </div>
</aside>

<!-- ── MAIN ── -->
<main class="main">

  <!-- Topbar -->
  <div class="topbar">
    <div>
      <div class="breadcrumb">
        <span>Dashboard</span>
        <span class="sep">›</span>
        <a href="{{ route('admin.employees.index') }}" style="color:var(--muted);text-decoration:none;">Data Pegawai</a>
        <span class="sep">›</span>
        <span class="current">Edit Pegawai</span>
      </div>
      <h1 class="page-title">Edit Data Pegawai</h1>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
      <div class="edit-badge">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Mode Edit
      </div>
      <div class="req-note">
        <span style="color:var(--danger);font-size:14px;">*</span> Wajib diisi
      </div>
    </div>
  </div>

  {{-- ── FLASH SUCCESS ── --}}
  @if (session('success'))
    <div class="alert-success">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      {{ session('success') }}
    </div>
  @endif

  {{-- ── VALIDATION ERROR BANNER ── --}}
  @if ($errors->any())
    <div class="alert-error">
      <svg class="alert-error-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
      <div>
        <div class="alert-error-title">Terdapat {{ $errors->count() }} kesalahan pada form:</div>
        <ul class="alert-error-list">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  <form id="emp-form"
        action="{{ route('admin.employees.update', $employee) }}"
        method="POST">
    @csrf
    @method('PUT')

    <!-- ── SECTION 1: INFORMASI DASAR ── -->
    <div class="card">
      <div class="card-header">
        <div class="card-icon" style="background:#f0fdf4;">
          <svg width="18" height="18" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
          <div class="card-title">Informasi Dasar</div>
          <div class="card-sub">Identitas dan posisi pegawai dalam organisasi</div>
        </div>
      </div>

      <div class="grid-2">

        <!-- Nama Lengkap -->
        <div class="field col-span-2">
          <label>Nama Lengkap <span class="req">*</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <input type="text" name="name"
                   class="inp has-icon @error('name') is-invalid @enderror"
                   placeholder="Masukkan nama lengkap pegawai"
                   value="{{ old('name', $employee->name) }}">
          </div>
          @error('name')
            <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span>
          @enderror
        </div>

        <!-- Email -->
        <div class="field">
          <label>Email <span class="req">*</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <input type="email" name="email"
                   class="inp has-icon @error('email') is-invalid @enderror"
                   placeholder="nama@rscm.co.id"
                   value="{{ old('email', $employee->email) }}">
          </div>
          @error('email') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
        </div>

        <!-- NIP -->
        <div class="field">
          <label>NIP <span class="req">*</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
            <input type="text" name="nip"
                   class="inp has-icon @error('nip') is-invalid @enderror"
                   placeholder="Nomor Induk Pegawai"
                   value="{{ old('nip', $employee->nip) }}">
          </div>
          @error('nip') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
        </div>

        <!-- Jabatan -->
        <div class="field">
          <label>Jabatan <span class="req">*</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <input type="text" name="jabatan"
                   class="inp has-icon @error('jabatan') is-invalid @enderror"
                   placeholder="Perawat / Dokter / Staf"
                   value="{{ old('jabatan', $employee->jabatan) }}">
          </div>
          @error('jabatan') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
        </div>

        <!-- Role -->
        <div class="field">
          <label>Role <span class="req">*</span></label>
          <select name="role_id" class="inp @error('role_id') is-invalid @enderror">
            <option value="">— Pilih Role —</option>
            @foreach ($roles as $role)
              <option value="{{ $role->id }}"
                {{ old('role_id', $employee->role_id) == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
            @endforeach
          </select>
          @error('role_id') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
        </div>

        <!-- Unit Kerja — diambil dari Room -->
        <div class="field">
          <label>Asal Ruangan / Unit Kerja <span class="req">*</span></label>

          {{-- Hidden input yang dikirim ke server --}}
          <input type="hidden" name="unit" id="unit-value"
                 value="{{ old('unit', $employee->unit) }}">

          <div class="unit-dropdown" id="unit-dropdown">
            {{-- Trigger button --}}
            <div class="inp-wrap">
              <svg class="inp-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              <input type="text" id="unit-display"
                     class="inp has-icon @error('unit') is-invalid @enderror"
                     placeholder="Pilih atau cari ruangan..."
                     value="{{ old('unit', $employee->unit) }}"
                     autocomplete="off"
                     readonly
                     onclick="openUnitDropdown()"
                     style="cursor:pointer;">
              <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);pointer-events:none;">
                <svg width="12" height="12" fill="none" stroke="#94a3b8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </span>
            </div>

            {{-- Dropdown panel --}}
            <div class="unit-dropdown-menu" id="unit-menu">
              <div class="unit-search">
                <input type="text" id="unit-search-inp"
                       placeholder="Cari nama atau kode ruangan..."
                       oninput="filterUnits(this.value)">
              </div>
              <div id="unit-list">
                @forelse ($rooms as $room)
                  <div class="unit-option {{ old('unit', $employee->unit) === $room->name ? 'selected' : '' }}"
                       data-name="{{ $room->name }}"
                       data-code="{{ $room->code }}"
                       onclick="selectUnit('{{ $room->name }}', '{{ $room->code }}')">
                    {{ $room->name }}
                    <span class="unit-badge">{{ $room->code }}</span>
                  </div>
                @empty
                  <div class="unit-empty">Belum ada data ruangan</div>
                @endforelse
              </div>
            </div>
          </div>

          @error('unit') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
        </div>

        <!-- No Telepon -->
        <div class="field">
          <label>No. Telepon <span class="badge-opt">opsional</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <input type="text" name="phone"
                   class="inp has-icon"
                   placeholder="08xxxxxxxxxx"
                   value="{{ old('phone', $employee->phone) }}">
          </div>
        </div>

      </div>
    </div>

    <!-- ── SECTION 2: DATA PERSONAL ── -->
    <div class="card">
      <div class="card-header">
        <div class="card-icon" style="background:#eff6ff;">
          <svg width="18" height="18" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <div class="card-title">Data Personal</div>
          <div class="card-sub">Informasi demografis dan latar belakang pegawai</div>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:18px;">

        <!-- Jenis Kelamin -->
        <div class="field">
          <label>Jenis Kelamin <span class="req">*</span></label>
          <div class="gender-cards">
            <label class="gender-card">
              <input type="radio" name="gender" value="L"
                     {{ old('gender', $employee->gender) === 'L' ? 'checked' : '' }}>
              <div class="gender-card-body">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="10" cy="10" r="7"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 5l5-5m0 0h-4m4 0v4"/></svg>
                Laki-laki
              </div>
            </label>
            <label class="gender-card">
              <input type="radio" name="gender" value="P"
                     {{ old('gender', $employee->gender) === 'P' ? 'checked' : '' }}>
              <div class="gender-card-body">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v6m-3-3h6"/></svg>
                Perempuan
              </div>
            </label>
          </div>
          @error('gender') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
        </div>

        <div class="grid-2">

          <!-- Usia -->
          <div class="field">
            <label>Usia <span class="req">*</span></label>
            <div class="inp-wrap">
              <input type="number" name="usia"
                     class="inp has-suffix @error('usia') is-invalid @enderror"
                     placeholder="Contoh: 30" min="18" max="70"
                     value="{{ old('usia', $employee->usia) }}">
              <span class="inp-suffix">tahun</span>
            </div>
            <span class="helper">Rentang usia 18 — 70 tahun</span>
            @error('usia') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
          </div>

          <!-- Status Pernikahan -->
          <div class="field">
            <label>Status Pernikahan <span class="req">*</span></label>
            <select name="status_pernikahan" class="inp @error('status_pernikahan') is-invalid @enderror">
              <option value="">— Pilih —</option>
              @foreach(['belum_menikah' => 'Belum Menikah', 'menikah' => 'Menikah', 'cerai_hidup' => 'Cerai Hidup', 'cerai_mati' => 'Cerai Mati'] as $val => $label)
                <option value="{{ $val }}"
                  {{ old('status_pernikahan', $employee->status_pernikahan) === $val ? 'selected' : '' }}>
                  {{ $label }}
                </option>
              @endforeach
            </select>
            @error('status_pernikahan') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
          </div>

          <!-- Pendidikan -->
          <div class="field">
            <label>Pendidikan Terakhir <span class="req">*</span></label>
            <select name="pendidikan" class="inp @error('pendidikan') is-invalid @enderror">
              <option value="">— Pilih —</option>
              @foreach(['SMA/SMK' => 'SMA / SMK', 'D1' => 'Diploma 1 (D1)', 'D2' => 'Diploma 2 (D2)', 'D3' => 'Diploma 3 (D3)', 'D4' => 'Diploma 4 (D4)', 'S1' => 'Sarjana (S1)', 'Profesi' => 'Profesi (Ners / Dokter / Apoteker)', 'S2' => 'Magister (S2)', 'S3' => 'Doktor (S3)'] as $val => $label)
                <option value="{{ $val }}"
                  {{ old('pendidikan', $employee->pendidikan) === $val ? 'selected' : '' }}>
                  {{ $label }}
                </option>
              @endforeach
            </select>
            @error('pendidikan') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
          </div>

          <!-- Lama Kerja -->
          <div class="field">
            <label>Lama Bekerja di RSCM <span class="req">*</span></label>
            <div class="split-inputs">
              <div class="inp-wrap">
                <input type="number" name="lama_kerja_tahun"
                       class="inp has-suffix @error('lama_kerja_tahun') is-invalid @enderror"
                       placeholder="0" min="0" max="50"
                       value="{{ old('lama_kerja_tahun', $employee->lama_kerja_tahun) }}">
                <span class="inp-suffix">tahun</span>
              </div>
              <div class="inp-wrap">
                <input type="number" name="lama_kerja_bulan"
                       class="inp has-suffix @error('lama_kerja_bulan') is-invalid @enderror"
                       placeholder="0" min="0" max="11"
                       value="{{ old('lama_kerja_bulan', $employee->lama_kerja_bulan) }}">
                <span class="inp-suffix">bulan</span>
              </div>
            </div>
            @error('lama_kerja_tahun') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
          </div>

        </div>
      </div>
    </div>

    <!-- ── SECTION 3: RIWAYAT KESEHATAN ── -->
    <div class="card">
      <div class="card-header">
        <div class="card-icon" style="background:#fff7ed;">
          <svg width="18" height="18" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <div>
          <div class="card-title">Riwayat Kesehatan</div>
          <div class="card-sub">Informasi kondisi medis yang relevan</div>
        </div>
      </div>

      <div class="field" style="margin-bottom:16px;">
        <label>Apakah memiliki masalah kesehatan? <span class="req">*</span></label>
        <div class="radio-cards">
          <label class="radio-card">
            <input type="radio" name="has_health_issue" value="1"
                   onchange="toggleHealth(1)"
                   {{ old('has_health_issue', $employee->has_health_issue) == '1' ? 'checked' : '' }}>
            <div class="radio-card-body">
              <div class="rc-dot"></div>
              Ya, ada kondisi tertentu
            </div>
          </label>
          <label class="radio-card">
            <input type="radio" name="has_health_issue" value="0"
                   onchange="toggleHealth(0)"
                   {{ old('has_health_issue', $employee->has_health_issue) == '0' ? 'checked' : '' }}>
            <div class="radio-card-body">
              <div class="rc-dot"></div>
              Tidak ada masalah kesehatan
            </div>
          </label>
        </div>
        @error('has_health_issue') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
      </div>

      <div id="health-detail"
           class="{{ old('has_health_issue', $employee->has_health_issue) == '1' ? 'show' : '' }}">
        <div class="field">
          <label>Sebutkan masalah kesehatan yang dimiliki</label>
          <textarea name="health_issue_detail"
                    class="inp @error('health_issue_detail') is-invalid @enderror"
                    rows="3"
                    placeholder="Contoh: Hipertensi, Diabetes Melitus Tipe 2, Asma…">{{ old('health_issue_detail', $employee->health_issue_detail) }}</textarea>
          <span class="helper">Pisahkan dengan koma jika lebih dari satu kondisi</span>
          @error('health_issue_detail') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
        </div>
      </div>
    </div>

    <!-- ── SECTION 4: PASSWORD ── -->
    <div class="card">
      <div class="card-header">
        <div class="card-icon" style="background:#faf5ff;">
          <svg width="18" height="18" fill="none" stroke="#9333ea" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <div style="flex:1;">
          <div class="pwd-toggle-header">
            <div>
              <div class="card-title">Password Akun</div>
              <div class="card-sub">Kosongkan jika tidak ingin mengubah password</div>
            </div>
            <button type="button" class="pwd-toggle-btn" onclick="togglePwdSection()">
              Ganti Password
            </button>
          </div>
        </div>
      </div>

      <div id="pwd-section">
        <div class="grid-2">
          <div class="field">
            <label>Password Baru <span class="req">*</span></label>
            <div class="inp-wrap">
              <svg class="inp-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
              <input type="password" name="password" id="pwdInput"
                     class="inp has-icon @error('password') is-invalid @enderror"
                     placeholder="Min. 8 karakter"
                     oninput="checkStrength(this.value)">
            </div>
            <div class="pwd-strength">
              <div class="pwd-bar" id="bar1"></div>
              <div class="pwd-bar" id="bar2"></div>
              <div class="pwd-bar" id="bar3"></div>
              <div class="pwd-bar" id="bar4"></div>
            </div>
            <span class="pwd-hint" id="pwdHint">Gunakan kombinasi huruf, angka, dan simbol</span>
            @error('password') <span class="error-msg"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</span> @enderror
          </div>

          <div class="field">
            <label>Konfirmasi Password Baru <span class="req">*</span></label>
            <div class="inp-wrap">
              <svg class="inp-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <input type="password" name="password_confirmation"
                     class="inp has-icon"
                     placeholder="Ulangi password baru">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ACTION BAR ── -->
    <div class="action-bar">
      <div style="display:flex;align-items:center;gap:10px;">
        <a href="{{ route('admin.employees.index') }}" class="btn btn-ghost">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Batal
        </a>
        <span style="font-size:12px;color:#94a3b8;">atau tekan Esc untuk keluar</span>
      </div>
      <div style="display:flex;align-items:center;gap:10px;">
        <button type="submit" class="btn btn-primary">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Simpan Perubahan
        </button>
      </div>
    </div>

  </form>
</main>

<script>
  // ── Unit kerja dropdown ──
  function openUnitDropdown() {
    document.getElementById('unit-menu').classList.toggle('open');
    setTimeout(() => document.getElementById('unit-search-inp').focus(), 50);
  }

  function selectUnit(name, code) {
    document.getElementById('unit-value').value   = name;
    document.getElementById('unit-display').value = name;
    document.getElementById('unit-menu').classList.remove('open');

    // Update selected state visually
    document.querySelectorAll('.unit-option').forEach(el => {
      el.classList.toggle('selected', el.dataset.name === name);
    });
  }

  function filterUnits(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.unit-option').forEach(el => {
      const match = el.dataset.name.toLowerCase().includes(q)
                 || el.dataset.code.toLowerCase().includes(q);
      el.style.display = match ? '' : 'none';
    });
  }

  // Tutup dropdown saat klik di luar
  document.addEventListener('click', function(e) {
    const dd = document.getElementById('unit-dropdown');
    if (!dd.contains(e.target)) {
      document.getElementById('unit-menu').classList.remove('open');
    }
  });

  // ── Toggle health detail section ──
  function toggleHealth(val) {
    const el = document.getElementById('health-detail');
    if (val === 1) {
      el.classList.add('show');
    } else {
      el.classList.remove('show');
      el.querySelector('textarea').value = '';
    }
  }

  // ── Toggle password section ──
  function togglePwdSection() {
    const sec = document.getElementById('pwd-section');
    const btn = document.querySelector('.pwd-toggle-btn');
    const isOpen = sec.classList.toggle('show');
    btn.textContent = isOpen ? 'Batal Ganti' : 'Ganti Password';
    if (!isOpen) {
      document.getElementById('pwdInput').value = '';
      document.querySelector('[name=password_confirmation]').value = '';
      ['bar1','bar2','bar3','bar4'].forEach(id => {
        document.getElementById(id).className = 'pwd-bar';
      });
      document.getElementById('pwdHint').textContent = 'Gunakan kombinasi huruf, angka, dan simbol';
      document.getElementById('pwdHint').style.color = '';
    }
  }

  // ── Password strength meter ──
  function checkStrength(pwd) {
    const bars = ['bar1','bar2','bar3','bar4'].map(id => document.getElementById(id));
    const hint = document.getElementById('pwdHint');
    bars.forEach(b => { b.className = 'pwd-bar'; });

    if (!pwd) {
      hint.textContent = 'Gunakan kombinasi huruf, angka, dan simbol';
      hint.style.color = '';
      return;
    }

    let score = 0;
    if (pwd.length >= 8)          score++;
    if (/[A-Z]/.test(pwd))        score++;
    if (/[0-9]/.test(pwd))        score++;
    if (/[^A-Za-z0-9]/.test(pwd)) score++;

    const cls    = score <= 1 ? 'weak' : score === 2 ? 'ok' : 'strong';
    const labels = {
      weak:   'Lemah — tambahkan huruf kapital, angka, atau simbol',
      ok:     'Sedang — coba tambahkan simbol khusus',
      strong: 'Kuat — password ini sudah aman ✓'
    };
    const colors = { weak: '#ef4444', ok: '#f59e0b', strong: 'var(--teal)' };

    for (let i = 0; i < score; i++) bars[i].classList.add(cls);
    hint.textContent = labels[cls];
    hint.style.color = colors[cls];
  }

  // ── Tekan Esc untuk kembali ──
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      window.location.href = '{{ route('admin.employees.index') }}';
    }
  });
</script>
</body>
</html>