<?php
include "../baglanti.php"; // Veritabanı bağlantısını ekle
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'musteri') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Müşteri Paneli</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Hoşgeldiniz, <?php echo $_SESSION['ad']; ?>!</h2>
        <div class="menu">
		<a href="gecmis_siparislerim.php">📜 Geçmiş Siparişlerim</a>
            <a href="siparislerim.php">📦 Siparişlerim</a> | 
            <a href="siparis_olustur.php">🛒 Yeni Sipariş Ver</a> | 
            <a href="../logout.php">🚪 Çıkış Yap</a>
        </div>

        <h3>Mevcut Ürünler</h3>
        <table border="1">
            <tr>
                <th>Ürün Adı</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Fiyat</th>
            </tr>
            <?php
            $sql = "SELECT * FROM urunler WHERE stok_miktari > 0";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['ad']; ?></td>
                <td><?php echo $row['kategori']; ?></td>
                <td><?php echo $row['stok_miktari']; ?></td>
                <td><?php echo $row['fiyat']; ?> TL</td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>