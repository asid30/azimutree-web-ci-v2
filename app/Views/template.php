<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Template Excel<?= $this->endSection() ?>

<?= $this->section('description') ?>Template Excel Azimutree untuk impor beberapa klaster, Titik Ikat, plot, dan pohon, lengkap dengan petunjuk pengisian.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php
    // Repository context archived; keep template accessible from the landing page.
    $isRepositoryContext = false && service('request')->getGet('nav') === 'repo';
    $backUrl = $isRepositoryContext ? base_url('repositori-data') : base_url();
    ?>

    <section class="section">
        <div class="container">
            <h1>Template Excel untuk Azimutree</h1>
            <p>Gunakan template ini untuk menyiapkan satu atau beberapa klaster beserta Titik Ikat, plot, dan pohon, lalu impor ke aplikasi Android Azimutree.</p>
            <p class="release-note">Template ini menggunakan format lima sheet untuk aplikasi yang mendukung Titik Ikat dan impor beberapa klaster. Formatnya berbeda dari alur aplikasi lama; cocokkan dengan versi aplikasi Anda sebelum mengimpor.</p>
            <h2>Isi Template</h2>
            <ul>
                <li><strong>panduan:</strong> aturan pengisian, satuan, dan format tanggal.</li>
                <li><strong>klaster:</strong> kode klaster, nama pengukur, dan tanggal pengukuran.</li>
                <li><strong>titik_ikat:</strong> koordinat referensi setiap klaster serta informasi tambahan.</li>
                <li><strong>plot:</strong> kode plot dan koordinatnya, dihubungkan melalui kode klaster.</li>
                <li><strong>pohon:</strong> identitas pohon, azimut, dan jarak terhadap plot terkait.</li>
            </ul>
            <h2>Cara Mengisi</h2>
            <ol>
                <li>Unduh file XLSX dan baca sheet panduan terlebih dahulu.</li>
                <li>Isi seluruh kolom bertanda *. Pertahankan nama sheet dan judul kolom.</li>
                <li>Gunakan kode klaster yang sama pada semua sheet terkait, satu Titik Ikat per klaster, dan maksimal empat plot.</li>
                <li>Gunakan tanggal YYYY-MM-DD, lintang/bujur desimal, azimut dalam derajat, serta jarak dan ketinggian dalam meter.</li>
                <li>Periksa hubungan klaster, plot, dan pohon sebelum memilih Impor Data di aplikasi.</li>
            </ol>
            <p>Nama file boleh diubah. File hasil ekspor aplikasi dengan format yang sama juga dapat digunakan sebagai dasar pengisian. Untuk gambar, ikuti petunjuk URL pada template dan pastikan link dapat diakses.</p>
            <p><a href="<?= base_url('panduan') ?>#excel">Baca panduan impor dan ekspor →</a></p>
            <div class="template-actions" style="margin-top:1rem">
                <a class="btn primary" href="https://docs.google.com/spreadsheets/d/1EN-vjd3Tn1Q1wAyW599V07c_YIaMHK4fgSvLvuOS3pI/export?format=xlsx" target="_blank" rel="noopener">Klik untuk mendownload template (XLSX)</a>
                <a class="btn ghost" href="<?= esc($backUrl) ?>">Kembali</a>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
