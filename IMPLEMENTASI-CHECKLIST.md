# Checklist Implementasi Modal ACC/Tolak Jadwal Bimbingan

## ✅ File-File yang Sudah Dibuat

### 1. Livewire Component
- ✅ `app/Livewire/Dospem/JadwalBimbinganModal.php` - Logic component

### 2. View
- ✅ `resources/views/livewire/dospem/jadwal-bimbingan-modal.blade.php` - Modal UI

### 3. Dokumentasi
- ✅ `README-JADWAL-BIMBINGAN-MODAL.md` - Dokumentasi lengkap
- ✅ `resources/views/dospem/modals/jadwal-bimbingan-modal-guide.blade.php` - Implementation guide
- ✅ `resources/views/dospem/jadwal-bimbingan-updated.blade.php` - Contoh view terintegrasi

---

## 📋 Langkah Implementasi

### Step 1: Update Tabel Database
```bash
php artisan make:migration update_jadwal_table_add_approval_fields
```

Isikan migration:
```php
public function up()
{
    Schema::table('jadwal', function (Blueprint $table) {
        $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->change();
        $table->timestamp('approved_at')->nullable()->after('status');
        $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
        $table->timestamp('rejected_at')->nullable()->after('approved_by');
        $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
        $table->text('rejection_reason')->nullable()->after('rejected_by');
        
        $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
    });
}
```

Jalankan migration:
```bash
php artisan migrate
```

### Step 2: Update Model Jadwal
```php
// app/Models/Jadwal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'tempat',
        'topik',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // Relationships
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

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'ditolak');
    }
}
```

### Step 3: Implementasi di View
Edit atau ganti `resources/views/dospem/jadwal-bimbingan.blade.php`:

```blade
<livewire:dospem.jadwal-bimbingan-modal />

<!-- Dalam loop jadwal -->
@forelse($jadwals ?? [] as $jadwal)
    <div class="border rounded-lg p-4">
        <div class="flex justify-between items-start">
            <div>
                <h4>{{ $jadwal->mahasiswa->nama }}</h4>
                <p>{{ $jadwal->tanggal->format('d M Y') }} Pukul {{ date('H:i', strtotime($jadwal->jam_mulai)) }}</p>
                <p>{{ $jadwal->topik }}</p>
            </div>
            <div class="flex gap-2">
                @if($jadwal->status === 'menunggu')
                    <button wire:click="$dispatch('openJadwalModal', { jadwalId: {{ $jadwal->id }} })">
                        Review
                    </button>
                @endif
            </div>
        </div>
    </div>
@empty
    <p>Tidak ada jadwal</p>
@endforelse
```

### Step 4: Update Controller (Opsional)
Jika ingin menambah logic tambahan di controller:

```php
// app/Http/Controllers/Dospem/JadwalBimbinganController.php

public function index()
{
    $dosen = Auth::user()->dosen;
    $jadwals = Jadwal::with(['mahasiswa', 'dosen'])
        ->where('dosen_id', $dosen->id)
        ->orderBy('tanggal', 'desc')
        ->get();

    return view('dospem.jadwal-bimbingan', [
        'jadwals' => $jadwals,
    ]);
}
```

### Step 5: Test Functionality
1. Login sebagai dosen pembimbing
2. Navigate ke halaman jadwal bimbingan
3. Klik tombol "Review" pada jadwal yang menunggu
4. Modal akan terbuka dengan detail jadwal
5. Klik "Setujui" atau "Tolak"
6. Konfirmasi akan muncul
7. Setelah confirm, status jadwal akan berubah

---

## 🎨 UI Components Overview

### Modal States

#### 1. Default View
```
┌─────────────────────────────┐
│ Detail Jadwal Bimbingan  × │
├─────────────────────────────┤
│                             │
│ [Avatar] Nama Mahasiswa     │
│          NIM: 71220001      │
│                             │
│ 📅 Tanggal: 27 Nov 2025     │
│ ⏰ Waktu: 10:00 - 11:00     │
│ 📍 Tempat: Ruang A          │
│ 📚 Topik: Bimbingan         │
│ 📌 Status: ⏳ Menunggu      │
│                             │
├─────────────────────────────┤
│  [Tolak]      [Setujui]     │
└─────────────────────────────┘
```

#### 2. Approve Confirmation
```
┌─────────────────────────────┐
│ Setujui Jadwal      × │
├─────────────────────────────┤
│                             │
│    Apakah Anda yakin?       │
│                             │
│    Dengan Nama Mahasiswa    │
│    pada 27 Nov 2025         │
│                             │
├─────────────────────────────┤
│  [Batal]   [Ya, Setujui]    │
└─────────────────────────────┘
```

#### 3. Reject Confirmation
```
┌─────────────────────────────┐
│ Tolak Jadwal        × │
├─────────────────────────────┤
│                             │
│    Apakah Anda yakin?       │
│                             │
│ [Text Area Alasan]          │
│                             │
├─────────────────────────────┤
│  [Batal]      [Ya, Tolak]   │
└─────────────────────────────┘
```

---

## 🔧 Troubleshooting

### Problem: Modal tidak muncul
**Solution:**
- Pastikan `<livewire:dospem.jadwal-bimbingan-modal />` ada di view
- Clear cache: `php artisan cache:clear`
- Rebuild assets: `npm run build`

### Problem: Action tidak bekerja
**Solution:**
- Check database schema (pastikan kolom ada)
- Check browser console untuk Livewire errors
- Check `storage/logs/laravel.log`

### Problem: Styling tidak sesuai
**Solution:**
- Run `npm run build` untuk compile Tailwind
- Check dass sudah ada `@tailwind` di CSS

### Problem: Notifikasi tidak muncul
**Solution:**
- Pastikan layout punya section untuk display session flash
- Add ke layout:
```blade
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
```

---

## 📱 Responsive Design
- ✅ Mobile friendly
- ✅ Tablet optimized
- ✅ Desktop full-width
- ✅ Touch-friendly buttons

---

## ⚙️ Requirements
- PHP 8.0+
- Laravel 10+
- Livewire 3+
- Tailwind CSS 3+
- Font Awesome icons

---

## 📝 Notes
- Component menggunakan event listeners Livewire
- Data update real-time tanpa page reload
- Validasi di backend (hindari race condition)
- Session flash untuk notifikasi user

---

## 🎯 Next Steps
1. ✅ Implementasikan all steps di atas
2. ⭕ Test di staging/development
3. ⭕ Deploy ke production
4. ⭕ Monitor & support user

---

Generated: 27 Nov 2025
