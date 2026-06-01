<?php
include 'koneksi.php';

$id_order = $_POST['id_order'];

$namaFile = time().'_'.$_FILES['bukti']['name'];

move_uploaded_file(
    $_FILES['bukti']['tmp_name'],
    'uploads/'.$namaFile
);

mysqli_query($conn,"
UPDATE payments
SET bukti_transfer='$namaFile'
WHERE id_order='$id_order'
");

header("Location: status_pesanan.php?id=".$id_order);
exit;
?>