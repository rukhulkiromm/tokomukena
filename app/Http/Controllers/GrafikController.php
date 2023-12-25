<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// tambahan
use App\Models\Grafik;

class GrafikController extends Controller
{
    // view bulan berjalan
    public function viewPenjualanBlnBerjalan(){
        $grafik = Grafik::viewBulanBerjalan();
        return view('grafik/bulanberjalan',
                        [
                            'grafik' => $grafik
                        ]
                    );
    }

    // view status penjualan
    public function viewStatusPenjualan(){
        $grafik = Grafik::viewStatusPenjualan();
        return view('grafik/statuspenjualan',
                        [
                            'grafik' => $grafik
                        ]
                    );
    }

    // view jml barang terjual
    public function viewJmlBarangTerjual(){
        $grafik = Grafik::viewJmlBarangTerjual();
        return view('grafik/jmlbarangterjual',
                        [
                            'grafik' => $grafik
                        ]
                    );
    }
}
