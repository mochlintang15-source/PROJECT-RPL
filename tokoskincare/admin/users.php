<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../layout/sidebar.php';

$search = trim((string) getParam('search'));
$role   = trim((string) getParam('role'));

$sql = 'SELECT * FROM users WHERE 1=1';
$params = [];
$types = '';

if ($search !== '') {
    $sql .= ' AND (nama LIKE ? OR email LIKE ? OR no_hp LIKE ? OR alamat LIKE ?)';
    $keyword = '%' . $search . '%';
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
    $types .= 'ssss';
}

if ($role !== '') {
    $sql .= ' AND role = ?';
    $params[] = $role;
    $types .= 's';
}

$sql .= ' ORDER BY id_user DESC';
$stmt = mysqli_prepare($conn, $sql);
if ($stmt && $params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
} else {
    $data = mysqli_query($conn, 'SELECT * FROM users ORDER BY id_user DESC');
}
?>

<div class="admin-layout d-flex" style="min-height: 100vh;">
    <?php renderSidebar('users'); ?>

    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-0">Manajemen Pengguna</h3>
                <small class="text-muted">Kelola akun admin dan user sesuai tabel users</small>
            </div>
            <button class="btn btn-primary rounded-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah User
            </button>
        </div>

        <form method="GET" class="mb-4">
            <input type="hidden" name="page" value="admin-users">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="position-relative">
                    <span class="position-absolute top-50 translate-middle-y ms-3 text-muted" style="pointer-events:none;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" value="<?= e($search) ?>" class="form-control ps-5 rounded-3" placeholder="Cari nama, email, no HP, alamat..." style="min-width: 280px;">
                </div>

                <select name="role" class="form-select rounded-3" style="width: auto; min-width: 150px;">
                    <option value="">Semua Role</option>
                    <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
                </select>

                <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
                <?php if ($search !== '' || $role !== ''): ?>
                    <a href="index.php?page=admin-users" class="btn btn-secondary rounded-3"><i class="bi bi-x-circle me-1"></i> Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">No HP</th>
                            <th class="py-3">Alamat</th>
                            <th class="py-3">Role</th>
                            <th class="py-3">Dibuat</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data && mysqli_num_rows($data) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                <tr>
                                    <td class="px-4 py-3 fw-semibold"><i class="bi bi-person-circle text-primary me-2"></i><?= e($row['nama']) ?></td>
                                    <td><?= e($row['email']) ?></td>
                                    <td><?= e($row['no_hp'] ?? '-') ?></td>
                                    <td class="text-muted" style="max-width: 240px;"><?= e($row['alamat'] ?? '-') ?></td>
                                    <td><span class="badge text-bg-light border"><?= e(ucfirst($row['role'])) ?></span></td>
                                    <td><?= !empty($row['created_at']) ? e(date('d M Y', strtotime($row['created_at']))) : '-' ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm rounded-3" data-bs-toggle="modal" data-bs-target="#editUser<?= e($row['id_user']) ?>">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <a href="process/delete_user.php?id=<?= e($row['id_user']) ?>" class="btn btn-danger btn-sm rounded-3" onclick="return confirm('Hapus user ini?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editUser<?= e($row['id_user']) ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form method="POST" action="process/update_user.php">
                                                <input type="hidden" name="id" value="<?= e($row['id_user']) ?>">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold">Edit User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body pt-2">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-muted">Nama</label>
                                                        <input type="text" name="nama" value="<?= e($row['nama']) ?>" class="form-control rounded-3" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-muted">Email</label>
                                                        <input type="email" name="email" value="<?= e($row['email']) ?>" class="form-control rounded-3" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-muted">No HP</label>
                                                        <input type="text" name="no_hp" value="<?= e($row['no_hp'] ?? '') ?>" class="form-control rounded-3" maxlength="15" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-muted">Alamat</label>
                                                        <textarea name="alamat" class="form-control rounded-3" rows="3" required><?= e($row['alamat'] ?? '') ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-muted">Password Baru <span class="fw-normal">(opsional)</span></label>
                                                        <input type="password" name="password" class="form-control rounded-3" placeholder="Kosongkan jika tidak diganti">
                                                    </div>
                                                    <div class="mb-1">
                                                        <label class="form-label fw-semibold small text-muted">Role</label>
                                                        <select name="role" class="form-select rounded-3" required>
                                                            <option value="admin" <?= $row['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                            <option value="user" <?= $row['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-check-lg me-1"></i> Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data user.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="POST" action="process/add_user.php">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Nama</label>
                        <input type="text" name="nama" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Email</label>
                        <input type="email" name="email" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">No HP</label>
                        <input type="text" name="no_hp" class="form-control rounded-3" maxlength="15" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Alamat</label>
                        <textarea name="alamat" class="form-control rounded-3" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Password</label>
                        <input type="password" name="password" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold small text-muted">Role</label>
                        <select name="role" class="form-select rounded-3" required>
                            <option value="admin">Admin</option>
                            <option value="user" selected>User</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-plus-lg me-1"></i> Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
