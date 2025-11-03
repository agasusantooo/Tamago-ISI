<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'nip',
        'nama',
        'gelar',
        'email',
        'no_telp',
        'bidang_keahlian',
        'status',
        'foto',
    ];

    /**
     * Get the proposals for the dosen
     */
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    /**
     * Get active proposals
     */
    public function activeProposals()
    {
        return $this->hasMany(Proposal::class)
            ->whereIn('status', ['diajukan', 'review', 'revisi']);
    }

    /**
     * Get approved proposals
     */
    public function approvedProposals()
    {
        return $this->hasMany(Proposal::class)
            ->where('status', 'disetujui');
    }

    /**
     * Get full name with gelar
     */
    public function getFullNameAttribute()
    {
        return $this->nama . ($this->gelar ? ', ' . $this->gelar : '');
    }

    /**
     * Scope untuk filter dosen aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk filter berdasarkan bidang keahlian
     */
    public function scopeByBidang($query, $bidang)
    {
        return $query->where('bidang_keahlian', 'like', '%' . $bidang . '%');
    }
}