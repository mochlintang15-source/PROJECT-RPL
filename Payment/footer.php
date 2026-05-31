<!-- footer.php -->

<!-- PASTIKAN CSS TERLOAD -->
<link rel="stylesheet" href="style.css">

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