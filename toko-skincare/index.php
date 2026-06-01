<?php
require_once __DIR__ . '/config/helpers.php';

$page = getParam('page', 'home');

$adminPages = [
    'admin-dashboard',
    'admin-users',
    'admin-produk',
    'tambah-produk',
    'edit-produk',
    'admin-order',
    'update-order',
];

if (in_array($page, $adminPages, true)) {
    requireAdmin();
}

require_once __DIR__ . '/layout/header.php';

$messages = [
    'add_user'       => ['success', 'User berhasil ditambahkan.'],
    'update_user'    => ['success', 'User berhasil diperbarui.'],
    'delete_user'    => ['success', 'User berhasil dihapus.'],
    'add_product'    => ['success', 'Produk berhasil ditambahkan.'],
    'update_product' => ['success', 'Produk berhasil diperbarui.'],
    'delete_product' => ['success', 'Produk berhasil dihapus.'],
    'update_order'   => ['success', 'Status order berhasil diperbarui.'],
    'delete_order'   => ['success', 'Order berhasil dihapus.'],
    'logout'         => ['success', 'Kamu berhasil logout.'],
    'login_required' => ['warning', 'Silakan login terlebih dahulu.'],
    'forbidden'      => ['danger', 'Kamu tidak memiliki akses ke halaman tersebut.'],
    'login_failed'   => ['danger', 'Email atau password salah.'],
    'invalid'        => ['danger', 'Data yang dikirim tidak valid.'],
    'stock_not_enough'=> ['danger', 'Stok produk tidak cukup untuk memproses order ini.'],
];

$statusKey = getParam('success') ?: getParam('error');
if ($statusKey && isset($messages[$statusKey])):
    [$type, $message] = $messages[$statusKey];
    ?>
    <div class="container mt-3">
        <div class="alert alert-<?= e($type) ?> alert-dismissible fade show" role="alert">
            <?= e($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<?php
switch ($page) {
    case 'login':
        include __DIR__ . '/auth/login.php';
        break;

    case 'admin-dashboard':
        include __DIR__ . '/admin/dashboard.php';
        break;

    case 'admin-users':
        include __DIR__ . '/admin/users.php';
        break;

    case 'admin-produk':
        include __DIR__ . '/admin/produk.php';
        break;

    case 'tambah-produk':
        include __DIR__ . '/admin/tambah_produk.php';
        break;

    case 'edit-produk':
        include __DIR__ . '/admin/edit_produk.php';
        break;

    case 'admin-order':
        include __DIR__ . '/admin/order.php';
        break;

    case 'update-order':
        include __DIR__ . '/admin/update_order.php';
        break;

    case 'katalog':
        include __DIR__ . '/user/katalog.php';
        break;

    default:
        include __DIR__ . '/user/home.php';
        break;
}

require_once __DIR__ . '/layout/footer.php';
