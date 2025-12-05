# 🚀 Quick Start Guide - Halaman Dosen Pembimbing

## Panduan Cepat untuk Pengguna

Halaman dosen pembimbing telah diperbarui dengan integrasi database penuh. Berikut adalah panduan singkat untuk menggunakan fitur-fitur baru.

---

## 📍 Akses Halaman

### Login
1. Buka http://localhost:8000/login
2. Masukkan username dan password dosen
3. Klik "Login"

### Navigasi ke Dosen Pembimbing
- Setelah login, klik menu "Dosen Pembimbing" atau akses langsung:
  - Dashboard: http://localhost:8000/dospem/dashboard
  - Daftar Mahasiswa: http://localhost:8000/dospem/mahasiswa-bimbingan
  - Jadwal Bimbingan: http://localhost:8000/dospem/jadwal-bimbingan

---

## 👥 Menu Utama Dosen Pembimbing

1. **Dashboard** - Ringkasan statistik dan tugas
2. **Mahasiswa Bimbingan** - Daftar mahasiswa yang dibimbing
3. **Review Tugas** - Tugas mahasiswa yang perlu di-review
4. **Jadwal Bimbingan** - Kalender jadwal bimbingan
5. **Riwayat Bimbingan** - History bimbingan yang sudah selesai
6. **Profile** - Profil dosen

---

## 1️⃣ Kelola Mahasiswa Bimbingan

### Akses Halaman
Klik menu **"Mahasiswa Bimbingan"** atau buka:
```
http://localhost:8000/dospem/mahasiswa-bimbingan
```

### Yang Bisa Anda Lihat
- Daftar semua mahasiswa yang anda bimbing
- Nama mahasiswa
- Email mahasiswa
- Judul Tugas Akhir
- Progress bar
- Tanggal bimbingan terakhir

### Aksi yang Bisa Dilakukan
Klik button **"Detail"** untuk:
- Lihat detail mahasiswa
- Manage proposal
- Manage jadwal bimbingan
- Lihat history bimbingan
- Review produksi

---

## 2️⃣ Kelola Proposal Mahasiswa

### Akses
1. Di halaman **"Mahasiswa Bimbingan"**, klik button **"Detail"**
2. Di halaman detail mahasiswa, klik tab **"Pengajuan Proposal"**

### Yang Bisa Anda Lihat
- Daftar proposal yang diajukan mahasiswa
- Status proposal (Pending/Review/Revisi/Disetujui/Ditolak)
- Tanggal pengajuan
- Link download proposal (jika ada file)

### Cara Memberikan Tindakan

**Step 1: Klik Button "Tindakan"**
- Modal "Tindakan Proposal" akan terbuka

**Step 2: Pilih Status**
- 🟦 **Review** - Sedang dalam tahap review
- 🟨 **Revisi** - Mahasiswa diminta untuk revisi (wajib kasih feedback)
- 🟩 **Disetujui (ACC)** - Proposal sudah disetujui (feedback opsional)
- 🟥 **Ditolak** - Proposal ditolak (wajib kasih feedback alasan penolakan)

**Step 3: Isi Feedback (jika perlu)**
- Untuk Revisi/Tolak: Feedback WAJIB minimal 5 karakter
- Untuk Disetujui: Feedback OPSIONAL

**Step 4: Klik "Kirim Tindakan"**
- Status proposal akan berubah
- Feedback akan disimpan
- Mahasiswa akan melihat perubahan status

---

## 3️⃣ Kelola Jadwal Bimbingan

### Akses Halaman Jadwal
Klik menu **"Jadwal Bimbingan"** atau buka:
```
http://localhost:8000/dospem/jadwal-bimbingan
```

### View 1: Kalender (Jadwal Bimbingan Calendar)
- Tampil kalender dengan event jadwal bimbingan
- Event warna orange = Menunggu approval
- Event warna hijau = Sudah disetujui
- Klik event untuk lihat detail

### View 2: Daftar Permintaan
- Tampil list jadwal bimbingan yang diajukan mahasiswa
- Bisa filter berdasarkan status:
  - **Semua** - Tampil semua jadwal
  - **Menunggu** - Jadwal yang belum di-approve
  - **Disetujui** - Jadwal yang sudah di-approve
  - **Ditolak** - Jadwal yang ditolak

### Cara ACC/Tolak Jadwal

**Step 1: Klik Button "ACC" atau "Tolak"**
- Modal "Konfirmasi Jadwal Bimbingan" akan terbuka

**Step 2: Pilih Aksi**
- ✅ **Terima (ACC)** - Setujui jadwal bimbingan
- ❌ **Tolak** - Tolak jadwal bimbingan

**Step 3: Isi Alasan (jika tolak)**
- Textarea untuk alasan penolakan muncul jika pilih "Tolak"
- Alasan bersifat OPSIONAL

**Step 4: Klik "Konfirmasi"**
- Status jadwal berubah
- Kalender dan list akan ter-refresh otomatis

---

## 4️⃣ Kelola Produksi

### Akses
1. Di halaman detail mahasiswa, klik tab **"Persetujuan Produksi"**

### Yang Bisa Anda Lihat

#### Pra Produksi
- File skenario (jika sudah diupload)
- File storyboard (jika sudah diupload)
- File dokumen pendukung (jika sudah diupload)
- Status pra produksi
- Feedback dari review sebelumnya (jika ada)
- Tanggal upload

#### Produksi Akhir
- File produksi akhir (video/file karya)
- Status produksi akhir
- Feedback dari review sebelumnya (jika ada)
- Tanggal upload

### Cara Review Produksi

**Step 1: Klik Button "Review & Feedback"**
- Modal "Review Pra Produksi" atau "Review Produksi Akhir" akan terbuka

**Step 2: Pilih Status**
- 🟩 **Disetujui** - File sudah OK, bisa dilanjut (feedback opsional)
- 🟨 **Perlu Revisi** - Ada yang perlu diperbaiki (wajib kasih feedback)
- 🟥 **Ditolak** - File ditolak total (wajib kasih feedback alasan)

**Step 3: Isi Feedback (jika perlu)**
- Untuk Revisi/Tolak: Feedback WAJIB minimal 5 karakter
- Untuk Disetujui: Feedback OPSIONAL

**Step 4: Klik "Kirim Feedback"**
- Status produksi akan berubah
- Feedback akan disimpan
- Mahasiswa akan melihat perubahan

---

## 5️⃣ Lihat Riwayat Bimbingan

### Akses
1. Di halaman detail mahasiswa, klik tab **"Monitoring Progress"**

### Yang Bisa Anda Lihat
- Daftar semua bimbingan yang sudah selesai
- Tanggal dan waktu bimbingan
- Topik pembahasan
- Catatan mahasiswa
- Catatan dosen
- File pendukung (jika ada)
- Status (Selesai/Completed)

---

## ⚠️ Penting: Validasi Form

### Feedback/Catatan
- **OPSIONAL untuk status "Disetujui"**: Bisa kosong atau minimal 5 karakter
- **WAJIB untuk status "Revisi/Tolak"**: Minimal 5 karakter

### Status
- Harus memilih salah satu status sebelum submit

### Alasan Penolakan
- OPSIONAL saat tolak jadwal bimbingan
- WAJIB untuk tolak proposal/produksi (sebagai bagian dari feedback)

---

## 🎨 Indikator Status

### Warna-warna yang Digunakan

| Warna | Status | Arti |
|-------|--------|------|
| 🟨 Yellow | Pending/Menunggu | Belum ada tindakan |
| 🟩 Green | Disetujui/Approved | Sudah disetujui |
| 🟧 Orange | Revisi | Perlu diperbaiki/revisi |
| 🟥 Red | Ditolak/Rejected | Ditolak |
| ⚪ Gray | Belum Upload | Belum ada file |

---

## 💡 Tips & Trik

### 1. Gunakan Kalender untuk Melihat Jadwal Mingguan
- Klik tombol "Minggu" atau "Hari" di kalender untuk view mingguan/harian

### 2. Filter Jadwal Berdasarkan Status
- Gunakan filter button di list view untuk fokus ke jadwal tertentu

### 3. Lihat Detail Event dengan Klik Event
- Di kalender, klik event untuk lihat detail lengkap jadwal

### 4. Alasan Penolakan
- Tulis alasan yang jelas dan spesifik saat menolak proposal/produksi

### 5. Monitoring Progress
- Tab "Monitoring" berguna untuk track kemajuan bimbingan mahasiswa

---

## ❓ FAQ (Frequently Asked Questions)

### Q: Apa bedanya "Revisi" dengan "Tolak"?
**A:** 
- **Revisi**: Proposal/produksi masih bisa diperbaiki dan diajukan lagi
- **Tolak**: Proposal/produksi ditolak dan tidak bisa diperbaiki, harus buat baru

### Q: Bisa ganti tindakan yang sudah diberikan?
**A:** 
Ya, tapi Anda perlu masuk ke database admin untuk mengubah status. Hubungi admin jika perlu.

### Q: Feedback bisa dihapus setelah submit?
**A:** 
Tidak, feedback sudah tersimpan di database. Jika ingin mengubah, hubungi admin.

### Q: Mahasiswa bisa lihat feedback saya?
**A:** 
Ya, mahasiswa bisa lihat feedback di aplikasi mereka.

### Q: Kapan mahasiswa bisa lihat status yang saya berikan?
**A:** 
Langsung setelah Anda submit. Status akan ter-update real-time.

---

## 🚨 Troubleshooting Cepat

### Modal tidak muncul
- Refresh halaman (Ctrl+R atau Cmd+R)
- Buka Developer Tools (F12), lihat console ada error?

### Button tidak bisa diklik
- Pastikan form sudah pilih status
- Pastikan feedback (jika wajib) sudah diisi

### Status tidak berubah setelah submit
- Check database apakah data sudah berubah
- Refresh halaman untuk melihat perubahan terbaru
- Hubungi admin jika masalah persist

### Halaman loading lama
- Tunggu sampai selesai
- Check internet connection
- Clear browser cache (Ctrl+Shift+Delete)

---

## 📞 Bantuan & Support

Jika ada masalah atau pertanyaan:

1. **Cek Dokumentasi**: Baca file `DOSPEM-TROUBLESHOOTING.md`
2. **Chat/Email Admin**: Hubungi tim administrator
3. **Check Console**: F12 → Console, cari error message
4. **Clear Cache**: Ctrl+Shift+Delete, clear cookies dan cache

---

## 📚 Dokumentasi Lengkap

Untuk informasi lebih detail, baca file-file berikut:
- `DOSPEM-INTEGRATION-SUMMARY.md` - Ringkasan technical
- `DOSPEM-TROUBLESHOOTING.md` - Guide troubleshooting
- `TESTING-CHECKLIST.md` - Checklist testing lengkap
- `DATABASE-SCHEMA-REFERENCE.md` - Referensi database

---

## ✅ Checklist untuk Pengguna Baru

- [ ] Saya sudah login dengan akun dospem
- [ ] Saya sudah bisa akses halaman "Mahasiswa Bimbingan"
- [ ] Saya sudah bisa klik button "Detail" dan lihat detail mahasiswa
- [ ] Saya sudah bisa memberikan tindakan pada proposal
- [ ] Saya sudah bisa ACC/Tolak jadwal bimbingan
- [ ] Saya sudah bisa review produksi (pra dan akhir)
- [ ] Saya sudah paham tentang status values (approve/revisi/tolak)
- [ ] Saya sudah tahu dimana hubungi admin jika ada masalah

---

## 🎓 Training Notes

**untuk Trainer/Administrator:**

Saat training user, pastikan untuk menjelaskan:
1. ✅ Perbedaan antara Revisi dan Tolak
2. ✅ Kapan feedback wajib dan opsional
3. ✅ Bagaimana mahasiswa melihat feedback yang diberikan
4. ✅ Bagaimana update status di database
5. ✅ Tips & trik menggunakan fitur
6. ✅ Contact person untuk support

---

*Dokumen ini dibuat untuk memudahkan pengguna menggunakan fitur dosen pembimbing.*
*Terakhir diupdate: 3 Desember 2025*

🚀 **Happy Teaching & Mentoring!** 🚀

