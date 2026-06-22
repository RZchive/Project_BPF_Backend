@extends('layouts.app')

@section('content')
    <h1>Tambah Job Fair</h1>

    <form action="{{ route('job-fair.store') }}" method="POST" class="grid">
        @csrf
        <div class="field">
            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required>
        </div>
        <div class="field">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="{{ old('tanggal') }}">
        </div>
        <div class="field">
            <label>Lokasi</label>
            <input type="text" name="lokasi" value="{{ old('lokasi') }}">
        </div>
        <div class="field">
            <label>Deskripsi</label>
            <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>
        </div>
        <button type="submit" class="button">Simpan</button>
    </form>
@endsection
