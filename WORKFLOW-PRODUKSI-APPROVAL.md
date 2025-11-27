# Workflow Produksi Approval (Dosen → Database)

## Overview
This document outlines the complete flow of produksi approval from mahasiswa submission to dosen review and database updates.

---

## 1. Mahasiswa Submission Flow

### Step 1: Mahasiswa Upload Pra Produksi
- **Route**: `POST /mahasiswa/produksi/store-pra`
- **Controller**: `ProduksiController@storePraProduksi`
- **Process**:
  1. User uploads: `file_skenario`, `file_storyboard`, `file_dokumen_pendukung`
  2. Controller validates files (type, size)
  3. Files stored in `storage/produksi/{user_id}/`
  4. Creates/updates `Produksi` record:
     - `mahasiswa_id` = `Auth::user()->id` (user_id)
     - `proposal_id` = latest approved proposal
     - `status_pra_produksi` = `'menunggu_review'`
     - `tanggal_upload_pra` = now()
     - File paths saved

- **Database Result**:
  ```
  tim_produksi
  ├── mahasiswa_id: 5 (user_id)
  ├── proposal_id: 12
  ├── file_skenario: 'produksi/5/skenario_...'
  ├── file_storyboard: 'produksi/5/storyboard_...'
  ├── file_dokumen_pendukung: 'produksi/5/dokumen_...'
  ├── status_pra_produksi: 'menunggu_review' ← KEY STATUS
  └── tanggal_upload_pra: 2025-11-27 10:30:00
  ```

### Step 2: Mahasiswa Produksi View
- **Route**: `GET /mahasiswa/produksi`
- **Controller**: `ProduksiController@index`
- **View**: `mahasiswa/produksi.blade.php`
- **Display**:
  - Shows Pra Produksi tab with uploaded files
  - Status badge: **"Menunggu Review"** (yellow)
  - Buttons for upload/replace only if status is 'belum_upload'
  - Cannot submit Produksi Akhir until Pra is approved

---

## 2. Dosen Approval Flow

### Step 1: Dosen View Detail Mahasiswa
- **Route**: `GET /dospem/mahasiswa/{nim_or_user_id}`
- **Controller**: `MahasiswaBimbinganController@show`
- **Process**:
  1. Fetches mahasiswa by NIM or user_id
  2. Calls `MahasiswaProduksiController@getProduksiList(mahasiswa_nim)`
     - Function resolves NIM → mahasiswa record
     - Queries `Produksi::where('mahasiswa_id', mahasiswa->user_id)`
     - Returns array with produksi data
  3. Passes `$produksi` to view

- **Controller Code**:
  ```php
  $produksiController = new MahasiswaProduksiController();
  $produksi = $produksiController->getProduksiList($mahasiswa->nim);
  return view('dospem.detail-mahasiswa', compact('produksi'));
  ```

- **View Display**:
  - Tab: "Persetujuan Produksi"
  - Lists Pra Produksi section:
    - File list: skenario, storyboard, dokumen
    - Current status badge
    - Feedback (if any)
    - **Review & Feedback button** (only if `status_pra_produksi === 'menunggu_review'`)

### Step 2: Dosen Click Review & Feedback Button
- **Action**: `onclick="openProduksiModal(produksi_id, 'pra')"`
- **Modal**: Opens with title "Review Pra Produksi"
- **Form Fields**:
  - **Radio buttons** for status (Disetujui / Perlu Revisi / Ditolak)
  - **Textarea** for feedback (min 5 chars, required)

### Step 3: Dosen Submit Approval
- **Event**: Form submit with AJAX
- **JavaScript Handler**: Lines 508-557 in `detail-mahasiswa.blade.php`
- **Process**:
  1. Validates: status selected, feedback >= 5 chars
  2. Builds URL: `{{ route('dospem.produksi.pra-produksi', ['id' => produksi_id]) }}`
  3. Sends JSON POST with:
     ```json
     {
       "status": "disetujui" | "revisi" | "ditolak",
       "feedback": "Feedback text..."
     }
     ```
  4. Includes CSRF token

---

## 3. Dosen Approval API Endpoint

### Route
```
POST /dospem/produksi/{id}/pra-produksi
```

### Controller
- **Class**: `MahasiswaProduksiController`
- **Method**: `approvePraProduksi(Request $request, $id)`
- **Authorization**: Simplified (asumsikan middleware handles)

### Process
1. **Validation**:
   - Detects field name: `produksi_status` or `status`
   - Detects feedback field: `produksi_feedback` or `feedback`
   - Validates:
     - `status` ∈ ['disetujui', 'revisi', 'ditolak']
     - `feedback` is string, min 5 chars, required

2. **Database Update**:
   ```php
   $produksi->update([
       'status_pra_produksi' => 'disetujui',      // ← Updated
       'feedback_pra_produksi' => 'Feedback...',  // ← Updated
       'tanggal_review_pra' => now(),              // ← Timestamp added
   ]);
   ```

3. **Response**:
   - **Success**: JSON `{"status": "success", "message": "✅ Pra Produksi berhasil disetujui!"}`
   - **Error**: JSON `{"status": "error", "message": "..."}`

### Database Result After Approval
```
tim_produksi
├── mahasiswa_id: 5
├── proposal_id: 12
├── file_skenario: 'produksi/5/skenario_...'
├── status_pra_produksi: 'disetujui' ← UPDATED
├── feedback_pra_produksi: 'Feedback text...' ← UPDATED
├── tanggal_review_pra: 2025-11-27 11:00:00 ← UPDATED
└── tanggal_upload_pra: 2025-11-27 10:30:00
```

---

## 4. Mahasiswa View Updated Status

### Step 1: Automatic Page Reload
- **After approval**: `setTimeout(() => location.reload(), 1000)` (JavaScript, line 555)
- **Fetches**: `GET /mahasiswa/produksi` again

### Step 2: Controller Retrieves Updated Data
- **Controller**: `ProduksiController@index`
- **Query**: `Produksi::where('mahasiswa_id', $user->id)->first()`
- **Result**: Produksi record with updated `status_pra_produksi = 'disetujui'`

### Step 3: View Renders Updated Status
- **View**: `mahasiswa/produksi.blade.php`
- **Check**: `@if($produksi->status_pra_produksi === 'disetujui')`
- **Display**:
  - Status badge: **"Disetujui"** (green)
  - Feedback box shows dosen's feedback
  - **Produksi Akhir tab becomes available** ← Can now upload final product
  - Cannot edit/re-upload Pra files

---

## 5. Produksi Akhir Approval (Similar Flow)

### Mahasiswa Upload Produksi Akhir
- **Requirement**: `status_pra_produksi === 'disetujui'`
- **Upload**: File to `storage/produksi/{user_id}/`
- **Database**:
  - `status_produksi_akhir` = `'menunggu_review'`
  - `tanggal_upload_akhir` = now()

### Dosen Review & Approve
- **Route**: `POST /dospem/produksi/{id}/produksi-akhir`
- **Method**: `approveProduksiAkhir()`
- **Updates**:
  - `status_produksi_akhir` → 'disetujui'|'revisi'|'ditolak'
  - `feedback_produksi_akhir` → feedback text
  - `tanggal_review_akhir` → now()

---

## 6. Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│ MAHASISWA SIDE                                                   │
├─────────────────────────────────────────────────────────────────┤
│ 1. Upload Files (Pra Produksi)                                  │
│    ↓                                                             │
│ 2. POST /mahasiswa/produksi/store-pra                           │
│    ↓                                                             │
│ 3. Database: tim_produksi                                       │
│    ├── status_pra_produksi = 'menunggu_review' ← KEY!           │
│    ├── tanggal_upload_pra = 2025-11-27 10:30:00                 │
│    └── file_skenario, file_storyboard, ...                      │
│    ↓                                                             │
│ 4. View: Pra Produksi section shows "Menunggu Review"           │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │
│                                                                   │
├─────────────────────────────────────────────────────────────────┤
│ DOSEN SIDE                                                       │
├─────────────────────────────────────────────────────────────────┤
│ 1. Navigate: /dospem/mahasiswa/{nim}                             │
│    ↓                                                             │
│ 2. Tab: Persetujuan Produksi                                    │
│    ├── Section: Pra Produksi                                    │
│    ├── Status: "Menunggu Review"                                │
│    └── Button: "Review & Feedback"                              │
│    ↓                                                             │
│ 3. Click Review → Modal opens                                   │
│    ├── Select Status (Disetujui / Revisi / Ditolak)             │
│    ├── Type Feedback (min 5 chars)                              │
│    └── Submit                                                   │
│    ↓                                                             │
│ 4. AJAX POST /dospem/produksi/{id}/pra-produksi                │
│    {                                                             │
│      "status": "disetujui",                                     │
│      "feedback": "Skenario sudah bagus..."                      │
│    }                                                             │
│    ↓                                                             │
│ 5. Database: tim_produksi UPDATE                                │
│    ├── status_pra_produksi = 'disetujui' ← UPDATED              │
│    ├── feedback_pra_produksi = '...' ← UPDATED                  │
│    ├── tanggal_review_pra = 2025-11-27 11:00:00 ← UPDATED       │
│    └── (other fields unchanged)                                 │
│    ↓                                                             │
│ 6. Success response + page reload signal                        │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │
│                                                                   │
├─────────────────────────────────────────────────────────────────┤
│ MAHASISWA SIDE (Refreshed)                                      │
├─────────────────────────────────────────────────────────────────┤
│ 1. Page reloads: GET /mahasiswa/produksi                        │
│    ↓                                                             │
│ 2. Query: SELECT * FROM tim_produksi                            │
│          WHERE mahasiswa_id = 5 AND status_pra_produksi='disetujui'
│    ↓                                                             │
│ 3. View checks: @if($produksi->status_pra_produksi === 'disetujui')
│    ↓                                                             │
│ 4. Display:                                                      │
│    ├── Status Badge: "Disetujui" (green) ← UPDATED              │
│    ├── Feedback Box: Shows dosen's feedback ← VISIBLE            │
│    ├── Produksi Akhir Tab: Now ENABLED ← CLICKABLE              │
│    └── Pra Produksi Files: Read-only, no upload button          │
└─────────────────────────────────────────────────────────────────┘
```

---

## 7. Key Database Fields

### Status Enum Values
```
'belum_upload'      - Mahasiswa hasn't uploaded yet
'menunggu_review'   - Mahasiswa uploaded, waiting for dosen review
'disetujui'         - Dosen approved
'revisi'            - Dosen requested revision
'ditolak'           - Dosen rejected
```

### Timestamp Fields
- `tanggal_upload_pra`: When mahasiswa uploaded
- `tanggal_upload_akhir`: When mahasiswa uploaded final version
- `tanggal_review_pra`: When dosen reviewed (set at approval)
- `tanggal_review_akhir`: When dosen reviewed final (set at approval)
- `created_at`, `updated_at`: Standard Laravel timestamps

---

## 8. Testing Checklist

- [ ] Mahasiswa uploads pra produksi files
- [ ] Files stored correctly in `storage/produksi/{user_id}/`
- [ ] `tim_produksi` record created with `status_pra_produksi = 'menunggu_review'`
- [ ] Mahasiswa view shows correct status badge
- [ ] Dosen opens detail-mahasiswa page
- [ ] Persetujuan Produksi tab shows pra produksi section
- [ ] Click "Review & Feedback" opens modal
- [ ] Select status (Disetujui) and enter feedback
- [ ] Submit → AJAX POST succeeds
- [ ] Database record updated with new status and feedback
- [ ] Mahasiswa page reloads automatically
- [ ] New status reflects in mahasiswa view
- [ ] Produksi Akhir tab becomes enabled
- [ ] Mahasiswa uploads final product
- [ ] Dosen reviews and approves final
- [ ] Status updates in database
- [ ] Both pra and akhir show as approved in mahasiswa view

---

## 9. Troubleshooting

### Issue: Produksi tidak muncul di dospem view
**Cause**: `getProduksiList()` queries by wrong mahasiswa_id
**Fix**: Ensure controller receives mahasiswa object with correct `user_id`

### Issue: Modal form not submitting
**Cause**: JavaScript form submission error
**Fix**: Check console for AJAX errors, ensure CSRF token is present

### Issue: Status not updating in database
**Cause**: Route/controller method not called correctly
**Fix**: Check network tab in DevTools, verify route exists and method is correct

### Issue: Mahasiswa view not reflecting status change
**Cause**: Page not reloaded or query caching issue
**Fix**: Ensure `location.reload()` is called, clear browser cache

---

## 10. Code References

- **Mahasiswa Store**: `app/Http/Controllers/Mahasiswa/ProduksiController.php:50`
- **Dospem Approval**: `app/Http/Controllers/Dospem/MahasiswaProduksiController.php:50-95`
- **View (Dosen)**: `resources/views/dospem/detail-mahasiswa.blade.php:354-470`
- **View (Mahasiswa)**: `resources/views/mahasiswa/produksi.blade.php:1-474`
- **Model**: `app/Models/Produksi.php`
- **Routes**: `routes/web.php:214-219` (dospem) & `routes/web.php:119-125` (mahasiswa)
- **Migration**: `database/migrations/2025_10_06_030800_create_tim_produksi_table.php`
