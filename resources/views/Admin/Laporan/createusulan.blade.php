@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0 fw-bold">Tambah Usulan Laporan</h4>
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
                                        <li>
                                            {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('laporan.storeusulan') }}" enctype="multipart/form-data">

                            @csrf

                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>


                            <div class="mb-3">
                                <label for="golongan_ruang" class="form-label">Golongan Ruang</label>
                                <input type="text" name="golongan_ruang" class="form-control" required>
                            </div>


                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="unit_kerja" class="form-label">Unit Kerja</label>
                                <input type="text" name="unit_kerja" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Upload Foto</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                            </div>


                            <div class="mb-3">
                                <label for="nama_pelatihan" class="form-label">Nama Pelatihan</label>
                                <input type="text" name="nama_pelatihan" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="jenis_pelatihan" class="form-label">Jenis Pelatihan</label>
                                <select name="jenis_pelatihan" class="form-select" required>
                                    <option value="">-- Pilih Jenis Pelatihan --</option>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="metode_pelatihan" class="form-label">Metode Pelatihan</label>
                                <select name="metode_pelatihan" class="form-select" required>
                                    <option value="">-- Pilih Metode Pelatihan --</option>
                                    <option value="tatap_muka">Tatap Muka</option>
                                    <option value="daring">Daring</option>
                                    <option value="campuran">Campuran</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="rumpun_pelatihan" class="form-label">Rumpun Pelatihan</label>
                                <select name="rumpun_pelatihan" class="form-select" required>
                                    <option value="">-- Pilih Rumpun Pelatihan --</option>
                                    <option value="Teknis">Teknis</option>
                                    <option value="Manajerial">Manajerial</option>
                                    <option value="Fungsional">Fungsional</option>
                                </select>
                            </div>


                            <div class="mb-3">
                                <label for="penyelenggara_pelatihan" class="form-label">Penyelenggara Pelatihan</label>
                                <input type="text" name="penyelenggara_pelatihan" class="form-control"
                                    value="{{ old('penyelenggara_pelatihan') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="pelaksanaan_pelatihan" class="form-label">Pelaksanaan Pelatihan</label>
                                <input type="text" name="pelaksanaan_pelatihan" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="hasil_pelatihan" class="form-label">Hasil Pelatihan</label>
                                <select name="hasil_pelatihan" class="form-select">
                                    <option value="lulus">Lulus</option>
                                    <option value="tidak lulus">Tidak Lulus</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="sertifikat" class="form-label">Sertifikat (Opsional)</label>
                                <input type="file" name="sertifikat" class="form-control">
                            </div>


                            <div class="mb-3">
                                <label for="judul_laporan" class="form-label">Judul Laporan</label>
                                <input type="text" name="judul_laporan" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="abstrak_laporan" class="form-label">Abstrak Laporan</label>
                                <textarea name="abstrak_laporan" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="link_laporan" class="form-label">Link Laporan</label>
                                <input type="url" name="link_laporan" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="Status_peserta" class="form-label">Status Peserta</label>
                                <select name="Status_peserta" class="form-select">
                                    <option value="Alumni">Alumni</option>
                                    <option value="Non Alumni">Non Alumni</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
