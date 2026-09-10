<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Panduan Repositori Data<?= $this->endSection() ?>

<?= $this->section('description') ?>Panduan singkat penggunaan Repositori Data Lokasi Klaster Plot Azimutree.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="section">
        <div class="container">
            <h2>Panduan Repositori Data 📚</h2>
            <p>Repositori Data digunakan untuk membagikan arsip Excel lokasi klaster plot secara terstruktur. Satu arsip dapat berisi beberapa file klaster, sehingga data lebih mudah dicari, dikelola, dan diunduh oleh pengguna lain.</p>

            <h3>1. Melihat dan Mengunduh Arsip 🔎</h3>
            <ol>
                <li>Buka halaman <strong>Repositori Data</strong>.</li>
                <li>Pada tab <strong>Data Publik</strong>, pilih arsip yang ingin dilihat.</li>
                <li>Tekan <strong>Lihat</strong> untuk membuka daftar file dalam arsip.</li>
                <li>Tekan <strong>Download</strong>, baca konfirmasi, lalu pilih <strong>Konfirmasi</strong> untuk mengunduh file.</li>
            </ol>

            <h3>2. Membuat Arsip 🗂️</h3>
            <ol>
                <li>Login menggunakan akun repositori.</li>
                <li>Buka tab <strong>Kelola Arsip</strong>.</li>
                <li>Tekan <strong>Tambah Arsip</strong>, lalu isi nama arsip dengan jelas dan unik.</li>
                <li>Simpan arsip. Setelah arsip dibuat, Anda dapat menambahkan file Excel ke dalamnya.</li>
            </ol>

            <h3>3. Menambahkan File ke Arsip 📤</h3>
            <ol>
                <li>Pada tab <strong>Kelola Arsip</strong>, pilih arsip milik Anda.</li>
                <li>Tekan <strong>Tambah File</strong>.</li>
                <li>Isi kode klaster, tanggal pengambilan data, dan unggah file Excel sesuai format template.</li>
                <li>Pastikan tanggal tidak berada di masa depan dan file yang diunggah adalah file Excel yang benar.</li>
            </ol>

            <h3>4. Mengelola File Milik Sendiri 🛠️</h3>
            <p>Pengguna hanya dapat menghapus file yang berada dalam arsip miliknya. Sebelum menghapus, sistem akan menampilkan konfirmasi berisi informasi klaster, arsip, tanggal, dan pemilik. Tindakan hapus bersifat permanen dan tidak dapat dibatalkan.</p>

            <h3>5. Profile dan Kontak Publik 👤</h3>
            <p>Nama pemilik pada tabel dapat diklik untuk melihat ringkasan profile. Pengguna juga dapat membuka halaman profile publik yang menampilkan nama, instansi, kontak publik jika tersedia, dan jumlah arsip yang dimiliki.</p>

            <h3>6. Rekomendasi Penggunaan ✅</h3>
            <ul>
                <li>Gunakan nama arsip yang spesifik, misalnya nama kawasan atau lokasi pengukuran.</li>
                <li>Gunakan template resmi agar file mudah dibaca dan digunakan kembali.</li>
                <li>Periksa kembali kode klaster dan tanggal sebelum mengunggah file.</li>
                <li>Lengkapi profile dan kontak publik jika Anda bersedia dihubungi oleh pengguna lain.</li>
            </ul>

            <div class="template-actions" style="margin-top:1rem">
                <a class="btn primary" href="<?= base_url('repositori-data') ?>">Buka Repositori Data</a>
                <a class="btn ghost" href="<?= base_url('template') ?>?nav=repo">Unduh Template</a>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
