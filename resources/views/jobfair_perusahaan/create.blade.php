@extends('layouts.app')

@section('content')
    <h1>Tambah Job Fair Perusahaan</h1>

    <form action="{{ route('job-fair-perusahaan.store') }}" method="POST" class="grid">
        @csrf
        <div class="field">
            <label>Job Fair</label>
            <select name="job_fair_id" required>
                <option value="">Pilih Job Fair</option>
                @foreach($jobFairs as $job)
                    <option value="{{ $job->id }}">{{ $job->nama_kegiatan }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label>Perusahaan</label>
            <select name="perusahaan_id" required>
                <option value="">Pilih Perusahaan</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->nama_perusahaan }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label>Jumlah Lowongan</label>
            <input type="number" name="jumlah_lowongan" value="{{ old('jumlah_lowongan') }}">
        </div>
        <div class="field">
            <label>Realisasi Penempatan</label>
            <input type="number" name="realisasi_penempatan" value="{{ old('realisasi_penempatan') }}">
        </div>
        <button type="submit" class="button">Simpan</button>
    </form>
@endsection
