<?php
session_start();
if($_SESSION['role'] != 'user'){
    die("Bukan user!");
}
echo "HALAMAN USER - Welcome ".$_SESSION['email'];
?>