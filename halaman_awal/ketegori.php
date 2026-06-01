<?php
$products = [
    [
        "nama" => "Brightening Serum",
        "harga" => 120000,
        "kategori" => "Serum",
        "deskripsi" => "Mencerahkan kulit dan mengurangi noda hitam.",
        "img" => "https://images.unsplash.com/photo-1601049676869-702ea24cfd58"
    ],
    [
        "nama" => "Moisturizer",
        "harga" => 95000,
        "kategori" => "Moisturizer",
        "deskripsi" => "Menjaga kelembapan kulit.",
        "img" => "https://images.unsplash.com/photo-1596462502278-27bfdc403348"
    ],
    [
        "nama" => "Facial Wash",
        "harga" => 70000,
        "kategori" => "Cleanser",
        "deskripsi" => "Membersihkan wajah tanpa kering.",
        "img" => "https://images.unsplash.com/photo-1556228720-195a672e8a03"
    ],
    [
        "nama" => "Sunscreen",
        "harga" => 85000,
        "kategori" => "Sunscreen",
        "deskripsi" => "Melindungi dari sinar UV.",
        "img" => "https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9"
    ],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kategori Skincare</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<?php include 'navbar.php'; ?>

<!-- BANNER -->
<section class="banner">
    <h1>Kategori Skincare</h1>
    <p>Pilih produk terbaik sesuai kebutuhan kulitmu</p>
</section>

<!-- FILTER -->
<div class="filter container">
    <button>All</button>
    <button>Serum</button>
    <button>Moisturizer</button>
    <button>Cleanser</button>
    <button>Sunscreen</button>
</div>

<!-- PRODUK -->
<section class="products container">

<?php foreach($products as $p): ?>

<div class="card">

    <img src="<?= $p['img']; ?>">

    <div class="card-body">

        <h4><?= $p['nama']; ?></h4>

        <p class="kategori">
            <?= $p['kategori']; ?>
        </p>

        <p class="desc">
            <?= $p['deskripsi']; ?>
        </p>

        <div class="card-footer">

            <span class="price">
                Rp <?= number_format($p['harga']); ?>
            </span>

            <button class="btn">
                Beli
            </button>

        </div>

    </div>

</div>

<?php endforeach; ?>

</section>

<?php include 'footer.php'; ?>

</body>
</html>