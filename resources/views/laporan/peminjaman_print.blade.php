<!DOCTYPE html>
<html>
<head>
    <title>Print Laporan Peminjaman</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 6px; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman</h2>
    <table width="100%">
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
</body>
</html>
