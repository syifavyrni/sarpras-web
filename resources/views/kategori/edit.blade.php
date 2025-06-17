@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    
    <h2 class="text-2xl font-semibold mb-6">Edit Kategori Barang</h2>

    <div class="bg-[#2D2E83] p-8 rounded-lg w-full max-w-md mx-auto shadow-lg">
        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nama" class="block text-white text-sm mb-2">Edit kategori barang</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $kategori->nama) }}"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between mt-6">
                <button type="submit"
                    class="bg-white text-[#2D2E83] font-semibold px-6 py-2 rounded-full hover:bg-gray-100 transition">
                    Simpan
                </button>

                <a href="{{ route('kategori.index') }}"
                    class="bg-white text-[#2D2E83] font-semibold px-6 py-2 rounded-full hover:bg-gray-100 transition">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
