<?php

use App\Http\Controllers\ActivityLogController;
use App\Models\Supplier;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\LaporanBarangKeluarController;
use App\Http\Controllers\LaporanBarangMasukController;
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\UbahPasswordController;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;

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


Route::middleware('auth')->group(function () {

    Route::group(['middleware' => 'checkRole:superadmin'], function () {
        Route::get('/data-pengguna/get-data', [ManajemenUserController::class, 'getDataPengguna']);
        Route::get('/api/role/', [ManajemenUserController::class, 'getRole']);
        Route::resource('/data-pengguna', ManajemenUserController::class);

        Route::get('/hak-akses/get-data', [HakAksesController::class, 'getDataRole']);
        Route::get('/hak-akses/{id}/permissions', [HakAksesController::class, 'permissions'])->name('hak-akses.permissions');
        Route::post('/hak-akses/{id}/permissions', [HakAksesController::class, 'savePermissions'])->name('hak-akses.save-permissions');
        Route::get('/hak-akses/{id}/get-permissions', [HakAksesController::class, 'getPermissions'])->name('hak-akses.get-permissions');
        Route::resource('/hak-akses', HakAksesController::class);
    });

    // Aktivitas User — akses via permission
    Route::group(['middleware' => 'checkRole:permission:aktivitas-user'], function () {
        Route::resource('/aktivitas-user', ActivityLogController::class);
    });

    // Dashboard dapat diakses oleh semua user yang sudah login
    Route::resource('/dashboard', DashboardController::class);
    Route::get('/', [DashboardController::class, 'index']);

    // Laporan — akses via permission (read-only, tidak ada write)
    Route::group(['middleware' => 'checkRole:permission:laporan-stok'], function () {
        Route::get('/laporan-stok/get-data', [LaporanStokController::class, 'getData']);
        Route::get('/laporan-stok/print-stok', [LaporanStokController::class, 'printStok']);
        Route::get('/api/satuan/', [LaporanStokController::class, 'getSatuan']);
        Route::resource('/laporan-stok', LaporanStokController::class);
    });

    Route::group(['middleware' => 'checkRole:permission:laporan-barang-masuk'], function () {
        Route::get('/laporan-barang-masuk/get-data', [LaporanBarangMasukController::class, 'getData']);
        Route::get('/laporan-barang-masuk/print-barang-masuk', [LaporanBarangMasukController::class, 'printBarangMasuk']);
        Route::get('/api/supplier/', [LaporanBarangMasukController::class, 'getSupplier']);
        Route::resource('/laporan-barang-masuk', LaporanBarangMasukController::class);
    });

    Route::group(['middleware' => 'checkRole:permission:laporan-barang-keluar'], function () {
        Route::get('/laporan-barang-keluar/get-data', [LaporanBarangKeluarController::class, 'getData']);
        Route::get('/laporan-barang-keluar/print-barang-keluar', [LaporanBarangKeluarController::class, 'printBarangKeluar']);
        Route::get('/api/customer/', [LaporanBarangKeluarController::class, 'getCustomer']);
        Route::resource('/laporan-barang-keluar', LaporanBarangKeluarController::class);
    });

    Route::get('/ubah-password', [UbahPasswordController::class, 'index']);
    Route::POST('/ubah-password', [UbahPasswordController::class, 'changePassword']);

    // Data Master — akses via permission
    Route::group(['middleware' => 'checkRole:permission:barang'], function () {
        Route::get('/barang/cetak-pdf/{id}', [BarangController::class, 'cetakPdf'])
            ->name('barang.cetak.pdf');
        Route::get('/barang/get-data', [BarangController::class, 'getDataBarang']);
        Route::resource('/barang', BarangController::class);
    });

    Route::group(['middleware' => 'checkRole:permission:jenis-barang'], function () {
        Route::get('/jenis-barang/get-data', [JenisController::class, 'getDataJenisBarang']);
        Route::resource('/jenis-barang', JenisController::class);
    });

    Route::group(['middleware' => 'checkRole:permission:supplier'], function () {
        Route::get('/supplier/get-data', [SupplierController::class, 'getDataSupplier']);
        Route::resource('/supplier', SupplierController::class);
    });

    Route::group(['middleware' => 'checkRole:permission:customer'], function () {
        Route::get('/customer/get-data', [CustomerController::class, 'getDataCustomer']);
        Route::resource('/customer', CustomerController::class);
    });

    Route::group(['middleware' => 'checkRole:permission:barang-masuk'], function () {
        Route::get('/api/barang-masuk', [BarangMasukController::class, 'getAutoCompleteData']);
        Route::get('/barang-masuk/get-data', [BarangMasukController::class, 'getDataBarangMasuk']);
        Route::get('/api/satuan-masuk', [BarangMasukController::class, 'getSatuan']);
        Route::get('/barang-masuk/get-barang-detail', [BarangMasukController::class, 'getBarangDetail']);
        Route::resource('/barang-masuk', BarangMasukController::class)
            ->where(['barang_masuk' => '[0-9]+']);
    });

    Route::group(['middleware' => 'checkRole:permission:barang-keluar'], function () {
        Route::get('/api/barang-keluar/', [BarangKeluarController::class, 'getAutoCompleteData']);
        Route::get('/barang-keluar/get-data', [BarangKeluarController::class, 'getDataBarangKeluar']);
        Route::get('/api/satuan/', [BarangKeluarController::class, 'getSatuan']);
        Route::resource('/barang-keluar', BarangKeluarController::class);
    });
});



require __DIR__ . '/auth.php';
