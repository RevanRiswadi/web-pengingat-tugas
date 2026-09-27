<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'judul_tugas',
        'mata_pelajaran',
        'tenggat_waktu',
        'status',
    ];
}