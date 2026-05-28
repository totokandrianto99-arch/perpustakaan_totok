<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Daftar — Sistem Perpustakaan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --primary: #6366f1; --primary-dark: #4f46e5; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
            overflow: hidden;
        }

        .bg-shapes { position: fixed; inset: 0; overflow: hidden; z-index: 0; }
        .shape { position: absolute; border-radius: 50%; filter: blur(80px); opacity: .15; animation: float 8s ease-in-out infinite; }
        .shape-1 { width: 500px; height: 500px; background: #6366f1; top: -100px; left: -100px; }
        .shape-2 { width: 400px; height: 400px; background: #0ea5e9; bottom: -80px; right: -80px; animation-delay: 2s; }
        .shape-3 { width: 300px; height: 300px; background: #8b5cf6; top: 50%; left: 50%; animation-delay: 4s; }
        @keyframes float { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(20px,-20px) scale(1.05); } }

        .login-left {
            flex: 1; display: flex; align-items: center; justify-content: center;
            padding: 40px; position: relative; z-index: 1;
        }
        .left-content { max-width: 420px; }
        .brand { display: flex; align-items: center; gap: 14px; margin-bottom: 48px; }
        .brand-icon { width: 52px; height: 52px; background: var(--primary); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #fff; }
        .brand h1 { font-size: 22px; font-weight: 700; color: #fff; }
        .brand p  { font-size: 13px; color: #64748b; }
        .hero-title { font-size: 42px; font-weight: 700; color: #fff; line-height: 1.2; margin-bottom: 16px; }
        .hero-title span { color: var(--primary); }
        .hero-desc { font-size: 15px; color: #64748b; line-height: 1.7; }
        .features { margin-top: 40px; display: flex; flex-direction: column; gap: 16px; }
        .feature-item { display: flex; align-items: center; gap: 14px; color: #94a3b8; font-size: 14px; }
        .feature-icon { width: 36px; height: 36px; background: rgba(99,102,241,.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 14px; flex-shrink: 0; }

        /* FORM SIDE */
        .login-right {
            width: 500px; background: #fff;
            display: flex; align-items: center; justify-content: center;
            padding: 40px 40px; position: relative; z-index: 1;
            overflow-y: auto;
            animation: slideIn .5s ease;
        }
        @keyframes slideIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }

        .form-box { width: 100%; }

        /* TABS */
        .tabs { display: flex; background: #f1f5f9; border-radius: 12px; padding: 4px; margin-bottom: 28px; }
        .tab-btn {
            flex: 1; padding: 10px; border: none; background: none; border-radius: 9px;
            font-size: 14px; font-weight: 500; color: #64748b; cursor: pointer; transition: all .2s;
        }
        .tab-btn.active { background: #fff; color: var(--primary); box-shadow: 0 1px 4px rgba(0,0,0,.1); }

        .tabs-wrapper { position: relative; overflow: hidden; transition: height .3s ease; }

        .tab-pane {
            display: block;
            position: absolute;
            top: 0; left: 0; width: 100%;
            opacity: 0;
            pointer-events: none;
            transform: translateX(40px);
            transition: opacity .3s ease, transform .3s ease;
        }
        .tab-pane.active {
            position: relative;
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0);
        }
        .tab-pane.exit-left  { transform: translateX(-40px); opacity: 0; }
        .tab-pane.exit-right { transform: translateX(40px);  opacity: 0; }

        .pane-header { margin-bottom: 24px; }
        .pane-header h2 { font-size: 22px; font-weight: 700; color: #0f172a; }
        .pane-header p  { font-size: 13px; color: #64748b; margin-top: 5px; }

        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 7px; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }
        .form-control {
            width: 100%; padding: 11px 13px 11px 40px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 14px; font-family: inherit; background: #f8fafc; transition: all .2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
        .form-control.is-invalid { border-color: #ef4444; }
        .invalid-feedback { font-size: 12px; color: #ef4444; margin-top: 4px; }
        .toggle-pass { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 14px; padding: 0; }

        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .btn-submit {
            width: 100%; padding: 13px; background: var(--primary); color: #fff;
            border: none; border-radius: 12px; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 6px;
        }
        .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(99,102,241,.3); }

        .alert-danger {
            background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;
            border-radius: 10px; padding: 12px 16px; font-size: 13px; margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }

        .form-footer { margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 12px; color: #94a3b8; }

        @media (max-width: 768px) {
            body { flex-direction: column; overflow: auto; }
            .login-left { display: none; }
            .login-right { width: 100%; min-height: 100vh; }
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="login-left">
        <div class="left-content">
            <div class="brand">
                <div class="brand-icon"><i class="fas fa-book-open"></i></div>
                <div>
                    <h1>Perpustakaan</h1>
                    <p>Sistem Manajemen Buku</p>
                </div>
            </div>
            <h2 class="hero-title">Kelola <span>Perpustakaan</span><br>dengan Mudah</h2>
            <p class="hero-desc">Platform digital untuk manajemen buku dan transaksi peminjaman perpustakaan sekolah yang efisien.</p>
            <div class="features">
                <div class="feature-item"><div class="feature-icon"><i class="fas fa-book"></i></div> Manajemen data buku lengkap</div>
                <div class="feature-item"><div class="feature-icon"><i class="fas fa-exchange-alt"></i></div> Pencatatan transaksi peminjaman</div>
                <div class="feature-item"><div class="feature-icon"><i class="fas fa-chart-bar"></i></div> Dashboard statistik real-time</div>
            </div>
        </div>
    </div>

    <div class="login-right">
        <div class="form-box">

            <div class="tabs">
                <button class="tab-btn active" id="btn-login" onclick="switchTab('login', 0)"><i class="fas fa-sign-in-alt"></i> Masuk</button>
                <button class="tab-btn" id="btn-register" onclick="switchTab('register', 1)"><i class="fas fa-user-plus"></i> Daftar</button>
            </div>

            <div class="tabs-wrapper">

            {{-- TAB LOGIN --}}
            <div class="tab-pane active" id="tab-login">
                <div class="pane-header">
                    <h2>Selamat Datang 👋</h2>
                    <p>Masuk ke akun perpustakaan Anda</p>
                </div>

                @if(session('tab') !== 'register' && $errors->any())
                <div class="alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <div class="input-wrap">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="username" class="form-control {{ !session('tab') && $errors->has('username') ? 'is-invalid' : '' }}"
                                   placeholder="Masukkan username" value="{{ old('username') }}" autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrap">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" id="lpass" class="form-control" placeholder="Masukkan password">
                            <button type="button" class="toggle-pass" onclick="togglePass('lpass','leye')"><i class="fas fa-eye" id="leye"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit"><i class="fas fa-sign-in-alt"></i> Masuk</button>
                </form>

                <div class="form-footer"><i class="fas fa-shield-alt"></i> Sistem Perpustakaan &copy; {{ date('Y') }}</div>
            </div>

            {{-- TAB REGISTER --}}
            <div class="tab-pane" id="tab-register">
                <div class="pane-header">
                    <h2>Buat Akun Baru ✨</h2>
                    <p>Daftar untuk mulai meminjam buku</p>
                </div>

                @if(session('tab') === 'register' && $errors->any())
                <div class="alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span style="color:red">*</span></label>
                        <div class="input-wrap">
                            <i class="fas fa-id-card input-icon"></i>
                            <input type="text" name="nama_lengkap" class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
                                   placeholder="Nama lengkap kamu" value="{{ old('nama_lengkap') }}">
                        </div>
                        @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span style="color:#94a3b8;font-weight:400">(opsional)</span></label>
                        <div class="input-wrap">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   placeholder="email@contoh.com" value="{{ old('email') }}">
                        </div>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Username <span style="color:red">*</span></label>
                        <div class="input-wrap">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                   placeholder="Buat username unik" value="{{ old('username') }}">
                        </div>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row-2">
                        <div class="form-group">
                            <label class="form-label">Password <span style="color:red">*</span></label>
                            <div class="input-wrap">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" name="password" id="rpass1" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Min. 6 karakter">
                                <button type="button" class="toggle-pass" onclick="togglePass('rpass1','reye1')"><i class="fas fa-eye" id="reye1"></i></button>
                            </div>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi <span style="color:red">*</span></label>
                            <div class="input-wrap">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" name="password_confirmation" id="rpass2" class="form-control" placeholder="Ulangi password">
                                <button type="button" class="toggle-pass" onclick="togglePass('rpass2','reye2')"><i class="fas fa-eye" id="reye2"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit"><i class="fas fa-user-plus"></i> Daftar Sekarang</button>
                </form>
            </div>

            </div>{{-- end tabs-wrapper --}}
        </div>
    </div>

    <script>
        let currentTab = 'login';
        const tabOrder  = { login: 0, register: 1 };

        function switchTab(tab, idx) {
            if (tab === currentTab) return;

            const outPane = document.getElementById('tab-' + currentTab);
            const inPane  = document.getElementById('tab-' + tab);
            const goRight = idx > tabOrder[currentTab];

            // Wrapper height lock agar tidak collapse saat absolute
            const wrapper = document.querySelector('.tabs-wrapper');
            wrapper.style.height = outPane.offsetHeight + 'px';

            // Exit animasi pane lama
            outPane.classList.remove('active');
            outPane.classList.add(goRight ? 'exit-left' : 'exit-right');

            // Posisi awal pane baru
            inPane.style.transform = goRight ? 'translateX(40px)' : 'translateX(-40px)';
            inPane.style.opacity   = '0';
            inPane.style.position  = 'absolute';
            inPane.style.pointerEvents = 'none';

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    inPane.classList.add('active');
                    inPane.style.transform = '';
                    inPane.style.opacity   = '';
                    inPane.style.position  = '';
                    inPane.style.pointerEvents = '';

                    // Animasikan tinggi wrapper ke pane baru
                    wrapper.style.height = inPane.scrollHeight + 'px';
                    setTimeout(() => { wrapper.style.height = ''; }, 320);
                });
            });

            setTimeout(() => outPane.classList.remove('exit-left', 'exit-right'), 320);

            // Update tombol aktif
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('btn-' + tab).classList.add('active');

            currentTab = tab;
        }

        function togglePass(id, iconId) {
            const input = document.getElementById(id);
            const icon  = document.getElementById(iconId);
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }

        // Auto-switch ke tab register via query param atau session error
        const urlTab = new URLSearchParams(window.location.search).get('tab');
        @if(session('tab') === 'register' || $errors->has('nama_lengkap'))
            switchTab('register', 1);
        @elseif(false)
        @endif
        if (urlTab === 'register') switchTab('register', 1);
    </script>
</body>
</html>
