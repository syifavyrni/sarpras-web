@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Data Barang</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="image_url" class="form-label">Ganti Foto (Opsional)</label>
                <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*">
            </div>
            @if ($barang->image_url)
                <p>Foto Saat Ini:</p>
                <img src="{{ asset('storage/' . $barang->image_url) }}" alt="Foto Barang" width="150">
            @endif
            <div class="mb-3">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="{{ $barang->nama_barang }}" required>
            </div>
            <div class="mb-3">
                <label for="kategori_id" class="form-label">Kategori Barang</label>
                <input type="text" name="kategori_id" class="form-control" value="{{ $barang->kategori_id }}" required>
            </div>
            <div class="mb-3">
                <label for="stock_barang" class="form-label">Stok Barang</label>
                <input type="number" name="stock_barang" class="form-control" value="{{ $barang->stock_barang }}" required>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Ganti Foto (Opsional)</label>
                <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*">
            </div>
            @if ($barang->image_url)
                <p>Foto Saat Ini:</p>
                <img src="{{ asset('storage/' . $barang->image_url) }}" alt="Foto Barang" width="150">
            @endif

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
