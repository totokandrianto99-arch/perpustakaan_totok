<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --secondary: #7c3aed;
            --bg: #0f172a;
            --card: rgba(255,255,255,0.08);
            --text: #ffffff;
            --muted: #94a3b8;
            --border: rgba(255,255,255,0.1);
        }

        body.light {
            --bg: #f8fafc;
            --card: rgba(255,255,255,0.8);
            --text: #0f172a;
            --muted: #475569;
            --border: rgba(15,23,42,0.1);
        }

        body {
            scroll-behavior: smooth;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            transition: .3s ease;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        .container {
            width: 90%;
            max-width: 1300px;
            margin: auto;
        }

        nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            backdrop-filter: blur(18px);
            background: rgba(15,23,42,0.75);
            border-bottom: 1px solid var(--border);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 0;
        }

        .logo {
            font-size: 1.3rem;
            font-weight: 800;
            color: white;
            transition: opacity .2s ease;
            cursor: pointer;
        }
        .logo:hover { opacity: .8; }

        .nav-menu {
            display: flex;
            gap: 18px;
            align-items: center;
        }

        .nav-menu a {
            color: white;
            font-weight: 600;
            font-size: .95rem;
            transition: color .2s ease, transform .2s ease;
            display: inline-block;
        }
        .nav-menu a:hover {
            color: #60a5fa;
            transform: translateY(-2px);
        }

        .toggle-theme {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg,var(--primary),var(--secondary));
            color: white;
            font-size: 1rem;
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .toggle-theme:hover {
            transform: rotate(20deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(37,99,235,.5);
        }

        .nav-btn-masuk {
            border: 1.5px solid rgba(255,255,255,.35) !important;
            padding: 7px 16px !important;
            border-radius: 10px !important;
            font-size: .9rem !important;
            transition: border-color .2s, background .2s, color .2s, transform .2s !important;
        }
        .nav-btn-masuk:hover {
            border-color: #60a5fa !important;
            background: rgba(96,165,250,.12) !important;
            color: #60a5fa !important;
            transform: translateY(-2px) !important;
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 120px;
            position: relative;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text h1 {
            font-size: clamp(2.8rem, 6vw, 5rem);
            line-height: 1.05;
            margin-bottom: 24px;
            font-weight: 800;
        }

        .hero-text h1 span {
            background: linear-gradient(135deg,#60a5fa,#a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            color: var(--muted);
            line-height: 1.8;
            margin-bottom: 35px;
            font-size: 1.05rem;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 24px;
            border-radius: 14px;
            font-weight: 700;
            transition: .3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg,var(--primary),var(--secondary));
            color: white;
        }

        .btn-secondary {
            border: 1px solid var(--border);
            color: var(--text);
        }

        .btn:hover {
            transform: translateY(-4px);
        }

        .hero-image {
            position: relative;
        }

        .hero-image img {
            width: 100%;
            aspect-ratio: 1536 / 1024;
            object-fit: cover;
            border-radius: 32px;
            box-shadow: 0 25px 80px rgba(37,99,235,0.35);
        }

        .floating-card {
            position: absolute;
            background: rgba(15,23,42,0.75);
            backdrop-filter: blur(18px);
            padding: 16px 20px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.15);
            transition: transform .3s ease, box-shadow .3s ease;
            cursor: default;
            white-space: nowrap;
        }
        .floating-card h3 { color: #fff !important; }
        .floating-card p  { color: #94a3b8 !important; }
        .floating-card:hover {
            transform: translateY(-6px) scale(1.04) !important;
            box-shadow: 0 16px 40px rgba(37,99,235,.35);
            border-color: rgba(96,165,250,.5);
        }
        .floating-card-1 {
            bottom: 20px;
            left: -30px;
            animation: floatA 4s ease-in-out infinite;
        }
        .floating-card-2 {
            top: 30px;
            right: -20px;
            animation: floatB 4.5s ease-in-out infinite;
        }
        .floating-card-3 {
            bottom: 110px;
            right: -24px;
            animation: floatA 5s ease-in-out infinite;
        }
        @keyframes floatA {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-10px); }
        }
        @keyframes floatB {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(10px); }
        }

        .stats {
            padding: 80px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4,1fr);
            gap: 24px;
        }

        .stat-card {
            padding: 28px;
            border-radius: 24px;
            background: var(--card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            text-align: center;
            transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
        }
        .stat-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 20px 50px rgba(37,99,235,.2);
            border-color: rgba(96,165,250,.4);
        }

        .stat-card h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            margin-bottom: 14px;
        }

        .section-title p {
            color: var(--muted);
        }

        .books {
            padding: 100px 0;
        }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        /* ── Book Card ── */
        .book-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            backdrop-filter: blur(18px);
            transition: transform .35s cubic-bezier(.4,0,.2,1),
                        box-shadow .35s ease,
                        border-color .35s ease;
        }
        .book-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 28px 60px rgba(37,99,235,.25);
            border-color: rgba(96,165,250,.4);
        }
        .book-card .book-img-wrap {
            position: relative;
            overflow: hidden;
            height: 240px;
        }
        .book-card .book-img-wrap img,
        .book-card .book-img-wrap .book-placeholder {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
            display: block;
        }
        .book-card .book-placeholder {
            background: linear-gradient(135deg,rgba(37,99,235,.25),rgba(124,58,237,.25));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 52px;
        }
        .book-card:hover .book-img-wrap img,
        .book-card:hover .book-img-wrap .book-placeholder {
            transform: scale(1.08);
        }

        /* Hover overlay detail */
        .book-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                rgba(10,15,30,.97) 0%,
                rgba(10,15,30,.85) 45%,
                rgba(37,99,235,.3) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 22px;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity .3s ease, transform .3s ease;
            z-index: 2;
        }
        .book-card:hover .book-overlay {
            opacity: 1;
            transform: translateY(0);
        }
        .book-overlay .ov-kategori {
            font-size: 11px;
            font-weight: 700;
            color: #93c5fd;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .book-overlay .ov-title {
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .book-overlay .ov-author {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 10px;
        }
        .book-overlay .ov-sinopsis {
            font-size: 12px;
            color: #cbd5e1;
            line-height: 1.6;
            margin-bottom: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .book-overlay .ov-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .book-overlay .ov-stok {
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
        }
        .ov-stok.ada   { background: rgba(52,211,153,.15); color: #34d399; border: 1px solid rgba(52,211,153,.3); }
        .ov-stok.habis { background: rgba(248,113,113,.15); color: #f87171; border: 1px solid rgba(248,113,113,.3); }
        .book-overlay .ov-dipinjam {
            font-size: 11px;
            color: #94a3b8;
        }
        .book-overlay .ov-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px;
            border-radius: 12px;
            background: linear-gradient(135deg,#2563eb,#7c3aed);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: filter .2s, transform .2s;
        }
        .book-overlay .ov-btn:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }

        /* Info bawah card (visible default) */
        .book-content {
            padding: 16px 18px 18px;
        }
        .book-content h3 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .book-content p {
            color: var(--muted);
            font-size: .85rem;
            line-height: 1.5;
        }
        .book-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
        }

        .badge {
            background: rgba(37,99,235,.18);
            color: #93c5fd;
            padding: 5px 11px;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 700;
        }

        /* Tombol lihat lainnya */
        .btn-lihat {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 13px 28px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            border: 2px solid rgba(96,165,250,.5);
            background: rgba(37,99,235,.12);
            color: #93c5fd;
            transition: all .25s ease;
            backdrop-filter: blur(8px);
        }
        .btn-lihat:hover {
            background: rgba(37,99,235,.25);
            border-color: #60a5fa;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,99,235,.3);
        }
        body.light .btn-lihat {
            border-color: rgba(37,99,235,.4);
            background: rgba(37,99,235,.08);
            color: #2563eb;
        }
        body.light .btn-lihat:hover {
            background: rgba(37,99,235,.15);
            border-color: #2563eb;
            color: #1d4ed8;
        }
        .btn-lihat .icon-chevron {
            transition: transform .3s ease;
            font-size: 12px;
        }
        .btn-lihat.expanded .icon-chevron {
            transform: rotate(180deg);
        }

        @media(max-width: 1024px) {
            .book-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media(max-width: 600px) {
            .book-grid { grid-template-columns: 1fr; }
            .book-card .book-img-wrap { height: 200px; }
        }

        .features {
            padding: 100px 0;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(250px,1fr));
            gap: 24px;
        }

        .feature-card {
            padding: 30px;
            border-radius: 24px;
            background: var(--card);
            border: 1px solid var(--border);
            transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 24px 60px rgba(37,99,235,.2);
            border-color: rgba(96,165,250,.4);
        }
        .feature-card:hover i {
            transform: scale(1.15) rotate(-5deg);
        }
        .feature-card i {
            transition: transform .3s ease;
        }

        .feature-card i {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            margin-bottom: 18px;
            background: linear-gradient(135deg,var(--primary),var(--secondary));
        }

        .testimonial {
            padding: 100px 0;
        }

        .testimonial-card {
            background: var(--card);
            padding: 40px;
            border-radius: 28px;
            border: 1px solid var(--border);
            text-align: center;
            max-width: 800px;
            margin: auto;
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .testimonial-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(37,99,235,.15);
        }

        .faq {
            padding: 100px 0;
        }

        .faq-item {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            margin-bottom: 18px;
            overflow: hidden;
            transition: border-color .3s ease, box-shadow .3s ease;
        }
        .faq-item:hover {
            border-color: rgba(96,165,250,.4);
            box-shadow: 0 8px 30px rgba(37,99,235,.12);
        }

        .faq-question {
            padding: 20px;
            cursor: pointer;
            font-weight: 700;
            transition: color .2s ease;
        }
        .faq-question:hover { color: #60a5fa; }

        .faq-answer {
            display: none;
            padding: 0 20px 20px;
            color: var(--muted);
            line-height: 1.7;
        }

        .newsletter {
            padding: 100px 0;
        }

        .newsletter-box {
            padding: 60px;
            border-radius: 32px;
            text-align: center;
            background: linear-gradient(135deg,#2563eb,#7c3aed);
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .newsletter-box h2,
        .newsletter-box p { color: #fff !important; }
        .newsletter-box:hover {
            transform: scale(1.01);
            box-shadow: 0 30px 80px rgba(37,99,235,.4);
        }

        .newsletter input {
            width: 100%;
            max-width: 420px;
            padding: 16px;
            border-radius: 14px;
            border: none;
            margin-top: 24px;
        }

        footer {
            padding: 40px 0;
            text-align: center;
            color: var(--muted);
        }

        @media(max-width: 900px) {
            .hero-grid,
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .hero {
                padding-top: 150px;
            }

            .hero-image {
                order: -1;
            }

            .floating-card {
                position: static;
                margin-top: 12px;
                animation: none !important;
                display: inline-block;
            }
            .floating-card-2, .floating-card-3 { display: none; }

            .nav-menu {
                gap: 10px;
            }

            .nav-menu a {
                display: none;
            }
        }
    </style>
</head>
<body>

<nav>
    <div class="container nav-inner">
        <div class="logo">
            <i class="fa-solid fa-book-open"></i>
            Perpustakaan Digital
        </div>

        <div class="nav-menu">
            <a href="#home">Beranda</a>
            <a href="#books">Buku</a>
            <a href="#features">Fitur</a>
            <a href="#faq">FAQ</a>

            @auth
                @if(Auth::user()->isAdmin())
                <a href="{{ route('dashboard') }}" style="background:linear-gradient(135deg,#2563eb,#7c3aed);padding:9px 18px;border-radius:12px;font-size:.9rem;">Dashboard</a>
                @else
                <a href="{{ route('member.dashboard') }}" style="background:linear-gradient(135deg,#2563eb,#7c3aed);padding:9px 18px;border-radius:12px;font-size:.9rem;">Dashboard</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="nav-btn-masuk">Masuk</a>
                <a href="{{ route('login') }}?tab=register" style="background:linear-gradient(135deg,#2563eb,#7c3aed);padding:9px 18px;border-radius:12px;font-size:.9rem;">Daftar</a>
            @endauth

            <button class="toggle-theme" id="themeToggle" title="Toggle tema">
                <i class="fa-solid fa-moon"></i>
            </button>
        </div>
    </div>
</nav>

<section class="hero" id="home">
    <div class="container hero-grid">

        <div class="hero-text">
            <h1>
                Baca Buku Lebih Mudah di
                <span>Perpustakaan Digital Modern</span>
            </h1>

            <p>
                Temukan ribuan koleksi buku terbaik, pinjam secara online,
                kelola riwayat bacaan, wishlist, dan nikmati pengalaman
                membaca yang modern, cepat, serta interaktif.
            </p>

            <div class="hero-buttons">
                <a href="{{ route('login') }}?tab=register" class="btn btn-primary">
                    <i class="fa-solid fa-rocket"></i> Mulai Sekarang
                </a>
                <a href="#books" class="btn btn-secondary">
                    <i class="fa-solid fa-book"></i> Jelajahi Buku
                </a>
            </div>
        </div>

        <div class="hero-image">
            @if(file_exists(public_path('assets/hero.png')))
                <img src="{{ asset('assets/hero.png') }}" alt="Perpustakaan Digital">
            @else
                <div style="width:100%;aspect-ratio:1536/1024;border-radius:32px;background:linear-gradient(135deg,rgba(37,99,235,.2),rgba(124,58,237,.2));display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;color:rgba(255,255,255,.4);">
                    <i class="fas fa-image" style="font-size:48px;"></i>
                    <span style="font-size:13px;">Letakkan gambar di: <code style="color:#60a5fa">public/assets/hero.png</code></span>
                </div>
            @endif

            <div class="floating-card floating-card-1">
                <h3 style="font-size:.95rem;">📚 {{ $totalBuku }}+ Buku</h3>
                <p style="margin-top:6px;color:#cbd5e1;font-size:.82rem;">Koleksi tersedia sekarang.</p>
            </div>
            <div class="floating-card floating-card-2">
                <h3 style="font-size:.95rem;">👥 {{ $totalMember }}+ Member</h3>
                <p style="margin-top:6px;color:#cbd5e1;font-size:.82rem;">Bergabung &amp; membaca bersama.</p>
            </div>
            <div class="floating-card floating-card-3">
                <h3 style="font-size:.95rem;">⚡ Akses 24/7</h3>
                <p style="margin-top:6px;color:#cbd5e1;font-size:.82rem;">Kapan saja, di mana saja.</p>
            </div>
        </div>

    </div>
</section>

<section class="stats">
    <div class="container stats-grid">
        <div class="stat-card">
            <h2>{{ $totalBuku }}+</h2>
            <p>Koleksi Buku</p>
        </div>
        <div class="stat-card">
            <h2>{{ $totalMember }}+</h2>
            <p>Member Terdaftar</p>
        </div>
        <div class="stat-card">
            <h2>{{ $totalPinjam }}+</h2>
            <p>Buku Dipinjam</p>
        </div>
        <div class="stat-card">
            <h2>24/7</h2>
            <p>Akses Online</p>
        </div>
    </div>
</section>

<section class="books" id="books">
    <div class="container">
        <div class="section-title">
            <h2>Koleksi Buku Populer</h2>
            <p>Arahkan kursor ke buku untuk melihat detail lengkapnya.</p>
        </div>

        @php
            $bukuAwal  = $bukuTerbaru->take(4);
            $bukuSisa  = $bukuTerbaru->skip(4);
        @endphp

        {{-- 4 buku awal --}}
        <div class="book-grid" id="book-grid-main">
            @forelse($bukuAwal as $buku)
            <div class="book-card">
                <div class="book-img-wrap">
                    @if($buku->cover)
                        <img src="{{ Storage::url($buku->cover) }}" alt="{{ $buku->judul }}" loading="lazy">
                    @else
                        <div class="book-placeholder">📚</div>
                    @endif
                    {{-- Overlay hover --}}
                    <div class="book-overlay">
                        <div class="ov-kategori">{{ $buku->kategori }}</div>
                        <div class="ov-title">{{ $buku->judul }}</div>
                        <div class="ov-author"><i class="fas fa-user-pen" style="margin-right:5px;"></i>{{ $buku->pengarang }}@if($buku->penerbit) &middot; {{ $buku->penerbit }}@endif</div>
                        @if($buku->sinopsis)
                        <div class="ov-sinopsis">{{ $buku->sinopsis }}</div>
                        @endif
                        <div class="ov-meta">
                            <span class="ov-stok {{ $buku->stok > 0 ? 'ada' : 'habis' }}">
                                <i class="fas fa-{{ $buku->stok > 0 ? 'check' : 'times' }}"></i>
                                {{ $buku->stok > 0 ? 'Tersedia ('.$buku->stok.')' : 'Stok Habis' }}
                            </span>
                            <span class="ov-dipinjam"><i class="fas fa-redo" style="margin-right:4px;"></i>{{ $buku->total_dipinjam }}x dipinjam</span>
                        </div>
                        <a href="{{ route('login') }}" class="ov-btn">
                            <i class="fas fa-hand-holding-heart"></i>
                            {{ $buku->stok > 0 ? 'Pinjam Sekarang' : 'Lihat Detail' }}
                        </a>
                    </div>
                </div>
                <div class="book-content">
                    <span class="badge">{{ $buku->kategori }}</span>
                    <h3 style="margin-top:8px;">{{ Str::limit($buku->judul, 28) }}</h3>
                    <div class="book-footer">
                        <small style="color:var(--muted);font-size:.8rem;">{{ $buku->pengarang }}</small>
                        <span style="font-size:.78rem;font-weight:700;color:{{ $buku->stok > 0 ? '#34d399' : '#f87171' }}">
                            {{ $buku->stok > 0 ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--muted)">
                <i class="fas fa-book-open" style="font-size:48px;opacity:.3;display:block;margin-bottom:12px;"></i>
                Belum ada buku tersedia.
            </div>
            @endforelse
        </div>

        {{-- Buku sisa (tersembunyi) --}}
        @if($bukuSisa->count() > 0)
        <div class="book-grid" id="extra-books" style="display:none;margin-top:24px;">
            @foreach($bukuSisa as $buku)
            <div class="book-card">
                <div class="book-img-wrap">
                    @if($buku->cover)
                        <img src="{{ Storage::url($buku->cover) }}" alt="{{ $buku->judul }}" loading="lazy">
                    @else
                        <div class="book-placeholder">📚</div>
                    @endif
                    <div class="book-overlay">
                        <div class="ov-kategori">{{ $buku->kategori }}</div>
                        <div class="ov-title">{{ $buku->judul }}</div>
                        <div class="ov-author"><i class="fas fa-user-pen" style="margin-right:5px;"></i>{{ $buku->pengarang }}@if($buku->penerbit) &middot; {{ $buku->penerbit }}@endif</div>
                        @if($buku->sinopsis)
                        <div class="ov-sinopsis">{{ $buku->sinopsis }}</div>
                        @endif
                        <div class="ov-meta">
                            <span class="ov-stok {{ $buku->stok > 0 ? 'ada' : 'habis' }}">
                                <i class="fas fa-{{ $buku->stok > 0 ? 'check' : 'times' }}"></i>
                                {{ $buku->stok > 0 ? 'Tersedia ('.$buku->stok.')' : 'Stok Habis' }}
                            </span>
                            <span class="ov-dipinjam"><i class="fas fa-redo" style="margin-right:4px;"></i>{{ $buku->total_dipinjam }}x dipinjam</span>
                        </div>
                        <a href="{{ route('login') }}" class="ov-btn">
                            <i class="fas fa-hand-holding-heart"></i>
                            {{ $buku->stok > 0 ? 'Pinjam Sekarang' : 'Lihat Detail' }}
                        </a>
                    </div>
                </div>
                <div class="book-content">
                    <span class="badge">{{ $buku->kategori }}</span>
                    <h3 style="margin-top:8px;">{{ Str::limit($buku->judul, 28) }}</h3>
                    <div class="book-footer">
                        <small style="color:var(--muted);font-size:.8rem;">{{ $buku->pengarang }}</small>
                        <span style="font-size:.78rem;font-weight:700;color:{{ $buku->stok > 0 ? '#34d399' : '#f87171' }}">
                            {{ $buku->stok > 0 ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div style="text-align:center;margin-top:44px;display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            @if($bukuSisa->count() > 0)
            <button onclick="toggleExtraBooks()" id="btn-lihat" class="btn-lihat">
                <i class="fas fa-chevron-down icon-chevron"></i>
                <span id="label-lihat">Lihat {{ $bukuSisa->count() }} Buku Lainnya</span>
            </button>
            @endif
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="fas fa-book-open"></i> Masuk &amp; Pinjam
            </a>
        </div>
    </div>
</section>

<section class="features" id="features">
    <div class="container">

        <div class="section-title">
            <h2>Fitur Unggulan</h2>
            <p>Sistem perpustakaan modern dengan pengalaman interaktif.</p>
        </div>

        <div class="feature-grid">
            <div class="feature-card">
                <i class="fa-solid fa-paper-plane" style="color:#fff;font-size:1.4rem;"></i>
                <h3>Peminjaman Online</h3>
                <p style="margin-top:12px;color:var(--muted);">Ajukan peminjaman buku kapan saja. Request diproses dan disetujui oleh petugas.</p>
            </div>
            <div class="feature-card">
                <i class="fa-solid fa-bell" style="color:#fff;font-size:1.4rem;"></i>
                <h3>Notifikasi Otomatis</h3>
                <p style="margin-top:12px;color:var(--muted);">Pengingat jatuh tempo H-2 dan hari H agar tidak terlambat mengembalikan buku.</p>
            </div>
            <div class="feature-card">
                <i class="fa-solid fa-shield-halved" style="color:#fff;font-size:1.4rem;"></i>
                <h3>Sistem Approval</h3>
                <p style="margin-top:12px;color:var(--muted);">Setiap peminjaman melalui proses persetujuan admin untuk keamanan koleksi buku.</p>
            </div>
            <div class="feature-card">
                <i class="fa-solid fa-chart-line" style="color:#fff;font-size:1.4rem;"></i>
                <h3>Riwayat Lengkap</h3>
                <p style="margin-top:12px;color:var(--muted);">Pantau semua riwayat peminjaman, status, dan denda secara real-time di dashboard.</p>
            </div>
        </div>

    </div>
</section>

    <div class="container">

        <div class="section-title" id="faq">
            <h2>FAQ</h2>
            <p>Pertanyaan yang sering ditanyakan.</p>
        </div>

        <div class="faq-item">
            <div class="faq-question"><i class="fas fa-chevron-right" style="margin-right:10px;font-size:.8rem;"></i>Bagaimana cara meminjam buku?</div>
            <div class="faq-answer">Daftar akun, login, lalu pilih buku di katalog dan klik "Pinjam". Request akan diproses oleh petugas dan kamu akan mendapat notifikasi setelah disetujui.</div>
        </div>
        <div class="faq-item">
            <div class="faq-question"><i class="fas fa-chevron-right" style="margin-right:10px;font-size:.8rem;"></i>Berapa lama durasi peminjaman?</div>
            <div class="faq-answer">Default 7 hari, maksimal 30 hari. Kamu bisa memilih durasi saat mengajukan peminjaman.</div>
        </div>
        <div class="faq-item">
            <div class="faq-question"><i class="fas fa-chevron-right" style="margin-right:10px;font-size:.8rem;"></i>Apa yang terjadi jika terlambat mengembalikan?</div>
            <div class="faq-answer">Akun akan mendapat poin pelanggaran. Terlambat 1–7 hari: tidak bisa pinjam baru. 8–14 hari: perlu approval manual. Lebih dari 14 hari: akun disuspend sementara.</div>
        </div>
        <div class="faq-item">
            <div class="faq-question"><i class="fas fa-chevron-right" style="margin-right:10px;font-size:.8rem;"></i>Apakah layanan ini gratis?</div>
            <div class="faq-answer">Ya, sepenuhnya gratis. Daftar dan mulai meminjam buku tanpa biaya apapun.</div>
        </div>

    </div>
</section>

<section class="newsletter">
    <div class="container">
        <div class="newsletter-box">
            <h2>Siap Mulai Membaca?</h2>
            <p style="margin-top:14px;opacity:.9;">Daftar sekarang dan nikmati kemudahan meminjam buku secara digital — gratis!</p>
            <div style="margin-top:28px;display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('login') }}?tab=register" class="btn" style="background:#fff;color:#2563eb;font-weight:800;">
                    <i class="fas fa-rocket"></i> Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3);">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </a>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="testimonial-card" style="margin-bottom:32px;">
            <div style="font-size:3rem;margin-bottom:16px;opacity:.4;line-height:1;">&ldquo;</div>
            <h2 style="font-size:clamp(1.3rem,2.5vw,1.8rem);font-weight:700;line-height:1.5;">
                Membaca adalah meminjam dari pikiran orang lain untuk memperkaya pikiran kita sendiri.
            </h2>
            <p style="margin-top:18px;color:var(--muted);font-size:.95rem;">
                &mdash; <strong style="color:var(--text);">Georg Christoph Lichtenberg</strong>, Filsuf &amp; Penulis Jerman
            </p>
        </div>
        <p>&copy; {{ date('Y') }} <strong style="color:#60a5fa;">Perpustakaan Digital</strong> &mdash; Sistem Peminjaman Buku Modern.</p>
        @auth
        <p style="margin-top:8px;font-size:.85rem;">
            <a href="{{ Auth::user()->isAdmin() ? route('dashboard') : route('member.dashboard') }}" style="color:#60a5fa;">Ke Dashboard</a>
        </p>
        @endauth
    </div>
</footer>

<script>
    // Theme toggle dengan localStorage
    const themeToggle = document.getElementById('themeToggle');
    const savedTheme = localStorage.getItem('landing-theme') || 'dark';
    if (savedTheme === 'light') {
        document.body.classList.add('light');
        themeToggle.innerHTML = '<i class="fa-solid fa-sun"></i>';
    }
    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('light');
        const isLight = document.body.classList.contains('light');
        themeToggle.innerHTML = isLight ? '<i class="fa-solid fa-sun"></i>' : '<i class="fa-solid fa-moon"></i>';
        localStorage.setItem('landing-theme', isLight ? 'light' : 'dark');
    });

    // FAQ accordion
    document.querySelectorAll('.faq-question').forEach(item => {
        item.addEventListener('click', () => {
            const answer = item.nextElementSibling;
            const isOpen = answer.style.display === 'block';
            document.querySelectorAll('.faq-answer').forEach(a => a.style.display = 'none');
            answer.style.display = isOpen ? 'none' : 'block';
        });
    });

    // Toggle extra books
    let booksExpanded = false;
    function toggleExtraBooks() {
        booksExpanded = !booksExpanded;
        const extra = document.getElementById('extra-books');
        const btn   = document.getElementById('btn-lihat');
        const label = document.getElementById('label-lihat');
        const total = {{ $bukuSisa->count() ?? 0 }};

        if (booksExpanded) {
            extra.style.display = 'grid';
            // Animasi tiap card satu per satu
            const cards = extra.querySelectorAll('.book-card');
            cards.forEach((c, i) => {
                c.style.opacity = '0';
                c.style.transform = 'translateY(28px)';
                c.style.transition = 'none';
                setTimeout(() => {
                    c.style.transition = 'opacity .4s ease, transform .4s ease';
                    c.style.opacity = '1';
                    c.style.transform = 'translateY(0)';
                }, i * 80);
            });
            btn.classList.add('expanded');
            label.textContent = 'Sembunyikan';
            // Scroll ke extra books
            setTimeout(() => extra.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
        } else {
            extra.style.transition = 'opacity .3s ease';
            extra.style.opacity = '0';
            setTimeout(() => { extra.style.display = 'none'; extra.style.opacity = ''; }, 320);
            btn.classList.remove('expanded');
            label.textContent = 'Lihat ' + total + ' Buku Lainnya';
        }
    }

    // Scroll reveal — animasi muncul setiap kali elemen masuk & keluar viewport
    const revealEls = document.querySelectorAll('.stat-card, .book-card, .feature-card, .faq-item');
    revealEls.forEach(el => {
        el.style.transition = 'opacity .5s ease, transform .5s ease';
    });
    function setHidden(el) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(24px)';
    }
    function setVisible(el) {
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
    }
    revealEls.forEach(setHidden);
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) setVisible(e.target);
            else setHidden(e.target);
        });
    }, { threshold: 0.1 });
    revealEls.forEach(el => observer.observe(el));
</script>

</body>
</html>

