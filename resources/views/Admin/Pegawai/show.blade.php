@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="text-center mb-4">
        <h1 class="text-primary fw-bold">Detail Pegawai</h1>
        <p class="text-muted">Informasi lengkap terkait data pegawai</p>
    </div>

    <!-- Card Utama -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light">
            <!-- Foto & Identitas Utama -->
            <div class="row align-items-center mb-4">
                <!-- Foto Pegawai -->
                <div class="col-md-4 text-center">
                    @if($pegawai->foto)
                        <img src="{{ asset('storage/' . $pegawai->foto) }}" 
                             alt="Foto Pegawai" 
                             class="img-thumbnail rounded-circle shadow-sm mb-3"
                             style="width: 200px; height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" 
                             alt="Foto Default" 
                             class="img-thumbnail rounded-circle shadow-sm mb-3"
                             style="width: 200px; height: 200px; object-fit: cover;">
                    @endif
                    <h4 class="text-dark fw-bold mb-1">{{ $pegawai->nama }}</h4>
                    <span class="badge bg-info text-dark py-2 px-3">{{ $pegawai->jenis_asn ?? 'ASN' }}</span>
                </div>

                <!-- Identitas Utama -->
                <div class="col-md-8">
                    <table class="table table-borderless table-hover">
                        <tr>
                            <td class="text-muted"><i class="fa fa-id-card"></i> NIP:</td>
                            <td class="text-dark fw-semibold">{{ $pegawai->nip }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fa fa-user"></i> Jabatan:</td>
                            <td class="text-dark fw-semibold">{{ $pegawai->jabatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fa fa-medal"></i> Pangkat / Golongan:</td>
                            <td class="text-dark fw-semibold">{{ $pegawai->pangkat ?? '-' }} / {{ $pegawai->golongan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fa fa-sitemap"></i> Kategori Jabatan ASN:</td>
                            <td class="text-dark fw-semibold">{{ $pegawai->kategori_jabatanasn ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fa fa-building"></i> Unit Kerja:</td>
                            <td class="text-dark fw-semibold">
                                {{ optional($pegawai->unitKerja)->unitkerja ?? '-' }}
                                @if(optional($pegawai->unitKerja)->sub_unitkerja)
                                    <span class="text-secondary d-block">Sub Unit: {{ $pegawai->unitKerja->sub_unitkerja }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Informasi Kontak, Tambahan, dan Atasan -->
            <div class="row align-items-stretch">
                <!-- Informasi Kontak -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-4 h-100">
                        <div class="card-body">
                            <h5 class="text-primary fw-bold">Informasi Kontak</h5>
                            <p class="text-muted">Hubungi pegawai melalui kontak berikut.</p>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted"><i class="fa fa-envelope"></i> Email:</td>
                                    <td>{{ $pegawai->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fa fa-phone"></i> Nomor HP:</td>
                                    <td>{{ $pegawai->no_hp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fa fa-map-marker-alt"></i> Alamat:</td>
                                    <td>{{ $pegawai->alamat ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-4 h-100">
                        <div class="card-body">
                            <h5 class="text-primary fw-bold">Informasi Tambahan</h5>
                            <p class="text-muted">Detail terkait pegawai</p>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted"><i class="fa fa-calendar"></i> TMT:</td>
                                    <td>{{ $pegawai->tmt ? \Carbon\Carbon::parse($pegawai->tmt)->format('d-m-Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fa fa-user-tag"></i> Status:</td>
                                    <td>
                                        <span class="badge bg-success text-white py-2 px-3">Aktif</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informasi Atasan -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="text-primary fw-bold">Informasi Atasan</h5>
                            <p class="text-muted">Detail atasan saat ini</p>
                            @if($pegawai->atasan)
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted"><i class="fa fa-user-circle"></i> Nama Atasan:</td>
                                    <td class="text-dark fw-semibold">{{ $pegawai->atasan->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fa fa-medal"></i> Jabatan:</td>
                                    <td class="text-dark fw-semibold">{{ $pegawai->atasan->jabatan }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fa fa-sitemap"></i> Unit Kerja:</td>
                                    <td class="text-dark fw-semibold">{{ $pegawai->atasan->unitKerja->unitkerja ?? '-' }}</td>
                                </tr>
                            </table>
                            @else
                            <p class="text-center text-muted">Atasan tidak ditemukan.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="text-center mt-5">
                <a href="{{ route('Admin.Pegawai.edit', $pegawai->id) }}" class="btn btn-warning text-white shadow-sm px-4">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a href="{{ route('Admin.Pegawai.index') }}" class="btn btn-secondary shadow-sm px-4">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection