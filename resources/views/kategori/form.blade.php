@csrf
<div class="mb-3">
    <label>Nama Kategori</label>
    <input type="text" name="nama" value="{{ old('nama', $kategori->nama ?? '') }}" class="form-control" required>
</div>
<button type="submit" class="btn btn-success">{{ $submit }}</button>
