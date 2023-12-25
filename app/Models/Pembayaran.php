<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Pembayaran extends Model
{
    use HasFactory;
     // use HasFactory;
     protected $table = "pembayaran";

     // untuk melist kolom yang dapat dimasukkan
     protected $fillable = [
         'no_transaksi',
         'tgl_bayar',
         'tgl_konfirmasi',
         'bukti_bayar',
         'jenis_pembayaran',
         'status'
     ];
 
     public static function updateStatusKonformasiPembayaran($no_transaksi)
     {
         $affected = DB::table('penjualan')
               ->where('no_transaksi', $no_transaksi)
               ->update(['status' => 'konfirmasi_bayar']);
 
     }
 
     // untuk view status pembayaran berdasarkan id customer tertentu untuk PG
     public static function viewstatusPG($id_customer)
     {
         // query kode perusahaan
         $sql = "SELECT b.*,c.status_code,c.transaction_status,c.settlement_time
                 FROM penjualan b
                 LEFT OUTER JOIN pg_penjualan c
                 ON (b.id=c.id_penjualan)
                 WHERE b.id_customer = ? AND b.status in ('selesai','siap_bayar')
                 AND b.no_transaksi NOT IN 
                 (SELECT no_transaksi FROM pembayaran WHERE jenis_pembayaran = 'tunai')
                 ";
         $list = DB::select($sql,[$id_customer]);
 
         return $list;
     }
 
     // untuk view status pembayaran berdasarkan id customer tertentu untuk PG
     public static function viewstatusPGAll()
     {
         // query kode perusahaan
         $sql = "SELECT b.*,c.status_code,c.order_id
                 FROM penjualan b
                 JOIN pg_penjualan c
                 ON (b.id=c.id_penjualan)
                 WHERE b.status in ('siap_bayar')
                 AND b.no_transaksi NOT IN 
                 (SELECT no_transaksi FROM pembayaran WHERE jenis_pembayaran = 'tunai')
                 ";
         $list = DB::select($sql);
 
         return $list;
     }
 
     // viewstatus pembayaran seluruh customer
     public static function viewstatusall()
     {
         // query kode perusahaan
         $sql = "SELECT a.id,a.no_transaksi,a.tgl_bayar,a.tgl_konfirmasi,a.bukti_bayar,
                         a.jenis_pembayaran,a.status,
                         b.total_harga,
                         GROUP_CONCAT(d.nama_barang ORDER BY d.nama_barang) as list_barang
                 FROM pembayaran a
                 LEFT OUTER JOIN penjualan b
                 ON (a.no_transaksi=b.no_transaksi)
                 LEFT OUTER JOIN penjualan_detail c
                 ON (b.no_transaksi=c.no_transaksi)
                 LEFT OUTER JOIN barang d
                 ON (c.id_barang=d.id)
                 GROUP BY a.id,a.no_transaksi,a.tgl_bayar,a.tgl_konfirmasi,a.bukti_bayar,
                         a.jenis_pembayaran,a.status,
                         b.total_harga";
         $list = DB::select($sql);
 
         return $list;
     }
 
     // untuk view status pembayaran berdasarkan id customer tertentu
     public static function viewstatus($id_customer)
     {
         // query kode perusahaan
         $sql = "SELECT a.id,a.no_transaksi,a.tgl_bayar,a.tgl_konfirmasi,a.bukti_bayar,
                         a.jenis_pembayaran,a.status,
                         b.total_harga,
                         GROUP_CONCAT(d.nama_barang ORDER BY d.nama_barang) as list_barang
                 FROM pembayaran a
                 LEFT OUTER JOIN penjualan b
                 ON (a.no_transaksi=b.no_transaksi)
                 LEFT OUTER JOIN penjualan_detail c
                 ON (b.no_transaksi=c.no_transaksi)
                 LEFT OUTER JOIN barang d
                 ON (c.id_barang=d.id)
                 WHERE b.id_customer = ?
                 GROUP BY a.id,a.no_transaksi,a.tgl_bayar,a.tgl_konfirmasi,a.bukti_bayar,
                         a.jenis_pembayaran,a.status,
                         b.total_harga";
         $list = DB::select($sql,[$id_customer]);
 
         return $list;
     }
}
