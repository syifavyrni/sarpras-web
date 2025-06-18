@extends('layouts.app')

@section('content')
<style>
    form label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }
    form input[type="text"],
    form input[type="email"],
    form input[type="password"] {
        width: 100%;
        padding: 8px;
        margin-top: 4px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
    }
    .btn-add {
        background-color: #1e3a8a;
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
    }
    .btn-add:hover {
        background-color: #1e40af;
    }
    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        padding: 10px 15px;
        border-radius: 8px;
        margin: 10px 0;
    }
    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
    }
</style>

<h2>Tambah User</h2>

@if ($errors->any())
    <div class="alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <label for="name">Nama <span style="color: red;">*</span></label>
    <input type="text" name="name" id="name" value="{{ old('name') }}" required>

    <label for="email">Email <span style="color: red;">*</span></label>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
    
    <label for="password">Password <span style="color: red;">*</span></label>
    <input type="password" name="password" id="password" required>

    <button type="submit" class="btn-add">Simpan</button>
</form>

@endsection
