<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TefaFair extends Model
{
    use HasFactory;

    protected $table = 'tefa_fair';
    protected $primaryKey = 'id_tefa';

    protected $fillable = [
        'id_proyek_akhir',
        'semester',
        'file_presentasi',
        'daftar_kebutuhan',
        'status',
    ];

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'menunggu_review' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu Review'],
            'disetujui' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
            'ditolak' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
        ];

        return $badges[$this->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Unknown'];
    }


    public function projekAkhir()
    {
        return $this->belongsTo(ProjekAkhir::class, 'id_proyek_akhir', 'id_proyek_akhir');
    }
}
