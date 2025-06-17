<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjam',
        'barang_id',
        'tgl_kembali',
        'status',
        'kondisi',
        'denda'
    ];
    

    // Relasi ke tabel barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
