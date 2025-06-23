<?php
include "../baglanti.php";
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'musteri') {
    header("Location: ../login.php");
    exit();
}

$musteri_id = $_SESSION['id'];

// Siparişleri ve ürün bilgilerini çek
$sql = "SELECT s.id, u.ad AS urun_adi, s.miktar, u.fiyat, s.durum, s.urun_id 
        FROM siparisler s
        JOIN urunler u ON s.urun_id = u.id
        WHERE s.musteri_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $musteri_id);
$stmt->execute();
$result = $stmt->get_result();

// Sipariş silme isteği
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sil_id"])) {
    $siparis_id = (int)$_POST["sil_id"];

    $sql = "SELECT urun_id, miktar, durum FROM siparisler WHERE id = ? AND musteri_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $siparis_id, $musteri_id);
    $stmt->execute();
    $siparis = $stmt->get_result()->fetch_assoc();

    if ($siparis) {
        $durum = mb_strtolower(trim($siparis['durum']), 'UTF-8');
        if ($durum !== 'hazırlanıyor') {
            echo "<script>alert('Bu sipariş hazırlanıyor aşamasında değil, iptal edilemez!'); window.location.href = window.location.pathname;</script>";
            exit();
        }

        $urun_id = $siparis['urun_id'];
        $miktar = (int)$siparis['miktar'];

        // Stok iadesi
        $sql = "UPDATE urunler SET stok_miktari = stok_miktari + ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $miktar, $urun_id);
        $stmt->execute();

        // Siparişi sil
        $sql = "DELETE FROM siparisler WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $siparis_id);
        if ($stmt->execute()) {
            header("Location: ".$_SERVER['REQUEST_URI']); // Yeniden yönlendir
            exit();
        } else {
            echo "<script>alert('Sipariş silme hatası!'); window.location.href = window.location.pathname;</script>";
            exit();
        }
    } else {
        echo "<script>alert('Sipariş bulunamadı veya size ait değil!'); window.location.href = window.location.pathname;</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Siparişlerim</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Siparişlerim</h2>
    <a href="index.php" style="display: block; margin-top: 20px; font-size: 18px;">🔙 Kullanıcı Paneline Geri Dön</a>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Ürün Adı</th>
            <th>Miktar</th>
            <th>Fiyat</th>
            <th>Durum</th>
            <th>İptal Et</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['urun_adi']; ?></td>
            <td><?php echo $row['miktar']; ?></td>
            <td><?php echo $row['fiyat']; ?> TL</td>
            <td><?php echo $row['durum']; ?></td>
            <td>
                <?php if (mb_strtolower(trim($row['durum']), 'UTF-8') === "hazırlanıyor") { ?>
                    <form method="post">
                        <input type="hidden" name="sil_id" value="<?php echo $row['id']; ?>">
                        <button type="submit">❌ İptal Et</button>
                    </form>
                <?php } else { ?>
                    <button disabled style="background: #ccc; cursor: not-allowed;">🚫 İptal Edilemez</button>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
