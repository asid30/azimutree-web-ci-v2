<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Tambah File Arsip<?= $this->endSection() ?>

<?= $this->section('description') ?>Tambah file Excel ke arsip <?= esc($archiveGroup['name']) ?>.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="section">
        <div class="container auth-page">
            <div class="auth-card">
                <div class="auth-card-head">
                    <div>
                        <h2>Tambah File</h2>
                        <p class="panel-note">Arsip: <strong><?= esc($archiveGroup['name']) ?></strong></p>
                    </div>
                    <a class="btn ghost small" href="<?= base_url('repositori-data') ?>">Kembali</a>
                </div>

                <?php if (session('error')) : ?>
                    <div class="notice error"><?= esc(session('error')) ?></div>
                <?php endif; ?>

                <form class="repo-form" action="<?= base_url('repositori-data/arsip/' . $archiveGroup['id'] . '/upload') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <label for="cluster_code">Kode Klaster</label>
                    <input id="cluster_code" name="cluster_code" type="number" min="1" step="1" required>
                    <p class="field-note">Isi angka saja. Contoh 1 akan tampil sebagai CL1.</p>

                    <label for="taken_date">Tanggal Diambil</label>
                    <div class="date-field">
                        <input id="taken_date" name="taken_date" type="date" max="<?= esc($today) ?>" required>
                        <button class="btn ghost small date-picker-button" type="button" data-date-picker-trigger="taken_date">Pilih</button>
                    </div>

                    <label for="archive_file">File Excel (.xls/.xlsx)</label>
                    <input id="archive_file" name="archive_file" type="file" accept=".xls,.xlsx" required>
                    <a class="form-helper-link" href="<?= base_url('template') ?>" target="_blank" rel="noopener">Unduh template</a>

                    <button class="btn primary" type="submit">Upload File</button>
                </form>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
