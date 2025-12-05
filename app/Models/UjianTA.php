<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianTA extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     * The table uses a non-standard primary key `id_ujian`.
     */
    protected $primaryKey = 'id_ujian';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The data type of the primary key.
     */
    protected $keyType = 'int';

    // migration creates table 'ujian_tugas_akhir'
    protected $table = 'ujian_tugas_akhir';

    protected $fillable = [
        'id_proyek_akhir',
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
        'hasil_akhir',
        'catatan_penguj',
        'catatan_penguji',
        'feedback_revisi',
        'tanggal_daftar',
        'tanggal_submit_revisi',
        'tanggal_approve_revisi',
        'status_kelayakan',
    ];

    protected $casts = [
        'tanggal_ujian' => 'datetime',
        'waktu_ujian' => 'datetime',
        'tanggal_daftar' => 'datetime',
        'tanggal_submit_revisi' => 'datetime',
        'tanggal_approve_revisi' => 'datetime',
    ];

    /**
     * Cache of enum values per column to avoid repeated DB queries.
     * @var array
     */
    protected static $enumCache = [];

    /**
     * Mutator for status_ujian - since columns are strings, no enum mapping needed
     */
    public function setStatusUjianAttribute($value)
    {
        $this->attributes['status_ujian'] = $value;
    }

    /**
     * Map a desired value to a valid enum value for given column.
     * Returns first allowed enum that matches normalized form or a sensible fallback.
     */
    protected function mapToEnumValue(string $column, $desired)
    {
        $desiredNorm = $this->normalizeString($desired);

        // load allowed values from cache or DB
        if (!isset(self::$enumCache[$column])) {
            try {
                $col = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM " . $this->getTable() . " LIKE ?", [$column]);
                $allowed = [];
                if (!empty($col) && isset($col[0]->Type)) {
                    preg_match_all("/'([^']+)'/", $col[0]->Type, $m);
                    $allowed = $m[1] ?? [];
                }
            } catch (\Throwable $t) {
                $allowed = [];
            }
            self::$enumCache[$column] = $allowed;
        } else {
            $allowed = self::$enumCache[$column];
        }

        // Normalized allowed map
        $normMap = [];
        foreach ($allowed as $val) {
            $normMap[$this->normalizeString($val)] = $val;
        }

        // direct normalized match
        if (isset($normMap[$desiredNorm])) {
            return $normMap[$desiredNorm];
        }

        // try replacing underscores/hyphens in desired and match
        $alt = str_replace(['_', '-'], ' ', $desired);
        $altNorm = $this->normalizeString($alt);
        if (isset($normMap[$altNorm])) {
            return $normMap[$altNorm];
        }

        // fallback synonyms mapping (common conversions)
        $synonyms = [
            'belumujian' => 'Menunggu_hasil',
            'ujianberlangsung' => 'Berlangsung',
            'selesaiujian' => 'Selesai',
            'lulus' => 'Lulus',
            'tidaklulus' => 'Tidak Lulus',
            'menungguhasil' => 'Menunggu_hasil',
        ];
        $d = $desiredNorm;
        if (isset($synonyms[$d]) && in_array($synonyms[$d], $allowed)) {
            return $synonyms[$d];
        }

        // As last resort, if there is any allowed value, return the first one
        return $allowed[0] ?? $desired;
    }

    protected function normalizeString($s)
    {
        if (is_null($s)) return '';
        // lowercase, remove non-alphanumeric
        $s = mb_strtolower((string)$s);
        $s = str_replace([' ', '_', '-'], '', $s);
        $s = preg_replace('/[^a-z0-9]/u', '', $s);
        return $s;
    }

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

    /**
     * Automatically update status_ujian based on conditions
     */
    public function updateStatusUjianIfNeeded()
    {
        // If exam date has passed and status is still 'belum_ujian', mark as 'selesai_ujian'
        if ($this->tanggal_ujian && $this->status_ujian === 'belum_ujian') {
            $examDateTime = $this->tanggal_ujian->setTimeFromTimeString($this->waktu_ujian ?? '00:00:00');
            if (now()->greaterThanOrEqualTo($examDateTime)) {
                $this->status_ujian = 'selesai_ujian';
                $this->save();
                return true;
            }
        }

        // If results are entered (nilai_akhir or catatan_penguji), mark as completed
        if ($this->status_ujian === 'belum_ujian' &&
            (!is_null($this->nilai_akhir) || !empty($this->catatan_penguji))) {
            $this->status_ujian = 'selesai_ujian';
            $this->save();
            return true;
        }

        return false;
    }
}