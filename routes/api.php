<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarangApiController;
use App\Http\Controllers\Api\PeminjamanApiController;
use App\Http\Controllers\Api\PengembalianController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/barangs', [BarangApiController::class, 'index']);
Route::get('/barangs/{id}', [BarangApiController::class, 'show']);

Route::post('/barangs', [BarangApiController::class, 'store']);
Route::post('/barangs/{id}', [BarangApiController::class, 'update']);
Route::delete('/barangs/{id}', [BarangApiController::class, 'destroy']);


// Peminjaman API Routes
Route::get('/peminjaman', [PeminjamanApiController::class, 'index']);
Route::post('/peminjaman', [PeminjamanApiController::class, 'store']);
Route::get('/peminjaman/siap-kembali', [PeminjamanApiController::class, 'siapKembali']);
Route::get('/peminjaman/belum-kembali', [PeminjamanApiController::class, 'belumKembali']);

// Pengembalian API Routes
Route::get('/pengembalian', [PengembalianController::class, 'index']);
Route::post('/pengembalian', [PengembalianController::class, 'store']);

// Pengembalian API Routes
Route::get('/pengembalian', [PengembalianController::class, 'index']);
Route::post('/pengembalian', [PengembalianController::class, 'store']);
