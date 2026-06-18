# SIPELITA Backend API (Sistem Informasi Penilaian Nirwasita Tantra)

Repositori ini berisi kode sumber backend (API) untuk aplikasi **SIPELITA**, dibangun menggunakan framework **Laravel 12 (PHP 8.3)** dan menggunakan **MySQL 8.0** sebagai basis data.

---

## 🛠️ Tech Stack & Requirements

*   **Framework:** Laravel 12.x
*   **PHP CLI:** ^8.3
*   **Database:** MySQL 8.0
*   **Authentication:** Laravel Sanctum
*   **CORS Whitelist:** Mengizinkan request dari localhost port 3000, Vercel, serta IP privat VPS UGM (`10.33.35.48`).

---

## 🐳 Dockerization

Untuk deployment terdistribusi di VPS UGM, backend ini telah di-dockerize menggunakan:
*   **`Dockerfile`**: Menggunakan base image `php:8.3-fpm-alpine` yang ringan dan tool resmi `php-extension-installer` untuk kompilasi cepat ekstensi PHP (gd, zip, pdo_mysql, mbstring).
*   **`docker/nginx.conf`**: Konfigurasi reverse proxy Nginx untuk melayani file static dan meneruskan request dinamis ke kontainer PHP-FPM di port 9000.

---

## ⚡ Optimasi & Perbaikan Kinerja

1.  **Simple Testing Data Seeder:**
    *   Mengganti seeder default dengan `SimpleTestingDataSeeder.php` di lingkungan lokal/WSL/container.
    *   Berhasil memangkas waktu seeding database dari **34 detik menjadi hanya 2.9 detik** dengan membatasi copy file dummy yang lambat di Virtual Machine disk.
2.  **Bypass Directory Size Scan:**
    *   Bypass pemindaian ukuran direktori rekursif lokal di `DashboardController.php` untuk mempercepat pemuatan halaman dashboard utama dari **30 detik menjadi di bawah 100ms** pada local environment.

---

## 💻 Cara Install & Menjalankan secara Lokal

1.  **Clone repositori:**
    ```bash
    git clone https://github.com/Xte-1412/AreaBE-PAD.git
    cd AreaBE-PAD
    ```
2.  **Install dependensi via Composer:**
    ```bash
    composer install
    ```
3.  **Salin file konfigurasi `.env`:**
    ```bash
    cp .env.example .env
    # Sesuaikan konfigurasi DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, dan DB_PASSWORD
    ```
4.  **Jalankan migrasi dan seeder database:**
    ```bash
    php artisan migrate --seed
    ```
5.  **Jalankan server lokal:**
    ```bash
    php artisan serve
    ```

---

## 🐋 Cara Menjalankan Menggunakan Docker (Stand-alone)

1.  **Build Docker Image:**
    ```bash
    docker build -t areapad-backend .
    ```
2.  **Jalankan Container (PHP-FPM):**
    ```bash
    docker run -p 9000:9000 areapad-backend
    ```
