@extends('layouts.app')

@section('content')
<style>
    .btn-add {
        background-color: #1e3a8a;
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        margin-bottom: 10px;
        display: inline-block;
    }

    .btn-edit {
        background-color: #3b82f6;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 10px;
        margin-right: 5px;
        text-decoration: none;
    }

    .btn-delete {
        background-color: #ef4444;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
    }

    .peminjaman-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 16px;
        text-align: center;
    }

    .peminjaman-table th {
        background-color: #1e3a8a;
        color: white;
        padding: 12px;
    }

    .peminjaman-table td {
        background-color: #f3f4f6;
        padding: 12px;
        border: 1px solid #ccc;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        padding: 10px 15px;
        border-radius: 8px;
        margin: 10px 0;
    }
</style>

<h2>Daftar User</h2>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('users.create') }}" class="btn-add">+ Tambah User</a>

<table class="peminjaman-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $index => $user)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">Edit</a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
