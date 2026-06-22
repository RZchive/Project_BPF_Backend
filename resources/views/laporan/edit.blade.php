@extends('layouts.app')

@section('content')
    <h1>Edit Laporan</h1>

    <form action="{{ route('laporan.update', $item->id) }}" method="POST" class="grid">
        @csrf
        @method('PUT')
        <div class="field">
            <label>User</label>
            <select name="user_id">
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id', $item->user_id) == $user->id)>{{ $user->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label>Jenis Laporan</label>
            <input type="text" name="jenis_laporan" value="{{ old('jenis_laporan', $item->jenis_laporan) }}" required>
        </div>
        <div class="field">
            <label>Format File</label>
            <select name="format_file" required>
                <option value="pdf" @selected(old('format_file', $item->format_file) == 'pdf')>PDF</option>
                <option value="excel" @selected(old('format_file', $item->format_file) == 'excel')>Excel</option>
            </select>
        </div>
        <button type="submit" class="button">Perbarui</button>
    </form>
@endsection
