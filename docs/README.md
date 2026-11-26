# Dokumentasi RDM Internal MTs Persis 23

Dokumentasi ini disusun mengikuti kerangka **Diataxis** agar seluruh keputusan arsitektur, referensi teknis, dan panduan kerja mudah ditelusuri selama pengembangan aplikasi.

## Daftar Dokumen

- **[Standar Dokumentasi](standards/documentation.md)**: Aturan format ADR, keutuhan tautan, dan standar bahasa.
- **[Catatan Keputusan Arsitektur (ADR)](adr/)**:
  - [ADR 001: Arsitektur Modular Monolith Laravel 13](adr/001-laravel-modular-monolith.md)
  - [ADR 002: Tata Kelola UI daisyUI & Anti-Slop](adr/002-aturan-ui-daisyui-dan-anti-slop.md)
  - [ADR 003: Kontrol Akses Berbasis Permission Granular](adr/003-rbac-berbasis-permission.md)
- **[Referensi Teknis](reference/)**:
  - [Konvensi Arsitektur & 1 File = 1 Responsibility](reference/architecture-conventions.md)
  - [Glosarium Domain Madrasah](reference/domain-glossary.md)
- **[Panduan Praktis (How-To)](how-to/)**:
  - [Menambah Irisan Fitur (Feature Slice)](how-to/adding-a-feature-slice.md)
  - [Alur Kerja 1 Issue = 1 Branch & PR](how-to/workflow-issue-dan-pr.md)
  - [Pengembangan Lokal (Laragon & PostgreSQL)](how-to/local-development.md)
- **[Peta Jalan Proyek (Roadmap)](ROADMAP.md)**: Urutan fase pembangunan aplikasi RDM internal.
