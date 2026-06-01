<?php
include 'navbar.php';
$info = [
  "storeName" => "BeautyFy",
  "tagline" => "Skincare premium untuk kulit Indonesia",
  "founded" => "2025",

  "mission" => "Menghadirkan produk skincare berkualitas tinggi yang aman dan terjangkau.",
  "vision" => "Menjadi destinasi utama skincare terpercaya di Indonesia.",

  "values" => [
    ["title"=>"100% Original","desc"=>"Produk dijamin asli dari brand resmi"],
    ["title"=>"Cruelty-Free","desc"=>"Tidak diuji pada hewan"],
    ["title"=>"Customer First","desc"=>"Kepuasan pelanggan utama"],
  ],

  "team" => [
    ["name"=>"Fendi","role"=>"Founder & CEO","photo"=>"image/fendi.jpeg"],
    ["name"=>"Sirul","role"=>"Designer","photo"=>"image/sirul.jpeg"],
    ["name"=>"Lintang","role"=>"Head of Product","photo"=>"image/lintang.jpeg"],
    ["name"=>"RinaGabriel","role"=>"Customer Care","photo"=>"image/gabriel.jpeg"],
    ["name"=>"  Ika","role"=>"Marketing","photo"=>"image/ika.jpeg"],
   
  ]
];
?>
<!DOCTYPE html>
<html>
<head>
  <title>About</title>
  <link rel="stylesheet" href="about.css">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond&family=Jost&display=swap" rel="stylesheet">
</head>

<body>
<?php
$navLinks = [
  ["link"=>"produk.php","label"=>"Shop"],
  ["link"=>"brand.php","label"=>"Brand"],
  ["link"=>"about.php","label"=>"About Us"],
  ["link"=>"produk.php","label"=>"Produk"],
  ["link"=>"produk.php?kategori=makeup","label"=>"Makeup"],
];

$cartCount = 3; // dummy
$user = null; // ganti jadi session kalau ada login
?>



<!-- HERO -->
<section class="hero">
  <p class="label">About Us</p>
  <h1><?= $info['storeName']; ?></h1>
  <p class="subtitle"><?= $info['tagline']; ?></p>
  <p class="small">Berdiri sejak <?= $info['founded']; ?></p>
</section>

<!-- MISI VISI -->
<section class="visi">
  <div>
    <p class="label">Misi Kami</p>
    <h2>Apa yang kami kerjakan</h2>
    <p><?= $info['mission']; ?></p>
  </div>

  <div>
    <p class="label">Visi Kami</p>
    <h2>Tujuan jangka panjang</h2>
    <p><?= $info['vision']; ?></p>
  </div>
</section>

<!-- NILAI -->
<section class="nilai">
  <h2>Nilai yang Kami Pegang</h2>

  <div class="nilai-grid">
    <?php foreach($info['values'] as $v): ?>
      <div class="card">
        <div class="icon">✦</div>
        <h3><?= $v['title']; ?></h3>
        <p><?= $v['desc']; ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- TIM -->
<section class="tim">
  <h2>Tim Kami</h2>

  <div class="tim-grid">
    <?php foreach($info['team'] as $t): ?>
      <div class="tim-card">
        <div class="photo">
          <img src="<?= $t['photo']; ?>" onerror="this.style.display='none'">
        </div>
        <p class="name"><?= $t['name']; ?></p>
        <p class="role"><?= $t['role']; ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<footer class="footer">

  <div class="footer-container">

    <!-- BRAND -->
    <div>
      <h3 class="brand">Allure<span>.</span></h3>
      <p class="desc">
        Tujuan terpercaya untuk skincare & kosmetik premium di Indonesia.
      </p>

      <div class="socials">
        <a href="#">IG</a>
        <a href="#">FB</a>
        <a href="#">YT</a>
        <a href="#">IN</a>
      </div>
    </div>

    <!-- BANTUAN -->
    <div>
      <h4>Bantuan</h4>
      <ul>
        <li><a href="#">Halaman</a></li>
        <li>Hubungi: 0876-4825-5942</li>
        <li><a href="#">FAQ</a></li>
      </ul>
    </div>

    <!-- TENTANG -->
    <div>
      <h4>Tentang</h4>
      <ul>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Wishlist</a></li>
        <li><a href="#">Karir</a></li>
      </ul>
    </div>

    <!-- PEMBAYARAN -->
    <div>
      <h4>Pembayaran</h4>
      <p class="pay-text">Kami menerima:</p>

      <div class="payments">
        <?php 
        $pay = ["GoPay","OVO","DANA","ShopeePay","Transfer","COD"];
        foreach($pay as $p): ?>
          <span><?= $p; ?></span>
        <?php endforeach; ?>
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    © <?= date('Y'); ?> Allure. Semua hak cipta dilindungi.
  </div>

</footer>
</body>
</html>