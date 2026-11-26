# Konvensi Arsitektur & Kontrak Layer

Dokumen ini menjelaskan tata letak **Modular Monolith** dan aturan **1 File = 1 Responsibility** pada proyek **RDM Internal MTs Persis 23**.

## Struktur Modul (`app/Modules/<Modul>/`)

Setiap domain bisnis hidup di modulnya sendiri agar batas tanggung jawab tetap tegas:

```text
app/
  Modules/
    AccessControl/     # Role & permission granular
    Academic/          # Tahun pelajaran, semester, rombel, mapel, pengampu
    Students/          # Data induk siswa & penempatan kelas
    Grading/           # Input nilai guru, hitung nilai akhir, kirim/kunci
    Homeroom/          # Absensi, ekskul, prestasi, catatan wali kelas
    ReportCards/       # Leger nilai & cetak PDF rapor
  Shared/              # Kontrak & helper lintas modul
```

Di dalam satu modul, file diorganisasikan berdasarkan tanggung jawab tunggalnya:

1. **Controllers (`Http/Controllers/*Controller.php`)** — target ≤ 100 baris
   - Gunakan *Single-Action Controller* (`__invoke`) atau controller tipis.
   - Menerima `FormRequest`, memanggil satu `Action`, lalu mengembalikan response/view.
   - Dilarang berisi rumus bisnis atau query `DB::` langsung.
2. **Livewire Components (`Livewire/*.php`)** — target ≤ 150 baris
   - Satu komponen fokus untuk satu unit interaksi UI (misalnya tabel input nilai atau modal konfirmasi).
   - Validasi input tetap jelas dan aksi penulisan bisnis didelegasikan ke `Action::execute()`.
3. **Requests (`Http/Requests/*Request.php`)** — target ≤ 100 baris
   - Satu `FormRequest` untuk satu aksi/endpoint, dengan pesan error validasi berbahasa Indonesia.
4. **Actions / Use Cases (`Actions/*Action.php`)** — target ≤ 150 baris
   - **1 File = 1 Use Case**, memiliki satu method publik utama `execute(...)`.
   - Menampung logika bisnis, transaksi database, dan aturan domain.
5. **Models (`Models/*.php`)**
   - Mewakili satu tabel dan relasi/cast Eloquent-nya. Dilarang menaruh logika alur kerja bisnis berat di dalam Model.
6. **Queries (`Queries/*Query.php`)** — target ≤ 150 baris
   - Digunakan khusus untuk query pembacaan kompleks lintas tabel (misalnya Leger satu kelas atau rekap ketuntasan seluruh mapel). CRUD sederhana cukup memakai Eloquent Model.
7. **Policies (`Policies/*Policy.php`)**
   - Mengatur otorisasi berbasis permission granular dan kepemilikan tugas mengajar/wali kelas.

## Alur Ketergantungan Searah

```text
Route / Controller / Livewire
  ↓
FormRequest (Validasi & Otorisasi)
  ↓
Action (Logika Bisnis / Use Case)
  ↓
Eloquent Model / Query Class
  ↓
PostgreSQL
```
