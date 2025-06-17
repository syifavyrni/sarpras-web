@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/kategori.css') }}">

<div class="main-content">
        <button class="btn-add" onclick="openAddModal()">+ Tambah Kategori Barang</button>
    </div>

    <h2>KATEGORI BARANG</h2>

    <table class="kategori-table">
        <thead>
            <tr>
                <th>NO</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategoris as $index => $kategori)
                <tr>
                    <td>{{ $index + 1 }}.</td>
                    <td>{{ $kategori->nama }}</td>
                    <td>
                        <button class="btn-edit" onclick="openEditModal({{ $kategori->id }}, '{{ $kategori->nama }}')">Edit</button>
                        <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h3>Tambah Kategori</h3>
        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <input type="text" name="nama" placeholder="Nama kategori" required>
            <button type="submit">Simpan</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Edit Kategori</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="nama" id="editNama" required>
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

        const form = document.getElementById('editForm');
        form.action = '/kategori/' + id;

        modal.style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Close modal when clicking outside content
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeAddModal();
            closeEditModal();
        }
    }
</script>
@endsection