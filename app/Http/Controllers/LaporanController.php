<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // 🔹 LAPORAN PEMINJAMAN
    public function peminjamanIndex(Request $request)
    {
        $query = Peminjaman::query();

        if ($request->filled('tanggal_mulai')) {
            $query->where('tgl_dipinjam', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tgl_dipinjam', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('barang')) {
            $query->where('barang', $request->barang);
        }

        $peminjamans = $query->orderBy('tgl_dipinjam', 'desc')->get();
        $barangs = Barang::all();

        return view('laporan.peminjaman', compact('peminjamans', 'barangs'));
    }

    public function peminjamanPdf(Request $request)
    {
        $query = Peminjaman::query();

        if ($request->filled('tanggal_mulai')) {
            $query->where('tgl_dipinjam', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tgl_dipinjam', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('barang')) {
            $query->where('barang', $request->barang);
        }

        $peminjamans = $query->orderBy('tgl_dipinjam', 'desc')->get();

        $pdf = Pdf::loadView('laporan.peminjaman_pdf', [
            'peminjamans' => $peminjamans,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-peminjaman.pdf');
    }

    public function peminjamanPrint(Request $request)
    {
        $query = Peminjaman::query();

        if ($request->filled('tanggal_mulai')) {
            $query->where('tgl_dipinjam', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tgl_dipinjam', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('barang')) {
            $query->where('barang', $request->barang);
        }

        $peminjamans = $query->orderBy('tgl_dipinjam', 'desc')->get();

        return view('laporan.peminjaman_print', compact('peminjamans'));
    }

    // 🔹 LAPORAN PENGEMBALIAN
    public function pengembalianIndex(Request $request)
    {
        $query = Pengembalian::query();

        if ($request->filled('tanggal_mulai')) {
            $query->where('tgl_kembali', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tgl_kembali', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('barang')) {
            $query->where('barang', $request->barang);
        }

        $pengembalians = $query->orderBy('tgl_kembali', 'desc')->get();
        $barangs = Barang::all();

        return view('laporan.pengembalian', compact('pengembalians', 'barangs'));
    }

    public function pengembalianPdf(Request $request)
    {
        $query = Pengembalian::query();

        if ($request->filled('tanggal_mulai')) {
            $query->where('tgl_kembali', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tgl_kembali', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('barang')) {
            $query->where('barang', $request->barang);
        }

        $pengembalians = $query->orderBy('tgl_kembali', 'desc')->get();

        $pdf = Pdf::loadView('laporan.pengembalian_pdf', [
            'pengembalians' => $pengembalians,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pengembalian.pdf');
    }

    public function pengembalianPrint(Request $request)
    {
        $query = Pengembalian::query();

        if ($request->filled('tanggal_mulai')) {
            $query->where('tgl_kembali', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tgl_kembali', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('barang')) {
            $query->where('barang', $request->barang);
        }

        $pengembalians = $query->orderBy('tgl_kembali', 'desc')->get();

        return view('laporan.pengembalian_print', compact('pengembalians'));
    }
}
