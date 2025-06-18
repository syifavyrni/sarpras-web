<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Pengembalian::with('peminjaman.barang')->get();
        return view('pengembalian.index', compact('pengembalians'));
    }

    public function create()
    {
        // Ambil peminjaman yang sudah disetujui dan belum dikembalikan (tidak termasuk yang ditolak)
        $peminjamans = Peminjaman::with('barang')
            ->where('status', 'Disetujui')
            ->whereNotIn('status', ['Ditolak', 'Pending']) // Eksplisit exclude yang ditolak dan pending
            ->whereDoesntHave('pengembalian')
            ->get();

        return view('pengembalian.create', compact('peminjamans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'tgl_dikembalikan' => 'required|date',
            'kondisi' => 'required|in:baik,rusak',
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

            // Cek status peminjaman
            if ($peminjaman->status !== 'Disetujui') {
                return redirect()->back()->with('error', 'Hanya peminjaman yang sudah disetujui yang bisa dikembalikan.');
            }

            // Cek apakah peminjaman sudah dikembalikan
            if ($peminjaman->pengembalian) {
                return redirect()->back()->with('error', 'Peminjaman ini sudah dikembalikan.');
            }

            // User hanya mengajukan pengembalian, denda akan ditentukan admin nanti
            Pengembalian::create([
                'peminjaman_id' => $request->peminjaman_id,
                'tgl_dikembalikan' => $request->tgl_dikembalikan,
                'kondisi' => $request->kondisi,
                'catatan' => $request->catatan,
                'denda' => 0, // Default 0, admin yang akan set
                'status' => 'Pending',
            ]);

            DB::commit();
            return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pengembalian = Pengembalian::with('peminjaman.barang')->findOrFail($id);
        return view('pengembalian.show', compact('pengembalian'));
    }

    public function terima(Request $request, $id)
    {
        // Denda default 0 untuk pengembalian yang diterima
        $denda = $request->input('denda', 0);

        DB::beginTransaction();
        try {
            $pengembalian = Pengembalian::with('peminjaman.barang')->findOrFail($id);

            if ($pengembalian->status !== 'Pending') {
                return redirect()->route('pengembalian.index')->with('error', 'Pengembalian sudah diproses.');
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

            return redirect()->route('pengembalian.index')->with('success', 'Pengembalian diterima dan stok barang telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'denda' => 'required|integer|min:0',
            'alasan_denda' => 'required|string|max:500',
        ]);

        $pengembalian = Pengembalian::findOrFail($id);
        if ($pengembalian->status !== 'Pending') {
            return redirect()->route('pengembalian.index')->with('error', 'Pengembalian sudah diproses.');
        }

        // Update pengembalian dengan denda dan alasan penolakan
        $pengembalian->status = 'Ditolak';
        $pengembalian->denda = $request->denda;
        $pengembalian->alasan_denda = $request->alasan_denda;
        $pengembalian->save();

        $message = 'Pengembalian ditolak.';
        if ($request->denda > 0) {
            $message .= ' Denda: Rp ' . number_format($request->denda, 0, ',', '.');
        }

        return redirect()->route('pengembalian.index')->with('success', $message);
    }
}
