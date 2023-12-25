<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Contohform extends Model
{
    use HasFactory;
     // tambahan
     protected $table = "contohform";

     // untuk melist kolom yang dapat dimasukkan
     protected $fillable = [
         'nama_dokumen',
         'gambar_dokumen',
         'tgl_rilis',
         'klasifikasi_dokumen',
     ];
      // untuk mendapatkan list dokumen dengan list jenis dokumen dan hobi
    public static function getAllDocumentLists()
    {
        $sql = "SELECT a.id,a.nama_dokumen,a.gambar_dokumen,a.tgl_rilis,a.klasifikasi_dokumen,
                ifnull(b.list_dokumen,'-') as list_dokumen, ifnull(c.list_hobi,'-') as list_hobi 
                FROM contohform a 
                left outer join 
                (SELECT id_dokumen, 
                        GROUP_CONCAT(nama_dokumen ORDER BY nama_dokumen ASC) as list_dokumen 
                 FROM contohform_jenis_dokumen GROUP BY id_dokumen
                ) b on (a.id=b.id_dokumen) 
                left outer join 
                (SELECT id_dokumen, GROUP_CONCAT(hobi ORDER BY hobi ASC) as list_hobi 
                 from contohform_hobi GROUP BY id_dokumen) c on (a.id=c.id_dokumen) 
                GROUP BY a.id,a.nama_dokumen,a.gambar_dokumen,
                         a.tgl_rilis,a.klasifikasi_dokumen,b.list_dokumen, c.list_hobi";
        $c = DB::select($sql);

        return $c;
    }
 // untuk mendapatkan data list dokumen sesuai id dokumen
 public static function getAllDocumentListsByIdDokumen($id)
 {
     $sql = "SELECT a.id,a.nama_dokumen,a.gambar_dokumen,a.tgl_rilis,a.klasifikasi_dokumen,
                    ifnull(b.list_dokumen,'-') as list_dokumen, 
                    ifnull(c.list_hobi,'-') as list_hobi 
             FROM contohform a 
             left outer join 
             (SELECT id_dokumen, GROUP_CONCAT(nama_dokumen) as list_dokumen 
              FROM contohform_jenis_dokumen GROUP BY id_dokumen) b on (a.id=b.id_dokumen) 
             left outer join 
             (SELECT id_dokumen, GROUP_CONCAT(hobi) as list_hobi from contohform_hobi 
              GROUP BY id_dokumen) c on (a.id=c.id_dokumen) 
             GROUP BY a.id,a.nama_dokumen,a.gambar_dokumen,
                      a.tgl_rilis,a.klasifikasi_dokumen,b.list_dokumen, c.list_hobi 
             HAVING a.id=?";
     $c = DB::select($sql,[$id]);

     return $c;
 }
// hapus tabel turunan dari contohform
public static function delHobiJenisDokumen($id){
    // hapus contohform_hobi
    $sql = "DELETE FROM contohform_hobi WHERE id_dokumen = ?";
    $nrd = DB::delete($sql,[$id]);

    // hapus contohform_hobi
    $sql = "DELETE FROM contohform_jenis_dokumen WHERE id_dokumen = ?";
    $nrd = DB::delete($sql,[$id]);
}

// memasukkan ke tabel contohform_jenis_dokumen (tabel anak dari contoh form)
public static function inputJenisDokumen($id_dokumen, $nama_dokumen){
    $sql = "insert into contohform_jenis_dokumen(id_dokumen, nama_dokumen) values (?, ?)";
    $nrd = DB::insert($sql,[$id_dokumen, $nama_dokumen]);
}

// memasukkan ke tabel contohform_jenis_dokumen (tabel anak dari contoh form)
public static function inputHobi($id_dokumen, $hobi){
    $sql = "insert into contohform_hobi(id_dokumen, hobi) values (?, ?)";
    $nrd = DB::insert($sql,[$id_dokumen, $hobi]);
}

// dapatkan id dokumen terakhir
public static function getLastId(){
    $sql = "SELECT max(id) as mak_id
            FROM contohform";
    $c = DB::select($sql);

    return $c;
 }
}
