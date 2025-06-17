<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;


class DashboardController extends Controller
{
    public function index()
    {
        $total = Barang::count('jumlah');
        $dipinjam = Barang::count('dipinjam');
        $tersedia = $total - $dipinjam;

        return view('dashboard', compact('total', 'tersedia', 'dipinjam'));
    }
}
