# Troubleshooting Guide - Halaman Dosen Pembimbing

## Masalah Umum dan Solusi

### 1. Button "Detail" di Halaman Mahasiswa Tidak Berfungsi

**Gejala:** Klik button "Detail" tidak membawa ke halaman detail mahasiswa

**Penyebab Umum:**
- Route tidak terdaftar dengan benar
- NIM/user_id tidak terpass dengan benar

**Solusi:**
```php
// Cek route di routes/web.php:
Route::match(['GET', 'POST'], '/mahasiswa-bimbingan/{id}', 
    [MahasiswaBimbinganController::class, 'show'])
    ->name('dospem.mahasiswa-bimbingan.show');

// Pastikan di view, button menggunakan:
<a href="{{ route('dospem.mahasiswa-bimbingan.show', ['id' => $mhs->nim ?? $mhs->id]) }}">
```

---

### 2. Modal Proposal/Bimbingan/Produksi Tidak Muncul

**Gejala:** Klik button "Tindakan" atau "ACC/Tolak" tapi modal tidak muncul

**Penyebab Umum:**
- JavaScript error di console
- Element modal hidden class tidak dihapus
- Function `openProposalModal()` atau `openAccBimbinganModal()` tidak defined

**Solusi:**
1. Buka Developer Tools (F12) → Console
2. Lihat error message yang muncul
3. Pastikan script tag lengkap di akhir file blade
4. Cek apakah JavaScript function sudah di-load sebelum dipanggil

---

### 3. Form Submit Modal Tidak Bekerja (Form Tidak Terkirim)

**Gejala:** Klik "Kirim" tapi tidak ada respons, atau alert error tanpa pesan

**Penyebab Umum:**
- CSRF token tidak terkirim
- Endpoint URL salah
- Fetch request error
- Content-Type header salah

**Solusi:**
```javascript
// Pastikan CSRF token ada:
<meta name="csrf-token" content="{{ csrf_token() }}">

// Pastikan fetch header benar:
fetch(url, {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({...})
})

// Di console, cek response:
// - Jika 419: CSRF token error
// - Jika 404: Route tidak ditemukan
// - Jika 500: Server error (cek Laravel logs)
```

---

### 4. Feedback Textarea Required/Opsional Tidak Jalan

**Gejala:** Bisa submit tanpa feedback padahal seharusnya wajib, atau sebaliknya

**Penyebab Umum:**
- Validasi JavaScript tidak berjalan
- Textarea masih punya atribut `required`
- Logika if statement salah

**Solusi:**
```javascript
// Validasi yang benar untuk produksi:
const status = document.querySelector('input[name="produksi_status"]:checked')?.value;
const feedback = document.querySelector('textarea[name="produksi_feedback"]').value;

if (!status) {
    alert('Pilih status terlebih dahulu!');
    return;
}

// Feedback wajib HANYA untuk revisi/tolak, optional untuk approve
if (status !== 'disetujui' && (!feedback.trim() || feedback.length < 5)) {
    alert('Feedback minimal 5 karakter untuk revisi/tolak!');
    return;
}
```

---

### 5. Status Tidak Berubah di Database

**Gejala:** Submit form berhasil (alert muncul) tapi status tidak berubah

**Penyebab Umum:**
- Controller method tidak di-hit
- Query tidak tepat sasaran (ID salah)
- Save() atau update() tidak dipanggil
- Relationship model salah

**Solusi:**
1. Cek Laravel logs: `tail storage/logs/laravel.log`
2. Pasang breakpoint di controller atau `dd()` untuk debug
3. Pastikan ID yang dikirim dari frontend sama dengan DB
4. Cek apakah model punya `$fillable` atau `$guarded` yang benar

```php
// Contoh debug:
public function approveBimbingan(Request $request, $id) {
    Log::info('approveBimbingan called', [
        'id' => $id,
        'request' => $request->all()
    ]);
    
    $bimbingan = Bimbingan::find($id);
    Log::info('Found bimbingan', ['data' => $bimbingan]);
    
    $bimbingan->status = 'disetujui';
    $saved = $bimbingan->save();
    Log::info('Save result', ['saved' => $saved]);
}
```

---

### 6. Kalender (FullCalendar) Tidak Menampilkan Event

**Gejala:** Halaman `/dospem/jadwal-bimbingan` kalender kosong atau error

**Penyebab Umum:**
- Endpoint `/dospem/jadwal-bimbingan/events` tidak mengambalkan data
- Data event format tidak sesuai FullCalendar
- Tidak ada data jadwal bimbingan untuk dosen ini

**Solusi:**
1. Cek endpoint dengan Postman:
   ```
   GET http://localhost/dospem/jadwal-bimbingan/events
   Header: Authorization: Bearer [token] (jika perlu)
   ```

2. Response harus berupa JSON array:
   ```json
   [
     {
       "id": 1,
       "title": "Bimbingan - Budi Santoso",
       "topik": "Bimbingan",
       "tanggal": "2025-12-01",
       "waktu_mulai": "10:00",
       "waktu_selesai": "11:00",
       "status": "pending",
       "mahasiswa_name": "Budi Santoso",
       "start": "2025-12-01T10:00",
       "end": "2025-12-01T11:00"
     }
   ]
   ```

3. Pastikan di controller, `nidn` dari `Auth::user()` ada:
   ```php
   $nidn = Auth::user()->nidn; // Harus ada, tidak null
   ```

---

### 7. Filter Status Jadwal Tidak Berfungsi

**Gejala:** Klik filter "Disetujui" atau "Ditolak" tapi list tidak berubah

**Penyebab Umum:**
- JavaScript variable `window.allJadwalEvents` belum populate
- Filter function tidak dipanggil dengan benar
- Status value tidak match

**Solusi:**
1. Di console, cek: `console.log(window.allJadwalEvents)`
2. Pastikan nilai status di DB adalah: `'pending'`, `'approved'`, atau `'rejected'`
3. Kalau dari API return nilai berbeda, mapping di JavaScript:
   ```javascript
   const statusUI = {
     'disetujui': 'approved',
     'ditolak': 'rejected',
     'pending': 'pending'
   }[statusDb];
   ```

---

### 8. Permission Denied (403) Error

**Gejala:** Submit form tapi error 403 "Anda tidak berhak"

**Penyebab:**
- NIDN dosen berbeda dengan `dosen_nidn` di bimbingan
- User tidak memiliki role 'dospem'

**Solusi:**
```php
// Cek di database:
// 1. Pastikan dosen punya nidn yang benar
SELECT id, name, nidn FROM users WHERE role_id = (SELECT id FROM roles WHERE name = 'dospem');

// 2. Pastikan bimbingan punya dosen_nidn yang match
SELECT id, dosen_nidn, nim, status FROM bimbingan WHERE dosen_nidn = 'XX.XX.XXXXX';

// 3. Atau remove permission check jika tidak diperlukan:
// Komentar conditional `if ($bimbingan->dosen_nidn && ...)` di controller
```

---

### 9. Response Berhasil Tapi Alert Tidak Muncul

**Gejala:** Form submit selesai tapi alert() tidak dipanggil

**Penyebab Umum:**
- Response.json() tidak di-await dengan benar
- Data structure tidak sesuai harapan
- Condition if untuk success salah

**Solusi:**
```javascript
// Pastikan .then() chain benar:
fetch(url, {...})
.then(res => res.json())
.then(data => {
    console.log('Response:', data); // Debug
    
    // Check semua kemungkinan success:
    if (data && (data.status === 'success' || data.success)) {
        alert('✅ ' + data.message);
    } else {
        alert('❌ ' + data.message);
    }
})
.catch(err => {
    console.error('Fetch error:', err);
});
```

---

### 10. Halaman Loading Lama atau Blank

**Gejala:** Buka `/dospem/mahasiswa-bimbingan` halaman blank atau loading lama

**Penyebab Umum:**
- Query database slow
- N+1 query problem
- View syntax error

**Solusi:**
1. Cek network tab di DevTools (F12):
   - Lihat request ke `/dospem/mahasiswa-bimbingan`
   - Lihat berapa lama response time
   - Ada error status code?

2. Gunakan `with()` untuk eager loading:
   ```php
   $mahasiswa = Mahasiswa::where('dosen_pembimbing_id', $nidn)
       ->with('user', 'projekAkhir') // Eager load
       ->get();
   ```

3. Cek Laravel logs untuk error:
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## Useful Commands untuk Debug

### Cek Database
```bash
# Masuk ke database
php artisan tinker

# Query mahasiswa
>>> $mhs = Mahasiswa::where('dosen_pembimbing_id', 'XX.XX.XXXXX')->get();
>>> $mhs->first();

# Query bimbingan
>>> $bim = Bimbingan::where('dosen_nidn', 'XX.XX.XXXXX')->get();
```

### Cek Logs
```bash
# Live logs
tail -f storage/logs/laravel.log

# Search specific error
grep "approveBimbingan" storage/logs/laravel.log
```

### Jalankan Test
```bash
# Test controller method
php artisan test --filter="approveBimbingan"

# Run single test file
php artisan test tests/Feature/BimbinganTest.php
```

---

## Frontend Debug Tips

### Buka Console (F12)
```javascript
// Cek CSRF token
document.querySelector('meta[name="csrf-token"]').content

// Cek auth user
console.log(document.body.innerText); // Cari nama user

// Cek all jadwal events
console.log(window.allJadwalEvents);

// Cek calendar instance
console.log(window.jadwalCalendar);

// Trigger filter manual
filterJadwalStatus('approved');
```

### Network Tab
- Lihat semua request/response
- Klik request yang bermasalah untuk lihat detail
- Preview tab untuk lihat response JSON
- Console akan muncul error kalau ada

---

## Contact & Support

Jika masih ada masalah yang tidak terselesaikan:
1. Gather logs dari `storage/logs/laravel.log`
2. Catat langkah-langkah untuk reproduce issue
3. Buka console (F12) dan screenshot error
4. Hubungi developer untuk investigasi lebih lanjut

