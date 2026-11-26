# Alur Kerja 1 Issue = 1 Branch dan Pull Request

Seluruh perubahan pada repositori ini mengikuti disiplin **1 Issue = 1 Branch** dan terinspirasi dari alur kerja `gradsun`.

## Aturan Dasar

1. **Dilarang Mengubah `main` Secara Langsung:** Branch `main` harus selalu dalam keadaan stabil dan lulus `composer check`.
2. **1 Issue = 1 Branch:** Penamaan branch mengikuti format `feat/issue-<N>-<slug>`, `fix/issue-<N>-<slug>`, atau `chore/issue-<N>-<slug>`.
3. **Owned Paths:** Setiap issue mencantumkan daftar file/folder yang menjadi cakupan perubahan agar tidak menyentuh modul lain di luar tugas.
4. **Isolasi Skema Database (Schema Isolation):** Perubahan tabel PostgreSQL (`database/migrations/`) dikerjakan dan di-merge terlebih dahulu sebagai PR terpisah sebelum branch fitur UI/Action dibangun di atasnya.
5. **Squash and Merge:** Seluruh PR ke `main` digabung menggunakan *Squash and Merge* setelah pemeriksaan CI lulus.

## Urutan Kerja dengan GitHub CLI (`gh`)

Jika sudah terhubung ke repositori GitHub dan `gh` CLI aktif:

```bash
# 1. Pastikan main terbaru
git checkout main && git pull origin main

# 2. Tandai issue & buat branch fitur
gh issue edit <N> --add-assignee @me
git checkout -b feat/issue-<N>-<slug>

# 3. Kerjakan dengan TDD lalu verifikasi
composer check

# 4. Push & buka PR ke main
git push -u origin feat/issue-<N>-<slug>
gh pr create --title "feat(scope): ringkasan perubahan" --body "Closes #<N>" --base main
```

*(Jika sedang bekerja di Git lokal sebelum terhubung ke remote GitHub, tetap gunakan branch fitur terpisah dari `main` dan jangan commit langsung ke `main`.)*
