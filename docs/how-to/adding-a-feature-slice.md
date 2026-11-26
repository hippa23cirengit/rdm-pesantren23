# Panduan: Menambah Irisan Fitur (Feature Slice)

Ikuti urutan berikut setiap kali membangun fitur baru di dalam `app/Modules/<Modul>/`:

1. **Siapkan Issue & Branch:** Pastikan bekerja di branch `feat/issue-<N>-<slug>` sesuai panduan [Alur Kerja Issue & PR](workflow-issue-dan-pr.md).
2. **Isolasi Skema Database (Bila Ada Perubahan Tabel):** Buat migration di `database/migrations/`, Model, serta Factory dummy lebih dulu dalam PR terpisah dan jalankan `php artisan migrate`.
3. **Tulis Test Terlebih Dahulu (TDD):** Buat unit/feature test di `tests/Unit/` atau `tests/Feature/` untuk menguji aturan bisnis dan otorisasi.
4. **Buat Validasi & Otorisasi:** Tambah `FormRequest` dan `Policy` (cek permission granular, bukan nama role).
5. **Implementasikan Action (`execute()`):** Tulis logika bisnis di `app/Modules/<Modul>/Actions/<Nama>Action.php` (1 file = 1 tanggung jawab).
6. **Bangun Layer Tampilan:** Buat Single-Action Controller atau komponen Livewire tipis beserta Blade view yang memakai komponen `resources/views/components/ui/` dalam Bahasa Indonesia.
7. **Verifikasi Otomatis:** Jalankan `composer check` dan `npm run build` (atau `npm.cmd run build` di Windows) sebelum membuka Pull Request.
