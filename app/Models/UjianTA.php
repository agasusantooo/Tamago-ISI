<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianTA extends Model
{
    use HasFactory;

    protected $table = 'ujian_tas';

    protected $fillable = [
        'mahasiswa_id',
        'proposal_id',
        'dosen_pembimbing_id',
        'ketua_penguji_id',
        'penguji_ahli_id',
        'file_surat_pengantar',
        'file_transkrip_nilai',
        'file_revisi',
        'deskripsi_revisi',
        'tanggal_ujian',
        'waktu_ujian',
        'ruang_ujian',
        'status_pendaftaran',
        'status_ujian',
        'status_revisi',
        'nilai_akhir',
        'catatan_penguji',
        'feedback_revisi',
        'tanggal_daftar',
        'tanggal_submit_revisi',
        'tanggal_approve_revisi',
    ];

    protected $casts = [
        'tanggal_ujian' => 'date',
        'waktu_ujian' => 'datetime',
        'tanggal_daftar' => 'datetime',
        'tanggal_submit_revisi' => 'datetime',
        'tanggal_approve_revisi' => 'datetime',
    ];

    /**
     * Get mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Get proposal
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get dosen pembimbing
     */
    public function dosenPembimbing()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id');
    }

    /**
     * Get ketua penguji
     */
    public function ketuaPenguji()
    {
        return $this->belongsTo(Dosen::class, 'ketua_penguji_id');
    }

    /**
     * Get penguji ahli
     */
    public function pengujiAhli()
    {
        return $this->belongsTo(Dosen::class, 'penguji_ahli_id');
    }

    /**
     * Get status badge for pendaftaran
     */
    public function getStatusPendaftaranBadgeAttribute()
    {
        $badges = [
            'pengajuan_ujian' => ['color' => 'yellow', 'text' => 'Pengajuan Ujian', 'icon' => 'clock'],
            'jadwal_ditetapkan' => ['color' => 'blue', 'text' => 'Jadwal Ditetapkan', 'icon' => 'calendar-check'],
            'ujian_berlangsung' => ['color' => 'purple', 'text' => 'Ujian Berlangsung', 'icon' => 'hourglass-half'],
        ];

        return $badges[$this->status_pendaftaran] ?? ['color' => 'gray', 'text' => 'Unknown', 'icon' => 'question'];
    }

    /**
     * Get status badge for ujian
     */
    public function getStatusUjianBadgeAttribute()
    {
        $badges = [
            'belum_ujian' => ['color' => 'gray', 'text' => 'Belum Ujian', 'icon' => 'clock'],
            'selesai_ujian' => ['color' => 'green', 'text' => 'Selesai Ujian', 'icon' => 'check-circle'],
        ];

        return $badges[$this->status_ujian] ?? ['color' => 'gray', 'text' => 'Unknown', 'icon' => 'question'];
    }

    /**
     * Get status badge for revisi
     */
    public function getStatusRevisiBadgeAttribute()
    {
        $badges = [
            'belum_revisi' => ['color' => 'gray', 'text' => 'Belum Revisi', 'icon' => 'clock'],
            'perlu_revisi' => ['color' => 'orange', 'text' => 'Perlu Revisi', 'icon' => 'exclamation-triangle'],
            'menunggu_persetujuan' => ['color' => 'yellow', 'text' => 'Menunggu Persetujuan Revisi', 'icon' => 'hourglass-half'],
            'revisi_selesai' => ['color' => 'green', 'text' => 'Revisi Selesai', 'icon' => 'check-circle'],
        ];

        return $badges[$this->status_revisi] ?? ['color' => 'gray', 'text' => 'Unknown', 'icon' => 'question'];
    }

    /**
     * Get status color for revisi
     */
    public function getStatusRevisiColorAttribute()
    {
        $colors = [
            'belum_revisi' => 'bg-gray-100 text-gray-800',
            'perlu_revisi' => 'bg-orange-100 text-orange-800',
            'menunggu_persetujuan' => 'bg-yellow-100 text-yellow-800',
            'revisi_selesai' => 'bg-green-100 text-green-800',
        ];

        return $colors[$this->status_revisi] ?? 'bg-gray-100 text-gray-800';
    }
}