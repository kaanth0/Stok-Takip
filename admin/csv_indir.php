<?php
include "../baglanti.php";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=stok_durumu.csv');

$output = fopen('php://output', 'w');

// CSV başlıklarını ekleyelim
fputcsv($output, ['ID', 'Ürün Adı', 'Kategori', 'Stok', 'Fiyat']);

// Verileri çekip CSV olarak ekleyelim
$sql = "SELECT * FROM urunler ORDER BY id DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['id'], $row['ad'], $row['kategori'], $row['stok_miktari'], $row['fiyat']]);
}

fclose($output);
exit();
?>