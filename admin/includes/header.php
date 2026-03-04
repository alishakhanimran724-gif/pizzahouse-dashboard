<?php
$page      = $_GET['page']   ?? 'dashboard';
$adminName = $_SESSION['admin_name'] ?? 'Administrator';

$navItems = [
    'dashboard' => ['icon'=>'fa-house',       'label'=>'Dashboard'],
    'orders'    => ['icon'=>'fa-bag-shopping', 'label'=>'Orders'],
    'products'  => ['icon'=>'fa-utensils',     'label'=>'Menu'],
    'customers' => ['icon'=>'fa-users',        'label'=>'Customers'],
    'revenue'   => ['icon'=>'fa-chart-line',   'label'=>'Revenue'],
    'reviews'   => ['icon'=>'fa-star',         'label'=>'Reviews'],
    'coupons'   => ['icon'=>'fa-ticket',       'label'=>'Coupons'],
    'settings'  => ['icon'=>'fa-gear',         'label'=>'Settings'],
];
$pageTitle = $navItems[$page]['label'] ?? ucfirst($page);
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> — Pizza House Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{
  --brand:#E8351F;--brand-dk:#C42B18;--brand-soft:rgba(232,53,31,.10);--brand-glow:rgba(232,53,31,.25);--brand-text:#fff;
  --bg:#FDF4F2;--bg-card:#FFFFFF;--bg-up:#FEF8F6;--bg-hov:#FCF0ED;
  --bdr:rgba(232,53,31,.08);--bdr-md:rgba(0,0,0,.09);
  --t1:#1C1410;--t2:#6B6560;--t3:#B0A8A5;
  --sb-bg:#1A0C0A;--sb-hover:rgba(232,53,31,.14);
  --green:#16A34A;--green-s:rgba(22,163,74,.10);
  --red:#DC2626;--red-s:rgba(220,38,38,.10);
  --blue:#2563EB;--blue-s:rgba(37,99,235,.10);
  --gold:#D97706;--gold-soft:rgba(217,119,6,.10);
  --purple:#7C3AED;--purple-s:rgba(124,58,237,.10);
  --amber:#EA580C;--amber-s:rgba(234,88,12,.10);--orange:#C2410C;
  --sidebar-w:230px;--sidebar-w-c:64px;--header-h:60px;
  --r-sm:8px;--r-md:12px;--r-lg:16px;--r-xl:20px;
  --tr:.16s ease;--font:'Sora',sans-serif;--font2:'DM Sans',sans-serif;
  --shadow-sm:0 1px 4px rgba(0,0,0,.05);--shadow:0 2px 12px rgba(0,0,0,.07);--shadow-md:0 6px 24px rgba(0,0,0,.11);
}
[data-theme="dark"]{
  --bg:#0F1117;--bg-card:#161B27;--bg-up:#1D2333;--bg-hov:#222840;
  --bdr:rgba(255,255,255,.06);--bdr-md:rgba(255,255,255,.11);
  --t1:#E8ECF4;--t2:#8B95A8;--t3:#4A5568;
  --sb-bg:#0D0F14;--sb-hover:rgba(232,53,31,.18);
}
html,body{height:100%;}
body{font-family:var(--font2);background:var(--bg);color:var(--t1);transition:background var(--tr),color var(--tr);overflow-x:hidden;}
::-webkit-scrollbar{width:4px;height:4px;}
::-webkit-scrollbar-thumb{background:var(--bdr-md);border-radius:99px;}

/* Shell */
.admin-shell{display:flex;min-height:100vh;}

/* Overlay */
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:199;backdrop-filter:blur(3px);}
.sidebar-overlay.active{display:block;}

/* Sidebar */
.sidebar{width:var(--sidebar-w);min-height:100vh;background:var(--sb-bg);display:flex;flex-direction:column;position:fixed;top:0;left:0;z-index:200;transition:width var(--tr),transform var(--tr);overflow:hidden;box-shadow:2px 0 20px rgba(0,0,0,.2);}
.sidebar.collapsed{width:var(--sidebar-w-c);}
.sb-brand{height:var(--header-h);display:flex;align-items:center;gap:10px;padding:0 16px;border-bottom:1px solid rgba(255,255,255,.05);flex-shrink:0;overflow:hidden;}
.sb-logo{width:34px;height:34px;background:var(--brand);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;box-shadow:0 0 18px var(--brand-glow);}
.sb-name{font-family:var(--font);font-size:15.5px;font-weight:800;color:#fff;letter-spacing:-.3px;white-space:nowrap;opacity:1;transition:opacity var(--tr);}
.sb-name span{color:var(--brand);}
.sidebar.collapsed .sb-name{opacity:0;pointer-events:none;}
.sb-nav{flex:1;padding:10px 8px;overflow-y:auto;overflow-x:hidden;}
.sb-section-label{font-size:9px;font-weight:700;color:rgba(255,255,255,.22);text-transform:uppercase;letter-spacing:1.4px;padding:10px 8px 4px;white-space:nowrap;transition:opacity var(--tr);}
.sidebar.collapsed .sb-section-label{opacity:0;}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:10px;margin-bottom:2px;color:rgba(255,255,255,.5);font-size:12.5px;font-weight:500;text-decoration:none;cursor:pointer;transition:all var(--tr);white-space:nowrap;position:relative;overflow:hidden;}
.nav-item:hover{background:var(--sb-hover);color:#fff;}
.nav-item.active{background:var(--brand);color:#fff;font-weight:700;box-shadow:0 4px 14px var(--brand-glow);}
.nav-icon{width:18px;text-align:center;font-size:14px;flex-shrink:0;}
.nav-label{opacity:1;transition:opacity var(--tr);}
.sidebar.collapsed .nav-label{opacity:0;}
.sidebar.collapsed .nav-item[data-tip]:hover::after{content:attr(data-tip);position:absolute;left:calc(var(--sidebar-w-c) - 2px);top:50%;transform:translateY(-50%);background:var(--bg-card);color:var(--t1);border:1px solid var(--bdr-md);border-radius:var(--r-sm);padding:5px 10px;font-size:12px;font-weight:600;white-space:nowrap;z-index:999;box-shadow:var(--shadow-md);pointer-events:none;}
.sb-toggle{position:absolute;top:50%;right:-13px;transform:translateY(-50%);width:26px;height:26px;background:var(--bg-card);border:1px solid var(--bdr-md);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:10px;color:var(--t2);z-index:201;transition:all var(--tr);box-shadow:var(--shadow);}
.sb-toggle:hover{color:var(--t1);background:var(--bg-up);}
.sb-footer{padding:10px 8px;border-top:1px solid rgba(255,255,255,.05);flex-shrink:0;overflow:hidden;transition:opacity var(--tr);}
.sidebar.collapsed .sb-footer{opacity:0;pointer-events:none;}
.upgrade-card{background:linear-gradient(135deg,var(--brand),#B52215);border-radius:var(--r-lg);padding:14px;position:relative;overflow:hidden;}
.upgrade-card::before{content:'';position:absolute;top:-18px;right:-18px;width:60px;height:60px;background:rgba(255,255,255,.1);border-radius:50%;}
.upgrade-card h4{font-size:12px;font-weight:800;color:#fff;margin-bottom:4px;line-height:1.4;position:relative;z-index:1;}
.upgrade-card p{font-size:10.5px;color:rgba(255,255,255,.65);margin-bottom:10px;position:relative;z-index:1;}
.upgrade-btn{display:inline-flex;align-items:center;gap:5px;background:#fff;color:var(--brand);border:none;border-radius:var(--r-sm);padding:6px 13px;font-size:11.5px;font-weight:700;font-family:var(--font);cursor:pointer;position:relative;z-index:1;transition:all .15s;}
.upgrade-btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.3);}

/* Main wrap */
.main-wrap{flex:1;margin-left:var(--sidebar-w);display:flex;flex-direction:column;min-height:100vh;transition:margin-left var(--tr);}
.main-wrap.collapsed{margin-left:var(--sidebar-w-c);}

/* Topbar */
.top-header{height:var(--header-h);background:var(--bg-card);border-bottom:1px solid var(--bdr);display:flex;align-items:center;gap:12px;padding:0 22px;position:sticky;top:0;z-index:100;box-shadow:var(--shadow-sm);}
.hdr-hamburger{display:none;background:none;border:none;color:var(--t2);font-size:18px;cursor:pointer;padding:6px;border-radius:var(--r-sm);transition:all var(--tr);}
.hdr-hamburger:hover{color:var(--t1);background:var(--bg-hov);}
.hdr-bread{flex:1;}
.hdr-bread .bread-title{font-family:var(--font);font-size:17px;font-weight:800;color:var(--t1);letter-spacing:-.3px;line-height:1;}
.hdr-bread .bread-sub{font-size:10.5px;color:var(--t3);margin-top:2px;}
.hdr-search{position:relative;flex-shrink:0;}
.hdr-search i{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:12px;pointer-events:none;}
.hdr-search input{background:var(--bg-up);border:1.5px solid var(--bdr-md);border-radius:99px;padding:8px 14px 8px 32px;color:var(--t1);font-size:12.5px;font-family:var(--font2);width:200px;transition:all var(--tr);}
.hdr-search input:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 3px var(--brand-soft);width:240px;}
.hdr-search input::placeholder{color:var(--t3);}
.hdr-actions{display:flex;align-items:center;gap:6px;}
.hdr-icon-btn{width:36px;height:36px;border-radius:var(--r-sm);border:1.5px solid var(--bdr-md);background:var(--bg-up);color:var(--t2);display:flex;align-items:center;justify-content:center;font-size:14px;cursor:pointer;transition:all var(--tr);position:relative;text-decoration:none;}
.hdr-icon-btn:hover{color:var(--brand);border-color:var(--brand);background:var(--brand-soft);}
.hdr-icon-btn .badge-dot{position:absolute;top:6px;right:6px;width:7px;height:7px;background:var(--red);border-radius:50%;border:1.5px solid var(--bg-card);}
.btn-add-new{display:inline-flex;align-items:center;gap:7px;background:var(--brand);color:#fff;border:none;border-radius:var(--r-sm);padding:9px 16px;font-size:12.5px;font-weight:700;font-family:var(--font);cursor:pointer;text-decoration:none;transition:all var(--tr);white-space:nowrap;box-shadow:0 2px 10px var(--brand-glow);}
.btn-add-new:hover{background:var(--brand-dk);transform:translateY(-1px);box-shadow:0 5px 16px var(--brand-glow);}
.theme-btn{width:36px;height:36px;border-radius:var(--r-sm);border:1.5px solid var(--bdr-md);background:var(--bg-up);color:var(--t2);display:flex;align-items:center;justify-content:center;font-size:14px;cursor:pointer;transition:all var(--tr);}
.theme-btn:hover{color:var(--brand);border-color:var(--brand);}
.hdr-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--brand),#C42B18);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;color:#fff;cursor:pointer;border:2px solid transparent;transition:border-color var(--tr);flex-shrink:0;}
.hdr-avatar:hover{border-color:var(--brand);}
.mobile-search-bar{display:none;padding:10px 16px;background:var(--bg-card);border-bottom:1px solid var(--bdr);}
.mobile-search-wrap{position:relative;}
.ms-icon{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:13px;pointer-events:none;}
.mobile-search-bar input{width:100%;background:var(--bg-up);border:1.5px solid var(--bdr-md);border-radius:99px;padding:8px 14px 8px 34px;color:var(--t1);font-size:13px;font-family:var(--font2);}
.mobile-search-bar input:focus{outline:none;border-color:var(--brand);}

/* Content */
.content{flex:1;padding:22px 24px 32px;}

/* Cards */
.card{background:var(--bg-card);border:1px solid var(--bdr);border-radius:var(--r-lg);margin-bottom:16px;overflow:hidden;box-shadow:var(--shadow-sm);}
.card-hd{padding:14px 18px;border-bottom:1px solid var(--bdr);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
.card-title{font-family:var(--font);font-size:13.5px;font-weight:700;color:var(--t1);display:flex;align-items:center;gap:7px;}
.card-title i{color:var(--brand);font-size:13px;}
.card-bd{padding:16px 18px;}

/* Stats */
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
.stat-card{background:var(--bg-card);border:1px solid var(--bdr);border-radius:var(--r-lg);padding:18px;transition:transform var(--tr),box-shadow var(--tr);box-shadow:var(--shadow-sm);}
.stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-md);}
.sc-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
.sc-icon{width:38px;height:38px;border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:15px;}
.si-red   {background:var(--red-s);   color:var(--red);}
.si-green {background:var(--green-s); color:var(--green);}
.si-blue  {background:var(--blue-s);  color:var(--blue);}
.si-gold  {background:var(--gold-soft);color:var(--gold);}
.si-purple{background:var(--purple-s);color:var(--purple);}
.si-brand {background:var(--brand-soft);color:var(--brand);}
.sc-red  {border-left:3px solid var(--red);}
.sc-green{border-left:3px solid var(--green);}
.sc-blue {border-left:3px solid var(--blue);}
.sc-gold {border-left:3px solid var(--gold);}
.sc-trend{font-size:10.5px;font-weight:700;padding:3px 8px;border-radius:99px;display:flex;align-items:center;gap:3px;}
.tr-up {background:var(--green-s);color:var(--green);}
.tr-down{background:var(--red-s);  color:var(--red);}
.tr-neu {background:var(--bg-up);  color:var(--t3);}
.sc-val{font-family:var(--font);font-size:1.65rem;font-weight:800;color:var(--t1);line-height:1;margin-bottom:4px;letter-spacing:-.5px;}
.sc-lbl{font-size:11.5px;color:var(--t3);font-weight:500;}
.sc-sub{font-size:10.5px;color:var(--t3);margin-top:3px;}

/* Buttons */
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;font-family:var(--font2);cursor:pointer;border:none;transition:all var(--tr);text-decoration:none;white-space:nowrap;}
.btn-primary{background:var(--brand);color:#fff;box-shadow:0 2px 8px var(--brand-glow);}
.btn-primary:hover{background:var(--brand-dk);transform:translateY(-1px);box-shadow:0 4px 14px var(--brand-glow);}
.btn-ghost{background:var(--bg-up);color:var(--t2);border:1.5px solid var(--bdr-md);}
.btn-ghost:hover{color:var(--t1);border-color:var(--brand);background:var(--brand-soft);}
.btn-danger{background:var(--red-s);color:var(--red);border:1px solid rgba(220,38,38,.2);}
.btn-danger:hover{background:var(--red);color:#fff;}
.btn-success{background:var(--green-s);color:var(--green);border:1px solid rgba(22,163,74,.2);}
.btn-success:hover{background:var(--green);color:#fff;}
.btn-sm{padding:6px 11px;font-size:11.5px;}
.btn-xs{padding:4px 9px;font-size:11px;border-radius:6px;}

/* Badges */
.badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:99px;font-size:11px;font-weight:700;white-space:nowrap;}
.badge-pending      {background:var(--amber-s); color:var(--amber);}
.badge-confirmed    {background:var(--blue-s);  color:var(--blue);}
.badge-preparing    {background:var(--purple-s);color:var(--purple);}
.badge-out_for_delivery{background:rgba(194,65,12,.12);color:var(--orange);}
.badge-delivered    {background:var(--green-s); color:var(--green);}
.badge-cancelled    {background:var(--red-s);   color:var(--red);}

/* Progress */
.prog{height:5px;background:var(--bg-hov);border-radius:99px;overflow:hidden;}
.prog-fill{height:100%;border-radius:99px;transition:width .5s ease;}

/* Table */
.tbl-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 14px;text-align:left;font-size:10.5px;font-weight:700;color:var(--t3);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--bdr);white-space:nowrap;background:var(--bg-up);}
tbody td{padding:11px 14px;font-size:12.5px;color:var(--t1);border-bottom:1px solid var(--bdr);vertical-align:middle;}
tr.s-row:hover td{background:var(--bg-hov);}
tbody tr:last-child td{border-bottom:none;}
.prod-thumb{width:40px;height:40px;border-radius:var(--r-sm);object-fit:cover;border:1px solid var(--bdr);}

/* Pills */
.pill{padding:5px 12px;border-radius:99px;font-size:11.5px;font-weight:600;color:var(--t2);background:var(--bg-up);border:1px solid transparent;cursor:pointer;text-decoration:none;transition:all var(--tr);white-space:nowrap;}
.pill:hover{color:var(--t1);border-color:var(--bdr-md);}
.pill.active{background:var(--brand);color:#fff;border-color:transparent;box-shadow:0 2px 8px var(--brand-glow);}

/* Search */
.srch-wrap{position:relative;}
.srch-wrap i{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:12px;pointer-events:none;}
.srch-inp{background:var(--bg-up);border:1.5px solid var(--bdr-md);border-radius:99px;padding:7px 12px 7px 30px;color:var(--t1);font-size:12px;font-family:var(--font2);width:190px;transition:all var(--tr);}
.srch-inp:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 2px var(--brand-soft);width:220px;}
.srch-inp::placeholder{color:var(--t3);}

/* Forms */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.form-full{grid-column:1/-1;}
.form-grp{display:flex;flex-direction:column;gap:5px;}
.form-lbl{font-size:10.5px;font-weight:700;color:var(--t2);text-transform:uppercase;letter-spacing:.7px;}
.form-ctrl{background:var(--bg-up);border:1.5px solid var(--bdr-md);border-radius:var(--r-sm);padding:9px 12px;color:var(--t1);font-size:13px;font-family:var(--font2);transition:all var(--tr);width:100%;}
.form-ctrl:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 3px var(--brand-soft);}
.form-ctrl::placeholder{color:var(--t3);}
textarea.form-ctrl{min-height:88px;resize:vertical;}
select.form-ctrl{cursor:pointer;}

/* Toggle */
.tog-wrap{display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none;}
.tog{position:relative;flex-shrink:0;}
.tog input{opacity:0;width:0;height:0;position:absolute;}
.tog-track{width:36px;height:20px;background:var(--bg-hov);border-radius:99px;border:1.5px solid var(--bdr-md);display:block;transition:all var(--tr);}
.tog-thumb{position:absolute;top:3px;left:3px;width:14px;height:14px;background:var(--t3);border-radius:50%;transition:all var(--tr);}
.tog input:checked~.tog-track{background:var(--brand);border-color:var(--brand);}
.tog input:checked~.tog-thumb{background:#fff;transform:translateX(16px);}
.tog-lbl{font-size:12.5px;font-weight:500;color:var(--t2);}

/* Empty */
.empty-box{padding:40px 20px;text-align:center;color:var(--t3);}
.empty-box i{font-size:36px;margin-bottom:10px;opacity:.3;display:block;}
.empty-box h3{font-size:14px;font-weight:600;margin-bottom:4px;}
.empty-box p{font-size:12px;}

/* Alert */
.alert{padding:12px 16px;border-radius:var(--r-md);margin-bottom:16px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;}
.alert-success{background:var(--green-s);color:var(--green);border:1px solid rgba(22,163,74,.25);}
.alert-danger {background:var(--red-s);  color:var(--red);  border:1px solid rgba(220,38,38,.25);}

/* Info row */
.inf-row{display:flex;align-items:flex-start;gap:10px;padding:9px 0;border-bottom:1px solid var(--bdr);font-size:12.5px;}
.inf-row:last-child{border-bottom:none;}
.inf-row i{color:var(--t3);font-size:13px;width:15px;text-align:center;flex-shrink:0;margin-top:1px;}
.inf-key{color:var(--t3);width:72px;flex-shrink:0;font-weight:500;}
.inf-val{color:var(--t1);font-weight:600;flex:1;word-break:break-word;}

/* Modal */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:500;align-items:center;justify-content:center;backdrop-filter:blur(4px);padding:20px;}
.modal-overlay.open{display:flex;animation:mFadeIn .15s ease;}
@keyframes mFadeIn{from{opacity:0}to{opacity:1}}
.modal{background:var(--bg-card);border-radius:var(--r-xl);width:100%;max-width:600px;max-height:90vh;overflow-y:auto;box-shadow:0 24px 64px rgba(0,0,0,.2);animation:mSlideUp .2s ease;}
@keyframes mSlideUp{from{opacity:0;transform:translateY(18px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}
.modal-hd{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--bdr);position:sticky;top:0;background:var(--bg-card);z-index:1;}
.modal-title{font-family:var(--font);font-size:15px;font-weight:800;color:var(--t1);}
.modal-close{width:32px;height:32px;border:none;background:var(--bg-up);border-radius:var(--r-sm);cursor:pointer;color:var(--t2);display:flex;align-items:center;justify-content:center;transition:all var(--tr);}
.modal-close:hover{background:var(--red-s);color:var(--red);}
.modal-bd{padding:22px;}
.modal-ft{padding:14px 22px;border-top:1px solid var(--bdr);display:flex;gap:9px;justify-content:flex-end;background:var(--bg-card);position:sticky;bottom:0;}

/* Prod grid cards */
.prod-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;}
.prod-card{background:var(--bg-card);border:1.5px solid var(--bdr);border-radius:var(--r-xl);overflow:hidden;transition:all var(--tr);}
.prod-card:hover{border-color:var(--brand);transform:translateY(-3px);box-shadow:0 10px 28px rgba(232,53,31,.12);}
.prod-card.featured{border-color:var(--brand);box-shadow:0 0 0 2px var(--brand-glow);}
.prod-card-img{aspect-ratio:4/3;overflow:hidden;background:var(--bg-up);position:relative;}
.prod-card-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s ease;}
.prod-card:hover .prod-card-img img{transform:scale(1.05);}
.prod-card-badge{position:absolute;top:8px;left:8px;background:var(--brand);color:#fff;font-size:10px;font-weight:800;padding:3px 8px;border-radius:99px;}
.prod-card-body{padding:12px;}
.prod-card-name{font-family:var(--font);font-size:13px;font-weight:700;color:var(--t1);margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.prod-card-desc{font-size:11px;color:var(--t3);margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.prod-card-foot{display:flex;align-items:center;justify-content:space-between;}
.prod-card-price{font-family:var(--font);font-size:15px;font-weight:800;color:var(--brand);}
.prod-card-actions{display:flex;gap:4px;opacity:0;transition:opacity var(--tr);}
.prod-card:hover .prod-card-actions{opacity:1;}

/* Responsive */
@media(max-width:1280px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:1024px){:root{--sidebar-w:220px;}.hdr-search{display:none;}}
@media(max-width:900px){
  .sidebar{transform:translateX(-100%);width:var(--sidebar-w)!important;box-shadow:4px 0 24px rgba(0,0,0,.4);}
  .sidebar.mobile-open{transform:translateX(0);}
  .main-wrap,.main-wrap.collapsed{margin-left:0!important;}
  .hdr-hamburger{display:flex;}
  .sb-toggle{display:none!important;}
  .content{padding:16px;}
  .form-grid{grid-template-columns:1fr;}
  .form-full{grid-column:1;}
  .stat-grid{grid-template-columns:repeat(2,1fr);gap:10px;}
}
@media(max-width:600px){
  .stat-grid{grid-template-columns:1fr 1fr;gap:10px;}
  .sc-val{font-size:1.3rem;}
  .content{padding:12px;}
  .top-header{padding:0 14px;gap:8px;}
  .hdr-bread .bread-title{font-size:15px;}
  .btn-add-new span{display:none;}
  .card-hd,.card-bd{padding:12px 14px;}
  thead th,tbody td{padding:9px 10px;}
  .prod-grid{grid-template-columns:1fr 1fr;}
}
@media(max-width:400px){
  .stat-grid{grid-template-columns:1fr;}
  .prod-grid{grid-template-columns:1fr;}
}
</style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="admin-shell">

  <aside class="sidebar" id="sidebar">
    <button class="sb-toggle" id="sbToggle" onclick="toggleSidebar()" title="Toggle">
      <i class="fas fa-chevron-left" id="sbToggleIco"></i>
    </button>
    <div class="sb-brand">
      <div class="sb-logo">🍕</div>
      <span class="sb-name">Pizza<span>.</span>Admin</span>
    </div>
    <nav class="sb-nav">
      <div class="sb-section-label">Main</div>
      <?php foreach($navItems as $key => $item): ?>
      <a href="index.php?page=<?= $key ?>"
         class="nav-item <?= $page===$key?'active':'' ?>"
         data-tip="<?= htmlspecialchars($item['label']) ?>">
        <i class="fas <?= $item['icon'] ?> nav-icon"></i>
        <span class="nav-label"><?= htmlspecialchars($item['label']) ?></span>
        <?php if($key==='orders' && ($pendingOrders??0)>0): ?>
        <span class="nav-label" style="margin-left:auto;background:<?= $page==='orders'?'rgba(255,255,255,.25)':'var(--brand-soft)' ?>;color:<?= $page==='orders'?'#fff':'var(--brand)' ?>;font-size:10px;font-weight:700;padding:1px 6px;border-radius:99px;"><?= $pendingOrders ?></span>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
      <div class="sb-section-label" style="margin-top:8px">Account</div>
      <a href="index.php?action=logout" class="nav-item" data-tip="Logout">
        <i class="fas fa-right-from-bracket nav-icon"></i>
        <span class="nav-label">Logout</span>
      </a>
    </nav>
    <div class="sb-footer">
      <div class="upgrade-card">
        <h4>Upgrade your Account</h4>
        <p>Unlock analytics, exports & more.</p>
        <button class="upgrade-btn"><i class="fas fa-bolt"></i> Upgrade</button>
      </div>
    </div>
  </aside>

  <div class="main-wrap" id="mainWrap">
    <header class="top-header">
      <button class="hdr-hamburger" id="hamburger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
      <div class="hdr-bread">
        <div class="bread-title"><?= htmlspecialchars($pageTitle) ?></div>
        <div class="bread-sub"><?= date('l, d M Y') ?></div>
      </div>
      <div class="hdr-search">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search anything…" id="globalSearch">
      </div>
      <div class="hdr-actions">
        <button class="hdr-icon-btn" onclick="toggleMobileSearch()" title="Search"><i class="fas fa-search"></i></button>
        <a href="index.php?page=orders&filter=pending" class="hdr-icon-btn" title="Pending">
          <i class="fas fa-bell"></i>
          <?php if(($pendingOrders??0)>0): ?><span class="badge-dot"></span><?php endif; ?>
        </a>
        <button class="theme-btn" onclick="toggleTheme()"><i class="fas fa-moon" id="thIco"></i></button>
        <?php
        $addLink='index.php?page=products&add=1'; $addLabel='Add Pizza';
        if($page==='coupons') {$addLink='index.php?page=coupons&add=1';  $addLabel='New Coupon';}
        if($page==='customers'){$addLink='index.php?page=customers&add=1';$addLabel='Add Customer';}
        ?>
        <a href="<?= $addLink ?>" class="btn-add-new"><i class="fas fa-plus"></i><span><?= htmlspecialchars($addLabel) ?></span></a>
        <div class="hdr-avatar"><?= strtoupper(substr($adminName,0,1)) ?></div>
      </div>
    </header>
    <div class="mobile-search-bar" id="mobileSearchBar">
      <div class="mobile-search-wrap">
        <span class="ms-icon"><i class="fas fa-search"></i></span>
        <input type="text" placeholder="Search anything…">
      </div>
    </div>
    <main class="content">
<?php
echo '<script>
document.addEventListener("DOMContentLoaded",function(){
  // Table search
  function tableSearch(q){document.querySelectorAll("tbody tr.s-row").forEach(function(r){r.style.display=r.textContent.toLowerCase().includes(q)?"":"none";});}
  var ts=document.getElementById("tableSearch");
  if(ts)ts.addEventListener("input",function(){tableSearch(this.value.toLowerCase());});
  var gs=document.getElementById("globalSearch");
  if(gs)gs.addEventListener("input",function(){tableSearch(this.value.toLowerCase());});
  // Data-confirm on forms
  document.querySelectorAll("form[data-confirm]").forEach(function(f){
    f.addEventListener("submit",function(e){if(!confirm(f.dataset.confirm))e.preventDefault();});
  });
});
function openModal(id){var m=document.getElementById(id);if(m){m.classList.add("open");document.body.style.overflow="hidden";}}
function closeModal(id){var m=document.getElementById(id);if(m){m.classList.remove("open");document.body.style.overflow="";}}
document.addEventListener("keydown",function(e){if(e.key==="Escape"){document.querySelectorAll(".modal-overlay.open").forEach(function(m){m.classList.remove("open");});document.body.style.overflow="";}});
</script>';
?>
<script>
var sbCollapsed=localStorage.getItem("ph_sb_collapsed")==="1";
function applySidebar(){var sb=document.getElementById("sidebar"),mw=document.getElementById("mainWrap"),ico=document.getElementById("sbToggleIco");if(sbCollapsed){sb.classList.add("collapsed");mw.classList.add("collapsed");if(ico)ico.className="fas fa-chevron-right";}else{sb.classList.remove("collapsed");mw.classList.remove("collapsed");if(ico)ico.className="fas fa-chevron-left";}}
function toggleSidebar(){sbCollapsed=!sbCollapsed;localStorage.setItem("ph_sb_collapsed",sbCollapsed?"1":"0");applySidebar();}
applySidebar();
function openSidebar(){document.getElementById("sidebar").classList.add("mobile-open");document.getElementById("sidebarOverlay").classList.add("active");document.body.style.overflow="hidden";}
function closeSidebar(){document.getElementById("sidebar").classList.remove("mobile-open");document.getElementById("sidebarOverlay").classList.remove("active");document.body.style.overflow="";}
(function(){var t=localStorage.getItem("ph_theme")||"light";applyTheme(t,false);})();
function applyTheme(t,save){document.documentElement.setAttribute("data-theme",t);var i=document.getElementById("thIco");if(i)i.className=t==="dark"?"fas fa-sun":"fas fa-moon";if(save!==false)localStorage.setItem("ph_theme",t);}
function toggleTheme(){var c=document.documentElement.getAttribute("data-theme")||"light";applyTheme(c==="dark"?"light":"dark");}
function toggleMobileSearch(){var bar=document.getElementById("mobileSearchBar");var show=bar.style.display==="block";bar.style.display=show?"none":"block";if(!show)bar.querySelector("input").focus();}
function checkViewport(){var t=document.getElementById("sbToggle");if(t)t.style.display=window.innerWidth<=900?"none":"flex";}
window.addEventListener("resize",checkViewport);checkViewport();
</script>