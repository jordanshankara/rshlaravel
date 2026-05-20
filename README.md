# RSH Satu Bumi — Laravel

Website & admin panel untuk program retreat kesehatan RSH Satu Bumi.

---

## Setup Lokal (XAMPP di D:\xampp, project di F:\)

Karena project ada di drive berbeda dari XAMPP, cara paling mudah adalah menjalankan
Laravel lewat PHP bawaan XAMPP tanpa perlu mengkonfigurasi Apache Virtual Host.

### 1. Jalankan MySQL XAMPP

Buka **XAMPP Control Panel** → klik **Start** di baris **MySQL**.  
Apache tidak perlu dijalankan.

### 2. Buka terminal di folder project

```
F:\0. Code\rshsatubumi-laravel\
```

Bisa klik kanan di folder → *Open in Terminal*, atau buka PowerShell lalu:

```powershell
cd "F:\0. Code\rshsatubumi-laravel"
```

### 3. Jalankan dev server pakai PHP dari XAMPP

```powershell
D:\xampp\php\php.exe artisan serve
```

Akses di browser: **http://localhost:8000**

> Kalau port 8000 sudah dipakai, tambahkan `--port=8001` (atau port lain).

---

## Pertama Kali Setup (clone fresh)

```powershell
# 1. Install dependencies
D:\xampp\php\php.exe D:\xampp\php\composer.phar install

# 2. Salin file environment
copy .env.example .env

# 3. Generate app key
D:\xampp\php\php.exe artisan key:generate

# 4. Buat database baru di phpMyAdmin (http://localhost/phpmyadmin)
#    Nama database: rshsatubumi (atau sesuai .env DB_DATABASE)

# 5. Jalankan migrasi
D:\xampp\php\php.exe artisan migrate

# 6. Jalankan dev server
D:\xampp\php\php.exe artisan serve
```

---

## Konfigurasi .env Penting

```env
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rshsatubumi
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mail.rshsatubumi.id
MAIL_PORT=587
MAIL_USERNAME=ai@rshsatubumi.id
MAIL_PASSWORD=YourLuxuryHealing123
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="ai@rshsatubumi.id"
MAIL_FROM_NAME="RSH Satu Bumi"
EMAIL_CS="rshsatubumi@gmail.com"
```

---

## URL Penting

| URL | Keterangan |
|-----|------------|
| `http://localhost:8000` | Halaman publik |
| `http://localhost:8000/admin` | Dashboard admin |
| `http://localhost:8000/admin/pengaturan` | Pengaturan + tes email |
| `http://localhost:8000/admin/log` | Log error aplikasi |
| `http://localhost:8000/daftar` | Halaman pendaftaran peserta |

---

## Perintah Artisan yang Sering Dipakai

```powershell
# Jalankan server
D:\xampp\php\php.exe artisan serve

# Bersihkan cache (wajib setelah ubah .env atau config)
D:\xampp\php\php.exe artisan config:clear
D:\xampp\php\php.exe artisan cache:clear
D:\xampp\php\php.exe artisan view:clear

# Migrasi database
D:\xampp\php\php.exe artisan migrate

# Buat symlink storage (untuk akses file upload)
D:\xampp\php\php.exe artisan storage:link
```

---

## Deploy ke Server (Hostwhitelabel)

Upload file, lalu jalankan `deploy.php` sekali via browser dengan token:

```
https://rshsatubumi.id/deploy.php?confirm=DEPLOY_RSH_YYYYMMDD
```

Ganti `YYYYMMDD` dengan tanggal hari ini (contoh: `DEPLOY_RSH_20260520`).  
**Hapus `deploy.php` dari server segera setelah selesai.**
