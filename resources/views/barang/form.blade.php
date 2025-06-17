@csrf

<div class="mb-3">
    <label for="image_url" class="form-label">Upload Foto</label>
    <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*">
    @if (!empty($barang->image_url))
        <small class="form-text text-muted mt-2">Foto saat ini:</small><br>
        <img src="{{ asset($barang->image_url) }}" alt="Foto Barang" style="max-width: 150px; height: auto; margin-top: 5px; border-radius: 4px;">
    @elseif (isset($barang) && empty($barang->image_url))
        <small class="form-text text-muted mt-2">Belum ada foto.</small>
    @endif
</div>

<div class="mb-3">
    <label for="nama_barang" class="form-label">Nama Barang</label>
    <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label for="kategori_id" class="form-label">Kategori Barang</label>

    <select name="kategori_id" id="kategori_id" class="form-control" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach ($kategoris as $kategori)
            <option value="{{ $kategori->id }}" {{ old('kategori_id', $barang->kategori_id ?? '') == $kategori->id ? 'selected' : '' }}>
                {{ $kategori->nama_kategori }} 
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="stock_barang" class="form-label">Stok Barang</label> 
    <input type="number" name="stock_barang" value="{{ old('stock_barang', $barang->stock_barang ?? '') }}" class="form-control" required min="0">
</div>

<button type="submit" class="btn btn-success">{{ $submit ?? 'Simpan' }}</button>
---

