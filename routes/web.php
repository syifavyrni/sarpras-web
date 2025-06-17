<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('login');
});
    
// Login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

//Dashboard
Route::get('/dashboard', function () {
    $total = 50;
    $available = 40;
    $borrowed = 10;

    return view('dashboard', compact('total', 'available', 'borrowed'));
})->middleware('auth')->name('dashboard');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

//Kategori Barang
Route::post('/kategori-create', [KategoriController::class, 'store'])->name('kategori.store');
Route::resource('kategori', KategoriController::class);

//Data Barang
Route::resource('barang', BarangController::class);
Route::put('barang/update/{id}', [BarangController::class, 'update'])->name('barang.update');

//Peminjaman
Route::resource('peminjaman', PeminjamanController::class);
Route::post('peminjaman-create', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::post('peminjaman/updateStatus/{id}', [PeminjamanController::class, 'updateStatus'])->name('peminjaman.updateStatus');

//Pengembalian
Route::resource('pengembalian', PengembalianController::class);
Route::post('/peminjaman/store', [PengembalianController::class, 'store'])->name('pengembalian.store');
    
// Laporan Peminjaman
Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjamanIndex'])->name('laporan.peminjaman');
Route::get('/laporan/peminjaman/print', [LaporanController::class, 'peminjamanPrint'])->name('laporan.peminjaman.print');
Route::get('/laporan/peminjaman/pdf', [LaporanController::class, 'peminjamanPdf'])->name('laporan.peminjaman.pdf');


Route::get('/laporan/pengembalian', [LaporanController::class, 'pengembalianIndex'])->name('laporan.pengembalian');
Route::get('/laporan/pengembalian/print', [LaporanController::class, 'pengembalianPrintView'])->name('laporan.pengembalian.print');
Route::get('/laporan/pengembalian/pdf', [LaporanController::class, 'pengembalianExportPdf'])->name('laporan.pengembalian.pdf');
Route::resource('laporan', LaporanController::class);
Route::get('/laporan/pengembalian', [LaporanController::class, 'pengembalianIndex'])->name('laporan.pengembalian');

//User
Route::resource('users', UserController::class);






