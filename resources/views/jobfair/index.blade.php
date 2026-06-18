@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center">
        <h1>Job Fair</h1>
        <a href="{{ route('job-fair.create') }}" class="button">Tambah Job Fair</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kegiatan</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nama_kegiatan }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td>
                        <a href="{{ route('job-fair.edit', $item->id) }}" class="button button-secondary">Edit</a>
                        <form action="{{ route('job-fair.destroy', $item->id) }}" method="POST" style="display:inline-block;">
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
