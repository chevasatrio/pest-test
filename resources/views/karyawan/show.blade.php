@extends('layouts.karyawan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Detail Karyawan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><th width="35%">Nama</th><td>{{ $karyawan->nama }}</td></tr>
                    <tr><th>Jabatan</th><td>{{ $karyawan->jabatan }}</td></tr>
                    <tr><th>Departemen</th><td>{{ $karyawan->departemen }}</td></tr>
                    <tr><th>No. Telepon</th><td>{{ $karyawan->no_telepon ?? '-' }}</td></tr>
                    <tr><th>Tanggal Masuk</th><td>{{ \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('d M Y') }}</td></tr>
                    <tr><th>Email</th><td>{{ $karyawan->user->email }}</td></tr>
                </table>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Kembali</a>
                    @if(auth()->user()->isManager())
                        <a href="{{ route('karyawan.edit', $karyawan) }}" class="btn btn-warning">Edit</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
