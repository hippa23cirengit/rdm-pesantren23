# RDM Internal — MTs Persis 23

Aplikasi **Raport Digital Madrasah (RDM)** internal untuk **MTs Persis 23**, dirancang untuk menyederhanakan alur pengelolaan nilai guru mata pelajaran, rekap wali kelas, leger nilai, hingga cetak PDF rapor dengan alur kerja yang selaras dengan standar RDM Kemenag.

## Teknologi & Versi yang Digunakan

| Komponen | Teknologi | Versi / Syarat |
| :--- | :--- | :--- |
| Bahasa Pemrograman | PHP | **8.4** *(minimal `^8.3` di `composer.json`)* |
| Framework Backend | Laravel | **13** (`^13.17`) |
| UI Reaktif Full-Stack | Livewire | **4** (`^4.4`) + Alpine.js |
| Database | PostgreSQL | **17** *(wajib ekstensi PHP `pdo_pgsql` & `pgsql`)* |
| Styling & Komponen UI | Tailwind CSS + daisyUI | **Tailwind v4** (`^4.0`) + **daisyUI 5** (`^5.8`, tema `rdm`) |
| Asset Bundler | Vite + Laravel Vite Plugin | **Vite 8** (`^8.0`) + Plugin `^3.1` |
| Runtime Frontend | Node.js & NPM | **Node.js 22 LTS** |
| Dependency Manager PHP | Composer | **2.x** |
| Pengujian & Guardrails | Pest + Laravel Pint | **Pest 4** (`^4.7`) + **Pint** (`^1.27`) |
| Asistensi Agent | Laravel Boost | `^2.10` *(dev dependency)* |

> **Ekstensi PHP Wajib:** `pdo_pgsql`, `pgsql`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `curl`, `zip`.

## Arsitektur & Tata Kelola

- **Modular Monolith** berbasis fitur dan *use-case* di `app/Modules/<Modul>/`
- Prinsip **1 File = 1 Responsibility** dengan alur ketergantungan searah (`Route / Livewire -> FormRequest -> Action -> Model / Query -> PostgreSQL`)
- **Kontrol Akses Berbasis Permission Granular** melalui Laravel Policy/Gate
- **Tata Kelola UI Terpusat** lewat komponen `resources/views/components/ui/` dalam Bahasa Indonesia
- **Alur Git:** 1 Issue = 1 Branch (`feat/issue-<N>-<slug>`) dengan isolasi migrasi database dan *Squash and Merge* via Pull Request

## Instalasi & Menjalankan di Lokal (Laptop / PC Server)

Panduan lebih detail (termasuk pemecahan masalah Windows/Laragon dan checklist pindah ke PC server) tersedia di [Panduan Pengembangan Lokal](docs/how-to/local-development.md).

### 1. Prasyarat
Pastikan sudah terpasang:
- PHP **8.4** (dengan ekstensi `pdo_pgsql` & `pgsql` aktif di `php.ini`)
- Composer **2.x**
- PostgreSQL **17**
- Node.js **22** & NPM
- Git

### 2. Clone & Pasang Dependensi

```bash
git clone https://github.com/hippa23cirengit/rdm-pesantren23.git rdm-pesantren
cd rdm-pesantren

# Pasang dependensi PHP dan JavaScript
composer install
npm install
```

### 3. Siapkan Database PostgreSQL & File `.env`

Buat database baru di PostgreSQL bernama `rdm_pesantren` (misalnya melalui `psql` atau pgAdmin):

```sql
CREATE DATABASE rdm_pesantren;
```

Salin `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Ubah bagian koneksi database di `.env` agar mengarah ke PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=rdm_pesantren
DB_USERNAME=postgres
DB_PASSWORD=password_postgresql_anda
```

### 4. Generate Key, Migrasi Database, dan Build Aset

```bash
php artisan key:generate
php artisan migrate
npm run build
```

### 5. Menjalankan Aplikasi

- **Mode pengembangan (server Laravel + Vite watch sekaligus):**
  ```bash
  composer dev
  ```
- **Mode sederhana (pakai hasil `npm run build`):**
  ```bash
  php artisan serve
  ```
  Lalu buka `http://127.0.0.1:8000` di browser (atau gunakan Virtual Host Laragon/Nginx/Apache yang mengarah ke folder `public/`).
- **Jika dijalankan untuk jaringan lokal sekolah (PC Server):**
  Atur `APP_ENV=production` dan `APP_DEBUG=false` di `.env`, jalankan `php artisan optimize`, lalu jalankan web server (Nginx/Apache/Laragon atau `php artisan serve --host=0.0.0.0 --port=8000`) sesuai IP lokal server sekolah.

### 6. Verifikasi Kualitas & Guardrails

```bash
composer check
```

Perintah di atas menjalankan **Laravel Pint** (`--test`), **skrip guardrails 6 Hukum Mekanis** (`scripts/guardrails.php`), dan seluruh suite **Pest** (`Architecture`, `Unit`, `Feature`).

## Dokumentasi Proyek

- [Aturan Arsitektur & Agent](AGENTS.md)
- [Peta Dokumentasi (Diataxis)](docs/README.md)
- [Konvensi Arsitektur](docs/reference/architecture-conventions.md)
- [Glosarium Domain Madrasah](docs/reference/domain-glossary.md)
- [Alur Kerja 1 Issue = 1 Branch & PR](docs/how-to/workflow-issue-dan-pr.md)
- [Panduan Pengembangan Lokal & Server](docs/how-to/local-development.md)
- [Peta Jalan Proyek (Roadmap)](docs/ROADMAP.md)

## Privasi Data

Repositori ini dilarang memuat data asli siswa, guru, orang tua, NIK/NISN, nilai nyata sekolah, maupun file `.env`. Seluruh seeder dan fixture pengujian wajib menggunakan data fiktif/Faker.
