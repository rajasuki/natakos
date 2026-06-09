# IchiKOS — Sistem Manajemen Kos

Aplikasi web manajemen kos (boarding house) berbasis **Laravel 13**, **PHP 8.3**, **Blade**, **Tailwind 4**, dan **Vite 8**.

IchiKOS membantu pemilik kos mengelola kamar, penghuni, pembayaran, dan fasilitas secara terpadu. Dilengkapi halaman publik untuk menampilkan kamar yang tersedia, serta dashboard terpisah untuk admin dan penghuni.

---

## Fitur

### Halaman Publik
- Beranda dengan daftar kamar unggulan dan fasilitas populer.
- Halaman daftar kamar dengan filter harga, status, fasilitas, dan pencarian.
- Halaman detail kamar dengan galeri foto, spesifikasi, dan daftar fasilitas.
- Tautan WhatsApp langsung ke pengelola.

### Dashboard Admin
- **Manajemen Kamar**: CRUD kamar dengan slug otomatis, foto utama, galeri foto, dan pemilihan fasilitas.
- **Manajemen Fasilitas**: CRUD fasilitas dengan ikon terintegrasi Material Symbols.
- **Manajemen Penghuni**: Catat penghuni ke kamar, proses checkout, riwayat hunian.
- **Manajemen Pembayaran**: Catat pembayaran, verifikasi bukti transfer (approve/reject), ekspor data.
- **Ekspor Data**: CSV untuk kamar, penghuni, dan pembayaran.

### Dashboard Penghuni
- Informasi kamar yang ditempati.
- Status pembayaran dan tenggat waktu.

### Manajemen Role
- Role `admin` dan `tenant` dengan redirect otomatis setelah login.
- Middleware terpisah untuk masing-masing role.

---

## Persyaratan Sistem

| Komponen | Versi |
|---|---|
| PHP | ^8.3 |
| Composer | ^2.0 |
| Node.js | ^20 atau ^22 |
| NPM | ^9 atau ^10 |
| Database | MySQL 8.0+ atau SQLite |
| Ekstensi PHP | BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML |

---

## Instalasi

### 1. Clone Repository

```bash
git clone <url-repository> natakos
cd natakos
```

### 2. Install Dependencies

```bash
composer install
npm install --ignore-scripts
```

### 3. Konfigurasi Environment

Salin file environment lalu sesuaikan pengaturan database:

```bash
cp .env.example .env
php artisan key:generate
```

Buka `.env` dan sesuaikan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=natakos
DB_USERNAME=root
DB_PASSWORD=
```

> **Catatan**: Untuk pengembangan lokal, Anda bisa menggunakan SQLite dengan membiarkan `DB_CONNECTION=sqlite` dan membuat file `database/database.sqlite`.

### 4. Buat Database

Buat database MySQL:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS natakos"
```

Atau jika menggunakan SQLite:

```bash
touch database/database.sqlite
```

### 5. Setup Storage Link

```bash
php artisan storage:link
```

Symlink `public/storage` → `storage/app/public` untuk akses foto kamar dan logo.

### 6. Migrasi & Seeder

```bash
php artisan migrate --seed
```

Seeder akan membuat:
- **12 fasilitas** (Kasur, AC, WiFi, Parkir, dll.)
- **5 akun penghuni** (Andi, Budi, Citra, Dedi, Eka — password: `password`)
- **1 akun admin** (email: `admin@ichikos.test`, password: `password`)
- **8 kamar** dengan pembagian fasilitas
- **5 penempatan penghuni** dengan riwayat
- **7 catatan pembayaran** (berbagai status)

### 7. Build Frontend

```bash
npm run build
```

### 8. Setup Queue Worker

IchiKOS menggunakan database queue. Jalankan migrasi tabel queue:

```bash
php artisan queue:table
php artisan migrate
```

### 9. Jalankan Aplikasi

Untuk pengembangan:

```bash
composer dev
```

Perintah ini menjalankan 4 proses sekaligus:
- `php artisan serve` — server HTTP di `http://localhost:8000`
- `php artisan queue:listen` — worker queue
- `php artisan pail` — log viewer real-time
- `npm run dev` — Vite dev server (HMR)

Atau manual:

```bash
php artisan serve &
php artisan queue:listen --tries=1 --timeout=0 &
npm run dev
```

### 10. Buka Aplikasi

Akses di browser: **http://localhost:8000**
atau membuka link : **http://ardhan-dev.com**
---

## Setup Cepat (Satu Perintah)

```bash
composer setup
```

Perintah ini menjalankan: `composer install` → buat `.env` → `key:generate` → `migrate --force` → `npm install --ignore-scripts` → `npm run build`.

Setelah itu jalankan:

```bash
php artisan storage:link
php artisan serve
```

---

## Menjalankan Test

```bash
composer test
```

Test menggunakan in-memory SQLite, tidak memengaruhi database utama. Terdapat 13+ test yang mencakup:

- Workflow pembayaran
- Workflow penghuni
- Room occupancy
- Fitur publik

---

## Route Utama

| URL | Role | Deskripsi |
|---|---|---|
| `/` | Publik | Beranda |
| `/rooms` | Publik | Daftar kamar |
| `/rooms/{room:slug}` | Publik | Detail kamar |
| `/login` | Publik | Login |
| `/register` | Publik | Register |
| `/dashboard` | Semua | Dashboard (redirect by role) |
| `/admin/*` | Admin | Manajemen kamar, fasilitas, penghuni, pembayaran |
| `/tenant/*` | Penghuni | Dashboard penghuni |

---

## Tech Stack

- **Backend**: Laravel 13, PHP 8.3
- **Frontend**: Blade, Tailwind 4, Vite 8, Material Symbols
- **Database**: MySQL (runtime), SQLite (test)
- **Queue**: Database driver
- **Storage**: Local `public` disk untuk gambar kamar & logo

---

## Lisensi

MIT
