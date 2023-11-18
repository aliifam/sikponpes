<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nis',
        'alamat',
        'telepon',
        'email',
        'jenis_kelamin',
        'pesantren_id',
    ];
}
