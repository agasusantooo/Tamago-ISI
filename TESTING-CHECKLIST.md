# Testing Checklist - Halaman Dosen Pembimbing

> Status: ✅ Semua komponen siap untuk testing
> Last Updated: December 3, 2025

---

## Pre-Testing Checklist

### ✅ Prerequisites
- [ ] Server Laravel berjalan (php artisan serve)
- [ ] Database terkoneksi dan sudah di-migrate
- [ ] Test data sudah di-seed (mahasiswa, bimbingan, proposal, produksi)
- [ ] User dosen login dengan akun yang memiliki:
  - Role: 'dospem'
  - NIDN: Ada dan valid
- [ ] Mailhog/email testing siap (jika menggunakan notifikasi)

### ✅ Browser Setup
- [ ] Buka Chrome/Firefox terbaru
- [ ] Clear cache/cookies
- [ ] Buka DevTools (F12)
- [ ] Network tab siap untuk monitor request
- [ ] Console tab siap untuk lihat JavaScript error

---

## Test Suite 1: Halaman Mahasiswa Bimbingan

### Test 1.1: Load Halaman Mahasiswa Bimbingan
```
URL: http://localhost:8000/dospem/mahasiswa-bimbingan
Expected: Halaman terbuka, tampil daftar mahasiswa yang dibimbing
```

**Checklist:**
- [ ] Halaman load tanpa error
- [ ] Header menampilkan "Daftar Mahasiswa"
- [ ] Stats "Mahasiswa Aktif" menampilkan angka
- [ ] Stats "Tugas Review" menampilkan angka
- [ ] Tabel berisi mahasiswa-mahasiswa yang dibimbing
- [ ] Setiap row menampilkan: Nama, Judul TA, Progress bar, Tanggal bimbingan terakhir
- [ ] Console tidak ada error (F12 → Console)

**Debug jika gagal:**
```javascript
// Di console:
console.log(document.querySelector('table'));
// Harusnya ada element <table> dengan data mahasiswa
```

---

### Test 1.2: Button "Detail" Berfungsi
```
Action: Klik button "Detail" pada salah satu mahasiswa
Expected: Terbuka halaman detail mahasiswa
```

**Checklist:**
- [ ] URL berubah ke `/dospem/mahasiswa-bimbingan/{nim}`
- [ ] Halaman detail mahasiswa terbuka
- [ ] Nama mahasiswa ditampilkan
- [ ] NIM dan email mahasiswa ditampilkan
- [ ] Judul TA ditampilkan
- [ ] Tab-tab tersedia: Proposal, Bimbingan, Monitoring, Produksi

**Debug jika gagal:**
```javascript
// Di console cek route:
console.log('Route:', window.location.href);
// Cek apakah data mahasiswa ada:
console.log(document.querySelector('h2')?.textContent);
```

---

## Test Suite 2: Detail Mahasiswa - Tab Proposal

### Test 2.1: Tampilkan Data Proposal
```
Action: Buka tab "Pengajuan Proposal" di halaman detail mahasiswa
Expected: List proposal dari mahasiswa ditampilkan
```

**Checklist:**
- [ ] Tab "Pengajuan Proposal" aktif (border biru)
- [ ] Proposal-proposal ditampilkan dalam card
- [ ] Setiap proposal menampilkan:
  - Judul proposal
  - Tanggal pengajuan
  - Status (badge warna sesuai)
  - Link "Lihat Proposal" (jika ada file)
  - Button "Tindakan" (jika status belum final)

**Debug jika gagal:**
```javascript
// Cek apakah data proposal terload:
console.log(document.querySelectorAll('[data-tab="proposal"]'));
// Cek jumlah proposal:
console.log(document.querySelectorAll('.border-rounded').length);
```

---

### Test 2.2: Buka Modal Proposal & Pilih Status
```
Action: Klik button "Tindakan" pada proposal
Expected: Modal "Tindakan Proposal" terbuka
```

**Checklist:**
- [ ] Modal muncul dengan smooth animation
- [ ] Modal title: "Tindakan Proposal"
- [ ] Detail proposal ditampilkan
- [ ] Ada 4 pilihan status:
  - [ ] Review
  - [ ] Revisi
  - [ ] Disetujui (ACC)
  - [ ] Ditolak
- [ ] Default tidak ada status yang selected
- [ ] Textarea feedback ada

**Debug jika gagal:**
```javascript
// Cek apakah modal element ada:
console.log(document.getElementById('proposalModal'));
// Cek jika hidden class:
console.log(document.getElementById('proposalModal').classList);
```

---

### Test 2.3: Submit Proposal dengan Status Approve
```
Action: 
1. Pilih radio "Disetujui (ACC)"
2. Kosongkan atau isi feedback (seharusnya optional)
3. Klik "Kirim Tindakan"

Expected: Status proposal berubah menjadi "disetujui"
```

**Checklist:**
- [ ] Klik radio "Disetujui" berhasil
- [ ] Textarea feedback bisa dikosongkan tanpa error
- [ ] Klik "Kirim Tindakan" tidak ada alert error
- [ ] Ada loading indicator (button berubah "Processing...")
- [ ] Alert success: "✅ Tindakan proposal berhasil disimpan!"
- [ ] Modal menutup
- [ ] Halaman reload, proposal status berubah ke "Disetujui"
- [ ] Check database: `UPDATE proposal SET status = 'disetujui' WHERE id = ?`

**Debug jika gagal:**
```javascript
// Monitor fetch request:
// DevTools → Network → Cari POST request ke /dospem/proposal/{id}/update-status
// Lihat response status (200 = OK, 422 = Validation error, 500 = Server error)

// Cek apakah fetch berjalan:
console.log('Submitting...');
// Lihat console message setelah submit
```

---

### Test 2.4: Submit Proposal dengan Status Revisi
```
Action: 
1. Pilih radio "Revisi"
2. Isi feedback minimal 5 karakter (wajib)
3. Klik "Kirim Tindakan"

Expected: Feedback tersimpan, status berubah menjadi "revisi"
```

**Checklist:**
- [ ] Radio "Revisi" bisa dipilih
- [ ] Textarea feedback bisa diisi
- [ ] Jika feedback kosong atau < 5 karakter: Alert error "Feedback minimal 5 karakter..."
- [ ] Jika feedback valid: Submit berhasil
- [ ] Alert success: "✅ Tindakan proposal berhasil disimpan!"
- [ ] Database status = 'revisi', feedback terisi dengan text yang dikirim

---

### Test 2.5: Submit Proposal dengan Status Tolak
```
Action: 
1. Pilih radio "Ditolak"
2. Isi feedback alasan penolakan (wajib, min 5 karakter)
3. Klik "Kirim Tindakan"

Expected: Proposal di-reject dengan alasan
```

**Checklist:**
- [ ] Radio "Ditolak" bisa dipilih
- [ ] Textarea feedback wajib dan minimal 5 karakter
- [ ] Jika feedback invalid: Alert error
- [ ] Jika valid: Submit berhasil
- [ ] Database status = 'ditolak', feedback terisi

---

## Test Suite 3: Detail Mahasiswa - Tab Bimbingan

### Test 3.1: Tampilkan Jadwal Bimbingan Pending
```
Action: Buka tab "Bimbingan"
Expected: Jadwal bimbingan yang pending ditampilkan
```

**Checklist:**
- [ ] Tab "Bimbingan" aktif
- [ ] List jadwal bimbingan ditampilkan
- [ ] Setiap jadwal menampilkan:
  - Tanggal & waktu
  - Topik bimbingan
  - Status badge
  - Button "ACC/Tolak" (untuk status pending)
- [ ] Status badge warna sesuai (yellow=pending, green=approved, red=rejected)

---

### Test 3.2: Buka Modal ACC/Tolak Bimbingan
```
Action: Klik button "ACC/Tolak" pada jadwal bimbingan
Expected: Modal konfirmasi terbuka dengan detail jadwal
```

**Checklist:**
- [ ] Modal muncul dengan smooth animation
- [ ] Modal title: "Konfirmasi Jadwal Bimbingan"
- [ ] Detail jadwal ditampilkan:
  - Tanggal & Waktu
  - Topik Bimbingan
  - Catatan Mahasiswa
- [ ] Ada 2 opsi:
  - [ ] Terima (ACC) - border hijau
  - [ ] Tolak - border merah
- [ ] Checkbox untuk alasan penolakan (hidden jika approve)

---

### Test 3.3: Approve Jadwal Bimbingan
```
Action: 
1. Pilih radio "Terima (ACC)"
2. Klik "Konfirmasi"

Expected: Jadwal bimbingan di-approve
```

**Checklist:**
- [ ] Radio "Terima" bisa dipilih
- [ ] Textarea alasan hidden
- [ ] Klik "Konfirmasi" → Loading indicator
- [ ] Alert success: "✅ Jadwal bimbingan berhasil diterima!"
- [ ] Modal menutup
- [ ] Jadwal di list berubah status menjadi "Disetujui"
- [ ] Database: `UPDATE bimbingan SET status = 'disetujui' WHERE id = ?`

---

### Test 3.4: Reject Jadwal Bimbingan
```
Action: 
1. Pilih radio "Tolak"
2. Isi textarea alasan (opsional)
3. Klik "Konfirmasi"

Expected: Jadwal bimbingan di-reject
```

**Checklist:**
- [ ] Radio "Tolak" bisa dipilih
- [ ] Textarea alasan penolakan muncul
- [ ] Textarea bisa dikosongkan atau diisi
- [ ] Klik "Konfirmasi" → Submit berhasil
- [ ] Alert success: "✅ Jadwal bimbingan berhasil ditolak."
- [ ] Jadwal status berubah menjadi "Ditolak"
- [ ] Database: `UPDATE bimbingan SET status = 'ditolak', catatan_dosen = ? WHERE id = ?`

---

## Test Suite 4: Detail Mahasiswa - Tab Monitoring

### Test 4.1: Tampilkan Riwayat Bimbingan
```
Action: Buka tab "Monitoring Progress"
Expected: Riwayat bimbingan yang sudah selesai ditampilkan
```

**Checklist:**
- [ ] Tab "Monitoring" aktif
- [ ] Tabel riwayat bimbingan ditampilkan
- [ ] Kolom: Tanggal, Topik, Catatan Mahasiswa, File, Status
- [ ] Menampilkan bimbingan dengan status 'completed', 'disetujui', atau 'selesai'
- [ ] Jumlah pertemuan ditampilkan di header

---

## Test Suite 5: Detail Mahasiswa - Tab Produksi

### Test 5.1: Tampilkan File Pra Produksi
```
Action: Buka tab "Persetujuan Produksi"
Expected: File pra produksi (skenario, storyboard, dokumen) ditampilkan
```

**Checklist:**
- [ ] Tab "Produksi" aktif
- [ ] Setiap produksi item menampilkan:
  - [ ] Status badge (yellow=menunggu, green=disetujui, orange=revisi, red=ditolak)
  - [ ] Tanggal upload
  - [ ] List file yang diupload (skenario, storyboard, dokumen pendukung)
  - [ ] Feedback dari dosen (jika ada)
  - [ ] Button "Review & Feedback" (jika status menunggu_review)

---

### Test 5.2: Buka Modal Pra Produksi & Pilih Status
```
Action: Klik button "Review & Feedback" pada pra produksi
Expected: Modal review produksi terbuka
```

**Checklist:**
- [ ] Modal muncul
- [ ] Modal title: "Review Pra Produksi"
- [ ] 3 pilihan status:
  - [ ] Disetujui (hijau)
  - [ ] Perlu Revisi (orange)
  - [ ] Ditolak (merah)
- [ ] Textarea feedback ada (opsional untuk approve)

---

### Test 5.3: Approve Pra Produksi (Tanpa Feedback)
```
Action: 
1. Pilih "Disetujui"
2. Kosongkan textarea feedback
3. Klik "Kirim Feedback"

Expected: Pra produksi di-approve tanpa feedback
```

**Checklist:**
- [ ] Feedback textarea bisa dikosongkan
- [ ] Submit berhasil tanpa error
- [ ] Alert: "✅ Pra Produksi berhasil disetujui!"
- [ ] Database: `UPDATE tim_produksi SET status_pra_produksi = 'disetujui' WHERE id = ?`
- [ ] Status badge berubah menjadi hijau "Disetujui"

---

### Test 5.4: Revisi Pra Produksi (Dengan Feedback Wajib)
```
Action: 
1. Pilih "Perlu Revisi"
2. Isi feedback (min 5 karakter)
3. Klik "Kirim Feedback"

Expected: Pra produksi di-revisi dengan feedback
```

**Checklist:**
- [ ] Radio "Perlu Revisi" bisa dipilih
- [ ] Textarea feedback wajib (min 5 karakter)
- [ ] Jika < 5 karakter: Alert error
- [ ] Jika valid: Submit berhasil
- [ ] Alert: "⚠️ Feedback revisi telah dikirim ke mahasiswa."
- [ ] Database: status = 'revisi', feedback terisi

---

### Test 5.5: Tolak Pra Produksi
```
Action: 
1. Pilih "Ditolak"
2. Isi alasan penolakan (min 5 karakter)
3. Klik "Kirim Feedback"

Expected: Pra produksi ditolak dengan alasan
```

**Checklist:**
- [ ] Radio "Ditolak" bisa dipilih
- [ ] Feedback wajib (min 5 karakter)
- [ ] Submit berhasil
- [ ] Alert: "❌ Pra Produksi ditolak."
- [ ] Database: status = 'ditolak', feedback terisi

---

### Test 5.6: Approve Produksi Akhir (Sama dengan Pra Produksi)
```
Action: Klik "Review & Feedback" pada produksi akhir
Expected: Modal review produksi akhir, sama proses dengan pra
```

**Checklist:**
- [ ] Modal title: "Review Produksi Akhir"
- [ ] Semua proses sama dengan pra produksi
- [ ] Database field: `status_produksi_akhir`, `feedback_produksi_akhir`

---

## Test Suite 6: Halaman Jadwal Bimbingan

### Test 6.1: Load Halaman Jadwal Bimbingan
```
URL: http://localhost:8000/dospem/jadwal-bimbingan
Expected: Halaman terbuka dengan kalender atau list view
```

**Checklist:**
- [ ] Halaman load tanpa error
- [ ] 2 tab tersedia: "Kalender" dan "Daftar Permintaan"
- [ ] Tab "Kalender" aktif by default
- [ ] FullCalendar terbuka dengan bulan saat ini

---

### Test 6.2: Kalender Menampilkan Event
```
Action: Lihat kalender di tab "Kalender"
Expected: Event jadwal bimbingan ditampilkan
```

**Checklist:**
- [ ] Kalender render dengan benar
- [ ] Event bimbingan ditampilkan sebagai colored blocks
- [ ] Event warna: orange=pending, hijau=approved
- [ ] Klik event → Modal terbuka dengan detail
- [ ] Next/Prev button bekerja untuk navigasi bulan

**Debug jika gagal:**
```javascript
// Cek apakah calendar instance ada:
console.log(window.jadwalCalendar);
// Cek events data:
console.log(window.allJadwalEvents);
```

---

### Test 6.3: Switch ke Tab Daftar Permintaan
```
Action: Klik tab "Daftar Permintaan"
Expected: List view jadwal ditampilkan
```

**Checklist:**
- [ ] Tab "Daftar Permintaan" menjadi aktif
- [ ] List jadwal bimbingan ditampilkan sebagai card/row
- [ ] Setiap item menampilkan:
  - Nama mahasiswa
  - Status badge
  - Tanggal & waktu
  - Topik bimbingan
  - Button ACC/Tolak (jika pending) atau Lihat (jika approved/rejected)

---

### Test 6.4: Filter Jadwal Berdasarkan Status
```
Action: 
1. Klik button filter "Menunggu"
2. Lihat list ter-update
3. Klik filter "Disetujui", "Ditolak", "Semua"

Expected: List ter-filter sesuai status
```

**Checklist:**
- [ ] Tombol "Semua" - tampil semua jadwal
- [ ] Tombol "Menunggu" - tampil hanya pending
- [ ] Tombol "Disetujui" - tampil hanya approved
- [ ] Tombol "Ditolak" - tampil hanya rejected
- [ ] Button styling update (yang active jadi blue)
- [ ] List instantly update sesuai filter

---

### Test 6.5: ACC/Tolak dari List View
```
Action: Klik button "ACC" atau "Tolak" pada list item
Expected: Modal ACC/tolak terbuka (sama dengan detail mahasiswa)
```

**Checklist:**
- [ ] Button ACC → Modal terbuka dengan radio "Terima"
- [ ] Button Tolak → Modal terbuka dengan radio "Tolak" + textarea alasan
- [ ] Detail jadwal ditampilkan dengan benar
- [ ] Submit modal → Status berubah, list dan kalender ter-refresh

---

## Test Suite 7: API & Database Integration

### Test 7.1: Verify Database Updates
```
Action: Setelah submit form, cek database
Expected: Data berubah sesuai request
```

**Checklist:**
Buka database client (phpMyAdmin/DBeaver) dan cek:

**Untuk Proposal:**
```sql
SELECT id, status, feedback, tanggal_review FROM proposal WHERE id = ?;
-- status harus berubah, feedback terisi, tanggal_review updated
```

**Untuk Bimbingan:**
```sql
SELECT id, status, catatan_dosen FROM bimbingan WHERE id = ?;
-- status berubah, catatan_dosen terisi jika ada alasan
```

**Untuk Produksi:**
```sql
SELECT id, status_pra_produksi, feedback_pra_produksi, tanggal_review_pra 
FROM tim_produksi WHERE id = ?;
-- Status dan feedback terisi, timestamp updated
```

---

### Test 7.2: Verify No Data Loss
```
Action: Submit form berulang kali dengan data berbeda
Expected: Data lama ter-overwrite dengan yang baru
```

**Checklist:**
- [ ] Submit proposal status "revisi" dengan feedback "Revisi pertama"
- [ ] Cek database feedback = "Revisi pertama"
- [ ] Submit lagi status "disetujui" tanpa feedback
- [ ] Cek database feedback masih "Revisi pertama" atau kosong (sesuai logic)
- [ ] Status terakhir adalah "disetujui"

---

## Test Suite 8: Error Handling

### Test 8.1: Submit dengan Data Kosong
```
Action: 
- Buka modal
- Klik submit tanpa pilih status
Expected: Alert error, form tidak tersubmit
```

**Checklist:**
- [ ] Alert: "Pilih status terlebih dahulu!"
- [ ] Form tidak di-submit
- [ ] Status tidak berubah di database
- [ ] Modal tetap terbuka

---

### Test 8.2: Submit Tanpa Feedback (untuk revisi/tolak)
```
Action: 
- Pilih "Revisi"
- Kosongkan feedback
- Klik submit
Expected: Alert error
```

**Checklist:**
- [ ] Alert: "Feedback minimal 5 karakter untuk status revisi/tolak!"
- [ ] Form tidak tersubmit
- [ ] Modal tetap terbuka

---

### Test 8.3: Network Error Handling
```
Action: 
- Disconnect internet
- Buka modal, submit form
Expected: Proper error message
```

**Checklist:**
- [ ] Alert error muncul (bukan blank)
- [ ] Button kembali ke state normal
- [ ] Modal tetap terbuka untuk retry
- [ ] Console tidak ada unhandled Promise rejection

---

### Test 8.4: Server Error (500)
```
Cara trigger: Hapus sementara salah satu field di model, submit
Expected: Error 500 ditangani dengan baik
```

**Checklist:**
- [ ] Alert error: "❌ Gagal memproses: ..."
- [ ] Pesan error deskriptif
- [ ] Button state normal
- [ ] Modal tetap terbuka

---

## Test Suite 9: Performance & UX

### Test 9.1: Halaman Load Time
```
Action: Buka halaman `/dospem/mahasiswa-bimbingan`
Expected: Load < 2 detik
```

**Checklist:**
- [ ] Network tab: Request selesai < 2 detik
- [ ] Tidak ada visual janky/lag
- [ ] Data langsung tampil

---

### Test 9.2: Modal Animation
```
Action: Buka modal proposal, production, bimbingan
Expected: Modal muncul dengan smooth animation
```

**Checklist:**
- [ ] Modal fade in, tidak instant appear
- [ ] Backdrop fade in smooth
- [ ] Modal close juga smooth
- [ ] Tidak ada jank/stutter

---

### Test 9.3: Button Loading State
```
Action: Klik submit button → tunggu 1 detik
Expected: Button menampilkan loading state
```

**Checklist:**
- [ ] Button text berubah jadi "Processing..."
- [ ] Icon spinner muncul
- [ ] Button disabled (tidak bisa diklik 2x)
- [ ] Setelah selesai, button state kembali normal

---

## Test Suite 10: Responsive Design

### Test 10.1: Desktop View (1920x1080)
```
Action: Buka di desktop/laptop
Expected: Layout proper, tidak ada horizontal scroll
```

**Checklist:**
- [ ] Sidebar visible
- [ ] Main content tidak crowded
- [ ] Tabel readable dengan semua kolom
- [ ] Modal centered, tidak cutoff

---

### Test 10.2: Tablet View (768x1024)
```
Action: Buka DevTools, set ke iPad size
Expected: Layout responsive
```

**Checklist:**
- [ ] Sidebar bisa di-hide/toggle
- [ ] Tabel tetap readable (mungkin horizontal scroll OK)
- [ ] Modal full width dengan padding
- [ ] Button readable dan clickable

---

### Test 10.3: Mobile View (375x667)
```
Action: Set ke iPhone size
Expected: Mobile-friendly layout
```

**Checklist:**
- [ ] Sidebar hidden/hamburger menu
- [ ] Main content full width
- [ ] Tabel scrollable horizontal
- [ ] Modal full screen atau nearly full screen
- [ ] Button big enough to click

---

## Final Checklist Sebelum Go-Live

- [ ] Semua test suite lulus (100% green)
- [ ] Tidak ada console error (F12 → Console)
- [ ] Database sudah backup sebelum testing
- [ ] Permission/role yang benar sudah di-set
- [ ] Email/notification (jika ada) sudah tested
- [ ] Documentation sudah updated
- [ ] Troubleshooting guide sudah dibuat
- [ ] Team sudah di-brief tentang fitur baru
- [ ] Monitoring setup untuk production errors

---

## Sign-Off

**Tested By:** ___________________
**Date:** ___________________
**Status:** [ ] Lulus Semua [ ] Ada Issues (lihat list di bawah)

### Issues Found:
```
1. ...
2. ...
3. ...
```

**Resolved By:** ___________________
**Date:** ___________________

