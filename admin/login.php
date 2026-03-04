<?php
session_start();
if(isset($_SESSION['admin_logged_in'])){header('Location: index.php');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=trim($_POST['email']??'');$pass=$_POST['password']??'';
  if($email==='admin@pizzahouse.com'&&$pass==='Admin@123'){
    $_SESSION['admin_logged_in']=true;$_SESSION['admin_name']='Administrator';
    header('Location: index.php');exit;
  }
  $error='Invalid credentials. Please try again.';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Admin Login — Pizza House</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{
  --brand:#E8351F;--brand-dk:#C42B18;--brand-soft:rgba(232,53,31,.10);--brand-glow:rgba(232,53,31,.25);
  --bg:#FDF4F2;--bg-card:#fff;--bg-up:#FEF8F6;
  --bdr:rgba(232,53,31,.08);--bdr-md:rgba(0,0,0,.09);
  --t1:#1C1410;--t2:#6B6560;--t3:#B0A8A5;
  --green:#16A34A;--green-s:rgba(22,163,74,.10);
  --red:#DC2626;--red-s:rgba(220,38,38,.10);
  --font:'Sora',sans-serif;--font2:'DM Sans',sans-serif;
}
[data-theme="dark"]{
  --bg:#0F1117;--bg-card:#161B27;--bg-up:#1D2333;
  --bdr:rgba(255,255,255,.06);--bdr-md:rgba(255,255,255,.11);
  --t1:#E8ECF4;--t2:#8B95A8;--t3:#4A5568;
}
body{font-family:var(--font2);background:var(--bg);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;position:relative;overflow:hidden;transition:background .2s;}
body::before{content:'';position:fixed;top:-200px;right:-200px;width:520px;height:520px;background:radial-gradient(circle,rgba(232,53,31,.08),transparent 70%);border-radius:50%;pointer-events:none;}
body::after{content:'';position:fixed;bottom:-200px;left:-200px;width:460px;height:460px;background:radial-gradient(circle,rgba(245,158,11,.06),transparent 70%);border-radius:50%;pointer-events:none;}
.card{background:var(--bg-card);border:1px solid var(--bdr);border-radius:20px;width:100%;max-width:400px;padding:40px 36px;box-shadow:0 20px 60px rgba(232,53,31,.08);position:relative;z-index:1;animation:up .4s ease;}
@keyframes up{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
.logo{text-align:center;margin-bottom:28px;}
.logo-icon{width:56px;height:56px;background:var(--brand);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 14px;box-shadow:0 0 28px var(--brand-glow);}
.logo h1{font-family:var(--font);font-size:1.35rem;font-weight:800;color:var(--t1);margin-bottom:3px;}
.logo p{font-size:12.5px;color:var(--t3);}
.error-box{background:var(--red-s);border:1px solid rgba(220,38,38,.2);border-radius:8px;padding:10px 13px;font-size:12.5px;color:var(--red);margin-bottom:16px;display:flex;align-items:center;gap:7px;}
.hint{background:var(--bg-up);border:1px solid var(--bdr-md);border-radius:8px;padding:10px 13px;font-size:11.5px;color:var(--t3);margin-bottom:18px;line-height:1.6;}
.hint strong{color:var(--t2);}
.fgrp{margin-bottom:14px;}
.flbl{display:block;font-size:10.5px;font-weight:700;color:var(--t2);text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px;}
.iw{position:relative;}
.iw i.ico{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:13px;pointer-events:none;}
.inp{width:100%;padding:10px 12px 10px 36px;background:var(--bg-up);border:1.5px solid var(--bdr-md);border-radius:8px;color:var(--t1);font-size:13.5px;font-family:var(--font2);transition:border-color .15s,box-shadow .15s;}
.inp:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 3px var(--brand-soft);}
.inp::placeholder{color:var(--t3);}
.eye{position:absolute;right:11px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--t3);cursor:pointer;padding:0;font-size:13px;transition:color .15s;}
.eye:hover{color:var(--t2);}
.login-btn{width:100%;padding:12px;background:var(--brand);color:#fff;border:none;border-radius:8px;font-family:var(--font);font-size:14px;font-weight:700;cursor:pointer;transition:all .15s;display:flex;align-items:center;justify-content:center;gap:7px;margin-top:4px;box-shadow:0 4px 14px var(--brand-glow);}
.login-btn:hover{background:var(--brand-dk);transform:translateY(-1px);box-shadow:0 6px 20px var(--brand-glow);}
.back{text-align:center;margin-top:18px;}
.back a{font-size:12.5px;color:var(--t3);text-decoration:none;transition:color .15s;}
.back a:hover{color:var(--brand);}
.theme-toggle{position:fixed;top:16px;right:16px;background:var(--bg-card);border:1px solid var(--bdr-md);border-radius:8px;padding:7px 12px;font-size:12px;font-weight:600;color:var(--t2);cursor:pointer;display:flex;align-items:center;gap:5px;transition:all .15s;font-family:var(--font2);z-index:10;}
.theme-toggle:hover{color:var(--t1);}
</style>
</head>
<body>
<button class="theme-toggle" onclick="toggleTheme()"><i class="fas fa-moon" id="thIco"></i><span id="thTxt">Light</span></button>
<div class="card">
  <div class="logo">
    <div class="logo-icon">🍕</div>
    <h1>Pizza House Admin</h1>
    <p>Sign in to manage your restaurant</p>
  </div>
  <?php if($error): ?><div class="error-box"><i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <div class="hint"><strong>Demo credentials</strong><br>Email: admin@pizzahouse.com &nbsp;·&nbsp; Password: Admin@123</div>
  <form method="POST">
    <div class="fgrp">
      <label class="flbl">Email Address</label>
      <div class="iw"><i class="fas fa-envelope ico"></i><input type="email" name="email" class="inp" required placeholder="admin@pizzahouse.com" value="admin@pizzahouse.com"></div>
    </div>
    <div class="fgrp">
      <label class="flbl">Password</label>
      <div class="iw">
        <i class="fas fa-lock ico"></i>
        <input type="password" name="password" class="inp" required placeholder="••••••••" id="pw" value="Admin@123">
        <button type="button" class="eye" onclick="var p=document.getElementById('pw');p.type=p.type==='password'?'text':'password';this.innerHTML=p.type==='password'?'<i class=\'fas fa-eye\'></i>':'<i class=\'fas fa-eye-slash\'></i>'"><i class="fas fa-eye"></i></button>
      </div>
    </div>
    <button type="submit" class="login-btn"><i class="fas fa-sign-in-alt"></i> Sign In</button>
  </form>
  <div class="back"><a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Website</a></div>
</div>
<script>
(function(){var t=localStorage.getItem('ph_theme')||'light';setT(t);})();
function setT(t){document.documentElement.setAttribute('data-theme',t);var i=document.getElementById('thIco'),tx=document.getElementById('thTxt');if(t==='dark'){i.className='fas fa-sun';if(tx)tx.textContent='Dark';}else{i.className='fas fa-moon';if(tx)tx.textContent='Light';}localStorage.setItem('ph_theme',t);}
function toggleTheme(){var c=document.documentElement.getAttribute('data-theme')||'light';setT(c==='dark'?'light':'dark');}
</script>
</body></html>