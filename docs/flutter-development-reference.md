# Acuan fitur Azimutree Flutter — development

Ditinjau pada 10 September 2026 dari branch development, commit `845cbcb0c1c65ad26c1499ea6ef57b9272a36101` (9 September 2026).
Ini rekonstruksi berdasarkan kode, panduan aplikasi, dan riwayat Git; bukan rekaman percakapan pengembangan terdahulu. Pemeriksaan statis, bukan pengujian aplikasi Android atau layanan Firebase live.

## Ruang lingkup aplikasi

Aplikasi Android untuk pencatatan, visualisasi, dan navigasi lokasi penelitian Forest Health Monitoring (FHM). Fokusnya lokasi Titik Ikat, klaster, plot, dan pohon; bukan pencatatan lengkap nilai kesehatan pohon/hutan. Data utama tetap lokal di SQLite. Peta menggunakan Mapbox; fitur awan memakai Firebase Authentication dan Cloud Firestore.

## 1. Titik Ikat dan pengelolaan data

- Setiap klaster memiliki satu Titik Ikat dan maksimal empat plot; Plot 1 menjadi pusat klaster.
- Form klaster mencakup kode, pengukur, tanggal, dan koordinat Titik Ikat. Nama Titik Ikat mengikuti kode klaster; ketinggian, keterangan, dan URL gambar bersifat opsional.
- Kode klaster dinormalisasi menjadi huruf kapital tanpa spasi.
- Posisi plot dapat diinput lewat azimut/jarak atau lintang/bujur. Plot pertama mengacu ke Titik Ikat, lalu plot tersimpan dapat menjadi referensi.
- Pemilih koordinat melalui peta tersedia pada form terkait.
- Pohon mengacu pada plot tempatnya berada, bukan selalu Plot 1.
- Saat pusat plot diedit, koordinat absolut pohon dipertahankan; azimut dan jarak relatif dihitung ulang. Data lama yang hanya memiliki arah/jarak dipulihkan menjadi koordinat menggunakan pusat plot lama.
- Database lokal sudah versi 6 dengan jalur migrasi dan relasi Titik Ikat.

Sumber: `lib/data/models/titik_ikat_model.dart`, `lib/data/database/azimutree_db.dart`, `lib/data/database/plot_dao.dart`, form pada `lib/views/widgets/manage_data_widget/`.

## 2. Survey Lokasi

- Alur: pilih klaster → dekati Titik Ikat dengan GPS/peta kecil → konfirmasi objek secara fisik → navigasi ke Plot 1 dengan azimut/jarak → pindah plot atau cari pohon.
- GPS dipakai untuk mendekati lokasi. Konfirmasi lapangan tidak mengganti koordinat dengan posisi GPS; jarak referensi tidak boleh disebut sebagai sisa jarak GPS.
- Radar berpusat pada pusat plot, bukan posisi ponsel. Sektor arah mengikuti kompas; saat kompas mati, tampilan berorientasi utara.
- Ada penyaringan pohon berdasarkan arah, detail pohon, serta pin pohon pada daftar.
- Sesi navigasi disimpan memakai SharedPreferences dan bisa dilanjutkan setelah kembali ke Beranda. Akhiri Sesi Survey menutup sesi; pembatalan navigasi tidak otomatis mengakhirinya.
- Pin pohon disimpan dalam state halaman; jangan menjanjikan pin persisten lintas restart.
- Ada refresh GPS, informasi akurasi, dan dialog kalibrasi kompas dengan GIF.

Sumber: `lib/views/pages/survey_location_page.dart`, `lib/data/notifiers/survey_navigation_notifier.dart`, `lib/services/survey_session_storage.dart`, `lib/views/widgets/survey_widget/`.

## 3. Penyimpanan Awan

- Data penelitian publik dapat ditelusuri, dicari berdasarkan nama folder lokasi, dan diunduh tanpa login.
- Login Google opsional, diperlukan untuk mengunggah dan mengelola data sendiri.
- Folder lokasi penelitian memiliki nama unik dan tanggal penelitian; visibilitas publik/privat, dengan nilai awal publik.
- Unggah satu atau beberapa klaster lokal beserta Titik Ikat, plot, pohon, dan metadata ke folder pilihan.
- Transfer dilakukan manual melalui unggah/unduh snapshot. Perubahan di awan tidak otomatis memperbarui salinan lokal.
- Bila kode klaster unduhan sudah ada di lokal, pengguna memilih kode salinan baru.
- Pencarian dan filter sembunyikan folder kosong tersedia; preferensi filter disimpan.
- Klaster milik sendiri dapat dihapus; penghapusan folder biasa mensyaratkan folder kosong.
- Gambar dibagikan sebagai URL, bukan unggahan berkas gambar ke Firebase Storage.
- Profil awan mendukung perubahan nama tampilan.
- Hapus akun meminta autentikasi ulang Google, menghapus data awan milik akun beserta profil dan akun Firebase; data SQLite lokal tetap tersimpan. Ini tidak menghapus akun Google pengguna.
- Ada pemeriksaan koneksi/status layanan serta aturan akses Firestore.

Sumber: `lib/services/cloud_owned_data_service.dart`, `lib/services/cloud_public_data_service.dart`, `lib/services/cloud_user_profile_service.dart`, `lib/services/cloud_account_deletion_service.dart`, `lib/views/pages/cloud_profile_page.dart`, `firestore.rules`.

## 4. Excel, peta, dan antarmuka

- Impor/ekspor mendukung beberapa klaster. Format berisi sheet `panduan`, `klaster`, `titik_ikat`, `plot`, dan `pohon`, dengan validasi kolom dan relasi.
- Titik Ikat ditampilkan sebagai pin merah; plot biru, centroid ungu, dan pohon dengan ikon pohon. Ada area plot berdasarkan pohon terjauh ditambah margin.
- Pencarian mencakup lokasi Mapbox dan data lokal. Hasil lokal tetap tersedia ketika pencarian daring bermasalah.
- Tracking Data membuka peta dengan target terpilih; tampilan kamera dan legenda disempurnakan.
- Map Tools mengatur garis relasi, ukuran marker, legenda, info marker, dan workflow inspeksi; preferensi disimpan.
- Beranda memiliki Survey Lokasi; sidebar menambahkan Penyimpanan Awan dan Versi Aplikasi.
- Catatan versi mengambil entri terpublikasi dari Firestore. Tema terang/gelap dan Mode Debug tetap tersedia.
- Pembaruan lain mencakup istilah Lintang/Bujur/Ketinggian, dialog aplikasi, aset screenshot, konfigurasi Firebase dari environment, serta namespace Android.

Sumber: `lib/services/excel_import_service.dart`, `lib/services/excel_export_service.dart`, `lib/views/widgets/location_map_widget/`, `lib/views/pages/tutorial_page.dart`, `lib/services/app_version_service.dart`.

## Acuan pembaruan website

- `home.php`: tambahkan Titik Ikat, Survey Lokasi, radar/kompas, awan, dan dukungan beberapa klaster pada Excel.
- `about.php`: perbarui konsep data, teknologi Firebase, penjelasan transfer manual, profil, serta fitur publik/privat.
- `panduan.php`: sesuaikan beranda, pembuatan klaster/Titik Ikat, referensi plot, format Excel, interaksi peta, Survey Lokasi, dan Penyimpanan Awan.
- `template.php`: periksa kesesuaian spreadsheet tertaut dengan format ekspor baru sebelum mengganti panduannya; isi spreadsheet belum diperiksa dalam peninjauan ini.
- Repositori Data CodeIgniter tetap diarsipkan. Fitur Penyimpanan Awan Flutter memakai Firebase dan tidak bergantung pada route repositori website.
- `pubspec.yaml` masih `1.1.26+26`, walaupun terdapat commit setelah tag `v1.1.26`. Jangan menganggap APK bertag itu sudah berisi semua fitur development. APK belum diuji.

## Referensi tetap

- [README.md](https://github.com/asid30/azimutree-flutter/blob/845cbcb0c1c65ad26c1499ea6ef57b9272a36101/README.md)
- [lib/views/pages/tutorial_page.dart](https://github.com/asid30/azimutree-flutter/blob/845cbcb0c1c65ad26c1499ea6ef57b9272a36101/lib/views/pages/tutorial_page.dart)
- [lib/views/pages/survey_location_page.dart](https://github.com/asid30/azimutree-flutter/blob/845cbcb0c1c65ad26c1499ea6ef57b9272a36101/lib/views/pages/survey_location_page.dart)
- [lib/services/cloud_owned_data_service.dart](https://github.com/asid30/azimutree-flutter/blob/845cbcb0c1c65ad26c1499ea6ef57b9272a36101/lib/services/cloud_owned_data_service.dart)
- [lib/views/pages/cloud_profile_page.dart](https://github.com/asid30/azimutree-flutter/blob/845cbcb0c1c65ad26c1499ea6ef57b9272a36101/lib/views/pages/cloud_profile_page.dart)
- [lib/data/database/plot_dao.dart](https://github.com/asid30/azimutree-flutter/blob/845cbcb0c1c65ad26c1499ea6ef57b9272a36101/lib/data/database/plot_dao.dart)
- [pubspec.yaml](https://github.com/asid30/azimutree-flutter/blob/845cbcb0c1c65ad26c1499ea6ef57b9272a36101/pubspec.yaml)

## Riwayat commit setelah tag v1.1.26

Daftar berikut mencakup commit yang terjangkau dari development tetapi tidak dari tag tersebut, termasuk merge; tidak semua adalah fitur baru.

```text
845cbcb 2026-09-09 feat: update .gitignore to include additional documentation files
852c413 2026-09-09 feat: implement cloud account deletion service and add cloud profile page
242a2ce 2026-09-08 feat: update application namespace and add MainActivity class
134c8af 2026-09-07 feat: update image assets for improved quality
1cb3dbb 2026-09-07 feat: update image assets with new versions for improved quality
fbd2ce2 2026-09-07 feat: add compass calibration dialog with instructional GIF and update compass calibration info
71c460e 2026-09-07 feat: enhance survey location page with session handling and tree pinning functionality
0c31bbb 2026-09-07 docs: update README to improve clarity and consistency in feature descriptions
f6922ba 2026-09-07 docs: add clarification on cluster reference point in README
3b436bc 2026-09-07 Refactor TutorialPage: Simplify build method, enhance readability, and improve widget structure
ce7a005 2026-09-07 feat: add ClusterCodeInputFormatter for input normalization and validation
5fb4d20 2026-09-06 Merge pull request #62 from asid30:61-cloud-data-synchronization-feature
3b5aea9 2026-09-06 feat: implement search functionality and toggle for hiding empty folders in cloud storage widgets
1e45d65 2026-09-06 feat: add app version management with Firestore integration and UI support
919f197 2026-09-06 feat: enhance plot update logic to recalculate tree coordinates and add tree notifier to relevant widgets
e9cfada 2026-09-06 feat: refine map layout and legend styling for improved clarity and usability
d6970f2 2026-09-06 feat: add dynamic camera padding based on map height for improved user experience
5867e1e 2026-09-06 feat: implement map tracking request handling and update related notifiers
3dc78e3 2026-09-06 feat: update terminology for altitude to ketinggian and enhance local data synchronization
295d291 2026-09-06 feat: enhance Firestore rules for research locations and add rename functionality for downloaded clusters
9b33aeb 2026-09-06 feat: implement alert dialog services and refactor snackbar usage across the application
5e0f162 2026-09-06 feat: refactor FirebaseOptions to use environment variables for API keys
3216a42 2026-09-06 feat: update Firestore rules for display name validation and enhance user profile management
aaed54a 2026-09-06 feat: enhance Firestore rules and update cloud data service for cluster management
20e06f9 2026-09-06 feat: enhance Firestore rules for research locations and add cloud data management features
2629c19 2026-09-06 feat: add public cloud data browsing functionality and update Firestore rules for research locations
e5bf0c3 2026-09-06 feat: implement cloud data synchronization with Firestore and user profile management
9eaa278 2026-09-05 feat: implement cloud storage page and integrate Google Sign-In functionality
94c2339 2026-09-05 feat: integrate Firebase services for cloud data synchronization and connection management
f7942c5 2026-09-05 feat: update data management options with cloud storage integration and improve UI layout
57cfca2 2026-09-05 feat: enhance searchLocationService with injectable dependencies and local data prioritization
fd1b4c7 2026-09-05 feat: add listeners for plot selection and search field focus management
b5f1f11 2026-09-05 Merge pull request #60 from asid30:58-location-survey-feature
7b9b9c5 2026-09-05 Refactor import data dialog and update import logic
d19d65e 2026-09-05 feat: implement multi-cluster export functionality and update related UI components
11a7086 2026-09-04 fix: update comments and variable names for clarity in MapboxWidget
c911e6f 2026-09-04 feat: enhance button styles in plot and cluster dialog widgets for better accessibility
960d8c8 2026-09-04 fix: update radar compass subtitle for clarity
918608a 2026-09-04 Refactor manage data widgets and update database migration
11654c2 2026-09-04 feat: add urlFoto field to TitikIkat model and update related dialogs for image support
9cd4721 2026-09-03 feat: implement anchor mini map widget and enhance survey navigation with new anchor selection features
1302849 2026-09-02 feat: enhance MapLegendWidget with customizable visibility options and update labels for improved clarity
dafa753 2026-09-02 feat: add Titik Ikat support with notifiers and UI integration for improved map interaction
6bad985 2026-09-02 feat: add coordinate picker for selecting locations on the map in various dialogs
75bd618 2026-09-02 feat: clear previous selections when tracking trees and plots for improved map navigation
60219f0 2026-09-02 feat: implement geodesic calculations for azimuth and distance, update plot dialog to include reference options
8c6bf84 2026-09-02 feat: add plot area coverage calculation and rendering for improved visualization
75908e6 2026-09-02 feat: adjust icon size for selected and unselected tree markers for better visibility
4e14785 2026-09-01 feat: add tree point annotation manager and custom tree icon rendering for improved visualization
ecf611f 2026-09-01 feat: update tree information display to show 'Kode Pohon' and improve null checks for tree names
36fba02 2026-09-01 feat: update terminology from Latitude/Longitude to Lintang/Bujur across the application
be1373f 2026-09-01 feat: enhance HomePage layout and MenuButtonWidget for improved UI responsiveness
f078951 2026-09-01 feat: add TreeRadarWidget and related tests for visualizing tree data based on azimuth and distance
4a3068d 2026-09-01 feat: implement tree direction filtering and enhance survey location page with tree data integration
3a0e748 2026-09-01 feat: implement session management for survey navigation with storage and restoration functionality
1871516 2026-09-01 feat: enhance Survey Location page UI with improved dropdown and button styles
aafbcde 2026-09-01 feat: implement Survey Navigation feature with state management and location handling
7b58e64 2026-08-31 feat: update database schema to version 4 and add Titik Ikat coordinates handling
e35e338 2026-08-31 feat: add Survey Location page with compass functionality
8bcac11 2026-08-31 Merge pull request #59 from asid30/main
9180506 2026-08-31 chore: update gradle properties and dependencies
a8f691e 2026-01-27 chore: Add MIT License and update copyright notice in README.md
69a6810 2026-01-27 docs: Update Mapbox access token note in README.md for clarity
f434dd6 2026-01-27 docs: Add Mapbox access token note and clarify Android device support in README.md
327af62 2026-01-27 chore: Remove developer notes section for Mapbox access token from README.md
a3c16f1 2026-01-27 docs: Update developer notes in README.md for Mapbox access token configuration
0715704 2026-01-27 feat: Add developer notes for Mapbox access token configuration in README.md
a1abf75 2026-01-27 chore: Remove screenshots section from README.md
a809474 2026-01-27 feat: Enhance README.md with improved screenshot formatting for light and dark modes
928e33b 2026-01-27 feat: Update terminology from "Cluster" to "Klaster" throughout the application and add screenshots
0bc5eee 2026-01-27 Merge pull request #57 from asid30:development
2bb37bd 2026-01-25 Merge pull request #56 from asid30:development
bd0a21b 2026-01-24 Merge pull request #53 from asid30:development
c19c2b6 2026-01-14 Merge pull request #49 from asid30:development
```

## Tindak lanjut website — 10 September 2026

Beranda, Tentang, Panduan, Template, metadata, navigasi, dan README telah diperbarui berdasarkan acuan ini. Keterangan rilis membedakan APK publik v1.1.26 dari fitur development. API GitHub mengonfirmasi rilis terbaru masih v1.1.26, diterbitkan 26 Januari 2026; belum ada APK baru yang diberikan pengguna pada saat pembaruan.

Template Google Sheets telah diunduh untuk pemeriksaan read-only: kelima sheet beserta header cocok dengan format ekspor Flutter. Impor di perangkat Android belum diuji. Halaman publik, aset, anchor panduan, dan penutupan route repositori telah diperiksa melalui HTTP lokal; sintaks PHP dan JavaScript lolos. Deployment belum dijalankan.
