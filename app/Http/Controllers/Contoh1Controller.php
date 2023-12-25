<?php
 
namespace App\Http\Controllers;
 
use App\Models\Coa; //load model dari kelas model coa

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Str; //tambahan untuk penggunaan helper string
 
class Contoh1Controller extends Controller
{
    public function show()
    {
        return "Halo ini adalah contoh kontroller sederhana tanpa view";
    }

    public function validasilogin(Request $request)
    {
        if(
            ($request->username=='admin') and ($request->password=='admin')
           ){
                return 'berhasil login';
        }else{
                return 'gagal login';
        }
    }

    // coba tes form
    public function contohdom(){
        return view('contohdom');
    }

    // coba tes form
    public function contohajax(){
        return view('contohajax');
    }

    public function fetchcoa()
    {
        $coa = Coa::all();
        return response()->json([
            'coas'=>$coa,
        ]);
    }

    // tes helper masking
    public function cobamasking(){
        $angka = Str::of('081321405677')->mask('*', -3);
        echo $angka;
    }
    
    // tes helper custom
    public function teshelpercustom(){
        $angka = 5500;
        echo rupiah($angka);
    }
}