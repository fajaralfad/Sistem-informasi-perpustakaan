@extends('layouts.app')

@section('content')
<h1>Edit Pengarang</h1>
<form action="{{ route('admin.pengarang.update', $pengarang->id) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Nama:</label><br>
    <input type="text" name="nama" value="{{ $pengarang->nama }}" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="{{ $pengarang->email }}"><br><br>

    <label>Alamat:</label><br>
    <textarea name="alamat">{{ $pengarang->alamat }}</textarea><br><br>

    <button type="submit">Update</button>
</form>
@endsection
