# Peta Jalan Proyek (Roadmap) RDM Internal MTs Persis 23

## Fase 0 — Pondasi Proyek & Tata Kelola *(Selesai)*
- [x] Konfigurasi Laravel 13, PHP 8.4, dan database PostgreSQL `rdm_pesantren`
- [x] Instalasi Laravel Boost, Livewire 4, Pest 4, Tailwind CSS v4, dan daisyUI 5
- [x] Tema `rdm` serta komponen UI terpusat (`resources/views/components/ui/`)
- [x] Dokumen aturan (`AGENTS.md`, `docs/`, SOP skill) & gerbang otomatis (`composer check`)
- [x] Inisialisasi Git, template Issue/PR, dan workflow CI GitHub Actions

## Fase 1 — Penyelarasan Use Case & Skema Database *(Menunggu Referensi Sekolah)*
- [ ] Review alur login & menu RDM Kemenag sekolah secara langsung
- [ ] Bedah file contoh `.xlsb` per kelas dari Kepala TU (daftar mapel, rumus nilai, leger, format rapor)
- [ ] Finalisasi skema ERD PostgreSQL & migration awal (isolasi per PR skema)

## Fase 2 — Autentikasi, RBAC Granular & Master Data
- [ ] Implementasi autentikasi pengguna dan permission granular (`AccessControl`)
- [ ] Manajemen profil madrasah, tahun ajaran/semester, rombel, mapel, dan pengampu (`Academic`)
- [ ] Manajemen data siswa & penempatan kelas + import Excel (`Students`)

## Fase 3 — Modul Penilaian Guru & Wali Kelas
- [ ] Input nilai guru mapel (web grid + import/export template) serta status kirim/kunci (`Grading`)
- [ ] Workspace wali kelas: pantau progres guru, absensi S/I/A, ekskul, prestasi, catatan (`Homeroom`)

## Fase 4 — Leger & Cetak PDF Rapor
- [ ] Generate leger nilai kelas (`ReportCards`)
- [ ] Template cetak PDF rapor siswa (per siswa & massal satu kelas) sesuai standar MTs Persis 23
