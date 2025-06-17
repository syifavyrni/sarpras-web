@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pengembalian.css') }}">

<div class="container">
    <h2>PENGEMBALIAN</h2>

    {{-- Pesan Error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="list-style: none;">
                @foreach ($errors->all() as $error)
                    <li><strong>{{ $error }}</strong></li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabel --}}
    <table class="pengembalian-table">
        <thead>
            <tr>
                <th>NO</th>
                <th>Nama Peminjam</th>
                <th>Barang</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Kondisi</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengembalians as $index => $pengembalian)
                <tr>
                    <td>{{ $index + 1 }}.</td>
                    <td>{{ $pengembalian->peminjam }}</td>
                    <td>{{ $pengembalian->barang }}</td>
                    <td>{{ \Carbon\Carbon::parse($pengembalian->tgl_kembali)->format('d-m-Y') }}</td>
                    <td>{{ $pengembalian->status }}</td>
                    <td>{{ ucfirst($pengembalian->kondisi) }}</td>
                    <td>Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('pengembalian.show', $pengembalian->id) }}" class="btn-show">Lihat</a>

                        @if($pengembalian->status === 'Pending')
                            <form action="{{ route('pengembalian.terima', $pengembalian->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-accept" onclick="return confirm('Terima pengembalian ini?')">Terima</button>
                            </form>
                            <form action="{{ route('pengembalian.tolak', $pengembalian->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-reject" onclick="return confirm('Tolak pengembalian ini?')">Tolak</button>
                            </form>
                        @endif

                        <form action="{{ route('pengembalian.destroy', $pengembalian->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn-delete" onclick="return confirm('Hapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Belum ada data pengembalian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
