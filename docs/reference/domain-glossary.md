# Glosarium Domain Madrasah (RDM MTs Persis 23)

Dokumen ini memetakan istilah kerja madrasah dan RDM Kemenag ke penamaan teknis di kode serta database.
*(Catatan: Istilah dan struktur final akan disesuaikan kembali setelah verifikasi login RDM sekolah dan bedah file `.xlsb` dari Kepala TU.)*

| Istilah Madrasah / RDM | Penamaan Kode / Modul | Keterangan |
| :--- | :--- | :--- |
| Tahun Pelajaran & Semester | `AcademicPeriod` (`Academic`) | Periode aktif seperti Ganjil / Genap beserta tanggal rapor |
| Kelas / Rombel | `Classroom` (`Academic`) | Rombongan belajar (misal VII-A, VIII-B, IX-A) |
| Mata Pelajaran | `Subject` (`Academic`) | Mapel umum, PAI Kemenag, dan muatan lokal/khas Persis |
| Guru / Pegawai | `Teacher` (`Academic`) | Data pendidik dan tenaga kependidikan |
| Pengampu / Tugas Mengajar | `TeachingAssignment` (`Academic`) | Pemetaan Guru X mengajar Mapel Y di Rombel Z pada periode aktif |
| Siswa / Santri | `Student` (`Students`) | Data induk siswa dan riwayat penempatan rombel |
| KKM / KKTP | `PassingGrade` (`Grading`) | Kriteria Ketuntasan Minimal / Ketercapaian Tujuan Pembelajaran |
| Nilai Harian / Sumatif / Akhir | `GradeEntry` / `FinalGrade` (`Grading`) | Komponen penilaian guru mapel beserta deskripsi capaian |
| Kirim / Kunci Nilai | `SubmitGrades` / `LockGrades` (`Grading`) | Status nilai dari `draft` ke `submitted` atau `locked` |
| Wali Kelas | `HomeroomAssignment` (`Homeroom`) | Guru yang bertanggung jawab atas satu rombel |
| Absensi (Sakit, Izin, Alpha) | `AttendanceSummary` (`Homeroom`) | Rekap ketidakhadiran per semester oleh wali kelas |
| Ekstrakurikuler & Prestasi | `ExtracurricularRecord` (`Homeroom`) | Catatan kegiatan ekstrakurikuler dan capaian siswa |
| Catatan Wali Kelas & Kenaikan | `HomeroomNote` / `PromotionDecision` | Catatan pembinaan dan keputusan naik kelas / lulus |
| Leger Nilai | `ClassroomLedger` (`ReportCards`) | Rekap nilai seluruh siswa dan seluruh mapel dalam satu rombel |
| Cetak Rapor PDF | `ReportCardExport` (`ReportCards`) | Dokumen rapor cetak per siswa atau massal satu rombel |
