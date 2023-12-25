<?php

namespace App\Http\Controllers;

use App\Models\Contohform;
use App\Http\Requests\StoreContohformRequest;
use App\Http\Requests\UpdateContohformRequest;

// tambahan
use Illuminate\Support\Facades\Storage; //tambahan 
use Illuminate\Support\Facades\File; //untuk hapus file
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContohformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // mengambil data coa dan perusahaan dari database
    	$c = Contohform::getAllDocumentLists();

        return view('contohform/view',
            [
                'contohform' => $c
            ]
        );
    }
     // contoh form
     public function fetchcontohform()
     {
         $c = Contohform::getAllDocumentLists();
         return response()->json([
             'contohform'=>$c,
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
    public function store(StoreContohformRequest $request)
    {
         //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
         $validator = Validator::make(
            $request->all(),
            [
                'nama_dokumen' => 'required|min:3',
                'tgl_rilis' => 'required',
                'klasifikasi_dokumen' => 'required',
                'gambar_dokumen' => 'file|image|mimes:jpeg,png,jpg|max:2048'
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

                $file = $request->file('gambar_dokumen');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $tujuan_upload = 'gambar';
		        $file->move($tujuan_upload,$fileName);

                $empData = ['nama_dokumen' => $request->input('nama_dokumen'), 'gambar_dokumen' => $fileName, 'tgl_rilis' => $request->input('tgl_rilis'), 'klasifikasi_dokumen' => $request->input('klasifikasi_dokumen')];
		        Contohform::create($empData);

                // pemrosesan jenis dokumen
                $jd = $request->input('jenis_dok');
                // dapatkan id terakhir setelah diinputkan
                $l = Contohform::getLastId();
                $idmaks = $l[0]->mak_id; //dapatkan id terakhir dan simpan ke idmaks
                
                // masukkan setiap data jenis dokumen dari select2
                foreach ($jd as $value) {
                    // masukkan ke db
                    Contohform::inputJenisDokumen($idmaks, $value);
                }

                // proses checkbox
                $renang = $request->input('renang');
                if(isset($renang)){
                    // masukkan ke db
                    Contohform::inputHobi($idmaks, $renang);
                }
                $musik = $request->input('musik');
                if(isset($musik)){
                    // masukkan ke db
                    Contohform::inputHobi($idmaks, $musik);
                }
                $tidur = $request->input('tidur');
                if(isset($tidur)){
                    // masukkan ke db
                    Contohform::inputHobi($idmaks, $tidur);
                }
                
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Sukses Input Data',
                    ]
                );
            }else{
                // update ke db
                // cek dulu jika ada file yg diupload lagi maka prosedur input image dilakukan lagi
                if($request->hasFile('gambar_dokumen')){ 
                    // jalankan prosedur upload ke server
                    $file = $request->file('gambar_dokumen');
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $tujuan_upload = 'gambar';
                    $file->move($tujuan_upload,$fileName);

                    // update ke db
                    $c = Contohform::find($request->input('idcontohformhidden'));
                
                    // proses update dari inputan form data
                    $c->nama_dokumen = $request->input('nama_dokumen');
                    $c->gambar_dokumen = $fileName;
                    $c->tgl_rilis = $request->input('tgl_rilis');
                    $c->klasifikasi_dokumen = $request->input('klasifikasi_dokumen');
                    $c->update(); //proses update ke db

                }else{
                    // kalau tidak maka nilainya tidak perlu di update
                    // update ke db
                    // dapatkan record yang mau diupdate berdasarkan idnya
                    $c = Contohform::find($request->input('idcontohformhidden'));
                
                    // proses update dari inputan form data
                    $c->nama_dokumen = $request->input('nama_dokumen');
                    $c->gambar_dokumen = $request->input('namadokumenlama');
                    $c->tgl_rilis = $request->input('tgl_rilis');
                    $c->klasifikasi_dokumen = $request->input('klasifikasi_dokumen');
                    $c->update(); //proses update ke db
                }

                // hapus dulu baru masukin lagi
                Contohform::delHobiJenisDokumen($request->input('idcontohformhidden'));

                // masukin lagi jenis dokumen
                $jd = $request->input('jenis_dok');
                foreach ($jd as $value) {
                    // masukkan ke db
                    Contohform::inputJenisDokumen($request->input('idcontohformhidden'), $value);
                }

                // proses checkbox hobi
                $renang = $request->input('renang');
                if(isset($renang)){
                    // masukkan ke db
                    Contohform::inputHobi($request->input('idcontohformhidden'), $renang);
                }
                $musik = $request->input('musik');
                if(isset($musik)){
                    // masukkan ke db
                    Contohform::inputHobi($request->input('idcontohformhidden'), $musik);
                }
                $tidur = $request->input('tidur');
                if(isset($tidur)){
                    // masukkan ke db
                    Contohform::inputHobi($request->input('idcontohformhidden'), $tidur);
                }

                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'update data',
                    ]
                );
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
         // $c = Contohform::find($id);
         $c = Contohform::getAllDocumentListsByIdDokumen($id);
         if($c)
         {
             return response()->json([
                 'status'=>200,
                 'c'=> $c,
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
    public function update(UpdateContohformRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         //hapus dari database
         $c = Contohform::findOrFail($id);

         // hapus file
         $pathfile = public_path('gambar/' .$c->gambar_dokumen);
         File::delete($pathfile);
         // hapus record di database
         $c->delete();
 
         // hapus tabel anaknya
         Contohform::delHobiJenisDokumen($id);
 
         return view('contohform/view',
             [
                 'contohform' => $c,
                 'status_hapus' => 'Sukses Hapus '
             ]
         );
     }
    
}
