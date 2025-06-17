<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Kategori; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategori')->get();
        $kategoris = Kategori::all(); 
        return view('barang.index', compact('barangs', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'stock_barang' => 'required|integer|min:0',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('public/barangs');
            $imageUrl = Storage::url($path); // /storage/barangs/nama.jpg
        }

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'stock_barang' => $request->stock_barang,
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'stock_barang' => 'required|integer|min:0',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imageUrl = $barang->image_url;

        if ($request->hasFile('image_url')) {
            if ($barang->image_url) {
                $oldPath = str_replace('/storage/', 'public/', $barang->image_url);
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }

            $path = $request->file('image_url')->store('public/barangs');
            $imageUrl = Storage::url($path);
        }

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'stock_barang' => $request->stock_barang,
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diupdate!');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->image_url) {
            $path = str_replace('/storage/', 'public/', $barang->image_url);
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus!');
    }
}
