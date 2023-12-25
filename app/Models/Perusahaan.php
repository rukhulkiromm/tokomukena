<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';
    protected $fillable = ['kode_perusahaan','nama_perusahaan','alamat_perusahaan'];

    // query nilai max dari kode perusahaan untuk generate otomatis kode perusahaan
    public static function getKodePerusahaan()

    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(kode_perusahaan), 'PR-000') as kode_perusahaan 
                FROM perusahaan";
        $kodeperusahaan = DB::select($sql);

        // cacah hasilnya
        foreach ($kodeperusahaan as $kdprsh) {
            $kd = $kdprsh->kode_perusahaan;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'PR-'.str_pad($noakhir,3,"0",STR_PAD_LEFT); //menyambung dengan string PR-001
        return $noakhir;

    }
}
