<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Member') — Perpustakaan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

        :root{--p1:#6366F1;--p2:#818CF8;--s1:#10B981;--red:#EF4444;--acc:#F59E0B;--sidebar-w:240px;--radius:14px;}

        [data-theme="light"]{
            --bg:#F1F5F9;--sidebar-bg:#fff;--card-bg:#fff;--card-border:#E2E8F0;
            --topbar-bg:rgba(255,255,255,.95);--text:#0F172A;--text2:#475569;--text3:#94A3B8;
            --border:#E2E8F0;--input-bg:#F8FAFC;--hover-bg:#F1F5F9;--shadow:0 2px 12px rgba(0,0,0,.06);
        }
        [data-theme="dark"]{
            --bg:#071022;--sidebar-bg:#0F172A;--card-bg:rgba(255,255,255,.06);--card-border:rgba(255,255,255,.10);
            --topbar-bg:rgba(15,23,42,.9);--text:#E7EAF0;--text2:#94A3B8;--text3:#475569;
            --border:rgba(255,255,255,.10);--input-bg:rgba(255,255,255,.06);--hover-bg:rgba(255,255,255,.05);--shadow:0 4px 24px rgba(0,0,0,.3);
        }

        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh;transition:background .25s,color .25s;}

        /* SIDEBAR */
        .sidebar{width:var(--sidebar-w);background:var(--sidebar-bg);border-right:1px solid var(--border);min-height:100vh;position:fixed;top:0;left:0;display:flex;flex-direction:column;z-index:100;transition:background .25s,border-color .25s;}
        .sidebar-brand{padding:20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;}
        .brand-icon{width:36px;height:36px;background:var(--p1);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;}
        .brand-text h1{font-size:15px;font-weight:700;color:var(--text);}
        .brand-text p{font-size:11px;color:var(--text2);}

        .sidebar-nav{padding:14px 12px;flex:1;}
        .nav-label{font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:1px;padding:8px 8px 4px;}
        .nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:var(--text2);text-decoration:none;font-size:14px;font-weight:500;transition:all .2s;margin-bottom:2px;}
        .nav-item:hover{background:rgba(99,102,241,.1);color:var(--p1);}
        .nav-item.active{background:var(--p1);color:#fff;}
        .nav-item i{width:16px;text-align:center;}

        .sidebar-footer{padding:14px 12px;border-top:1px solid var(--border);}
        .user-card{background:var(--hover-bg);border-radius:12px;padding:12px;margin-bottom:10px;display:flex;align-items:center;gap:10px;}
        .user-avatar{width:36px;height:36px;background:var(--p1);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;}
        .user-name{font-size:13px;font-weight:600;color:var(--text);}
        .user-level{font-size:11px;color:var(--text2);}
        .btn-logout{display:flex;align-items:center;gap:8px;width:100%;padding:10px 12px;border-radius:10px;background:rgba(239,68,68,.1);color:var(--red);border:none;cursor:pointer;font-size:13px;font-weight:500;transition:all .2s;}
        .btn-logout:hover{background:rgba(239,68,68,.18);}

        /* MAIN */
        .main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;}
        .topbar{background:var(--topbar-bg);padding:14px 28px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;backdrop-filter:blur(14px);}
        .topbar h2{font-size:18px;font-weight:600;color:var(--text);}
        .topbar .sub{font-size:13px;color:var(--text2);margin-top:2px;}
        .topbar-right{display:flex;align-items:center;gap:10px;}

        .theme-btn{width:36px;height:36px;border-radius:9px;background:var(--card-bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text2);font-size:14px;transition:all .2s;}
        .theme-btn:hover{background:var(--hover-bg);color:var(--text);}

        /* Notif badge */
        .notif-wrap{position:relative;}
        .notif-badge{position:absolute;top:-4px;right:-4px;background:var(--red);color:#fff;font-size:9px;font-weight:700;padding:1px 5px;border-radius:999px;min-width:16px;text-align:center;}

        .content{padding:24px 28px;flex:1;animation:fadeUp .3s ease both;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}

        /* CARDS */
        .card{background:var(--card-bg);border-radius:var(--radius);border:1px solid var(--card-border);overflow:visible;box-shadow:var(--shadow);transition:background .25s,border-color .25s;}
        .card-header{overflow:visible;}
        .card-header{padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;border-radius:var(--radius) var(--radius) 0 0;}
        .card-header h3{font-size:15px;font-weight:600;color:var(--text);}
        .card-body{padding:22px;}

        /* STATS */
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:22px;}
        .stat-card{background:var(--card-bg);border-radius:var(--radius);padding:18px;border:1px solid var(--card-border);display:flex;align-items:center;gap:14px;transition:transform .2s,box-shadow .2s;box-shadow:var(--shadow);}
        .stat-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.1);}
        .stat-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
        .si-purple{background:rgba(99,102,241,.12);color:var(--p1);}
        .si-orange{background:rgba(245,158,11,.12);color:var(--acc);}
        .si-green{background:rgba(16,185,129,.12);color:var(--s1);}
        .si-red{background:rgba(239,68,68,.12);color:var(--red);}
        .stat-value{font-size:26px;font-weight:700;line-height:1;color:var(--text);}
        .stat-label{font-size:12px;color:var(--text2);margin-top:3px;}

        /* TABLE */
        .table-wrap{overflow-x:auto;}
        table{width:100%;border-collapse:collapse;font-size:14px;}
        thead th{background:var(--hover-bg);padding:11px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.5px;border-bottom:1px solid var(--border);}
        tbody td{padding:12px 16px;border-bottom:1px solid var(--border);vertical-align:middle;color:var(--text);}
        tbody tr:last-child td{border-bottom:none;}
        tbody tr:hover{background:var(--hover-bg);}

        /* BADGES */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;}
        .badge-success{background:rgba(16,185,129,.12);color:#10B981;}
        .badge-warning{background:rgba(245,158,11,.12);color:#F59E0B;}
        .badge-danger{background:rgba(239,68,68,.12);color:#EF4444;}
        .badge-info{background:rgba(59,130,246,.12);color:#3B82F6;}
        .badge-primary{background:rgba(99,102,241,.12);color:var(--p1);}
        .badge-secondary{background:rgba(100,116,139,.12);color:#64748B;}

        /* BUTTONS */
        .btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;font-size:13px;font-weight:500;border:none;cursor:pointer;text-decoration:none;transition:all .2s;}
        .btn-primary{background:var(--p1);color:#fff;}
        .btn-primary:hover{background:#4F46E5;transform:translateY(-1px);}
        .btn-success{background:var(--s1);color:#fff;}
        .btn-success:hover{transform:translateY(-1px);}
        .btn-danger{background:var(--red);color:#fff;}
        .btn-danger:hover{transform:translateY(-1px);}
        .btn-secondary{background:var(--hover-bg);color:var(--text);border:1px solid var(--border);}
        .btn-secondary:hover{background:var(--border);}
        .btn-sm{padding:6px 12px;font-size:12px;border-radius:8px;}

        /* FORMS */
        .form-group{margin-bottom:18px;}
        .form-label{display:block;font-size:13px;font-weight:500;margin-bottom:6px;color:var(--text2);}
        .form-control{width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-family:inherit;background:var(--input-bg);color:var(--text);transition:all .2s;}
        .form-control:focus{outline:none;border-color:var(--p1);box-shadow:0 0 0 3px rgba(99,102,241,.1);}
        .form-control.is-invalid{border-color:var(--red);}
        .invalid-feedback{font-size:12px;color:var(--red);margin-top:4px;}
        select.form-control option{background:var(--bg);color:var(--text);}

        /* ALERTS / TOAST */
        .toast-container{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;pointer-events:none;}
        .toast{
            display:flex;align-items:flex-start;gap:12px;
            padding:14px 16px;border-radius:14px;
            min-width:300px;max-width:420px;
            box-shadow:0 8px 32px rgba(0,0,0,.22),0 2px 8px rgba(0,0,0,.12);
            backdrop-filter:blur(16px);
            pointer-events:auto;
            animation:toastIn .35s cubic-bezier(.34,1.56,.64,1) both;
            position:relative;overflow:hidden;
            border:1px solid transparent;
        }
        .toast.hiding{animation:toastOut .3s ease forwards;}
        .toast-success{background:rgba(16,185,129,.15);border-color:rgba(16,185,129,.3);}
        .toast-danger {background:rgba(239,68,68,.15); border-color:rgba(239,68,68,.3);}
        .toast-warning{background:rgba(245,158,11,.15);border-color:rgba(245,158,11,.3);}
        .toast-icon{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;}
        .toast-success .toast-icon{background:rgba(16,185,129,.2);color:#10B981;}
        .toast-danger  .toast-icon{background:rgba(239,68,68,.2); color:#EF4444;}
        .toast-warning .toast-icon{background:rgba(245,158,11,.2);color:#F59E0B;}
        .toast-body{flex:1;min-width:0;}
        .toast-title{font-size:13px;font-weight:700;margin-bottom:2px;}
        .toast-success .toast-title{color:#10B981;}
        .toast-danger  .toast-title{color:#EF4444;}
        .toast-warning .toast-title{color:#F59E0B;}
        .toast-msg{font-size:12.5px;color:var(--text2);line-height:1.5;}
        .toast-close{background:none;border:none;cursor:pointer;color:var(--text3);font-size:13px;padding:2px;flex-shrink:0;margin-top:1px;transition:color .15s;}
        .toast-close:hover{color:var(--text);}
        .toast-progress{position:absolute;bottom:0;left:0;height:3px;border-radius:0 0 14px 14px;animation:toastProgress 4s linear forwards;}
        .toast-success .toast-progress{background:linear-gradient(90deg,#10B981,#34D399);}
        .toast-danger  .toast-progress{background:linear-gradient(90deg,#EF4444,#F87171);}
        .toast-warning .toast-progress{background:linear-gradient(90deg,#F59E0B,#FCD34D);}
        @keyframes toastIn{from{opacity:0;transform:translateX(60px) scale(.92);}to{opacity:1;transform:translateX(0) scale(1);}}
        @keyframes toastOut{from{opacity:1;transform:translateX(0) scale(1);}to{opacity:0;transform:translateX(60px) scale(.92);}}
        @keyframes toastProgress{from{width:100%;}to{width:0%;}}

        /* PAGINATION */
        .pagination{display:flex;gap:4px;justify-content:center;padding:16px 0 0;}
        .pagination .page-link{padding:7px 13px;border-radius:8px;font-size:13px;color:var(--text2);text-decoration:none;border:1px solid var(--border);background:var(--card-bg);transition:all .2s;}
        .pagination .page-link:hover{background:var(--p1);color:#fff;border-color:var(--p1);}
        .pagination .page-item.active .page-link{background:var(--p1);color:#fff;border-color:var(--p1);}
        .pagination .page-item.disabled .page-link{opacity:.4;pointer-events:none;}

        /* BUKU GRID */
        .buku-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;}
        .buku-card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius);padding:18px;text-align:center;transition:all .25s;cursor:pointer;}
        .buku-card:hover{transform:translateY(-4px);box-shadow:0 12px 28px rgba(0,0,0,.1);border-color:rgba(99,102,241,.3);}
        .buku-cover{width:70px;height:90px;background:linear-gradient(135deg,var(--p1),var(--p2));border-radius:8px;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;overflow:hidden;}
        .buku-cover img{width:100%;height:100%;object-fit:cover;}
        .buku-card h4{font-size:13px;font-weight:600;margin-bottom:4px;line-height:1.4;color:var(--text);}
        .buku-card .author{font-size:12px;color:var(--text2);margin-bottom:8px;}

        /* CUSTOM SELECT */
        .cs-wrap{position:relative;display:inline-block;min-width:140px;}
        .cs-trigger{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:8px 12px;border-radius:10px;background:var(--input-bg);border:1.5px solid var(--border);color:var(--text);font-size:13px;font-family:'Inter',sans-serif;cursor:pointer;user-select:none;transition:border-color .2s,box-shadow .2s,background .2s;white-space:nowrap;}
        .cs-trigger:hover{border-color:var(--p1);background:var(--card-bg);}
        .cs-trigger.open{border-color:var(--p1);box-shadow:0 0 0 3px rgba(99,102,241,.1);background:var(--card-bg);}
        .cs-trigger .cs-arrow{font-size:10px;color:var(--text3);transition:transform .25s cubic-bezier(.4,0,.2,1);flex-shrink:0;}
        .cs-trigger.open .cs-arrow{transform:rotate(180deg);}
        .cs-trigger .cs-val{flex:1;overflow:hidden;text-overflow:ellipsis;}
        .cs-trigger .cs-icon{color:var(--p1);font-size:12px;flex-shrink:0;}
        .cs-dropdown{position:absolute;top:calc(100% + 6px);left:0;min-width:100%;z-index:300;background:var(--card-bg);border:1.5px solid var(--border);border-radius:12px;overflow:hidden;box-shadow:0 16px 48px rgba(0,0,0,.18);opacity:0;transform:translateY(-8px) scale(.97);pointer-events:none;transition:opacity .2s cubic-bezier(.4,0,.2,1),transform .2s cubic-bezier(.4,0,.2,1);backdrop-filter:blur(12px);}
        .cs-dropdown.open{opacity:1;transform:translateY(0) scale(1);pointer-events:auto;}
        .cs-search-wrap{padding:8px 8px 4px;position:relative;}
        .cs-search{width:100%;padding:7px 10px 7px 30px;border:1.5px solid var(--border);border-radius:8px;background:var(--input-bg);color:var(--text);font-size:12px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s;}
        .cs-search:focus{border-color:var(--p1);}
        .cs-search-icon{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:var(--text3);font-size:11px;pointer-events:none;}
        .cs-list{max-height:220px;overflow-y:auto;padding:4px;}
        .cs-list::-webkit-scrollbar{width:4px;}
        .cs-list::-webkit-scrollbar-thumb{background:var(--border);border-radius:4px;}
        .cs-option{display:flex;align-items:center;gap:8px;padding:8px 10px;border-radius:8px;font-size:13px;color:var(--text);cursor:pointer;transition:background .15s;white-space:nowrap;}
        .cs-option:hover{background:var(--hover-bg);}
        .cs-option.selected{background:rgba(99,102,241,.1);color:var(--p1);font-weight:600;}
        .cs-option .cs-check{margin-left:auto;font-size:11px;color:var(--p1);opacity:0;transition:opacity .15s;}
        .cs-option.selected .cs-check{opacity:1;}
        .cs-option .cs-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}

        /* EMPTY */
        .empty-state{text-align:center;padding:48px 24px;color:var(--text2);}
        .empty-state i{font-size:48px;opacity:.3;margin-bottom:12px;display:block;}

        /* MODAL */
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:500;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .25s;}
        .modal-overlay.open{opacity:1;pointer-events:auto;}
        .modal{background:var(--card-bg);border:1px solid var(--card-border);border-radius:18px;padding:28px;width:100%;max-width:460px;box-shadow:0 24px 80px rgba(0,0,0,.3);transform:translateY(20px);transition:transform .25s;}
        .modal-overlay.open .modal{transform:translateY(0);}
        .modal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
        .modal-header h3{font-size:16px;font-weight:700;color:var(--text);}
        .modal-close{background:none;border:none;cursor:pointer;color:var(--text2);font-size:18px;}
        .modal-footer{display:flex;gap:10px;justify-content:flex-end;margin-top:18px;}

        @keyframes fadeDown{from{opacity:0;transform:translateY(-10px);}to{opacity:1;transform:translateY(0);}}
        .animate-fade{animation:fadeUp .4s ease both;}

        @media(max-width:768px){
            .sidebar{transform:translateX(-100%);}
            .main{margin-left:0;}
            .stats-grid{grid-template-columns:1fr 1fr;}
        }
    </style>
    @stack('styles')
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fas fa-book-open"></i></div>
        <div class="brand-text">
            <h1>Perpustakaan</h1>
            <p>Portal Member</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu</div>
        <a href="{{ route('member.dashboard') }}" class="nav-item {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('member.katalog') }}" class="nav-item {{ request()->routeIs('member.katalog','member.detail_buku') ? 'active' : '' }}">
            <i class="fas fa-book"></i> Katalog Buku
        </a>
        <a href="{{ route('member.riwayat') }}" class="nav-item {{ request()->routeIs('member.riwayat') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Riwayat Pinjam
        </a>
        <a href="{{ route('member.wishlist') }}" class="nav-item {{ request()->routeIs('member.wishlist') ? 'active' : '' }}" style="position:relative;">
            <i class="fas fa-heart"></i> Wishlist
            @php $wlCount = Auth::user()->wishlists()->count(); @endphp
            @if($wlCount > 0)
            <span class="wl-count-badge" style="margin-left:auto;background:var(--red);color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:999px;min-width:18px;text-align:center;">{{ $wlCount }}</span>
            @else
            <span class="wl-count-badge" style="display:none;margin-left:auto;background:var(--red);color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:999px;"></span>
            @endif
        </a>
        <div class="nav-label" style="margin-top:8px;">Lainnya</div>
        <a href="{{ route('landing') }}" class="nav-item">
            <i class="fas fa-globe"></i> Beranda
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ Str::limit(Auth::user()->nama_lengkap, 16) }}</div>
                <div class="user-level">
                    @if(Auth::user()->isSuspended())
                        <span style="color:var(--red)"><i class="fas fa-ban" style="font-size:9px"></i> Suspended</span>
                    @else
                        <i class="fas fa-circle" style="font-size:7px;color:#10b981"></i> Member Aktif
                    @endif
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logout-form-member">
            @csrf
            <button type="button" class="btn-logout" onclick="confirmLogout('logout-form-member')">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div>
            <h2>@yield('page-title','Dashboard')</h2>
            <div class="sub">@yield('breadcrumb','Member')</div>
        </div>
        <div class="topbar-right">
            <span style="font-size:12px;color:var(--text2)">
                <i class="fas fa-calendar-alt"></i>
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM Y') }}
            </span>
            <button class="theme-btn" onclick="toggleTheme()" id="theme-btn"><i class="fas fa-moon"></i></button>
        </div>
    </div>

    @if(Auth::user()->isSuspended())
    <div style="background:rgba(239,68,68,.1);border-bottom:1px solid rgba(239,68,68,.2);padding:10px 28px;display:flex;align-items:center;gap:8px;font-size:13px;color:#EF4444;">
        <i class="fas fa-ban"></i>
        <strong>Akun Anda ditangguhkan.</strong> {{ Auth::user()->suspend_reason }}
    </div>
    @endif

    <div class="content">
        @yield('content')
    </div>
</div>

{{-- Toast Container --}}
<div class="toast-container" id="toast-container">
@if(session('success'))
    <div class="toast toast-success" id="toast-s">
        <div class="toast-icon"><i class="fas fa-check"></i></div>
        <div class="toast-body">
            <div class="toast-title">Berhasil</div>
            <div class="toast-msg">{{ session('success') }}</div>
        </div>
        <button class="toast-close" onclick="dismissToast('toast-s')"><i class="fas fa-times"></i></button>
        <div class="toast-progress"></div>
    </div>
@endif
@if(session('error'))
    <div class="toast toast-danger" id="toast-e">
        <div class="toast-icon"><i class="fas fa-exclamation"></i></div>
        <div class="toast-body">
            <div class="toast-title">Gagal</div>
            <div class="toast-msg">{{ session('error') }}</div>
        </div>
        <button class="toast-close" onclick="dismissToast('toast-e')"><i class="fas fa-times"></i></button>
        <div class="toast-progress"></div>
    </div>
@endif
@if($errors->any())
    <div class="toast toast-danger" id="toast-v">
        <div class="toast-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="toast-body">
            <div class="toast-title">Validasi Gagal</div>
            <div class="toast-msg">{{ $errors->first() }}</div>
        </div>
        <button class="toast-close" onclick="dismissToast('toast-v')"><i class="fas fa-times"></i></button>
        <div class="toast-progress"></div>
    </div>
@endif
</div>

@stack('scripts')
<script>
const html = document.documentElement;
const themeBtn = document.getElementById('theme-btn');
function applyTheme(t){html.setAttribute('data-theme',t);localStorage.setItem('theme',t);themeBtn.innerHTML=t==='dark'?'<i class="fas fa-sun"></i>':'<i class="fas fa-moon"></i>';}
function toggleTheme(){applyTheme(html.getAttribute('data-theme')==='dark'?'light':'dark');}
applyTheme(localStorage.getItem('theme')||'light');
function confirmLogout(id){if(confirm('Yakin ingin keluar?'))document.getElementById(id).submit();}
function openModal(id){document.getElementById(id).classList.add('open');}
function closeModal(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal-overlay').forEach(m=>{m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open');});});
function dismissToast(id){const t=document.getElementById(id);if(!t)return;t.classList.add('hiding');setTimeout(()=>t.remove(),300);}
setTimeout(()=>{document.querySelectorAll('.toast').forEach(t=>{t.classList.add('hiding');setTimeout(()=>t.remove(),300);});},4500);

// Custom Select
function initCustomSelects(){
    document.querySelectorAll('.cs-wrap:not([data-init])').forEach(wrap=>{
        wrap.setAttribute('data-init','1');
        const trigger=wrap.querySelector('.cs-trigger');
        const dropdown=wrap.querySelector('.cs-dropdown');
        const valEl=wrap.querySelector('.cs-val');
        const hiddenInput=wrap.querySelector('input[type=hidden]');
        const searchInput=wrap.querySelector('.cs-search');
        const options=wrap.querySelectorAll('.cs-option');
        const form=wrap.closest('form');
        trigger.addEventListener('click',e=>{
            e.stopPropagation();
            const isOpen=dropdown.classList.contains('open');
            document.querySelectorAll('.cs-dropdown.open').forEach(d=>{
                d.classList.remove('open');
                d.closest('.cs-wrap').querySelector('.cs-trigger').classList.remove('open');
            });
            if(!isOpen){
                dropdown.classList.add('open');
                trigger.classList.add('open');
                if(searchInput){searchInput.value='';filterOptions('');searchInput.focus();}
            }
        });
        if(searchInput){
            searchInput.addEventListener('input',()=>filterOptions(searchInput.value));
            searchInput.addEventListener('click',e=>e.stopPropagation());
        }
        function filterOptions(q){
            const lq=q.toLowerCase();
            options.forEach(opt=>{opt.style.display=opt.dataset.label.toLowerCase().includes(lq)?'':'none';});
        }
        options.forEach(opt=>{
            opt.addEventListener('click',e=>{
                e.stopPropagation();
                options.forEach(o=>o.classList.remove('selected'));
                opt.classList.add('selected');
                valEl.textContent=opt.dataset.label;
                if(hiddenInput)hiddenInput.value=opt.dataset.value;
                dropdown.classList.remove('open');
                trigger.classList.remove('open');
                if(form){trigger.style.opacity='.6';trigger.style.pointerEvents='none';form.submit();}
            });
        });
    });
    document.addEventListener('click',()=>{
        document.querySelectorAll('.cs-dropdown.open').forEach(d=>{
            d.classList.remove('open');
            d.closest('.cs-wrap').querySelector('.cs-trigger').classList.remove('open');
        });
    });
}
document.addEventListener('DOMContentLoaded',initCustomSelects);
initCustomSelects();
</script>
</body>
</html>
