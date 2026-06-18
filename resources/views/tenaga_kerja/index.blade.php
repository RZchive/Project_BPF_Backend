@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center">
        <h1>Tenaga Kerja</h1>
        <a href="{{ route('tenaga-kerja.create') }}" class="button">Tambah Tenaga Kerja</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Pendidikan Terakhir</th>
                <th>Status Pekerjaan</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nik }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->no_hp }}</td>
                    <td>{{ $item->jenis_kelamin }}</td>
                    <td>{{ $item->tanggal_lahir }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->pendidikan_terakhir }}</td>
                    <td>{{ $item->status_pekerjaan }}</td>
                    <td>
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" style="max-width:80px; max-height:80px; object-fit:cover;">
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('tenaga-kerja.edit', $item->id) }}" class="button button-secondary">Edit</a>
                        <form action="{{ route('tenaga-kerja.destroy', $item->id) }}" method="POST" style="display:inline-block; margin-left:4px;">
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
