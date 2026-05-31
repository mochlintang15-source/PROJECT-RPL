<?php
include 'koneksi.php';

if(!isset($_GET['id'])){
  die("Order tidak ditemukan!");
}

$id = (int)$_GET['id'];

// ambil data order
$data = mysqli_query($conn, "
SELECT o.*, p.metode_pembayaran
FROM orders o
JOIN payments p ON o.id_order = p.id_order
WHERE o.id_order = $id
");

$row = mysqli_fetch_assoc($data);

if(!$row){
  die("Data tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Verifikasi OTP</title>
  <style>
    body{
      font-family: Arial;
      background:#f5f5f5;
      display:flex;
      justify-content:center;
      align-items:center;
      height:100vh;
    }

    .otp-box{
      background:white;
      padding:30px;
      border-radius:10px;
      width:350px;
      text-align:center;
      box-shadow:0 5px 15px rgba(0,0,0,0.1);
    }

    h2{
      margin-bottom:10px;
    }

    p{
      color:gray;
      font-size:14px;
    }

    input{
      width:100%;
      padding:12px;
      margin-top:15px;
      font-size:18px;
      text-align:center;
      letter-spacing:5px;
      border:1px solid #ddd;
      border-radius:8px;
    }

    button{
      margin-top:20px;
      width:100%;
      padding:12px;
      background:orange;
      border:none;
      color:white;
      font-size:16px;
      border-radius:8px;
      cursor:pointer;
    }

    button:hover{
      background:#e69500;
    }

    .info{
      margin-top:15px;
      font-size:13px;
      color:#666;
    }
  </style>
</head>
<body>

<div class="otp-box">

  <h2>Verifikasi OTP</h2>

  <p>
    Masukkan kode OTP yang dikirim ke nomor Anda
  </p>

  <p><b>Order ID:</b> <?= $row['id_order'] ?></p>
  <p><b>Total:</b> Rp<?= number_format($row['total_harga']) ?></p>

  <form onsubmit="return cekOTP()">
    <input type="text" id="otp" placeholder="______" maxlength="6" required>
    <button type="submit">Verifikasi</button>
  </form>

  <div class="info">
    *Simulasi: gunakan kode <b>123456</b>
  </div>

</div>

<script>
function cekOTP(){
  let otp = document.getElementById("otp").value;

  if(otp.length != 6){
    alert("OTP harus 6 digit!");
    return false;
  }

  // simulasi OTP benar
  if(otp === "123456"){
    window.location.href = "success.php?id=<?= $row['id_order'] ?>";
  } else {
    alert("OTP salah!");
  }

  return false;
}
</script>

</body>
</html>