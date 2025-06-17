<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Pengembalian::all();
        return view('pengembalian.index', compact('pengembalians'));
    }

    public function create()
    {
        return view('pengembalian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjam' => 'required|string|max:255',
            'barang' => 'required|string|max:255',
            'tgl_kembali' => 'required|date',
            'kondisi' => 'required|in:baik,rusak',
        ]);

        $tglKembali = Carbon::parse($request->tgl_kembali);
        $now = now();

        $denda = 0;
        if ($tglKembali->isPast()) {
            $selisihHari = $tglKembali->diffInDays($now);
            $denda += $selisihHari * 10000; // Denda keterlambatan
        }

        if ($request->kondisi === 'rusak') {
            $denda += 100000; // Denda kerusakan
        }

        Pengembalian::create([
            'peminjam' => $request->peminjam,
            'barang' => $request->barang,
            'tgl_kembali' => $request->tgl_kembali,
            'status' => 'Pending',
            'kondisi' => $request->kondisi,
            'denda' => $denda,
        ]);

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil diajukan.');
    }

    public function show($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        return view('pengembalian.show', compact('pengembalian'));
    }

    public function terima($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        if ($pengembalian->status !== 'Pending') {
            return redirect()->route('pengembalian.index')->with('error', 'Pengembalian sudah diproses.');
        }

        $pengembalian->status = 'Dikembalikan';
        $pengembalian->save();

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian diterima.');
    }

    public function tolak(Request $request, $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        if ($pengembalian->status !== 'Pending') {
            return redirect()->route('pengembalian.index')->with('error', 'Pengembalian sudah diproses.');
        }

        $pengembalian->status = 'Dipinjam'; 
        $pengembalian->save();

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian ditolak.');
    }

    public function edit($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        return view('pengembalian.edit', compact('pengembalian'));
    }

    public function destroy($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->delete();
        return redirect()->route('pengembalian.index')->with('success', 'Data berhasil dihapus.');
    }
}
