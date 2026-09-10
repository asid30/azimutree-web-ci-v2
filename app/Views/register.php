<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Registrasi Repositori Data<?= $this->endSection() ?>

<?= $this->section('description') ?>Registrasi akun pengelola repositori data Azimutree.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="section">
        <div class="container auth-page">
            <div class="auth-card">
                <div class="auth-card-head">
                    <div>
                        <h2>Registrasi Akun</h2>
                        <p class="panel-note">Buat akun untuk upload dan mengelola arsip data klaster plot.</p>
                    </div>
                    <a class="btn ghost small" href="<?= base_url('repositori-data') ?>">Kembali</a>
                </div>

                <?php if (session('error')) : ?>
                    <div class="notice error"><?= esc(session('error')) ?></div>
                <?php endif; ?>

                <form class="repo-form" action="<?= base_url('repositori-data/register') ?>" method="post">
                    <?= csrf_field() ?>
                    <label for="register_email">Email</label>
                    <input id="register_email" name="email" type="email" maxlength="150" value="<?= esc(old('email')) ?>" autocomplete="email" required>

                    <label for="register_username">Username</label>
                    <input id="register_username" name="username" type="text" maxlength="100" value="<?= esc(old('username')) ?>" autocomplete="username" required>

                    <label for="register_password">Password</label>
                    <div class="password-field">
                        <input id="register_password" name="password" type="password" minlength="8" maxlength="255" autocomplete="new-password" required>
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
                    </div>

                    <label for="register_password_confirm">Konfirmasi Password</label>
                    <div class="password-field">
                        <input id="register_password_confirm" name="password_confirm" type="password" minlength="8" maxlength="255" autocomplete="new-password" required>
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
                    </div>

                    <button class="btn primary" type="submit">Daftar</button>
                </form>

                <p class="auth-switch">Sudah punya akun? <a href="<?= base_url('repositori-data') ?>">Login dari halaman repositori</a>.</p>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
