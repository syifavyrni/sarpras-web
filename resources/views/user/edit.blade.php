@extends('layouts.app')

@section('content')
<style>
    form label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }

    form input[type="text"],
    form input[type="email"] {
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

    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        padding: 10px 15px;
        border-radius: 8px;
        margin: 10px 0;
    }
</style>

<h2>Edit User</h2>

@if ($errors->any())
    <div class="alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nama</label>
    <input type="text" name="name" value="{{ old('name', $user->name) }}">

    <label>Email</label>
    <input type="email" name="email" value="{{ old('email', $user->email) }}">

    <button type="submit" class="btn-add">Update</button>
</form>
@endsection
