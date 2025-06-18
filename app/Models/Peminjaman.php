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
        'peminjam',
        'barang_id',
        'tgl_dipinjam',
        'tgl_kembali',
        'status',
        'jumlah_pinjam'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }
}
