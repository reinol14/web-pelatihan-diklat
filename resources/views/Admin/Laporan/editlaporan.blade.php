@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0 fw-bold">Edit Usulan Laporan</h4>
                    </div>

                    <div class="card-body bg-light">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('laporan.updateusulan', $laporan->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="number" name="nip" class="form-control" value="{{ old('nip', $laporan->nip) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $laporan->nama) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="golongan_ruang" class="form-label">Golongan Ruang</label>
                                <input type="text" name="golongan_ruang" class="form-control" value="{{ old('golongan_ruang', $laporan->golongan_ruang) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $laporan->jabatan) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="unit_kerja" class="form-label">Unit Kerja</label>
                                <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', $laporan->unit_kerja) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $laporan->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Upload Foto</label>
                                <input type="file" name="foto" class="form-control" accept="image/jpeg, image/png, image/jpg">

                                @if ($laporan->foto)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto" width="150">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="sertifikat" class="form-label">Upload Sertifikat</label>
                                <input type="file" name="sertifikat" class="form-control" accept="image/jpeg, image/png, image/jpg">

                                @if ($laporan->sertifikat)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $laporan->sertifikat) }}" target="_blank">Lihat Sertifikat</a>
                                    </div>
                                @endif
                            </div>


                            <div class="mb-3">
                                <label for="nama_pelatihan" class="form-label">Nama Pelatihan</label>
                                <input type="text" name="nama_pelatihan" class="form-control" value="{{ old('nama_pelatihan', $laporan->nama_pelatihan) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="pelaksanaan_pelatihan" class="form-label">Pelaksanaan Pelatihan</label>
                                <input type="text" name="pelaksanaan_pelatihan" class="form-control" value="{{ old('pelaksanaan_pelatihan', $laporan->pelaksanaan_pelatihan) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="penyelenggara_pelatihan" class="form-label">Penyelenggara Pelatihan</label>
                                <input type="text" name="penyelenggara_pelatihan" class="form-control" value="{{ old('penyelenggara_pelatihan', $laporan->penyelenggara_pelatihan) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="rumpun_pelatihan" class="form-label">Rumpun Pelatihan</label>
                                <input type="text" name="rumpun_pelatihan" class="form-control" value="{{ old('rumpun_pelatihan', $laporan->rumpun_pelatihan) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $laporan->tanggal_mulai) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $laporan->tanggal_selesai) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="hasil_pelatihan" class="form-label">Hasil Pelatihan</label>
                                <select name="hasil_pelatihan" class="form-select" required>
                                    <option value="lulus">Lulus</option>
                                    <option value="tidak lulus">Tidak Lulus</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="judul_laporan" class="form-label">Judul Laporan</label>
                                <input type="text" name="judul_laporan" class="form-control" value="{{ old('judul_laporan', $laporan->judul_laporan) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="abstrak_laporan" class="form-label">Abstrak Laporan</label>
                                <textarea name="abstrak_laporan" class="form-control" required>{{ old('abstrak_laporan', $laporan->abstrak_laporan) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="link_laporan" class="form-label">Link Laporan</label>
                                <input type="url" name="link_laporan" class="form-control" value="{{ old('link_laporan', $laporan->link_laporan) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan...">{{ old('keterangan', $laporan->keterangan) }}</textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('laporan.usulan') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
