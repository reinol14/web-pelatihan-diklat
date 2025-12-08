@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Detail Pegawai</h4>

    {{-- Foto Pegawai --}}
    <div class="mb-3">
        @if($pegawai->foto)
            <img src="{{ asset('storage/' . $pegawai->foto) }}"
                 alt="Foto Pegawai"
                 class="img-fluid rounded"
                 style="max-width: 200px;">
        @else
            <img src="{{ asset('images/default-avatar.png') }}"
                 alt="Foto Default"
                 class="img-fluid rounded"
                 style="max-width: 200px;">
        @endif
    </div>

    {{-- Data Utama --}}
    <div class="mb-3"><strong>NIP:</strong> {{ $pegawai->nip }}</div>
    <div class="mb-3"><strong>Nama:</strong> {{ $pegawai->nama }}</div>
    <div class="mb-3"><strong>Pangkat / Golongan:</strong>
        {{ $pegawai->pangkat ?? '-' }} / {{ $pegawai->golongan ?? '-' }}
    </div>
    <div class="mb-3"><strong>Jabatan:</strong> {{ $pegawai->jabatan ?? '-' }}</div>
    <div class="mb-3"><strong>Jenis ASN:</strong> {{ $pegawai->jenis_asn ?? '-' }}</div>
    <div class="mb-3"><strong>Kategori Jabatan ASN:</strong> {{ $pegawai->kategori_jabatanasn ?? '-' }}</div>

    {{-- Unit & Sub-Unit Kerja --}}
    <div class="mb-3">
        <strong>Unit Kerja:</strong>
        {{ optional($pegawai->unitKerja)->unitkerja ?? '-' }}
        @if(optional($pegawai->unitKerja)->sub_unitkerja)
            <br><strong>Sub-Unit Kerja:</strong>
            {{ $pegawai->unitKerja->sub_unitkerja }}
        @endif
    </div>

    <div class="mb-3"><strong>Email:</strong> {{ $pegawai->email ?? '-' }}</div>
    <div class="mb-3"><strong>No HP:</strong> {{ $pegawai->no_hp ?? '-' }}</div>
    <div class="mb-3"><strong>Alamat:</strong> {{ $pegawai->alamat ?? '-' }}</div>
    <div class="mb-3"><strong>TMT:</strong>
        {{ $pegawai->tmt ? \Carbon\Carbon::parse($pegawai->tmt)->format('d-m-Y') : '-' }}
    </div>

    {{-- Aksi --}}
    <div class="mb-3">
        <a href="{{ route('Admin.Pegawai.edit', $pegawai->id) }}" class="btn btn-warning">
            <i class="fa fa-edit"></i> Edit
        </a>
        <a href="{{ route('Admin.Pegawai.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
