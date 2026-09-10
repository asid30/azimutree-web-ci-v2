<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Tentang Azimutree — Titik Ikat, Survey Lokasi, dan Penyimpanan Awan<?= $this->endSection() ?>

<?= $this->section('description') ?>Kenali Azimutree untuk pemetaan dan navigasi lokasi FHM: Titik Ikat, klaster, plot, pohon, survei lapangan, serta berbagi data penelitian.<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section"><div class="container">
    <h1>Tentang Azimutree</h1>
    <p><strong>Azimutree</strong> adalah aplikasi Android untuk membantu penelitian kesehatan hutan dengan metode <strong>Forest Health Monitoring (FHM)</strong>. Aplikasi mencatat lokasi Titik Ikat, klaster, plot, dan pohon, lalu menampilkannya pada peta digital. Kompas dan radar membantu pengguna menemukan kembali lokasi penelitian di lapangan.</p>
    <h2>Latar Belakang dan Tujuan</h2>
    <p>Pengamatan berulang sering melibatkan perubahan vegetasi, kondisi lingkungan, dan pergantian peneliti. Koordinat yang hanya tersimpan dalam tabel dapat menyulitkan pencarian lokasi saat kembali ke lapangan. Azimutree menghubungkan data tersebut dengan peta dan panduan arah untuk menjaga konsistensi lokasi pengamatan.</p>
    <p>Fokus aplikasi adalah pencatatan, visualisasi, dan navigasi lokasi. Pencatatan lengkap nilai kesehatan pohon atau hutan berada di luar cakupan fitur yang dijelaskan di sini.</p>
    <h2>Konsep Titik Ikat dan Klaster Plot</h2>
    <ul>
        <li>Setiap klaster memiliki <strong>satu Titik Ikat</strong> sebagai referensi awal untuk menemukan lokasi penelitian.</li>
        <li>Satu klaster memiliki maksimal <strong>empat plot</strong>. Plot 1 menjadi pusat klaster; plot lain berada di sekitarnya.</li>
        <li>Setiap plot dapat memiliki beberapa pohon, dengan posisi yang mengacu pada plot tersebut.</li>
        <li>Plot pertama dibuat dengan referensi Titik Ikat. Plot yang sudah tersimpan dapat dipilih sebagai referensi untuk plot berikutnya.</li>
        <li>Posisi dapat dimasukkan melalui lintang/bujur atau dihitung dari azimut dan jarak. Pemilih koordinat di peta membantu menentukan posisi secara visual.</li>
    </ul>
    <figure class="cluster-figure"><img loading="lazy" class="cluster-plot" src="<?= base_url('assets/dark-cl-plot.webp') ?>" alt="Ilustrasi susunan klaster dan plot"><figcaption>Ilustrasi susunan plot. Titik Ikat digunakan sebagai referensi awal di lapangan.</figcaption></figure>
    <h2>Survey Lokasi</h2>
    <p>Survei dimulai dengan mendekati Titik Ikat menggunakan GPS dan peta kecil. Setelah objek ditemukan secara fisik, pengguna menuju Plot 1 berdasarkan azimut dan jarak referensi, kemudian memilih plot lain atau mencari pohon dengan radar.</p>
    <p><strong>Pusat radar adalah pusat plot, bukan posisi ponsel.</strong> Jarak referensi di tahap navigasi plot merupakan jarak pengukuran lapangan. Konfirmasi kedatangan tidak mengganti koordinat tersimpan dengan koordinat GPS. Sesi dapat dilanjutkan setelah kembali ke Beranda.</p>
    <h2>Penyimpanan Awan dan Berbagi Data</h2>
    <p>Data penelitian publik dapat dicari dan diunduh tanpa login. Login Google diperlukan untuk mengunggah serta mengelola data sendiri. Klaster dikelompokkan dalam folder lokasi penelitian yang dapat diatur publik atau privat; folder baru secara default bersifat publik.</p>
    <p><strong>Unggah dan unduh dilakukan manual.</strong> Salinan lokal tidak berubah otomatis ketika data awan diperbarui. Excel tetap tersedia untuk impor, ekspor beberapa klaster, dan cadangan data.</p>
    <h2>Teknologi</h2>
    <ul>
        <li><strong>Flutter</strong> untuk antarmuka aplikasi Android.</li>
        <li><strong>SQLite</strong> untuk menyimpan data penelitian lokal.</li>
        <li><strong>Mapbox</strong> untuk peta dan pencarian lokasi daring.</li>
        <li><strong>Firebase Authentication</strong> untuk login Google opsional.</li>
        <li><strong>Cloud Firestore</strong> untuk data penelitian awan, profil, status layanan, dan catatan versi.</li>
    </ul>
    <h2>Data, Akun, dan Izin Perangkat</h2>
    <ul>
        <li>Data penelitian utama disimpan di perangkat. Data yang Anda pilih untuk diunggah akan dikirim ke Penyimpanan Awan.</li>
        <li>Folder publik memungkinkan pengguna lain mengakses dan mengunduh datanya. Periksa visibilitas folder sebelum berbagi.</li>
        <li>Gambar disimpan sebagai link. Pastikan gambar yang ingin dibagikan dapat diakses penerima.</li>
        <li>Profil awan mendukung perubahan nama tampilan. Penghapusan akun memerlukan autentikasi ulang Google dan menghapus profil serta data awan milik akun, tetapi mempertahankan data lokal. Akun Google Anda tidak dihapus.</li>
        <li>Izin lokasi diperlukan untuk posisi pengguna dan navigasi GPS. Pemilih file atau folder digunakan saat impor dan ekspor; permintaan akses mengikuti versi Android dan fitur yang digunakan.</li>
        <li>Internet diperlukan untuk peta daring, layanan awan, login, dan catatan versi. Penyimpanan lokal tidak berarti seluruh fitur peta tersedia offline.</li>
    </ul>
    <h2>Panduan dan Ketersediaan Fitur</h2>
    <p>Informasi ini mencakup pengembangan terbaru Azimutree, termasuk Titik Ikat, Survey Lokasi, dan Penyimpanan Awan. APK publik v1.1.26 yang saat ini ditautkan berasal dari 26 Januari 2026 dan mendahului penambahan fitur-fitur tersebut. Cocokkan panduan dengan versi aplikasi yang Anda gunakan.</p>
    <p><a class="app-link" href="<?= base_url('panduan') ?>">Buka panduan penggunaan →</a> <a class="app-link" href="https://github.com/asid30/azimutree-flutter/releases" target="_blank" rel="noopener noreferrer">Lihat rilis APK →</a></p>
    <h2>Open Source dan Dukungan</h2>
    <p>Azimutree dikembangkan oleh Asid30 dan tersedia dengan lisensi MIT. Kode serta petunjuk pengembangan dapat dipelajari di GitHub. Pengguna aplikasi tidak perlu mengatur API key sendiri.</p>
    <div class="support-actions"><a class="btn primary" href="https://saweria.co/asid30" target="_blank" rel="noopener noreferrer">Dukung lewat Saweria</a> <a class="btn ghost" href="https://github.com/asid30/azimutree-flutter" target="_blank" rel="noopener noreferrer">Kode Sumber</a></div>
</div></section>
<?= $this->endSection() ?>
