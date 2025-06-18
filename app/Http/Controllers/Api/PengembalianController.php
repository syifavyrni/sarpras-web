<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    // GET /api/pengembalian
    public function index()
    {
        try {
            $pengembalians = Pengembalian::with('peminjaman.barang')->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar pengembalian',
                'data' => $pengembalians,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // GET /api/peminjaman-siap-kembali
    public function peminjamanSiapKembali()
    {
        try {
            // Hanya peminjaman yang disetujui dan belum dikembalikan (tidak termasuk yang ditolak)
            $peminjamans = Peminjaman::with('barang')
                ->where('status', 'Disetujui')
                ->whereNotIn('status', ['Ditolak', 'Pending']) // Eksplisit exclude yang ditolak dan pending
                ->whereDoesntHave('pengembalian')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar peminjaman siap dikembalikan',
                'data' => $peminjamans,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // POST /api/pengembalian
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'peminjaman_id' => 'required|exists:peminjamans,id',
                'tgl_dikembalikan' => 'required|date',
                'kondisi' => 'required|in:baik,rusak',
                'catatan' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            $peminjaman = Peminjaman::findOrFail($validated['peminjaman_id']);

            // Cek status peminjaman
            if ($peminjaman->status !== 'Disetujui') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya peminjaman yang sudah disetujui yang bisa dikembalikan.'
                ], 400);
            }

            // Cek apakah peminjaman sudah dikembalikan
            if ($peminjaman->pengembalian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman ini sudah dikembalikan.'
                ], 400);
            }

            // User hanya mengajukan pengembalian, denda akan ditentukan admin nanti
            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $validated['peminjaman_id'],
                'tgl_dikembalikan' => $validated['tgl_dikembalikan'],
                'kondisi' => $validated['kondisi'],
                'catatan' => $validated['catatan'] ?? null,
                'denda' => 0, // Default 0, admin yang akan set
                'status' => 'Pending',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengembalian berhasil diajukan',
                'data' => $pengembalian->load('peminjaman.barang')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // POST /api/pengembalian/{id}/terima
    public function terima(Request $request, $id)
    {
        try {
            // Denda default 0 untuk pengembalian yang diterima
            $denda = $request->input('denda', 0);

            DB::beginTransaction();

            $pengembalian = Pengembalian::with('peminjaman.barang')->findOrFail($id);

            if ($pengembalian->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengembalian sudah diproses.'
                ], 400);
            }

            // Update pengembalian - diterima tanpa denda
            $pengembalian->status = 'Diterima';
            $pengembalian->denda = $denda;
            $pengembalian->alasan_denda = $denda > 0 ? $request->alasan_denda : null;
            $pengembalian->save();

            // Update status peminjaman menjadi Selesai
            $peminjaman = $pengembalian->peminjaman;
            $peminjaman->status = 'Selesai';
            $peminjaman->save();

            // Kembalikan stok barang
            $barang = $peminjaman->barang;
            $barang->stock_barang += $peminjaman->jumlah_pinjam;
            $barang->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengembalian diterima dan stok barang telah dikembalikan',
                'data' => $pengembalian->fresh('peminjaman.barang')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // POST /api/pengembalian/{id}/tolak
    public function tolak(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'denda' => 'required|integer|min:0',
                'alasan_denda' => 'required|string|max:500',
            ]);

            $pengembalian = Pengembalian::findOrFail($id);

            if ($pengembalian->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengembalian sudah diproses.'
                ], 400);
            }

            // Update pengembalian dengan denda dan alasan penolakan
            $pengembalian->status = 'Ditolak';
            $pengembalian->denda = $validated['denda'];
            $pengembalian->alasan_denda = $validated['alasan_denda'];
            $pengembalian->save();

            $message = 'Pengembalian ditolak';
            if ($validated['denda'] > 0) {
                $message .= '. Denda: Rp ' . number_format($validated['denda'], 0, ',', '.');
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $pengembalian->fresh('peminjaman.barang')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
