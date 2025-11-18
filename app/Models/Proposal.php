<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_nim',
        'dosen_id',
        'judul',
        'deskripsi',
        'file_proposal',
        'file_pitch_deck',
        'versi',
        'status',
        'tanggal_pengajuan',
        'tanggal_review',
        'feedback',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_review' => 'datetime',
    ];

    /**
     * Get the mahasiswa that owns the proposal
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_nim');
    }

    /**
     * Get the dosen assigned to the proposal
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => [
                'class' => 'bg-gray-100 text-gray-800', 
                'text' => 'Draft'
            ],
            'diajukan' => [
                'class' => 'bg-blue-100 text-blue-800', 
                'text' => 'Diajukan'
        ],
        'review' => [
            'class' => 'bg-yellow-100 text-yellow-800', 
            'text' => 'Dalam Review'
        ],
        'revisi' => [
            'class' => 'bg-orange-100 text-orange-800', 
            'text' => 'Perlu Revisi'
        ],
        'disetujui' => [
            'class' => 'bg-green-100 text-green-800', 
            'text' => 'Disetujui'
        ],
        'ditolak' => [
            'class' => 'bg-red-100 text-red-800', 
            'text' => 'Ditolak'
        ],
        ];

        return $badges[$this->status] ?? ['color' => 'gray', 'text' => 'Unknown'];
    }

    /**
     * Get formatted file size
     */
    public function getFileProposalSizeAttribute()
    {
        if (!$this->file_proposal) return null;
        
        $path = storage_path('app/public/' . $this->file_proposal);
        if (!file_exists($path)) return null;
        
        $bytes = filesize($path);
        return $this->formatBytes($bytes);
    }

    /**
     * Format bytes to human readable size
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan mahasiswa
     */
    public function scopeByMahasiswa($query, $mahasiswaNim)
    {
        return $query->where('mahasiswa_nim', $mahasiswaNim);
    }
}