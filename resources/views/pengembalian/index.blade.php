@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/pengembalian.css') }}">

    {{-- Menampilkan pesan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="list-style: none;">
                @foreach ($errors->all() as $error)
                    <li><strong>{{ $error }}</strong></li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="main-content">
        <h2>DAFTAR PENGEMBALIAN</h2>

        <table class="pengembalian-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama Peminjam</th>
                    <th>Barang</th>
                    <th>Jumlah Kembali</th>
                    <th>Tanggal Kembali</th>
                    <th>Kondisi</th>
                    <th>Denda</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengembalians as $index => $pengembalian)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pengembalian->peminjaman->peminjam ?? 'N/A' }}</td>
                        <td>{{ $pengembalian->peminjaman->barang->nama_barang ?? 'N/A' }}</td>
                        <td>{{ $pengembalian->peminjaman->jumlah_pinjam ?? 0 }} unit</td>
                        <td>{{ \Carbon\Carbon::parse($pengembalian->tgl_dikembalikan)->format('d-m-Y') }}</td>
                        <td>{{ ucfirst($pengembalian->kondisi) }}</td>
                        <td>Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</td>
                        <td>
                            @if ($pengembalian->status === 'Pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($pengembalian->status === 'Diterima')
                                <span class="badge badge-success">Diterima</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>

                            @if ($pengembalian->status === 'Pending')
                                <form action="{{ route('pengembalian.terima', $pengembalian->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="denda" value="0">
                                    <button class="btn-accept"
                                        onclick="return confirm('Terima pengembalian ini tanpa denda?')">Terima</button>
                                </form>
                                <button class="btn-reject"
                                    onclick="openTolakModal({{ $pengembalian->id }}, '{{ $pengembalian->peminjaman->peminjam }}', '{{ $pengembalian->peminjaman->barang->nama_barang }}')">Tolak</button>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center;">Belum ada data pengembalian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Tolak --}}
    <div id="tolakModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeTolakModal()">&times;</span>
            <h3>Tolak Pengembalian</h3>
            <form id="tolakForm" method="POST">
                @csrf
                <div class="form-group">
                    <label>Peminjam:</label>
                    <span id="modalTolakPeminjam"></span>
                </div>
                <div class="form-group">
                    <label>Barang:</label>
                    <span id="modalTolakBarang"></span>
                </div>
                <div class="form-group">
                    <label for="dendaTolak">Denda (Rp):</label>
                    <input type="number" name="denda" id="dendaTolak" min="0" value="0" required>
                </div>
                <div class="form-group">
                    <label for="alasan_denda_tolak">Alasan Penolakan & Denda:</label>
                    <textarea name="alasan_denda" id="alasan_denda_tolak" rows="3" required
                        placeholder="Contoh: Barang rusak parah, keterlambatan 7 hari, dll."></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-reject">Tolak Pengembalian</button>
                    <button type="button" class="btn-cancel" onclick="closeTolakModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Modal --}}
    <script>
        function openTolakModal(id, peminjam, barang) {
            document.getElementById('modalTolakPeminjam').textContent = peminjam;
            document.getElementById('modalTolakBarang').textContent = barang;
            document.getElementById('tolakForm').action = '/pengembalian/' + id + '/tolak';
            document.getElementById('tolakModal').style.display = 'block';
        }

        function closeTolakModal() {
            document.getElementById('tolakModal').style.display = 'none';
            document.getElementById('dendaTolak').value = '0';
            document.getElementById('alasan_denda_tolak').value = '';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('tolakModal');
            if (event.target == modal) {
                closeTolakModal();
            }
        }
    </script>
@endsection
