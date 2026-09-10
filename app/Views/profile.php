<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Edit Profile<?= $this->endSection() ?>

<?= $this->section('description') ?>Edit profile pengguna repositori data Azimutree.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php $publicContacts = ($publicContacts ?? []) ?: ['']; ?>

    <section class="section">
        <div class="container">
            <div class="profile-panel">
                <div class="profile-panel-main">
                    <div class="profile-avatar"><?= esc(strtoupper(substr($user['username'], 0, 1))) ?></div>
                    <div>
                        <h2><?= esc($user['name'] ?: $user['username']) ?></h2>
                        <p class="panel-note">@<?= esc($user['username']) ?> · <?= esc($user['email']) ?></p>
                    </div>
                </div>
                <a class="btn ghost small profile-back-btn" href="<?= base_url('repositori-data') ?>">Kembali</a>
            </div>

            <?php if (session('success')) : ?>
                <div class="notice success"><?= esc(session('success')) ?></div>
            <?php endif; ?>

            <?php if (session('error')) : ?>
                <div class="notice error"><?= esc(session('error')) ?></div>
            <?php endif; ?>

            <?php if (! ($emailVerified ?? true)) : ?>
                <div class="notice error persistent-notice">
                    Email Anda belum diverifikasi. <a href="<?= base_url('repositori-data/verifikasi-email') ?>">Verifikasi di sini</a>.
                </div>
            <?php endif; ?>

            <div class="profile-grid">
                <form class="profile-card repo-form" action="<?= base_url('repositori-data/profile') ?>" method="post">
                    <?= csrf_field() ?>
                    <h3>Informasi Profile</h3>

                    <label for="name">Nama</label>
                    <input id="name" name="name" type="text" maxlength="150" value="<?= esc($user['name'] ?? '') ?>" required>

                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" maxlength="100" value="<?= esc($user['username']) ?>" required>

                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" maxlength="150" value="<?= esc($user['email']) ?>" required>
                    <?php if (! ($emailVerified ?? true)) : ?>
                        <p class="field-note danger-text">Email belum diverifikasi. <a href="<?= base_url('repositori-data/verifikasi-email') ?>">Silakan verifikasi di sini</a>.</p>
                    <?php endif; ?>

                    <label for="institution">Instansi</label>
                    <input id="institution" name="institution" type="text" maxlength="150" value="<?= esc($user['institution'] ?? '') ?>">

                    <div class="contact-field-head">
                        <label>Kontak Publik</label>
                        <span>Maksimal 3</span>
                    </div>
                    <p class="field-note">Opsional, akan ditampilkan agar pengguna lain bisa menghubungi Anda.</p>

                    <div class="public-contact-list" data-public-contact-list>
                        <?php foreach (array_slice($publicContacts, 0, 3) as $contactIndex => $contact) : ?>
                            <div class="public-contact-item" data-public-contact-item>
                                <input
                                    id="public_contact_<?= esc($contactIndex) ?>"
                                    name="public_contact[]"
                                    type="text"
                                    maxlength="150"
                                    value="<?= esc($contact) ?>"
                                    placeholder="Email, no telp, WhatsApp, atau kontak lain"
                                >
                                <button class="icon-action danger" type="button" data-public-contact-remove aria-label="Hapus kontak">x</button>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="btn ghost small contact-add-btn" type="button" data-public-contact-add>Tambah Kontak</button>

                    <button class="btn primary" type="submit">Simpan Profile</button>
                </form>

                <form class="profile-card repo-form" action="<?= base_url('repositori-data/profile/password') ?>" method="post">
                    <?= csrf_field() ?>
                    <h3>Ganti Password</h3>

                    <label for="current_password">Password Saat Ini</label>
                    <div class="password-field">
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
                    </div>

                    <label for="password">Password Baru</label>
                    <div class="password-field">
                        <input id="password" name="password" type="password" autocomplete="new-password" required>
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
                    </div>

                    <label for="password_confirm">Konfirmasi Password Baru</label>
                    <div class="password-field">
                        <input id="password_confirm" name="password_confirm" type="password" autocomplete="new-password" required>
                        <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
                    </div>

                    <button class="btn primary" type="submit">Simpan Password</button>
                </form>
            </div>

            <div class="profile-grid">
                <div class="profile-stat">
                    <span>Jumlah Arsip</span>
                    <strong><?= esc($archiveCount) ?></strong>
                </div>

                <div class="profile-stat">
                    <span>Jumlah File</span>
                    <strong><?= esc($fileCount ?? 0) ?></strong>
                </div>
            </div>

        </div>
    </section>
<?= $this->endSection() ?>
