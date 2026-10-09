# Website Penampung Aspirasi Mahasiswa
> Satu Suara, Benahi Kondisi

---

## 📌 Informasi Kelompok
- **Nomor Kelompok:** Kelompok 05
- **Shift Praktikum:** Shift B

---

## 👥 Anggota Kelompok

| No |         Nama Lengkap         |    NIM    | Shift Awal | Shift Akhir | Jobdesk / Kontribusi | Link Video Penjelasan |
|----|------------------------------|-----------|------------|-------------|----------------------|-----------------------|
| 1  | Chaedar Ali Amrulloh         | H1H024044 | Shift B    | Shift B     | [Jobdesk Fitur] | [YouTube/Drive](https://...) |
| 2  | Bintang Nugraha Putra        | H1H024045 | [Shift Awal] | Shift B   | [Jobdesk Fitur] | [YouTube/Drive](https://...) |
| 3  | Gerard Roland Kusuma Sarwoko | H1H024047 | [Shift Awal] | Shift B   | [Jobdesk Fitur] | [YouTube/Drive](https://...) |

---

## 📖 Deskripsi Aplikasi
Aspirasi dibutuhkan untuk menunjang kesejahteraan di masa yang akan datang. Suara aspirasi yang terpecah sulit untuk mendapatkan perhatian umum, Website yang kami kembangkan bertujuan untuk menyatukan suara-suara aspirasi agar lebih banyak didengar dan diakui umum dengan harapan aspirasi tersebut dapat terpenuhi. Kami harap mahasiswa bisa menggunakan website kami sebagai platform untuk menyuarakan hal yang mengganjal di benak pikiran.

---

## ⚙️ Penjelasan Teknis

### 1. Teknologi (Tech Stack)
- **Backend:** Laravel 13 (PHP 8.4.12)
- **Frontend:** Blade, Tailwind CSS, JavaScript
- **Database:** MySQL 
- **Library / Package:** [Contoh: Laravel Breeze, DomPDF, Filament, dll.]

### 2. Fitur Utama & Modul
- **Autentikasi & Otorisasi:** Role admin, mahasiswa
- **[Modul 1]:** [CRUD data, validasi, upload file]
- **[Modul 2]:** [Fitur transaksi, reporting, notifikasi]

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