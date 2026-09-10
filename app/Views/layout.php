<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $this->renderSection('title') ?: 'Azimutree — Pemetaan dan Survey Lokasi Hutan (FHM)' ?></title>
    <meta name="description" content="<?= $this->renderSection('description') ?: 'Azimutree membantu pemetaan Titik Ikat, klaster, plot, dan pohon, survei lapangan dengan kompas dan radar, serta berbagi data penelitian melalui Penyimpanan Awan.
' ?>">
    <link rel="icon" href="<?= base_url('icon.ico') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/style.css') ?>">
    <?= $this->renderSection('head') ?>
</head>
<body>

<?php
// Hide appbar/sidebar for easteregg pages (secret + mybestie)
$uri = uri_string();
$hideNav = strpos($uri, 'easteregg/') === 0;
$navContext = service('request')->getGet('nav');
// Archived repository navigation; original condition retained for restoration.
$isRepositoryNav = false && ($uri === 'repositori-data' || strpos($uri, 'repositori-data/') === 0 || ($uri === 'template' && $navContext === 'repo'));
?>

<?php if (! $hideNav) : ?>
<header class="site-header appbar">
    <div class="container appbar-inner">
        <div class="appbar-left">
            <a class="brand" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/icon-128.webp') ?>" alt="Azimutree logo" class="logo" onerror="this.style.display='none'">
                <span class="brand-text">Azimutree</span>
            </a>
            <?php if ($isRepositoryNav) : ?>
                <span class="appbar-repo-context">
                    <a class="appbar-context" href="<?= base_url('repositori-data') ?>">Repositori Data</a>
                    <?php if (session('repo_role') === 'admin') : ?>
                        <span class="appbar-admin-badge">Administrator</span>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
        </div>
        <div class="appbar-right">
            <nav class="appbar-nav">
                <?php if ($isRepositoryNav) : ?>
                    <a class="app-link" href="<?= base_url('repositori-data/panduan') ?>">📘 Panduan</a>
                    <a class="app-link" href="<?= base_url('template') ?>?nav=repo">📄 Template</a>
                    <a class="app-link" href="https://forms.gle/7KE2jecH4CiNQxTg9" target="_blank" rel="noopener noreferrer">📝 Saran</a>
                <?php else : ?>
                    <a class="app-link" href="<?= base_url() ?>">🏠 Beranda</a>
                    <a class="app-link" href="<?= base_url('template') ?>">📄 Template</a>
<?php /* Archived repository link. */ if (false) : ?>
                    <a class="app-link" href="<?= base_url('repositori-data') ?>" target="_blank" rel="noopener noreferrer">🗂️ Repositori Data</a>
<?php endif; ?>
                    <a class="app-link" href="<?= base_url('panduan') ?>">📘 Panduan</a>
                    <a class="app-link" href="<?= base_url('about') ?>">ℹ️ Tentang</a>
                    <a class="app-link" href="https://forms.gle/7KE2jecH4CiNQxTg9" target="_blank" rel="noopener noreferrer">📝 Saran</a>
                <?php endif; ?>
            </nav>
            <button id="menuToggle" class="menu-btn" aria-label="Toggle menu">☰</button>
        </div>
    </div>
</header>

<!-- Mobile sidebar (toggled by #menuToggle) -->
<nav id="menu" class="mobile-sidebar hidden" aria-hidden="true">
    <div class="container">
        <div class="mobile-sidebar-header"><h3>Menu</h3></div>
        <?php if ($isRepositoryNav) : ?>
            <a class="app-link" href="<?= base_url('repositori-data/panduan') ?>">📘 Panduan</a>
            <a class="app-link" href="<?= base_url('template') ?>?nav=repo">📄 Template</a>
        <?php else : ?>
            <a class="app-link" href="<?= base_url() ?>">🏠 Beranda</a>
            <a class="app-link" href="<?= base_url('panduan') ?>">📘 Panduan</a>
            <a class="app-link" href="<?= base_url('template') ?>">📄 Template</a>
<?php /* Archived repository link. */ if (false) : ?>
            <a class="app-link" href="<?= base_url('repositori-data') ?>" target="_blank" rel="noopener noreferrer">🗂️ Repositori Data</a>
<?php endif; ?>
            <a class="app-link" href="<?= base_url('about') ?>">ℹ️ Tentang</a>
        <?php endif; ?>
        <a class="app-link" href="https://forms.gle/7KE2jecH4CiNQxTg9" target="_blank" rel="noopener noreferrer">📝 Saran</a>
    </div>
</nav>
<?php endif; ?>

<main>
    <?= $this->renderSection('content') ?>
</main>

<footer class="site-footer">
    <div class="container">
        <p>© <?= date('Y') ?> Azimutree · Lisensi MIT · Dikembangkan oleh Asid30</p>
    </div>
</footer>

<!-- Download Confirmation Modal (kept in layout so pages can trigger it) -->
<div id="downloadModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="downloadModalTitle">
    <div class="modal-backdrop"></div>
    <div class="modal-box" role="document">
        <h3 id="downloadModalTitle">Unduh APK Azimutree</h3>
        <p class="muted" style="margin-top:.5rem">⚠️ Catatan: Aplikasi ini diinstal di luar Play Store.<br>Aktifkan “Izinkan dari sumber ini” saat diminta oleh Android.</p>
        <p class="release-note">APK v1.1.26 berasal dari 26 Januari 2026, sebelum penambahan Titik Ikat, Survey Lokasi, dan Penyimpanan Awan yang dijelaskan dalam panduan terbaru.</p>
        <p id="downloadVersion" class="muted" style="margin-top:.5rem"></p>
        <div class="modal-actions" style="margin-top:1rem">
            <button id="downloadConfirm" class="btn primary">Lanjutkan dan Unduh</button>
            <button id="downloadCancel" class="btn ghost">Batal</button>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/app.js') ?>?v=<?= filemtime(FCPATH . 'assets/js/app.js') ?>"></script>
<?= $this->renderSection('scripts') ?>

</body>
</html>
