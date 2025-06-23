<?php
include "baglanti.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST["ad"];
    $email = $_POST["email"];
    $telefon = $_POST["telefon"];
    $sifre = password_hash($_POST["sifre"], PASSWORD_DEFAULT);
    $rol = "musteri"; 

    if (!preg_match('/^\d{10}$/', $telefon)) {
        echo "<script>alert('Telefon numarası hatalı! Lütfen sadece 10 hane girin.'); window.location.href='register.php';</script>";
        exit();
    }

    $telefon = "+90 " . substr($telefon, 0, 3) . " " . substr($telefon, 3, 3) . " " . substr($telefon, 6, 2) . " " . substr($telefon, 8, 2);

    $sql_kontrol = "SELECT * FROM musteriler WHERE email = ? OR telefon = ?";
    $stmt_kontrol = $conn->prepare($sql_kontrol);
    $stmt_kontrol->bind_param("ss", $email, $telefon);
    $stmt_kontrol->execute();
    $result_kontrol = $stmt_kontrol->get_result();

    if ($result_kontrol->num_rows > 0) {
        echo "<script>alert('Bu e-posta veya telefon zaten kayıtlı!'); window.location.href='register.php';</script>";
        exit();
    }

    $sql = "INSERT INTO musteriler (ad, email, telefon, sifre, rol) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $ad, $email, $telefon, $sifre, $rol);

    if ($stmt->execute()) {
        echo "<script>alert('Kayıt başarılı! Şimdi giriş yapabilirsiniz.'); window.location.href='login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Kayıt yapılırken hata oluştu!'); window.location.href='register.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Üye Kaydı</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function telefonFormatla(input) {
            input.value = input.value.replace(/\D/g, ''); 
            if (input.value.length > 10) {
                input.value = input.value.slice(0, 10); 
            }
        }
    </script>
</head>
<body>
    <h2>Yeni Üyelik</h2>
    <form method="post">
        <label>Ad:</label>
        <input type="text" name="ad" required>

        <label>E-posta:</label>
        <input type="email" name="email" required>
		
		<label>Şifre:</label>
        <input type="password" name="sifre" required>

        <label>Telefon Numarası:</label>
        <input type="text" name="telefon" maxlength="10" placeholder="5XXXXXXXXX" oninput="telefonFormatla(this)" required>

        <input type="submit" value="Kaydol">
		<a href="index.php">🔙 Geri Dön</a>
    </form>
</body>
</html>