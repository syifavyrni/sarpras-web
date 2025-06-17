@csrf

<div class="mb-3">
    <label for="peminjam">Nama Peminjam</label>
    <input type="text" id="peminjam" name="peminjam" value="{{ old('peminjam', $pengembalian->peminjam ?? '') }}" class="form-control" {{ isset($pengembalian) ? 'readonly' : '' }}>
</div>

<div class="mb-3">
    <label for="barang">Nama Barang</label>
    <input type="text" id="barang" name="barang" value="{{ old('barang', $pengembalian->barang ?? '') }}" class="form-control" {{ isset($pengembalian) ? 'readonly' : '' }}>
</div>

<div class="mb-3">
    <label for="tgl_kembali">Tanggal Pengembalian</label>
    <input type="date" id="tgl_kembali" name="tgl_kembali" value="{{ old('tgl_kembali', $pengembalian->tgl_kembali ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label for="kondisi">Kondisi Barang</label>
    <select id="kondisi" name="kondisi" class="form-control" required>
        <option value="baik" {{ old('kondisi', $pengembalian->kondisi ?? '') == 'baik' ? 'selected' : '' }}>Baik</option>
        <option value="rusak" {{ old('kondisi', $pengembalian->kondisi ?? '') == 'rusak' ? 'selected' : '' }}>Rusak</option>
    </select>
</div>

<div class="mb-3">
    <label for="status">Status Pengembalian</label>
    <select id="status" name="status" class="form-control" required>
        <option value="Pending" {{ old('status', $pengembalian->status ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="Dipinjam" {{ old('status', $pengembalian->status ?? '') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
        <option value="Dikembalikan" {{ old('status', $pengembalian->status ?? '') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
    </select>
</div>

<!-- 🔴 Input Denda -->
<div class="mb-3">
    <label for="denda">Denda (Rp)</label>
    <input type="number" id="denda" name="denda" value="{{ old('denda', $pengembalian->denda ?? 0) }}" class="form-control" min="0" step="1000" placeholder="Masukkan jumlah denda">
</div>

<button type="submit" class="btn btn-success">{{ $submit ?? 'Simpan' }}</button>
