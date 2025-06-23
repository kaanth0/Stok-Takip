<?php
include "baglanti.php"; // Veritabanı bağlantısını dahil et
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Siparişleri çek
$sql = "SELECT s.id, m.ad AS musteri_adi, u.ad AS urun_adi, s.miktar, s.toplam_fiyat, s.durum, s.siparis_tarihi 
        FROM siparisler s 
        JOIN musteriler m ON s.musteri_id = m.id 
        JOIN urunler u ON s.urun_id = u.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<link rel="stylesheet" href="../css/style.css">
    <meta charset="UTF-8">
    <title>Sipariş Yönetimi</title>
</head>
<body>
    <h2>Siparişler</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Müşteri</th>
            <th>Ürün</th>
            <th>Miktar</th>
            <th>Toplam Fiyat</th>
            <th>Durum</th>
            <th>Tarih</th>
            <th>Güncelle</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['musteri_adi']; ?></td>
            <td><?php echo $row['urun_adi']; ?></td>
            <td><?php echo $row['miktar']; ?></td>
            <td><?php echo $row['toplam_fiyat']; ?> TL</td>
            <td><?php echo $row['durum']; ?></td>
            <td><?php echo $row['siparis_tarihi']; ?></td>
            <td>
                <form action="siparis_guncelle.php" method="post">
                    <input type="hidden" name="siparis_id" value="<?php echo $row['id']; ?>">
                    <select name="yeni_durum">
                        <option value="hazırlanıyor">Hazırlanıyor</option>
                        <option value="kargoya verildi">Kargoya Verildi</option>
                        <option value="teslim edildi">Teslim Edildi</option>
                    </select>
                    <input type="submit" value="Güncelle">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>