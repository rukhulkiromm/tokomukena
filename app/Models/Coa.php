<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $table = 'coa';
    // untuk melist kolom yang dapat diisi
    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'header_akun',
    ];
}
