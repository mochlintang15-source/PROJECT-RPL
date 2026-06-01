<?php

$kategori = ["Serum", "Moisturizer", "Cleanser", "Sunscreen"];

$deskripsi = [
    "Membantu mencerahkan kulit wajah.",
    "Menjaga kelembapan kulit sepanjang hari.",
    "Membersihkan wajah dari debu dan minyak.",
    "Melindungi kulit dari sinar UV."
];

$gambar = [
    "https://images.unsplash.com/photo-1601049676869-702ea24cfd58?q=80&w=800",
    "https://images.unsplash.com/photo-1556228578-8c89e6adf883?q=80&w=800",
    "https://images.unsplash.com/photo-1556228720-195a672e8a03?q=80&w=800",
    "https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=800"
];

$products = [];

/* GENERATE 100 PRODUK */
for($i = 1; $i <= 100; $i++){

    $products[] = [
        "nama" => "Produk Skincare ".$i,
        "harga" => rand(50000,250000),
        "kategori" => $kategori[array_rand($kategori)],
        "deskripsi" => $deskripsi[array_rand($deskripsi)],
        "img" => $gambar[array_rand($gambar)]
    ];

}

/* PAGINATION */
$limit = 10;

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$start = ($page - 1) * $limit;

$total_products = count($products);

$total_pages = ceil($total_products / $limit);

$current_products = array_slice($products, $start, $limit);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Skincare</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <h1 class="title">Kategori Skincare</h1>

    <p class="subtitle">
        Temukan berbagai produk skincare terbaik untuk kebutuhan kulitmu
    </p>

    <!-- FILTER -->
    <div class="filter">

        <button>All</button>
        <button>Serum</button>
        <button>Moisturizer</button>
        <button>Cleanser</button>
        <button>Sunscreen</button>

    </div>

    <!-- PRODUCTS -->
    <section class="products">

    <?php foreach($current_products as $p): ?>

    <div class="card">
        <img src="<?= $p['img']; ?>">
        <div class="card-body">
            <h3><?= $p['nama']; ?></h3>
            <p class="kategori">
                <?= $p['kategori']; ?>
            </p>
            <p class="desc">
                <?= $p['deskripsi']; ?>
            </p>
            <div class="card-footer">
                <span class="price">
                    Rp <?= number_format($p['harga'],0,',','.'); ?>
                </span>
                <a href="buy.php?id=<?= $i; ?>" class="btn"> Beli</a>
            </div>
        </div>
    </div>

    <?php endforeach; ?>

    </section>

    <!-- PAGINATION -->
    <div class="pagination">

        <!-- PREV -->
        <?php if($page > 1): ?>
            <a href="?page=<?= $page-1; ?>">&laquo;</a>
        <?php endif; ?>

        <!-- NUMBER -->
        <?php for($i = 1; $i <= $total_pages; $i++): ?>

            <a href="?page=<?= $i; ?>"
               class="<?= ($page == $i) ? 'active' : ''; ?>">

               <?= $i; ?>

            </a>

        <?php endfor; ?>

        <!-- NEXT -->
        <?php if($page < $total_pages): ?>
            <a href="?page=<?= $page+1; ?>">&raquo;</a>
        <?php endif; ?>

    </div>

</div>

<?php include 'footer.php'; ?>

</body>
</html>