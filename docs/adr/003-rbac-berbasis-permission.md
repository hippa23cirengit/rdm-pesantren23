# ADR 003: Kontrol Akses Berbasis Permission Granular

## Status

Disetujui

## Konteks

Di lingkungan madrasah, satu orang pengguna sering memegang peran ganda secara bersamaan—misalnya seorang guru mata pelajaran sekaligus menjabat sebagai wali kelas atau wakil kepala bidang kurikulum. Pemeriksaan hak akses berbasis nama role tunggal secara mentah (`hasRole('guru')` atau `$user->role === 'admin'`) rapuh dan menyulitkan saat ada tugas tambahan.

## Keputusan

1. Menggunakan otorisasi berbasis **permission granular** (contoh konvensi: `academic.periods.manage`, `grading.scores.input`, `grading.scores.submit`, `homeroom.notes.edit`, `report_cards.pdf.export`).
2. Role berfungsi sebagai pengelompokan permission yang dapat diberikan kepada pengguna melalui relasi terstruktur.
3. Melarang keras pemeriksaan nama role secara langsung di kode modul, controller, action, maupun view.
4. Seluruh aksi sensitif wajib memeriksa izin melalui **Laravel Policy / Gate** serta kepemilikan konteks tugas (misalnya guru hanya boleh menginput nilai pada rombel dan mapel yang diampunya).

## Konsekuensi

- Satu akun guru bisa merangkap jabatan tanpa perubahan logika kode bisnis.
- Hak akses per fitur lebih aman dan dapat diuji dengan jelas lewat feature test.
