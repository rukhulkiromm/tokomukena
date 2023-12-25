<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboardbootstrap', function () {
    return view('dashboardbootstrap');
})->middleware(['auth', 'verified'])->name('dashboardbootstrap');

Route::get('contoh1/fetchcoa', function () {
    return view('fetchcoa');
})->middleware(['auth', 'verified'])->name('contoh1/fetchcoa');

Route::get('contoh1/contohajax', function () {
    return view('contohajax');
})->middleware(['auth', 'verified'])->name('contoh1/contohajax');

Route::get('contoh1/contohdom', function () {
    return view('contohdom');
})->middleware(['auth', 'verified'])->name('contoh1/contohdom');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// untuk master data perusahaan
Route::resource('perusahaan', App\Http\Controllers\PerusahaanController::class)->middleware(['auth','admin']);
Route::get('/perusahaan/destroy/{id}', [App\Http\Controllers\PerusahaanController::class,'destroy'])->middleware(['auth','admin']);

// untuk master data coa
// jika ingin menambahkan routes baru selain default resource di tambah di awal
// sebelum route resource
Route::get('/coa/fetchcoa', [App\Http\Controllers\CoaController::class,'fetchcoa'])->middleware(['auth','admin']);
Route::get('/coa/edit/{id}', [App\Http\Controllers\CoaController::class,'edit'])->middleware(['auth','admin']);
Route::get('/coa/destroy/{id}', [App\Http\Controllers\CoaController::class,'destroy'])->middleware(['auth','admin']);
Route::resource('coa', App\Http\Controllers\CoaController::class)->middleware(['auth','admin']);

// contohdom dan ajax
Route::get('/contohdom', [App\Http\Controllers\Contoh1Controller::class,'contohdom']);
Route::get('/contohajax', [App\Http\Controllers\Contoh1Controller::class,'contohajax']);
Route::get('contoh1/rupiah', [App\Http\Controllers\Contoh1Controller::class,'teshelpercustom'])->middleware(['auth','admin']);
Route::get('contoh1/masking', [App\Http\Controllers\Contoh1Controller::class,'cobamasking'])->middleware(['auth','admin']);
Route::get('contoh1/fetchcoa', [App\Http\Controllers\Contoh1Controller::class,'fetchcoa']);

// contoh form
Route::get('contohform/fetchcontohform', [App\Http\Controllers\ContohformController::class,'fetchcontohform'])->middleware(['auth','admin']);
Route::get('contohform/edit/{id}', [App\Http\Controllers\ContohformController::class,'edit'])->middleware(['auth','admin']);
Route::get('contohform/destroy/{id}', [App\Http\Controllers\ContohformController::class,'destroy'])->middleware(['auth','admin']);
Route::resource('contohform', App\Http\Controllers\ContohformController::class)->middleware(['auth','admin']);

// untuk transaksi penjualan
Route::get('/penjualan/barang/{id}', [App\Http\Controllers\PenjualanController::class,'getDataBarang'])->middleware(['auth']);
Route::get('/penjualan/keranjang', [App\Http\Controllers\PenjualanController::class,'keranjang'])->middleware(['auth']);
Route::get('/penjualan/destroypenjualandetail/{id}', [App\Http\Controllers\PenjualanController::class,'destroypenjualandetail'])->middleware(['auth']);
Route::get('/penjualan/barang', [App\Http\Controllers\PenjualanController::class,'getDataBarangAll'])->middleware(['auth']);
Route::get('/penjualan/jmlbarang', [App\Http\Controllers\PenjualanController::class,'getJumlahBarang'])->middleware(['auth']);
Route::get('/penjualan/keranjangjson', [App\Http\Controllers\PenjualanController::class,'keranjangjson'])->middleware(['auth']);
Route::get('/penjualan/checkout', [App\Http\Controllers\PenjualanController::class,'checkout'])->middleware(['auth']);
Route::get('penjualan/invoice', [App\Http\Controllers\PenjualanController::class,'invoice'])->middleware(['auth']);
Route::get('penjualan/jmlinvoice', [App\Http\Controllers\PenjualanController::class,'getInvoice'])->middleware(['auth']);
Route::resource('penjualan', App\Http\Controllers\PenjualanController::class)->middleware(['auth']);

// transaksi pembayaran viewkeranjang
Route::get('pembayaran/viewkeranjang', [App\Http\Controllers\PembayaranController::class,'viewkeranjang'])->middleware(['auth']);
Route::get('pembayaran/viewstatus', [App\Http\Controllers\PembayaranController::class,'viewstatus'])->middleware(['auth']); 
Route::get('pembayaran/viewapprovalstatus', [App\Http\Controllers\PembayaranController::class,'viewapprovalstatus'])->middleware(['auth']);
Route::get('pembayaran/approve/{no_transaksi}', [App\Http\Controllers\PembayaranController::class,'approve'])->middleware(['auth']);
Route::get('pembayaran/unapprove/{no_transaksi}', [App\Http\Controllers\PembayaranController::class,'unapprove'])->middleware(['auth']);
Route::get('pembayaran/viewstatusPG', [App\Http\Controllers\PembayaranController::class,'viewstatusPG'])->middleware(['auth']);
Route::resource('pembayaran', App\Http\Controllers\PembayaranController::class)->middleware(['auth']);

// laporan
Route::get('jurnal/umum', [App\Http\Controllers\JurnalController::class,'jurnalumum'])->middleware(['auth','admin']);
Route::get('jurnal/viewdatajurnalumum/{periode}', [App\Http\Controllers\JurnalController::class,'viewdatajurnalumum'])->middleware(['auth','admin']);
Route::get('jurnal/bukubesar', [App\Http\Controllers\JurnalController::class,'bukubesar'])->middleware(['auth','admin']);
Route::get('jurnal/viewdatabukubesar/{periode}/{akun}', [App\Http\Controllers\JurnalController::class,'viewdatabukubesar'])->middleware(['auth','admin']);

// grafik
Route::get('grafik/viewPenjualanBlnBerjalan', [App\Http\Controllers\GrafikController::class,'viewPenjualanBlnBerjalan'])->middleware(['auth','admin']);
Route::get('grafik/viewStatusPenjualan', [App\Http\Controllers\GrafikController::class,'viewStatusPenjualan'])->middleware(['auth','admin']);
Route::get('grafik/viewJmlBarangTerjual', [App\Http\Controllers\GrafikController::class,'viewJmlBarangTerjual'])->middleware(['auth','admin']);

// untuk midtrans
Route::get('midtrans', [App\Http\Controllers\CobaMidtransController::class,'index'])->middleware(['auth']);
Route::get('midtrans/status', [App\Http\Controllers\CobaMidtransController::class,'cekstatus2'])->middleware(['auth']);
Route::get('midtrans/status2/{id}', [App\Http\Controllers\CobaMidtransController::class,'cekstatus'])->middleware(['auth']);
Route::get('midtrans/bayar', [App\Http\Controllers\CobaMidtransController::class,'bayar'])->middleware(['auth']);
Route::post('midtrans/proses_bayar', [App\Http\Controllers\CobaMidtransController::class,'proses_bayar'])->middleware(['auth']);

require __DIR__.'/auth.php';