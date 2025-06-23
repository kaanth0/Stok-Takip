<?php
include "../baglanti.php";
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ürünleri çek
$sql = "SELECT * FROM urunler ORDER BY id DESC";
$result = $conn->query($sql);

// Ürün Silme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sil_id"])) {
    $urun_id = (int)$_POST["sil_id"];
    $sql = "DELETE FROM urunler WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $urun_id);

    if ($stmt->execute()) {
        echo "Ürün başarıyla silindi!";
    } else {
        echo "Ürün silinirken hata oluştu!";
    }
    exit();
}

// Ürün Güncelleme 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["urun_id"])) {
    $urun_id = (int)$_POST["urun_id"];
    $stok = (int)$_POST["stok"];
    $fiyat = (float)$_POST["fiyat"];

    $sql = "UPDATE urunler SET stok_miktari = ?, fiyat = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idi", $stok, $fiyat, $urun_id);

    if ($stmt->execute()) {
        echo "Güncelleme başarılı!";
    } else {
        echo "Hata oluştu!";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürünler Listesi</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function duzenle(id) {
            let row = document.getElementById("urun_" + id);
            row.querySelectorAll("td[data-field='stok'], td[data-field='fiyat']").forEach(td => td.setAttribute("contenteditable", "true"));
            row.querySelector(".kaydet").style.display = "inline";
        }

        function kaydet(id) {
            let row = document.getElementById("urun_" + id);
            let stok = row.querySelector("[data-field='stok']").innerText;
            let fiyat = row.querySelector("[data-field='fiyat']").innerText;

            fetch("urunler.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `urun_id=${id}&stok=${stok}&fiyat=${fiyat}`
            }).then(response => response.text()).then(data => {
                alert(data);
                row.querySelectorAll("td[contenteditable]").forEach(td => td.setAttribute("contenteditable", "false"));
                row.querySelector(".kaydet").style.display = "none";
            });
        }

        function sil(id) {
            if (confirm("Bu ürünü silmek istediğinizden emin misiniz?")) {
                fetch("urunler.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `sil_id=${id}`
                }).then(response => response.text()).then(data => {
                    alert(data);
                    document.getElementById("urun_" + id).remove();
                });
            }
        }
    </script>
</head>
<body>
    <h2>Ürün Listesi</h2>
    <a href="urun_ekle.php">🔙 Ürün Ekle</a>
	<a href="csv_indir.php" download>
    📂 Stok CSV Olarak İndir
</a>
	<a href="index.php">🔙 Admin Paneline Geri Dön</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Ürün Adı</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Fiyat</th>
            <th>Düzenle</th>
            <th>Sil</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr id="urun_<?php echo $row['id']; ?>">
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['ad']; ?></td>
            <td><?php echo $row['kategori']; ?></td>
            <td data-field="stok" contenteditable="false"><?php echo $row['stok_miktari']; ?></td>
            <td data-field="fiyat" contenteditable="false"><?php echo $row['fiyat']; ?> TL</td>
            <td>
                <button onclick="duzenle(<?php echo $row['id']; ?>)">✏️ Düzenle</button>
                <button class="kaydet" onclick="kaydet(<?php echo $row['id']; ?>)" style="display:none;">💾 Kaydet</button>
            </td>
            <td>
                <button onclick="sil(<?php echo $row['id']; ?>)">🗑️ Sil</button>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>