# Standar Dokumentasi & Keutuhan Tautan

Dokumen ini mengatur penulisan seluruh berkas dokumentasi pada proyek **RDM Internal MTs Persis 23**.

## Kerangka Diataxis

Seluruh dokumen di dalam `docs/` dibagi menjadi empat kelompok:

1. **Panduan Praktis (`docs/how-to/`)**: Langkah operasional untuk menyelesaikan tugas tertentu.
2. **Referensi (`docs/reference/`)**: Penjelasan struktur modul, kontrak layer, dan glosarium istilah domain.
3. **Catatan Keputusan Arsitektur (`docs/adr/`)**: Catatan keputusan teknis beserta alasan dan konsekuensinya.
4. **Standar (`docs/standards/`)**: Aturan penulisan kode, pengujian, dan dokumentasi.

## Syarat Wajib Berkas ADR

Setiap berkas di `docs/adr/` wajib memiliki keempat bagian berikut secara eksplisit:

- `## Status` (Usulan / Disetujui / Usang / Digantikan)
- `## Konteks` (Latar belakang masalah dan kebutuhan)
- `## Keputusan` (Solusi yang dipilih beserta alasannya)
- `## Konsekuensi` (Dampak positif, negatif, dan hal yang perlu dijaga)

## Aturan Bahasa & Tautan

- Seluruh dokumentasi ditulis dalam **Bahasa Indonesia** yang ringkas dan konsisten.
- Tautan relatif antar dokumen wajib mengarah ke berkas yang benar-benar ada.
- Setiap blok kode wajib mencantumkan pengenal bahasa (misalnya `bash`, `php`, `css`).
