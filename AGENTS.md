# Aturan Arsitektur & Kolaborasi Agent: RDM Internal MTs Persis 23

Selamat datang. Aplikasi ini dibangun dengan **Laravel 13**, **PHP 8.4**, **PostgreSQL**, **Livewire 4**, **Tailwind CSS v4**, dan **daisyUI 5**.
Panduan teknis Laravel Boost tetap berlaku dan dapat dirujuk di `CLAUDE.md`.

## Mandat Inti Arsitektur & Alur Kerja

1. **Pola Arsitektur:** *Modular Monolith* berbasis fitur (*Feature-Driven & Use-Case Driven*) di `app/Modules/<Modul>/`.
2. **1 File = 1 Responsibility:** File ramping dan fokus satu tugas (target ≤ 150 baris untuk Controller/Action/Request/Query):
   - **Action / Use Case:** `app/Modules/<Modul>/Actions/<Nama>Action.php` (memiliki method `execute()`).
   - **Validasi Input:** `app/Modules/<Modul>/Http/Requests/<Nama>Request.php` (`FormRequest`).
   - **Controller:** Single-Action Controller (`__invoke`) atau Livewire Component fokus 1 unit UI.
   - **Data Access:** Eloquent `Models/` untuk CRUD standar; `Queries/` khusus query berat lintas tabel (seperti leger/rapor).
   - **Otorisasi:** `Policies/` berbasis *permission* granular.
3. **Alur Ketergantungan Searah:** `Route / Livewire -> FormRequest -> Action -> Model / Query -> PostgreSQL`. Dilarang menaruh logika bisnis atau query `DB::` langsung di Blade view maupun Controller.
4. **Alur Git (1 Issue = 1 Branch):**
   - Dilarang mengubah kode atau push langsung ke `main`. Setiap tugas wajib memakai issue dan branch `feat/issue-<N>-<slug>` (atau `fix/`, `chore/`), lalu masuk ke `main` lewat Pull Request (*Squash and Merge*).
   - **Isolasi Skema Database:** Perubahan migration PostgreSQL harus diselesaikan di branch/PR tersendiri sebelum fitur UI atau Action di atasnya dikerjakan.
   - **Owned Paths:** Sentuh hanya file yang relevan dengan cakupan issue; hindari refactor acak di luar tugas.
5. **TDD untuk Domain Logic:** Tulis unit/feature test yang gagal terlebih dahulu di `tests/` sebelum mengimplementasikan `Action` atau aturan bisnis penilaian.
6. **Privasi Data Madrasah:** Data asli siswa, guru, orang tua, NIK/NISN, atau nilai nyata MTs Persis 23 dilarang keras dimasukkan ke seeder, test fixture, atau commit Git. Gunakan data fiktif/Faker saja.
7. **Mandat Satu Bahasa:** Seluruh antarmuka pengguna (UI), pesan validasi, dan dokumentasi proyek (`docs/`) menggunakan **Bahasa Indonesia** yang jelas. Penamaan class, tabel database, kolom, dan identifier kode tetap menggunakan Bahasa Inggris standar Laravel.

## 6 Hukum Mekanis (`composer check`)

Seluruh perubahan wajib lulus perintah `composer check` (Pint + Pest + Guardrails):

- **Hukum 1 (Tata Kelola UI daisyUI):** Gunakan komponen terpusat `resources/views/components/ui/` (`<x-ui.button>`, `<x-ui.input>`, `<x-ui.select>`, `<x-ui.textarea>`, `<x-ui.table>`, `<x-ui.badge>`, `<x-ui.card>`, `<x-ui.modal>`, `<x-ui.alert>`) dan token tema `rdm` di `resources/css/app.css`. Dilarang menulis kontrol HTML mentah (`<button>`, `<input>`, `<select>`, `<textarea>`, `<table>`), warna hex/rgb arbitrer (`bg-[#...]`), gradient dekoratif (`bg-gradient-`), atau emoji pada UI modul—kecuali folder cetak PDF (`resources/views/pdf/`).
- **Hukum 2 (Konsistensi Bahasa UI):** Seluruh label UI, judul halaman, tombol, dan pesan validasi untuk pengguna wajib berbahasa Indonesia tanpa teks campur aduk yang tidak perlu.
- **Hukum 3 (Isolasi Rahasia & Env):** `.env` dan file kredensial lain dilarang masuk Git. `.env.example` hanya boleh berisi nilai placeholder. Penggunaan `env()` dilarang di luar direktori `config/`.
- **Hukum 4 (Isolasi Query & RBAC Granular):** Query mentah `DB::` dilarang di Blade view dan Controller/Livewire. Cek role mentah (`hasRole()`, `->role ===`) dilarang—wajib gunakan *granular permission* melalui Policy/Gate (`can()` / `authorize()`).
- **Hukum 5 (Integritas Dokumentasi & Agent):** `AGENTS.md` tidak boleh memakai frontmatter dan panjangnya maksimal **120 baris**. Setiap skill di `.agents/skills/*/SKILL.md` wajib punya frontmatter `name:` dan `description:`. Setiap ADR di `docs/adr/` wajib memuat bagian `Status`, `Konteks`, `Keputusan`, dan `Konsekuensi`.
- **Hukum 6 (Disiplin Struktur & Kualitas):** Kode PHP wajib lolos formatting `Laravel Pint`, test arsitektur Pest (`tests/Architecture/`), dan tidak meninggalkan `dd()` / `dump()` / `ray()`.

## Peta Dokumentasi

- [Peta Dokumentasi](docs/README.md): Indeks panduan Diataxis, ADR, dan roadmap proyek.
- [Konvensi Arsitektur](docs/reference/architecture-conventions.md): Tata letak modul, aturan 1 File = 1 Responsibility, dan batas layer.
- [Glosarium Domain Madrasah](docs/reference/domain-glossary.md): Padanan istilah RDM & sekolah ke penamaan kode.
- [Alur Kerja Issue & PR](docs/how-to/workflow-issue-dan-pr.md): Panduan 1 Issue = 1 Branch dan isolasi migration.
