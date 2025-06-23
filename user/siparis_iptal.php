<?php
include "../baglanti.php"; // Veritabanı bağlantısını ekle
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'musteri') {
    header("Location: ../login.php");
    exit();
}

// Sipariş ID'yi al
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $siparis_id = $_POST["siparis_id"];
    $musteri_id = $_SESSION["id"];

    // Siparişin durumunu kontrol et
    $sql = "SELECT durum FROM siparisler WHERE id = ? AND musteri_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $siparis_id, $musteri_id);
    $stmt->execute();
    $stmt->bind_result($durum);
    $stmt->fetch();
    $stmt->close();

    // Sipariş henüz teslim edilmediyse iptal et
    if ($durum == "hazırlanıyor") {
        $sql = "DELETE FROM siparisler WHERE id = ? AND musteri_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $siparis_id, $musteri_id);
        if ($stmt->execute()) {
            echo "Sipariş başarıyla iptal edildi!";
        } else {
            echo "Sipariş iptal edilemedi!";
        }
        $stmt->close();
    } else {
        echo "Sipariş kargoya verildi veya teslim edildi, iptal edilemez!";
    }

    $conn->close();
    header("Location: siparislerim.php");
    exit();
}
?>