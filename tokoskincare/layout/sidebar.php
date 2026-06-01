<?php
require_once __DIR__ . '/../config/helpers.php';

function renderSidebar(string $active = 'dashboard'): void
{
    $menus = [
        'dashboard' => ['📊', 'Dashboard', 'index.php?page=admin-dashboard'],
        'users'     => ['👥', 'Manage Users', 'index.php?page=admin-users'],
        'produk'    => ['📦', 'Produk', 'index.php?page=admin-produk'],
        'order'     => ['🛒', 'Order', 'index.php?page=admin-order'],
        'bukti'     => ['🧾', 'Bukti Pembayaran', 'index.php?page=admin-bukti-pembayaran'],
    ];
    ?>
    <aside class="sidebar d-flex flex-column p-3">
        <div class="d-flex align-items-center gap-2 mb-4">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold"
                style="width:38px; height:38px; background: linear-gradient(135deg,#6c63ff,#a78bfa); font-size:1rem;">
                ✨
            </div>
            <span class="fw-bold fs-6">All Day Skincare</span>
        </div>

        <nav class="d-flex flex-column gap-1 flex-grow-1">
            <?php foreach ($menus as $key => $menu): ?>
                <a href="<?= e($menu[2]) ?>" class="sidebar-link <?= $active === $key ? 'active' : '' ?>">
                    <span><?= $menu[0] ?></span> <?= e($menu[1]) ?>
                </a>
            <?php endforeach; ?>

            <hr class="my-2">
            <a href="index.php?page=katalog" class="sidebar-link">
                🛍️ Lihat Toko
            </a>
            <a href="auth/logout.php" class="sidebar-link text-danger">
                🚪 Logout
            </a>
        </nav>
    </aside>
    <?php
}
