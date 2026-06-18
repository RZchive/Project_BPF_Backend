@extends('layouts.app')

@section('content')
    <h1>Edit Job Fair</h1>

    <form action="{{ route('job-fair.update', $item->id) }}" method="POST" class="grid">
        @csrf
        @method('PUT')
        <div class="field">
            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $item->nama_kegiatan) }}" required>
        </div>
        <div class="field">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="{{ old('tanggal', $item->tanggal) }}">
        </div>
        <div class="field">
            <label>Lokasi</label>
            <input type="text" name="lokasi" value="{{ old('lokasi', $item->lokasi) }}">
        </div>
        <div class="field">
            <label>Deskripsi</label>
            <textarea name="deskripsi">{{ old('deskripsi', $item->deskripsi) }}</textarea>
        </div>
        <button type="submit" class="button">Perbarui</button>
    </form>
@endsection
