<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class PeminjamanApiController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with('barang')->get();
        return response()->json($peminjamans);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjam' => 'required|string|max:255',
            'barang_id' => 'required|exists:barangs,id',
            'tgl_dipinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_dipinjam',
        ]);

        $validated['status'] = 'Pending';
        
        $peminjaman = Peminjaman::create($validated);

        return response()->json([
            'message' => 'Peminjaman berhasil ditambahkan',
            'data' => $peminjaman
        ], 201);
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();

        return response()->json(['message' => 'Peminjaman berhasil dihapus']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'tgl_kembali' => 'nullable|date'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => $request->status,
            'tgl_kembali' => $request->tgl_kembali,
        ]);

        return response()->json(['message' => 'Status berhasil diperbarui', 'data' => $peminjaman]);
    }

    public function pengembalian()
    {
        $peminjamans = Peminjaman::with('barang')->where('status', 'Pending')->get();
        return response()->json($peminjamans);
    }

    public function prosesKembali($id)
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status !== 'Pending') {
                return response()->json(['error' => 'Hanya peminjaman dengan status Pending yang dapat dikembalikan.'], 400);
            }

            $barang = Barang::findOrFail($peminjaman->barang_id);
            $barang->stok += 1;
            $barang->save();

            $peminjaman->status = 'Selesai';
            $peminjaman->save();

            DB::commit();

            return response()->json(['message' => 'Pengembalian berhasil diproses.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function acc($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $barang = Barang::findOrFail($peminjaman->barang_id);

        if ($barang->stok < 1) {
            return response()->json(['error' => 'Stok barang tidak mencukupi.'], 400);
        }

        $barang->stok -= 1;
        $barang->save();

        $peminjaman->status = 'Pending';
        $peminjaman->save();

        return response()->json(['message' => 'Peminjaman disetujui.']);
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'Ditolak';
        $peminjaman->save();

        return response()->json(['message' => 'Peminjaman berhasil ditolak.']);
    }
}
