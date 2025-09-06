# 🚀 Quick Start Guide - DOKEMA

Panduan cepat untuk menjalankan sistem DOKEMA dalam hitungan menit.

## Prerequisites Checklist

-   [ ] PHP >= 8.2 installed
-   [ ] Composer installed
-   [ ] Node.js & NPM installed
-   [ ] MariaDB/MySQL running
-   [ ] Git installed

## 5-Minute Setup

### 1. Clone & Install (2 minutes)

```bash
git clone https://github.com/sukmaajidigital/dokema.git
cd dokema
composer install
npm install
```

### 2. Environment Setup (1 minute)

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file - update database credentials:

```env
DB_DATABASE=dokema
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Database Setup (1 minute)

```bash
# Create database 'dokema' in your MySQL
php artisan migrate:fresh
php artisan db:seed
```

### 4. Build & Run (1 minute)

```bash
npm run build
php artisan serve
```

🎉 **Done!** Visit http://localhost:8000

## Default Login Credentials

### HR Admin

-   **Email**: `hr@dokema.com`
-   **Password**: `password`

### Pembimbing

-   **Email**: `pembimbing@dokema.com`
-   **Password**: `password`

### Peserta Magang

-   **Email**: `magang@dokema.com`
-   **Password**: `password`

## Quick Feature Tour

1. **Dashboard** - Overview statistik sistem
2. **Workflow Approval** - Proses persetujuan magang
3. **User Management** - Kelola akun pengguna
4. **Data Magang** - Pendaftaran dan status magang
5. **Laporan & Bimbingan** - Tracking kegiatan
6. **Penilaian** - Evaluasi akhir peserta

## Development Mode

Untuk development dengan hot reload:

```bash
npm run dev
# Di terminal lain:
php artisan serve
```

## Troubleshooting

### Database Connection Error

-   Pastikan MySQL running
-   Check credentials di `.env`
-   Database `dokema` sudah dibuat

### Permission Error

```bash
chmod -R 775 storage bootstrap/cache
```

### NPM/Node Issues

```bash
npm cache clean --force
npm install
```

### Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Next Steps

-   Baca [README.md](README.md) untuk dokumentasi lengkap
-   Lihat [COMPONENTS.md](COMPONENTS.md) untuk arsitektur komponen
-   Check [CONTRIBUTING.md](CONTRIBUTING.md) untuk berkontribusi

## Support

Butuh bantuan? Hubungi support@sukmaajidigital.com

---

**Selamat mencoba DOKEMA!** 🎯
