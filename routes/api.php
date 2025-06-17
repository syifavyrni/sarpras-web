<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarangApiController;
use App\Http\Controllers\Api\PeminjamanApiController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Barang Routes
Route::get('/barangs', [BarangApiController::class, 'index']); 
Route::get('/barangs/{id}', [BarangApiController::class, 'show']); 

Route::post('/barangs', [BarangApiController::class, 'store']); 
Route::post('/barangs/{id}', [BarangApiController::class, 'update']); 
Route::delete('/barangs/{id}', [BarangApiController::class, 'destroy']); 

// Peminjaman Route


Route::get('/peminjaman', [PeminjamanApiController::class, 'index']);


