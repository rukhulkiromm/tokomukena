<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Grafik extends Model
{
    use HasFactory;

    // untuk mendapatkan view grafik per bulan berjalan
    public static function viewBulanBerjalan()
    {
        // query kode perusahaan
        $sql = "
                    SELECT a.waktu,ifnull(b.total,0) as total FROM 
                    v_waktu a 
                    LEFT OUTER JOIN
                    (
                    SELECT DATE_FORMAT(tgl_transaksi,'%Y-%m') as waktu,
                        SUM(total_harga) as total
                    FROM penjualan
                    WHERE status = 'selesai'
                    GROUP BY DATE_FORMAT(tgl_transaksi,'%Y-%m')
                    ) b
                    ON (a.waktu=b.waktu) ";
        $hasil = DB::select($sql);

        return $hasil;

    }

    // untuk mendapatkan view grafik status penjualan
    public static function viewStatusPenjualan()
    {
        $sql = "SELECT status,count(*) as jml_penjualan 
                FROM penjualan 
                GROUP BY status";
        $hasil = DB::select($sql);

        return $hasil;

    }

    // untuk mendapatkan view grafik jml barang terjual
    public static function viewJmlBarangTerjual()
    {
        $sql = "
            SELECT  ax.waktu,
                (SELECT ifnull(SUM(jml_barang),0) 
                FROM penjualan a 
                    JOIN penjualan_detail b
                    ON (a.no_transaksi=b.no_transaksi)
                    JOIN barang c
                    ON (b.id_barang=c.id)
                    WHERE a.status = 'selesai' 
                    AND c.id = 1
                    AND DATE_FORMAT(a.tgl_transaksi,'%Y-%m') = ax.waktu
                ) as jml_mukena_artiz,
                (SELECT ifnull(SUM(jml_barang),0) 
                FROM penjualan a 
                    JOIN penjualan_detail b
                    ON (a.no_transaksi=b.no_transaksi)
                    JOIN barang c
                    ON (b.id_barang=c.id)
                    WHERE a.status = 'selesai' 
                    AND c.id = 2
                    AND DATE_FORMAT(a.tgl_transaksi,'%Y-%m') = ax.waktu
                ) as jml_mukena_religius,
                (SELECT ifnull(SUM(jml_barang),0) 
                FROM penjualan a 
                    JOIN penjualan_detail b
                    ON (a.no_transaksi=b.no_transaksi)
                    JOIN barang c
                    ON (b.id_barang=c.id)
                    WHERE a.status = 'selesai' 
                    AND c.id = 3
                    AND DATE_FORMAT(a.tgl_transaksi,'%Y-%m') = ax.waktu
                ) as jml_mukena_modis,
                (SELECT ifnull(SUM(jml_barang),0) 
                FROM penjualan a 
                    JOIN penjualan_detail b
                    ON (a.no_transaksi=b.no_transaksi)
                    JOIN barang c
                    ON (b.id_barang=c.id)
                    WHERE a.status = 'selesai' 
                    AND c.id = 4
                    AND DATE_FORMAT(a.tgl_transaksi,'%Y-%m') = ax.waktu
                ) as jml_mukena_unyuy
            FROM 
            v_waktu ax 
                ";
        $hasil = DB::select($sql);

        return $hasil;

    }

    // untuk mendapatkan view grafik per bulan berjalan
    public static function viewPenjualan()
    {
        // query kode perusahaan
        $sql = "
                    SELECT DATE_FORMAT(tgl_transaksi, '%Y-%m-%d') as tgl, SUM(total_harga) as total
                    FROM penjualan
                    GROUP BY DATE_FORMAT(tgl_transaksi, '%Y-%m-%d')
                    ORDER BY 1
               ";
        $hasil = DB::select($sql);

        return $hasil;

    }
}
