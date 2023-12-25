<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Perusahaan;
use App\Http\Requests\StoreJurnalRequest;
use App\Http\Requests\UpdateJurnalRequest;


class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJurnalRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Jurnal $jurnal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurnal $jurnal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJurnalRequest $request, Jurnal $jurnal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurnal $jurnal)
    {
        //
    }
     // jurnal umum
     public function jurnalumum(){
        return view('laporan/jurnalumum');
    }

    // view data jurnal umum
    public function viewdatajurnalumum($periode){
        //query data
        $id_perusahaan = 1; //ini nanti diganti dengan session id perusahaan
        $perusahaan = Perusahaan::whereId($id_perusahaan)->first();

        $jurnal = Jurnal::viewjurnalumum($id_perusahaan,$periode);
        if($jurnal)
        {
            return response()->json([
                'status'=>200,
                'jurnal'=> $jurnal,
                'perusahaan' => $perusahaan
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Tidak ada data ditemukan.'
            ]);
        }
    }

    // buku besar
    public function bukubesar(){
        $id_perusahaan = 1; //ini nanti diganti dengan session id perusahaan
        $akun = Jurnal::viewakunbukubesar($id_perusahaan);
        return view('laporan/bukubesar',
                        [
                            'akun' => $akun
                        ]
                    );
    }

    // view data buku besar
    public function viewdatabukubesar($periode,$akun){
        //query data
        $id_perusahaan = 1; //ini nanti diganti dengan session id perusahaan
        $perusahaan = Perusahaan::whereId($id_perusahaan)->first();

        $saldoawal = Jurnal::viewsaldobukubesar($id_perusahaan,$periode,$akun);
        $posisi = Jurnal::viewposisisaldonormalakun($akun);

        $bukubesar = Jurnal::viewdatabukubesar($id_perusahaan,$periode,$akun);
            return response()->json([
                'status'=>200,
                'bukubesar'=> $bukubesar,
                'perusahaan' => $perusahaan,
                'saldoawal' => $saldoawal,
                'posisi' => $posisi
            ]);
       
    }
}
