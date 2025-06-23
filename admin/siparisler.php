<?php
include "../baglanti.php"; 
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$mesaj = ""; // Güncelleme mesajı için değişken

// Siparişleri çek (Hazırlanıyor ve Kargoya Verildi olanları)
$sql = "SELECT s.id, m.ad AS musteri_adi, u.ad AS urun_adi, s.miktar, s.toplam_fiyat, s.durum, s.siparis_tarihi 
        FROM siparisler s 
        JOIN musteriler m ON s.musteri_id = m.id 
        JOIN urunler u ON s.urun_id = u.id 
        WHERE s.durum IN ('hazırlanıyor', 'kargoya verildi')";
$result = $conn->query($sql);

// Sipariş güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $siparis_id = $_POST["siparis_id"];
    $yeni_durum = $_POST["yeni_durum"];

    if ($yeni_durum == "teslim edildi") {
        // Siparişi log tablosuna ekle
        $log_sorgu = $conn->prepare("INSERT INTO siparis_log (musteri_id, urun_adi, miktar, toplam_fiyat, siparis_tarihi)
                                     SELECT musteri_id, (SELECT ad FROM urunler WHERE id = urun_id), miktar, toplam_fiyat, siparis_tarihi 
                                     FROM siparisler WHERE id = ?");
        $log_sorgu->bind_param("i", $siparis_id);
        $log_sorgu->execute();
        $log_sorgu->close();

        // Siparişi siparişler tablosundan sil
        $delete_sorgu = $conn->prepare("DELETE FROM siparisler WHERE id = ?");
        $delete_sorgu->bind_param("i", $siparis_id);
        $delete_sorgu->execute();
        $delete_sorgu->close();

        $mesaj = "<p style='color:green;'>Sipariş teslim edildi ve loga kaydedildi!</p>";
    } else {
        // Normal sipariş durumu güncellemesi
        $update_sorgu = $conn->prepare("UPDATE siparisler SET durum = ? WHERE id = ?");
        $update_sorgu->bind_param("si", $yeni_durum, $siparis_id);
        $update_sorgu->execute();
        $update_sorgu->close();

        $mesaj = "<p style='color:green;'>Sipariş durumu güncellendi!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Sipariş Yönetimi</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Tüm Siparişler</h2>
    <a href="index.php">🔙 Geri Dön</a>
    <a href="siparis_log.php">📜 Sipariş Loglarını Görüntüle</a>

    <!-- Güncelleme mesajı -->
    <?php echo $mesaj; ?>

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
            <th>İptal</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['musteri_adi']; ?></td>
            <td><?php echo $row['urun_adi']; ?></td>
            <td><?php echo $row['miktar']; ?></td>
            <td><?php echo $row['toplam_fiyat']; ?> TL</td>
            <td><?php echo $row['durum']; ?></td>
            <td><?php echo $row['siparis_tarihi']; ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="siparis_id" value="<?php echo $row['id']; ?>">
                    <select name="yeni_durum">
                        <option value="hazırlanıyor" <?php if ($row['durum'] == "hazırlanıyor") echo "selected"; ?>>Hazırlanıyor</option>
                        <option value="kargoya verildi" <?php if ($row['durum'] == "kargoya verildi") echo "selected"; ?>>Kargoya Verildi</option>
                        <option value="teslim edildi">Teslim Edildi</option>
                    </select>
                    <input type="submit" value="Güncelle">
                </form>
            </td>
            <td>
                <form action="siparis_iptal.php" method="post">
                    <input type="hidden" name="siparis_id" value="<?php echo $row['id']; ?>">
                    <input type="submit" value="İptal Et">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>