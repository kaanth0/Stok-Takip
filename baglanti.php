<?php
$servername = "localhost";
$username = "root"; // Sunucuna göre değiştirebilirsin
$password = ""; // Şifren varsa buraya ekle
$dbname = "stok_takip";

// Bağlantıyı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

?>