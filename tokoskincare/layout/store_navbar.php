<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/order_helpers.php';

function renderStoreNavbar(string $active = 'home'): void
{
    $cartCount = currentCartCount();
    ?>
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">✨ All Day Skincare</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#storeNavbar" aria-controls="storeNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="storeNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link <?= $active === 'home' ? 'active fw-semibold text-primary' : '' ?>" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?= $active === 'katalog' ? 'active fw-semibold text-primary' : '' ?>" href="index.php?page=katalog">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link <?= $active === 'keranjang' ? 'active fw-semibold text-primary' : '' ?>" href="index.php?page=keranjang">Keranjang</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="index.php?page=keranjang" class="btn btn-outline-primary btn-sm rounded-3">
                        <i class="bi bi-bag me-1"></i> Keranjang
                        <?php if ($cartCount > 0): ?>
                            <span class="badge text-bg-primary ms-1"><?= e($cartCount) ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <a href="index.php?page=admin-dashboard" class="btn btn-primary btn-sm rounded-3">Dashboard</a>
                        <?php endif; ?>
                        <a href="auth/logout.php" class="btn btn-outline-danger btn-sm rounded-3">Logout</a>
                    <?php else: ?>
                        <a href="index.php?page=login" class="btn btn-primary btn-sm rounded-3">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <?php
}
