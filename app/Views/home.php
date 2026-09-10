<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Azimutree — Aplikasi Pemetaan Klaster Plot Kesehatan Hutan (FHM)
<?= $this->endSection() ?>



<?= $this->section('description') ?>
Azimutree adalah aplikasi Android open source untuk pemantauan kesehatan hutan, Titik Ikat, survei lokasi, pemetaan klaster, dan berbagi data penelitian dengan metode Forest Health Monitoring (FHM).
<?= $this->endSection() ?>


<?= $this->section('content') ?>
    <section class="hero">
        <div class="container">
            <h1>Azimutree</h1>
            <p class="lead">Temukan kembali lokasi penelitian hutan — kelola Titik Ikat, klaster, plot, dan pohon, lalu gunakan kompas dan radar untuk survei lapangan.</p>
            <p style="margin-top:.5rem;font-weight:700;color:#0b6efd">Untuk Android · Data lokal di perangkat · Berbagi melalui Penyimpanan Awan</p>
            <p class="cta">
                <a id="download" class="btn primary" href="https://github.com/asid30/azimutree-flutter/releases/download/v1.1.26/app-release-azimutree-v1.1.26.apk" target="_blank" rel="noopener noreferrer">Unduh APK v1.1.26</a>
                <a class="btn ghost" href="https://github.com/asid30/azimutree-flutter" target="_blank" rel="noopener noreferrer">Kode Sumber</a>
            </p>

            <p class="release-note">Unduhan yang tersedia: rilis v1.1.26 (26 Januari 2026). Fitur Titik Ikat, Survey Lokasi, dan Penyimpanan Awan yang dijelaskan di website ini berasal dari pengembangan setelah rilis tersebut. Periksa <a href="https://github.com/asid30/azimutree-flutter/releases" target="_blank" rel="noopener noreferrer">rilis aplikasi</a> untuk pembaruan APK.</p>
            <div class="screenshot-slider">
                <div class="slider-container">
                    <div class="screenshots">
                        <img src="<?= base_url('assets/1.webp') ?>?v=<?= filemtime(FCPATH . 'assets/1.webp') ?>" alt="Tampilan aplikasi Azimutree 1" width="1080" height="2436" onerror="this.style.display='none'">
                        <img src="<?= base_url('assets/2.webp') ?>?v=<?= filemtime(FCPATH . 'assets/2.webp') ?>" alt="Tampilan aplikasi Azimutree 2" width="1080" height="2436" loading="lazy" onerror="this.style.display='none'">
                        <img src="<?= base_url('assets/3.webp') ?>?v=<?= filemtime(FCPATH . 'assets/3.webp') ?>" alt="Tampilan aplikasi Azimutree 3" width="1080" height="2436" loading="lazy" onerror="this.style.display='none'">
                        <img src="<?= base_url('assets/4.webp') ?>?v=<?= filemtime(FCPATH . 'assets/4.webp') ?>" alt="Tampilan aplikasi Azimutree 4" width="1080" height="2436" loading="lazy" onerror="this.style.display='none'">
                        <img src="<?= base_url('assets/5.webp') ?>?v=<?= filemtime(FCPATH . 'assets/5.webp') ?>" alt="Tampilan aplikasi Azimutree 5" width="1080" height="2436" loading="lazy" onerror="this.style.display='none'">
                        <img src="<?= base_url('assets/6.webp') ?>?v=<?= filemtime(FCPATH . 'assets/6.webp') ?>" alt="Tampilan aplikasi Azimutree 6" width="1080" height="2436" loading="lazy" onerror="this.style.display='none'">
                    </div>
                </div>
                <div class="slider-dots">
                    <span class="dot active" data-slide="0"></span>
                    <span class="dot" data-slide="1"></span>
                    <span class="dot" data-slide="2"></span>
                    <span class="dot" data-slide="3"></span>
                    <span class="dot" data-slide="4"></span>
                    <span class="dot" data-slide="5"></span>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="section">
        <div class="container">
            <h2>Pemetaan dan Navigasi untuk Penelitian Hutan</h2>
            <p><strong>Azimutree</strong> membantu kegiatan Forest Health Monitoring (FHM) melalui pencatatan dan pencarian kembali lokasi penelitian. Data utama disimpan secara lokal di perangkat; Anda memilih sendiri kapan mengekspor atau mengunggahnya.</p>
            <p>Aplikasi berfokus pada lokasi dan navigasi lapangan, bukan pencatatan lengkap nilai kesehatan pohon atau hutan.</p>
            <div class="features">
                <div class="feature-card"><h3>Titik Ikat dan Data Klaster</h3><p>Simpan satu Titik Ikat sebagai referensi awal setiap klaster, hingga empat plot, dan data pohon di masing-masing plot. Tentukan posisi lewat koordinat atau azimut dan jarak.</p></div>
                <div class="feature-card"><h3>Survey Lokasi</h3><p>Dekati Titik Ikat dengan GPS dan peta kecil, lalu ikuti kompas dan jarak referensi menuju plot. Sesi survei dapat dilanjutkan setelah kembali ke Beranda.</p></div>
                <div class="feature-card"><h3>Radar Pohon</h3><p>Lihat arah pohon dari pusat plot, saring daftar mengikuti arah kompas, dan pin pohon yang sedang dicari. Radar memakai pusat plot sebagai referensi.</p></div>
                <div class="feature-card"><h3>Peta Digital</h3><p>Tampilkan Titik Ikat, plot, pohon, area plot, dan garis relasi di peta Mapbox. Cari data lokal atau lokasi, lalu gunakan Tracking Data untuk memilih tujuan.</p></div>
                <div class="feature-card"><h3>Penyimpanan Awan</h3><p>Cari dan unduh data penelitian publik tanpa login. Login Google untuk mengunggah klaster serta mengelola folder penelitian publik atau privat. Unggah dan unduh dilakukan manual.</p></div>
                <div class="feature-card"><h3>Impor dan Ekspor Excel</h3><p>Pindahkan satu atau beberapa klaster beserta Titik Ikat, plot, dan pohon melalui format Excel terstruktur untuk berbagi data atau membuat cadangan.</p></div>
            </div>
            <p><a href="<?= base_url('about') ?>" class="app-link">Tentang Azimutree →</a> <a href="<?= base_url('panduan') ?>" class="app-link">Pelajari panduan penggunaan →</a></p>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <h2>Dari Data ke Lokasi Lapangan</h2>
            <ol>
                <li><strong>Siapkan data.</strong> Buat klaster dan Titik Ikat, impor Excel, atau unduh data publik ke perangkat.</li>
                <li><strong>Temukan lokasi.</strong> Gunakan Survey Lokasi untuk mendekati Titik Ikat dan menuju plot berdasarkan referensi lapangan.</li>
                <li><strong>Simpan dan bagikan.</strong> Ekspor beberapa klaster atau unggah secara manual ke Penyimpanan Awan.</li>
            </ol>
            <p>Pengelolaan data lokal tidak memerlukan akun. Pemuatan peta daring, login Google, dan layanan awan membutuhkan internet. Ketepatan navigasi bergantung pada data, GPS, kompas, dan kondisi lapangan.</p>
        </div>
    </section>
<?= $this->endSection() ?>
