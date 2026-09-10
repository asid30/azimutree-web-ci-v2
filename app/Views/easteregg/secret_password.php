<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Masuk Halaman Rahasia
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div style="max-width:520px;margin:6rem auto;padding:2rem;background:var(--card);border-radius:12px;color:var(--text);text-align:center">
        <h2>Masukkan password untuk melanjutkan</h2>
        <?php if (! empty($error)) : ?>
            <div style="color:#ff6666;margin:0.75rem 0"><?= esc($error) ?></div>
        <?php endif ?>

        <form method="post" action="<?= site_url('easteregg/secret') ?>" style="margin-top:1rem;display:flex;gap:.5rem;justify-content:center;align-items:center">
            <?= csrf_field() ?>
            <div class="password-field">
                <input name="password" type="password" placeholder="password">
                <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
            </div>
            <button class="btn" type="submit">Masuk</button>
        </form>
    </div>
<?= $this->endSection() ?>
