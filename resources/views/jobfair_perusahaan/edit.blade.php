@extends('layouts.app')

@section('content')
    <h1>Edit Job Fair Perusahaan</h1>

    <form action="{{ route('job-fair-perusahaan.update', $item->id) }}" method="POST" class="grid">
        @csrf
        @method('PUT')
        <div class="field">
            <label>Job Fair</label>
            <select name="job_fair_id" required>
                <option value="">Pilih Job Fair</option>
                @foreach($jobFairs as $job)
                    <option value="{{ $job->id }}" @selected(old('job_fair_id', $item->job_fair_id) == $job->id)>{{ $job->nama_kegiatan }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label>Perusahaan</label>
            <select name="perusahaan_id" required>
                <option value="">Pilih Perusahaan</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" @selected(old('perusahaan_id', $item->perusahaan_id) == $company->id)>{{ $company->nama_perusahaan }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label>Jumlah Lowongan</label>
            <input type="number" name="jumlah_lowongan" value="{{ old('jumlah_lowongan', $item->jumlah_lowongan) }}">
        </div>
        <div class="field">
            <label>Realisasi Penempatan</label>
            <input type="number" name="realisasi_penempatan" value="{{ old('realisasi_penempatan', $item->realisasi_penempatan) }}">
        </div>
        <button type="submit" class="button">Perbarui</button>
    </form>
@endsection
