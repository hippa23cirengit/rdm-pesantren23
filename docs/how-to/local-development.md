# Panduan Pengembangan Lokal & Pemindahan ke PC Server

Panduan ini berisi langkah lengkap untuk menjalankan **RDM Internal MTs Persis 23** di komputer lokal maupun saat memindahkan aplikasi ke PC server sekolah.

## 1. Spesifikasi Lingkungan Wajib

- **PHP:** `8.4` *(minimal `^8.3` sesuai `composer.json`)*
  - Ekstensi wajib aktif di `php.ini`: `pdo_pgsql`, `pgsql`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `curl`, `zip`.
- **Composer:** `2.x`
- **Database:** PostgreSQL `17.x` (port default `5432`)
- **Node.js & NPM:** Node.js `22.x` (untuk build aset Tailwind CSS v4 + daisyUI 5 via Vite 8)
- **Git:** `2.x`

Verifikasi cepat di terminal:

```bash
php -v
php -m | grep -i pgsql
composer -V
node -v
npm -v
```

*(Di Windows PowerShell, gunakan `php -m | Select-String pgsql`. Jika menggunakan Laragon dan `node`/`npm` belum ada di `PATH`, arahkan ke folder biner Node.js Laragon, misalnya `C:\laragon\bin\nodejs\node-v22`, dan gunakan `npm.cmd` bila `npm.ps1` terblokir ExecutionPolicy.)*

## 2. Langkah Instalasi dari Nol

```bash
# 1. Clone repositori
git clone https://github.com/hippa23cirengit/rdm-pesantren23.git rdm-pesantren
cd rdm-pesantren

# 2. Pasang paket PHP & JS
composer install
npm install

# 3. Salin konfigurasi environment
cp .env.example .env
```

### Buat Database & Konfigurasi `.env`

Buat database `rdm_pesantren` di PostgreSQL:

```sql
CREATE DATABASE rdm_pesantren;
```

Lalu sesuaikan bagian database pada `.env`:

```env
APP_NAME="RDM MTs Persis 23"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=rdm_pesantren
DB_USERNAME=postgres
DB_PASSWORD=password_postgresql_anda
```

### Inisialisasi Aplikasi & Build Aset

```bash
php artisan key:generate
php artisan migrate
npm run build
```

## 3. Menjalankan Aplikasi di Lokal

- **Pengembangan aktif (Laravel server + queue + log + Vite HMR):**
  ```bash
  composer dev
  ```
- **Server PHP standar (memakai aset hasil `npm run build`):**
  ```bash
  php artisan serve
  ```
- **Verifikasi seluruh aturan & test:**
  ```bash
  composer check
  ```

## 4. Checklist Saat Memindahkan ke PC Server Sekolah

1. Pastikan PC server sudah memasang **PHP 8.4** (dengan `pdo_pgsql` & `pgsql` aktif), **Composer 2**, **PostgreSQL 17**, dan **Node.js 22**.
2. Clone proyek, jalankan `composer install --no-dev --optimize-autoloader` (atau `composer install` jika juga dipakai testing di server), lalu `npm install && npm run build`.
3. Buat `.env` baru khusus server (jangan menyalin `.env` dev begitu saja), isi kredensial PostgreSQL server, dan atur:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=http://<IP-atau-domain-server-sekolah>`
4. Jalankan:
   ```bash
   php artisan key:generate
   php artisan migrate --force
   php artisan storage:link
   php artisan optimize
   ```
5. Arahkan Document Root web server (Nginx / Apache / Laragon) ke folder `public/` aplikasi (bukan ke root folder proyek).
