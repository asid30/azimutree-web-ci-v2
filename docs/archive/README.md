# Arsip Repositori Data

Fitur Repositori Data dinonaktifkan dengan menghapus semua route aktifnya dari `app/Config/Routes.php`. Definisi route lama disimpan di `repositori-data-routes.php.txt`; file ini tidak dimuat aplikasi.

Controller, model, view, CSS, JavaScript, database, dan file unggahan tetap dipertahankan. Navigasi repositori disembunyikan, termasuk konteks `template?nav=repo`. Auto-routing dinonaktifkan secara eksplisit agar controller tidak bisa diakses lewat URL alternatif.

Untuk mengaktifkan kembali, pulihkan definisi route ke `app/Config/Routes.php`, kondisi konteks repositori di `app/Views/layout.php` dan `app/Views/template.php`, serta dua tautan navigasi yang ditandai sebagai arsip di layout. Auto-routing tetap tidak diperlukan.
