# Product Requirements Document (PRD): RDM Internal MTs Persis 23

> **Status Dokumen:** Draf Awal *(berdasarkan bedah template `RAPORT WALI KELAS 7A.xlsb` dari Kepala TU dan alur standar RDM Kemenag; menunggu verifikasi akhir dari akses RDM sekolah)*.

---

## 1. Ringkasan & Tujuan Produk

Membangun aplikasi web **Rapor Digital Madrasah (RDM) Internal** untuk **MTs Persis 23** berbasis Laravel 13 dan PostgreSQL yang:
- Menggantikan pengelolaan nilai berbasis file Excel (`.xlsb`) per kelas yang terpecah-pecah dan rawan rusak rumus.
- Mengadopsi alur kerja yang sederhana, terstruktur per peran, dan akrab bagi guru seperti **RDM Kemenag**.
- Tetap mengakomodasi kekhasan struktur mata pelajaran dan format rapor **PP. Persis (Bidgar Dikdasmen)**.

## 2. Pengguna & Peran (Multi-Role Berbasis Permission)

Satu akun pengguna dapat memegang lebih dari satu peran sekaligus (misalnya seorang guru mengampu mata pelajaran sekaligus menjabat sebagai Wali Kelas).

| Peran | Tanggung Jawab Utama |
| :--- | :--- |
| **Proktor / Kepala TU** | Mengelola profil lembaga, tahun pelajaran & semester aktif, master guru, master siswa & mutasi, rombel, daftar mapel + KKM, bobot penilaian, mapping pengampu & wali kelas, akun/permission, pantau progres, buka kunci nilai. |
| **Guru Mapel** | Melihat daftar mapel & rombel yang diampu, input nilai (web atau template Excel), melihat pratinjau nilai akhir, lalu mengirim & mengunci nilai ke wali kelas. |
| **Wali Kelas** | Memantau status kirim nilai seluruh mapel di kelasnya, melengkapi absensi semester, ekstrakurikuler, catatan wali kelas, meninjau kepribadian & kenaikan kelas, serta mengunduh leger dan PDF rapor. |
| **Mudir / Kepala Madrasah** *(Opsional)* | Memantau progres ketuntasan pengisian nilai dan melihat rekap leger kelas *(read-only)*. |

## 3. Alur Kerja Utama (End-to-End Use Case)

1. **Setup Awal Semester (`Proktor / TU`):**
   Mengatur periode aktif (`Tahun Pelajaran` + `Ganjil/Genap`), bobot komponen nilai, KKM, syarat kenaikan kelas, titimangsa rapor, master siswa/guru/mapel, serta mapping guru pengampu dan wali kelas.
2. **Penilaian Mata Pelajaran (`Guru Mapel`):**
   Mengisi nilai komponen (Harian, PTS, PAS) untuk siswa di rombel yang diampu → sistem menghitung nilai akhir & predikat → guru klik **Kirim Nilai** (status berubah menjadi terkirim/terkunci; perubahan ulang membutuhkan pembukaan kunci oleh Proktor/Wali Kelas).
3. **Rekap Wali Kelas (`Wali Kelas`):**
   Memastikan seluruh mapel telah berstatus terkirim → mengisi rekap absensi (`Sakit`, `Izin`, `Alpha`), ekstrakurikuler (maksimal 4 per siswa), catatan wali kelas, serta evaluasi ketuntasan/kenaikan kelas.
4. **Leger & Cetak Rapor (`Wali Kelas / TU`):**
   Menghasilkan **Leger Nilai Kelas** dan dokumen **PDF Rapor** (Sampul, Biodata, Prestasi Belajar, Ekskul, Kepribadian, Absensi, Catatan, Kenaikan/Kelulusan) per siswa maupun massal satu kelas.

## 4. Kebutuhan Fungsional per Modul

### 4.1 `AccessControl` — Autentikasi & Otorisasi
- Login terpusat satu pintu; menu sidebar tampil dinamis sesuai *granular permission*.
- Otorisasi kontekstual melalui Laravel `Policy` (guru hanya dapat mengubah nilai pada penugasan mengajarnya; wali kelas hanya dapat mengelola rombel binaannya).

### 4.2 `Academic` — Pengaturan Lembaga & Kurikulum
- Profil pesantren/madrasah (nama, NSM/NPSN, alamat, Mudir, Mudirul 'Am, Waka Kurikulum).
- Periode aktif (Tahun Pelajaran, Semester Ganjil/Genap, tanggal titimangsa rapor).
- Manajemen Guru, Rombel (`VII`, `VIII`, `IX`), dan Mata Pelajaran per Bidang Kajian (*Al-Ulum Asy-Syar'iyyah*, *Al-Ulum Al-Insaniyah*, *Al-Ulum Al-Kauniyah*, *Program Unggulan*) beserta KKM.
- Pengaturan bobot nilai dan syarat kenaikan kelas.

### 4.3 `Students` — Data Induk & Mutasi Santri
- Data lengkap santri (NIS, NISN, identitas, asal sekolah, data orang tua & wali).
- Penempatan siswa ke rombel per tahun pelajaran dan pencatatan mutasi keluar/pindah (siswa keluar otomatis dikecualikan dari daftar input nilai semester berikutnya).

### 4.4 `Grading` — Penilaian Guru Mapel
- Input nilai harian (`NH 1–6`), `PTS`, dan `PAS` (serta komponen sikap bila tetap diisi per mapel).
- Kalkulasi otomatis dari acuan `.xlsb`:
  - **`RPH`:** Jika terisi ≥ 2 nilai harian maka dirata-rata bulat (`ROUND(SUM / jumlah_terisi, 0)`); jika hanya 1 nilai maka langsung diambil nilainya.
  - **`HPA`:** `ROUND(((RPH * bobot_NH) + (PTS * bobot_PTS) + (PAS * bobot_PAS)) / total_bobot, 0)` *(default di template `.xlsb`: NH 70%, PTS 0%, PAS 30%)*.
  - **Predikat Mapel:** `91–100 = A` (*Baik Sekali*), `75–90 = B` (*Baik*), `60–74 = C` (*Cukup*), `40–59 = D` (*Kurang*), `< 40 = E` (*Kurang Sekali*).
- Status alur nilai: `draft` → `submitted` → `locked` (dengan fitur permintaan/pembukaan kunci).

### 4.5 `Homeroom` — Pengelolaan Wali Kelas
- Dashboard status kelengkapan nilai seluruh mapel di rombelnya.
- Input total ketidakhadiran per semester (`Sakit`, `Izin`, `Tanpa Keterangan`).
- Input maksimal 4 kegiatan ekstrakurikuler + nilai & predikat skala 10 tingkat (*Sangat Baik dan Sempurna* hingga *Sangat Kurang*).
- Pengelolaan catatan wali kelas (memilih hingga 3 butir template otomatis yang dirangkai atau teks kustom).
- Evaluasi syarat kenaikan kelas otomatis (kehadiran min `80%`, mapel belum tuntas maks `2`, nilai sikap min `70`) beserta opsi koreksi manual wali kelas.

### 4.6 `ReportCards` — Leger & Cetak PDF Rapor
- **Leger Nilai Kelas:** Rekap seluruh mapel, 6 aspek kepribadian, absensi, jumlah nilai, rata-rata, dan peringkat siswa.
- **PDF Rapor:**
  - Halaman Sampul, Petunjuk & Skala Nilai, Biodata Santri, Lembar Mutasi, dan Keterangan Lulus.
  - Halaman Prestasi Belajar: nilai per bidang kajian (angka + huruf terbilang + rata-rata kelas), jumlah nilai, rata-rata, peringkat *(dibatasi konfigurasi peringkat teratas, misal 5 besar)*, ekstrakurikuler, 6 aspek kepribadian, ketidakhadiran, catatan wali kelas, status kenaikan kelas, serta blok TTD Orang Tua/Wali, Mudir, dan Wali Kelas.

## 5. Di Luar Cakupan Awal (Non-Goals Fase Awal)

- Portal login khusus siswa/orang tua secara mandiri *(fokus awal internal staf sekolah)*.
- Sinkronisasi API otomatis ke server EMIS / RDM Kemenag pusat.
- Pengelolaan keuangan/SPP pesantren.

---

## 6. Checklist Verifikasi RDM Sekolah & Kepala TU *(Untuk Dikonfirmasi)*

Poin-poin berikut wajib dipastikan setelah meninjau akun RDM sekolah dan berdiskusi dengan Kepala TU sebelum merancang migrasi database Fase 1:

- [ ] **Format Rapor Final:** Apakah aplikasi ini menggantikan Rapor Pesantren PP. Persis (`.xlsb`), meniru format RDM Kemenag (Kurikulum Merdeka / K13), atau mendukung keduanya?
- [ ] **Metode Login Pengguna:** Apakah guru login memakai `NUPTK / NIAT / Username` seperti RDM Kemenag, atau memakai `Email`?
- [ ] **Kurikulum Aktif di RDM Sekolah:** Apakah RDM sekolah memakai mode Kurikulum Merdeka (Sumatif + SAS + Deskripsi TP + P5RA) atau K13 (Pengetahuan + Keterampilan + Sikap)?
- [ ] **Cara Input 6 Aspek Kepribadian:** Apakah *Ketaatan beribadah, Akhlaq, Kerajinan, Kedisiplinan, Kerapihan, Kebersihan* tetap diisi tiap guru di setiap mapel lalu dirata-rata seperti `.xlsb`, atau disederhanakan hanya diisi oleh Wali Kelas?
- [ ] **Download/Upload Template Excel Nilai:** Apakah guru membutuhkan fitur unduh template Excel dan unggah nilai massal seperti di RDM Kemenag?
- [ ] **Daftar Mapel Tingkat VIII & IX:** Apa saja perbedaan daftar mapel dan KKM untuk kelas VIII dan IX (karena pada `.xlsb` contoh baru terisi kelas VII)?
- [ ] **Format Export Leger & Nilai Ujian:** Apakah sheet `nujian` (nilai ujian tingkat akhir) masuk ke cakupan awal, dan apakah leger perlu export `.xlsx` selain PDF?
