# ADR 001: Arsitektur Modular Monolith Laravel 13

## Status

Disetujui

## Konteks

Aplikasi RDM internal MTs Persis 23 mencakup banyak area bisnis yang saling terhubung (manajemen tahun ajaran, siswa, penilaian guru mapel, rekap wali kelas, leger, dan cetak PDF rapor). Jika seluruh controller, action, dan model ditumpuk di folder global tanpa batas modul, kode cepat membesar dan rawan merusak fitur lain saat pengembangan berbasis agent.

## Keputusan

1. Menerapkan arsitektur **Modular Monolith** berbasis fitur (*Feature-Driven & Use-Case Driven*) di `app/Modules/<Modul>/`.
2. Mengadopsi prinsip **1 File = 1 Responsibility** secara idiomatis Laravel:
   - Controller Single-Action (`__invoke`) atau komponen Livewire terfokus.
   - Validasi input dipisah ke `FormRequest`.
   - Logika bisnis dipisah ke kelas `Action` dengan method `execute()`.
   - Akses data memakai `Eloquent Model` untuk operasi standar dan kelas `Queries/` khusus untuk query berat lintas tabel.
3. Menjaga alur ketergantungan searah: `Presentation -> FormRequest -> Action -> Model/Query -> Database`.

## Konsekuensi

- Batas tiap domain bisnis jelas dan mudah diaudit per file kecil.
- Perubahan pada satu modul (misalnya rumus nilai) tidak mencemari modul lain.
- Membutuhkan disiplin konvensi dan pengecekan otomatis melalui test arsitektur di `tests/Architecture/`.
