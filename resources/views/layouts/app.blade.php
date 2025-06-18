<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SARPRAS</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <h2>SARPRAS</h2>
            <ul>
                <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('kategori.index') }}">Kategori Barang</a></li>
                <li><a href="{{ route('barang.index') }}">Data Barang</a></li>
                <li><a href="{{ route('peminjaman.index') }}">Peminjaman</a></li>
                <li><a href="{{ route('pengembalian.index') }}">Kelola Pengembalian</a></li>
                <li><a href="{{ route('laporan.peminjaman') }}">Laporan Peminjaman</a></li>
                <li><a href="{{ route('laporan.pengembalian') }}">Laporan Pengembalian</a></li>
                <li><a href="{{ route('users.index') }}">User</a></li>
            </ul>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
            </form>
            <button form="logout-form" class="danger-btn">Log out</button>
        </aside>

        <main class="main">
            @yield('content')
        </main>
    </div>
</body>

</html>
