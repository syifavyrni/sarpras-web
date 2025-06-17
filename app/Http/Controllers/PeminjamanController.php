<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Tampilkan semua data peminjaman dan barang
    public function index()
    {
        $barangs = Barang::all();
        $peminjamans = Peminjaman::with('barang')->get();
        return view('peminjaman.index', compact('peminjamans', 'barangs'));
    }   

    // Halaman tambah peminjaman
    public function create()
    {
        $barangs = Barang::all();
        return view('peminjaman.create', compact('barangs'));
    }

    // Simpan data peminjaman
    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjam' => 'required|string|max:255',
            'barang_id' => 'required|exists:barangs,id',
            'tgl_dipinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_dipinjam',
        ]);

        $validated['status'] = 'Pending'; // default status saat simpan
        Peminjaman::create($validated);

        return redirect()->back()->with('success', 'Peminjaman berhasil ditambahkan!');
    }

    // Hapus peminjaman
    public function destroy($id)
    {
        Peminjaman::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    // Update status peminjaman
    public function updateStatus(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = $request->input('status');
        $peminjaman->tgl_kembali = $request->input('tgl_kembali');
        $peminjaman->save();

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    // ✅ Menampilkan daftar pengembalian
    public function pengembalian()
    {
        $peminjamans = Peminjaman::with('barang')
                            ->where('status', 'Pending') // hanya yang belum selesai
                            ->get();
        return view('peminjaman.pengembalian', compact('peminjamans'));
    }

    // ✅ Proses pengembalian
    public function prosesKembali($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status !== 'Pending') {
                return redirect()->back()->with('error', 'Hanya peminjaman dengan status Pending yang dapat dikembalikan.');
            }

            // Kembalikan stok
            $barang = Barang::findOrFail($peminjaman->barang_id);
            $barang->stok += 1; // diasumsikan jumlah = 1
            $barang->save();

            $peminjaman->status = 'Selesai';
            $peminjaman->save();

            DB::commit();
            return redirect()->back()->with('success', 'Pengembalian berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ✅ Menerima/menyetujui peminjaman
    public function acc($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $barang = Barang::findOrFail($peminjaman->barang_id);

        if ($barang->stok < 1) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi.');
        }

        $barang->stok -= 1;
        $barang->save();

        $peminjaman->status = 'Pending'; // atau langsung 'Selesai' jika ingin dianggap langsung digunakan
        $peminjaman->save();

        return redirect()->back()->with('success', 'Peminjaman disetujui.');
    }

    // ✅ Menolak peminjaman
    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'Ditolak';
        $peminjaman->save();

        return redirect()->back()->with('success', 'Peminjaman berhasil ditolak.');
    }
}
