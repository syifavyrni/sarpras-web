<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class Kategori extends Model
{
    use HasFactory;

    
    protected $fillable = ['nama'];
    protected $table = 'kategoris';

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
