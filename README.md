# 🚗 SiParkir UKK

Sistem Informasi Parkir berbasis PHP Native dengan penerapan konsep **OOP (Object Oriented Programming)** dan arsitektur **MVC (Model View Controller)**.

Proyek ini dibuat untuk membantu pengelolaan data parkir kendaraan, data petugas, transaksi parkir, serta laporan yang dapat diakses sesuai hak akses pengguna.

---

## ✨ Fitur Utama

### Admin
- Mengelola data pengguna
- Mengelola data kendaraan
- Mengelola data petugas
- Mengelola data pemilik kendaraan
- Melihat seluruh transaksi parkir
- Mengelola laporan

### Petugas
- Input kendaraan masuk
- Input kendaraan keluar
- Mengelola transaksi parkir
- Melihat riwayat transaksi

### Owner
- Melihat laporan transaksi
- Melihat statistik parkir
- Monitoring pendapatan parkir

---

## 🏗️ Teknologi Yang Digunakan

- PHP Native OOP
- MySQL
- MVC Architecture
- Bootstrap
- JavaScript
- CSS

---

## 📂 Struktur Folder

```text
siparkir_ukk/
│
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
│
├── config/
│
├── public/
│   ├── assets/
│   └── index.php
│
├── database/
│
└── README.md
```

---

## ⚙️ Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/yaspaizrailla95-collab/siparkir_ukk.git
```

### 2. Pindahkan ke Folder Server

Jika menggunakan Laragon:

```text
C:\laragon\www\
```

### 3. Import Database

- Buka phpMyAdmin
- Buat database sesuai konfigurasi aplikasi
- Import file SQL yang tersedia pada folder database

### 4. Atur Konfigurasi Database

Sesuaikan file konfigurasi:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'siparkir');
```

### 5. Jalankan Aplikasi

```text
http://localhost/siparkir_ukk/public
```

---

## 🔑 Akun Login

### Admin

| Username | Password |
|-----------|-----------|
| yaspa | 123456 |

### Owner

| Username | Password |
|-----------|-----------|
| mantapparva | 123456 |

### Petugas

| Username | Password |
|-----------|-----------|
| malvien | 123456 |

---

## 📸 Tampilan Sistem

Tambahkan screenshot aplikasi pada bagian ini.

```text
assets/screenshots/
```

Contoh:

- Dashboard
- Data Kendaraan
- Data Parkir
- Laporan
- Login

---

## 🎯 Konsep MVC

### Model
Berfungsi untuk mengelola data dan berinteraksi dengan database.

### View
Berfungsi menampilkan antarmuka kepada pengguna.

### Controller
Berfungsi sebagai penghubung antara Model dan View.

---

## 👨‍💻 Pengembang

**Yaspa Izrailla**

Project UKK Rekayasa Perangkat Lunak (RPL)

GitHub:
https://github.com/yaspaizrailla95-collab

---

## 📄 Lisensi

Project ini dibuat untuk keperluan pembelajaran, pengembangan portofolio, dan tugas UKK.
