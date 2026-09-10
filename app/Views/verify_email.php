<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Verifikasi Email<?= $this->endSection() ?>

<?= $this->section('description') ?>Verifikasi email akun repositori data Azimutree.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="section">
        <div class="container auth-page">
            <div class="auth-card">
                <div class="auth-card-head">
                    <div>
                        <h2>Verifikasi Email</h2>
                        <p class="panel-note">Masukkan kode OTP 6 digit yang dikirim ke <strong><?= esc($user['email']) ?></strong>.</p>
                    </div>
                    <a class="btn ghost small" href="<?= base_url('repositori-data') ?>">Kembali</a>
                </div>

                <?php if (session('success')) : ?>
                    <div class="notice success"><?= esc(session('success')) ?></div>
                <?php endif; ?>

                <?php if (session('error')) : ?>
                    <div class="notice error"><?= esc(session('error')) ?></div>
                <?php endif; ?>

                <form class="repo-form" action="<?= base_url('repositori-data/verifikasi-email') ?>" method="post">
                    <?= csrf_field() ?>
                    <label for="otp">Kode OTP</label>
                    <input id="otp" class="otp-input" name="otp" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="000000" autocomplete="one-time-code" required>

                    <button class="btn primary" type="submit">Verifikasi Email</button>
                </form>

                <div class="auth-inline-actions">
                    <form action="<?= base_url('repositori-data/verifikasi-email/kirim-ulang') ?>" method="post">
                        <?= csrf_field() ?>
                        <button class="btn ghost small" type="submit">Kirim Ulang OTP</button>
                    </form>
                    <button class="btn ghost small" type="button" data-skip-verification-open>Verifikasi Nanti</button>
                </div>
            </div>
        </div>
    </section>

    <div id="skipVerificationModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="skipVerificationTitle">
        <div class="modal-backdrop"></div>
        <div class="modal-box" role="document">
            <h3 id="skipVerificationTitle">Lewati verifikasi email?</h3>
            <p class="muted modal-message">Kalau suatu saat Anda lupa password, email terverifikasi akan dibutuhkan untuk pemulihan akun.</p>
            <div class="modal-actions">
                <button id="skipVerificationCancel" class="btn ghost" type="button">Batal</button>
                <form action="<?= base_url('repositori-data/verifikasi-email/nanti') ?>" method="post">
                    <?= csrf_field() ?>
                    <button class="btn primary" type="submit">Konfirmasi</button>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
