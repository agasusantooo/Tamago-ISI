<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Proposals yang diajukan oleh Mahasiswa
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    public function latestProposal()
    {
        return $this->hasOne(Proposal::class)->latestOfMany();
    }

    public function mahasiswaBimbingan()
    {
        return $this->hasMany(Proposal::class, 'dosen_id');
    }

    // Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Helper methods untuk cek role
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function isMahasiswa()
    {
        return $this->hasRole('mahasiswa');
    }

    public function isDospem()
    {
        return $this->hasRole('dospem');
    }

    public function isKaprodi()
    {
        return $this->hasRole('kaprodi');
    }

    public function isKoordinatorTA()
    {
        return $this->hasRole('koordinator_ta');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isDosenPenguji()
    {
        return $this->hasRole('dosen_penguji');
    }

    // Get role name
    public function getRoleName()
    {
        return $this->role ? $this->role->name : 'guest';
    }

    // Get role display name
    public function getRoleDisplayName()
    {
        return $this->role ? $this->role->display_name : 'Guest';

    }

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
    // user_id = kolom di tabel mahasiswa yang mengacu ke users.id
        return $this->hasOne(Mahasiswa::class, 'user_id', 'id');
    }

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'user_id', 'id');
    }

}
