<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files'; // Pastikan nama tabel sesuai dengan database
    protected $fillable = ['uploaded_by', 'path', 'created_at', 'updated_at']; // Tambahkan kolom yang relevan
}
