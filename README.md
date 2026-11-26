# RDM Internal — MTs Persis 23

Aplikasi **Raport Digital Madrasah (RDM)** internal untuk **MTs Persis 23**, dirancang untuk menyederhanakan alur pengelolaan nilai guru mata pelajaran, rekap wali kelas, leger nilai, hingga cetak PDF rapor dengan alur kerja yang selaras dengan standar RDM Kemenag.

## Teknologi Utama

- **Backend:** Laravel 13 & PHP 8.4
- **Database:** PostgreSQL 17
- **Frontend & UI:** Livewire 4, Alpine.js, Tailwind CSS v4, daisyUI 5 (tema kustom `rdm`)
- **Kualitas & Pengujian:** Pest 4 (termasuk Architecture Tests), Laravel Pint, dan skrip guardrails (`composer check`)

## Arsitektur & Tata Kelola

- **Modular Monolith** berbasis fitur dan *use-case* di `app/Modules/<Modul>/`
- Prinsip **1 File = 1 Responsibility** dengan alur ketergantungan searah (`Route / Livewire -> FormRequest -> Action -> Model / Query -> PostgreSQL`)
- **Kontrol Akses Berbasis Permission Granular** melalui Laravel Policy/Gate
- **Tata Kelola UI Terpusat** lewat komponen `resources/views/components/ui/` dalam Bahasa Indonesia
- **Alur Git:** 1 Issue = 1 Branch (`feat/issue-<N>-<slug>`) dengan isolasi migrasi database dan *Squash and Merge* via Pull Request

## Menjalankan Proyek Secara Lokal

Prasyarat dan langkah lengkap tersedia di [Panduan Pengembangan Lokal](docs/how-to/local-development.md).

```bash
# 1. Salin konfigurasi environment lalu sesuaikan koneksi PostgreSQL
cp .env.example .env

# 2. Pasang dependensi PHP dan frontend
composer install
npm install

# 3. Generate key & jalankan migrasi database
php artisan key:generate
php artisan migrate

# 4. Build aset Tailwind CSS v4 + daisyUI 5
npm run build

# 5. Jalankan seluruh verifikasi (Pint + Guardrails + Pest)
composer check
```

## Dokumentasi Proyek

- [Aturan Arsitektur & Agent](AGENTS.md)
- [Peta Dokumentasi (Diataxis)](docs/README.md)
- [Konvensi Arsitektur](docs/reference/architecture-conventions.md)
- [Glosarium Domain Madrasah](docs/reference/domain-glossary.md)
- [Alur Kerja 1 Issue = 1 Branch & PR](docs/how-to/workflow-issue-dan-pr.md)
- [Peta Jalan Proyek (Roadmap)](docs/ROADMAP.md)

## Privasi Data

Repositori ini dilarang memuat data asli siswa, guru, orang tua, NIK/NISN, nilai nyata sekolah, maupun file `.env`. Seluruh seeder dan fixture pengujian wajib menggunakan data fiktif/Faker.
