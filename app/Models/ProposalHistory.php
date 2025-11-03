<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalHistory extends Model
{
    protected $fillable = [
        'user_id',
        'proposal_id',
        'version',
        'judul',
        'status',
        'file_proposal',
        'file_pitch_deck',
        'feedback',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Proposal
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    // Get status badge
    public function getStatusBadge()
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'pending_review' => 'bg-blue-100 text-blue-800',
            'need_revision' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}