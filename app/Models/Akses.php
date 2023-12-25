<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Akses extends Model
{
    use HasFactory;

    // untuk mendapatkan kelompok user berdasarkan idnya
    public static function getGrupUser($id)
    {
        // query untuk mendapatkan kelompok
        $sql = "SELECT kelompok
                FROM users_kelompok WHERE id_user = ?";
        $kelompok = DB::select($sql, [$id]);
        return $kelompok;
    }
}
