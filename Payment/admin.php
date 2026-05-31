<?php
include 'koneksi.php';

$data = mysqli_query($conn, "
SELECT o.id_order, o.nama, o.total_harga, p.status_pembayaran
FROM orders o
JOIN payments p ON o.id_order = p.id_order
ORDER BY o.id_order DESC
");
?>

<h2>Dashboard Admin</h2>

<table border="1" cellpadding="10">
<tr>
  <th>ID</th>
  <th>Nama</th>
  <th>Total</th>
  <th>Status</th>
  <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)): ?>

<tr>
  <td><?= $row['id_order'] ?></td>
  <td><?= $row['nama'] ?></td>
  <td>Rp<?= number_format($row['total_harga']) ?></td>
  <td><?= $row['status_pembayaran'] ?></td>
  <td>
    <?php if($row['status_pembayaran'] == 'pending'): ?>
      <a href="validasi.php?id=<?= $row['id_order'] ?>">
        <button>Validasi</button>
      </a>
    <?php else: ?>
      ✔
    <?php endif; ?>
  </td>
</tr>

<?php endwhile; ?>
</table>