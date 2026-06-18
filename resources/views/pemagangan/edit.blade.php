@extends('layouts.app')

@section('content')
    <h1>Edit Pemagangan</h1>

    <form action="{{ route('pemagangan.update', $item->id) }}" method="POST" class="grid">
        @csrf
        @method('PUT')
        <div class="field">
            <label>Tenaga Kerja</label>
            <select name="tenaga_kerja_id" required>
                <option value="">Pilih Tenaga Kerja</option>
                @foreach($tenagaKerja as $tk)
                    <option value="{{ $tk->id }}" @selected(old('tenaga_kerja_id', $item->tenaga_kerja_id) == $tk->id)>{{ $tk->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label>Perusahaan</label>
            <select name="perusahaan_id" required>
                <option value="">Pilih Perusahaan</option>
                @foreach($perusahaan as $p)
                    <option value="{{ $p->id }}" @selected(old('perusahaan_id', $item->perusahaan_id) == $p->id)>{{ $p->nama_perusahaan }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label>Bidang</label>
            <input type="text" name="bidang" value="{{ old('bidang', $item->bidang) }}">
        </div>
        <div class="field">
            <label>Durasi</label>
            <input type="text" name="durasi" value="{{ old('durasi', $item->durasi) }}">
        </div>
        <div class="field">
            <label>Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $item->tanggal_mulai) }}">
        </div>
        <div class="field">
            <label>Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $item->tanggal_selesai) }}">
        </div>
        <div class="field">
            <label>Status</label>
            <select name="status">
                <option value="berjalan" @selected(old('status', $item->status) == 'berjalan')>Berjalan</option>
                <option value="selesai" @selected(old('status', $item->status) == 'selesai')>Selesai</option>
            </select>
        </div>
        <button type="submit" class="button">Perbarui</button>
    </form>
@endsection
