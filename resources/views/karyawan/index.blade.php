@extends('layouts.karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data Karyawan</h4>
    @if(auth()->user()->isManager())
        <a href="{{ route('karyawan.create') }}" class="btn btn-primary">Tambah Karyawan</a>
    @endif
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Departemen</th>
                    <th>Tanggal Masuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawans as $index => $karyawan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $karyawan->nama }}</td>
                    <td>{{ $karyawan->jabatan }}</td>
                    <td>{{ $karyawan->departemen }}</td>
                    <td>{{ \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('karyawan.show', $karyawan) }}" class="btn btn-info btn-sm">Detail</a>
                        @if(auth()->user()->isManager())
                            <a href="{{ route('karyawan.edit', $karyawan) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('karyawan.destroy', $karyawan) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada data karyawan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
