@csrf

<div class="mb-3">
    <label>Nama Peminjam</label>
    <input type="text" name="peminjam" value="{{ old('peminjam', $peminjaman->peminjam ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Nama Barang</label>
    <input type="text" name="barang_id" value="{{ old('barang_id', $peminjaman->barang_id ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Tanggal Dipinjam</label>
    <input type="date" name="tgl_dipinjam" value="{{ old('tgl_dipinjam', $peminjaman->tgl_dipinjam ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Tanggal Kembali</label>
    <input type="date" name="tgl_kembali" value="{{ old('tgl_kembali', $peminjaman->tgl_kembali ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select name="status" id="status" class="form-control" required>
        <option value="Pending" {{ old('status', $peminjaman->status ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="Selesai" {{ old('status', $peminjaman->status ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        <option value="Ditolak" {{ old('status', $peminjaman->status ?? '') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
    </select>
</div>


<button type="submit" class="btn btn-success">{{ $submit }}</button>
