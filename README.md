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

## Deploy Publik di Railway (Gratis, Tanpa Kartu)

Proyek sudah disiapkan untuk deploy ke [Railway](https://railway.app) lewat Dockerfile + `railway.json`.

1. Pastikan kode sudah di-push ke repo GitHub (repo boleh **Private**).
2. Login ke [railway.app](https://railway.app) pakai akun GitHub (free tier $5/bulan, tanpa kartu kredit).
3. Klik **New Project** → **Deploy from GitHub repo** → pilih repo proyek ini.
4. Tambahkan database: **New** → **Database** → pilih **PostgreSQL** (gratis, ~500 MB).
5. Buka service **PostgreSQL** → tab **Variables** → salin nilai `DATABASE_URL` (di generate otomatis).
6. Buka service **stockify** → tab **Variables** → tambahkan:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:8ULmy9TGAupHnEh3hbIfgHN3eIgNqa4snQcNwOU3IbA=
   APP_URL=<URL service stockify kamu, mis. https://stockify-production.up.railway.app>
   DB_CONNECTION=pgsql
   DATABASE_URL=<nilai dari langkah 5>
   DB_SSLMODE=require
   ```
   > Karena `.env` tidak ikut di-deploy, `APP_KEY` wajib diisi (pakai nilai di atas atau buat baru dengan `php artisan key:generate --show`). `DATABASE_URL` cukup untuk koneksi; `DB_CONNECTION=pgsql` membuat Laravel memakai koneksi PostgreSQL.
7. Railwail akan build otomatis dari Dockerfile. Saat start otomatis menjalankan `migrate` + `db:seed` + `storage:link`.
8. Buka URL service kamu, lalu login dengan akun demo:
   | Peran   | Email              | Password   |
   |---------|--------------------|------------|
   | Admin   | admin@example.com  | password   |
   | Manajer | manager@example.com| password   |
   | Staff   | staff@example.com  | password   |

Catatan:
- Free tier Railway = $5/bulan kredit (dipakai untuk PostgreSQL + web service kecil). Jika kredit habis, tinggal buat project baru.
- Service akan tidur saat tidak ada request; request pertama agak lambat (cold start).
- File session/cache pakai `file`, jadi login akan hilang saat service restart — normal untuk demo.

## Alur Kerja Gitflow

Proyek ini memakai model branching **Gitflow**:

- `main` — kode produksi/rilis (yang siap di-deploy).
- `develop` — cabang pengembangan tempat semua fitur digabung.
- `feature/<nama>` — dibuat dari `develop` untuk tiap fitur baru.
- `release/<versi>` — persiapan rilis dari `develop`, lalu digabung ke `main`.
- `hotfix/<nama>` — perbaikan darurat dari `main`.

Alur menambah fitur:

```bash
git checkout develop
git pull origin develop
git checkout -b feature/nama-fitur   # kerjakan di sini
git add -A && git commit -m "Tambah fitur ..."
git checkout develop
git pull origin develop
git merge --no-ff feature/nama-fitur
git push origin develop
```

## Struktur

- `app/Http/Controllers` - controller per modul
- `app/Repositories` & `app/Services` - pola repository & service
- `app/Models` - model Eloquent
- `database/migrations` - skema tabel
- `resources/views` - template Blade
