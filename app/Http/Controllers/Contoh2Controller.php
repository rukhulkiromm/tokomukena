<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Contoh2Controller extends Controller
{
    //method untuk akses ke view
    public function show(){
        return view(
                        'layout',
                        [
                            'title'=>'Selamat Datang Web Framework',
                            'nama' => 'Di akses dari controller'
                        ]
        );
    }
}
