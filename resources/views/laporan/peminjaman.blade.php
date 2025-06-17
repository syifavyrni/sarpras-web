@extends('layouts.app')

@section('content')
<style>
    .laporan-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .laporan-container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #1e3a8a;
        font-size: 28px;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
        align-items: flex-end;
    }

    .filter-form label {
        display: flex;
        flex-direction: column;
        font-weight: 600;
        font-size: 14px;
        color: #374151;
    }

    .filter-form input[type="date"],
    .filter-form select {
        padding: 6px 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .filter-form button,
    .filter-form a {
        background-color: #1e3a8a;
        color: white;
        padding: 8px 14px;
        font-size: 14px;
        text-decoration: none;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .filter-form button:hover,
    .filter-form a:hover {
        background-color: #2745b3;
    }

    .laporan-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
        margin-top: 15px;
    }

    .laporan-table th,
    .laporan-table td {
        border: 1px solid #d1d5db;
        padding: 10px;
        text-align: center;
    }

    .laporan-table th {
        background-color: #1e3a8a;
        color: white;
    }

    .laporan-table td {
        background-color: #f3f4f6;
    }
</style>

<div class="laporan-container">
    <h2>Laporan Peminjaman</h2>

    <form method="GET" action="{{ route('laporan.peminjaman') }}" class="filter-form">
        <label>
            Tanggal Mulai:
            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
        </label>
        <label>
            Tanggal Akhir:
            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
        </label>
        <label>
            Status:
            <select name="status">
                <option value="">Semua</option>
                <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </label>
        <label>
            Barang:
            <select name="barang_id">
                <option value="">Semua</option>
                @foreach ($barangs as $barang)
                    <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                        {{ $barang->nama_barang }}
                    </option>
                @endforeach
            </select>
        </label>
        <button type="submit">Filter</button>
        <a href="{{ route('laporan.peminjaman.pdf') }}">Export PDF</a>
        <a href="{{ route('laporan.peminjaman.print', request()->query()) }}" target="_blank">Print</a>
    </form>

    <table class="laporan-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tanggal Pinjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peminjamans as $index => $pinjam)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pinjam->peminjam }}</td>
                <td>{{ $pinjam->barang->nama_barang ?? '-' }}</td>
                <td>{{ $pinjam->tanggal_pinjam }}</td>
                <td>{{ $pinjam->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
