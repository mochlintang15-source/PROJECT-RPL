<?php
session_start();
if($_SESSION['role'] != 'admin'){
    die("Bukan admin!");
}
echo "HALAMAN ADMIN - Welcome ".$_SESSION['email'];
?>