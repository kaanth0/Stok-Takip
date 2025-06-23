<?php
include "../baglanti.php";
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Geçmiş siparişleri çek
$sql = "SELECT * FROM siparis_log ORDER BY teslim_tarihi DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sipariş Logları</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Geçmiş Siparişler</h2>
    <a href="siparisler.php">↩ Sipariş Yönetimine Geri Dön</a>
    <table border="1">
        <tr>
            <th>Müşteri</th>
            <th>Ürün</th>
            <th>Miktar</th>
            <th>Toplam Fiyat</th>
            <th>Sipariş Tarihi</th>
            <th>Teslim Tarihi</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['musteri_id']; ?></td>
            <td><?php echo $row['urun_adi']; ?></td>
            <td><?php echo $row['miktar']; ?></td>
            <td><?php echo $row['toplam_fiyat']; ?> TL</td>
            <td><?php echo $row['siparis_tarihi']; ?></td>
            <td><?php echo $row['teslim_tarihi']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>