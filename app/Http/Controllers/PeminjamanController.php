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

    // Simpan data peminjaman dengan validasi stok
    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjam' => 'required|string|max:255',
            'barang_id' => 'required|exists:barangs,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tgl_dipinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_dipinjam',
        ]);

        // Validasi stok sebelum menyimpan
        $barang = Barang::findOrFail($validated['barang_id']);
        if ($barang->stock_barang < $validated['jumlah_pinjam']) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Stok barang tidak mencukupi. Stok tersedia: {$barang->stock_barang}, diminta: {$validated['jumlah_pinjam']}");
        }

        $validated['status'] = 'Pending';
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

    // Menampilkan daftar pengembalian
    public function pengembalian()
    {
        $peminjamans = Peminjaman::with('barang')
            ->where('status', 'Disetujui') // Ubah ke 'Disetujui' karena yang bisa dikembalikan hanya yang sudah disetujui
            ->get();
        return view('peminjaman.pengembalian', compact('peminjamans'));
    }

    // Proses pengembalian
    public function prosesKembali($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status !== 'Disetujui') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Hanya peminjaman yang sudah disetujui yang dapat dikembalikan.');
            }

            $barang = Barang::findOrFail($peminjaman->barang_id);
            $barang->stock_barang += $peminjaman->jumlah_pinjam; // Kembalikan sesuai jumlah pinjam
            $barang->save();

            $peminjaman->status = 'Selesai';
            $peminjaman->save();

            DB::commit();
            return redirect()->back()->with('success', "Pengembalian berhasil diproses. Stok {$barang->nama_barang} bertambah {$peminjaman->jumlah_pinjam} unit.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menerima/menyetujui peminjaman dengan validasi stok yang lebih detail
    public function acc($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status !== 'Pending') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
            }

            $barang = Barang::findOrFail($peminjaman->barang_id);

            if ($barang->stock_barang < $peminjaman->jumlah_pinjam) {
                DB::rollBack();
                return redirect()->back()->with('error', "Stok barang tidak mencukupi. Stok tersedia: {$barang->stock_barang}, diminta: {$peminjaman->jumlah_pinjam}");
            }

            // Kurangi stok
            $barang->stock_barang -= $peminjaman->jumlah_pinjam;
            $barang->save();

            // Update status peminjaman
            $peminjaman->status = 'Disetujui';
            $peminjaman->save();

            DB::commit();
            return redirect()->back()->with('success', "Peminjaman disetujui. Stok {$barang->nama_barang} berkurang {$peminjaman->jumlah_pinjam} unit.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menolak peminjaman
    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'Pending') {
            return redirect()->back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $peminjaman->status = 'Ditolak';
        $peminjaman->save();

        return redirect()->back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    // API endpoints untuk Flutter
    public function apiIndex()
    {
        try {
            $peminjamans = Peminjaman::with('barang')->get();
            return response()->json([
                'success' => true,
                'data' => $peminjamans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'peminjam' => 'required|string|max:255',
                'barang_id' => 'required|exists:barangs,id',
                'jumlah_pinjam' => 'required|integer|min:1',
                'tgl_dipinjam' => 'required|date',
                'tgl_kembali' => 'required|date|after_or_equal:tgl_dipinjam',
            ]);

            // Validasi stok
            $barang = Barang::findOrFail($validated['barang_id']);
            if ($barang->stock_barang < $validated['jumlah_pinjam']) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok barang tidak mencukupi. Stok tersedia: {$barang->stock_barang}, diminta: {$validated['jumlah_pinjam']}"
                ], 400);
            }

            $validated['status'] = 'Pending';
            $peminjaman = Peminjaman::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil ditambahkan!',
                'data' => $peminjaman->load('barang')
            ], 201);
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
