<?php
include "../baglanti.php"; // Veritabanı bağlantısını ekle
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'musteri') {
    header("Location: ../login.php");
    exit();
}

$musteri_id = $_SESSION["id"];

// Kullanıcının teslim edilen siparişlerini çek
$sql = "SELECT * FROM siparis_log WHERE musteri_id = ? ORDER BY teslim_tarihi DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $musteri_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Geçmiş Siparişlerim</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Geçmiş Siparişlerim</h2>
    <a href="index.php">↩ Kullanıcı Paneline Geri Dön</a>
    <table border="1">
        <tr>
            <th>Ürün</th>
            <th>Miktar</th>
            <th>Toplam Fiyat</th>
            <th>Sipariş Tarihi</th>
            <th>Teslim Tarihi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
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