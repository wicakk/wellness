<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WellnessApp — Masuk / Daftar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&family=Sora:wght@700;800&display=swap" rel="stylesheet">
<style>
  *{box-sizing:border-box;margin:0;padding:0;}
  :root{
    --teal:#10b981;--teal-dark:#059669;--teal-deep:#064e3b;
    --teal-light:#d1fae5;--bg:#f0faf4;
  }
  body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:var(--bg);
    background-image:
      radial-gradient(ellipse 80% 60% at 20% 10%,rgba(52,211,153,.18) 0%,transparent 60%),
      radial-gradient(ellipse 60% 50% at 80% 80%,rgba(16,185,129,.12) 0%,transparent 55%),
      radial-gradient(ellipse 40% 40% at 60% 20%,rgba(110,231,183,.15) 0%,transparent 50%);
    min-height:100vh;display:flex;align-items:center;justify-content:center;padding:16px;
  }

  .wrap{
    width:100%;max-width:1060px;
    display:flex;border-radius:28px;overflow:hidden;
    box-shadow:0 24px 80px rgba(0,0,0,.14);
    min-height:620px;
  }

  /* ── Panel Kiri ── */
  .panel-left{
    width:38%;flex-shrink:0;
    background:linear-gradient(160deg,#064e3b 0%,#065f46 40%,#047857 100%);
    display:flex;flex-direction:column;justify-content:space-between;
    padding:36px 32px;
  }
  .logo{display:flex;align-items:center;gap:12px;}
  .logo-icon{
    width:42px;height:42px;border-radius:14px;
    background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.3);
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
  }
  .logo-name{color:#fff;font-family:'Sora',sans-serif;font-size:17px;font-weight:800;line-height:1;}
  .logo-sub{color:#6ee7b7;font-size:10px;margin-top:3px;font-weight:500;}
  .panel-headline{
    font-family:'Playfair Display',serif;
    color:#fff;font-size:36px;line-height:1.2;margin-bottom:12px;
  }
  .panel-desc{color:#a7f3d0;font-size:13px;line-height:1.7;}

  .float-card{
    background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.22);
    backdrop-filter:blur(12px);border-radius:18px;padding:20px;
  }
  .fc-label{color:#6ee7b7;font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;margin-bottom:14px;}
  .bar-row{margin-bottom:10px;}
  .bar-meta{display:flex;justify-content:space-between;font-size:11px;color:rgba(255,255,255,.75);margin-bottom:5px;}
  .bar-track{height:5px;border-radius:99px;background:rgba(255,255,255,.1);}
  .bar-fill{height:100%;border-radius:99px;}

  .stats{display:flex;gap:10px;}
  .stat{
    flex:1;text-align:center;padding:12px 6px;
    background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);
    border-radius:14px;backdrop-filter:blur(8px);
  }
  .stat-num{color:#fff;font-weight:800;font-size:20px;}
  .stat-lbl{color:#6ee7b7;font-size:10px;margin-top:2px;font-weight:500;}

  /* ── Panel Kanan ── */
  .panel-right{
    flex:1;
    background:rgba(255,255,255,.94);
    backdrop-filter:blur(24px);
    display:flex;flex-direction:column;
    overflow:hidden;position:relative;
  }

  /* Tab nav */
  .tab-nav{
    display:flex;border-bottom:1.5px solid #e5e7eb;
    background:#fff;flex-shrink:0;
  }
  .tab-btn{
    flex:1;padding:18px 12px;
    font-size:13px;font-weight:600;
    cursor:pointer;border:none;background:transparent;
    color:#9ca3af;position:relative;transition:color .2s;
    display:flex;align-items:center;justify-content:center;gap:8px;
  }
  .tab-btn.active{color:#059669;}
  .tab-btn::after{
    content:'';position:absolute;bottom:-1.5px;left:0;right:0;height:2.5px;
    background:linear-gradient(90deg,#059669,#10b981);border-radius:99px 99px 0 0;
    opacity:0;transition:opacity .2s;
  }
  .tab-btn.active::after{opacity:1;}

  /* Panels */
  .panel-body{flex:1;overflow-y:auto;padding:28px 36px 28px;}

  .view{display:none;}
  .view.show{display:block;}

  /* Login form */
  .welcome-head{margin-bottom:28px;}
  .welcome-head h1{font-size:28px;font-weight:800;color:#0f172a;font-family:'Sora',sans-serif;}
  .welcome-head p{color:#64748b;font-size:13px;margin-top:4px;}

  .field{margin-bottom:16px;}
  .field label{display:block;font-size:11px;font-weight:700;color:#374151;letter-spacing:.8px;text-transform:uppercase;margin-bottom:7px;}
  .field label .req{color:#ef4444;}
  .field label .opt{background:#f1f5f9;color:#94a3b8;font-size:9px;font-weight:700;padding:2px 6px;border-radius:4px;letter-spacing:.5px;text-transform:uppercase;margin-left:6px;}

  .inp-wrap{position:relative;}
  .inp-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none;}
  .inp{
    width:100%;padding:12px 14px;
    border:1.5px solid #e5e7eb;border-radius:12px;
    background:#fafafa;font-family:'Plus Jakarta Sans',sans-serif;
    font-size:13.5px;color:#0f172a;outline:none;
    transition:all .22s;
  }
  .inp.has-icon{padding-left:42px;}
  .inp.has-suffix{padding-right:52px;}
  .inp-suffix{position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:11px;color:#94a3b8;pointer-events:none;font-weight:500;}
  .inp::placeholder{color:#9ca3af;}
  .inp:focus{border-color:#10b981;background:#fff;box-shadow:0 0 0 4px rgba(16,185,129,.1);}
  .inp.is-invalid{border-color:#ef4444;background:#fff8f8;}
  .inp.is-invalid:focus{box-shadow:0 0 0 4px rgba(239,68,68,.1);}
  select.inp{
    cursor:pointer;appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat:no-repeat;background-position:right 14px center;padding-right:36px;
  }
  textarea.inp{resize:none;}

  .error-msg{font-size:11px;color:#ef4444;margin-top:4px;display:flex;align-items:center;gap:4px;}

  .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
  .col-2{grid-column:span 2;}
  .split{display:grid;grid-template-columns:1fr 1fr;gap:10px;}

  /* Radio cards */
  .radio-group{display:flex;gap:10px;}
  .rc{flex:1;position:relative;cursor:pointer;}
  .rc input{position:absolute;opacity:0;width:0;height:0;}
  .rc-body{
    padding:11px 14px;border-radius:12px;border:1.5px solid #e5e7eb;
    background:#fafafa;display:flex;align-items:center;gap:10px;
    font-size:13px;font-weight:600;color:#64748b;transition:all .15s;cursor:pointer;
  }
  .rc-dot{
    width:16px;height:16px;border-radius:50%;border:2px solid #cbd5e1;
    flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:all .15s;
  }
  .rc input:checked + .rc-body{border-color:#10b981;background:#f0fdf4;color:#065f46;}
  .rc input:checked + .rc-body .rc-dot{border-color:#10b981;background:#10b981;}
  .rc input:checked + .rc-body .rc-dot::after{content:'';width:5px;height:5px;border-radius:50%;background:#fff;}

  /* Gender cards */
  .gender-group{display:flex;gap:10px;}
  .gc{flex:1;position:relative;cursor:pointer;}
  .gc input{position:absolute;opacity:0;width:0;height:0;}
  .gc-body{
    padding:14px 12px;border-radius:12px;border:1.5px solid #e5e7eb;
    background:#fafafa;display:flex;flex-direction:column;align-items:center;gap:6px;
    font-size:12px;font-weight:600;color:#64748b;transition:all .15s;cursor:pointer;
  }
  .gc input:checked + .gc-body{border-color:#10b981;background:#f0fdf4;color:#065f46;box-shadow:0 0 0 4px rgba(16,185,129,.1);}

  /* Remember row */
  .remember-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
  .chk-label{display:flex;align-items:center;gap:10px;cursor:pointer;font-size:13px;color:#64748b;}
  .chk-wrap{position:relative;width:16px;height:16px;flex-shrink:0;}
  .chk-wrap input{position:absolute;opacity:0;width:0;height:0;}
  .chk-box{
    width:16px;height:16px;border:2px solid #d1d5db;border-radius:4px;
    background:#fff;transition:all .15s;display:flex;align-items:center;justify-content:center;
  }
  .chk-wrap input:checked ~ .chk-box{background:#10b981;border-color:#10b981;}
  .chk-wrap input:checked ~ .chk-box::after{
    content:'';display:block;width:9px;height:6px;
    border-left:2px solid #fff;border-bottom:2px solid #fff;
    transform:rotate(-45deg) translate(1px,-1px);
  }
  .forgot{font-size:13px;color:#10b981;font-weight:600;text-decoration:none;}
  .forgot:hover{color:#065f46;}

  /* Btn */
  .btn-primary{
    width:100%;padding:14px;border:none;border-radius:14px;cursor:pointer;
    background:linear-gradient(135deg,#059669 0%,#10b981 60%,#34d399 100%);
    color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;
    display:flex;align-items:center;justify-content:center;gap:8px;
    box-shadow:0 4px 20px rgba(16,185,129,.35);transition:all .3s;
  }
  .btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 28px rgba(16,185,129,.45);}
  .btn-primary:active{transform:translateY(0);}

  /* Demo accounts */
  .divider{display:flex;align-items:center;gap:12px;margin:22px 0 16px;}
  .divider-line{flex:1;height:1px;background:linear-gradient(to right,transparent,#d1d5db,transparent);}
  .divider span{font-size:11px;color:#9ca3af;white-space:nowrap;font-weight:500;}

  .demo-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
  .demo-card{
    display:flex;align-items:center;gap:10px;padding:12px;
    border:1.5px solid #e5e7eb;border-radius:14px;background:#fff;
    cursor:pointer;transition:all .18s;text-align:left;
    font-family:'Plus Jakarta Sans',sans-serif;
  }
  .demo-card:hover{border-color:#10b981;background:#f0fdf8;transform:translateY(-2px);box-shadow:0 4px 16px rgba(16,185,129,.12);}
  .demo-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .demo-name{font-size:13px;font-weight:700;color:#0f172a;line-height:1;}
  .demo-email{font-size:11px;color:#94a3b8;margin-top:2px;}

  /* Alert */
  .alert{padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:13px;}
  .alert-danger{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;}
  .alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;}
  .alert ul{margin:6px 0 0 16px;}
  .alert li{margin-bottom:2px;}

  /* Section card for register */
  .sec{
    border:1px solid #f1f5f9;border-radius:16px;padding:20px;
    margin-bottom:16px;background:#fff;
  }
  .sec-hdr{display:flex;align-items:center;gap:10px;margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;}
  .sec-icon{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .sec-title{font-size:13px;font-weight:700;color:#0f172a;}
  .sec-sub{font-size:11px;color:#64748b;margin-top:1px;}

  /* password strength */
  .pwd-bars{display:flex;gap:4px;margin-top:8px;}
  .pwd-bar{height:3px;flex:1;border-radius:99px;background:#e5e7eb;transition:background .3s;}
  .pwd-bar.weak{background:#ef4444;}
  .pwd-bar.ok{background:#f59e0b;}
  .pwd-bar.strong{background:#10b981;}
  .pwd-hint{font-size:11px;color:#94a3b8;margin-top:5px;}

  /* health toggle */
  #reg-health-detail{display:none;}
  #reg-health-detail.show{display:block;margin-top:14px;}

  /* scroll inside panel */
  .panel-body::-webkit-scrollbar{width:4px;}
  .panel-body::-webkit-scrollbar-track{background:transparent;}
  .panel-body::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:99px;}

  /* submit area reg */
  .reg-actions{display:flex;gap:12px;margin-top:8px;}
  .btn-outline{
    flex:1;padding:13px;border:1.5px solid #e5e7eb;border-radius:14px;cursor:pointer;
    background:#fff;color:#64748b;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;
    transition:all .2s;
  }
  .btn-outline:hover{border-color:#94a3b8;color:#374151;background:#f8fafc;}

  .helper{font-size:11px;color:#94a3b8;margin-top:4px;}

  @keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:translateY(0);}}
  .view.show .sec{animation:fadeUp .35s ease both;}
  .view.show .sec:nth-child(2){animation-delay:.06s;}
  .view.show .sec:nth-child(3){animation-delay:.12s;}
  .view.show .sec:nth-child(4){animation-delay:.18s;}
  .view.show .welcome-head{animation:fadeUp .4s ease both;}

  @media(max-width:700px){
    .panel-left{display:none;}
    .grid-2,.demo-grid{grid-template-columns:1fr;}
    .col-2{grid-column:span 1;}
    .panel-body{padding:20px;}
  }
</style>
</head>
<body>

<div class="wrap">

  <!-- Panel Kiri -->
  <div class="panel-left">
    <div class="logo">
      <div class="logo-icon">
        <svg width="20" height="20" fill="#fff" viewBox="0 0 24 24"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402C1 3.05 3.027 1 5.5 1c1.71 0 3.252.862 4.5 2.2C11.248 1.862 12.79 1 14.5 1 16.973 1 19 3.05 19 7.19c0 4.105-5.37 8.863-11 14.402z"/></svg>
      </div>
      <div>
        <div class="logo-name">WellnessApp</div>
        <div class="logo-sub">Mental Health Platform</div>
      </div>
    </div>

    <div>
      <p class="panel-headline">Monitoring<br>Kesehatan<br>Mental Pegawai</p>
      <p class="panel-desc">Skrining mandiri, pemantauan risiko, dan tindak lanjut berbasis data untuk organisasi yang peduli.</p>
    </div>

    <div class="float-card">
      <div class="fc-label">Skrining Bulan Ini</div>
      <div class="bar-row">
        <div class="bar-meta"><span>Risiko Rendah</span><span style="color:#34d399;font-weight:700;">60%</span></div>
        <div class="bar-track"><div class="bar-fill" style="width:60%;background:#34d399;"></div></div>
      </div>
      <div class="bar-row">
        <div class="bar-meta"><span>Risiko Sedang</span><span style="color:#fbbf24;font-weight:700;">32%</span></div>
        <div class="bar-track"><div class="bar-fill" style="width:32%;background:#fbbf24;"></div></div>
      </div>
      <div class="bar-row" style="margin-bottom:0;">
        <div class="bar-meta"><span>Risiko Tinggi</span><span style="color:#f87171;font-weight:700;">8%</span></div>
        <div class="bar-track"><div class="bar-fill" style="width:8%;background:#f87171;"></div></div>
      </div>
    </div>

    <div class="stats">
      <div class="stat"><div class="stat-num">4</div><div class="stat-lbl">Role</div></div>
      <div class="stat"><div class="stat-num">10+</div><div class="stat-lbl">Fitur</div></div>
      <div class="stat"><div class="stat-num">100%</div><div class="stat-lbl">Aman</div></div>
    </div>
  </div>

  <!-- Panel Kanan -->
  <div class="panel-right">

    <!-- Tab Nav -->
    <div class="tab-nav">
      <button type="button" class="tab-btn {{ request()->routeIs('login') ? 'active' : '' }}" id="tab-login" onclick="switchTab('login')">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
        Masuk
      </button>
      <button type="button" class="tab-btn {{ request()->routeIs('register') ? 'active' : '' }}" id="tab-register" onclick="switchTab('register')">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        Daftar Akun
      </button>
    </div>

    <!-- ── LOGIN VIEW ── -->
    <div class="panel-body view {{ request()->routeIs('login') || !request()->routeIs('register') ? 'show' : '' }}" id="view-login">

      <div class="welcome-head">
        <h1>Selamat Datang</h1>
        <p>Masuk dengan akun yang telah terdaftar</p>
      </div>

      {{-- Session Error --}}
      @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
          <label>Email</label>
          <div class="inp-wrap">
            <svg class="inp-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <input type="email" name="email" value="{{ old('email') }}"
              class="inp has-icon @error('email') is-invalid @enderror"
              placeholder="nama@instansi.id" autofocus autocomplete="username">
          </div>
          @error('email')
            <div class="error-msg">
              <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="field">
          <label>Password</label>
          <div class="inp-wrap">
            <svg class="inp-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <input type="password" name="password" id="loginPwd"
              class="inp has-icon @error('password') is-invalid @enderror"
              placeholder="••••••••" autocomplete="current-password" style="padding-right:44px;">
            <button type="button" onclick="togglePwd('loginPwd','eyeLogin')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:4px;">
              <svg id="eyeLogin" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
          </div>
          @error('password')
            <div class="error-msg">
              <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="remember-row">
          <label class="chk-label">
            <div class="chk-wrap">
              <input type="checkbox" name="remember">
              <div class="chk-box"></div>
            </div>
            Ingat saya
          </label>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot">Lupa password?</a>
          @endif
        </div>

        <button type="submit" class="btn-primary">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
          Masuk ke Dashboard
        </button>
      </form>

      <div class="divider">
        <div class="divider-line"></div>
        <span>Akun Demo — klik untuk langsung masuk</span>
        <div class="divider-line"></div>
      </div>

      <div class="demo-grid">
        <button type="button" class="demo-card" onclick="fillDemo('admin@wellness.id')">
          <div class="demo-icon" style="background:#eff6ff;color:#3b82f6;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          </div>
          <div><div class="demo-name">Admin</div><div class="demo-email">admin@wellness.id</div></div>
        </button>
        <button type="button" class="demo-card" onclick="fillDemo('wellness@wellness.id')">
          <div class="demo-icon" style="background:#f0fdf4;color:#16a34a;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
          </div>
          <div><div class="demo-name">Wellness</div><div class="demo-email">wellness@wellness.id</div></div>
        </button>
        <button type="button" class="demo-card" onclick="fillDemo('psikolog@wellness.id')">
          <div class="demo-icon" style="background:#faf5ff;color:#9333ea;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          </div>
          <div><div class="demo-name">Psikolog</div><div class="demo-email">psikolog@wellness.id</div></div>
        </button>
        <button type="button" class="demo-card" onclick="fillDemo('pegawai@wellness.id')">
          <div class="demo-icon" style="background:#fffbeb;color:#d97706;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </div>
          <div><div class="demo-name">Pegawai</div><div class="demo-email">pegawai@wellness.id</div></div>
        </button>
      </div>

    </div><!-- /view-login -->

    <!-- ── REGISTER VIEW ── -->
    <div class="panel-body view {{ request()->routeIs('register') ? 'show' : '' }}" id="view-register">

      <div class="welcome-head">
        <h1>Daftar Akun Baru</h1>
        <p>Isi data lengkap untuk membuat akun pegawai</p>
      </div>

      {{-- Validation Errors --}}
      @if ($errors->any() && request()->routeIs('register'))
        <div class="alert alert-danger">
          <strong>Terdapat beberapa kesalahan:</strong>
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- SEKSI 1: Informasi Dasar -->
        <div class="sec">
          <div class="sec-hdr">
            <div class="sec-icon" style="background:#f0fdf4;">
              <svg width="16" height="16" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
              <div class="sec-title">Informasi Dasar</div>
              <div class="sec-sub">Identitas dan posisi di organisasi</div>
            </div>
          </div>

          <div class="grid-2">
            <div class="field col-2">
              <label>Nama Lengkap <span class="req">*</span></label>
              <div class="inp-wrap">
                <svg class="inp-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <input type="text" name="name" value="{{ old('name') }}"
                  class="inp has-icon @error('name') is-invalid @enderror"
                  placeholder="Masukkan nama lengkap" autocomplete="name">
              </div>
              @error('name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label>Email <span class="req">*</span></label>
              <div class="inp-wrap">
                <svg class="inp-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <input type="email" name="email" value="{{ old('email') }}"
                  class="inp has-icon @error('email') is-invalid @enderror"
                  placeholder="nama@rscm.co.id" autocomplete="email">
              </div>
              @error('email')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label>NIP <span class="req">*</span></label>
              <div class="inp-wrap">
                <svg class="inp-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                <input type="text" name="nip" value="{{ old('nip') }}"
                  class="inp has-icon @error('nip') is-invalid @enderror"
                  placeholder="Nomor Induk Pegawai">
              </div>
              @error('nip')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label>Jabatan <span class="req">*</span></label>
              <div class="inp-wrap">
                <svg class="inp-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                  class="inp has-icon @error('jabatan') is-invalid @enderror"
                  placeholder="Perawat / Dokter / Staf">
              </div>
              @error('jabatan')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label>Role <span class="req">*</span></label>
              <select name="role_id" class="inp @error('role_id') is-invalid @enderror">
                <option value="">— Pilih Role —</option>
                @foreach($roles as $role)
                  <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                  </option>
                @endforeach
              </select>
              @error('role_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label>Unit Kerja / Ruangan <span class="req">*</span></label>
              <div class="inp-wrap">
                <svg class="inp-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <input type="text" name="unit" value="{{ old('unit') }}"
                  class="inp has-icon @error('unit') is-invalid @enderror"
                  placeholder="IGD / ICU / Poli Umum …" list="unit-opts">
                <datalist id="unit-opts">
                  @foreach($units as $u)
                    <option>{{ $u }}</option>
                  @endforeach
                  <option>IGD</option><option>ICU</option><option>Poli Umum</option>
                  <option>Poli Jiwa</option><option>Bedah</option><option>Radiologi</option>
                  <option>Farmasi</option><option>Administrasi</option><option>Keuangan</option>
                </datalist>
              </div>
              @error('unit')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field col-2">
              <label>No. Telepon <span class="opt">opsional</span></label>
              <div class="inp-wrap">
                <svg class="inp-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <input type="text" name="phone" value="{{ old('phone') }}"
                  class="inp has-icon @error('phone') is-invalid @enderror"
                  placeholder="08xxxxxxxxxx">
              </div>
              @error('phone')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        <!-- SEKSI 2: Data Personal -->
        <div class="sec">
          <div class="sec-hdr">
            <div class="sec-icon" style="background:#eff6ff;">
              <svg width="16" height="16" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
              <div class="sec-title">Data Personal</div>
              <div class="sec-sub">Informasi demografis pegawai</div>
            </div>
          </div>

          <div class="field">
            <label>Jenis Kelamin <span class="req">*</span></label>
            <div class="gender-group">
              <label class="gc">
                <input type="radio" name="gender" value="L" {{ old('gender') === 'L' ? 'checked' : '' }}>
                <div class="gc-body">
                  <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="10" cy="10" r="7"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 5l5-5m0 0h-4m4 0v4"/></svg>
                  Laki-laki
                </div>
              </label>
              <label class="gc">
                <input type="radio" name="gender" value="P" {{ old('gender') === 'P' ? 'checked' : '' }}>
                <div class="gc-body">
                  <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v6m-3-3h6"/></svg>
                  Perempuan
                </div>
              </label>
            </div>
            @error('gender')<div class="error-msg">{{ $message }}</div>@enderror
          </div>

          <div class="grid-2" style="margin-top:14px;">
            <div class="field">
              <label>Usia <span class="req">*</span></label>
              <div class="inp-wrap">
                <input type="number" name="usia" value="{{ old('usia') }}"
                  class="inp has-suffix @error('usia') is-invalid @enderror"
                  placeholder="30" min="18" max="70">
                <span class="inp-suffix">tahun</span>
              </div>
              <span class="helper">Rentang 18 – 70 tahun</span>
              @error('usia')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label>Status Pernikahan <span class="req">*</span></label>
              <select name="status_pernikahan" class="inp @error('status_pernikahan') is-invalid @enderror">
                <option value="">— Pilih —</option>
                <option value="belum_menikah"  {{ old('status_pernikahan') === 'belum_menikah'  ? 'selected' : '' }}>Belum Menikah</option>
                <option value="menikah"         {{ old('status_pernikahan') === 'menikah'         ? 'selected' : '' }}>Menikah</option>
                <option value="cerai_hidup"     {{ old('status_pernikahan') === 'cerai_hidup'     ? 'selected' : '' }}>Cerai Hidup</option>
                <option value="cerai_mati"      {{ old('status_pernikahan') === 'cerai_mati'      ? 'selected' : '' }}>Cerai Mati</option>
              </select>
              @error('status_pernikahan')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label>Pendidikan Terakhir <span class="req">*</span></label>
              <select name="pendidikan" class="inp @error('pendidikan') is-invalid @enderror">
                <option value="">— Pilih —</option>
                @foreach(['SMA/SMK','D1','D2','D3','D4','S1','Profesi','S2','S3'] as $p)
                  <option value="{{ $p }}" {{ old('pendidikan') === $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
              </select>
              @error('pendidikan')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label>Lama Bekerja <span class="req">*</span></label>
              <div class="split">
                <div class="inp-wrap">
                  <input type="number" name="lama_kerja_tahun" value="{{ old('lama_kerja_tahun', 0) }}"
                    class="inp has-suffix @error('lama_kerja_tahun') is-invalid @enderror"
                    placeholder="0" min="0" max="50">
                  <span class="inp-suffix">tahun</span>
                </div>
                <div class="inp-wrap">
                  <input type="number" name="lama_kerja_bulan" value="{{ old('lama_kerja_bulan', 0) }}"
                    class="inp has-suffix @error('lama_kerja_bulan') is-invalid @enderror"
                    placeholder="0" min="0" max="11">
                  <span class="inp-suffix">bulan</span>
                </div>
              </div>
              @error('lama_kerja_tahun')<div class="error-msg">{{ $message }}</div>@enderror
              @error('lama_kerja_bulan')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        <!-- SEKSI 3: Riwayat Kesehatan -->
        <div class="sec">
          <div class="sec-hdr">
            <div class="sec-icon" style="background:#fff7ed;">
              <svg width="16" height="16" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <div>
              <div class="sec-title">Riwayat Kesehatan</div>
              <div class="sec-sub">Informasi kondisi medis yang relevan</div>
            </div>
          </div>

          {{--
            PENTING: has_health_issue dikirim sebagai boolean.
            Hidden input value="0" sebagai default, radio value="1" akan override jika dipilih.
          --}}
          <input type="hidden" name="has_health_issue" value="0">

          <div class="field">
            <label>Apakah memiliki masalah kesehatan? <span class="req">*</span></label>
            <div class="radio-group">
              <label class="rc">
                <input type="radio" name="has_health_issue" value="1"
                  {{ old('has_health_issue') == '1' ? 'checked' : '' }}
                  onchange="toggleRegHealth(1)">
                <div class="rc-body"><div class="rc-dot"></div>Ya, ada kondisi tertentu</div>
              </label>
              <label class="rc">
                <input type="radio" name="has_health_issue" value="0"
                  {{ old('has_health_issue', '0') == '0' ? 'checked' : '' }}
                  onchange="toggleRegHealth(0)">
                <div class="rc-body"><div class="rc-dot"></div>Tidak ada masalah</div>
              </label>
            </div>
            @error('has_health_issue')<div class="error-msg">{{ $message }}</div>@enderror
          </div>

          <div id="reg-health-detail" class="{{ old('has_health_issue') == '1' ? 'show' : '' }}">
            <div class="field">
              <label>Sebutkan kondisi kesehatan</label>
              <textarea name="health_issue_detail"
                class="inp @error('health_issue_detail') is-invalid @enderror"
                rows="2"
                placeholder="Hipertensi, Diabetes, Asma… (pisahkan dengan koma)">{{ old('health_issue_detail') }}</textarea>
              @error('health_issue_detail')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        <!-- SEKSI 4: Password -->
        <div class="sec">
          <div class="sec-hdr">
            <div class="sec-icon" style="background:#faf5ff;">
              <svg width="16" height="16" fill="none" stroke="#9333ea" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
              <div class="sec-title">Password Akun</div>
              <div class="sec-sub">Buat password yang aman</div>
            </div>
          </div>

          <div class="grid-2">
            <div class="field">
              <label>Password <span class="req">*</span></label>
              <div class="inp-wrap">
                <svg class="inp-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                <input type="password" name="password" id="regPwd"
                  class="inp has-icon @error('password') is-invalid @enderror"
                  placeholder="Min. 8 karakter"
                  autocomplete="new-password"
                  oninput="checkStrength(this.value)">
              </div>
              <div class="pwd-bars">
                <div class="pwd-bar" id="rb1"></div>
                <div class="pwd-bar" id="rb2"></div>
                <div class="pwd-bar" id="rb3"></div>
                <div class="pwd-bar" id="rb4"></div>
              </div>
              <span class="pwd-hint" id="regHint">Kombinasi huruf, angka, dan simbol</span>
              @error('password')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label>Konfirmasi Password <span class="req">*</span></label>
              <div class="inp-wrap">
                <svg class="inp-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <input type="password" name="password_confirmation"
                  class="inp has-icon"
                  placeholder="Ulangi password"
                  autocomplete="new-password">
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="reg-actions">
          <button type="button" class="btn-outline" onclick="switchTab('login')">
            Batal
          </button>
          <button type="submit" class="btn-primary" style="flex:2;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Daftar Sekarang
          </button>
        </div>

      </form>
    </div><!-- /view-register -->

  </div><!-- /panel-right -->

</div><!-- /wrap -->

<script>
  // Tentukan tab aktif awal dari PHP
  const initialTab = '{{ request()->routeIs("register") ? "register" : "login" }}';

  function switchTab(tab) {
    document.getElementById('view-login').classList.toggle('show', tab === 'login');
    document.getElementById('view-register').classList.toggle('show', tab === 'register');
    document.getElementById('tab-login').classList.toggle('active', tab === 'login');
    document.getElementById('tab-register').classList.toggle('active', tab === 'register');
  }

  // Jaga agar tab yang benar aktif saat halaman load
  switchTab(initialTab);

  function togglePwd(inputId, iconId) {
    const inp  = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (inp.type === 'password') {
      inp.type = 'text';
      icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
    } else {
      inp.type = 'password';
      icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
  }

  function fillDemo(email) {
    const emailInp = document.querySelector('#view-login input[type="email"]');
    const pwdInp   = document.querySelector('#view-login input[type="password"]');
    if (emailInp) emailInp.value = email;
    if (pwdInp)   pwdInp.value   = 'password';
  }

  function toggleRegHealth(val) {
    const el = document.getElementById('reg-health-detail');
    if (val === 1) {
      el.classList.add('show');
    } else {
      el.classList.remove('show');
      const ta = el.querySelector('textarea');
      if (ta) ta.value = '';
    }
  }

  function checkStrength(pwd) {
    const bars = ['rb1','rb2','rb3','rb4'].map(id => document.getElementById(id));
    const hint = document.getElementById('regHint');
    bars.forEach(b => { b.className = 'pwd-bar'; });
    if (!pwd) { hint.textContent = 'Kombinasi huruf, angka, dan simbol'; hint.style.color = ''; return; }
    let score = 0;
    if (pwd.length >= 8)          score++;
    if (/[A-Z]/.test(pwd))        score++;
    if (/[0-9]/.test(pwd))        score++;
    if (/[^A-Za-z0-9]/.test(pwd)) score++;
    const cls    = score <= 1 ? 'weak' : score === 2 ? 'ok' : 'strong';
    const labels = { weak: 'Lemah — tambahkan huruf kapital, angka, atau simbol', ok: 'Sedang — coba tambahkan simbol khusus', strong: 'Kuat — password sudah aman ✓' };
    const colors = { weak: '#ef4444', ok: '#f59e0b', strong: '#10b981' };
    for (let i = 0; i < score; i++) bars[i].classList.add(cls);
    hint.textContent = labels[cls];
    hint.style.color = colors[cls];
  }
</script>
</body>
</html>