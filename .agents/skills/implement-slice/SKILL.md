---
name: implement-slice
description: Prosedur standar pengerjaan satu issue/fitur dari awal hingga PR menggunakan alur 1 Issue = 1 Branch, TDD, dan composer check
---

# SOP Implementasi Fitur (1 Issue = 1 Branch)

Skill ini menjadi panduan baku setiap kali mengerjakan issue atau irisan fitur baru pada proyek **RDM Internal MTs Persis 23**.

## Urutan Langkah Kerja

1. **Verifikasi Issue & Cakupan (*Owned Paths*):**
   - Baca detail issue (`gh issue view <N>` atau spesifikasi tugas yang diberikan).
   - Catat daftar file/folder dalam *Owned Paths* dan pastikan tidak mengubah file di luar cakupan tanpa alasan jelas.

2. **Siapkan Branch Khusus (`feat/issue-<N>-<slug>`):**
   - Pastikan berada di `main` terbaru sebelum bercabang:
     ```bash
     git checkout main && git pull origin main
     git checkout -b feat/issue-<N>-<slug>
     ```
   - Jika menggunakan GitHub Issue, tandai penugasan dan beri komentar mulai kerja di issue tersebut.

3. **Cek Isolasi Skema Database:**
   - Jika tugas mengubah tabel/kolom PostgreSQL (`database/migrations/`), pastikan perubahan skema dikerjakan dalam branch/PR skema tersendiri terlebih dahulu hingga `php artisan migrate` dan test model dasar lulus sebelum branch UI/use-case dibuat.

4. **Terapkan Siklus TDD (Red -> Green -> Refactor):**
   - Buat test unit/feature di `tests/` untuk memverifikasi aturan bisnis dan skenario gagal terlebih dahulu.
   - Jalankan test dan pastikan gagal sesuai harapan.
   - Implementasikan `FormRequest`, `Action` (`execute()`), `Policy`, `Query` (bila perlu), serta tampilan Blade/Livewire menggunakan komponen `<x-ui.*>`.
   - Pastikan seluruh test lulus hijau.

5. **Cek Privasi Data & Aturan UI:**
   - Pastikan seeder dan test fixture hanya memakai data fiktif/Faker (bukan data asli sekolah).
   - Pastikan tampilan UI berbahasa Indonesia, memakai komponen `resources/views/components/ui/`, tanpa warna hex arbitrer, tanpa gradient dekoratif, dan tanpa emoji.

6. **Jalankan Gerbang Verifikasi Otomatis:**
   ```bash
   composer check
   ```
   Jika ada perubahan CSS/JS, jalankan juga `npm run build` (atau `npm.cmd run build` di Windows).

7. **Buka Pull Request & Tautkan Issue:**
   - Push branch ke remote dan buat Pull Request ke `main` dengan keterangan `Closes #<N>`.
   - Lakukan *Squash and Merge* setelah seluruh pemeriksaan CI lulus.
