<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_id',
        'tgl_dikembalikan',
        'kondisi',
        'catatan',
        'denda',
        'status',
        'alasan_denda'
    ];

    protected $casts = [
        'tgl_dikembalikan' => 'date',
    ];

    // Relasi ke tabel peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    // Accessor untuk mendapatkan data barang melalui peminjaman
    public function getBarangAttribute()
    {
        return $this->peminjaman->barang ?? null;
    }

    // Accessor untuk mendapatkan nama peminjam melalui peminjaman
    public function getPeminjamAttribute()
    {
        return $this->peminjaman->peminjam ?? null;
    }
}
