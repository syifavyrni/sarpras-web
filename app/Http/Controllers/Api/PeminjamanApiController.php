<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Barang;

class PeminjamanApiController extends Controller
{
    // GET /api/peminjaman
    public function index()
    {
        $data = Peminjaman::with('barang')->get(); // relasi barang

        return response()->json([
            'success' => true,
            'message' => 'Daftar peminjaman',
            'data' => $data,
        ]);
    }

    // POST /api/peminjaman
    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjam' => 'required|string|max:255',
            'barang_id' => 'required|exists:barangs,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tgl_dipinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_dipinjam',
        ]);

        $barang = Barang::find($validated['barang_id']);

        if ($barang->stock_barang < $validated['jumlah_pinjam']) {
            return response()->json([
                'success' => false,
                'message' => 'Stok barang tidak mencukupi',
                'stock_barang' => $barang->stock_barang,
            ], 422);
        }

        $peminjaman = Peminjaman::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil ditambahkan',
            'data' => $peminjaman,
        ], 201);
    }

    // GET /api/peminjaman/siap-kembali - Peminjaman yang siap dikembalikan
    public function siapKembali()
    {
        try {
            // Hanya peminjaman yang sudah disetujui dan belum dikembalikan
            $data = Peminjaman::with('barang')
                ->where('status', 'Disetujui') // Hanya yang sudah di-ACC
                ->whereDoesntHave('pengembalian') // Belum dikembalikan
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar peminjaman yang siap dikembalikan',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }

    // GET /api/peminjaman/belum-kembali - Semua peminjaman yang belum dikembalikan (termasuk pending/ditolak)
    public function belumKembali()
    {
        try {
            // Ambil semua peminjaman yang belum ada di tabel pengembalian
            $data = Peminjaman::with('barang')
                ->whereDoesntHave('pengembalian')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar peminjaman yang belum dikembalikan',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }
}
