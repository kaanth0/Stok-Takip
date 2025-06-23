<?php
include "baglanti.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $sifre = $_POST["sifre"];

    // Kullanıcıyı bul
    $sql = "SELECT id, ad, sifre, rol FROM musteriler WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $ad, $hashed_sifre, $rol);
        $stmt->fetch();
        
        if (password_verify($sifre, $hashed_sifre)) {
            $_SESSION["id"] = $id;
            $_SESSION["ad"] = $ad;
            $_SESSION["rol"] = $rol;

            if ($rol == "admin") {
                header("Location: admin/index.php");
            } else {
                header("Location: user/index.php");
            }
            exit();
        } else {
            echo "Şifre hatalı!";
        }
    } else {
        echo "Kullanıcı bulunamadı!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
</head>
<body>
    <h2>Giriş Yap</h2>
    <form method="post">
        <input type="email" name="email" placeholder="E-posta" required>
        <input type="password" name="sifre" placeholder="Şifre" required>
        <input type="submit" value="Giriş Yap">
		<a href="index.php">🔙 Geri Dön</a>
    </form>
</body>
</html>