<?php
// Tangkap pesan dari proses login/register/forgot
$msg = '';
if (isset($_GET['msg'])) {
  $msg = htmlspecialchars($_GET['msg']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Beauty Always Network – Login / Register</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --rose:    #c8697a;
    --rose-lt: #e8a0ad;
    --blush:   #f5dfe3;
    --cream:   #fdf7f8;
    --sky:     #dce9f5;
    --dark:    #2a1a1f;
    --mid:     #6b4a52;
    --border:  #e2c8cc;
    --font-h:  'Cormorant Garamond', serif;
    --font-b:  'Jost', sans-serif;
  }

  body {
    font-family: var(--font-b);
    background: #db88b2;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 0 60px;
  }

  /* ── TOP BAR ── */
  .topbar {
    width: 100%;
    background: #111;
    color: #ccc;
    font-size: 11px;
    letter-spacing: .18em;
    text-transform: uppercase;
    padding: 10px 32px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .topbar span { color: var(--rose-lt); }

  /* ── SECTION LABEL ── */
  .section-label {
    width: 100%;
    max-width: 900px;
    color: #eee;
    font-family: var(--font-b);
    font-size: 13px;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding: 28px 0 12px;
  }

  /* ── CARD ── */
  .card {
    width: 100%;
    max-width: 900px;
    border-radius: 18px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1fr 1fr;
    box-shadow: 0 30px 80px rgba(0,0,0,.55);
    margin-bottom: 40px;
    background: var(--cream);
  }

  /* ── FORM SIDE ── */
  .form-side {
    background: var(--cream);
    padding: 48px 44px 40px;
    position: relative;
    overflow: hidden;
  }
  .form-side::before {
    content: '';
    position: absolute;
    top: -60px; left: -60px;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(200,105,122,.18) 0%, transparent 70%);
    pointer-events: none;
  }

  .brand {
    font-family: var(--font-h);
    font-size: 11px;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--rose);
    margin-bottom: 6px;
  }
  .title {
    font-family: var(--font-h);
    font-size: 26px;
    font-weight: 600;
    color: var(--dark);
    line-height: 1.2;
    margin-bottom: 4px;
  }
  .subtitle {
    font-size: 12px;
    color: var(--mid);
    letter-spacing: .04em;
    margin-bottom: 28px;
  }

  /* ── TABS ── */
  .tabs {
    display: flex;
    gap: 0;
    margin-bottom: 28px;
    border-bottom: 1.5px solid var(--border);
  }
  .tab {
    font-family: var(--font-b);
    font-size: 12px;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 8px 0;
    margin-right: 24px;
    cursor: pointer;
    color: var(--mid);
    border-bottom: 2px solid transparent;
    margin-bottom: -1.5px;
    transition: color .25s, border-color .25s;
    background: none;
    border-left: none; border-right: none; border-top: none;
  }
  .tab.active {
    color: var(--rose);
    border-bottom-color: var(--rose);
    font-weight: 500;
  }

  /* ── FIELDS ── */
  .field { margin-bottom: 18px; }
  .field label {
    display: block;
    font-size: 11px;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--mid);
    margin-bottom: 7px;
  }
  .field input {
    width: 100%;
    height: 42px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: #fff;
    padding: 0 14px;
    font-family: var(--font-b);
    font-size: 13px;
    color: var(--dark);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
  }
  .field input:focus {
    border-color: var(--rose);
    box-shadow: 0 0 0 3px rgba(200,105,122,.12);
  }
  .field input::placeholder { color: #c0a8ad; }

  /* phone row */
  .phone-row { display: flex; gap: 8px; }
  .phone-row .country-select {
    height: 42px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: #fff;
    padding: 0 10px;
    font-family: var(--font-b);
    font-size: 13px;
    color: var(--dark);
    cursor: pointer;
    outline: none;
    transition: border-color .2s;
    min-width: 80px;
  }
  .phone-row .country-select:focus { border-color: var(--rose); }
  .phone-row input { flex: 1; }

  /* password strength */
  .strength-bar {
    display: flex; gap: 4px; margin-top: 6px;
  }
  .strength-bar span {
    flex: 1; height: 3px; border-radius: 2px;
    background: var(--border); transition: background .3s;
  }
  .strength-bar.weak span:first-child { background: #e07070; }
  .strength-bar.medium span:first-child,
  .strength-bar.medium span:nth-child(2) { background: #e0b870; }
  .strength-bar.strong span { background: #70c090; }

  /* forgot */
  .forgot-link {
    display: block;
    text-align: right;
    font-size: 12px;
    color: var(--rose);
    text-decoration: none;
    letter-spacing: .03em;
    margin: -6px 0 20px;
    cursor: pointer;
    transition: opacity .2s;
  }
  .forgot-link:hover { opacity: .7; }

  /* button */
  .btn-primary {
    width: 100%;
    height: 44px;
    background: linear-gradient(135deg, var(--rose) 0%, #a3495a 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-family: var(--font-b);
    font-size: 12px;
    letter-spacing: .18em;
    text-transform: uppercase;
    cursor: pointer;
    transition: opacity .2s, transform .15s;
    margin-top: 4px;
  }
  .btn-primary:hover { opacity: .88; transform: translateY(-1px); }
  .btn-primary:active { transform: translateY(0); }

  .or-divider {
    text-align: center;
    font-size: 11px;
    color: #c0a8ad;
    letter-spacing: .1em;
    margin: 18px 0;
    position: relative;
  }
  .or-divider::before, .or-divider::after {
    content: '';
    position: absolute;
    top: 50%; width: calc(50% - 24px);
    height: 1px; background: var(--border);
  }
  .or-divider::before { left: 0; }
  .or-divider::after { right: 0; }

  .switch-link {
    text-align: center;
    font-size: 12px;
    color: var(--mid);
    margin-top: 16px;
  }
  .switch-link a {
    color: var(--rose);
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
  }
  .switch-link a:hover { text-decoration: underline; }

  .back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--rose);
    cursor: pointer;
    margin-bottom: 24px;
    text-decoration: none;
    transition: opacity .2s;
  }
  .back-link:hover { opacity: .7; }

  .info-box {
    background: rgba(200,105,122,.08);
    border: 1px solid rgba(200,105,122,.2);
    border-radius: 8px;
    padding: 12px 14px;
    font-size: 12px;
    color: var(--mid);
    line-height: 1.6;
    margin-bottom: 22px;
  }

  /* success state */
  .success-msg {
    text-align: center;
    padding: 20px 0 10px;
  }
  .success-icon {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--rose), #a3495a);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
    font-size: 24px;
    color: #fff;
    box-shadow: 0 8px 24px rgba(200,105,122,.4);
  }
  .success-msg h3 {
    font-family: var(--font-h);
    font-size: 22px;
    color: var(--dark);
    margin-bottom: 8px;
  }
  .success-msg p {
    font-size: 12px;
    color: var(--mid);
    line-height: 1.7;
  }

  /* ── IMAGE SIDE ── */
  .image-side {
    background-image: url("../image/log1.jpg"); 
      background-size: cover;
      background-position: center;
      min-height: 480px;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      position: relative;
  }
  .image-side .floral {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background:
      radial-gradient(ellipse 120px 90px at 20% 18%, rgba(255,182,193,.55) 0%, transparent 70%),
      radial-gradient(ellipse 80px 70px at 75% 8%, rgba(255,200,210,.45) 0%, transparent 70%),
      radial-gradient(ellipse 60px 55px at 90% 35%, rgba(240,160,175,.35) 0%, transparent 70%),
      radial-gradient(ellipse 100px 80px at 10% 55%, rgba(255,192,203,.4) 0%, transparent 70%),
      radial-gradient(ellipse 70px 60px at 60% 80%, rgba(255,182,193,.3) 0%, transparent 70%);
  }
  /* Petal shapes */
  .petal {
    position: absolute;
    border-radius: 50% 0 50% 0;
    opacity: .35;
    pointer-events: none;
  }

  .image-side .model-placeholder {
    position: relative;
    z-index: 2;
    width: 85%;
    max-width: 260px;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-bottom: 20px;
  }

  /* SVG model illustration */
  .model-svg {
    width: 100%;
    filter: drop-shadow(0 20px 40px rgba(100,50,60,.25));
  }

  .brand-tag {
    position: absolute;
    top: 50%; right: 24px;
    transform: translateY(-50%);
    text-align: right;
    z-index: 3;
  }
  .brand-tag .cn {
    font-family: var(--font-h);
    font-size: 22px;
    font-style: italic;
    color: #fff;
    text-shadow: 0 1px 8px rgba(0,0,0,.4);
    display: block;
    line-height: 1;
  }
  .brand-tag .en {
    font-size: 10px;
    letter-spacing: .18em;
    color: rgba(255,255,255,.85);
    text-transform: uppercase;
    text-shadow: 0 1px 6px rgba(0,0,0,.35);
  }

  /* product bottles */
  .bottle-group {
    position: absolute;
    bottom: 20px; right: 16px;
    display: flex;
    gap: 6px;
    align-items: flex-end;
    z-index: 3;
  }

  /* ── FOOTER ── */
  .page-footer {
    width: 100%;
    max-width: 900px;
    text-align: center;
    font-size: 10px;
    letter-spacing: .1em;
    color: #ffffff;
    text-transform: uppercase;
    margin-top: 4px;
  }

  /* ── HIDDEN PANEL SYSTEM ── */
  .panel { display: none; }
  .panel.active { display: block; }

  /* checkbox */
  .check-field {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 18px;
  }
  .check-field input[type=checkbox] {
    width: 16px; height: 16px;
    accent-color: var(--rose);
    margin-top: 2px;
    flex-shrink: 0;
  }
  .check-field span {
    font-size: 11px;
    color: var(--mid);
    line-height: 1.55;
  }
  .check-field a { color: var(--rose); text-decoration: none; }

  /* name row */
  .name-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

  @media (max-width: 640px) {
    .card { grid-template-columns: 1fr; }
    .image-side { display: none; }
    .form-side { padding: 36px 28px 32px; }
    .name-row { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>


<div class="topbar">
  <span>✦</span> Beauty Always Network &nbsp;·&nbsp; Members Portal
</div>

<!-- ════════════════════════════
     SECTION 1 – LOGIN
════════════════════════════ -->
<div class="section-label">Login / Register</div>

<div class="card" id="main-card">

  <!-- FORM SIDE -->
  <div class="form-side">

    <!-- LOGIN PANEL -->
    <div class="panel active" id="panel-login">
      <div class="brand">Beauty Always Network</div>
      <div class="title">Welcome Back to<br>Beauty Always!</div>
      <div class="subtitle">Enter your registered data to log in</div>

      <div class="tabs">
        <button class="tab active" onclick="switchTab(this,'email-tab','phone-tab')">Email / Username</button>
        <button class="tab" onclick="switchTab(this,'phone-tab','email-tab')">Phone Number</button>
      </div>


      <!-- Email tab -->
      <div id="email-tab">
        <?php if ($msg && isset($_GET['from']) && $_GET['from']==='login'): ?>
          <div class="info-box" style="color:red;"> <?= $msg ?> </div>
        <?php endif; ?>
        <form method="POST" action="login.php" onsubmit="return setFrom('login')">
          <div class="field">
            <label>Email</label>
            <input type="text" name="email" placeholder="Enter Email" required>
          </div>
          <div class="field">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter Password" required>
          </div>
          <button type="submit" class="btn-primary">Login</button>
        </form>
        <div class="switch-link">Not a member? <a onclick="showPanel('panel-register')">Sign Up</a></div>
      </div>

      <!-- Phone tab -->
      <div id="phone-tab" style="display:none">
        <div class="info-box">Login via nomor HP belum tersedia.</div>
        <div class="switch-link">Not a member? <a onclick="showPanel('panel-register')">Sign Up</a></div>
      </div>
    </div>

    <!-- REGISTER PANEL -->
   <div class="panel" id="panel-register">
  <div class="brand">Beauty Always Network</div>
  <div class="title">Create Your<br>Account</div>
  <div class="subtitle">Join our beauty community today</div>

  <?php if ($msg && isset($_GET['from']) && $_GET['from']==='register'): ?>
    <div class="info-box" style="color:red;"> <?= $msg ?> </div>
  <?php endif; ?>
  <form method="POST" action="register.php" onsubmit="return setFrom('register')">

  <div class="name-row">
    <div class="field">
      <label>First Name</label>
      <input type="text" name="first_name" placeholder="First Name" required>
    </div>
    <div class="field">
      <label>Last Name</label>
      <input type="text" name="last_name" placeholder="Last Name" required>
    </div>
  </div>

  <div class="field">
    <label>Email</label>
    <input type="email" name="email" placeholder="Enter your email" required>
  </div>

  <div class="field">
    <label>Phone Number</label>
    <div class="phone-row">
      <select class="country-select">
        <option>+62</option>
      </select>
      <input type="tel" name="phone" placeholder="Enter Phone Number" required>
    </div>
  </div>

  <div class="field">
    <label>Password</label>
    <input type="password" name="password" placeholder="Create a password" required>
  </div>

  <div class="field">
    <label>Confirm Password</label>
    <input type="password" name="confirm_password" placeholder="Repeat your password" required>
  </div>

  <div class="check-field">
    <input type="checkbox" required>
    <span>I agree to the Terms</span>
  </div>

  <button type="submit" class="btn-primary">Create Account</button>

</form>

  <div class="switch-link">
    Already a member? <a onclick="showPanel('panel-login')">Sign In</a>
  </div>
</div>

    <!-- FORGOT PASSWORD PANEL -->
    <div class="panel" id="panel-forgot">
      <a class="back-link" onclick="showPanel('panel-login')">← Back to Login</a>
      <div class="brand">Beauty Always Network</div>
      <div class="title">Reset Your<br>Password</div>
      <div class="subtitle">Kami akan reset password Anda ke default (123456)</div>
      <?php if ($msg && isset($_GET['from']) && $_GET['from']==='forgot'): ?>
        <div class="info-box" style="color:<?= strpos($msg,'berhasil')!==false?'green':'red' ?>;"> <?= $msg ?> </div>
      <?php endif; ?>
      <form method="POST" action="forgot.php" onsubmit="return setFrom('forgot')">
        <div class="field">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="Enter your registered email" required>
        </div>
        <button type="submit" class="btn-primary">Reset Password</button>
      </form>
      <div class="switch-link" style="margin-top:14px">Remember your password? <a onclick="showPanel('panel-login')">Sign In</a></div>
    </div>

    <!-- SUCCESS PANEL -->
    <div class="panel" id="panel-success">
      <a class="back-link" onclick="showPanel('panel-login')">← Back to Login</a>
      <div class="success-msg">
        <div class="success-icon">✓</div>
        <h3>Check Your Email!</h3>
        <p>We've sent a password reset link to your email address.<br>Please check your inbox and follow the instructions.</p>
      </div>
      <div style="margin-top:32px">
        <button class="btn-primary" onclick="showPanel('panel-login')">Back to Login</button>
      </div>
      <div class="switch-link" style="margin-top:14px">Didn't receive the email? <a onclick="showPanel('panel-forgot')">Resend</a></div>
    </div>

  </div>

  <!-- IMAGE SIDE -->
  <div class="image-side">

    <div class="brand-tag">
      <span class="cn">白鹿</span>
      <span class="en">Beauty Always Ambassador</span>
    </div>
  </div>

</div>

<div class="page-footer">Copyright © 2019 – 2026 Beauty Always Network · All rights reserved</div>

<script>
  // Untuk menambah parameter ?from=login/register/forgot pada action form
  function setFrom(val) {
    var f = event.target;
    if (f.action.indexOf('?') === -1) {
      f.action += '?from=' + val;
    } else {
      f.action += '&from=' + val;
    }
    return true;
  }
  function showPanel(id) {
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
    document.getElementById(id).classList.add('active');
  }
  function switchTab(btn, show, hide) {
    btn.closest('.tabs').querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(show).style.display = 'block';
    document.getElementById(hide).style.display = 'none';
  }
</script>
</body>
</html>