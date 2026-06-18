@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center">
        <h1>Pemagangan</h1>
        <a href="{{ route('pemagangan.create') }}" class="button">Tambah Pemagangan</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tenaga Kerja</th>
                <th>Perusahaan</th>
                <th>Bidang</th>
                <th>Durasi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->tenagaKerja?->nama }}</td>
                    <td>{{ $item->perusahaan?->nama_perusahaan }}</td>
                    <td>{{ $item->bidang }}</td>
                    <td>{{ $item->durasi }}</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        <a href="{{ route('pemagangan.edit', $item->id) }}" class="button button-secondary">Edit</a>
                        <form action="{{ route('pemagangan.destroy', $item->id) }}" method="POST" style="display:inline-block;">
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
