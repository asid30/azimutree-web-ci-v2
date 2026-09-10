<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Admin Repositori Data<?= $this->endSection() ?>

<?= $this->section('description') ?>Menu administrator Repositori Data Azimutree.<?= $this->endSection() ?>

<?= $this->section('head') ?>
    <link rel="stylesheet" href="<?= base_url('assets/vendor/datatables/dataTables.dataTables.min.css') ?>">
    <script src="<?= base_url('assets/vendor/jquery/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/dataTables.min.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="section">
        <div class="container">
            <div class="profile-panel">
                <div class="profile-panel-main">
                    <div class="profile-avatar"><?= esc(strtoupper(substr($user['username'], 0, 1))) ?></div>
                    <div>
                        <h2>Menu Admin</h2>
                        <p class="panel-note">@<?= esc($user['username']) ?> · administrator</p>
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

            <div class="repo-tabs admin-tabs" role="tablist" aria-label="Menu admin">
                <button class="repo-tab active" type="button" role="tab" aria-selected="true" data-tab-target="adminSummaryPanel">Ringkasan</button>
                <button class="repo-tab" type="button" role="tab" aria-selected="false" data-tab-target="adminUsersPanel">Kelola User</button>
            </div>

            <section id="adminSummaryPanel" class="repo-section-block repo-tab-panel">
                <div class="section-heading">
                    <h3>Ringkasan</h3>
                    <p>Ikhtisar data administrator repositori.</p>
                </div>

                <div class="admin-summary-grid">
                    <div class="profile-stat public-profile-stat">
                        <span>Jumlah User</span>
                        <strong><?= esc(count($users)) ?></strong>
                    </div>

                    <div class="profile-stat public-profile-stat">
                        <span>Jumlah Arsip</span>
                        <strong><?= esc(count($archiveGroups)) ?></strong>
                    </div>

                    <div class="profile-stat public-profile-stat">
                        <span>Jumlah File</span>
                        <strong><?= esc($fileCount ?? 0) ?></strong>
                    </div>
                </div>
            </section>

            <section id="adminUsersPanel" class="repo-section-block repo-tab-panel hidden">
                <div class="section-heading">
                    <h3>Kelola User</h3>
                    <p>Buat user baru, lalu kelola akun melalui tabel user.</p>
                </div>

                <form class="profile-card repo-form admin-create-user" action="<?= base_url('repositori-data/admin/users') ?>" method="post">
                    <?= csrf_field() ?>
                    <h3>Buat User Baru</h3>

                    <div class="admin-form-grid">
                        <label for="admin_create_name">
                            Nama
                            <input id="admin_create_name" name="name" type="text" maxlength="150" value="<?= esc(old('name')) ?>" required>
                        </label>

                        <label for="admin_create_username">
                            Username
                            <input id="admin_create_username" name="username" type="text" maxlength="100" value="<?= esc(old('username')) ?>" required>
                        </label>

                        <label for="admin_create_email">
                            Email
                            <input id="admin_create_email" name="email" type="email" maxlength="150" value="<?= esc(old('email')) ?>" required>
                        </label>

                        <label for="admin_create_password">
                            Password
                            <span class="password-field">
                                <input id="admin_create_password" name="password" type="password" required>
                                <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
                            </span>
                        </label>

                        <label for="admin_create_institution">
                            Instansi
                            <input id="admin_create_institution" name="institution" type="text" maxlength="150" value="<?= esc(old('institution')) ?>">
                        </label>

                        <div class="admin-form-wide">
                            <div class="contact-field-head">
                                <label>Kontak Publik</label>
                                <span>Maksimal 3</span>
                            </div>
                            <p class="field-note">Opsional, akan ditampilkan agar pengguna lain bisa menghubungi user ini.</p>
                            <div class="public-contact-list" data-public-contact-list>
                                <div class="public-contact-item" data-public-contact-item>
                                    <input id="admin_create_contact_0" name="public_contact[]" type="text" maxlength="150" placeholder="Email, no telp, WhatsApp, atau kontak lain">
                                    <button class="icon-action danger" type="button" data-public-contact-remove aria-label="Hapus kontak">x</button>
                                </div>
                            </div>
                            <button class="btn ghost small contact-add-btn" type="button" data-public-contact-add>Tambah Kontak</button>
                        </div>
                    </div>

                    <p class="field-note">User baru akan dibuat sebagai user biasa dan emailnya langsung terverifikasi.</p>

                    <button class="btn primary" type="submit">Buat User</button>
                </form>

                <div class="repo-main">
                    <div class="repo-table-wrap">
                        <table id="adminUserTable" class="repo-table display">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Arsip</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $managedUser) : ?>
                                    <?php $isProtectedAdmin = ($managedUser['role'] ?? 'user') === 'admin'; ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($managedUser['name'] ?: $managedUser['username']) ?></strong><br>
                                            <span class="panel-note">@<?= esc($managedUser['username']) ?></span>
                                            <div class="admin-user-mobile-summary">
                                                <span><?= esc($managedUser['email']) ?></span>
                                                <span><?= esc($managedUser['role']) ?> · <?= $managedUser['email_verified'] ? 'terverifikasi' : 'belum verifikasi' ?> · <?= esc($managedUser['archive_count']) ?> arsip</span>
                                            </div>
                                        </td>
                                        <td><?= esc($managedUser['email']) ?></td>
                                        <td><?= esc($managedUser['role']) ?></td>
                                        <td><?= $managedUser['email_verified'] ? 'Terverifikasi' : 'Belum verifikasi' ?></td>
                                        <td><?= esc($managedUser['archive_count']) ?></td>
                                        <td class="repo-actions archive-group-actions">
                                            <?php if ($isProtectedAdmin) : ?>
                                                <span class="admin-protected-note">Akun admin</span>
                                            <?php else : ?>
                                                <form action="<?= base_url('repositori-data/admin/users/' . $managedUser['id'] . '/login') ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <button class="btn ghost small action-chip" type="submit">Login</button>
                                                </form>
                                                <a class="btn primary small action-chip" href="<?= base_url('repositori-data/admin/users/' . $managedUser['id'] . '/edit') ?>">Ubah</a>
                                                <?php if (! $managedUser['email_verified']) : ?>
                                                    <form action="<?= base_url('repositori-data/admin/users/' . $managedUser['id'] . '/verify') ?>" method="post">
                                                        <?= csrf_field() ?>
                                                        <button class="btn ghost small action-chip" type="submit">Verifikasi</button>
                                                    </form>
                                                <?php endif; ?>
                                                <form action="<?= base_url('repositori-data/admin/users/' . $managedUser['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Hapus user ini beserta arsip miliknya? Tindakan ini tidak bisa di-undo.')">
                                                    <?= csrf_field() ?>
                                                    <button class="btn danger small action-chip" type="submit">Hapus</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </section>
<?= $this->endSection() ?>
