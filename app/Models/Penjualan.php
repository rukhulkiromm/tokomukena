<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    use HasFactory;
    use HasFactory;

    protected $table = 'penjualan';
    protected $fillable = ['no_transaksi','id_customer','tgl_transaksi','tgl_expired','total_harga','status'];

    // untuk melihat data barang
    public static function getViewBarang()
    {
        // query barang
        $sql = "SELECT * FROM barang";
        $barang = DB::select($sql);

        return $barang;

    }

    // untuk melihat data barang berdasarkan id
    public static function getViewBarangId($id)
    {
        // query barang berdasarkan id
        $sql = "SELECT * FROM barang WHERE id = ?";
        $barang = DB::select($sql,[$id]);

        return $barang;

    }

    // untuk melihat data invoice
    public static function getListInvoice($id_customer)
    {
        // query kode perusahaan
        $sql = "SELECT * FROM penjualan WHERE id_customer = ? AND status='siap_bayar'";
        $barang = DB::select($sql,[$id_customer]);

        return $barang;

    }

    // cekout
    public static function checkout($id_customer)
    {

        // dapatkan nomor transaksi
        $sql = "
                    SELECT no_transaksi
                    FROM penjualan
                    WHERE id_customer = ? AND status ='pesan'
                ";
        $barang = DB::select($sql,[$id_customer]);
        foreach($barang as $b):
            $no_transaksi = $b->no_transaksi;
        endforeach;

        $affected = DB::table('penjualan')
        ->where('no_transaksi', $no_transaksi)
        ->update(['status' => 'siap_bayar']);
    }

    // prosedur input data penjualan 
    public static function inputPenjualan($id_customer,$total_harga,$id_barang,$jml_barang,$harga_barang,$total){
        date_default_timezone_set('Asia/Jakarta');
        // query apakah ada di keranjang
        // query kode perusahaan
        $sql = "SELECT COUNT(*) as jml FROM penjualan WHERE id_customer = ? AND status not in ('expired','selesai','siap_bayar','konfirmasi_bayar')";
        $barang = DB::select($sql,[$id_customer]);
        foreach($barang as $b):
            $jml = $b->jml;
        endforeach;

        // jika jumlahnya 0 maka buat nomor transaksi baru
        // ['no_transaksi','id_customer','tgl_transaksi','tgl_expired','total_harga','status'];
        if($jml==0){

            // dapatkan nomor faktur terakhir cth format FK-0004
            $sql = "SELECT SUBSTRING(IFNULL(MAX(no_transaksi),'FK-0000'),4)+0 AS no FROM penjualan";
            $barang = DB::select($sql);
            foreach($barang as $b):
                $urutan = $b->no;
            endforeach;

            // pembentukan nomor faktur
            $urutan = $urutan + 1;
            $str = (string)$urutan;
            $no  = str_pad($str,4,"0",STR_PAD_LEFT); //menambahkan 0 di samping kiri angka
            $faktur = 'FK-'.$no;

            // masukkan ke tabel induk dulu yaitu di tabel penjualan
            
            $date = date('Y-m-d H:i:s');
            $date_plus_3=Date('Y-m-d H:i:s', strtotime('+3 days')); //tambahkan 3 hari untuk expired datenya
            DB::table('penjualan')->insert([
                'no_transaksi' => $faktur,
                'id_customer' => $id_customer,
                'tgl_transaksi' => $date,
                'tgl_expired' => $date_plus_3,
                'total_harga' => $total_harga,
                'status' => 'pesan' //isinya pesan, selesai, expired
            ]);

            // masukkan ke tabel detail_penjualan
            DB::table('penjualan_detail')->insert([
                'no_transaksi' => $faktur,
                'id_barang' => $id_barang,
                'harga_barang' => $harga_barang,
                'jml_barang' => $jml_barang,
                'total' => $total,
                'tgl_transaksi' => $date,
                'tgl_expired' => $date_plus_3
            ]);

            // update stok di tabel barang menjadi berkurang
            // dapatkan stok dulu
            $sql = "SELECT stok FROM barang WHERE id = ?";
            $barang = DB::select($sql,[$id_barang]);
            foreach($barang as $b):
                $stok = $b->stok;
            endforeach;

            $stok_akhir = $stok - $jml_barang;
            $affected = DB::table('barang')
              ->where('id', $id_barang)
              ->update(['stok' => $stok_akhir]);
        }else{
            // jika sudah ada nomor fakturnya
            // 1. update transaksi yang masih menggantung ke expired jika di tabel detail sudah expired semua
            //    dapatkan max tgl expired
            $sql = "SELECT no_transaksi,MAX(tgl_expired) as mak_expired FROM penjualan_detail WHERE  
                    no_transaksi IN 
                    (
                        SELECT no_transaksi
                        FROM penjualan
                        WHERE id_customer = ? AND status NOT IN ('selesai','expired','siap_bayar','konfirmasi_bayar')
                    ) 
                    GROUP BY no_transaksi
                   ";
            $barang = DB::select($sql,[$id_customer]);
            foreach($barang as $b):
                $mak_expired = $b->mak_expired;
                $no_transaksi = $b->no_transaksi;
            endforeach;

            // update ke tabel transaksi expirednya menjadi expired terlama dari detail penjualan
            $affected = DB::table('penjualan')
              ->where('no_transaksi', $no_transaksi)
              ->update(['tgl_expired' => $mak_expired]);

            // jika mak expired sudah melewati masa sekarang
            // maka lakukan update status pesanan menjadi 'expired'
            $date = date('Y-m-d H:i:s');
            if($date>$mak_expired){
                // update status menjadi expired
                    $affected = DB::table('penjualan')
                ->where('no_transaksi', $no_transaksi)
                ->update(['status' => 'expired']);

                // kembalikan stok
                $sql = "SELECT id_barang,jml_barang FROM penjualan_detail WHERE  
                        no_transaksi = ?
                    ";
                $barang = DB::select($sql,[$no_transaksi]);
                foreach($barang as $b):
                    $id_barang = $b->id_barang;
                    $jml_barang_lama = $b->jml_barang;
                    // query stok
                    // kembalikan stok
                    $sql = "SELECT stok FROM barang WHERE id = ?";
                    $datastok = DB::select($sql,[$id_barang]);
                    foreach($datastok as $c):
                        $stok = $c->stok;
                    endforeach;

                    $stok_akhir = $stok + $jml_barang_lama;
                    $affected = DB::table('barang')
                    ->where('id', $id_barang)
                    ->update(['stok' => $stok_akhir]);
                endforeach;

                // buat nomor faktur baru dan masukkan ke tabel
                // dapatkan nomor faktur terakhir cth format FK-0004
                $sql = "SELECT SUBSTRING(IFNULL(MAX(no_transaksi),'FK-0000'),4)+0 AS no FROM penjualan";
                $barang = DB::select($sql);
                foreach($barang as $b):
                    $urutan = $b->no;
                endforeach;

                // pembentukan nomor faktur
                $urutan = $urutan + 1;
                $str = (string)$urutan;
                $no  = str_pad($str,4,"0",STR_PAD_LEFT); //menambahkan 0 di samping kiri angka
                $faktur = 'FK-'.$no;

                // masukkan ke tabel induk dulu yaitu di tabel penjualan
                
                $date = date('Y-m-d H:i:s');
                $date_plus_3=Date('Y-m-d H:i:s', strtotime('+3 days')); //tambahkan 3 hari untuk expired datenya
                DB::table('penjualan')->insert([
                    'no_transaksi' => $faktur,
                    'id_customer' => $id_customer,
                    'tgl_transaksi' => $date,
                    'tgl_expired' => $date_plus_3,
                    'total_harga' => $total_harga,
                    'status' => 'pesan' //isinya pesan, selesai, expired
                ]);

                // masukkan ke tabel detail_penjualan
                DB::table('penjualan_detail')->insert([
                    'no_transaksi' => $faktur,
                    'id_barang' => $id_barang,
                    'harga_barang' => $harga_barang,
                    'jml_barang' => $jml_barang,
                    'total' => $total,
                    'tgl_transaksi' => $date,
                    'tgl_expired' => $date_plus_3
                ]);

                // update stok di tabel barang menjadi berkurang
                // dapatkan stok dulu
                $sql = "SELECT stok FROM barang WHERE id = ?";
                $barang = DB::select($sql,[$id_barang]);
                foreach($barang as $b):
                    $stok = $b->stok;
                endforeach;

                $stok_akhir = $stok - $jml_barang;
                $affected = DB::table('barang')
                ->where('id', $id_barang)
                ->update(['stok' => $stok_akhir]);
                // akhir buat nomor faktur baru

            }else{
                // belum mencapai masa expired, maka
                // tambahkan total belanja ke tabel penjualan_detail
                // cek untuk id barang yang sama, maka tidak usah tambah lagi, tapi cukup jml belanjanya
                // yg ditambahkan
                // selain itu masukkan lagi ke penjualan detail
                // 1. cek apakah yg diinputkan adalah id barang yang sudah ada di keranjang atau tidak
                $sql = "SELECT id_barang,jml_barang,no_transaksi FROM penjualan_detail
                        WHERE  
                        no_transaksi IN 
                        (
                            SELECT no_transaksi
                            FROM penjualan
                            WHERE id_customer = ? AND status NOT IN ('selesai','expired','siap_bayar','konfirmasi_bayar')
                        ) AND id_barang = ?
                        ";
                $barang = DB::select($sql,[$id_customer,$id_barang]);
                $cek = 0;
                foreach($barang as $b):
                    $id_barang_tabel = $b->id_barang;
                    $jml_barang_tabel = $b->jml_barang;
                    $no_transaksi_tabel = $b->no_transaksi;
                    $cek = 1;
                    // tambahkan jml barangnya dan tamnbahkan masa expirednya
                    $date_plus_3=Date('Y-m-d H:i:s', strtotime('+3 days')); //tambahkan 3 hari untuk expired datenya
                    $jml_barang_akhir = $jml_barang + $jml_barang_tabel;
                    $total_tagihan  = $harga_barang * $jml_barang_akhir;
                    $affected = DB::table('penjualan_detail')
                    ->where('no_transaksi','=', $no_transaksi_tabel)
                    ->where('id_barang', '=',$id_barang_tabel)
                    ->update(['jml_barang' => $jml_barang_akhir,'total'=> $total_tagihan,
                              'tgl_transaksi' => $date_plus_3
                             ]);

                    // dapatkan stok dulu
                    $sql = "SELECT stok FROM barang WHERE id = ?";
                    $barang = DB::select($sql,[$id_barang_tabel]);
                    foreach($barang as $b):
                        $stok = $b->stok;
                    endforeach;

                    $stok_akhir = $stok - $jml_barang;
                    $affected = DB::table('barang')
                    ->where('id', $id_barang)
                    ->update(['stok' => $stok_akhir]);

                endforeach;

                // jika nilai variabel cek == 0 maka ini adalah inputan baru
                if($cek==0){
                    // 
                    // buat nomor faktur baru dan masukkan ke tabel
                    // dapatkan nomor faktur terakhir cth format FK-0004
                    $sql = "SELECT max(no_transaksi) as no_transaksi  FROM penjualan
                            WHERE id_customer = ? AND status NOT IN ('selesai','expired','siap_bayar','konfirmasi_bayar')
                           ";
                    $barang = DB::select($sql,[$id_customer]);
                    foreach($barang as $b):
                        $no_transaksi = $b->no_transaksi;
                    endforeach;

                    $sql = "SELECT total_harga  FROM penjualan
                            WHERE no_transaksi = ? 
                           ";
                    $barang = DB::select($sql,[$no_transaksi]);
                    foreach($barang as $b):
                        $total_harga_lama = $b->total_harga;
                    endforeach;

                    // $total_harga_lama = $b->total_harga;
                    // masukkan ke tabel induk dulu yaitu di tabel penjualan
                    $total_harga_baru = $total_harga+$total_harga_lama;
                    $date = date('Y-m-d H:i:s');
                    $date_plus_3=Date('Y-m-d H:i:s', strtotime('+3 days')); //tambahkan 3 hari untuk expired datenya
                    // update total harga di penjualan karena sudah ditambah item baru
                    $affected = DB::table('penjualan')
                    ->where('no_transaksi', $no_transaksi)
                    ->update(
                                [   'tgl_expired' => $date_plus_3,
                                    'total_harga'=> $total_harga_baru, 
                                ]
                            );

                    // masukkan ke tabel detail_penjualan
                    DB::table('penjualan_detail')->insert([
                        'no_transaksi' => $no_transaksi,
                        'id_barang' => $id_barang,
                        'harga_barang' => $harga_barang,
                        'jml_barang' => $jml_barang,
                        'total' => $total,
                        'tgl_transaksi' => $date,
                        'tgl_expired' => $date_plus_3
                    ]);

                    // update stok di tabel barang menjadi berkurang
                    // dapatkan stok dulu
                    $sql = "SELECT stok FROM barang WHERE id = ?";
                    $barang = DB::select($sql,[$id_barang]);
                    foreach($barang as $b):
                        $stok = $b->stok;
                    endforeach;

                    $stok_akhir = $stok - $jml_barang;
                    $affected = DB::table('barang')
                    ->where('id', $id_barang)
                    ->update(['stok' => $stok_akhir]);
                    // akhir buat nomor faktur baru
                    // 
                }
            }
        }
        
    }

    // view keranjang belanja
    public static function viewKeranjang($id_customer){
        $sql = "SELECT  a.no_transaksi,
                        c.nama_barang,
                        c.foto,
                        c.harga,
                        b.tgl_transaksi,
                        b.tgl_expired,
                        b.jml_barang,
                        b.total,
                        a.status,
                        b.id as id_penjualan_detail
                FROM penjualan a
                JOIN penjualan_detail b
                ON (a.no_transaksi=b.no_transaksi)
                JOIN barang c 
                ON (b.id_barang = c.id)
                WHERE a.id_customer = ? AND a.status 
                not in ('selesai','expired','siap_bayar','konfirmasi_bayar')";
        $barang = DB::select($sql,[$id_customer]);
        return $barang;
    }

    // view data siap bayar
    // view keranjang belanja
    public static function viewSiapBayar($id_customer){
        $sql = "SELECT  a.no_transaksi,
                        c.nama_barang,
                        c.foto,
                        c.harga,
                        b.tgl_transaksi,
                        b.tgl_expired,
                        b.jml_barang,
                        b.total,
                        a.status,
                        b.id as id_penjualan_detail,
                        a.id as id_penjualan
                FROM penjualan a
                JOIN penjualan_detail b
                ON (a.no_transaksi=b.no_transaksi)
                JOIN barang c 
                ON (b.id_barang = c.id)
                WHERE a.id_customer = ? AND a.status 
                in ('siap_bayar')";
        $barang = DB::select($sql,[$id_customer]);
        return $barang;
    }

    public static function jmlviewSiapBayar($id_customer){
        $sql = "SELECT  count(*) as jml
                FROM penjualan a
                JOIN penjualan_detail b
                ON (a.no_transaksi=b.no_transaksi)
                JOIN barang c 
                ON (b.id_barang = c.id)
                WHERE a.id_customer = ? AND a.status 
                in ('siap_bayar')";
        $barang = DB::select($sql,[$id_customer]);
        return $barang;
    }

   // untuk menghapus data penjualan detail
   public static function hapuspenjualandetail($id_penjualan_detail){
       
    // cek apakah jml stok
    $sql = "SELECT no_transaksi
            FROM penjualan_detail
            WHERE id = ?";
    $barang = DB::select($sql,[$id_penjualan_detail]);
    foreach($barang as $b):
        $no_transaksi = $b->no_transaksi;
    endforeach;

    $sql = "SELECT count(*) as jml
            FROM penjualan_detail
            WHERE no_transaksi = ?";
    $barang = DB::select($sql,[$no_transaksi]);
    foreach($barang as $b):
        $jml = $b->jml;
    endforeach;
   
    $sql = "DELETE FROM penjualan_detail WHERE id = ?";
    $nrd = DB::delete($sql,[$id_penjualan_detail]);

    if($jml==1){
        // hapus induknya juga
        $sql = "DELETE FROM penjualan WHERE no_transaksi = ?";
        $nrd = DB::delete($sql,[$no_transaksi]);

    }
}

    // kembalikan stok
    public static function kembalikanstok($id_penjualan_detail){

            $sql = "SELECT jml_barang,id_barang FROM penjualan_detail WHERE id = ?";
            $barang = DB::select($sql,[$id_penjualan_detail]);
            foreach($barang as $b):
                $jml_barang = $b->jml_barang;
                $id_barang = $b->id_barang;
            endforeach;

            $sql = "SELECT stok FROM barang WHERE id = ?";
            $barang = DB::select($sql,[$id_barang]);
            foreach($barang as $b):
                $stok = $b->stok;
            endforeach;

            $stok_akhir = $stok + $jml_barang;
            $affected = DB::table('barang')
              ->where('id', $id_barang)
              ->update(['stok' => $stok_akhir]);
    }

    // dapatkan jumlah barang
    public static function getJmlBarang($id_customer){
        $sql = "SELECT count(*) as jml FROM penjualan_detail WHERE no_transaksi IN (SELECT no_transaksi FROM penjualan WHERE id_customer = ? AND status NOT IN ('expired','hapus','siap_bayar','konfirmasi_bayar','selesai'))";
        $barang = DB::select($sql,[$id_customer]);
        foreach($barang as $b):
            $jml = $b->jml;
        endforeach;
        return $jml;
    }

    public static function getJmlInvoice($id_customer){
        $sql = "SELECT count(*) as jml FROM penjualan 
                WHERE status = 'siap_bayar' AND id_customer = ?";
        $barang = DB::select($sql,[$id_customer]);
        foreach($barang as $b):
            $jml = $b->jml;
        endforeach;
        return $jml;
    }
}
