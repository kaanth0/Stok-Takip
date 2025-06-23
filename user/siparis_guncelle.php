<?php
include "../baglanti.php";
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'musteri') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $siparis_id = $_POST["siparis_id"];
    $yeni_miktar = $_POST["yeni_miktar"];

    // Sipariş durumunu kontrol et
    $sql = "SELECT urun_id, durum FROM siparisler WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $siparis_id);
    $stmt->execute();
    $stmt->bind_result($urun_id, $durum);
    $stmt->fetch();
    $stmt->close();

    if ($durum == "hazırlanıyor") {
        // Ürünün fiyatını al
        $urun_sorgu = $conn->prepare("SELECT fiyat FROM urunler WHERE id = ?");
        $urun_sorgu->bind_param("i", $urun_id);
        $urun_sorgu->execute();
        $urun_sorgu->bind_result($fiyat);
        $urun_sorgu->fetch();
        $urun_sorgu->close();

        $toplam_fiyat = $fiyat * $yeni_miktar;

        // Sipariş miktarını güncelle
        $sql = "UPDATE siparisler SET miktar = ?, toplam_fiyat = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idi", $yeni_miktar, $toplam_fiyat, $siparis_id);
        if ($stmt->execute()) {
            echo "Sipariş miktarı başarıyla güncellendi!";
        } else {
            echo "Sipariş güncellenemedi!";
        }
        $stmt->close();
    } else {
        echo "Sipariş kargoya verildi veya teslim edildi, güncellenemez!";
    }

    $conn->close();
    header("Location: siparislerim.php");
    exit();
}
?>