<?= $this->extend('layout') ?>

<?= $this->section('title') ?><?= esc($archiveGroup['name']) ?><?= $this->endSection() ?>

<?= $this->section('description') ?>Detail file dalam arsip <?= esc($archiveGroup['name']) ?>.<?= $this->endSection() ?>

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
    $isOwner = $user && (int) $user['id'] === (int) $archiveGroup['user_id'];
    ?>

    <section class="section">
        <div class="container">
            <div class="profile-panel">
                <div class="profile-panel-main">
                    <div>
                        <h2><?= esc($archiveGroup['name']) ?></h2>
                        <p class="panel-note">
                            <?= esc(count($files)) ?> file
                            <?php if ($owner) : ?>
                                · pemilik <?= esc(($owner['name'] ?? '') ?: $owner['username']) ?>
                            <?php endif; ?>
                        </p>
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

            <div class="repo-section-block">
                <div class="section-heading section-heading-row">
                    <div>
                        <h3>File Arsip</h3>
                        <p>Daftar file Excel yang tersimpan dalam arsip ini.</p>
                    </div>

                    <?php if ($isOwner) : ?>
                        <div class="repo-inline-action">
                            <a class="repo-auth-trigger" href="<?= base_url('repositori-data/arsip/' . $archiveGroup['id'] . '/upload') ?>">Tambah File</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="repo-main">
                    <div class="repo-table-wrap">
                        <table id="fileArchiveTable" class="repo-table file-archive-table display">
                            <thead>
                                <tr>
                                    <th>Kode Klaster</th>
                                    <th>Nama File</th>
                                    <th>Pemilik</th>
                                    <th>Tanggal Diambil</th>
                                    <th>Data File</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($files as $file) : ?>
                                    <?php
                                    $uploaderName = $file['uploader_name'] ?: $file['uploaded_by'];
                                    $uploaderUsername = $file['uploader_username'] ?: $file['uploaded_by'];
                                    $uploaderInstitution = $file['uploader_institution'] ?? '';
                                    $uploaderContact = $firstPublicContact($file['uploader_public_contact'] ?? null);
                                    $fileDate = date('Y-m-d', strtotime($file['upload_date']));
                                    $clusterLabel = 'CL' . $file['cluster_code'];
                                    $publicProfileUrl = base_url('repositori-data/u/' . rawurlencode($uploaderUsername));
                                    ?>
                                    <tr>
                                        <td><?= esc($clusterLabel) ?></td>
                                        <td><?= esc($file['original_filename']) ?></td>
                                        <td>
                                            <span class="uploader-popover">
                                                <button class="uploader-trigger" type="button"><?= esc($uploaderName) ?></button>
                                                <span class="uploader-card">
                                                    <button class="uploader-close" type="button" aria-label="Tutup info pemilik">×</button>
                                                    <strong><?= esc($uploaderName) ?></strong>
                                                    <span>@<?= esc($uploaderUsername) ?></span>
                                                    <?php if ($uploaderInstitution) : ?>
                                                        <span><?= esc($uploaderInstitution) ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($uploaderContact) : ?>
                                                        <span><?= esc($uploaderContact) ?></span>
                                                    <?php endif; ?>
                                                    <a class="btn ghost small" href="<?= esc($publicProfileUrl) ?>">Lihat Profile</a>
                                                </span>
                                            </span>
                                        </td>
                                        <td><?= esc($fileDate) ?></td>
                                        <td class="archive-mobile-summary">
                                            <strong><?= esc($file['original_filename']) ?></strong>
                                            <span>
                                                <?= esc($fileDate) ?> ·
                                                <span class="uploader-popover">
                                                    <button class="uploader-trigger" type="button"><?= esc($uploaderName) ?></button>
                                                    <span class="uploader-card">
                                                        <button class="uploader-close" type="button" aria-label="Tutup info pemilik">×</button>
                                                        <strong><?= esc($uploaderName) ?></strong>
                                                        <span>@<?= esc($uploaderUsername) ?></span>
                                                        <?php if ($uploaderInstitution) : ?>
                                                            <span><?= esc($uploaderInstitution) ?></span>
                                                        <?php endif; ?>
                                                        <?php if ($uploaderContact) : ?>
                                                            <span><?= esc($uploaderContact) ?></span>
                                                        <?php endif; ?>
                                                        <a class="btn ghost small" href="<?= esc($publicProfileUrl) ?>">Lihat Profile</a>
                                                    </span>
                                                </span>
                                            </span>
                                            <div class="mobile-row-actions">
                                                <a class="icon-action download" href="<?= base_url('repositori-data/download/' . $file['id']) ?>" data-download-link data-download-name="<?= esc($clusterLabel) ?>" data-download-location="<?= esc($archiveGroup['name']) ?>" data-download-date="<?= esc($fileDate) ?>" data-download-uploader="<?= esc($uploaderName) ?>" aria-label="Download data <?= esc($clusterLabel) ?>">↓</a>
                                                <?php if ($isOwner && (int) $file['user_id'] === (int) $user['id']) : ?>
                                                    <form action="<?= base_url('repositori-data/delete/' . $file['id']) ?>" method="post" data-delete-form data-delete-name="<?= esc($clusterLabel) ?>" data-delete-location="<?= esc($archiveGroup['name']) ?>" data-delete-date="<?= esc($fileDate) ?>" data-delete-uploader="<?= esc($uploaderName) ?>">
                                                        <?= csrf_field() ?>
                                                        <button class="icon-action danger" type="submit" aria-label="Hapus data <?= esc($clusterLabel) ?>">×</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="repo-actions">
                                            <a class="btn primary small" href="<?= base_url('repositori-data/download/' . $file['id']) ?>" data-download-link data-download-name="<?= esc($clusterLabel) ?>" data-download-location="<?= esc($archiveGroup['name']) ?>" data-download-date="<?= esc($fileDate) ?>" data-download-uploader="<?= esc($uploaderName) ?>">Download</a>
                                            <?php if ($isOwner && (int) $file['user_id'] === (int) $user['id']) : ?>
                                                <form action="<?= base_url('repositori-data/delete/' . $file['id']) ?>" method="post" data-delete-form data-delete-name="<?= esc($clusterLabel) ?>" data-delete-location="<?= esc($archiveGroup['name']) ?>" data-delete-date="<?= esc($fileDate) ?>" data-delete-uploader="<?= esc($uploaderName) ?>">
                                                    <?= csrf_field() ?>
                                                    <button class="btn danger small" type="submit">Hapus</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="deleteArchiveModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="deleteArchiveTitle">
        <div class="modal-backdrop"></div>
        <div class="modal-box danger-modal" role="document">
            <h3 id="deleteArchiveTitle">Hapus file arsip?</h3>
            <p id="deleteArchiveMessage" class="muted modal-message">File ini akan dihapus dari repositori.</p>
            <p class="modal-warning">Tindakan ini tidak bisa di-undo.</p>
            <div class="modal-actions">
                <button id="deleteArchiveCancel" class="btn ghost" type="button">Batal</button>
                <button id="deleteArchiveConfirm" class="btn danger" type="button">Konfirmasi</button>
            </div>
        </div>
    </div>

    <div id="downloadArchiveModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="downloadArchiveTitle">
        <div class="modal-backdrop"></div>
        <div class="modal-box" role="document">
            <h3 id="downloadArchiveTitle">Download file arsip?</h3>
            <p id="downloadArchiveMessage" class="muted modal-message">File arsip akan diunduh ke perangkat Anda.</p>
            <div class="modal-actions">
                <button id="downloadArchiveCancel" class="btn ghost" type="button">Batal</button>
                <a id="downloadArchiveConfirm" class="btn primary" href="#">Konfirmasi</a>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
