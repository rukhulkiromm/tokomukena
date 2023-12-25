<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Http\Requests\StoreCoaRequest;
use App\Http\Requests\UpdateCoaRequest;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Validator;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coa = Coa::all();
        // var_dump($coa);
        // dd;
    	// mengirim data pegawai ke view pegawai
        
    	return view('coa/view', 
                        [
                            'coa' => $coa,
                        ]
                    );
    }

    // untuk mendapatkan data coa
    public function fetchcoa()
    {
        $coa = Coa::all();
        return response()->json([
            'coa'=>$coa,
        ]);
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
    public function store(StoreCoaRequest $request)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
        $validator = Validator::make(
            $request->all(),
            [
                'kode_akun' => 'required|min:3',
                'nama_akun' => 'required',
                'header_akun' => 'required',
            ]
        );

        if($validator->fails()){
            // gagal
            return response()->json(
                [
                    'status' => 400,
                    'errors' => $validator->messages(),
                ]
            );
        }else{
            // berhasil

            // cek apakah tipenya input atau update
            // input => tipeproses isinya adalah tambah
            // update => tipeproses isinya adalah ubah
            
            if($request->input('tipeproses')=='tambah'){
                // simpan ke db
                Coa::create($request->all());
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Sukses Input Data',
                    ]
                );
            }else{
                // update ke db
                $coa = Coa::find($request->input('idcoahidden'));
            
                // proses update dari inputan form data
                $coa->kode_akun = $request->input('kode_akun');
                $coa->nama_akun = $request->input('nama_akun');
                $coa->header_akun = $request->input('header_akun');
                $coa->update(); //proses update ke db

                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Sukses Update Data',
                    ]
                );
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Coa $coa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $coa = Coa::find($id);
        if($coa)
        {
            return response()->json([
                'status'=>200,
                'coa'=> $coa,
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoaRequest $request, Coa $coa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         //hapus dari database
         $coa = Coa::findOrFail($id);
         $coa->delete();
         return view('coa/view',
             [
                 'coa' => $coa,
                 'status_hapus' => 'Sukses Hapus'
             ]
         );
    }
}
