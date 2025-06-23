<?php
include "../baglanti.php";
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Kullanıcı arama işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["arama"])) {
    $arama = "%" . $_POST["arama"] . "%";
    $sql = "SELECT * FROM musteriler WHERE ad LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $arama, $arama);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
    exit();
}

// Kullanıcı güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kullanici_id"])) {
    $kullanici_id = (int)$_POST["kullanici_id"];
    $yeni_rol = $_POST["yeni_rol"];

    $sql = "UPDATE musteriler SET rol = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $yeni_rol, $kullanici_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Rol güncellendi!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Güncelleme hatası!"]);
    }
    exit();
}

// Kullanıcı silme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sil_id"])) {
    $kullanici_id = (int)$_POST["sil_id"];

    $sql = "DELETE FROM musteriler WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kullanici_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Kullanıcı başarıyla silindi!", "user_id" => $kullanici_id]);
    } else {
        echo json_encode(["status" => "error", "message" => "Silme işlemi hatalı!"]);
    }
    exit();
}

// Kullanıcıları çek
$sql = "SELECT * FROM musteriler ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Yönetimi</title>
    <link rel="stylesheet" href="../css/admin.css">
	<link rel="stylesheet" href="../css/style.css">
    <script>
        function aramaYap() {
            let aramaMetni = document.getElementById("arama").value;
            fetch("kullanicilar.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `arama=${aramaMetni}`
            }).then(response => response.json()).then(data => {
                let tableBody = document.getElementById("kullanici_listesi");
                tableBody.innerHTML = "";
                if (data.length > 0) {
                    data.forEach(user => {
                        tableBody.innerHTML += `<tr id="kullanici_${user.id}">
                            <td>${user.id}</td>
                            <td>${user.ad}</td>
                            <td>${user.email}</td>
                            <td>${user.telefon}</td>
                            <td>
                                <select data-field="rol">
                                    <option value="musteri" ${user.rol === "musteri" ? "selected" : ""}>Müşteri</option>
                                    <option value="admin" ${user.rol === "admin" ? "selected" : ""}>Admin</option>
                                </select>
                            </td>
                            <td>
                                <button onclick="rolGuncelle(${user.id})">💾 Güncelle</button>
                            </td>
                            <td>
                                <button onclick="sil(${user.id})">🗑️ Sil</button>
                            </td>
                        </tr>`;
                    });
                } else {
                    tableBody.innerHTML = "<tr><td colspan='7' style='text-align:center;'>Sonuç bulunamadı</td></tr>";
                }
            });
        }

        function rolGuncelle(id) {
            let row = document.getElementById("kullanici_" + id);
            let yeniRol = row.querySelector("[data-field='rol']").value;

            fetch("kullanicilar.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `kullanici_id=${id}&yeni_rol=${yeniRol}`
            }).then(response => response.json()).then(data => {
                alert(data.message);
            });
        }

        function sil(id) {
    if (confirm("Bu kullanıcıyı silmek istediğinizden emin misiniz?")) {
        fetch("kullanicilar.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `sil_id=${id}`
        }).then(response => response.json()).then(data => {
            console.log("Silme işlemi sonucu:", data); // Hata ayıklamak için
            alert(data.message);
            if (data.status === "success") {
                document.getElementById("kullanici_" + id).remove();
            }
        }).catch(error => {
            console.error("Silme hatası:", error);
            alert("Silme işlemi başarısız oldu!");
        });
    }
}
    </script>
</head>
<body>
    <h2>Kullanıcı Yönetimi</h2>
    <a href="index.php">🔙 Admin Paneline Geri Dön</a>

    <input type="text" id="arama" oninput="aramaYap()" placeholder="İsim veya e-posta ile ara..." style="width: 300px; padding: 8px;">

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Ad</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>Rol</th>
            <th>Güncelle</th>
            <th>Sil</th>
        </tr>
        <tbody id="kullanici_listesi">
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr id="kullanici_<?php echo $row['id']; ?>">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['ad']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['telefon']; ?></td>
                <td>
                    <select data-field="rol">
                        <option value="musteri" <?php if ($row['rol'] == "musteri") echo "selected"; ?>>Müşteri</option>
                        <option value="admin" <?php if ($row['rol'] == "admin") echo "selected"; ?>>Admin</option>
                    </select>
                </td>
                <td>
                    <button onclick="rolGuncelle(<?php echo $row['id']; ?>)">💾 Güncelle</button>
                </td>
                <td>
                    <button onclick="sil(<?php echo $row['id']; ?>)">🗑️ Sil</button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>