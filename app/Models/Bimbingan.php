<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Bimbingan extends Model
{
    use HasFactory;

    // Karena tabel bernama "bimbingans", tidak perlu define $table lagi.

    protected $fillable = [
        'mahasiswa_id',
        'topik',
        'catatan_mahasiswa',
        'file_pendukung',
        'status',
        'tanggal',
    ];

    protected $dates = ['tanggal', 'created_at', 'updated_at'];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function getTanggalFormattedAttribute()
    {
        return Carbon::parse($this->tanggal)->translatedFormat('d F Y');
    }

    public function getStatusBadgeAttribute()
    {
        $map = [
            'pending' => ['text' => 'Menunggu', 'color' => 'bg-yellow-100 text-yellow-800'],
            'disetujui' => ['text' => 'Disetujui', 'color' => 'bg-green-100 text-green-800'],
            'ditolak' => ['text' => 'Ditolak', 'color' => 'bg-red-100 text-red-800'],
        ];

        return $map[$this->status] ?? ['text' => 'Tidak Diketahui', 'color' => 'bg-gray-100 text-gray-800'];
    }
}
