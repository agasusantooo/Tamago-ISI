<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryConference extends Model
{
    use HasFactory;

    protected $table = 'story_conference';

    protected $fillable = [
        'mahasiswa_id',
        'mahasiswa_nim',
        'proposal_id',
        'proposals_id',
        'dosen_id',
        'judul_karya',
        'slot_waktu',
        'file_presentasi',
        'status',
        'tanggal_daftar',
        'tanggal_review',
        'catatan_panitia',
        'ruang',
        'waktu_presentasi',
        'tanggal',
        'catatan_evaluasi',
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
        'tanggal_review' => 'datetime',
        'waktu_presentasi' => 'datetime',
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
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'menunggu_persetujuan' => ['color' => 'yellow', 'text' => 'Menunggu Persetujuan', 'icon' => 'clock'],
            'sedang_direview' => ['color' => 'blue', 'text' => 'Sedang Direview', 'icon' => 'eye'],
            'diterima' => ['color' => 'green', 'text' => 'Diterima', 'icon' => 'check-circle'],
            'ditolak' => ['color' => 'red', 'text' => 'Ditolak', 'icon' => 'times-circle'],
            'konfirmasi_akhir' => ['color' => 'purple', 'text' => 'Konfirmasi Akhir', 'icon' => 'clipboard-check'],
            'selesai' => ['color' => 'gray', 'text' => 'Selesai', 'icon' => 'flag-checkered'],
        ];

        return $badges[$this->status] ?? ['color' => 'gray', 'text' => 'Unknown', 'icon' => 'question'];
    }

    /**
     * Get status color for display
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'menunggu_persetujuan' => 'bg-yellow-100 text-yellow-800',
            'sedang_direview' => 'bg-blue-100 text-blue-800',
            'diterima' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            'konfirmasi_akhir' => 'bg-purple-100 text-purple-800',
            'selesai' => 'bg-gray-100 text-gray-800',
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk pendaftar yang diterima
     */
    public function scopeAccepted($query)
    {
        return $query->whereIn('status', ['diterima', 'konfirmasi_akhir', 'selesai']);
    }
}