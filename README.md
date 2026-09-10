# Azimutree Web

Website informasi aplikasi Android Azimutree untuk pemetaan dan navigasi lokasi Forest Health Monitoring (FHM).

Website: https://azimutree.my.id/  
Aplikasi: https://github.com/asid30/azimutree-flutter

## Konten publik

- `/`: pengenalan, screenshot, unduhan APK, Titik Ikat, Survey Lokasi, radar, Penyimpanan Awan, dan Excel.
- `/about`: tujuan aplikasi, konsep klaster, teknologi, data, akun, serta izin perangkat.
- `/panduan`: penggunaan data lokal, peta, survei, impor/ekspor, dan awan.
- `/template`: unduhan Excel dengan sheet `panduan`, `klaster`, `titik_ikat`, `plot`, dan `pohon`.

Konten fitur mengacu pada Flutter branch `development`, commit `845cbcb0c1c65ad26c1499ea6ef57b9272a36101`. Rincian dan sumber ada di [acuan Flutter](docs/flutter-development-reference.md).

Pada pemeriksaan 10 September 2026, rilis publik GitHub masih `v1.1.26`, diterbitkan 26 Januari 2026. Rilis ini mendahului fitur development yang dijelaskan. Website menampilkan keterangan perbedaan versi. Ketika APK baru diterbitkan, perbarui URL dan label unduhan di `home.php`, serta keterangan versi di `home.php`, `about.php`, `panduan.php`, dan modal `layout.php`.

Template Google Sheets diperiksa pada 10 September 2026: kelima sheet dan header-nya sesuai format ekspor Flutter yang ditinjau. Konversi impor pada perangkat Android belum diuji dari proyek website ini.

## Repositori Data diarsipkan

Seluruh route Repositori Data telah dihapus dari konfigurasi aktif; auto-routing dinonaktifkan secara eksplisit. Menu disembunyikan. Controller, model, view, dan aset dipertahankan. Database serta file unggahan tidak dihapus.

Lihat [petunjuk arsip](docs/archive/README.md). Penyimpanan Awan Flutter menggunakan Firebase dan terpisah dari modul repositori CodeIgniter ini.

## Teknologi dan struktur

CodeIgniter 4, PHP, HTML, CSS, dan JavaScript vanilla. Proyek mensyaratkan PHP 8.1+ melalui Composer; gunakan versi dan ekstensi yang cocok dengan dependensi terpasang, termasuk `intl` dan `mbstring`.

- `app/Config/Routes.php`: route publik.
- `app/Controllers/Home.php`: penyajian halaman.
- `app/Views/layout.php`: navigasi, metadata, footer, dan modal unduhan.
- `app/Views/home.php`, `about.php`, `panduan.php`, `template.php`: konten publik.
- `public/assets/css/style.css`, `public/assets/js/app.js`: tampilan dan interaksi.
- `public/assets/1.webp` sampai `6.webp`: screenshot landing page. JPG sumber tidak otomatis dikonversi; buat ulang WebP jika gambar diganti. URL screenshot memakai waktu modifikasi untuk pembaruan cache.

## Menjalankan lokal

```bash
composer install
php spark serve
```

Siapkan `.env` lokal dengan `CI_ENVIRONMENT = development` dan `app.baseURL` yang sesuai, misalnya `http://localhost:8080/`. Jangan publikasikan kredensial. Halaman publik tidak membutuhkan database repositori.

## Deployment

Arahkan document root web server ke folder `public/`; simpan `app/`, `vendor/`, `.env`, dan `writable/` di luar document root. Sesuaikan `app.baseURL`, gunakan environment production, dan berikan akses tulis yang dibutuhkan untuk `writable/`.

`public/index.php` saat ini memuat `../app/Config/Paths.php`, sehingga mengharapkan struktur proyek lengkap dengan `public/` sebagai anak direktori proyek.

File `.cpanel.yml` yang ada adalah konfigurasi deployment lama: menghapus isi non-hidden tujuan lalu menyalin `public/*` ke `/home/aziw7273/public_html`. Pola tersebut tidak menyalin `.htaccess`, dan pemisahan lokasi folder memerlukan penyesuaian bootstrap `public/index.php`. Jangan menganggap konfigurasi lama sudah cocok untuk tata letak hosting baru. Peninjauan konten ini tidak mengubah atau menjalankan deployment tersebut.

Setelah deployment, periksa beranda, tentang, panduan, template, aset screenshot, dan modal APK. URL `/repositori-data` harus tetap 404.

## Pemeriksaan lokal

```bash
php spark routes
php -l app/Views/layout.php
node --check public/assets/js/app.js
```

Tes bawaan tersedia di `tests/`; sebagian memerlukan dependensi pengembangan atau database. Pemeriksaan konten publik tidak memerlukan pengaktifan kembali repositori.

## Lisensi

MIT — lihat [LICENSE](LICENSE). Dikembangkan oleh Asid30.
