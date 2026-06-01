<?php
// ===== DEFAULT BIAR TIDAK ERROR =====
if(!isset($navLinks)){
  $navLinks = [
    ["link"=>"indx.php","label"=>"Home"],
    ["link"=>"index.php","label"=>"Produk"],
    ["link"=>"keranjang.php","label"=>"Keranjang"],
  ];
}

if(!isset($cartCount)){
  $cartCount = 0;
}

if(!isset($user)){
  $user = null;
}
?>

<link rel="stylesheet" href="navbar.css">

<header class="header">

  <!-- TOP BAR -->
  <div class="topbar">
    <span class="brand">ALL DAY SHOPPING</span>
    <span class="sub">— pengiriman ke seluruh Indonesia</span>
  </div>

  <!-- MAIN NAV -->
  <div class="nav-container">

    <!-- LOGO -->
    <a href="index.php" class="logo">Allure<span>.</span></a>

    <!-- MENU -->
    <nav class="nav-links">
      <?php foreach($navLinks as $n): ?>
        <a href="<?= htmlspecialchars($n['link']); ?>">
          <?= htmlspecialchars($n['label']); ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <!-- RIGHT -->
    <div class="nav-right">

      <!-- SEARCH -->
      <form action="produk.php" method="GET" class="search-box">
        <input type="text" name="q" placeholder="Cari produk...">
      </form>

      <!-- CART -->
      <a href="keranjang.php" class="cart">
        🛍
        <?php if($cartCount > 0): ?>
          <span class="badge"><?= $cartCount; ?></span>
        <?php endif; ?>
      </a>

      <!-- USER -->
      <?php if($user): ?>
        <div class="dropdown">
          <button onclick="toggleDropdown()">👤</button>

          <div id="dropdownMenu" class="dropdown-content">
            <a href="dashboard.php">Dashboard</a>
            <a href="pesanan.php">Pesanan</a>
            <a href="logout.php">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" class="login">Login</a>
      <?php endif; ?>

    </div>
  </div>
</header>

<script>
function toggleDropdown(){
  const menu = document.getElementById("dropdownMenu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}
</script>