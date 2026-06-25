@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center">
        <h1>Laporan</h1>
        <a href="{{ route('laporan.create') }}" class="button">Tambah Laporan</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Jenis Laporan</th>
                <th>Format</th>
                <th>Created At</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->user?->nama }}</td>
                    <td>{{ $item->jenis_laporan }}</td>
                    <td>{{ $item->format_file }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        <a href="{{ route('laporan.edit', $item->id) }}" class="button button-secondary">Edit</a>
                        <form action="{{ route('laporan.destroy', $item->id) }}" method="POST" style="display:inline-block;">
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
