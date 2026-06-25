@extends('layouts.app')

@section('content')
    <h1>Tambah Laporan</h1>

    <form action="{{ route('laporan.store') }}" method="POST" class="grid">
        @csrf
        <div class="field">
            <label>User</label>
            <select name="user_id">
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label>Jenis Laporan</label>
            <input type="text" name="jenis_laporan" value="{{ old('jenis_laporan') }}" required>
        </div>
        <div class="field">
            <label>Format File</label>
            <select name="format_file" required>
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
            </select>
        </div>
        <button type="submit" class="button">Simpan</button>
    </form>
@endsection
