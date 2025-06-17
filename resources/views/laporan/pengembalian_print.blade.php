<!DOCTYPE html>
<html>
<head>
    <title>Print Laporan Pengembalian</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 6px; }
    </style>
</head>
<body>
    <h2>Laporan Pengembalian</h2>
    <table width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Barang</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Kondisi</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengembalians as $index => $kembali)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $kembali->peminjam }}</td>
                <td>{{ $kembali->barang }}</td>
                <td>{{ $kembali->tgl_kembali }}</td>
                <td>{{ $kembali->status }}</td>
                <td>{{ ucfirst($kembali->kondisi) }}</td>
                <td>Rp{{ number_format($kembali->denda, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
