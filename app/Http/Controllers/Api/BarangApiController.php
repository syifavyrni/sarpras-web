<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Barang;
use App\Models\Kategori;

class BarangApiController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategori')->get();
        return response()->json($barangs);
    }

    public function show($id)
    {
        $barang = Barang::with('kategori')->find($id);
        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }
        return response()->json($barang);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'kategori_id' => 'required|exists:kategoris,id',
                'stock_barang' => 'required|integer|min:0',
                'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            $imageUrl = null;

            if ($request->hasFile('image_url')) {
                $path = $request->file('image_url')->store('public/image_url');
                $imageUrl = Storage::url($path);
            }

            $barang = Barang::create([
                'nama_barang' => $request->nama_barang,
                'kategori_id' => $request->kategori_id,
                'stock_barang' => $request->stock_barang,
                'image_url' => $imageUrl,
            ]);

            return response()->json($barang, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('API Store Barang failed: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menyimpan data barang'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
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
                    $oldImagePath = str_replace('/storage/', 'public/', $barang->image_url);
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }
                $path = $request->file('image_url')->store('public/image_url');
                $imageUrl = Storage::url($path);
            }

            $barang->update([
                'nama_barang' => $request->nama_barang,
                'kategori_id' => $request->kategori_id,
                'stock_barang' => $request->stock_barang,
                'image_url' => $imageUrl,
            ]);

            return response()->json($barang);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('API Update Barang failed: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengupdate data barang'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $barang = Barang::findOrFail($id);

            if ($barang->image_url) {
                $imagePathToDelete = str_replace('/storage/', 'public/', $barang->image_url);
                if (Storage::exists($imagePathToDelete)) {
                    Storage::delete($imagePathToDelete);
                }
            }

            $barang->delete();

            return response()->json(['message' => 'Data barang berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('API Delete Barang failed: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menghapus data barang'], 500);
        }
    }
}
