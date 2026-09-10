<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Ubah User Admin<?= $this->endSection() ?>

<?= $this->section('description') ?>Ubah informasi user melalui menu administrator.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php $publicContacts = ($publicContacts ?? []) ?: ['']; ?>

    <section class="section">
        <div class="container">
            <div class="profile-panel">
                <div class="profile-panel-main">
                    <div class="profile-avatar"><?= esc(strtoupper(substr($managedUser['username'], 0, 1))) ?></div>
                    <div>
                        <h2>Ubah User</h2>
                        <p class="panel-note">@<?= esc($managedUser['username']) ?> · <?= esc($archiveCount) ?> arsip</p>
                    </div>
                </div>
                <a class="btn ghost small profile-back-btn" href="<?= base_url('repositori-data/admin') ?>">Kembali</a>
            </div>

            <?php if (session('success')) : ?>
                <div class="notice success"><?= esc(session('success')) ?></div>
            <?php endif; ?>

            <?php if (session('error')) : ?>
                <div class="notice error"><?= esc(session('error')) ?></div>
            <?php endif; ?>

            <div class="profile-grid">
                <form class="profile-card repo-form" action="<?= base_url('repositori-data/admin/users/' . $managedUser['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <h3>Informasi User</h3>

                    <label for="admin_edit_name">Nama</label>
                    <input id="admin_edit_name" name="name" type="text" maxlength="150" value="<?= esc($managedUser['name'] ?: $managedUser['username']) ?>" required>

                    <label for="admin_edit_username">Username</label>
                    <input id="admin_edit_username" name="username" type="text" maxlength="100" value="<?= esc($managedUser['username']) ?>" required>

                    <label for="admin_edit_email">Email</label>
                    <input id="admin_edit_email" name="email" type="email" maxlength="150" value="<?= esc($managedUser['email']) ?>" required>
                    <p class="field-note <?= $emailVerified ? '' : 'danger-text' ?>">
                        Status email: <?= $emailVerified ? 'terverifikasi' : 'belum terverifikasi' ?>.
                    </p>

                    <label for="admin_edit_institution">Instansi</label>
                    <input id="admin_edit_institution" name="institution" type="text" maxlength="150" value="<?= esc($managedUser['institution'] ?? '') ?>">

                    <div class="contact-field-head">
                        <label>Kontak Publik</label>
                        <span>Maksimal 3</span>
                    </div>
                    <p class="field-note">Opsional, akan ditampilkan agar pengguna lain bisa menghubungi user ini.</p>

                    <div class="public-contact-list" data-public-contact-list>
                        <?php foreach (array_slice($publicContacts, 0, 3) as $contactIndex => $contact) : ?>
                            <div class="public-contact-item" data-public-contact-item>
                                <input
                                    id="admin_public_contact_<?= esc($contactIndex) ?>"
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

                    <p class="field-note">Role akun ini tetap user.</p>

                    <button class="btn primary" type="submit">Simpan User</button>
                </form>

                <div class="profile-card repo-form">
                    <h3>Aksi Akun</h3>

                    <form class="repo-form" action="<?= base_url('repositori-data/admin/users/' . $managedUser['id'] . '/password') ?>" method="post">
                        <?= csrf_field() ?>
                        <label for="admin_edit_password">Password Baru</label>
                        <div class="password-field">
                            <input id="admin_edit_password" name="password" type="password" required>
                            <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
                        </div>
                        <button class="btn ghost" type="submit">Ubah Password</button>
                    </form>

                    <?php if (! $emailVerified) : ?>
                        <form action="<?= base_url('repositori-data/admin/users/' . $managedUser['id'] . '/verify') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="redirect_to" value="edit">
                            <button class="btn primary" type="submit">Verifikasi Email Manual</button>
                        </form>
                    <?php endif; ?>

                    <form action="<?= base_url('repositori-data/admin/users/' . $managedUser['id'] . '/login') ?>" method="post">
                        <?= csrf_field() ?>
                        <button class="btn ghost" type="submit">Login sebagai User Ini</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
