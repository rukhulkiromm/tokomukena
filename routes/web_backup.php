<?php

use Illuminate\Support\Facades\Route;

// tambahkan akses ke contoh1controller
use App\Http\Controllers\Contoh1Controller;

Route::get('/contoh1', [Contoh1Controller::class, 'show']);

Route::post('/contoh1/validasilogin',[App\Http\Controllers\Contoh1Controller::class, 'validasilogin']);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//contoh routes menggunakan resource
Route::get('/coa2', [Contoh1Controller::class, 'index']);

Route::resource('coa', App\Http\Controllers\CoaController::class);

Route::get('/', function () {
    return view('login',);
});

Route::get('/coba', function () {
    return view('coba', ['nama'=>'Farel Prayoga']);
});
Route::get('/coba_layout', function () {
    return view('layout', ['nama'=>'Farel Prayoga',
                           'title'=>'Video'
                        ]);
});

