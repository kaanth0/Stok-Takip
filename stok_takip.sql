-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 23 Haz 2025, 20:41:47
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `stok_takip`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `musteriler`
--

CREATE TABLE `musteriler` (
  `id` int(11) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `sifre` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `musteriler`
--

INSERT INTO `musteriler` (`id`, `ad`, `email`, `telefon`, `kayit_tarihi`, `sifre`, `rol`) VALUES
(6, 'Kaan', 'kaanalmaci181@gmail.com', '+90 501 143 78 37', '2025-05-21 22:30:26', '$2y$10$QPxZSwrZ/iRG8bx6tCb.p.OV5pEjcDK5EQqSHuSgDMOD4FMFXfOPW', 'admin'),
(7, 'admin', 'admin@gmail.com', '+90 555 555 55 55', '2025-05-21 22:31:01', '$2y$10$wgkrjRC.gR/y3mLHVcu2XOY52GYTDA7xu6bMRyFxbSbtS2SvoI736', 'admin'),
(8, 'berke', 'berke@gmail.com', '+90 534 470 57 37', '2025-05-21 22:35:22', '$2y$10$RiWQeFGY6o/fZPuRbZUd3epPKOwAogQu/1ZF.VY9QxpyhGsHr3L3e', 'musteri'),
(11, 'Kuzey', 'kuzey@gmail.com', '+90 536 500 19 01', '2025-05-22 11:09:18', '$2y$10$vpN9tTn7R2Ya9Co1VLBR/enrnX.za8d..n5fQyPTg7qdctGRx/1si', 'musteri');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

CREATE TABLE `siparisler` (
  `id` int(11) NOT NULL,
  `musteri_id` int(11) NOT NULL,
  `urun_id` int(11) NOT NULL,
  `miktar` int(11) NOT NULL,
  `toplam_fiyat` decimal(10,2) NOT NULL,
  `siparis_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `durum` enum('hazırlanıyor','kargoya verildi','teslim edildi') DEFAULT 'hazırlanıyor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`id`, `musteri_id`, `urun_id`, `miktar`, `toplam_fiyat`, `siparis_tarihi`, `durum`) VALUES
(22, 11, 4, 10, 120000.00, '2025-05-22 11:23:52', 'hazırlanıyor');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis_log`
--

CREATE TABLE `siparis_log` (
  `id` int(11) NOT NULL,
  `musteri_id` int(11) DEFAULT NULL,
  `urun_adi` varchar(255) DEFAULT NULL,
  `miktar` int(11) DEFAULT NULL,
  `toplam_fiyat` decimal(10,2) DEFAULT NULL,
  `siparis_tarihi` datetime DEFAULT NULL,
  `teslim_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `siparis_log`
--

INSERT INTO `siparis_log` (`id`, `musteri_id`, `urun_adi`, `miktar`, `toplam_fiyat`, `siparis_tarihi`, `teslim_tarihi`) VALUES
(4, 8, 'Kulaklık', 25, 25000.00, '2025-05-22 02:25:16', '2025-05-22 02:25:50'),
(5, 11, 'Laptop', 5, 75000.00, '2025-05-22 14:09:46', '2025-05-22 14:10:59'),
(6, 11, 'Mouse', 10, 5000.00, '2025-05-22 14:10:11', '2025-05-22 14:11:03'),
(7, 11, 'Kulaklık', 5, 5000.00, '2025-05-22 14:10:21', '2025-05-22 14:11:10'),
(8, 11, 'Laptop', 10, 150000.00, '2025-05-22 14:23:42', '2025-05-22 14:28:44');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunler`
--

CREATE TABLE `urunler` (
  `id` int(11) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `stok_miktari` int(11) DEFAULT 0,
  `fiyat` decimal(10,2) NOT NULL,
  `eklenme_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `urunler`
--

INSERT INTO `urunler` (`id`, `ad`, `kategori`, `stok_miktari`, `fiyat`, `eklenme_tarihi`) VALUES
(3, 'Laptop', 'Elektronik', 40, 15000.00, '2025-05-21 20:45:27'),
(4, 'Telefon', 'Elektronik', 15, 12000.00, '2025-05-21 20:45:27'),
(5, 'Tablet', 'Elektronik', 15, 8000.00, '2025-05-21 20:45:27'),
(6, 'Kulaklık', 'Elektronik', 20, 1000.00, '2025-05-21 20:45:27'),
(7, 'Mouse', 'Elektronik', 20, 500.00, '2025-05-21 20:45:27'),
(8, 'Klavyet', 'Elektronik', 20, 750.00, '2025-05-21 20:45:27'),
(9, 'Monitör', 'Elektronik', 12, 6000.00, '2025-05-21 20:45:27'),
(10, 'Televizyon', 'Elektronik', 8, 20000.00, '2025-05-21 20:45:27'),
(11, 'Buzdolabı', 'Ev Aletleri', 5, 18000.00, '2025-05-21 20:45:27'),
(12, 'Çamaşır Makinesi', 'Ev Aletleri', 6, 14000.00, '2025-05-21 20:45:27'),
(13, 'Koltuk', 'Ev Mobilyası', 12, 9000.00, '2025-05-21 20:45:27'),
(14, 'Masa', 'Ev Mobilyası', 14, 4000.00, '2025-05-21 20:45:27'),
(15, 'Sandalyet', 'Ev Mobilyası', 25, 750.00, '2025-05-21 20:45:27'),
(16, 'Çalışma Masası', 'Ev Mobilyası', 10, 2500.00, '2025-05-21 20:45:27'),
(17, 'Dolap', 'Ev Mobilyası', 7, 7000.00, '2025-05-21 20:45:27'),
(18, 'Tişört', 'Moda', 30, 250.00, '2025-05-21 20:45:27'),
(19, 'Kot Pantolon', 'Moda', 20, 500.00, '2025-05-21 20:45:27'),
(20, 'Saat', 'Moda', 15, 1200.00, '2025-05-21 20:45:27'),
(21, 'Güneş Gözlüğü', 'Moda', 18, 800.00, '2025-05-21 20:45:27'),
(22, 'Çanta', 'Moda', 12, 1500.00, '2025-05-21 20:45:27'),
(23, 'Ayakkabı', 'Moda', 25, 2000.00, '2025-05-21 20:45:27'),
(24, 'Koşu Bandı', 'Spor', 5, 7000.00, '2025-05-21 20:45:27'),
(25, 'Dambıl Seti', 'Spor', 10, 2500.00, '2025-05-21 20:45:27'),
(26, 'Yoga Matı', 'Spor', 30, 500.00, '2025-05-21 20:45:27'),
(27, 'Kamp Çadırı', 'Spor', 15, 3000.00, '2025-05-21 20:45:27');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `musteriler`
--
ALTER TABLE `musteriler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `siparisler`
--
ALTER TABLE `siparisler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `musteri_id` (`musteri_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `siparis_log`
--
ALTER TABLE `siparis_log`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `urunler`
--
ALTER TABLE `urunler`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `musteriler`
--
ALTER TABLE `musteriler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `siparisler`
--
ALTER TABLE `siparisler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Tablo için AUTO_INCREMENT değeri `siparis_log`
--
ALTER TABLE `siparis_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `urunler`
--
ALTER TABLE `urunler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `siparisler`
--
ALTER TABLE `siparisler`
  ADD CONSTRAINT `siparisler_ibfk_1` FOREIGN KEY (`musteri_id`) REFERENCES `musteriler` (`id`),
  ADD CONSTRAINT `siparisler_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
