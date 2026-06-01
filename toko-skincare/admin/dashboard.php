<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../layout/sidebar.php';

function fetchSingleValue(mysqli $conn, string $query, string $key = 'total')
{
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return 0;
    }
    $row = mysqli_fetch_assoc($result);
    return $row[$key] ?? 0;
}

$totalUser   = fetchSingleValue($conn, 'SELECT COUNT(*) AS total FROM users');
$totalProduk = fetchSingleValue($conn, 'SELECT COUNT(*) AS total FROM products');
$totalOrder  = fetchSingleValue($conn, 'SELECT COUNT(*) AS total FROM orders');
$totalPendapatan = fetchSingleValue($conn, "SELECT COALESCE(SUM(total_harga), 0) AS total FROM orders WHERE status_order = 'selesai'");

$userStats = mysqli_query($conn, 'SELECT role, COUNT(*) AS total FROM users GROUP BY role ORDER BY role');
$labels = [];
$dataUser = [];
while ($userStats && $row = mysqli_fetch_assoc($userStats)) {
    $labels[]   = ucfirst($row['role']);
    $dataUser[] = (int) $row['total'];
}

$namaBulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$orderStats = mysqli_query($conn, "
    SELECT MONTH(tanggal_order) AS bulan, COUNT(*) AS total
    FROM orders
    GROUP BY MONTH(tanggal_order)
    ORDER BY MONTH(tanggal_order)
");
$bulan = [];
$dataOrder = [];
while ($orderStats && $row = mysqli_fetch_assoc($orderStats)) {
    $bulan[]     = $namaBulan[(int) $row['bulan']] ?? $row['bulan'];
    $dataOrder[] = (int) $row['total'];
}

$pendapatanStats = mysqli_query($conn, "
    SELECT MONTH(tanggal_order) AS bulan, COALESCE(SUM(total_harga), 0) AS pendapatan
    FROM orders
    WHERE status_order = 'selesai'
    GROUP BY MONTH(tanggal_order)
    ORDER BY MONTH(tanggal_order)
");
$bulanPendapatan = [];
$dataPendapatan = [];
while ($pendapatanStats && $row = mysqli_fetch_assoc($pendapatanStats)) {
    $bulanPendapatan[] = $namaBulan[(int) $row['bulan']] ?? $row['bulan'];
    $dataPendapatan[]  = (float) $row['pendapatan'];
}
?>

<div class="admin-layout d-flex" style="min-height: 100vh;">
    <?php renderSidebar('dashboard'); ?>

    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-0">Dashboard Admin</h3>
                <small class="text-muted">Ringkasan data toko skincare</small>
            </div>
            <span class="text-secondary fw-medium">Admin 👤</span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 card-hover h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px; height:52px; background:#ede9fe; font-size:1.4rem;">👥</div>
                        <div>
                            <div class="text-uppercase text-muted fw-semibold small">Users</div>
                            <div class="fw-bold fs-2 lh-1"><?= e($totalUser) ?></div>
                            <div class="text-muted small">total pengguna</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 card-hover h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px; height:52px; background:#dbeafe; font-size:1.4rem;">📦</div>
                        <div>
                            <div class="text-uppercase text-muted fw-semibold small">Produk</div>
                            <div class="fw-bold fs-2 lh-1"><?= e($totalProduk) ?></div>
                            <div class="text-muted small">total produk</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 card-hover h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px; height:52px; background:#d1fae5; font-size:1.4rem;">🛒</div>
                        <div>
                            <div class="text-uppercase text-muted fw-semibold small">Order</div>
                            <div class="fw-bold fs-2 lh-1"><?= e($totalOrder) ?></div>
                            <div class="text-muted small">total transaksi</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 card-hover h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px; height:52px; background:#fef9c3; font-size:1.4rem;">💰</div>
                        <div>
                            <div class="text-uppercase text-muted fw-semibold small">Pendapatan</div>
                            <div class="fw-bold fs-5 lh-1"><?= rupiah($totalPendapatan) ?></div>
                            <div class="text-muted small">transaksi selesai</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-7 d-flex flex-column gap-3">
                <div class="card border-0 shadow-sm p-4">
                    <h6 class="fw-bold mb-3">Order per Bulan</h6>
                    <canvas id="barChart" height="130"></canvas>
                </div>

                <div class="card border-0 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-muted small fw-semibold">Total Pendapatan</div>
                            <div class="fw-bold fs-4"><?= rupiah($totalPendapatan) ?></div>
                        </div>
                        <span class="badge border text-dark fw-medium px-2 py-2 rounded-3" style="background:#f9fafb;">Tahun Ini</span>
                    </div>
                    <canvas id="pendapatanChart" height="130"></canvas>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h6 class="fw-bold mb-3">User Role</h6>
                    <div class="d-flex flex-column align-items-center justify-content-center gap-3 h-100">
                        <canvas id="donutChart" style="max-width:240px; max-height:240px;"></canvas>
                        <div class="d-flex flex-column gap-2 align-items-start">
                            <?php $donutColors = ['#6c63ff', '#67e8f9', '#3b82f6', '#22c55e']; ?>
                            <?php foreach ($labels as $i => $label): ?>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle d-inline-block" style="width:14px; height:14px; background:<?= e($donutColors[$i % count($donutColors)]) ?>;"></span>
                                    <span class="fw-medium"><?= e($label) ?></span>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($labels)): ?>
                                <span class="text-muted">Belum ada data user.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const orderLabels = <?= json_encode($bulan) ?>;
    const orderData = <?= json_encode($dataOrder) ?>;
    const incomeLabels = <?= json_encode($bulanPendapatan) ?>;
    const incomeData = <?= json_encode($dataPendapatan) ?>;
    const roleLabels = <?= json_encode($labels) ?>;
    const roleData = <?= json_encode($dataUser) ?>;

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: orderLabels,
            datasets: [{
                label: 'Order',
                data: orderData,
                backgroundColor: '#3b82f6',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('pendapatanChart'), {
        type: 'bar',
        data: {
            labels: incomeLabels,
            datasets: [{
                label: 'Pendapatan',
                data: incomeData,
                backgroundColor: '#1a2e22',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: ctx => 'Pendapatan: Rp ' + Number(ctx.raw).toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: val => 'Rp ' + Number(val).toLocaleString('id-ID') }
                },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: roleLabels,
            datasets: [{
                data: roleData,
                backgroundColor: ['#6c63ff', '#67e8f9', '#3b82f6', '#22c55e'],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            cutout: '62%',
            plugins: { legend: { display: false } }
        }
    });
</script>
