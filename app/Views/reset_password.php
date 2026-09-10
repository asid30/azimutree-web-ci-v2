<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Reset Password<?= $this->endSection() ?>

<?= $this->section('description') ?>Masukkan OTP dan password baru akun repositori data Azimutree.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="section">
        <div class="container auth-page">
            <div class="auth-card">
                <div class="auth-card-head">
                    <div>
                        <h2>Reset Password</h2>
                        <p class="panel-note">Kode OTP dikirim ke email akun <strong><?= esc($user['email']) ?></strong>.</p>
                    </div>
                    <a class="btn ghost small" href="<?= base_url('repositori-data') ?>">Kembali</a>
                </div>

                <?php if (session('error')) : ?>
                    <div class="notice error"><?= esc(session('error')) ?></div>
                <?php endif; ?>

                <?php if (session('success')) : ?>
                    <div class="notice success"><?= esc(session('success')) ?></div>
                <?php endif; ?>

                <form class="repo-form" action="<?= base_url('repositori-data/reset-password') ?>" method="post">
                    <?= csrf_field() ?>
                    <label for="reset_otp">Kode OTP</label>
                    <input id="reset_otp" class="otp-input" name="otp" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="000000" autocomplete="one-time-code" required>

                    <label for="reset_password">Password Baru</label>
                    <div class="password-field">
                        <input id="reset_password" name="password" type="password" minlength="8" maxlength="255" autocomplete="new-password" required>
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
                    </div>

                    <label for="reset_password_confirm">Konfirmasi Password Baru</label>
                    <div class="password-field">
                        <input id="reset_password_confirm" name="password_confirm" type="password" minlength="8" maxlength="255" autocomplete="new-password" required>
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
                    </div>

                    <button class="btn primary" type="submit">Simpan Password Baru</button>
                </form>

                <div class="auth-inline-actions">
                    <form action="<?= base_url('repositori-data/lupa-password') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="identity" value="<?= esc($user['email']) ?>">
                        <button class="btn ghost small" type="submit">Kirim Ulang OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
