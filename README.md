# Azimutree — Website Statis

Website informasi aplikasi Android Azimutree untuk pemetaan dan navigasi lokasi Forest Health Monitoring (FHM). Versi aktif berupa HTML, CSS, dan JavaScript biasa di folder **`site/`**, siap untuk GitHub Pages tanpa PHP, Composer, database, atau proses build.

## Halaman

- `site/index.html`: beranda, screenshot, fitur, unduhan APK.
- `site/about/index.html`: tentang aplikasi.
- `site/panduan/index.html`: panduan penggunaan.
- `site/template/index.html`: tautan dan petunjuk template Excel.
- `site/easteregg/secret/index.html`: pintu masuk easter egg.
- `site/easteregg/mybestie/index.html`: kuis easter egg.
- `site/404.html`: halaman tidak ditemukan.
- `site/assets/`: stylesheet, JavaScript, dan gambar publik.

Alamat halaman menggunakan folder, misalnya `/about/`. Tautan relatif mendukung root domain dan GitHub project Pages seperti `https://USERNAME.github.io/REPOSITORY/`.

## Preview dan pemeriksaan

Dari root repository:

```bash
python3 scripts/check_static.py
python3 -m http.server 8080 --directory site
```

Buka `http://localhost:8080/`. Tidak perlu menjalankan CodeIgniter. Untuk preview selalu gunakan `site/` sebagai document root.

## Terbitkan ke GitHub Pages

1. Push perubahan ke branch default repository (`main` pada proyek ini).
2. Buka **Settings → Pages → Build and deployment → Source**, pilih **GitHub Actions**.
3. Buka **Actions → Publish static site to GitHub Pages → Run workflow**, pilih branch default untuk penerbitan pertama jika push sebelumnya terjadi sebelum Pages diaktifkan.
4. Setelah workflow selesai, alamat website ditampilkan di deployment `github-pages` dan Settings → Pages.

Workflow `.github/workflows/pages.yml` memeriksa HTML lalu mengunggah **hanya `site/`**. Push berikutnya ke `main`/`master` (yang merupakan branch default) akan menerbitkan perubahan pada folder situs. Jika branch default menggunakan nama lain, sesuaikan daftar branch pada workflow.

Tidak membutuhkan secret tambahan; workflow menggunakan izin GitHub Pages dan token Actions bawaan. Pengaturan Pages di GitHub tetap perlu dilakukan oleh pemilik repository. Workflow belum dijalankan dari workspace lokal ini.

Untuk memakai domain sendiri, atur **Custom domain** di Settings → Pages dan DNS sesuai petunjuk GitHub. Domain lama tidak otomatis dipindahkan oleh konversi ini.

Dokumentasi: https://docs.github.com/en/pages/getting-started-with-github-pages/using-custom-workflows-with-github-pages

## Mengubah konten

Edit HTML langsung di `site/`. Navigasi dan footer ada di masing-masing halaman, jadi sesuaikan seluruh halaman ketika mengubah bagian bersama. Edit tampilan di `site/assets/css/style.css` dan interaksi di `site/assets/js/`.

Screenshot aktif adalah `site/assets/1.webp` sampai `6.webp`. Mengganti JPG sumber di `public/assets/` tidak otomatis memperbarui situs statis. Konversi lalu salin WebP ke `site/assets/`; perbarui parameter `?v=` di `site/index.html` jika diperlukan untuk cache.

Konten mengacu pada [catatan Flutter development](docs/flutter-development-reference.md). APK yang ditautkan masih v1.1.26 (26 Januari 2026); keterangan ketersediaan fitur dipertahankan. Saat APK baru tersedia, ubah URL/label unduhan dan keterangan versi pada beranda, Tentang, Panduan, dan modal beranda.

Template Excel Google Sheets memiliki lima sheet: `panduan`, `klaster`, `titik_ikat`, `plot`, dan `pohon`.

## Easter egg

Easter egg sengaja dipertahankan. Form password kini berjalan di JavaScript dan status permainan disimpan di `sessionStorage`, menggantikan PHP session. Ini bagian dari permainan, bukan autentikasi untuk data privat; kode, jawaban, dan asetnya memang merupakan bagian situs publik.

## Kode CodeIgniter dan repositori data

Folder `app/`, `public/`, `writable/`, file Composer, serta `.cpanel.yml` dipertahankan sebagai versi lama dan **tidak ikut artifact GitHub Pages**. Jangan memakai deployment cPanel untuk versi statis ini. Panduan sebelumnya disimpan di [arsip README CodeIgniter](docs/archive/codeigniter-README.md).

Modul Repositori Data tetap diarsipkan; tidak ada halaman atau endpoint repositori dalam versi statis. Fitur Penyimpanan Awan Flutter memakai Firebase dan tidak memerlukan backend website ini.

## Lisensi

MIT — lihat [LICENSE](LICENSE). Dikembangkan oleh Asid30.
