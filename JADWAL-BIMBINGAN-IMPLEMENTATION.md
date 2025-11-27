# Implementasi Fitur Jadwal Bimbingan - ACC/Tolak Permintaan

## 📋 Ringkasan

Implementasi fitur lengkap untuk dosen pembimbing (dospem) mengelola dan menyetujui/menolak permintaan jadwal bimbingan dari mahasiswa dengan modal dialog interaktif dan pembaruan database real-time.

## ✅ Komponen yang Telah Diimplementasikan

### 1. **Database Migration** 
**File:** `database/migrations/2025_11_27_180000_create_jadwal_bimbingan_table.php`

```php
Membuat/update table 'jadwal' dengan struktur:
- id (Primary Key)
- nim (Foreign Key → mahasiswa.nim)
- nidn (Foreign Key → dosen.nidn)
- tanggal (date)
- jam_mulai (time)
- jam_selesai (time)
- tempat (string, nullable)
- topik (text, nullable)
- status (enum: 'menunggu', 'disetujui', 'ditolak') - default 'menunggu'
- approved_at (timestamp, nullable)
- approved_by (FK → users.id, nullable)
- rejected_at (timestamp, nullable)
- rejected_by (FK → users.id, nullable)
- rejection_reason (text, nullable)
- created_at, updated_at (timestamps)
```

**Status:** ✅ Migration berhasil dijalankan (Nov 27, 2025 - 18:00 UTC)

---

### 2. **Model Jadwal** 
**File:** `app/Models/Jadwal.php`

```php
Fitur:
✅ Relationships:
   - belongsTo(Mahasiswa::class, 'nim', 'nim')
   - belongsTo(Dosen::class, 'nidn', 'nidn')
   - belongsTo(User::class, 'approved_by', 'id')
   - belongsTo(User::class, 'rejected_by', 'id')

✅ Scopes (untuk filter):
   - pending()  → where status = 'menunggu'
   - approved() → where status = 'disetujui'
   - rejected() → where status = 'ditolak'

✅ Type Casting:
   - tanggal → date
   - approved_at → datetime
   - rejected_at → datetime

✅ Fillable Fields:
   nim, nidn, tanggal, jam_mulai, jam_selesai, tempat, topik,
   status, approved_at, approved_by, rejected_at, rejected_by,
   rejection_reason
```

---

### 3. **Controller - JadwalApprovalController**
**File:** `app/Http/Controllers/Dospem/JadwalApprovalController.php`

Memiliki 4 method utama:

#### a. `getJadwal($id)` - Get Jadwal Detail (API)
```php
Route: GET /dospem/jadwal/{id}
Returns: JSON dengan data jadwal beserta relasi mahasiswa dan dosen
Response:
{
  "success": true,
  "data": {
    "id": 1,
    "nim": "23101234",
    "nidn": "0511234567",
    "tanggal": "2025-12-15",
    "jam_mulai": "10:00",
    "jam_selesai": "11:00",
    "tempat": "Ruang Bimbingan",
    "topik": "Bab 1 - Pengenalan",
    "status": "menunggu",
    "mahasiswa": { ... },
    "dosen": { ... }
  }
}
```

#### b. `approve($id)` - Setujui Jadwal
```php
Route: POST /dospem/jadwal/{id}/approve
Logic:
  1. Find jadwal by ID
  2. Check if status == 'menunggu'
  3. Update:
     - status = 'disetujui'
     - approved_at = now()
     - approved_by = auth()->id()
  4. Return JSON success
```

#### c. `reject($id, Request $request)` - Tolak Jadwal
```php
Route: POST /dospem/jadwal/{id}/reject
Request: { "reason": "Alasan penolakan..." }
Logic:
  1. Find jadwal by ID
  2. Check if status == 'menunggu'
  3. Update:
     - status = 'ditolak'
     - rejected_at = now()
     - rejected_by = auth()->id()
     - rejection_reason = $request->reason
  4. Return JSON success
```

#### d. `index()` - Get Daftar Jadwal
```php
Route: GET /dospem/jadwal-bimbingan
Logic:
  1. Get authenticated user
  2. Query all jadwal where nidn = auth()->username
  3. Load relationships (mahasiswa, dosen)
  4. Order by tanggal DESC
  5. Return view with $jadwals collection
View: resources/views/dospem/jadwal-bimbingan-new.blade.php
```

---

### 4. **Routes Configuration**
**File:** `routes/web.php` (dalam dospem middleware group)

```php
// Jadwal Approval Routes
Route::controller(\App\Http\Controllers\Dospem\JadwalApprovalController::class)
    ->prefix('jadwal')
    ->name('jadwal.')
    ->group(function () {
        Route::get('/{id}', 'getJadwal')->name('show');
        Route::post('/{id}/approve', 'approve')->name('approve');
        Route::post('/{id}/reject', 'reject')->name('reject');
    });
```

**Routes yang tersedia:**
- `GET /dospem/jadwal/{id}` → getJadwal
- `POST /dospem/jadwal/{id}/approve` → approve
- `POST /dospem/jadwal/{id}/reject` → reject
- `GET /dospem/jadwal-bimbingan` → index (menampilkan list view)

---

### 5. **Modal Dialog - Blade View**
**File:** `resources/views/dospem/modals/jadwal-approval-modal.blade.php`

Fitur:
```
✅ Modal dengan 3 state:
   1. Detail View
      - Menampilkan info lengkap jadwal
      - Nama mahasiswa, NIM, tanggal, waktu, tempat, topik
      - Button: Review, Setujui, Tolak

   2. Approval Confirmation Dialog
      - Pertanyaan konfirmasi sebelum setujui
      - Button: Konfirmasi atau Batal

   3. Rejection Confirmation Dialog
      - Pertanyaan konfirmasi sebelum tolak
      - Text area untuk alasan penolakan
      - Button: Tolak atau Batal

✅ JavaScript Functions:
   - openJadwalModal(jadwalId)
     → Fetch data dari /dospem/jadwal/{id}
     → Tampilkan modal dengan data

   - submitApproval()
     → POST ke /dospem/jadwal/{id}/approve
     → Success: refresh page

   - submitRejection()
     → POST ke /dospem/jadwal/{id}/reject
     → Success: refresh page

   - showApproveConfirmation()
     → Toggle state ke approval confirmation

   - showRejectConfirmation()
     → Toggle state ke rejection confirmation

   - cancelConfirmation()
     → Kembali ke detail view

   - filterStatus(status)
     → Filter jadwal list berdasarkan status
     → Update button appearance

✅ Styling:
   - Tailwind CSS
   - Warna status:
     * Menunggu: Yellow (#FCD34D)
     * Disetujui: Green (#34D399)
     * Ditolak: Red (#F87171)
```

---

### 6. **Main View - Jadwal Bimbingan List**
**File:** `resources/views/dospem/jadwal-bimbingan-new.blade.php`

Fitur:
```
✅ Header dengan judul dan informasi
✅ Tab Navigation (List View)
✅ Filter Buttons:
   - Semua (active by default)
   - Menunggu
   - Disetujui
   - Ditolak

✅ Jadwal List dengan loop:
   @forelse($jadwals ?? [] as $jadwal)
   
   Setiap item menampilkan:
   - Avatar mahasiswa (inisial)
   - Nama mahasiswa
   - NIM mahasiswa
   - Status badge (warna sesuai status)
   - Tanggal dan waktu
   - Tempat
   - Topik
   - Button "Review" (onclick="openJadwalModal(...)")

✅ Data Binding:
   - Real database data via $jadwals collection
   - Dynamic status coloring
   - Formatted dates

✅ Modal Inclusion:
   @include('dospem.modals.jadwal-approval-modal')
```

---

## 🔄 Workflow Lengkap

### 1. **Dosen Login dan Akses Dashboard**
```
User (dosen) login → Redirect ke /dospem/dashboard
```

### 2. **View Jadwal Bimbingan List**
```
Click "Jadwal Bimbingan" di menu → GET /dospem/jadwal-bimbingan
Controller: JadwalApprovalController@index
  ↓
  Query jadwal dari database (where nidn = dosen_nidn)
  ↓
  Render view dengan $jadwals collection
```

### 3. **Filter Jadwal (Optional)**
```
Click filter button (Semua/Menunggu/Disetujui/Ditolak)
  ↓
JavaScript: filterStatus(status)
  ↓
Filter list di client-side berdasarkan status badge
```

### 4. **Klik Button Review/Setujui/Tolak**
```
Click "Review" button → openJadwalModal(jadwalId)
  ↓
Fetch GET /dospem/jadwal/{id}
  ↓
Controller returns JSON dengan data jadwal
  ↓
Modal terbuka menampilkan detail jadwal
  ↓
Dosen bisa klik:
   - Button "Setujui" → showApproveConfirmation()
   - Button "Tolak" → showRejectConfirmation()
```

### 5. **Setujui Jadwal**
```
Click "Setujui" → showApproveConfirmation()
  ↓
Approval confirmation dialog tampil
  ↓
Click "Confirm Persetujuan" → submitApproval()
  ↓
POST /dospem/jadwal/{id}/approve
  ↓
Controller:
  1. Find jadwal
  2. Validate status == 'menunggu'
  3. Update: status='disetujui', approved_at=now(), approved_by=auth_id
  4. Return JSON success
  ↓
JavaScript: 
  - Show success message
  - Refresh page after 1 second
  ↓
Page reload → List updated dengan status baru
```

### 6. **Tolak Jadwal**
```
Click "Tolak" → showRejectConfirmation()
  ↓
Rejection confirmation dialog tampil (dengan textarea untuk alasan)
  ↓
Input alasan penolakan (optional)
  ↓
Click "Confirm Penolakan" → submitRejection()
  ↓
POST /dospem/jadwal/{id}/reject
Request body: { reason: "Alasan..." }
  ↓
Controller:
  1. Find jadwal
  2. Validate status == 'menunggu'
  3. Update: status='ditolak', rejected_at=now(), rejected_by=auth_id,
            rejection_reason=request.reason
  4. Return JSON success
  ↓
JavaScript:
  - Show success message
  - Refresh page after 1 second
  ↓
Page reload → List updated dengan status baru
```

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 10+ |
| Database | MySQL 8.0+ |
| ORM | Eloquent |
| Frontend | Blade Template Engine |
| Styling | Tailwind CSS 3+ |
| Icons | Font Awesome 6+ |
| Interactivity | Vanilla JavaScript (fetch API) |
| HTTP Method | RESTful (GET, POST) |

---

## 🔐 Security Features

✅ **Authentication**
- Routes protected dengan middleware 'auth'
- Middleware 'role:dospem' memastikan hanya dosen yang akses

✅ **CSRF Protection**
- CSRF token di meta tag: `<meta name="csrf-token">`
- Token ditambahkan ke semua POST requests

✅ **Authorization**
- Controller memastikan dosen hanya akses jadwal miliknya
- Query berdasarkan `nidn` dari authenticated user

✅ **Input Validation**
- Jadwal status di-validate sebelum update
- Hanya status 'menunggu' yang bisa di-approve/reject

---

## 📊 Database Relationships

```
Jadwal
├── has one Mahasiswa (via nim)
├── has one Dosen (via nidn)
├── has one User (approved_by) - nullable
└── has one User (rejected_by) - nullable

Mahasiswa
└── has many Jadwal

Dosen
└── has many Jadwal

User
├── has many Jadwal (as approver)
└── has many Jadwal (as rejector)
```

---

## 📝 Response Format

### Sukses Approval
```json
{
  "success": true,
  "message": "Jadwal bimbingan berhasil disetujui"
}
```

### Sukses Rejection
```json
{
  "success": true,
  "message": "Jadwal bimbingan berhasil ditolak"
}
```

### Error - Jadwal tidak ditemukan
```json
{
  "success": false,
  "message": "Jadwal tidak ditemukan"
}
Status Code: 404
```

### Error - Jadwal sudah diproses
```json
{
  "success": false,
  "message": "Jadwal sudah diproses sebelumnya"
}
Status Code: 400
```

### Error - Server error
```json
{
  "success": false,
  "message": "Error message here"
}
Status Code: 500
```

---

## 🧪 Testing Checklist

- [ ] Login sebagai dosen pembimbing
- [ ] Navigate ke halaman Jadwal Bimbingan
- [ ] Verify list menampilkan jadwal dari database
- [ ] Filter status bekerja dengan baik
- [ ] Click "Review" button membuka modal
- [ ] Modal menampilkan detail jadwal dengan benar
- [ ] Click "Setujui" → konfirmasi dialog → update database
- [ ] Verify status berubah ke "Disetujui" di list
- [ ] Click "Tolak" → konfirmasi dialog dengan reason → update database
- [ ] Verify status berubah ke "Ditolak" dan reason tersimpan
- [ ] Coba akses jadwal yang sudah di-approve → harus error 400
- [ ] Coba akses jadwal tidak ada → harus error 404

---

## 📁 File Structure

```
Tamago-ISI/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Dospem/
│   │           └── JadwalApprovalController.php ✅
│   └── Models/
│       └── Jadwal.php ✅
├── database/
│   └── migrations/
│       └── 2025_11_27_180000_create_jadwal_bimbingan_table.php ✅
├── resources/
│   └── views/
│       └── dospem/
│           ├── jadwal-bimbingan-new.blade.php ✅
│           └── modals/
│               └── jadwal-approval-modal.blade.php ✅
└── routes/
    └── web.php ✅ (updated)
```

---

## 🚀 Status Implementasi

| Komponen | Status | Catatan |
|----------|--------|---------|
| Database Migration | ✅ Selesai | Jadwal table created/updated |
| Model (Jadwal) | ✅ Selesai | All relationships & scopes |
| Controller | ✅ Selesai | 4 methods implemented |
| Routes | ✅ Selesai | 3 endpoints configured |
| Modal View | ✅ Selesai | 3 states + JavaScript |
| Main View | ✅ Selesai | Database-driven list |
| Styling | ✅ Selesai | Tailwind CSS applied |
| Testing | ⏳ Pending | Ready for manual testing |

---

## 🔗 Related Documentation

Fitur ini terkait dengan:
- User Authentication System
- Dosen Role Management
- Mahasiswa Model
- Jadwal Management

---

## 📞 Support

Untuk debugging atau pertanyaan:
1. Check error messages di browser console
2. Check Laravel logs di `storage/logs/laravel.log`
3. Verify database connection dan table structure
4. Ensure all files are in correct locations

---

**Last Updated:** November 27, 2025
**Implementation Time:** Complete  
**Tested:** Pending
