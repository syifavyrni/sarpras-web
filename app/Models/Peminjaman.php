<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // 👇 Tambahkan ini untuk menetapkan nama tabel yang benar
    protected $table = 'peminjamans';

    protected $fillable = [
        'peminjam', 'barang_id', 'tgl_dipinjam', 'tgl_kembali', 'status'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}


