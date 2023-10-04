

<?php

use App\Http\Controllers\CRUD\DataPasienController;
use App\Http\Controllers\CRUD\DataDokterController;
use App\Http\Controllers\CRUD\DataPoliController;
use App\Http\Controllers\CRUD\DataJadwalController;
use App\Http\Controllers\CRUD\DataSusterController;
use App\Http\Controllers\CRUD\DataStaffPendaftaranController;

use App\Http\Controllers\Transaksi\PendaftaranController;
use App\Http\Controllers\Transaksi\VitalSignController;
use App\Http\Controllers\Transaksi\SpesialistikController;
use App\Http\Controllers\Transaksi\JadwalDokterController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;


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
    return view('login');
})->name("login");

Route::post('/', [LoginController::class, 'loginAuth']);
Route::get('/logout', [LoginController::class, 'logout']);



    
Route::middleware('auth')->group(function () {
    //access only role admin

    Route::resource('datapasien', DataPasienController::class)->names("datapasien");
    Route::resource('datadokter', DataDokterController::class)->names("datadokter");
    Route::resource('datapoli', DataPoliController::class)->names("datapoli");
    Route::resource('datajadwal', DataJadwalController::class)->names("datajadwal");
    Route::resource('datasuster', DataSusterController::class)->names("datasuster");
    Route::resource('datastaffpendaftaran', DataStaffPendaftaranController::class)->names("datastaffpendaftaran");


    Route::resource('vitalsign', VitalSignController::class)->names("vitalsign");
    Route::resource('pendaftaran', PendaftaranController::class)->names("pendaftaran");

    Route::get('spesialistik', [SpesialistikController::class, 'index'])->name("home-spesialistik")->middleware('can:access-dokter');
    Route::get('spesialistik/antrianpasien', [SpesialistikController::class, 'antrianPasien'])->name("antrianpasien-spesialistik")->middleware('can:access-dokter');
    Route::get('spesialistik/kunjunganhariini', [SpesialistikController::class, 'kunjunganHarini'])->name("kunjunganhariini-spesialistik")->middleware('can:access-dokter');
    Route::get('spesialistik/cetakpdf/{idsps?}', [SpesialistikController::class, 'cetak_pdf'])->name("cetakpdf")->middleware('can:access-dokter');
    Route::post('spesialistik/rekammedis/{id?}', [SpesialistikController::class, 'storePemeriksaan'])->name("storepemeriksaan")->middleware('can:access-dokter');
    Route::get('spesialistik/rekammedis/{id?}', [SpesialistikController::class, 'rekammedis'])->name("rekammedis-spesialistik")->middleware('can:access-dokter');
    Route::get('spesialistik/rekammedis/{id?}/detail', [SpesialistikController::class, 'detailRekammedis'])->name("detailrekammedis-spesialistik")->middleware('can:access-dokter');

    Route::get('spesialistik/addresep', [SpesialistikController::class, 'addResep'])->name("addresep")->middleware('can:access-dokter');

});



