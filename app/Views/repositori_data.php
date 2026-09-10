<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Repositori Data Lokasi Klaster Plot<?= $this->endSection() ?>

<?= $this->section('description') ?>Repositori arsip data lokasi klaster plot Azimutree.<?= $this->endSection() ?>

<?= $this->section('head') ?>
    <link rel="stylesheet" href="<?= base_url('assets/vendor/datatables/dataTables.dataTables.min.css') ?>">
    <script src="<?= base_url('assets/vendor/jquery/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/dataTables.min.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php
    $firstPublicContact = static function (?string $contacts): string {
        $contacts = trim((string) $contacts);

        if ($contacts === '') {
            return '';
        }

        $decoded = json_decode($contacts, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            foreach ($decoded as $contact) {
                $contact = trim((string) $contact);
                if ($contact !== '') {
                    return $contact;
                }
            }

            return '';
        }

        return $contacts;
    };
    ?>

    <section class="section">
        <div class="container">
            <div class="repo-header">
                <div>
                    <h2>Repositori Data Lokasi Klaster Plot</h2>
                    <p>Daftar arsip data Excel lokasi klaster plot. Satu arsip dapat berisi banyak file klaster.</p>
                </div>

                <div class="repo-auth">
                    <?php if ($user) : ?>
                        <button class="repo-auth-trigger" type="button" data-dropdown-toggle="repoProfileMenu" aria-expanded="false">
                            <span class="repo-avatar"><?= esc(strtoupper(substr($user['username'], 0, 1))) ?></span>
                            <span><?= esc($user['username']) ?></span>
                        </button>
                        <div id="repoProfileMenu" class="repo-dropdown hidden">
                            <p class="panel-note">Login sebagai <strong><?= esc($user['username']) ?></strong>.</p>
                            <?php if (($user['role'] ?? 'user') === 'admin') : ?>
                                <a class="repo-dropdown-link" href="<?= base_url('repositori-data/admin') ?>">Menu Admin</a>
                            <?php endif; ?>
                            <?php if (! empty($user['impersonator_admin_id'])) : ?>
                                <form action="<?= base_url('repositori-data/admin/stop-impersonation') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button class="repo-dropdown-link repo-dropdown-button" type="submit">Kembali sebagai Admin</button>
                                </form>
                            <?php endif; ?>
                            <a class="repo-dropdown-link" href="<?= base_url('repositori-data/profile') ?>">Profile</a>
                            <a class="repo-dropdown-link" href="<?= base_url('repositori-data/logout') ?>">Logout</a>
                        </div>
                    <?php else : ?>
                        <button class="repo-auth-trigger" type="button" data-dropdown-toggle="repoLoginMenu" aria-expanded="false">Login</button>
                        <div id="repoLoginMenu" class="repo-dropdown repo-login-dropdown hidden">
                            <h3>Login Pengelola</h3>
                            <form class="repo-form" action="<?= base_url('repositori-data/login') ?>" method="post">
                                <?= csrf_field() ?>
                                <label for="username">Username atau Email</label>
                                <input id="username" name="username" type="text" autocomplete="username" required>

                                <label for="password">Password</label>
                                <div class="password-field">
                                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                                    <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password">Lihat</button>
                                </div>

                                <button class="btn primary" type="submit">Login</button>
                            </form>
                            <p class="auth-switch compact">Belum punya akun? <a href="<?= base_url('repositori-data/register') ?>">Registrasi</a>.</p>
                            <p class="auth-switch compact"><a href="<?= base_url('repositori-data/lupa-password') ?>">Lupa password?</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (session('success')) : ?>
                <div class="notice success"><?= esc(session('success')) ?></div>
            <?php endif; ?>

            <?php if (session('error')) : ?>
                <div class="notice error"><?= esc(session('error')) ?></div>
            <?php endif; ?>

            <?php if ($user && ! $user['email_verified']) : ?>
                <div class="notice error persistent-notice">
                    Email Anda belum diverifikasi. <a href="<?= base_url('repositori-data/verifikasi-email') ?>">Verifikasi terlebih dahulu</a>.
                </div>
            <?php endif; ?>

            <?php if ($user) : ?>
                <?php $activeTab = session('repo_active_tab') === 'manage' ? 'manage' : 'public'; ?>
                <div class="repo-tabs" role="tablist" aria-label="Pilihan tampilan repositori">
                    <button class="repo-tab <?= $activeTab === 'public' ? 'active' : '' ?>" type="button" role="tab" aria-selected="<?= $activeTab === 'public' ? 'true' : 'false' ?>" data-tab-target="repoPublicPanel">Data Publik</button>
                    <button class="repo-tab <?= $activeTab === 'manage' ? 'active' : '' ?>" type="button" role="tab" aria-selected="<?= $activeTab === 'manage' ? 'true' : 'false' ?>" data-tab-target="repoManagePanel">Kelola Arsip</button>
                </div>
            <?php endif; ?>

            <section id="repoPublicPanel" class="repo-section-block repo-tab-panel <?= isset($activeTab) && $activeTab === 'manage' ? 'hidden' : '' ?>">
                <div class="section-heading section-heading-row">
                    <div>
                        <h3>Data Publik</h3>
                        <p>Semua arsip yang tersedia untuk pengunjung.</p>
                    </div>

                    <label class="repo-filter-toggle">
                        <input id="publicArchiveHasFilesOnly" type="checkbox" data-public-has-files-filter checked>
                        <span>Sembunyikan arsip tanpa file</span>
                    </label>
                </div>

                <div class="repo-main">
                    <div class="repo-table-wrap">
                        <table id="publicArchiveTable" class="repo-table archive-group-table display">
                            <thead>
                                <tr>
                                    <th>Nama Arsip</th>
                                    <th>Pemilik</th>
                                    <th>Jumlah File</th>
                                    <th>Update Terakhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($archiveGroups as $archiveGroup) : ?>
                                    <?php
                                    $ownerName = $archiveGroup['owner_name'] ?: $archiveGroup['owner_username'] ?: '-';
                                    $ownerUsername = $archiveGroup['owner_username'] ?: '';
                                    $ownerInstitution = $archiveGroup['owner_institution'] ?? '';
                                    $ownerContact = $firstPublicContact($archiveGroup['owner_public_contact'] ?? null);
                                    $ownerProfileUrl = $ownerUsername ? base_url('repositori-data/u/' . rawurlencode($ownerUsername)) : '#';
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="archive-title-desktop"><?= esc($archiveGroup['name']) ?></span>
                                            <span class="archive-group-mobile-summary">
                                                <strong><?= esc($archiveGroup['name']) ?></strong>
                                                <span><?= $archiveGroup['latest_upload'] ? esc(date('Y-m-d', strtotime($archiveGroup['latest_upload']))) : 'Belum ada file' ?> · <?= esc($archiveGroup['file_count']) ?> file</span>
                                                <span>pemilik <?= esc($ownerName) ?></span>
                                                <span class="mobile-row-actions">
                                                    <a class="btn primary small" href="<?= base_url('repositori-data/arsip/' . $archiveGroup['id']) ?>">Lihat</a>
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($ownerUsername) : ?>
                                                <span class="uploader-popover">
                                                    <button class="uploader-trigger" type="button"><?= esc($ownerName) ?></button>
                                                    <span class="uploader-card">
                                                        <button class="uploader-close" type="button" aria-label="Tutup info pemilik">×</button>
                                                        <strong><?= esc($ownerName) ?></strong>
                                                        <span>@<?= esc($ownerUsername) ?></span>
                                                        <?php if ($ownerInstitution) : ?>
                                                            <span><?= esc($ownerInstitution) ?></span>
                                                        <?php endif; ?>
                                                        <?php if ($ownerContact) : ?>
                                                            <span><?= esc($ownerContact) ?></span>
                                                        <?php endif; ?>
                                                        <a class="btn ghost small" href="<?= esc($ownerProfileUrl) ?>">Lihat Profile</a>
                                                    </span>
                                                </span>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($archiveGroup['file_count']) ?></td>
                                        <td><?= $archiveGroup['latest_upload'] ? esc(date('Y-m-d', strtotime($archiveGroup['latest_upload']))) : '-' ?></td>
                                        <td class="repo-actions">
                                            <a class="btn primary small" href="<?= base_url('repositori-data/arsip/' . $archiveGroup['id']) ?>">Lihat</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <?php if ($user) : ?>
                <section id="repoManagePanel" class="repo-section-block repo-tab-panel <?= $activeTab === 'manage' ? '' : 'hidden' ?>">
                    <div class="section-heading section-heading-row">
                        <div>
                            <h3>Kelola Arsip</h3>
                            <p>Buat arsip terlebih dahulu, lalu tambahkan file klaster ke dalam arsip tersebut.</p>
                        </div>

                        <div class="repo-inline-action">
                            <button class="repo-auth-trigger" type="button" data-dropdown-toggle="repoCreateArchiveMenu" aria-expanded="false">Tambah Arsip</button>
                            <div class="repo-upload-backdrop hidden" data-dropdown-close="repoCreateArchiveMenu"></div>
                            <div id="repoCreateArchiveMenu" class="repo-dropdown repo-upload-dropdown hidden">
                                <div class="dropdown-heading">
                                    <h3>Tambah Arsip</h3>
                                    <button class="dropdown-close" type="button" data-dropdown-close="repoCreateArchiveMenu" aria-label="Tutup form tambah arsip">×</button>
                                </div>
                                <form class="repo-form" action="<?= base_url('repositori-data/arsip') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <label for="archive_name">Nama Arsip</label>
                                    <input id="archive_name" name="archive_name" type="text" maxlength="150" required>
                                    <button class="btn primary" type="submit">Simpan Arsip</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php if (empty($ownedArchiveGroups)) : ?>
                        <div class="repo-empty-action">
                            <p class="panel-note">Anda belum membuat arsip.</p>
                            <button class="btn primary" type="button" data-dropdown-toggle="repoCreateArchiveMenu" aria-expanded="false">Tambah Arsip</button>
                        </div>
                    <?php else : ?>
                        <div class="repo-main">
                            <div class="repo-table-wrap">
                                <table id="ownedArchiveTable" class="repo-table archive-group-table display">
                                    <thead>
                                        <tr>
                                            <th>Nama Arsip</th>
                                            <th>Pemilik</th>
                                            <th>Jumlah File</th>
                                            <th>Update Terakhir</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ownedArchiveGroups as $archiveGroup) : ?>
                                            <?php
                                            $ownerName = ($user['name'] ?? '') ?: $user['username'];
                                            $ownerUsername = $user['username'] ?? '';
                                            $ownerInstitution = $user['institution'] ?? '';
                                            $ownerContact = $firstPublicContact($user['public_contact'] ?? null);
                                            $ownerProfileUrl = $ownerUsername ? base_url('repositori-data/u/' . rawurlencode($ownerUsername)) : '#';
                                            ?>
                                            <tr>
                                                <td>
                                                    <span class="archive-title-desktop"><?= esc($archiveGroup['name']) ?></span>
                                                    <span class="archive-group-mobile-summary">
                                                        <strong><?= esc($archiveGroup['name']) ?></strong>
                                                        <span><?= $archiveGroup['latest_upload'] ? esc(date('Y-m-d', strtotime($archiveGroup['latest_upload']))) : 'Belum ada file' ?> · <?= esc($archiveGroup['file_count']) ?> file</span>
                                                        <span>pemilik <?= esc($ownerName) ?></span>
                                                        <span class="mobile-row-actions">
                                                            <a class="btn ghost small" href="<?= base_url('repositori-data/arsip/' . $archiveGroup['id']) ?>">Lihat</a>
                                                            <a class="btn primary small" href="<?= base_url('repositori-data/arsip/' . $archiveGroup['id'] . '/upload') ?>">Tambah File</a>
                                                            <form action="<?= base_url('repositori-data/arsip/' . $archiveGroup['id'] . '/delete') ?>" method="post" data-delete-group-form data-delete-group-name="<?= esc($archiveGroup['name']) ?>" data-delete-group-files="<?= esc($archiveGroup['file_count']) ?>">
                                                                <?= csrf_field() ?>
                                                                <button class="btn danger small" type="submit">Hapus</button>
                                                            </form>
                                                        </span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($ownerUsername) : ?>
                                                        <span class="uploader-popover">
                                                            <button class="uploader-trigger" type="button"><?= esc($ownerName) ?></button>
                                                            <span class="uploader-card">
                                                                <button class="uploader-close" type="button" aria-label="Tutup info pemilik">×</button>
                                                                <strong><?= esc($ownerName) ?></strong>
                                                                <span>@<?= esc($ownerUsername) ?></span>
                                                                <?php if ($ownerInstitution) : ?>
                                                                    <span><?= esc($ownerInstitution) ?></span>
                                                                <?php endif; ?>
                                                                <?php if ($ownerContact) : ?>
                                                                    <span><?= esc($ownerContact) ?></span>
                                                                <?php endif; ?>
                                                                <a class="btn ghost small" href="<?= esc($ownerProfileUrl) ?>">Lihat Profile</a>
                                                            </span>
                                                        </span>
                                                    <?php else : ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= esc($archiveGroup['file_count']) ?></td>
                                                <td><?= $archiveGroup['latest_upload'] ? esc(date('Y-m-d', strtotime($archiveGroup['latest_upload']))) : '-' ?></td>
                                                <td class="repo-actions archive-group-actions">
                                                    <a class="btn ghost small action-chip" href="<?= base_url('repositori-data/arsip/' . $archiveGroup['id']) ?>">Lihat</a>
                                                    <a class="btn primary small action-chip" href="<?= base_url('repositori-data/arsip/' . $archiveGroup['id'] . '/upload') ?>">+ File</a>
                                                    <form action="<?= base_url('repositori-data/arsip/' . $archiveGroup['id'] . '/delete') ?>" method="post" data-delete-group-form data-delete-group-name="<?= esc($archiveGroup['name']) ?>" data-delete-group-files="<?= esc($archiveGroup['file_count']) ?>">
                                                        <?= csrf_field() ?>
                                                        <button class="btn danger small action-chip" type="submit">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
        </div>
    </section>

    <div id="deleteArchiveGroupModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="deleteArchiveGroupTitle">
        <div class="modal-backdrop"></div>
        <div class="modal-box danger-modal" role="document">
            <h3 id="deleteArchiveGroupTitle">Hapus arsip?</h3>
            <p id="deleteArchiveGroupMessage" class="muted modal-message">Arsip ini akan dihapus dari repositori.</p>
            <p class="modal-warning">Semua file di dalam arsip ikut terhapus. Tindakan ini tidak bisa di-undo.</p>
            <div class="modal-actions">
                <button id="deleteArchiveGroupCancel" class="btn ghost" type="button">Batal</button>
                <button id="deleteArchiveGroupConfirm" class="btn danger" type="button">Konfirmasi</button>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
