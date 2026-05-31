<?php
session_start();

$id = intval($_POST['id_product']);

if(!isset($_SESSION['cart'])){
  $_SESSION['cart'] = [];
}

$found = false;

foreach($_SESSION['cart'] as &$item){
  if($item['id'] == $id){
    $item['qty']++;
    $found = true;
    break;
  }
}

if(!$found){
  $_SESSION['cart'][] = [
    "id" => $id,
    "qty" => 1
  ];
}

header("Location: keranjang.php");
exit;