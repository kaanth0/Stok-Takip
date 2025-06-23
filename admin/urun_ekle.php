<?php
include "../baglanti.php";
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Manuel Ürün Ekleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["urun_adi"])) {
    $urun_adi = $_POST["urun_adi"];
    $kategori = $_POST["kategori"];
    $stok = (int)$_POST["stok"];
    $fiyat = (float)$_POST["fiyat"];

    $sql = "INSERT INTO urunler (ad, kategori, stok_miktari, fiyat) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $urun_adi, $kategori, $stok, $fiyat);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Ürün başarıyla eklendi!</p>";
    } else {
        echo "<p style='color:red;'>Ürün eklenirken hata oluştu!</p>";
    }
}

// CSV Dosyasından Ürün Yükleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["csv_file"])) {
    $file = $_FILES["csv_file"]["tmp_name"];
    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $urun_adi = $data[0];
            $kategori = $data[1];
            $stok = (int)$data[2];
            $fiyat = (float)$data[3];

            $sql = "INSERT INTO urunler (ad, kategori, stok_miktari, fiyat) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $urun_adi, $kategori, $stok, $fiyat);
            $stmt->execute();
        }
        fclose($handle);
        echo "<p style='color:green;'>CSV dosyası başarıyla yüklendi!</p>";
    } else {
        echo "<p style='color:red;'>CSV dosyası okunamadı!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Ekle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Yeni Ürün Ekle</h2>

    <h2>Manuel Ürün Ekleme</h2>
    <form method="post">
        <label>Ürün Adı:</label>
        <input type="text" name="urun_adi" required>
        
        <label>Kategori:</label>
        <input type="text" name="kategori" required>
        
        <label>Stok Miktarı:</label>
        <input type="number" name="stok" min="1" required>
        
        <label>Fiyat:</label>
        <input type="number" name="fiyat" step="0.01" required>
        
        <input type="submit" value="Ekle">
    </form>

    <h2>CSV Dosyası ile Toplu Ürün Ekleme</h2>
    <form method="post" enctype="multipart/form-data">
        <label>CSV Dosyası Yükle:</label>
        <input type="file" name="csv_file" accept=".csv" required>
        <input type="submit" value="Yükle">
    </form>

    <a href="urunler.php">↩ Ürün Listesine Geri Dön</a>
</body>
</html>