@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Data Barang</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        
        <div class="mb-3">
            <label for="image_url" class="form-label">Upload Foto Barang</label>
            <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*" required>
           
            <small class="form-text text-muted">Hanya file gambar (JPG, JPEG, PNG, GIF) maksimal 2MB.</small>
        </div>

        <div class="mb-3">
            <label for="nama_barang" class="form-label">Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required>
        </div>

        <div class="mb-3">
            <label for="kategori_id" class="form-label">Kategori Barang</label>
            {{-- Menggunakan select dropdown untuk Kategori Barang --}}
            <select name="kategori_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                {{-- Variabel $kategoris harus dikirim dari controller --}}
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }} {{-- Sesuaikan 'nama_kategori' dengan nama kolom kategori Anda --}}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="stock_barang" class="form-label">Stok Barang</label> {{-- Menggunakan stock_barang --}}
            <input type="number" name="stock_barang" class="form-control" value="{{ old('stock_barang') }}" required min="0">
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection