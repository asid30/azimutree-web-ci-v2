<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Panduan Azimutree — Data Lokal, Survey Lokasi, dan Penyimpanan Awan<?= $this->endSection() ?>

<?= $this->section('description') ?>Panduan Android Azimutree: kelola Titik Ikat, klaster, plot, dan pohon; impor ekspor Excel; survei dengan kompas dan radar; unggah unduh data awan.<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section"><div class="container">
    <h1>Panduan Penggunaan Azimutree</h1>
    <p>Panduan ini mencakup fitur pengembangan terbaru aplikasi Android Azimutree. Data utama tersimpan di perangkat, kecuali data yang sengaja Anda unggah ke Penyimpanan Awan.</p>
    <p class="release-note">APK publik v1.1.26 (26 Januari 2026) mendahului fitur Titik Ikat, Survey Lokasi, dan Penyimpanan Awan. Jika menu pada aplikasi Anda berbeda, periksa <a href="https://github.com/asid30/azimutree-flutter/releases" target="_blank" rel="noopener noreferrer">rilis APK</a> dan gunakan panduan yang sesuai dengan versi aplikasi.</p>
    <nav aria-label="Daftar isi panduan"><p><a href="#beranda">Beranda</a> · <a href="#data-lokal">Data lokal</a> · <a href="#excel">Excel</a> · <a href="#peta">Peta</a> · <a href="#survey">Survey Lokasi</a> · <a href="#awan">Penyimpanan Awan</a> · <a href="#pengaturan">Pengaturan</a> · <a href="#bantuan">Bantuan</a></p></nav>
    <h2 id="beranda">1. Beranda dan Navigasi</h2>
    <p>Empat menu utama adalah <strong>Kelola Data Klaster Plot</strong>, <strong>Peta Lokasi Klaster Plot</strong>, <strong>Survey Lokasi</strong>, dan <strong>Panduan Aplikasi</strong>.</p>
    <ul><li>Sidebar menyediakan perpindahan halaman, termasuk submenu Data Klaster dan Penyimpanan Awan.</li><li>Versi Aplikasi menampilkan catatan perubahan yang dipublikasikan melalui layanan awan.</li><li>Ganti tema terang/gelap melalui ikon tema atau Pengaturan.</li></ul>
    <h2 id="data-lokal">2. Kelola Data Lokal</h2>
    <h3>Tambah Klaster dan Titik Ikat</h3>
    <ol><li>Buka Kelola Data dan pilih tambah klaster.</li><li>Isi kode klaster tanpa spasi, misalnya CL1. Huruf akan dinormalisasi menjadi kapital.</li><li>Isi nama pengukur dan tanggal pengukuran.</li><li>Isi lintang dan bujur Titik Ikat, atau gunakan <strong>Pilih dari Peta</strong>. Nama Titik Ikat mengikuti kode klaster.</li><li>Lengkapi ketinggian, keterangan, dan link gambar bila diperlukan, lalu simpan.</li></ol>
    <p>Setiap klaster memiliki satu Titik Ikat dan maksimal empat plot. Gunakan objek yang dapat dikenali kembali secara fisik sebagai referensi lapangan.</p>
    <h3>Tambah Plot</h3>
    <ol><li>Pilih klaster dan nomor plot yang tersedia.</li><li>Pilih metode <strong>Azimut &amp; Jarak</strong> atau <strong>Lintang &amp; Bujur</strong>.</li><li>Untuk metode azimut/jarak, pilih referensi: Titik Ikat untuk plot pertama, atau plot tersimpan yang tersedia.</li><li>Masukkan posisi dan ketinggian jika diperlukan, lalu simpan.</li></ol>
    <p>Jika pusat plot diedit, lokasi absolut pohon dipertahankan. Aplikasi menghitung ulang azimut dan jarak pohon terhadap pusat plot yang baru.</p>
    <h3>Tambah Pohon</h3>
    <ol><li>Pilih klaster dan plot tempat pohon berada.</li><li>Tentukan posisi melalui azimut/jarak relatif ke plot tersebut atau lintang/bujur.</li><li>Masukkan kode pohon berupa angka dan lengkapi informasi nama pohon, nama ilmiah, ketinggian, keterangan, serta link gambar sesuai kebutuhan form.</li><li>Pada pemilih koordinat, geser peta hingga pin tepat, lalu tekan <strong>Gunakan Lokasi Ini</strong>.</li><li>Simpan dan periksa kembali posisi pada peta.</li></ol>
    <h3>Edit, Hapus, dan Tracking Data</h3>
    <p>Pilih klaster pada dropdown untuk melihat data terkait. Gunakan tombol edit, hapus, atau <strong>Tracking Data</strong> pada kartu data. Tracking Data membuka peta dengan marker tujuan terpilih. Menghapus klaster turut menghapus Titik Ikat, plot, dan pohon di dalamnya; ekspor cadangan terlebih dahulu jika masih dibutuhkan.</p>
    <h2 id="excel">3. Impor dan Ekspor Excel</h2>
    <p>Format Excel mendukung beberapa klaster dan berisi sheet <strong>panduan</strong>, <strong>klaster</strong>, <strong>titik_ikat</strong>, <strong>plot</strong>, dan <strong>pohon</strong>.</p>
    <h3>Impor</h3>
    <ol><li>Unduh <a href="<?= base_url('template') ?>">template Excel</a> atau gunakan file ekspor aplikasi dengan format yang sesuai.</li><li>Baca sheet panduan dan isi semua kolom bertanda *. Pertahankan nama sheet dan kolom.</li><li>Gunakan kode klaster yang konsisten pada setiap sheet, kode plot yang sesuai, dan kode pohon berupa angka.</li><li>Isi tanggal pengukuran dengan format YYYY-MM-DD, misalnya 2026-09-05. Lintang/bujur memakai desimal, azimut memakai derajat, dan jarak/ketinggian memakai meter.</li><li>Pilih Impor Data di aplikasi, pilih file, lalu ikuti hasil validasi. Periksa data setelah impor.</li></ol>
    <h3>Ekspor</h3>
    <ol><li>Pilih Ekspor Data, lalu pilih satu atau beberapa klaster.</li><li>Pilih folder tujuan dan simpan file Excel.</li><li>Bagikan berkas atau simpan sebagai cadangan. Nama file boleh diubah; nama sheet harus dipertahankan agar dapat diimpor kembali.</li></ol>
    <h2 id="peta">4. Peta Lokasi</h2>
    <ul><li><strong>Titik Ikat:</strong> pin merah. <strong>Plot:</strong> biru. <strong>Centroid:</strong> ungu. <strong>Pohon:</strong> ikon pohon.</li><li>Pohon yang selesai pada Workflow Inspeksi berubah menjadi hijau.</li><li>Area biru muda merupakan visualisasi area plot berdasarkan pohon terjauh ditambah margin.</li><li>Tekan dan tahan marker untuk memilih; lihat detail pada kartu informasi atau bottom sheet. Gunakan Sebelumnya/Berikutnya untuk berpindah marker.</li><li>Pencarian mencakup lokasi Mapbox serta klaster, Titik Ikat, plot, dan pohon lokal. Hasil lokal tetap tersedia jika pencarian daring bermasalah.</li></ul>
    <p>Kontrol peta menyediakan pilihan tipe peta, lokasi pengguna, dan arah utara. Map Tools mengatur legenda, info marker, pemilihan marker, Workflow Inspeksi, garis Pohon → Plot dan Plot → Plot, serta ukuran marker. Pengaturannya disimpan untuk penggunaan berikutnya.</p>
    <h2 id="survey">5. Survey Lokasi</h2>
    <h3>Persiapan dan Alur Survei</h3>
    <ol><li>Pastikan klaster, Titik Ikat, dan plot sudah tersedia. Aktifkan GPS serta izin lokasi, lalu pilih klaster pada Survey Lokasi.</li><li>Gunakan refresh akses GPS jika GPS baru diaktifkan. Periksa akurasi sebelum mulai.</li><li>Dekati Titik Ikat menggunakan estimasi jarak GPS dan peta kecil. Temukan objek secara fisik, kemudian konfirmasi.</li><li>Dari Titik Ikat, ikuti azimut dan ukur jarak referensi menuju pusat Plot 1. Konfirmasikan setelah tiba di lokasi yang benar.</li><li>Pilih plot tujuan lain, kembali ke Titik Ikat, atau cari pohon pada plot aktif.</li></ol>
    <p><strong>GPS membantu mendekati lokasi.</strong> Jarak pada tahap navigasi plot adalah referensi pengukuran lapangan, bukan sisa jarak GPS. Konfirmasi posisi tidak mengganti koordinat tersimpan dengan pembacaan GPS.</p>
    <h3>Kompas, Radar, dan Pin Pohon</h3>
    <ul><li>Berdirilah dekat pusat plot saat menggunakan radar. <strong>Pusat radar adalah pusat plot, bukan posisi ponsel.</strong></li><li>Huruf N menunjukkan utara. Saat kompas aktif, sektor mengikuti arah ponsel dan daftar pohon disaring berdasarkan arah.</li><li>Saat kompas mati, radar menampilkan pohon dengan orientasi utara tanpa animasi sektor.</li><li>Tekan dan tahan pohon atau pusat plot pada radar untuk membuka info; tekan area kosong untuk menutup.</li><li>Pin pohon pada daftar untuk mempertahankan fokus pencarian, lalu lepas pin ketika selesai.</li><li>Gunakan petunjuk kalibrasi kompas jika arah tidak stabil; hindari magnet atau logam di dekat perangkat.</li></ul>
    <h3>Melanjutkan dan Mengakhiri Sesi</h3>
    <p>Sesi survei disimpan dan dapat dilanjutkan setelah kembali ke Beranda. Membatalkan navigasi atau kembali ke Titik Ikat tidak otomatis mengakhiri sesi. Pilih <strong>Akhiri Sesi Survey</strong> untuk menutupnya sepenuhnya.</p>
    <h2 id="awan">6. Penyimpanan Awan</h2>
    <p>Penyimpanan Awan tersedia di aplikasi Android. <strong>Unggah dan unduh dilakukan manual;</strong> perubahan awan tidak otomatis memperbarui salinan lokal.</p>
    <h3>Jelajahi Data Publik Tanpa Login</h3>
    <ol><li>Buka Penyimpanan Awan dan bagian Data Penelitian Publik.</li><li>Cari nama folder lokasi penelitian. Filter Sembunyikan Folder Kosong aktif secara default dan pilihannya disimpan.</li><li>Buka folder, pilih klaster, lalu unduh ke perangkat.</li><li>Jika kode sudah digunakan di lokal, masukkan kode salinan baru. Data unduhan dapat dikelola melalui Data Klaster.</li></ol>
    <h3>Unggah dan Kelola Data Sendiri</h3>
    <ol><li>Login menggunakan Google.</li><li>Buat folder lokasi penelitian dengan nama unik dan tanggal penelitian.</li><li>Periksa visibilitas folder: <strong>publik secara default</strong>, atau pilih privat untuk membatasi akses.</li><li>Pilih satu atau beberapa klaster lokal untuk diunggah ke folder tersebut.</li><li>Unduh atau hapus klaster milik sendiri sesuai kebutuhan. Folder harus kosong sebelum dihapus melalui pengelolaan folder.</li></ol>
    <p>Gambar disimpan sebagai link. Pastikan penerima memiliki akses ke gambar yang dibagikan. Buat ekspor cadangan jika Anda membutuhkan salinan di luar perangkat dan layanan awan.</p>
    <h3>Profil dan Penghapusan Akun</h3>
    <p>Buka profil awan untuk mengubah nama tampilan. Penghapusan akun meminta konfirmasi dan autentikasi ulang menggunakan akun Google yang sama. Proses ini menghapus profil serta semua data penelitian awan milik akun, termasuk yang publik dan privat. <strong>Data lokal perangkat dan akun Google tetap ada.</strong></p>
    <h2 id="pengaturan">7. Pengaturan dan Versi Aplikasi</h2>
    <p>Pengaturan menyediakan tema terang/gelap dan Mode Debug untuk data contoh atau pengujian. Gunakan penghapusan data uji dengan hati-hati. Halaman Versi Aplikasi memerlukan internet untuk mengambil catatan perubahan yang dipublikasikan.</p>
    <h2 id="bantuan">8. Tips dan Masalah Umum</h2>
    <ul><li><strong>GPS tidak aktif atau kurang akurat:</strong> aktifkan GPS dan izin lokasi, refresh akses GPS, lalu cari area dengan penerimaan lebih baik. Gunakan objek Titik Ikat dan pengukuran lapangan sebagai referensi.</li><li><strong>Arah kompas tidak stabil:</strong> ikuti dialog kalibrasi dan jauhkan perangkat dari gangguan magnetik.</li><li><strong>Impor gagal:</strong> periksa format sheet, kolom wajib, tanggal, kode duplikat, dan hubungan klaster–plot–pohon.</li><li><strong>Data publik tidak terlihat:</strong> periksa internet, kata pencarian, filter folder kosong, dan apakah pemilik menjadikan folder publik.</li><li><strong>Data awan berbeda dari lokal:</strong> unggah atau unduh kembali secara manual. Gunakan kode salinan baru bila ada benturan kode lokal.</li><li><strong>Gambar tidak terbuka:</strong> periksa URL dan izin berbagi gambar.</li><li><strong>Menu berbeda dari panduan:</strong> periksa versi aplikasi dan ketersediaan APK yang mendukung fitur tersebut.</li></ul>
    <p><a class="app-link" href="<?= base_url('template') ?>">Unduh template Excel →</a> <a class="app-link" href="https://forms.gle/7KE2jecH4CiNQxTg9" target="_blank" rel="noopener noreferrer">Kirim saran →</a></p>
</div></section>
<?= $this->endSection() ?>
