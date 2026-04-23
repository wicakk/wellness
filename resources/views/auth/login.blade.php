<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellnessApp — Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }

        .mesh-bg {
            background-color: #f0faf4;
            background-image:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(52,211,153,0.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 80%, rgba(16,185,129,0.12) 0%, transparent 55%),
                radial-gradient(ellipse 40% 40% at 60% 20%, rgba(110,231,183,0.15) 0%, transparent 50%);
        }

        .card-glass {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .input-field {
            transition: all 0.25s ease;
            border: 1.5px solid #e5e7eb;
            background: #fafafa;
        }
        .input-field:focus {
            border-color: #10b981;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(16,185,129,0.1);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #059669 0%, #10b981 60%, #34d399 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(16,185,129,0.35);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(16,185,129,0.45);
        }
        .btn-primary:active { transform: translateY(0); }

        .demo-card {
            border: 1.5px solid #e5e7eb;
            transition: all 0.2s ease;
            background: #fff;
            cursor: pointer;
        }
        .demo-card:hover {
            border-color: #10b981;
            background: #f0fdf8;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(16,185,129,0.12);
        }

        .panel-left {
            background: linear-gradient(160deg, #064e3b 0%, #065f46 40%, #047857 100%);
        }

        .stat-pill {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .floating-card {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.25);
            backdrop-filter: blur(12px);
        }

        .bar-fill { transition: width 1.2s cubic-bezier(0.4,0,0.2,1); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-16px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .anim-1 { animation: fadeUp 0.6s ease both; }
        .anim-2 { animation: fadeUp 0.6s 0.1s ease both; }
        .anim-3 { animation: fadeUp 0.6s 0.2s ease both; }
        .anim-4 { animation: fadeUp 0.6s 0.3s ease both; }
        .anim-5 { animation: fadeUp 0.6s 0.4s ease both; }
        .panel-anim { animation: slideIn 0.7s ease both; }

        .divider-line {
            background: linear-gradient(to right, transparent, #d1d5db, transparent);
        }

        .role-icon-admin    { background: #eff6ff; color: #3b82f6; }
        .role-icon-wellness { background: #f0fdf4; color: #16a34a; }
        .role-icon-psikolog { background: #faf5ff; color: #9333ea; }
        .role-icon-pegawai  { background: #fffbeb; color: #d97706; }
    </style>
</head>
<body class="mesh-bg min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-5xl flex rounded-3xl overflow-hidden shadow-2xl" style="min-height:600px;">

        {{-- ── Panel Kiri ── --}}
        <div class="panel-left hidden lg:flex flex-col justify-between w-2/5 p-10 panel-anim">

            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center border border-white/30">
                    {{-- Heart icon --}}
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402C1 3.05 3.027 1 5.5 1c1.71 0 3.252.862 4.5 2.2C11.248 1.862 12.79 1 14.5 1 16.973 1 19 3.05 19 7.19c0 4.105-5.37 8.863-11 14.402z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-lg leading-none">WellnessApp</p>
                    <p class="text-emerald-300 text-xs mt-0.5">Mental Health Platform</p>
                </div>
            </div>

            {{-- Headline --}}
            <div>
                <h2 class="font-display text-white text-4xl leading-tight mb-4">
                    Monitoring<br>Kesehatan<br>Mental Pegawai
                </h2>
                <p class="text-emerald-200 text-sm leading-relaxed">
                    Skrining mandiri, pemantauan risiko, dan tindak lanjut berbasis data untuk organisasi yang peduli.
                </p>
            </div>

            {{-- Floating Card --}}
            <div class="floating-card rounded-2xl p-5">
                <p class="text-emerald-300 text-xs font-semibold uppercase tracking-widest mb-3">Skrining Bulan Ini</p>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs text-white/80 mb-1">
                            <span>Risiko Rendah</span><span class="text-emerald-300 font-semibold">60%</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-emerald-400 bar-fill" style="width:60%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-white/80 mb-1">
                            <span>Risiko Sedang</span><span class="text-amber-300 font-semibold">32%</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-amber-400 bar-fill" style="width:32%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-white/80 mb-1">
                            <span>Risiko Tinggi</span><span class="text-red-300 font-semibold">8%</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-red-400 bar-fill" style="width:8%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex gap-3">
                <div class="stat-pill rounded-xl px-4 py-3 flex-1 text-center">
                    <p class="text-white font-bold text-xl">4</p>
                    <p class="text-emerald-300 text-xs mt-0.5">Role</p>
                </div>
                <div class="stat-pill rounded-xl px-4 py-3 flex-1 text-center">
                    <p class="text-white font-bold text-xl">10+</p>
                    <p class="text-emerald-300 text-xs mt-0.5">Fitur</p>
                </div>
                <div class="stat-pill rounded-xl px-4 py-3 flex-1 text-center">
                    <p class="text-white font-bold text-xl">100%</p>
                    <p class="text-emerald-300 text-xs mt-0.5">Aman</p>
                </div>
            </div>
        </div>

        {{-- ── Panel Kanan (Form) ── --}}
        <div class="card-glass flex-1 flex flex-col justify-center px-8 py-10 lg:px-12">

            <div class="anim-1 mb-8">
                {{-- Mobile logo --}}
                <div class="flex items-center gap-2 mb-6 lg:hidden">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402C1 3.05 3.027 1 5.5 1c1.71 0 3.252.862 4.5 2.2C11.248 1.862 12.79 1 14.5 1 16.973 1 19 3.05 19 7.19c0 4.105-5.37 8.863-11 14.402z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800">WellnessApp</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Selamat Datang</h1>
                <p class="text-gray-500 text-sm mt-1">Masuk dengan akun yang telah terdaftar</p>
            </div>

            @if(session('status'))
                <div class="anim-1 mb-5 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div class="anim-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Email</label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="nama@instansi.id"
                            class="input-field w-full pl-11 pr-4 py-3.5 rounded-xl text-sm text-gray-800 placeholder-gray-400 @error('email') border-red-400 @enderror">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="anim-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Password</label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" name="password" id="passwordInput" required
                            placeholder="••••••••"
                            class="input-field w-full pl-11 pr-12 py-3.5 rounded-xl text-sm text-gray-800 @error('password') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember & Forgot --}}
                <div class="anim-3 flex items-center justify-between">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="remember" class="sr-only peer">
                            <div class="w-4 h-4 border-2 border-gray-300 rounded peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all"></div>
                            <svg class="absolute inset-0 w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity p-0.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-500 group-hover:text-gray-700 transition-colors">Ingat saya</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-emerald-600 font-medium hover:text-emerald-800 transition-colors">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <div class="anim-4">
                    <button type="submit" class="btn-primary w-full text-white font-semibold py-3.5 rounded-xl flex items-center justify-center gap-2.5 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk ke Dashboard
                    </button>
                </div>
            </form>

            {{-- Demo Accounts --}}
            <div class="anim-5 mt-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-1 h-px divider-line"></div>
                    <p class="text-xs text-gray-400 font-medium whitespace-nowrap">Akun Demo — klik untuk langsung masuk</p>
                    <div class="flex-1 h-px divider-line"></div>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <button type="button" onclick="fillDemo('admin@wellness.id')"
                        class="demo-card rounded-xl p-3 flex items-center gap-3 text-left">
                        <div class="role-icon-admin w-9 h-9 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 leading-none">Admin</p>
                            <p class="text-xs text-gray-400 mt-0.5">admin@wellness.id</p>
                        </div>
                    </button>

                    <button type="button" onclick="fillDemo('wellness@wellness.id')"
                        class="demo-card rounded-xl p-3 flex items-center gap-3 text-left">
                        <div class="role-icon-wellness w-9 h-9 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 leading-none">Wellness</p>
                            <p class="text-xs text-gray-400 mt-0.5">wellness@wellness.id</p>
                        </div>
                    </button>

                    <button type="button" onclick="fillDemo('psikolog@wellness.id')"
                        class="demo-card rounded-xl p-3 flex items-center gap-3 text-left">
                        <div class="role-icon-psikolog w-9 h-9 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 leading-none">Psikolog</p>
                            <p class="text-xs text-gray-400 mt-0.5">psikolog@wellness.id</p>
                        </div>
                    </button>

                    <button type="button" onclick="fillDemo('pegawai@wellness.id')"
                        class="demo-card rounded-xl p-3 flex items-center gap-3 text-left">
                        <div class="role-icon-pegawai w-9 h-9 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 leading-none">Pegawai</p>
                            <p class="text-xs text-gray-400 mt-0.5">pegawai@wellness.id</p>
                        </div>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }

        function fillDemo(email) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = 'password';
        }
    </script>
</body>
</html>