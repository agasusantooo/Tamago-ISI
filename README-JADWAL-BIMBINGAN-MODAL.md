# Jadwal Bimbingan Modal Component

## Deskripsi
Livewire component yang menyediakan modal dialog untuk dosen pembimbing melakukan ACC (approval) atau tolak jadwal bimbingan dari mahasiswa.

## File-File
1. **Component**: `app/Livewire/Dospem/JadwalBimbinganModal.php`
2. **View**: `resources/views/livewire/dospem/jadwal-bimbingan-modal.blade.php`

## Fitur Utama
✅ Tampilkan detail jadwal bimbingan  
✅ ACC (setujui) jadwal dengan 1 klik  
✅ Tolak jadwal dengan alasan penolakan (opsional)  
✅ Konfirmasi dialog sebelum action  
✅ Notifikasi success/error  
✅ Responsive & modern UI  
✅ Triggered via event listener  

## Cara Implementasi

### 1. Tambahkan Component ke View
```blade
<!-- Di akhir view jadwal bimbingan -->
<livewire:dospem.jadwal-bimbingan-modal />
```

### 2. Trigger Modal dari List Item
```blade
<!-- Button untuk buka modal -->
<button wire:click="$dispatch('openJadwalModal', { jadwalId: {{ $jadwal->id }} })" 
        class="px-3 py-2 text-xs bg-blue-600 text-white rounded">
    <i class="fas fa-check-circle mr-1"></i>Review
</button>
```

### 3. Hubungkan dengan Route
Di `routes/web.php`, pastikan sudah ada:
```php
Route::post('/bimbingan/{id}/approve', [MahasiswaBimbinganController::class, 'approveBimbingan'])->name('bimbingan.approve');
Route::post('/bimbingan/{id}/reject', [MahasiswaBimbinganController::class, 'rejectBimbingan'])->name('bimbingan.reject');
```

## Database Schema
Pastikan tabel `jadwal` memiliki kolom:
```sql
- id (primary key)
- mahasiswa_id (foreign key)
- dosen_id (foreign key)
- tanggal (date)
- jam_mulai (time)
- jam_selesai (time)
- tempat (string)
- topik (text)
- status (enum: 'menunggu', 'disetujui', 'ditolak')
- approved_at (timestamp, nullable)
- approved_by (unsigned biginteger, nullable) -> FK ke users
- rejected_at (timestamp, nullable)
- rejected_by (unsigned biginteger, nullable) -> FK ke users
- rejection_reason (text, nullable)
- created_at, updated_at (timestamps)
```

## Model Relationships
### Jadwal Model
```php
public function mahasiswa()
{
    return $this->belongsTo(Mahasiswa::class);
}

public function dosen()
{
    return $this->belongsTo(Dosen::class);
}

public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}

public function rejectedBy()
{
    return $this->belongsTo(User::class, 'rejected_by');
}
```

## Methods

### openJadwalModal($jadwalId)
Membuka modal dengan data jadwal tertentu
```php
$this->dispatch('openJadwalModal', ['jadwalId' => 1]);
```

### approveBimbingan()
Approve jadwal bimbingan
- Update status menjadi 'disetujui'
- Set timestamp approved_at
- Set user ID yang approve

### rejectBimbingan()
Tolak jadwal bimbingan
- Update status menjadi 'ditolak'
- Set timestamp rejected_at
- Set user ID yang reject
- Simpan rejection_reason jika ada

### closeModal()
Menutup modal

## Event Listeners

Component mendengarkan event:
- `openJadwalModal` - Trigger buka modal

Component mengirim event:
- `jadwalApproved` - Setelah jadwal disetujui
- `jadwalRejected` - Setelah jadwal ditolak

## UI Elements

### Modal States
1. **Default**: Tampilkan detail jadwal
   - Nama mahasiswa + NIM
   - Tanggal & waktu
   - Tempat/ruangan
   - Topik bimbingan
   - Status saat ini
   - Tombol ACC & Tolak

2. **Approve Confirmation**: Konfirmasi sebelum ACC
   - Pesan konfirmasi
   - Tombol "Batal" & "Ya, Setujui"

3. **Reject Confirmation**: Konfirmasi sebelum tolak
   - Pesan konfirmasi
   - Field opsional untuk alasan penolakan
   - Tombol "Batal" & "Ya, Tolak"

## Styling
Component menggunakan Tailwind CSS dengan color scheme:
- **Success (Approve)**: Green (`bg-green-500`, `text-green-800`)
- **Danger (Reject)**: Red (`bg-red-500`, `text-red-800`)
- **Info**: Blue (`bg-blue-50`, `text-blue-700`)
- **Warning**: Yellow (`bg-yellow-100`, `text-yellow-800`)

## Contoh Penggunaan

```blade
<!-- Di resources/views/dospem/jadwal-bimbingan.blade.php -->
<div class="space-y-3">
    @forelse($jadwals as $jadwal)
        <div class="border rounded-lg p-4">
            <h4>{{ $jadwal->mahasiswa->nama }}</h4>
            <p>{{ $jadwal->tanggal->format('d M Y') }} Pukul {{ $jadwal->jam_mulai }}</p>
            
            @if($jadwal->status === 'menunggu')
                <button wire:click="$dispatch('openJadwalModal', { jadwalId: {{ $jadwal->id }} })">
                    Review Jadwal
                </button>
            @endif
        </div>
    @empty
        <p>Tidak ada jadwal</p>
    @endforelse
</div>

<!-- Jangan lupa tambahkan component di akhir view -->
<livewire:dospem.jadwal-bimbingan-modal />
```

## Session Alerts
Component mengirim flash session:
```php
session()->flash('success', 'Jadwal bimbingan berhasil disetujui!');
session()->flash('success', 'Jadwal bimbingan berhasil ditolak!');
```

Display di layout dengan:
```blade
@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded">
        {{ session('success') }}
    </div>
@endif
```

## Error Handling
Jika ada error:
- Error message ditampilkan di modal
- Exception message disimpan di error bag
- User bisa close modal dan coba lagi

## Tips
1. Pastikan user sudah login dan memiliki role 'dospem'
2. Pastikan relationship jadwal-mahasiswa-dosen sudah benar
3. Test di browser untuk memastikan modal responsive
4. Gunakan browser DevTools untuk debug Livewire events
5. Customisasi styling sesuai design system Anda

## Troubleshooting

**Modal tidak muncul?**
- Pastikan Livewire sudah ditambahkan ke layout
- Pastikan component didefinisikan di view

**Action tidak bekerja?**
- Check database schema
- Check user permissions
- Check Laravel logs (storage/logs/)

**Styling tidak sesuai?**
- Pastikan Tailwind CSS sudah compiled
- Run `npm run build`

## Catatan
- Component menggunakan Livewire v3+
- Memerlukan Tailwind CSS
- Memerlukan Font Awesome icons
- Support modern browsers
