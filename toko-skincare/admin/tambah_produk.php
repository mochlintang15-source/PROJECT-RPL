<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../layout/sidebar.php';

$categories = mysqli_query($conn, 'SELECT id_kategori, nama_kategori FROM categories ORDER BY nama_kategori ASC');
$brands = mysqli_query($conn, 'SELECT id_brand, nama_brand FROM brands ORDER BY nama_brand ASC');
?>

<div class="admin-layout d-flex" style="min-height: 100vh;">
    <?php renderSidebar('produk'); ?>

    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-0">Tambah Produk</h3>
                <small class="text-muted">Isi data sesuai struktur tabel products</small>
            </div>
            <a href="index.php?page=admin-produk" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 760px;">
            <form method="POST" action="process/add_produk.php" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control rounded-3" placeholder="Contoh: Moisturizer Ceramide" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="id_kategori" class="form-select rounded-3">
                            <option value="">Tanpa kategori</option>
                            <?php if ($categories): ?>
                                <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?= e($category['id_kategori']) ?>"><?= e($category['nama_kategori']) ?></option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Brand</label>
                        <select name="id_brand" class="form-select rounded-3">
                            <option value="">Tanpa brand</option>
                            <?php if ($brands): ?>
                                <?php while ($brand = mysqli_fetch_assoc($brands)): ?>
                                    <option value="<?= e($brand['id_brand']) ?>"><?= e($brand['nama_brand']) ?></option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Harga</label>
                        <input type="number" name="harga" class="form-control rounded-3" min="0" step="0.01" placeholder="Contoh: 75000" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Stok</label>
                        <input type="number" name="stok" class="form-control rounded-3" min="0" placeholder="Contoh: 20" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="4" placeholder="Tulis deskripsi singkat produk..." required></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Gambar Produk</label>
                        <input type="file" name="gambar" class="form-control rounded-3" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <small class="text-muted">Kolom gambar pada SQL wajib terisi. Jika tidak upload, sistem menyimpan tanda "-".</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-save me-1"></i> Simpan</button>
                    <a href="index.php?page=admin-produk" class="btn btn-secondary rounded-3">Batal</a>
                </div>
            </form>
        </div>
    </main>
</div>
