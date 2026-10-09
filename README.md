# Website Penampung Aspirasi Mahasiswa

> Satu Suara, Benahi Kondisi

---

## 📌 Informasi Kelompok

- **Nomor Kelompok:** Kelompok 05
- **Shift Praktikum:** Shift B

---

## 👥 Anggota Kelompok

| No  | Nama Lengkap                 | NIM       | Shift Awal   | Shift Akhir | Jobdesk / Kontribusi                                                               | Link Video Penjelasan                                                 |
| --- | ---------------------------- | --------- | ------------ | ----------- | ---------------------------------------------------------------------------------- | --------------------------------------------------------------------- |
| 1   | Chaedar Ali Amrulloh         | H1H024044 | Shift B      | Shift B     | [Jobdesk Fitur]                                                                    | [YouTube/Drive](https://...)                                          |
| 2   | Bintang Nugraha Putra        | H1H024045 | Shift B      | Shift B     | Backend (API route, Model, Migration, API Controller, lebih jelasnya ada di video) | [YouTube: https://youtu.be/9PEWFO2AD5Q](https://youtu.be/9PEWFO2AD5Q) |
| 3   | Gerard Roland Kusuma Sarwoko | H1H024047 | [Shift Awal] | Shift B     | [Jobdesk Fitur]                                                                    | [YouTube/Drive](https://...)                                          |

---

## 📖 Deskripsi Aplikasi

Aspirasi dibutuhkan untuk menunjang kesejahteraan di masa yang akan datang. Suara aspirasi yang terpecah sulit untuk mendapatkan perhatian umum, Website yang kami kembangkan bertujuan untuk menyatukan suara-suara aspirasi agar lebih banyak didengar dan diakui umum dengan harapan aspirasi tersebut dapat terpenuhi. Kami harap mahasiswa bisa menggunakan website kami sebagai platform untuk menyuarakan hal yang mengganjal di benak pikiran.

---

## ⚙️ Penjelasan Teknis

### 1. Teknologi (Tech Stack)

- **Backend:** Laravel 13 (PHP 8.4.12)
- **Frontend:**
    - Blade
    - Tailwind CSS
    - Vite
- **Database:** MySQL
- **Library / Package:**
    - Laravel Sanctum
    - Laravel Boost

### 2. Fitur Utama & Modul

- **Autentikasi & Otorisasi:**
    - Sistem login/register untuk mahasiswa dan admin
    - Role-based access control untuk membatasi akses fitur sesuai peran
    - Middleware guard untuk menjaga halaman admin dan halaman user agar aman

- **Modul Aspirasi Mahasiswa:**
    - Mahasiswa dapat mengirim aspirasi baru dengan judul, kategori, isi aspirasi, dan lampiran/file pendukung
    - Fitur CRUD (create, read, update, delete) untuk aspirasi milik user
    - Validasi input agar data yang masuk konsisten dan aman
    - Status aspirasi seperti menunggu, diproses, ditanggapi, atau selesai

- **Modul Kategori & Pencarian Aspirasi:**
    - Aspirasi dikelompokkan berdasarkan kategori tertentu agar lebih mudah dicari
    - Fitur filter, pencarian, dan pengurutan daftar aspirasi berdasarkan popularitas atau waktu
    - Tampilan daftar aspirasi yang rapi dan mudah dipahami

- **Modul Komentar & Dukungan:**
    - Pengguna dapat memberikan komentar pada aspirasi yang relevan
    - Fitur upvote / dukungan untuk aspirasi yang dianggap penting
    - Interaksi ini membantu menentukan aspirasi yang paling banyak mendapat perhatian

- **Modul Admin / Dashboard:**
    - Admin dapat melihat seluruh aspirasi masuk dari semua mahasiswa
    - Admin dapat menilai, menanggapi, mengubah status, dan mengelola data aspirasi
    - Dashboard admin menampilkan statistik seperti total aspirasi, aspirasi aktif, dan aspirasi selesai

- **Modul Notifikasi & Reporting:**
    - Sistem notifikasi untuk memberi tahu user dan admin mengenai perubahan status aspirasi atau balasan
    - Fitur laporan ringkas untuk memantau perkembangan aspirasi dan performa platform
    - Data dapat diolah untuk kebutuhan evaluasi dan tindak lanjut kebijakan kampus

### 3. Skema Data Singkat

- `users` (1 : N) `aspirations`
- `users` (1 : N) `comments`
- `aspirations` (1 : N) `comments`
- `users` (M : N) `upvotes`
- `categories` (M : N) `aspirations`
- `aspirations` (M : N) `upvotes`

---

## 🚀 Panduan Instalasi Lokal

```bash
# Clone repository
git clone https://github.com/laynsleep/Kelompok5-ShiftB-ResponsiPemweb2Laravel.git
cd Kelompok5-ShiftB-ResponsiPemweb2Laravel

# Install dependensi PHP & Node
composer install
npm install

# Konfigurasi Environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di file .env, lalu migrasi & seed
php artisan migrate --seed

# Jalankan development server
php artisan serve
npm run dev
```
