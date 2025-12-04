<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'tim_produksi';

    protected $fillable = [
        'mahasiswa_id',
        'proposal_id',
        'dosen_id',
        'file_skenario',
        'file_storyboard',
        'file_dokumen_pendukung',
        'catatan_produksi',
        'status_pra_produksi',
        'tanggal_upload_pra',
        'tanggal_review_pra',
        'feedback_pra_produksi',
        'status_produksi',
        'file_produksi',
        'feedback_produksi',
        'tanggal_upload_produksi',
        'tanggal_review_produksi',
        'status_pasca_produksi',
        'file_pasca_produksi',
        'feedback_pasca_produksi',
        'tanggal_upload_pasca',
        'tanggal_review_pasca',
    ];

    protected $casts = [
        'tanggal_upload_pra' => 'datetime',
        'tanggal_review_pra' => 'datetime',
        'tanggal_upload_produksi' => 'datetime',
        'tanggal_review_produksi' => 'datetime',
        'tanggal_upload_pasca' => 'datetime',
        'tanggal_review_pasca' => 'datetime',
    ];

    /**
     * Get the mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Get the proposal
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get the dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Get status badge for pra produksi
     */
    public function getStatusPraProduksiBadgeAttribute()
    {
        $badges = [
            'belum_upload' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Belum Upload'],
            'menunggu_review' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu Review'],
            'disetujui' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
            'revisi' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Perlu Revisi'],
            'ditolak' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
        ];

        return $badges[$this->status_pra_produksi] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Unknown'];
    }

    /**
     * Get status badge for produksi
     */
    public function getStatusProduksiBadgeAttribute()
    {
        $badges = [
            'belum_upload' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Belum Upload'],
            'menunggu_review' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu Review'],
            'disetujui' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
            'revisi' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Perlu Revisi'],
            'ditolak' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
        ];

        return $badges[$this->status_produksi] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Unknown'];
    }

     /**
     * Get status badge for pasca produksi
     */
    public function getStatusPascaProduksiBadgeAttribute()
    {
        $badges = [
            'belum_upload' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Belum Upload'],
            'menunggu_review' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu Review'],
            'disetujui' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Selesai'],
            'revisi' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Perlu Revisi'],
            'ditolak' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
        ];

        return $badges[$this->status_pasca_produksi] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Unknown'];
    }

    /**
     * Scope untuk filter berdasarkan status pra produksi
     */
    public function scopeByStatusPra($query, $status)
    {
        return $query->where('status_pra_produksi', $status);
    }

    /**
     * Scope untuk filter berdasarkan status produksi
     */
    public function scopeByStatusProduksi($query, $status)
    {
        return $query->where('status_produksi', $status);
    }

    /**
     * Scope untuk produksi yang sudah selesai (semua tahap disetujui)
     */
    public function scopeCompleted($query)
    {
        return $query->where('status_pra_produksi', 'disetujui')
            ->where('status_produksi', 'disetujui')
            ->where('status_pasca_produksi', 'disetujui');
    }
}