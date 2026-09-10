<?= $this->extend('layout') ?>

<?= $this->section('title') ?>Profile <?= esc($profileUser['name'] ?: $profileUser['username']) ?><?= $this->endSection() ?>

<?= $this->section('description') ?>Profile publik pengguna repositori data Azimutree.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="section">
        <div class="container">
            <div class="profile-panel">
                <div class="profile-panel-main">
                    <div class="profile-avatar"><?= esc(strtoupper(substr($profileUser['name'] ?: $profileUser['username'], 0, 1))) ?></div>
                    <div>
                        <h2><?= esc($profileUser['name'] ?: $profileUser['username']) ?></h2>
                        <p class="panel-note">@<?= esc($profileUser['username']) ?></p>
                    </div>
                </div>
                <a class="btn ghost small profile-back-btn" href="<?= base_url('repositori-data') ?>">Kembali</a>
            </div>

            <div class="public-profile-grid">
                <div class="profile-card">
                    <h3>Informasi Publik</h3>
                    <dl class="profile-detail-list">
                        <div>
                            <dt>Nama</dt>
                            <dd><?= esc($profileUser['name'] ?: $profileUser['username']) ?></dd>
                        </div>
                        <div>
                            <dt>Username</dt>
                            <dd>@<?= esc($profileUser['username']) ?></dd>
                        </div>
                        <div>
                            <dt>Instansi</dt>
                            <dd><?= esc($profileUser['institution'] ?: '-') ?></dd>
                        </div>
                    </dl>
                </div>

                <div class="profile-card">
                    <h3>Kontak Publik</h3>
                    <?php if ($publicContacts) : ?>
                        <ul class="public-contact-display">
                            <?php foreach ($publicContacts as $contact) : ?>
                                <li><?= esc($contact) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="panel-note">Pengguna ini belum menambahkan kontak publik.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="profile-grid">
                <div class="profile-stat public-profile-stat">
                    <span>Jumlah Arsip</span>
                    <strong><?= esc($archiveCount) ?></strong>
                </div>

                <div class="profile-stat public-profile-stat">
                    <span>Jumlah File</span>
                    <strong><?= esc($fileCount ?? 0) ?></strong>
                </div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
