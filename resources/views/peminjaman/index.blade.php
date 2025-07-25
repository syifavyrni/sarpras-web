@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/peminjaman.css') }}">

    {{-- Menampilkan pesan error validasi --}}
    @error('peminjam')
        <h1>{{ $message }}</h1>
    @enderror
    @error('barang_id')
        <h1>{{ $message }}</h1>
    @enderror
    @error('tgl_dipinjam')
        <h1>{{ $message }}</h1>
    @enderror
    @error('tgl_kembali')
        <h1>{{ $message }}</h1>
    @enderror

    <div class="main-content">
        <h2>DAFTAR PEMINJAMAN</h2>

        <table class="peminjaman-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama Peminjam</th>
                    <th>Barang</th>
                    <th>Jumlah</th> <!-- Tambah kolom ini -->
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($peminjamans as $index => $peminjaman)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $peminjaman->peminjam }}</td>
                        <td>{{ $peminjaman->barang->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                        <td>{{ $peminjaman->jumlah_pinjam }}</td>
                        <td>{{ $peminjaman->tgl_dipinjam }}</td>
                        <td>{{ $peminjaman->tgl_kembali }}</td>
                        <td>
                            @if ($peminjaman->status === 'Pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif ($peminjaman->status === 'Selesai')
                                <span class="badge badge-success">Selesai</span>
                            @elseif ($peminjaman->status === 'Ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-secondary">{{ $peminjaman->status }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($peminjaman->status === 'Pending')
                                <form action="{{ route('peminjaman.acc', $peminjaman->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-approve">Setujui</button>
                                </form>
                                <form action="{{ route('peminjaman.reject', $peminjaman->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-reject">Tolak</button>
                                </form>
                            @elseif ($peminjaman->status === 'Selesai')
                                <span class="text-success">Terkembalikan</span>
                            @elseif ($peminjaman->status === 'Ditolak')
                                <span class="text-danger">Ditolak</span>
                            @elseif ($peminjaman->status === 'Disetujui')
                                <span class="text-primary">Disetujui</span>
                            @else
                                <span>-</span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
