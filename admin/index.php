<?php
include "../baglanti.php"; // Veritabanı bağlantısını ekle
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Toplam kullanıcı sayısı
$sql = "SELECT COUNT(*) AS musteri_sayisi FROM musteriler WHERE rol='musteri'";
$musteri_sayisi = $conn->query($sql)->fetch_assoc()['musteri_sayisi'];

// Toplam ürün sayısı
$sql = "SELECT COUNT(*) AS urun_sayisi FROM urunler";
$urun_sayisi = $conn->query($sql)->fetch_assoc()['urun_sayisi'];

// Toplam sipariş sayısı
$sql = "SELECT COUNT(*) AS siparis_sayisi FROM siparisler";
$siparis_sayisi = $conn->query($sql)->fetch_assoc()['siparis_sayisi'];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h2>Admin Paneli</h2>
    
    <div class="dashboard">
        <div class="card">
            <h3>👥 Toplam Müşteri</h3>
            <p><?php echo $musteri_sayisi; ?> kişi</p>
        </div>
        <div class="card">
            <h3>📦 Toplam Ürün</h3>
            <p><?php echo $urun_sayisi; ?> ürün</p>
        </div>
        <div class="card">
            <h3>📜 Toplam Sipariş</h3>
            <p><?php echo $siparis_sayisi; ?> sipariş</p>
        </div>
    </div>

    <h3>İşlemler</h3>
    <div class="buttons">
        <button onclick="window.location.href='urunler.php'">🛒 Ürünleri Yönet</button>
        <button onclick="window.location.href='siparisler.php'">📜 Siparişleri Yönet</button>
        <button onclick="window.location.href='kullanicilar.php'">👥 Kullanıcıları Yönet</button>
    </div>

    <a href="../logout.php" class="logout-btn">🚪 Çıkış Yap</a>
</body>
</html>

 