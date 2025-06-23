# 📦 Stok Takip ve Sipariş Yönetim Sistemi

Bu proje, PHP ve MySQL kullanılarak geliştirilmiş kapsamlı bir stok takip ve sipariş yönetim sistemidir. Admin paneli ve müşteri paneli olmak üzere iki farklı kullanıcı rolü içerir.

## 🚀 Özellikler

### 👨‍💼 Admin Paneli

- 🧾 Ürünleri listeleme
- ➕ Manuel ürün ekleme
- 📥 CSV dosyası ile toplu ürün ekleme
- 👤 Kullanıcıları listeleme, bilgilerini düzenleme veya silme
- 🔁 Kullanıcı rolünü admin olarak değiştirme
- 📦 Siparişleri listeleme ve durum güncelleme
- 🕑 Geçmiş siparişlerin loglarını görüntüleme

### 🧍‍♂️ Müşteri Paneli

- 📋 Ürünleri görüntüleme
- 🛒 Sipariş oluşturma
- ⏳ Sipariş durumu sorgulama

## 🔐 Güvenlik

- Kullanıcı şifreleri **şifrelenmiş (hashlenmiş)** şekilde veritabanında saklanır.
- Yetkisiz işlemler oturum kontrolü ile engellenir.

## 🛠️ Kullanılan Teknolojiler

- **PHP** – Sunucu taraflı işlemler
- **MySQL** – Veritabanı yönetimi
- **XAMPP / Apache** – Yerel geliştirme ortamı
- **HTML/CSS** – Arayüz tasarımı

## 🧪 Kurulum

1. XAMPP'i başlat (Apache ve MySQL).
2. Bu projeyi `C:\xampp\htdocs\Stok_Takip` klasörüne kopyala.
3. `phpMyAdmin` paneline gir ve `stok_takip_db.sql` dosyasını içe aktararak veritabanını oluştur.
4. Tarayıcıdan projeyi çalıştır:  
   `http://localhost/Stok_Takip`

### 🔐 Admin Giriş Bilgileri

- **E-posta:** `admin@gmail.com`  
- **Şifre:** `12345`

## ⚖️ Lisans

Bu proje [MIT Lisansı](LICENSE) altında lisanslanmıştır.
