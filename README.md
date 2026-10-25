
# Digitalisasi Perpustakaan SMPN 147 Jakarta

Website perpustakaan digital SMP Negeri 147 Jakarta yang dikembangkan untuk mempermudah siswa dalam mengakses koleksi buku digital, menonton film edukatif, dan mengunggah karya secara digital. Website ini dilengkapi dengan sistem pengelolaan konten, rating dan ulasan, serta dashboard admin untuk mendukung pengelolaan perpustakaan.

## Fitur Utama

### User (Siswa)

- **Dashboard** — Menampilkan halaman utama dan informasi karya yang telah diunggah.
- **Buku Digital** — Menampilkan koleksi buku yang dapat dibaca melalui website.
- **Film Edukatif** — Menyediakan koleksi film yang dapat ditonton secara langsung.
- **Pencarian** — Memudahkan pencarian koleksi buku dan film.
- **Detail Buku dan Film** — Menampilkan deskripsi, informasi pengunggah, tanggal unggah, dan rating.
- **Baca Buku** — Menyediakan fitur membaca buku digital dan melihat progres membaca.
- **Rating dan Review** — Memungkinkan siswa memberikan penilaian dan ulasan terhadap buku atau film.
- **Unggah Karya** — Memungkinkan siswa mengunggah karya berupa file dan thumbnail.
- **Chatbot** — Membantu memberikan rekomendasi buku, video populer, dan informasi perpustakaan.
- **Registrasi dan Login** — Menyediakan autentikasi pengguna untuk mengakses fitur website.

### Admin

- **Dashboard Admin** — Menampilkan statistik siswa, karya, aktivitas pengguna, dan konten yang menunggu persetujuan.
- **Manajemen Data Siswa** — Melihat, mengubah, dan menghapus data siswa.
- **Aktivitas Siswa** — Memantau aktivitas pengguna dan tanggal akses.
- **Manajemen Review dan Komentar** — Memantau dan menghapus komentar pada buku atau film.
- **Manajemen Karya** — Melihat, mencari, menyortir, dan mengelola karya siswa.
- **Persetujuan Karya** — Menyetujui atau menolak karya yang diunggah siswa.
- **Unggah Karya Admin** — Menambahkan karya baru melalui panel admin.
- **Detail Karya** — Melihat informasi karya, pengunggah, tanggal unggah, status persetujuan, dan file karya.

## Teknologi yang Digunakan

| Teknologi | Keterangan |
|---|---|
| Laravel | Framework backend |
| PHP | Bahasa pemrograman backend |
| MySQL | Sistem manajemen basis data |
| Tailwind CSS | Framework CSS untuk antarmuka pengguna |
| Vite | Build tool untuk aset frontend |
| Alpine.js | Mendukung interaksi antarmuka pengguna |
| Composer | Pengelola dependensi PHP |
| NPM | Pengelola dependensi frontend |

## Persyaratan Sistem

Pastikan perangkat telah memiliki:

- PHP 8.2 atau versi yang sesuai dengan dependensi proyek
- Composer
- MySQL
- Node.js dan NPM
- Git
- Web browser

## Instalasi dan Konfigurasi

### 1. Clone Repository

```bash
git clone https://github.com/Culaa1125/digitalisasi-perpussmpn147.git
```

Masuk ke direktori proyek:

```bash
cd digitalisasi-perpussmpn147
```

### 2. Instal Dependensi Backend

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

**Windows:**

```bat
copy .env.example .env
```

**Linux, macOS, atau Git Bash:**

```bash
cp .env.example .env
```

Buat application key Laravel:

```bash
php artisan key:generate
```

### 4. Konfigurasi Database MySQL

Buat database baru melalui MySQL atau phpMyAdmin.

Contoh:

```sql
CREATE DATABASE digitalisasi_perpustakaan;
```

Selanjutnya, buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=digitalisasi_perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nama database, username, dan password berdasarkan konfigurasi MySQL pada perangkat masing-masing.

### 5. Migrasi Database

Jalankan perintah berikut:

```bash
php artisan migrate
```

Jika proyek memerlukan data awal, periksa konfigurasi seeder sebelum menjalankannya.

### 6. Instal Dependensi Frontend

```bash
npm install
```

### 7. Jalankan Website

Buka dua terminal pada direktori proyek.

**Terminal pertama — Laravel:**

```bash
php artisan serve
```

**Terminal kedua — Vite:**

```bash
npm run dev
```

Setelah kedua proses berjalan, buka browser dan akses:

http://127.0.0.1:8000

### 8. Build Aset Frontend

Untuk membangun aset frontend:

```bash
npm run build
```
