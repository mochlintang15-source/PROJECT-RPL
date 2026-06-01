<?php
require_once __DIR__ . '/../config/helpers.php';
?>
<?php if (isLoggedIn()): ?>
    <div class="container py-5" style="max-width: 520px;">
        <div class="alert alert-info rounded-4">
            Kamu sudah login sebagai <strong><?= e($_SESSION['user']['nama'] ?? 'User') ?></strong>.
        </div>
        <a href="index.php?page=admin-dashboard" class="btn btn-primary rounded-3">Masuk Dashboard</a>
        <a href="auth/logout.php" class="btn btn-outline-danger rounded-3">Logout</a>
    </div>
<?php else: ?>
    <div class="container py-5" style="max-width: 420px;">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="text-center mb-4">
                <div class="rounded-4 d-inline-flex align-items-center justify-content-center text-white mb-3"
                    style="width:56px; height:56px; background: linear-gradient(135deg,#6c63ff,#a78bfa); font-size:1.5rem;">
                    ✨
                </div>
                <h4 class="fw-bold mb-1">Login</h4>
                <p class="text-muted mb-0">Masuk ke dashboard toko</p>
            </div>

            <form action="process/login_process.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control rounded-3" placeholder="admin@email.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control rounded-3" placeholder="Password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-3">Login</button>
            </form>
        </div>
    </div>
<?php endif; ?>
