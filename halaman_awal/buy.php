<?php
session_start();

/* CEK LOGIN */
if(isset($_SESSION['email'])){

    // AMBIL ID PRODUK
    $id = $_GET['id'];

    // BUAT SESSION CART
    $_SESSION['cart'][] = $id;

    // MASUK KE KERANJANG
    header("Location: cart.php");

}else{

    // JIKA BELUM LOGIN
    header("Location: beauty-always1.php");

}
?>