<?php
include "baglanti.php"; // Veritabanı bağlantısını dahil et
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Formdan gelen sipariş ID ve yeni durum bilgileri
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $siparis_id = $_POST['siparis_id'];
    $yeni_durum = $_POST['yeni_durum'];

    // Güncelleme sorgusu
    $sql = "UPDATE siparisler SET durum = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $yeni_durum, $siparis_id);

    if ($stmt->execute()) {
        echo "Sipariş durumu güncellendi!";
    } else {
        echo "Güncelleme başarısız!";
    }
    $stmt->close();
    $conn->close();
    header("Location: siparisler.php");
    exit();
}
?>