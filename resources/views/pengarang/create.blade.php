@extends('layouts.app')

@section('content')
<h1>Tambah Pengarang</h1>
<form action="{{ route('admin.pengarang.store') }}" method="POST">
    @csrf
    <label>Nama:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email"><br><br>

    <label>Alamat:</label><br>
    <textarea name="alamat"></textarea><br><br>

    <button type="submit">Simpan</button>
</form>
@endsection
