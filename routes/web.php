<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WargaAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Homepage
Route::get('/', function () {
    return view('index');
});

// Warga Routes
Route::prefix('warga')->group(function () {
    // Authentication Routes
    Route::post('/', [WargaController::class, 'simpanWarga']);
    Route::get('/register', [WargaAuthController::class, 'showRegisterForm'])->name('warga.register');
    Route::post('/register', [WargaAuthController::class, 'register']);
    Route::get('/login', [WargaAuthController::class, 'showLoginForm'])->name('warga.login');
    Route::post('/login', [WargaAuthController::class, 'login']);
    Route::post('/logout', [WargaAuthController::class, 'logout'])->name('warga.logout');
    
    // Authenticated Warga Routes
    Route::middleware('auth:warga')->group(function () {
        Route::get('/dashboard', [WargaController::class, 'dashboard'])->name('warga.dashboard');
        
        // Iuran Routes
        Route::prefix('iuran')->group(function () {
            Route::get('/cash', [WargaController::class, 'iuranCash'])->name('warga.iuran.cash');
            Route::get('/transfer', [WargaController::class, 'iuranTransfer'])->name('warga.iuran.transfer');
            Route::get('/transfer/create', [WargaController::class, 'createTransfer'])->name('warga.transfer.create');
            Route::post('/transfer/store', [WargaController::class, 'storeTransfer'])->name('warga.transfer.store');
        });
        
        // Notification and Receipt Routes
        Route::get('/notifikasi', [WargaController::class, 'notifikasi'])->name('warga.notifikasi');
        Route::get('/kwitansi/{id}/download', [WargaController::class, 'downloadKwitansi'])->name('warga.kwitansi.download');
    });
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Authentication Routes
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    
    // Authenticated Admin Routes
    Route::middleware('auth:admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        Route::get('/warga-belum-bayar', [AdminController::class, 'wargaBelumBayar'])->name('admin.warga.belum_bayar');

        // Warga Management
        Route::prefix('warga')->group(function () {
            Route::get('/{id}/show-phone', [AdminController::class, 'showDecryptedPhone'])->name('admin.warga.show-phone');
            Route::get('/{id}/riwayat-iuran', [AdminController::class, 'riwayatIuranWarga'])->name('admin.warga.riwayat');
            Route::get('/create', [AdminController::class, 'createWarga'])->name('admin.warga.create');
            Route::post('/', [AdminController::class, 'storeWarga'])->name('admin.warga.store');
            Route::get('/{id}/edit', [AdminController::class, 'editWarga'])->name('admin.warga.edit');
            Route::put('/{id}', [AdminController::class, 'updateWarga'])->name('admin.warga.update');
            Route::delete('/{id}', [AdminController::class, 'destroyWarga'])->name('admin.warga.destroy');
        });
        
        
        // Iuran Management
        Route::prefix('iuran')->group(function () {
            // Cash Iuran
            Route::prefix('cash')->group(function () {
                Route::get('/', [AdminController::class, 'indexIuranCash'])->name('admin.iuran_cash.index');
                Route::get('/create', [AdminController::class, 'createIuranCash'])->name('admin.iuran.cash.create');
                Route::post('/', [AdminController::class, 'storeIuranCash'])->name('admin.iuran.cash.store');
            });
            
            // Transfer Iuran
            Route::get('/transfer', [AdminController::class, 'indexIuranTransfer'])->name('admin.iuran.transfer.index');
        });
        
        // Pengeluaran Management
        Route::prefix('pengeluaran')->group(function () {
            Route::get('/', [AdminController::class, 'indexPengeluaran'])->name('admin.pengeluaran.index');
            Route::get('/create', [AdminController::class, 'createPengeluaran'])->name('admin.pengeluaran.create');
            Route::post('/', [AdminController::class, 'storePengeluaran'])->name('admin.pengeluaran.store');
            Route::get('/{id}/edit', [AdminController::class, 'editPengeluaran'])->name('admin.pengeluaran.edit');
            Route::put('/{id}', [AdminController::class, 'updatePengeluaran'])->name('admin.pengeluaran.update');
            Route::delete('/{id}', [AdminController::class, 'destroyPengeluaran'])->name('admin.pengeluaran.destroy');
        });

        Route::get('/admin/warga/{id}/show-phone', [AdminController::class, 'showDecryptedPhone'])
    ->middleware('auth:admin')
    ->name('admin.warga.show-phone');
        
        // Reports
        Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    });

    // web.php - pastikan route ini ada
        Route::get('/warga', [AdminController::class, 'dataWarga'])->name('admin.data_warga');
});

// PDF Routes
Route::prefix('pdf')->group(function () {
    // Warga Receipt
    Route::get('/kwitansi/warga/{id}', [PdfController::class, 'generateWargaKwitansi'])
         ->name('pdf.kwitansi.warga')
         ->middleware('auth:warga');

    // Admin Receipt
    Route::get('/kwitansi/admin/{id}', [PdfController::class, 'generateAdminKwitansi'])
         ->name('pdf.kwitansi.admin')
         ->middleware('auth:admin');

    // Financial Report
    Route::get('/laporan', [PdfController::class, 'generateLaporan'])
         ->name('pdf.laporan')
         ->middleware('auth:admin');
});