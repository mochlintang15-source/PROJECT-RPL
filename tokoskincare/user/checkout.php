<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/order_helpers.php';
require_once __DIR__ . '/../layout/store_navbar.php';

$cart = getCartItems();
if (!$cart) {
    renderStoreNavbar('keranjang');
    ?>
    <div class="container py-5">
        <div class="alert alert-warning rounded-4 text-center">
            Keranjang masih kosong. <a href="index.php?page=katalog" class="alert-link">Pilih produk dulu</a>.
        </div>
    </div>
    <?php
    return;
}

$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id_product IN ($placeholders)");
mysqli_stmt_bind_param($stmt, $types, ...$ids);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[(int) $row['id_product']] = $row;
}

$subtotal = 0;
$totalItem = 0;
foreach ($cart as $idProduct => $qty) {
    if (!isset($products[$idProduct])) {
        continue;
    }
    $qty = min((int) $qty, (int) $products[$idProduct]['stok']);
    $subtotal += (float) $products[$idProduct]['harga'] * $qty;
    $totalItem += $qty;
}

if ($totalItem <= 0) {
    renderStoreNavbar('keranjang');
    ?>
    <div class="container py-5">
        <div class="alert alert-danger rounded-4 text-center">
            Stok produk di keranjang tidak cukup. Silakan kembali ke <a href="index.php?page=keranjang" class="alert-link">keranjang</a>.
        </div>
    </div>
    <?php
    return;
}

$user = currentUser() ?? [];
$shippingOptions = [
    ['JNE', 'REG', 10000, '3–5 hari'],
    ['JNE', 'YES', 20000, '1 hari'],
    ['J&T', 'REG', 12000, '3–5 hari'],
    ['J&T', 'YES', 22000, '1 hari'],
    ['SiCepat', 'REG', 11000, '3–5 hari'],
    ['SiCepat', 'BEST', 21000, '1 hari'],
];
$paymentMethods = ['Gopay', 'OVO', 'Dana', 'Transfer'];

renderStoreNavbar('keranjang');
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Checkout</h2>
            <p class="text-muted mb-0">Lengkapi data pengiriman dan metode pembayaran.</p>
        </div>
        <a href="index.php?page=keranjang" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>

    <form method="POST" action="process/checkout_process.php" id="checkoutForm">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3">Data Pembeli</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="nama" value="<?= e($user['nama'] ?? '') ?>" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor HP</label>
                            <input type="text" name="no_hp" value="<?= e($user['no_hp'] ?? '') ?>" class="form-control rounded-3" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control rounded-3" rows="3" required><?= e($user['alamat'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3">Metode Pembayaran</h5>
                    <div class="row g-3">
                        <?php foreach ($paymentMethods as $i => $method): ?>
                            <div class="col-md-6">
                                <label class="border rounded-4 p-3 w-100 h-100 d-flex align-items-center gap-3 payment-option" style="cursor:pointer;">
                                    <input type="radio" name="metode_pembayaran" value="<?= e($method) ?>" class="form-check-input" <?= $i === 0 ? 'required' : '' ?>>
                                    <span class="fs-4">💳</span>
                                    <span class="fw-semibold"><?= e($method) ?></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-3">
                        <label class="form-label fw-semibold">Nomor pembayaran / rekening / e-wallet</label>
                        <input type="text" name="nomor_pembayaran" class="form-control rounded-3" placeholder="Contoh: 08xxxx / nomor rekening" required>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3">Jasa Pengiriman</h5>
                    <div class="row g-3">
                        <?php foreach ($shippingOptions as $i => [$kurir, $layanan, $ongkir, $estimasi]): ?>
                            <div class="col-md-6">
                                <label class="border rounded-4 p-3 w-100 h-100 d-flex align-items-start gap-3 shipping-option" style="cursor:pointer;">
                                    <input type="radio" name="shipping" value="<?= e($kurir . '|' . $layanan . '|' . $ongkir) ?>" data-ongkir="<?= e($ongkir) ?>" class="form-check-input shipping-radio" <?= $i === 0 ? 'required' : '' ?>>
                                    <span>
                                        <span class="fw-bold d-block"><?= e($kurir) ?> <?= e($layanan) ?></span>
                                        <span class="text-muted small d-block"><?= e($estimasi) ?></span>
                                        <span class="text-primary fw-semibold"><?= rupiah($ongkir) ?></span>
                                    </span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                    <div class="d-flex flex-column gap-3 mb-3">
                        <?php foreach ($cart as $idProduct => $qty): ?>
                            <?php if (!isset($products[$idProduct])) { continue; } ?>
                            <?php
                            $product = $products[$idProduct];
                            $qty = min((int) $qty, (int) $product['stok']);
                            $itemSubtotal = (float) $product['harga'] * $qty;
                            ?>
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <div class="fw-semibold"><?= e($product['nama_produk']) ?></div>
                                    <div class="text-muted small"><?= e($qty) ?> x <?= rupiah($product['harga']) ?></div>
                                </div>
                                <div class="fw-semibold text-end"><?= rupiah($itemSubtotal) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold" id="subtotalText" data-subtotal="<?= e($subtotal) ?>"><?= rupiah($subtotal) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Ongkir</span>
                        <span class="fw-semibold" id="ongkirText">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between fs-5 fw-bold mb-4">
                        <span>Total</span>
                        <span class="text-primary" id="totalText"><?= rupiah($subtotal) ?></span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 btn-lg"><i class="bi bi-check2-circle me-1"></i> Buat Pesanan</button>
                    <p class="text-muted small mt-3 mb-0">Setelah pesanan dibuat, kamu akan diarahkan untuk upload bukti pembayaran.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const subtotal = Number(document.getElementById('subtotalText').dataset.subtotal || 0);
const ongkirText = document.getElementById('ongkirText');
const totalText = document.getElementById('totalText');
const formatRupiah = (value) => 'Rp ' + Number(value).toLocaleString('id-ID');

document.querySelectorAll('.shipping-radio').forEach((radio) => {
    radio.addEventListener('change', () => {
        const ongkir = Number(radio.dataset.ongkir || 0);
        ongkirText.textContent = formatRupiah(ongkir);
        totalText.textContent = formatRupiah(subtotal + ongkir);
    });
});
</script>
