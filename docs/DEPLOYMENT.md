# Deployment Guide

![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4-EF4223?logo=codeigniter&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-18%2B-4169E1?logo=postgresql&logoColor=white)

Panduan setup environment dan menjalankan Omnia dari awal sampai bisa diakses di browser.

## Daftar Isi

1. [Prasyarat](#prasyarat)
2. [Environment Variables](#environment-variables)
3. [Instalasi & Menjalankan](#instalasi--menjalankan)
4. [Build Aset Frontend (Opsional)](#build-aset-frontend-opsional)
5. [Referensi](#referensi)

## Prasyarat

| Kebutuhan | Versi |
|---|---|
| PHP | ^8.5, dengan extension `xml` dan `pgsql` |
| Composer | terbaru |
| PostgreSQL | 18+ |
| Node.js + npm | hanya jika mengubah styling Tailwind, lihat [Build Aset Frontend](#build-aset-frontend-opsional) |

### Ubuntu / Linux

Instalasi cepat (panduan lengkap dengan detail & screenshot ada di [Wiki - Setup Environment Khanza](https://github.com/ikti-its/khanza/wiki/1.-Setup-Environment-Khanza)):

```bash
# PHP 8.5 + extension yang dibutuhkan
sudo apt update
sudo apt install php8.5 php8.5-xml php8.5-pgsql

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# PostgreSQL 18+
sudo apt install postgresql
sudo systemctl status postgresql
```

### Windows

Belum ada panduan resmi di wiki untuk Windows. Instalasi native:

```powershell
# PHP 8.5 - unduh dari https://windows.php.net/download/ (Thread Safe, x64),
# ekstrak, lalu tambahkan foldernya ke PATH.
# Di php.ini, aktifkan (hapus tanda ";" di depan):
#   extension=pgsql
#   extension=xml

# Composer - unduh & jalankan installer resmi:
# https://getcomposer.org/Composer-Setup.exe

# PostgreSQL 18+ - unduh installer resmi:
# https://www.postgresql.org/download/windows/
```

### Set Password User `postgres`

Instalasi PostgreSQL baru biasanya belum punya password untuk user `postgres`. Kalau PostgreSQL di komputer kamu sudah pernah diinstall sebelumnya dan sudah punya password sendiri, **lewati langkah ini** - langsung pakai password yang sudah ada itu di `database.default.password` pada `.env`.

Kalau ini instalasi baru, set password-nya terlebih dahulu:

```bash
sudo -u postgres psql
```

```sql
ALTER USER postgres PASSWORD 'postgres';
\q
```

(Windows: buka psql lewat Command Prompt/pgAdmin dengan user `postgres`, lalu jalankan `ALTER USER` yang sama.)

### Verifikasi Instalasi

Berlaku untuk Ubuntu maupun Windows:

```bash
php -v
php -m        # pastikan xml dan pgsql terdaftar
composer -v
psql -U postgres -h 127.0.0.1
```

## Environment Variables

Konfigurasi ada di file `.env` pada root repo. Variabel utama yang perlu disesuaikan:

| Variabel | Keterangan |
|---|---|
| `CI_ENVIRONMENT` | `development` untuk lokal, `production` untuk deploy |
| `app.baseURL` | URL dasar aplikasi, misal `http://localhost:8080` |
| `database.default.hostname` | Host PostgreSQL, misal `localhost` |
| `database.default.database` | Database *admin* yang sudah pasti ada (misal `postgres`) - dipakai untuk koneksi awal, **bukan** nama database aplikasi |
| `database.default.username` / `password` | Kredensial PostgreSQL |
| `database.default.DBDriver` | `Postgre` |
| `database.default.port` | `5432` (default PostgreSQL) |
| `database.default.khanza_db` | Nama database aplikasi (misal `khanza_db`) - dibuat otomatis saat migrasi, lihat langkah 3 di bawah |

> `database.default.database` dan `database.default.khanza_db` sengaja dipisah: koneksi pertama kali dibuat ke database admin (`postgres`), lalu migration `InitDatabase` membuat database aplikasi (`khanza_db`) secara otomatis lewat `Forge::createDatabase()`.

## Instalasi & Menjalankan

Dari root repo:

1. Clone repo dan masuk ke foldernya:

   ```bash
   git clone https://github.com/ikti-its/khanza.git
   cd khanza
   ```

2. Konfigurasi `.env` sesuai tabel [Environment Variables](#environment-variables) di atas:

   ```
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost:8080'

   database.default.hostname  = localhost
   database.default.database  = postgres
   database.default.username  = postgres
   database.default.password  = <password postgres kamu>
   database.default.DBDriver  = Postgre
   database.default.port      = 5432
   database.default.khanza_db = khanza_db
   ```

3. Install dependency PHP:

   ```bash
   composer install
   ```

4. Jalankan migrasi. Migration `InitDatabase` otomatis membuat database `khanza_db` lalu mengisi struktur tabel & data awal - **tidak perlu `CREATE DATABASE` manual**:

   ```bash
   php spark migrate
   ```

5. Generate route, sidebar, dan icon untuk tiap modul di `app/Features/` (wajib, tanpa ini menu sidebar & routing modul tidak akan muncul):

   ```bash
   php spark omnia:route
   php spark omnia:sidebar
   php spark omnia:icon
   ```

6. Jalankan development server:

   ```bash
   php spark serve
   ```

   Aplikasi berjalan di `http://localhost:8080`.

   > Jika ingin menggunakan port lain, ubah `app.baseURL` di `.env` (nilai `.env` selalu meng-override default di `app/Config/App.php`) supaya portnya sama dengan yang digunakan `php spark serve --port <port>`. Kalau portnya berbeda, tampilan/asset/login bisa error.

7. Login dengan akun default:

   - Username: `admin@fathoor.dev`
   - Password: `admin`

## Build Aset Frontend (Opsional)

Styling pakai Tailwind CSS + Preline UI, dikonfigurasi di [.config/tailwind.config.js](../.config/tailwind.config.js). Hasil build (`public/css/style.css`) sudah di-commit ke repo, jadi langkah ini **tidak wajib** untuk sekadar menjalankan aplikasi - hanya diperlukan kalau kamu menambah/mengubah class Tailwind yang belum pernah dipakai sebelumnya.

```bash
npm install --prefix .config
npx tailwindcss --config .config/tailwind.config.js -i public/css/input.css -o public/css/style.css --watch
```

## Referensi

- [Wiki: Setup Environment Khanza](https://github.com/ikti-its/khanza/wiki/1.-Setup-Environment-Khanza)
- [Wiki: Menjalankan Khanza](https://github.com/ikti-its/khanza/wiki/2.1.-Menjalankan-Khanza)
- [README.md](README.md)
