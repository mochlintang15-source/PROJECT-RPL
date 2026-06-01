<?php

$products = [

    [
        "nama" => "Brightening Serum",
        "harga" => 120000,
        "kategori" => "Serum",
        "deskripsi" => "Mencerahkan kulit dan membantu menyamarkan noda hitam.",
        "img" => "https://images.unsplash.com/photo-1601049676869-702ea24cfd58?q=80&w=800"
    ],

    [
        "nama" => "Moisturizer",
        "harga" => 95000,
        "kategori" => "Moisturizer",
        "deskripsi" => "Menjaga kelembapan kulit agar tetap sehat dan glowing.",
        "img" => "https://images.unsplash.com/photo-1596462502278-27bfdc403348?q=80&w=800"
    ],

    [
        "nama" => "Facial Wash",
        "harga" => 70000,
        "kategori" => "Cleanser",
        "deskripsi" => "Membersihkan wajah dari minyak dan debu tanpa membuat kulit kering.",
        "img" => "https://images.unsplash.com/photo-1556228720-195a672e8a03?q=80&w=800"
    ],

    [
        "nama" => "Sunscreen",
        "harga" => 85000,
        "kategori" => "Sunscreen",
        "deskripsi" => "Melindungi kulit dari paparan sinar UV dan menjaga kulit tetap sehat.",
        "img" => "https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=800"
    ]

];

?>

<?php
$navLinks = [
    ["label" => "Home", "link" => "#"],
    ["label" => "Shop", "link" => "#"],
    ["label" => "Promo", "link" => "#"],
    ["label" => "Blog", "link" => "#"]
];

$cartCount = 0; // default keranjang

$user = null; // atau isi data user kalau login
?>

<!DOCTYPE html>
<html>
<head>
    <title>Beauty Store</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<?php include 'navbar.php'; ?>
<script src="assets/kod.js"></script>

<!-- HERO -->
<section class="hero container">
    <div>
        <h1>Glow Your Skin Naturally</h1>
        <p>Skincare terbaik untuk kulit sehat & glowing</p>
        <button class="btn">Shop Now</button>
    </div>

    <div class="hero-img">
        <img src="https://i.pinimg.com/736x/3a/3c/34/3a3c34e91e7af0ee3e81736459e6c738.jpg">
    </div>
</section>

<!-- BLOG -->
<section class="section container">
    <h2 class="title">Blog & Tips Kecantikan</h2>

    <div class="blog">

        <div class="blog-card">
            <img src="https://images.unsplash.com/photo-1601049676869-702ea24cfd58">
            <div class="blog-content">
                <h3>Manfaat Brightening Serum untuk Kulit Glowing</h3>
                <p>
                    Brightening serum membantu mencerahkan kulit, menyamarkan noda hitam, 
                    dan memberikan kelembapan ekstra. Gunakan secara rutin untuk hasil maksimal.
                </p>
                <a href="#">Baca Selengkapnya</a>
            </div>
        </div>

    </div>
</section>
<!-- logo pabrik -->
<section class="section container">
    <h2 class="title">Brand Kosmetik</h2>

    <div class="logo-grid">

        <div class="logo-card">
            <div class="logo-box">
                <img src="https://soc-phoenix.s3-ap-southeast-1.amazonaws.com/wp-content/uploads/2018/08/14203318/KsQxopLOVFOEVMQR-2459-wardah-1524032520_1.jpg">
            </div>
        </div>

        <div class="logo-card">
            <div class="logo-box">
                <img src="https://fabrikbrands.com/wp-content/uploads/Beauty-Brand-Logos-25-1200x750.png">
            </div>
        </div>

        <div class="logo-card">
            <div class="logo-box">
                <img src="https://fabrikbrands.com/wp-content/uploads/Beauty-Brand-Logos-21-1200x750.png">
            </div>
        </div>

        <div class="logo-card">
            <div class="logo-box">
                <img src="https://fabrikbrands.com/wp-content/uploads/Beauty-Brand-Logos-19-1200x750.png">
            </div>
        </div>

        <div class="logo-card">
            <div class="logo-box">
                <img src="https://fabrikbrands.com/wp-content/uploads/Beauty-Brand-Logos-10-1200x750.png">
            </div>
        </div>

        <div class="logo-card">
            <div class="logo-box">
                <img src="https://fabrikbrands.com/wp-content/uploads/Beauty-Brand-Logos-24-1200x750.png">
            </div>
        </div>

    </div>
</section>
<!-- KATEGORI -->
<section class="section container">
    <h2 class="title">Kategori Produk</h2>

    <div class="categories">

        <a href="ketegori.php" class="cat-link">
            <div class="cat">
               <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03">
              <h4>Skincare</h4>
              <p>Perawatan kulit harian untuk hasil glowing</p>
            </div>
        </a>
        <a href="shop.php" class="cat-link">
            <div class="cat">
              <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9">
              <h4>Makeup</h4>
              <p>Tampil cantik dengan produk terbaik</p>
           </div>
        </a>

        <div class="cat">
            <img src="https://images.unsplash.com/photo-1601049676869-702ea24cfd58">
            <h4>Serum</h4>
            <p>Nutrisi intensif untuk kulit sehat</p>
        </div>

        <div class="cat">
            <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348">
            <h4>Sunscreen</h4>
            <p>Perlindungan maksimal dari sinar UV</p>
        </div>

    </div>
</section>

<!-- PRODUK -->
    <section class="section container">
        <h2 class="title">
            Produk Unggulan
        </h2>
        <div class="products">
            <?php foreach($products as $i => $p): ?>
            <div class="card">
                <!-- GAMBAR -->
                <div class="card-img">
                    <img src="<?= $p['img']; ?>" alt="<?= $p['nama']; ?>">
                </div>
                <!-- ISI -->
                <div class="card-body">
                    <h4>
                        <?= $p['nama']; ?>
                    </h4>
                    <p class="kategori">
                        <?= $p['kategori']; ?>
                    </p>
                    <p class="desc">
                        <?= $p['deskripsi']; ?>
                    </p>
                    <div class="card-footer">
                        <p class="price">
                            Rp <?= number_format($p['harga'],0,',','.'); ?>
                        </p>
                        <!-- TOMBOL BELI -->
                        <a href="buy.php?id=<?= $i; ?>" class="btn">
                            Beli
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
<!-- PROMO -->
<section class="section container">
    <div class="promo">
        <h2>Diskon 50%</h2>
        <p>Khusus member baru</p>
        <button class="btn">Ambil Promo</button>
    </div>
</section>


<?php include 'footer.php'; ?>

</body>
</html>