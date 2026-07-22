# Omnia

![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4-EF4223?logo=codeigniter&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-database-4169E1?logo=postgresql&logoColor=white)
![License](https://img.shields.io/badge/license-AGPL--3.0-blue)

Sistem informasi manajemen rumah sakit berbasis web, hasil porting bertahap dari SIMKES Khanza yang sebelumnya berjalan sebagai aplikasi desktop. Pengembangan dilakukan sebagai tugas akhir secara tim, dengan tiap anggota bertanggung jawab atas kelompok modul yang berbeda di atas arsitektur Core yang sama. Daftar modul yang tersedia dapat dilihat pada direktori `app/Features/`.

## Tech Stack

| Layer | Technology |
|---|---|
| Language & Framework | ![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white) ![CodeIgniter](https://img.shields.io/badge/CodeIgniter-EF4223?logo=codeigniter&logoColor=white) |
| Database | ![PostgreSQL](https://img.shields.io/badge/PostgreSQL-4169E1?logo=postgresql&logoColor=white) |
| Production Server | ![FrankenPHP](https://img.shields.io/badge/FrankenPHP-black?logo=php&logoColor=white) ![Caddy](https://img.shields.io/badge/Caddy-1F88C0?logo=caddy&logoColor=white) |
| Frontend | ![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?logo=tailwindcss&logoColor=white) ![Preline UI](https://img.shields.io/badge/Preline_UI-0066FF?logo=preline&logoColor=white) |
| Code Quality | ![Mago](https://img.shields.io/badge/Mago-FF5722?logo=php&logoColor=white) ![GitHub Actions](https://img.shields.io/badge/GitHub_Actions-2088FF?logo=githubactions&logoColor=white) |

## Getting Started

```bash
git clone <url-repo>
cd Khanza
composer install
```

Siapkan PostgreSQL dan sesuaikan konfigurasi `database.default.*` pada `.env`, lalu jalankan:

```bash
php spark migrate
php spark omnia:route
php spark omnia:sidebar
php spark omnia:icon
php spark serve
```

Langkah instalasi dan konfigurasi yang lebih rinci ada di [DEPLOYMENT.md](DEPLOYMENT.md).

## Documentation

| Document | Description |
|---|---|
| [ARCHITECTURE.md](ARCHITECTURE.md) | Struktur Core dan Feature, serta pola yang diikuti saat menambah modul baru |
| [DEPLOYMENT.md](DEPLOYMENT.md) | Prasyarat, setup awal, dan deployment |
