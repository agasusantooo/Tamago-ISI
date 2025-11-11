<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksis';

    protected $fillable = [
        'mahasiswa_id',
        'proposal_id',
        'dosen_id',
        'file_skenario',
        'file_storyboard',
        'file_dokumen_pendukung',
        'file_produksi_akhir',
        'file_luaran_tambahan',
        'catatan_produksi',
        'status_pra_produksi',
        'status_produksi_akhir',
        'tanggal_upload_pra',
        'tanggal_upload_akhir',
        'tanggal_review_pra',
        'tanggal_review_akhir',
        'feedback_pra_produksi',
        'feedback_produksi_akhir',
    ];

    protected $casts = [
        'tanggal_upload_pra' => 'datetime',
        'tanggal_upload_akhir' => 'datetime',
        'tanggal_review_pra' => 'datetime',
        'tanggal_review_akhir' => 'datetime',
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
            'belum_upload' => ['color' => 'gray', 'text' => 'Belum Upload', 'icon' => 'clock'],
            'menunggu_review' => ['color' => 'yellow', 'text' => 'Menunggu Review', 'icon' => 'hourglass-half'],
            'disetujui' => ['color' => 'green', 'text' => 'Disetujui', 'icon' => 'check-circle'],
            'revisi' => ['color' => 'orange', 'text' => 'Perlu Revisi', 'icon' => 'exclamation-triangle'],
            'ditolak' => ['color' => 'red', 'text' => 'Ditolak', 'icon' => 'times-circle'],
        ];

        return $badges[$this->status_pra_produksi] ?? ['color' => 'gray', 'text' => 'Unknown', 'icon' => 'question'];
    }

    /**
     * Get status badge for produksi akhir
     */
    public function getStatusProduksiAkhirBadgeAttribute()
    {
        $badges = [
            'belum_upload' => ['color' => 'gray', 'text' => 'Belum Upload', 'icon' => 'clock'],
            'menunggu_review' => ['color' => 'yellow', 'text' => 'Menunggu Review', 'icon' => 'hourglass-half'],
            'disetujui' => ['color' => 'green', 'text' => 'Disetujui', 'icon' => 'check-circle'],
            'revisi' => ['color' => 'orange', 'text' => 'Perlu Revisi', 'icon' => 'exclamation-triangle'],
            'ditolak' => ['color' => 'red', 'text' => 'Ditolak', 'icon' => 'times-circle'],
        ];

        return $badges[$this->status_produksi_akhir] ?? ['color' => 'gray', 'text' => 'Unknown', 'icon' => 'question'];
    }

    /**
     * Get status color for pra produksi
     */
    public function getStatusPraProduksiColorAttribute()
    {
        $colors = [
            'belum_upload' => 'bg-gray-100 text-gray-800',
            'menunggu_review' => 'bg-yellow-100 text-yellow-800',
            'disetujui' => 'bg-green-100 text-green-800',
            'revisi' => 'bg-orange-100 text-orange-800',
            'ditolak' => 'bg-red-100 text-red-800',
        ];

        return $colors[$this->status_pra_produksi] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get status color for produksi akhir
     */
    public function getStatusProduksiAkhirColorAttribute()
    {
        $colors = [
            'belum_upload' => 'bg-gray-100 text-gray-800',
            'menunggu_review' => 'bg-yellow-100 text-yellow-800',
            'disetujui' => 'bg-green-100 text-green-800',
            'revisi' => 'bg-orange-100 text-orange-800',
            'ditolak' => 'bg-red-100 text-red-800',
        ];

        return $colors[$this->status_produksi_akhir] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Scope untuk filter berdasarkan status pra produksi
     */
    public function scopeByStatusPra($query, $status)
    {
        return $query->where('status_pra_produksi', $status);
    }

    /**
     * Scope untuk filter berdasarkan status produksi akhir
     */
    public function scopeByStatusAkhir($query, $status)
    {
        return $query->where('status_produksi_akhir', $status);
    }

    /**
     * Scope untuk produksi yang sudah selesai (kedua tahap disetujui)
     */
    public function scopeCompleted($query)
    {
        return $query->where('status_pra_produksi', 'disetujui')
            ->where('status_produksi_akhir', 'disetujui');
    }
}