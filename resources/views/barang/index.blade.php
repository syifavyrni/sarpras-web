@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/barang.css') }}">

<div class="main-content">
    <button class="btn-add" onclick="openAddModal()">+ Tambah Data Barang</button>
</div>

<h2>DATA BARANG</h2>

<table class="barang-table">
    <thead>
        <tr>
            <th>NO</th>
            <th>Foto</th>
            <th>Nama Barang</th>
            <th>Kategori Barang</th>
            <th>Stok Barang</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($barangs as $index => $barang)
        <tr>
            <td>{{ $index + 1 }}.</td>
            <td style="text-align: center;">
                @if ($barang->image_url)
                    <img src="{{ $barang->image_url }}" alt="Gambar Barang" class="barang-image" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <img src="{{ asset('images/no_images.png') }}" alt="No Image" class="barang-image" style="width: 100px; height: 100px; object-fit: cover;">
                @endif
            </td>
            <td>{{ $barang->nama_barang }}</td>
            <td>{{ $barang->kategori->nama ?? '-' }}</td>
            <td>{{ $barang->stock_barang }}</td>
            <td>
                <button class="btn-edit" onclick="openEditModal({{ $barang->id }}, '{{ $barang->nama_barang }}')">Edit</button>
                <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn-delete">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Add Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h3>Tambah Data Barang</h3>
        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="nama_barang" placeholder="Nama Barang" required><br><br>
            <select name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                @endforeach
            </select><br><br>
            <input type="number" name="stock_barang" placeholder="Stok Barang" required><br><br>
            <label for="image_url">Tambahkan Gambar</label>
            <input type="file" name="image_url"><br><br>
            <button type="submit" class="btn-add">Simpan</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Edit Data Barang</h3>
        <form id="editForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <label for="editNama">Nama Barang</label><br>
            <input type="text" name="nama_barang" id="editNama" required><br>
            <label for="editKategori">Kategori Barang</label><br>
            <select name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                @endforeach
            </select><br>
            <label for="editStok">Stock Barang</label><br>
            <input type="number" name="stock_barang" id="editStok" min="0" required><br>
            <label for="editImage">Ganti Gambar (opsional)</label><br>
            <input type="file" name="image_url" id="editImage"><br>
            <button type="submit">Update</button>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'block';
    }

    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }

    function openEditModal(id, nama) {
        const modal = document.getElementById('editModal');
        document.getElementById('editNama').value = nama;
        document.getElementById('editForm').action = `/barang/update/${id}`;
        modal.style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeAddModal();
            closeEditModal();
        }
    }
</script>
@endsection
