<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/order_helpers.php';
require_once __DIR__ . '/../layout/store_navbar.php';

$cart = getCartItems();
$products = [];
$total = 0;
$totalItem = 0;

if ($cart) {
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $sql = "SELECT * FROM products WHERE id_product IN ($placeholders)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$ids);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $products[(int) $row['id_product']] = $row;
    }
}

renderStoreNavbar('keranjang');
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Keranjang Belanja</h2>
            <p class="text-muted mb-0">Cek ulang produk sebelum checkout.</p>
        </div>
        <a href="index.php?page=katalog" class="btn btn-outline-primary rounded-3"><i class="bi bi-arrow-left me-1"></i> Lanjut Belanja</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <?php if (!$cart): ?>
                    <div class="p-5 text-center text-muted">
                        <div class="display-4 mb-2">🛒</div>
                        <h5 class="fw-bold">Keranjang masih kosong</h5>
                        <p>Tambahkan produk dari katalog terlebih dahulu.</p>
                        <a href="index.php?page=katalog" class="btn btn-primary rounded-3">Lihat Katalog</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3">Produk</th>
                                    <th class="py-3 text-center">Qty</th>
                                    <th class="py-3">Harga</th>
                                    <th class="py-3">Subtotal</th>
                                    <th class="py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $idProduct => $qty): ?>
                                    <?php if (!isset($products[$idProduct])) { continue; } ?>
                                    <?php
                                    $product = $products[$idProduct];
                                    $qty = min((int) $qty, max(0, (int) $product['stok']));
                                    $subtotal = (float) $product['harga'] * $qty;
                                    $total += $subtotal;
                                    $totalItem += $qty;
                                    $image = $product['gambar'] ?? '-';
                                    $imagePath = ($image && $image !== '-') ? 'uploads/products/' . $image : '';
                                    ?>
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <?php if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)): ?>
                                                    <img src="<?= e($imagePath) ?>" class="product-img" alt="<?= e($product['nama_produk']) ?>">
                                                <?php else: ?>
                                                    <div class="product-img d-flex align-items-center justify-content-center text-muted">🧴</div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-semibold"><?= e($product['nama_produk']) ?></div>
                                                    <div class="text-muted small">Stok tersedia: <?= e($product['stok']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center gap-2">
                                                <a href="process/update_cart.php?action=minus&id=<?= e($idProduct) ?>" class="btn btn-outline-secondary btn-sm rounded-3">−</a>
                                                <span class="fw-bold"><?= e($qty) ?></span>
                                                <a href="process/update_cart.php?action=plus&id=<?= e($idProduct) ?>" class="btn btn-outline-secondary btn-sm rounded-3">+</a>
                                            </div>
                                        </td>
                                        <td><?= rupiah($product['harga']) ?></td>
                                        <td class="fw-semibold"><?= rupiah($subtotal) ?></td>
                                        <td class="text-center">
                                            <a href="process/update_cart.php?action=remove&id=<?= e($idProduct) ?>" class="btn btn-outline-danger btn-sm rounded-3" onclick="return confirm('Hapus produk ini dari keranjang?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3">Ringkasan</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Item</span>
                    <span class="fw-semibold"><?= e($totalItem) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold"><?= rupiah($total) ?></span>
                </div>
                <hr>
                <?php if ($cart && $totalItem > 0): ?>
                    <a href="index.php?page=checkout" class="btn btn-primary w-100 rounded-3 mb-2"><i class="bi bi-credit-card me-1"></i> Checkout</a>
                    <a href="process/update_cart.php?action=clear" class="btn btn-outline-danger w-100 rounded-3" onclick="return confirm('Kosongkan keranjang?')">Kosongkan</a>
                <?php else: ?>
                    <button class="btn btn-secondary w-100 rounded-3" disabled>Checkout</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
