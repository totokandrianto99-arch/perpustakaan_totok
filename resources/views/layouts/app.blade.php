<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Perpustakaan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

        /* ── TOKENS ── */
        :root {
            --p1:#2543EB;--p2:#3B82F6;--p3:#60A5FA;
            --s1:#10B981;--s2:#34D399;
            --acc1:#F59E0B;--acc2:#F97316;
            --red:#EF4444;
            --sidebar-w:256px;--radius:14px;
            --transition:.22s cubic-bezier(.4,0,.2,1);
        }

        /* DARK */
        [data-theme="dark"] {
            --bg:        #071022;
            --bg2:       #0f1a33;
            --sidebar-bg:#0F172A;
            --card-bg:   rgba(255,255,255,.06);
            --card-border:rgba(255,255,255,.10);
            --topbar-bg: rgba(15,23,42,.85);
            --text:      #E7EAF0;
            --text2:     #94A3B8;
            --text3:     #64748B;
            --border:    rgba(255,255,255,.10);
            --input-bg:  rgba(255,255,255,.06);
            --hover-bg:  rgba(255,255,255,.06);
            --shadow:    0 4px 24px rgba(0,0,0,.4);
        }

        /* LIGHT */
        [data-theme="light"] {
            --bg:        #F1F5F9;
            --bg2:       #E2E8F0;
            --sidebar-bg:#1E293B;
            --card-bg:   #FFFFFF;
            --card-border:#E2E8F0;
            --topbar-bg: rgba(255,255,255,.92);
            --text:      #0F172A;
            --text2:     #475569;
            --text3:     #94A3B8;
            --border:    #E2E8F0;
            --input-bg:  #F8FAFC;
            --hover-bg:  #F1F5F9;
            --shadow:    0 4px 24px rgba(0,0,0,.08);
        }

        body {
            font-family:'Inter',sans-serif;
            min-height:100vh;
            display:flex;
            background:var(--bg);
            color:var(--text);
            transition:background var(--transition), color var(--transition);
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width:var(--sidebar-w);
            background:var(--sidebar-bg);
            min-height:100vh;
            position:fixed;top:0;left:0;
            display:flex;flex-direction:column;
            z-index:200;
            box-shadow:4px 0 24px rgba(0,0,0,.2);
            transition:background var(--transition);
        }

        .sidebar-brand {
            padding:22px 20px 18px;
            border-bottom:1px solid rgba(255,255,255,.07);
        }
        .brand-row{display:flex;align-items:center;gap:12px;}
        .brand-logo {
            width:40px;height:40px;
            background:linear-gradient(135deg,var(--p1),var(--p2));
            border-radius:12px;
            display:flex;align-items:center;justify-content:center;
            font-size:18px;color:#fff;
            box-shadow:0 4px 12px rgba(37,67,235,.4);
            flex-shrink:0;
        }
        .brand-text .name{font-family:'Poppins',sans-serif;font-size:15px;font-weight:700;color:#fff;}
        .brand-text .sub{font-size:11px;color:#64748B;margin-top:1px;}

        .sidebar-nav{padding:14px 12px;flex:1;overflow-y:auto;}
        .nav-section{font-size:10px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:1.2px;padding:12px 10px 6px;}

        .nav-item {
            display:flex;align-items:center;gap:11px;
            padding:10px 12px;border-radius:10px;
            color:#94A3B8;text-decoration:none;
            font-size:13.5px;font-weight:500;
            transition:all var(--transition);
            margin-bottom:2px;position:relative;
        }
        .nav-item .nav-icon {
            width:32px;height:32px;border-radius:8px;
            display:flex;align-items:center;justify-content:center;
            font-size:14px;flex-shrink:0;
            background:rgba(255,255,255,.04);
            transition:all var(--transition);
        }
        .nav-item .badge-count {
            margin-left:auto;
            background:var(--red);color:#fff;
            font-size:10px;font-weight:700;
            padding:2px 6px;border-radius:999px;
            min-width:18px;text-align:center;
        }
        .nav-item:hover{color:#fff;background:rgba(255,255,255,.07);}
        .nav-item:hover .nav-icon{background:rgba(37,67,235,.3);color:var(--p3);}
        .nav-item.active{color:#fff;background:linear-gradient(135deg,rgba(37,67,235,.3),rgba(59,130,246,.18));}
        .nav-item.active .nav-icon{background:linear-gradient(135deg,var(--p1),var(--p2));color:#fff;box-shadow:0 4px 10px rgba(37,67,235,.4);}
        .nav-item.active::before{content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);width:3px;height:60%;background:var(--p2);border-radius:0 3px 3px 0;}

        .sidebar-footer{padding:14px 12px;border-top:1px solid rgba(255,255,255,.07);}
        .user-pill{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:12px;background:rgba(255,255,255,.05);margin-bottom:8px;}
        .user-ava{width:34px;height:34px;background:linear-gradient(135deg,var(--p1),var(--s1));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;}
        .user-pill .uname{font-size:13px;font-weight:600;color:#fff;}
        .user-pill .urole{font-size:11px;color:#64748B;}
        .btn-logout{display:flex;align-items:center;gap:8px;width:100%;padding:10px 14px;border-radius:10px;background:rgba(239,68,68,.1);color:#F87171;border:none;cursor:pointer;font-size:13px;font-weight:500;font-family:'Inter',sans-serif;transition:all .2s;}
        .btn-logout:hover{background:rgba(239,68,68,.2);}

        /* ── MAIN ── */
        .main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh;}

        .topbar {
            background:var(--topbar-bg);
            padding:13px 28px;
            border-bottom:1px solid var(--border);
            display:flex;align-items:center;justify-content:space-between;
            position:sticky;top:0;z-index:100;
            backdrop-filter:blur(16px);
            transition:background var(--transition),border-color var(--transition);
        }
        .topbar-left h2{font-family:'Poppins',sans-serif;font-size:17px;font-weight:700;}
        .topbar-left .crumb{font-size:12px;color:var(--text2);margin-top:2px;}
        .topbar-right{display:flex;align-items:center;gap:10px;}

        .date-chip{display:flex;align-items:center;gap:6px;padding:7px 14px;border-radius:20px;background:var(--card-bg);border:1px solid var(--border);font-size:12px;color:var(--text2);}

        /* Notif bell */
        .notif-btn{position:relative;width:38px;height:38px;border-radius:10px;background:var(--card-bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text2);transition:all .2s;}
        .notif-btn:hover{background:var(--hover-bg);color:var(--text);}
        .notif-dot{position:absolute;top:7px;right:7px;width:8px;height:8px;background:var(--red);border-radius:50%;border:2px solid var(--topbar-bg);}

        /* Theme toggle */
        .theme-btn{width:38px;height:38px;border-radius:10px;background:var(--card-bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text2);transition:all .2s;font-size:15px;}
        .theme-btn:hover{background:var(--hover-bg);color:var(--text);}

        /* ── CONTENT ── */
        .content{padding:24px 28px;flex:1;animation:pageFadeIn .3s ease both;}
        @keyframes pageFadeIn{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}

        /* ── CARDS ── */
        .card{background:var(--card-bg);border-radius:var(--radius);border:1px solid var(--card-border);overflow:hidden;box-shadow:var(--shadow);transition:background var(--transition),border-color var(--transition);}
        .card-header{padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
        .card-header h3{font-family:'Poppins',sans-serif;font-size:14px;font-weight:600;}
        .card-body{padding:22px;}

        /* ── STATS ── */
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:16px;margin-bottom:24px;}
        .stat-card{background:var(--card-bg);border-radius:var(--radius);padding:20px;border:1px solid var(--card-border);display:flex;align-items:center;gap:16px;box-shadow:var(--shadow);transition:transform .25s,box-shadow .25s,background var(--transition);cursor:default;position:relative;overflow:hidden;}
        .stat-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(37,67,235,.15);}
        .stat-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;}
        .si-blue  {background:linear-gradient(135deg,#EFF6FF,#DBEAFE);color:var(--p1);}
        .si-green {background:linear-gradient(135deg,#ECFDF5,#D1FAE5);color:var(--s1);}
        .si-orange{background:linear-gradient(135deg,#FFFBEB,#FEF3C7);color:var(--acc1);}
        .si-red   {background:linear-gradient(135deg,#FEF2F2,#FEE2E2);color:var(--red);}
        .si-purple{background:linear-gradient(135deg,#F5F3FF,#EDE9FE);color:#7C3AED;}
        .si-teal  {background:linear-gradient(135deg,#F0FDFA,#CCFBF1);color:#0D9488;}
        .stat-val{font-family:'Poppins',sans-serif;font-size:28px;font-weight:700;line-height:1;}
        .stat-lbl{font-size:12px;color:var(--text2);margin-top:4px;}

        /* ── TABLE ── */
        .table-wrap{overflow-x:auto;}
        table{width:100%;border-collapse:collapse;font-size:13.5px;}
        thead th{background:var(--hover-bg);padding:11px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.6px;border-bottom:1px solid var(--border);}
        tbody td{padding:12px 16px;border-bottom:1px solid var(--border);vertical-align:middle;color:var(--text);}
        tbody tr:last-child td{border-bottom:none;}
        tbody tr{transition:background .15s;}
        tbody tr:hover{background:var(--hover-bg);}

        /* ── BADGES ── */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:600;}
        .badge-success{background:rgba(16,185,129,.12);color:#10B981;border:1px solid rgba(16,185,129,.2);}
        .badge-warning{background:rgba(245,158,11,.12);color:#F59E0B;border:1px solid rgba(245,158,11,.2);}
        .badge-danger {background:rgba(239,68,68,.12);color:#EF4444;border:1px solid rgba(239,68,68,.2);}
        .badge-info   {background:rgba(59,130,246,.12);color:#3B82F6;border:1px solid rgba(59,130,246,.2);}
        .badge-primary{background:rgba(37,67,235,.12);color:#2543EB;border:1px solid rgba(37,67,235,.2);}
        .badge-secondary{background:rgba(100,116,139,.12);color:#64748B;border:1px solid rgba(100,116,139,.2);}
        .badge-purple{background:rgba(124,58,237,.12);color:#7C3AED;border:1px solid rgba(124,58,237,.2);}

        /* ── BUTTONS ── */
        .btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;font-size:13px;font-weight:500;font-family:'Inter',sans-serif;border:none;cursor:pointer;text-decoration:none;transition:all var(--transition);}
        .btn-primary{background:linear-gradient(135deg,var(--p1),var(--p2));color:#fff;box-shadow:0 4px 12px rgba(37,67,235,.3);}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(37,67,235,.4);}
        .btn-success{background:linear-gradient(135deg,var(--s1),var(--s2));color:#fff;}
        .btn-success:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(16,185,129,.3);}
        .btn-warning{background:linear-gradient(135deg,var(--acc1),var(--acc2));color:#fff;}
        .btn-warning:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(245,158,11,.3);}
        .btn-danger{background:linear-gradient(135deg,#EF4444,#DC2626);color:#fff;}
        .btn-danger:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(239,68,68,.3);}
        .btn-secondary{background:var(--card-bg);color:var(--text);border:1px solid var(--border);}
        .btn-secondary:hover{background:var(--hover-bg);}
        .btn-sm{padding:6px 13px;font-size:12px;border-radius:8px;}
        .btn-icon{padding:7px 9px;border-radius:8px;}

        /* ── FORMS ── */
        .form-group{margin-bottom:18px;}
        .form-label{display:block;font-size:13px;font-weight:500;margin-bottom:7px;color:var(--text2);}
        .form-control{width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:13.5px;font-family:'Inter',sans-serif;background:var(--input-bg);color:var(--text);transition:border-color .2s,box-shadow .2s,background var(--transition);}
        .form-control:focus{outline:none;border-color:var(--p2);box-shadow:0 0 0 3px rgba(59,130,246,.12);background:var(--card-bg);}
        .form-control.is-invalid{border-color:var(--red);}
        .invalid-feedback{font-size:12px;color:var(--red);margin-top:4px;}
        select.form-control option{background:var(--bg2);color:var(--text);}

        /* ── ALERTS / TOAST ── */
        .toast-container{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;pointer-events:none;}
        .toast{
            display:flex;align-items:flex-start;gap:12px;
            padding:14px 16px;border-radius:14px;
            min-width:300px;max-width:420px;
            box-shadow:0 8px 32px rgba(0,0,0,.28),0 2px 8px rgba(0,0,0,.18);
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
        .toast-icon{
            width:34px;height:34px;border-radius:10px;
            display:flex;align-items:center;justify-content:center;
            font-size:15px;flex-shrink:0;
        }
        .toast-success .toast-icon{background:rgba(16,185,129,.2);color:#10B981;}
        .toast-danger  .toast-icon{background:rgba(239,68,68,.2); color:#EF4444;}
        .toast-warning .toast-icon{background:rgba(245,158,11,.2);color:#F59E0B;}
        .toast-body{flex:1;min-width:0;}
        .toast-title{font-size:13px;font-weight:700;margin-bottom:2px;}
        .toast-success .toast-title{color:#10B981;}
        .toast-danger  .toast-title{color:#EF4444;}
        .toast-warning .toast-title{color:#F59E0B;}
        .toast-msg{font-size:12.5px;color:var(--text2);line-height:1.5;}
        .toast-close{
            background:none;border:none;cursor:pointer;
            color:var(--text3);font-size:13px;padding:2px;
            flex-shrink:0;margin-top:1px;
            transition:color .15s;
        }
        .toast-close:hover{color:var(--text);}
        .toast-progress{
            position:absolute;bottom:0;left:0;height:3px;
            border-radius:0 0 14px 14px;
            animation:toastProgress 4s linear forwards;
        }
        .toast-success .toast-progress{background:linear-gradient(90deg,#10B981,#34D399);}
        .toast-danger  .toast-progress{background:linear-gradient(90deg,#EF4444,#F87171);}
        .toast-warning .toast-progress{background:linear-gradient(90deg,#F59E0B,#FCD34D);}
        @keyframes toastIn{from{opacity:0;transform:translateX(60px) scale(.92);}to{opacity:1;transform:translateX(0) scale(1);}}
        @keyframes toastOut{from{opacity:1;transform:translateX(0) scale(1);}to{opacity:0;transform:translateX(60px) scale(.92);}}
        @keyframes toastProgress{from{width:100%;}to{width:0%;}}

        /* ── PAGINATION ── */
        .pagination{display:flex;gap:4px;justify-content:center;padding:16px 0 0;}
        .pagination .page-link{padding:7px 13px;border-radius:8px;font-size:13px;color:var(--text2);text-decoration:none;border:1px solid var(--border);background:var(--card-bg);transition:all .2s;}
        .pagination .page-link:hover{background:var(--p1);color:#fff;border-color:var(--p1);}
        .pagination .page-item.active .page-link{background:linear-gradient(135deg,var(--p1),var(--p2));color:#fff;border-color:transparent;}
        .pagination .page-item.disabled .page-link{opacity:.4;pointer-events:none;}

        /* ── EMPTY STATE ── */
        .empty-state{text-align:center;padding:52px 24px;color:var(--text2);}
        .empty-state i{font-size:52px;opacity:.25;margin-bottom:14px;display:block;}

        /* ── MODAL ── */
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:500;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .25s;}
        .modal-overlay.open{opacity:1;pointer-events:auto;}
        .modal{background:var(--card-bg);border:1px solid var(--card-border);border-radius:18px;padding:28px;width:100%;max-width:480px;box-shadow:0 24px 80px rgba(0,0,0,.4);transform:translateY(20px);transition:transform .25s;backdrop-filter:blur(20px);}
        .modal-overlay.open .modal{transform:translateY(0);}
        .modal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
        .modal-header h3{font-size:16px;font-weight:700;}
        .modal-close{background:none;border:none;cursor:pointer;color:var(--text2);font-size:18px;padding:4px;}
        .modal-footer{display:flex;gap:10px;justify-content:flex-end;margin-top:20px;}

        /* ── ANIMATIONS ── */
        @keyframes slideUp{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}
        @keyframes slideDown{from{opacity:0;transform:translateY(-10px);}to{opacity:1;transform:translateY(0);}}
        .anim{animation:slideUp .4s ease both;}
        .d1{animation-delay:.05s;}.d2{animation-delay:.1s;}.d3{animation-delay:.15s;}.d4{animation-delay:.2s;}

        /* ── MISC ── */
        .flex{display:flex;}.items-center{align-items:center;}.justify-between{justify-content:space-between;}.gap-2{gap:8px;}.gap-3{gap:12px;}.mt-1{margin-top:4px;}.mb-4{margin-bottom:16px;}.w-full{width:100%;}
        .text-sm{font-size:13px;}.text-xs{font-size:11px;}.font-bold{font-weight:700;}.text-muted{color:var(--text2);}
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
        .grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;}

        /* ── CUSTOM SELECT ── */
        .cs-wrap{position:relative;display:inline-block;min-width:140px;}
        .cs-trigger{
            display:flex;align-items:center;justify-content:space-between;gap:8px;
            padding:8px 12px;border-radius:10px;
            background:var(--input-bg);border:1.5px solid var(--border);
            color:var(--text);font-size:13px;font-family:'Inter',sans-serif;
            cursor:pointer;user-select:none;
            transition:border-color .2s,box-shadow .2s,background .2s;
            white-space:nowrap;
        }
        .cs-trigger:hover{border-color:var(--p2);background:var(--card-bg);}
        .cs-trigger.open{border-color:var(--p2);box-shadow:0 0 0 3px rgba(59,130,246,.12);background:var(--card-bg);}
        .cs-trigger .cs-arrow{
            font-size:10px;color:var(--text3);
            transition:transform .25s cubic-bezier(.4,0,.2,1);
            flex-shrink:0;
        }
        .cs-trigger.open .cs-arrow{transform:rotate(180deg);}
        .cs-trigger .cs-val{flex:1;overflow:hidden;text-overflow:ellipsis;}
        .cs-trigger .cs-icon{color:var(--p2);font-size:12px;flex-shrink:0;}

        .cs-dropdown{
            position:absolute;top:calc(100% + 6px);left:0;min-width:100%;z-index:300;
            background:var(--card-bg);border:1.5px solid var(--border);
            border-radius:12px;overflow:hidden;
            box-shadow:0 16px 48px rgba(0,0,0,.22);
            opacity:0;transform:translateY(-8px) scale(.97);
            pointer-events:none;
            transition:opacity .2s cubic-bezier(.4,0,.2,1),transform .2s cubic-bezier(.4,0,.2,1);
            backdrop-filter:blur(12px);
        }
        .cs-dropdown.open{
            opacity:1;transform:translateY(0) scale(1);
            pointer-events:auto;
        }
        .cs-search-wrap{padding:8px 8px 4px;}
        .cs-search{
            width:100%;padding:7px 10px 7px 30px;
            border:1.5px solid var(--border);border-radius:8px;
            background:var(--input-bg);color:var(--text);
            font-size:12px;font-family:'Inter',sans-serif;
            outline:none;transition:border-color .2s;
        }
        .cs-search:focus{border-color:var(--p2);}
        .cs-search-icon{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:var(--text3);font-size:11px;pointer-events:none;}
        .cs-list{max-height:220px;overflow-y:auto;padding:4px;}
        .cs-list::-webkit-scrollbar{width:4px;}
        .cs-list::-webkit-scrollbar-track{background:transparent;}
        .cs-list::-webkit-scrollbar-thumb{background:var(--border);border-radius:4px;}
        .cs-option{
            display:flex;align-items:center;gap:8px;
            padding:8px 10px;border-radius:8px;
            font-size:13px;color:var(--text);
            cursor:pointer;transition:background .15s;
            white-space:nowrap;
        }
        .cs-option:hover{background:var(--hover-bg);}
        .cs-option.selected{background:rgba(59,130,246,.1);color:var(--p2);font-weight:600;}
        .cs-option .cs-check{margin-left:auto;font-size:11px;color:var(--p2);opacity:0;transition:opacity .15s;}
        .cs-option.selected .cs-check{opacity:1;}
        .cs-option .cs-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
        .cs-divider{height:1px;background:var(--border);margin:4px 8px;}

        @media(max-width:768px){
            .sidebar{transform:translateX(-100%);}
            .main{margin-left:0;}
            .stats-grid{grid-template-columns:1fr 1fr;}
            .grid-2,.grid-3{grid-template-columns:1fr;}
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-row">
            <div class="brand-logo"><i class="fas fa-book-open"></i></div>
            <div class="brand-text">
                <div class="name">Perpustakaan</div>
                <div class="sub">Panel Admin</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-chart-pie"></i></div> Dashboard
        </a>
        <a href="{{ route('buku.index') }}" class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-book"></i></div> Data Buku
        </a>
        <a href="{{ route('peminjaman.index') }}" class="nav-item {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-exchange-alt"></i></div> Peminjaman
            @php $pending = \App\Models\Peminjaman::where('status','pending')->count(); @endphp
            @if($pending > 0)<span class="badge-count">{{ $pending }}</span>@endif
        </a>
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-users"></i></div> Manajemen User
        </a>

        <div class="nav-section">Sistem</div>
        <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-cog"></i></div> Pengaturan
        </a>
        <a href="{{ route('landing') }}" class="nav-item" target="_blank">
            <div class="nav-icon"><i class="fas fa-globe"></i></div> Lihat Website
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-pill">
            <div class="user-ava">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->username, 0, 1)) }}</div>
            <div>
                <div class="uname">{{ Str::limit(Auth::user()->nama_lengkap ?? Auth::user()->username, 18) }}</div>
                <div class="urole">{{ ucfirst(Auth::user()->level) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logout-form-admin">
            @csrf
            <button type="button" class="btn-logout" onclick="confirmLogout('logout-form-admin')">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <h2>@yield('page-title','Dashboard')</h2>
            <div class="crumb">@yield('breadcrumb','Beranda')</div>
        </div>
        <div class="topbar-right">
            <div class="date-chip">
                <i class="fas fa-calendar-alt" style="color:var(--p2)"></i>
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('ddd, D MMM Y') }}
            </div>
            <button class="theme-btn" onclick="toggleTheme()" title="Toggle tema" id="theme-btn">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </div>

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
// ── Theme ──
const html = document.documentElement;
const themeBtn = document.getElementById('theme-btn');

function applyTheme(t) {
    html.setAttribute('data-theme', t);
    localStorage.setItem('theme', t);
    themeBtn.innerHTML = t === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
}

function toggleTheme() {
    applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
}

applyTheme(localStorage.getItem('theme') || 'dark');

// ── Logout confirm ──
function confirmLogout(id) {
    if (confirm('Yakin ingin keluar dari sistem?')) document.getElementById(id).submit();
}

// ── Page transition ──
document.querySelectorAll('.nav-item[href]').forEach(link => {
    link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (!href || href.startsWith('#') || this.target === '_blank') return;
        e.preventDefault();
        document.body.style.opacity = '.5';
        document.body.style.transition = 'opacity .18s';
        setTimeout(() => window.location.href = href, 180);
    });
});

// ── Modal helpers ──
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); });
});

// ── Toast ──
function dismissToast(id) {
    const t = document.getElementById(id);
    if (!t) return;
    t.classList.add('hiding');
    setTimeout(() => t.remove(), 300);
}
setTimeout(() => {
    document.querySelectorAll('.toast').forEach(t => {
        t.classList.add('hiding');
        setTimeout(() => t.remove(), 300);
    });
}, 4500);

// ── Custom Select ──
function initCustomSelects() {
    document.querySelectorAll('.cs-wrap:not([data-init])').forEach(wrap => {
        wrap.setAttribute('data-init','1');
        const trigger  = wrap.querySelector('.cs-trigger');
        const dropdown = wrap.querySelector('.cs-dropdown');
        const valEl    = wrap.querySelector('.cs-val');
        const hiddenInput = wrap.querySelector('input[type=hidden]');
        const searchInput = wrap.querySelector('.cs-search');
        const options  = wrap.querySelectorAll('.cs-option');
        const form     = wrap.closest('form');

        // Toggle open
        trigger.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = dropdown.classList.contains('open');
            // Close all others
            document.querySelectorAll('.cs-dropdown.open').forEach(d => {
                d.classList.remove('open');
                d.closest('.cs-wrap').querySelector('.cs-trigger').classList.remove('open');
            });
            if (!isOpen) {
                dropdown.classList.add('open');
                trigger.classList.add('open');
                if (searchInput) { searchInput.value=''; filterOptions(''); searchInput.focus(); }
            }
        });

        // Search filter
        if (searchInput) {
            searchInput.addEventListener('input', () => filterOptions(searchInput.value));
            searchInput.addEventListener('click', e => e.stopPropagation());
        }

        function filterOptions(q) {
            const lq = q.toLowerCase();
            options.forEach(opt => {
                const match = opt.dataset.label.toLowerCase().includes(lq);
                opt.style.display = match ? '' : 'none';
            });
        }

        // Select option
        options.forEach(opt => {
            opt.addEventListener('click', e => {
                e.stopPropagation();
                options.forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
                valEl.textContent = opt.dataset.label;
                if (hiddenInput) hiddenInput.value = opt.dataset.value;
                dropdown.classList.remove('open');
                trigger.classList.remove('open');
                // Auto-submit
                if (form) {
                    trigger.style.opacity = '.6';
                    trigger.style.pointerEvents = 'none';
                    form.submit();
                }
            });
        });
    });

    // Close on outside click
    document.addEventListener('click', () => {
        document.querySelectorAll('.cs-dropdown.open').forEach(d => {
            d.classList.remove('open');
            d.closest('.cs-wrap').querySelector('.cs-trigger').classList.remove('open');
        });
    });
}

document.addEventListener('DOMContentLoaded', initCustomSelects);
initCustomSelects();
</script>
</body>
</html>
