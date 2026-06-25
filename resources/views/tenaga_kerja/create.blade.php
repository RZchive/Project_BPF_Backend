@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center">
        <h1>Tambah Tenaga Kerja</h1>
    </div>

    <form action="{{ route('tenaga-kerja.store') }}" method="POST" enctype="multipart/form-data" class="grid">
        @csrf
        <div class="field">
            <label>NIK</label>
            <input type="text" name="nik" value="{{ old('nik') }}" required>
        </div>
        <div class="field">
            <label>Nama</label>
            <input type="text" name="nama" value="{{ old('nama') }}" required>
        </div>
        <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>
        <div class="field">
            <label>No HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp') }}">
        </div>
        <div class="field">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L" @selected(old('jenis_kelamin') == 'L')>Laki-laki</option>
                <option value="P" @selected(old('jenis_kelamin') == 'P')>Perempuan</option>
            </select>
        </div>
        <div class="field">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
        </div>
        <div class="field">
            <label>Alamat</label>
            <textarea name="alamat">{{ old('alamat') }}</textarea>
        </div>
        <div class="field">
            <label>Pendidikan Terakhir</label>
            <input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir') }}">
        </div>
        <div class="field">
            <label>Status Pekerjaan</label>
            <input type="text" name="status_pekerjaan" value="{{ old('status_pekerjaan') }}">
        </div>
        <div class="field">
            <label>Foto</label>
            <input type="file" name="foto" accept="image/*">
        </div>
        <button type="submit" class="button">Simpan</button>
    </form>
@endsection
