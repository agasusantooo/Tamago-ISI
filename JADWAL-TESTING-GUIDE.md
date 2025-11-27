# 🚀 Quick Start Testing - Jadwal Bimbingan ACC/Tolak

## Prerequisites

✅ Database migration sudah berjalan
✅ All code files sudah tersedia
✅ User (dosen) sudah terdaftar dengan role 'dospem'

---

## Step-by-Step Testing Guide

### 1️⃣ **Prepare Test Data**

Sebelum testing, pastikan ada jadwal di database:

```php
// Jalankan via tinker atau Seeder
Jadwal::create([
    'nim' => '23101234',           // NIM mahasiswa aktif
    'nidn' => '0511234567',        // NIDN dosen aktif
    'tanggal' => now()->addDays(5)->format('Y-m-d'),
    'jam_mulai' => '10:00:00',
    'jam_selesai' => '11:00:00',
    'tempat' => 'Ruang Bimbingan A',
    'topik' => 'Bab 1 - Pengenalan Konsep',
    'status' => 'menunggu'
]);
```

Or use SQL:
```sql
INSERT INTO jadwal (nim, nidn, tanggal, jam_mulai, jam_selesai, tempat, topik, status, created_at, updated_at)
VALUES ('23101234', '0511234567', '2025-12-15', '10:00:00', '11:00:00', 'Ruang A', 'Topic', 'menunggu', NOW(), NOW());
```

---

### 2️⃣ **Login as Dosen**

```
1. Go to http://your-app/login
2. Username: [NIDN dosen yang ada di database]
3. Password: [password]
4. Login
```

Expected: Redirect ke dashboard

---

### 3️⃣ **Navigate to Jadwal Bimbingan**

```
1. Click "Jadwal Bimbingan" menu di sidebar
   atau access: http://your-app/dospem/jadwal-bimbingan

Expected: 
- Page terbuka menampilkan list jadwal
- Jadwal yang dibuat dosen tersebut muncul di list
- Status badge berwarna kuning (menunggu)
- Filter buttons tersedia
```

---

### 4️⃣ **Test Filter Status**

```
1. Klik filter button "Menunggu"
   Expected: Hanya jadwal dengan status 'menunggu' yang ditampilkan

2. Klik filter button "Disetujui"
   Expected: List kosong (karena belum ada jadwal disetujui)

3. Klik filter button "Semua"
   Expected: Semua jadwal ditampilkan kembali
```

---

### 5️⃣ **Test Review Modal (GET Detail)**

```
1. Klik button "Review" pada salah satu jadwal
   Expected:
   - Modal dialog terbuka
   - Tampil detail jadwal:
     * Nama mahasiswa
     * NIM
     * Tanggal & waktu
     * Tempat
     * Topik
     * Status badge

2. Modal punya button:
   - "Setujui" (green)
   - "Tolak" (red)
   - Close (X)
```

**Debug: Jika modal tidak terbuka:**
- Check browser console untuk error JavaScript
- Verify fetch endpoint working: curl http://your-app/dospem/jadwal/{id}
- Check CSRF token ada di meta tag

---

### 6️⃣ **Test Approve (Setujui)**

```
1. Dari modal detail, klik button "Setujui"
   Expected: Confirmation dialog muncul dengan pesan:
   "Apakah Anda yakin ingin menyetujui jadwal ini?"

2. Klik "Confirm Persetujuan"
   Expected:
   - Loading spinner muncul
   - Request POST ke /dospem/jadwal/{id}/approve dikirim
   - Success message muncul
   - Page refresh otomatis (1 detik)
   - List diupdate, status jadwal berubah menjadi "Disetujui" (green)

3. Klik "Batal" di konfirmasi
   Expected: Kembali ke detail view, tidak ada update
```

**Database Check:**
```sql
SELECT id, status, approved_at, approved_by FROM jadwal WHERE id = 1;
```
Expected: status='disetujui', approved_at dan approved_by terisi

---

### 7️⃣ **Test Reject (Tolak)**

```
Buat jadwal baru dengan status 'menunggu' terlebih dahulu,
atau gunakan jadwal lain yang belum diproses

1. Klik button "Review"
2. Dari modal detail, klik button "Tolak"
   Expected: Rejection confirmation dialog muncul dengan:
   - Pesan: "Apakah Anda yakin ingin menolak jadwal ini?"
   - Text area untuk alasan penolakan (optional)

3. Isi alasan (contoh: "Jadwal bentrok dengan acara penting")
4. Klik "Confirm Penolakan"
   Expected:
   - Loading spinner muncul
   - POST ke /dospem/jadwal/{id}/reject dikirim
   - Success message muncul
   - Page refresh otomatis
   - List diupdate, status jadwal berubah "Ditolak" (red)

5. (Optional) Klik "Batal"
   Expected: Kembali ke detail view
```

**Database Check:**
```sql
SELECT id, status, rejected_at, rejected_by, rejection_reason FROM jadwal WHERE id = 2;
```
Expected: status='ditolak', rejected_at dan rejected_by terisi, rejection_reason ada

---

### 8️⃣ **Test Edge Cases**

#### Test 1: Try to approve already approved jadwal
```
1. Approve jadwal (dari test 6)
2. Klik "Review" lagi pada jadwal yang sama
3. Klik "Setujui"
   Expected: Error message "Jadwal sudah diproses sebelumnya"
   Status: 400 Bad Request
```

#### Test 2: Try to access non-existent jadwal
```
1. Manually modify URL in browser console:
   openJadwalModal(9999)
   
   Expected: Error message "Jadwal tidak ditemukan"
   Status: 404 Not Found
```

#### Test 3: Filter after approval
```
1. Approve beberapa jadwal
2. Klik filter "Menunggu"
   Expected: Hanya jadwal dengan status 'menunggu' tampil
3. Klik filter "Disetujui"
   Expected: Hanya jadwal yang sudah disetujui tampil
4. Klik filter "Ditolak"
   Expected: Hanya jadwal yang ditolak tampil
```

---

## 🔍 Browser Console Debugging

**Open DevTools: F12 → Console**

### Check CSRF Token
```javascript
document.querySelector('meta[name="csrf-token"]').content
// Should return token string
```

### Test Fetch Manually
```javascript
// Test getJadwal endpoint
fetch('/dospem/jadwal/1')
    .then(r => r.json())
    .then(d => console.log(d))

// Test approve endpoint
fetch('/dospem/jadwal/1/approve', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
    }
})
    .then(r => r.json())
    .then(d => console.log(d))
```

### Check Modal Display
```javascript
// Check modal visibility
document.getElementById('jadwalModal').style.display
// Should return 'block' when open

// Check modal content
document.getElementById('modalContent').innerHTML
// Should show jadwal details
```

---

## 📊 Network Tab Inspection

**Open DevTools: F12 → Network**

### Request 1: GET /dospem/jadwal-bimbingan
```
Method: GET
Status: 200
Response: HTML page dengan Blade view
```

### Request 2: GET /dospem/jadwal/{id} (when clicking Review)
```
Method: GET
Status: 200
Response: JSON dengan structure:
{
  "success": true,
  "data": {
    "id": 1,
    "nim": "23101234",
    "nidn": "0511234567",
    "tanggal": "2025-12-15",
    ...
  }
}
```

### Request 3: POST /dospem/jadwal/{id}/approve (when approving)
```
Method: POST
Status: 200
Request Headers:
  X-CSRF-TOKEN: [token]
  X-Requested-With: XMLHttpRequest
Response: JSON
{
  "success": true,
  "message": "Jadwal bimbingan berhasil disetujui"
}
```

### Request 4: POST /dospem/jadwal/{id}/reject (when rejecting)
```
Method: POST
Status: 200
Request Body:
{
  "reason": "Alasan penolakan"
}
Request Headers:
  X-CSRF-TOKEN: [token]
Response: JSON
{
  "success": true,
  "message": "Jadwal bimbingan berhasil ditolak"
}
```

---

## 🗄️ Database Verification

### View all jadwal
```sql
SELECT 
    id, nim, nidn, tanggal, jam_mulai, jam_selesai,
    topik, status, approved_at, approved_by, 
    rejected_at, rejected_by, rejection_reason
FROM jadwal
ORDER BY created_at DESC;
```

### View specific jadwal
```sql
SELECT * FROM jadwal WHERE id = 1 \G
```

### Check relationships
```sql
-- Mahasiswa info
SELECT m.nim, m.nama, m.email 
FROM mahasiswa m 
WHERE m.nim = '23101234';

-- Dosen info
SELECT d.nidn, d.nama, d.jabatan 
FROM dosen d 
WHERE d.nidn = '0511234567';

-- User (approver) info
SELECT u.id, u.username, u.email 
FROM users u 
WHERE u.id = 1;
```

---

## ⚠️ Troubleshooting

### Issue: Modal tidak terbuka
**Solution:**
```
1. Check browser console untuk JavaScript error
2. Verify CSRF token ada di page source
3. Check network tab untuk fetch error
4. Verify route /dospem/jadwal/{id} accessible (GET request)
```

### Issue: "Jadwal tidak ditemukan"
**Solution:**
```
1. Verify jadwal entry ada di database
2. Check nim dan nidn cocok dengan data
3. Verify authenticated user memiliki jadwal dengan nidn tersebut
```

### Issue: Button tidak response
**Solution:**
```
1. Check console untuk JavaScript error
2. Verify modal element ID matches
3. Check inline onclick handler
4. Try hard refresh: Ctrl+F5
```

### Issue: Status tidak berubah setelah approve
**Solution:**
```
1. Check network response status 200
2. Verify database UPDATE query berhasil
3. Check SQL di browser console
4. Manual refresh page: F5
```

---

## ✅ Test Results Checklist

| Test | Expected | Result |
|------|----------|--------|
| List jadwal tampil | Display all jadwal | ✓ |
| Filter "Menunggu" | Show only 'menunggu' | ✓ |
| Filter "Disetujui" | Show only 'disetujui' | ✓ |
| Click Review | Modal terbuka | ✓ |
| Modal show details | All fields visible | ✓ |
| Click Setujui | Confirmation dialog | ✓ |
| Confirm approval | Status → disetujui | ✓ |
| Click Tolak | Confirmation dialog | ✓ |
| Enter reason | Text saved | ✓ |
| Confirm rejection | Status → ditolak | ✓ |
| Already approved | Error 400 | ✓ |
| Not found | Error 404 | ✓ |

---

**Tanggal Testing:** _______________  
**Tester:** _______________  
**Status:** ✅ Passed / ❌ Failed  
**Catatan:** _______________
