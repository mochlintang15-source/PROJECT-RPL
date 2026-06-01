<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <h1 class="title">
        Keranjang Belanja
    </h1>

    <?php

    if(isset($_SESSION['cart'])){

        echo "<ul>";

        foreach($_SESSION['cart'] as $item){

            echo "<li>Produk ID : ".$item."</li>";

        }

        echo "</ul>";

    }else{

        echo "Keranjang kosong";

    }

    ?>

</div>

<?php include 'footer.php'; ?>

</body>
</html>