# ADR 002: Tata Kelola UI daisyUI dan Standar Anti-Slop

## Status

Disetujui

## Konteks

Aplikasi administrasi madrasah membutuhkan tampilan kerja yang padat informasi, rapi, cepat dipindai saat guru menginput nilai satu kelas, dan konsisten di seluruh halaman. Tanpa aturan tata kelola UI yang ketat, pengembangan berbasis AI berisiko menghasilkan tampilan acak, warna berlebih, gradient dekoratif, serta class Tailwind yang berulang tidak konsisten.

## Keputusan

1. Menggunakan **Tailwind CSS v4** dan **daisyUI 5** dengan tema kustom tunggal bernama `rdm` di `resources/css/app.css` (tampilan datar `--depth: 0`, garis batas `1px` tegas, warna primer hijau madrasah yang kalem).
2. Membungkus seluruh elemen antarmuka ke komponen Blade terpusat di `resources/views/components/ui/` (`button`, `input`, `select`, `textarea`, `table`, `badge`, `card`, `modal`, `alert`).
3. Melarang pemakaian kontrol HTML mentah (`<button>`, `<input>`, `<select>`, `<textarea>`, `<table>`), warna hex/rgb arbitrer, gradient dekoratif (`bg-gradient-`), dan emoji pada tampilan modul aplikasi—dengan pengecualian khusus folder `resources/views/pdf/` untuk layout cetak rapor/leger A4.
4. Menggunakan satu bahasa antarmuka (**Bahasa Indonesia**) di seluruh label, tombol, dan pesan validasi.

## Konsekuensi

- Tampilan aplikasi seragam, profesional, dan bebas dari gaya template generik.
- Perubahan gaya visual cukup dilakukan di tema `rdm` atau komponen `components/ui/`.
- Kepatuhan komponen diperiksa secara otomatis oleh skrip guardrail pada perintah `composer check`.
