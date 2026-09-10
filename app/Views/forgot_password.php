<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Lupa Password<?= $this->endSection() ?>

<?= $this->section('description') ?>Reset password akun repositori data Azimutree.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="section">
        <div class="container auth-page">
            <div class="auth-card">
                <div class="auth-card-head">
                    <div>
                        <h2>Lupa Password</h2>
                        <p class="panel-note">Masukkan email atau username. Kode OTP akan dikirim ke email akun yang sudah terverifikasi.</p>
                    </div>
                    <a class="btn ghost small" href="<?= base_url('repositori-data') ?>">Kembali</a>
                </div>

                <?php if (session('error')) : ?>
                    <div class="notice error"><?= esc(session('error')) ?></div>
                <?php endif; ?>

                <?php if (session('success')) : ?>
                    <div class="notice success"><?= esc(session('success')) ?></div>
                <?php endif; ?>

                <form class="repo-form" action="<?= base_url('repositori-data/lupa-password') ?>" method="post">
                    <?= csrf_field() ?>
                    <label for="identity">Email atau Username</label>
                    <input id="identity" name="identity" type="text" autocomplete="username" required>

                    <button class="btn primary" type="submit">Kirim OTP Reset</button>
                </form>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
