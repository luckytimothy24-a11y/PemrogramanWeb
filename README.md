# Stockify - Aplikasi Manajemen Stok Gudang

Dashboard admin manajemen inventaris/stok gudang berbasis **Laravel 10** dengan UI **Flowbite** (Tailwind CSS). Ditujukan untuk praktikum Pemrograman Web.

## Fitur

- Autentikasi login/logout dengan 3 peran: **Admin**, **Manajer Gudang**, **Staff**
- Kelola Kategori, Supplier, Produk (dengan atribut produk)
- Transaksi stok masuk & keluar
- Stock opname
- Laporan stok, transaksi, dan aktivitas (termasuk export CSV)
- Log aktivitas pengguna

## Akun Demo (hasil `db:seed`)

| Peran     | Email                | Password   |
|-----------|----------------------|------------|
| Admin     | admin@example.com    | password   |
| Manajer   | manager@example.com  | password   |
| Staff     | staff@example.com    | password   |

## Menjalankan di Lokal (XAMPP)

1. Salin `.env.example` menjadi `.env`, lalu sesuaikan:
   ```
   DB_CONNECTION=mysql
   DB_DATABASE=db_stockify
   DB_USERNAME=root
   DB_PASSWORD=
   ```
2. Buat database MySQL `db_stockify`.
3. Install dependensi & generate key:
   ```
   composer install
   npm install
   php artisan key:generate
   ```
4. Jalankan migrasi + seeder:
   ```
   php artisan migrate --seed
   ```
5. Build aset frontend (atau gunakan `npm run dev` untuk hot reload):
   ```
   npm run build
   ```
6. Jalankan server:
   ```
   php artisan serve
   ```
   Buka `http://localhost:8000`.

## Deploy Publik di Render (Gratis)

Proyek ini sudah disiapkan untuk deploy ke [Render](https://render.com) melalui `render.yaml` (Blueprint) + `Dockerfile`.

1. Pastikan kode sudah di-push ke repo GitHub (repo wajib **Public**).
2. Daftar/login di [render.com](https://dashboard.render.com) memakai akun GitHub.
3. Klik **New** → **Blueprint** → pilih repo GitHub proyek ini.
4. Render akan membaca `render.yaml`, membuat:
   - Web service **stockify** (free)
   - Database PostgreSQL **stockify-db** (free)
5. Klik **Apply** → tunggu build selesai (sekitar 3-5 menit).
6. Buka URL `https://stockify.onrender.com` (nama domain menyesuaikan).
7. Seed data demo satu kali lewat **Shell** di dashboard Render:
   ```
   php artisan db:seed --force
   ```

Catatan untuk plan gratis Render:
- Web service tidur setelah ±15 menit tidak ada akses; butuh beberapa detik saat pertama diakses (cold start).
- Database PostgreSQL free hanya bertahan 30 hari, setelah itu harus dibuat ulang.
- Migrasi otomatis dijalankan setiap start (lihat `startCommand` di `render.yaml`).

## Struktur

- `app/Http/Controllers` - controller per modul
- `app/Repositories` & `app/Services` - pola repository & service
- `app/Models` - model Eloquent
- `database/migrations` - skema tabel
- `resources/views` - template Blade
