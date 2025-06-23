<?php
include "baglanti.php"; // Veritabanı bağlantısını ekle
session_start();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <link rel="stylesheet" href="css/index.css">
    <meta charset="UTF-8">
    <title>Stok Takip Sistemi</title>
</head>
<body>
    <h2>Hoşgeldiniz!</h2>
    
    <?php if (!isset($_SESSION['rol'])) { ?>
        <div class="merkez">
            <button onclick="window.location.href='login.php'">🔐 Giriş Yap</button>
            <button onclick="window.location.href='register.php'">📝 Kayıt Ol</button>
		
			
        </div>
    <?php } else { ?>
        <p>Merhaba, <?php echo $_SESSION['ad']; ?>!</p>
        <?php if ($_SESSION['rol'] == 'admin') { ?>
            <a href="admin/index.php">Admin Paneli</a>
        <?php } else { ?>
            <a href="user/index.php">Müşteri Paneli</a>
        <?php } ?>
        <a href="logout.php">Çıkış Yap</a>
    <?php } ?>

    <style>
    table {
    width: 80%;
    margin: auto;
    border-collapse: collapse;
    font-size: 18px; /* Yazılar büyütüldü */
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background: #007bff;
    color: white;
    font-size: 20px; /* Başlıklar daha büyük */
}

td {
    background: #f9f9f9;
}
</style>

<h2>Ürünler</h2>

<table>
    <tr>
        <th>Ürün</th>
        <th>Kategori</th>
        <th>Stok</th>
        <th>Fiyat</th>
    </tr>
    <?php
    $sql = "SELECT * FROM urunler";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['ad']}</td>
                <td>{$row['kategori']}</td>
                <td>{$row['stok_miktari']}</td>
                <td>{$row['fiyat']} TL</td>
              </tr>";
    }
    ?>
</table>
</body>
</html>