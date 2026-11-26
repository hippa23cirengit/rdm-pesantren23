# Panduan Pengembangan Lokal (Laragon & PostgreSQL)

## Prasyarat Lingkungan

- PHP `8.4+` dengan ekstensi `pgsql` dan `pdo_pgsql` aktif.
- Composer `2.x`.
- PostgreSQL `17.x` berjalan di `127.0.0.1:5432` dengan database `rdm_pesantren`.
- Node.js `22.x` & NPM (pada instalasi Laragon Windows, biner tersedia di `C:\laragon\bin\nodejs\node-v22`).

## Menjalankan Proyek

```bash
# Migrasi database PostgreSQL
php artisan migrate

# Build aset Tailwind CSS v4 + daisyUI 5
npm run build

# Jalankan seluruh gerbang verifikasi (Pint + Architecture + Tests)
composer check
```

*(Catatan Windows PowerShell: bila `npm` terblokir ExecutionPolicy untuk `npm.ps1`, gunakan `npm.cmd run build` atau `npm.cmd run dev`.)*
