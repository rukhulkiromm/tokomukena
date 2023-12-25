<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Jurnal extends Model
{
    use HasFactory;
      // use HasFactory;
      protected $table = "jurnal";

      // untuk melist kolom yang dapat dimasukkan
      protected $fillable = [
          'id_transaksi',
          'id_perusahaan',
          'kode_akun',
          'tgl_jurnal',
          'posisi_d_c',
          'nominal',
          'kelompok',
          'transaksi'
      ];
  
      // pencatatan jurnal
      public static function inputjurnal($id_perusahaan,$transaksi,$kelompok,$idtransaksi,$nominal)
      {
          date_default_timezone_set('Asia/Jakarta');
          $date = date('Y-m-d');
  
          // query kode perusahaan
          $sql = "SELECT * 
                  FROM transaksi_coa
                  WHERE id_perusahaan = ? AND transaksi = ?
                  AND kelompok = ?";
          $list = DB::select($sql,[$id_perusahaan,$transaksi,$kelompok]);
  
          foreach($list as $l):
              DB::table('jurnal')->insert([
                  'id_transaksi' => $idtransaksi,
                  'id_perusahaan' => $id_perusahaan,
                  'kode_akun' => $l->kode_akun,
                  'tgl_jurnal' => $date,
                  'posisi_d_c' => $l->posisi,
                  'nominal' => $nominal,
                  'kelompok' => $kelompok,
                  'transaksi' => $transaksi,
              ]);
          endforeach;
  
      }
  
      // view data jurnal umum berdasarkan periode
      public static function viewjurnalumum($id_perusahaan,$periode){
          // periode memiliki format YYYY-MM
           $sql = "   SELECT a.*,b.nama_akun
                      FROM jurnal a JOIN coa b 
                      ON (a.kode_akun=b.kode_akun)
                      WHERE a.id_perusahaan = ? AND DATE_FORMAT(tgl_jurnal, '%Y-%m')=?
                      ORDER BY 1 ASC
                  ";
          $list = DB::select($sql,[$id_perusahaan,$periode]);
          return $list;
      }
  
      // view data data-data akun buku besar suatu perusahaan
      public static function viewakunbukubesar($id_perusahaan){
          // periode memiliki format YYYY-MM
           $sql = "   SELECT b.kode_akun, b.nama_akun
                      FROM jurnal a JOIN coa b 
                      ON (a.kode_akun=b.kode_akun)
                      WHERE a.id_perusahaan = ? 
                      GROUP BY b.kode_akun, b.nama_akun
                      ORDER BY 2 ASC
                  ";
          $list = DB::select($sql,[$id_perusahaan]);
          return $list;
      }
  
      // view data jurnal umum berdasarkan periode
      public static function viewdatabukubesar($id_perusahaan,$periode,$akun){
          // periode memiliki format YYYY-MM
           $sql = "   SELECT a.*,b.nama_akun
                      FROM jurnal a JOIN coa b 
                      ON (a.kode_akun=b.kode_akun)
                      WHERE a.id_perusahaan = ? AND DATE_FORMAT(tgl_jurnal, '%Y-%m')= ?
                      AND b.kode_akun = ?
                      ORDER BY 1 ASC
                  ";
          $list = DB::select($sql,[$id_perusahaan,$periode,$akun]);
          return $list;
      }
  
      // viewposisisdb saldo normal
      public static function viewposisisaldonormalakun($akun){
          $akun = substr($akun,0,1);
          switch ($akun) {
              case '1':
                  $posisi_saldo_normal = 'd';
                break;
              case '2':
                  $posisi_saldo_normal = 'c';
                break;
              case '3':
                  $posisi_saldo_normal= 'c';
                break;
              case '4':
                  $posisi_saldo_normal = 'c';
                break;
              case '5':
                  $posisi_saldo_normal = 'd';
                break;
            }
          return $posisi_saldo_normal;
      }
  
      // view saldo buku besar bulan sebelumnya berdasarkan periode dan kode akun
      public static function viewsaldobukubesar($id_perusahaan,$periode,$akun){
          // dapatkan posisi saldo normal akun tsb
          $sql = "   SELECT header_akun
                      FROM coa  
                      WHERE kode_akun = ?
                  ";
          $list = DB::select($sql,[$akun]);
          foreach($list as $l):
              switch ($l->header_akun) {
                  case '1':
                      $posisi_saldo_normal = 'd';
                    break;
                  case '2':
                      $posisi_saldo_normal = 'c';
                    break;
                  case '3':
                      $posisi_saldo_normal= 'c';
                    break;
                  case '4':
                      $posisi_saldo_normal = 'c';
                    break;
                  case '5':
                      $posisi_saldo_normal = 'd';
                    break;
                }
          endforeach;
  
          $sql = "   SELECT tbl1.posisi_d_c,ifnull(tbl2.total,0) as nominal FROM
                      (
                          SELECT 'c' posisi_d_c
                          UNION
                          SELECT 'd' posisi_d_c
                      ) tbl1
                      LEFT OUTER JOIN
                      (
                          Select a.posisi_d_c,sum(a.nominal) as total
                          FROM jurnal a
                          JOIN coa b ON (a.kode_akun=b.kode_akun)
                          WHERE a.kode_akun = ? AND a.id_perusahaan = ?
                          AND date_format(a.tgl_jurnal,'%Y-%m') < ?
                          GROUP BY  a.posisi_d_c
                      ) tbl2
                      ON (tbl1.posisi_d_c = tbl2.posisi_d_c)
                  ";
          $list = DB::select($sql,[$akun,$id_perusahaan,$periode]);
          $saldo_debet = 0;
          $saldo_kredit = 0;
          foreach($list as $cacah):
              if(strcmp($cacah->posisi_d_c,'d')==0){
                  $saldo_debet = $saldo_debet + $cacah->nominal;
              }else{
                  $saldo_kredit = $saldo_kredit + $cacah->nominal;
              }
          endforeach;
  
          if(strcmp($posisi_saldo_normal,'d')==0){
              $saldo = $saldo_debet - $saldo_kredit;
          }else{
              $saldo =  $saldo_kredit - $saldo_debet;
          }
          return $saldo;
      }
}
