@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center">
        <h1>Job Fair Perusahaan</h1>
        <a href="{{ route('job-fair-perusahaan.create') }}" class="button">Tambah</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Job Fair</th>
                <th>Perusahaan</th>
                <th>Jumlah Lowongan</th>
                <th>Realisasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->jobFair?->nama_kegiatan }}</td>
                    <td>{{ $item->perusahaan?->nama_perusahaan }}</td>
                    <td>{{ $item->jumlah_lowongan }}</td>
                    <td>{{ $item->realisasi_penempatan }}</td>
                    <td>
                        <a href="{{ route('job-fair-perusahaan.edit', $item->id) }}" class="button button-secondary">Edit</a>
                        <form action="{{ route('job-fair-perusahaan.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-secondary" onclick="return confirm('Hapus data?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $data->links() }}
@endsection
