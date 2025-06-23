<?php
include "../baglanti.php"; // Veritabanı bağlantısını ekle
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'musteri') {
    header("Location: ../login.php");
    exit();
}

// Ürünleri çek
$sql = "SELECT * FROM urunler WHERE stok_miktari > 0";
$result = $conn->query($sql);

// Sipariş oluşturma işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $musteri_id = $_SESSION["id"];
    $urun_id = $_POST["urun_id"];
    $miktar = $_POST["miktar"];

    // Seçilen ürünün stok miktarını ve fiyatını al
    $stok_sorgu = $conn->prepare("SELECT stok_miktari, fiyat FROM urunler WHERE id = ?");
    $stok_sorgu->bind_param("i", $urun_id);
    $stok_sorgu->execute();
    $stok_sorgu->bind_result($stok_miktari, $fiyat);
    $stok_sorgu->fetch();
    $stok_sorgu->close();

    // Eğer sipariş miktarı stoktan fazla ise hata ver
    if ($miktar > $stok_miktari) {
        echo "Sipariş oluşturulamadı! Stokta sadece $stok_miktari adet mevcut.";
    } else {
        $toplam_fiyat = $fiyat * $miktar;

        // Siparişi veritabanına ekleme
        $siparis_sorgu = $conn->prepare("INSERT INTO siparisler (musteri_id, urun_id, miktar, toplam_fiyat) VALUES (?, ?, ?, ?)");
        $siparis_sorgu->bind_param("iiid", $musteri_id, $urun_id, $miktar, $toplam_fiyat);

        if ($siparis_sorgu->execute()) {
            // Stok miktarını güncelle
            $stok_guncelle = $conn->prepare("UPDATE urunler SET stok_miktari = stok_miktari - ? WHERE id = ?");
            $stok_guncelle->bind_param("ii", $miktar, $urun_id);
            $stok_guncelle->execute();
            $stok_guncelle->close();

            echo "Sipariş başarıyla oluşturuldu!";
            header("Location: siparislerim.php");
            exit();
        } else {
            echo "Sipariş oluşturulurken hata oluştu!";
        }
        $siparis_sorgu->close();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sipariş Oluştur</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Sipariş Ver</h2>
	<a href="index.php">↩ Kullanıcı Paneline Geri Dön</a>
    <form method="post">
        <label>Ürün Seç:</label>
        <select name="urun_id" required>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['ad'] . " - " . $row['fiyat'] . " TL - Stok: " . $row['stok_miktari']; ?>
                </option>
            <?php } ?>
        </select>

        <label>Miktar:</label>
        <input type="number" name="miktar" min="1" required>

        <input type="submit" value="Sipariş Ver">
    </form>
</body>
</html>