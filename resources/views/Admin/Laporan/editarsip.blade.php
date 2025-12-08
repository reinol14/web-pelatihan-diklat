@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg">
                <div class="card-header" style="background-color: #D1B28B; color: black;">
                    <h3 class="mb-0 text-center fw-bold">EDIT ARSIP LAPORAN DIKLAT</h3>
                </div>

                <div class="card-body" style="background-color: #f8f5f1;">
                    <div class="form-container mx-auto" style="max-width: 1200px; padding: 40px; background-color: #ffffff; border-radius: 10px; border: 1px solid #ccc; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);">
                        <form action="{{ route('laporan.updatearsip', $laporan_arsip->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="nip" class="form-label">NIP</label>
                                        <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip', $laporan_arsip->nip) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_penulis" class="form-label">Nama Penulis</label>
                                        <input type="text" class="form-control" id="nama_penulis" name="nama_penulis" value="{{ old('nama_penulis', $laporan_arsip->nama_penulis) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="jabatan" class="form-label">Jabatan</label>
                                        <select class="form-control" id="jabatan" name="jabatan" required>
                                            <option value="" disabled selected>Pilih Jabatan</option>
                                            @foreach ($jabatan as $item)
                                                <option value="{{ $item->jabatan }}" {{ old('jabatan', $laporan_arsip->jabatan) == $item->jabatan ? 'selected' : '' }}>
                                                    {{ $item->jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="mb-3">
                                        <label for="golongan" class="form-label">Golongan</label>
                                        <select class="form-control" id="golongan" name="golongan" required>
                                            <option value="" disabled selected>Pilih Golongan</option>
                                            @foreach ($golongan as $item)
                                                <option value="{{ $item->golongan }}" {{ old('golongan', $laporan_arsip->golongan) == $item->golongan ? 'selected' : '' }}>
                                                    {{ $item->golongan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label for="unit_kerja" class="form-label">Unit Kerja</label>
                                        <select class="form-select @error('unit_kerja') is-invalid @enderror" id="unit_kerja" name="unit_kerja" required>
                                            <option value="" disabled selected>Pilih Unit Kerja</option>
                                            @foreach ($unitKerja as $item)
                                                <option value="{{ $item->kode_unitkerja }}" 
                                                    {{ old('unit_kerja', $laporan_arsip->unit_kerja) == $item->kode_unitkerja ? 'selected' : '' }}>
                                                    {{ $item->singkatan }} - {{ $item->sub_unitkerja }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unit_kerja')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <!-- Middle Column -->
                                <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jenis_pelatihan" class="form-label">Jenis Pelatihan</label>
                                    <select class="form-select @error('jenis_pelatihan') is-invalid @enderror" id="jenis_pelatihan" name="jenis_pelatihan" required>
                                        <option value="" disabled selected>Pilih Jenis Pelatihan</option>
                                        @foreach ($jenisPelatihan as $item)
                                            <option value="{{ $item->jenis_pelatihan }}" 
                                                {{ old('jenis_pelatihan', $laporan_arsip->jenis_pelatihan) == $item->jenis_pelatihan ? 'selected' : '' }}>
                                                {{ $item->jenis_pelatihan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_pelatihan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>




                                    <div class="mb-3">
                                        <label for="nama_pelatihan" class="form-label">Nama Pelatihan</label>
                                        <input type="text" class="form-control" id="nama_pelatihan" name="nama_pelatihan" value="{{ old('nama_pelatihan', $laporan_arsip->nama_pelatihan) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tahun_pelatihan" class="form-label">Tahun Pelatihan</label>
                                        <select class="form-select @error('tahun_pelatihan') is-invalid @enderror" id="tahun_pelatihan" name="tahun_pelatihan" required>
                                            <option value="" disabled selected>Pilih Tahun Pelatihan</option>
                                            @for ($year = 2021; $year <= 2030; $year++)
                                                <option value="{{ $year }}" {{ old('tahun_pelatihan', $laporan_arsip->tahun_pelatihan) == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('tahun_pelatihan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label for="pelaksanaan" class="form-label">Metode Pelaksanaan</label>
                                        <select class="form-select @error('pelaksanaan') is-invalid @enderror" id="pelaksanaan" name="pelaksanaan" required>
                                            <option value="" disabled selected>Pilih Metode Pelaksanaan</option>
                                            @foreach ($metodePelaksanaan as $item)
                                                <option value="{{ $item->metode_pelatihan }}" 
                                                    {{ old('pelaksanaan', $laporan_arsip->pelaksanaan) == $item->metode_pelatihan ? 'selected' : '' }}>
                                                    {{ $item->metode_pelatihan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('pelaksanaan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="status_ajuan" class="form-label">Status Ajuan</label>
                                        <select class="form-control" id="status_ajuan" name="status_ajuan" required>
                                            <option value="" disabled>Pilih Status</option>
                                            <option value="approved" {{ $laporan_arsip->status_ajuan == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="in progress" {{ $laporan_arsip->status_ajuan == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Right Column (Latar Belakang) -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mode_pelatihan" class="form-label">Mode Pelatihan</label>
                                        <select class="form-select @error('mode_pelatihan') is-invalid @enderror" id="mode_pelatihan" name="mode_pelatihan" required>
                                            <option value="" disabled selected>Pilih Mode Pelatihan</option>
                                            @foreach ($pelaksanaan as $item)
                                                <option value="{{ $item->pelaksanaan_pelatihan }}" 
                                                    {{ old('mode_pelatihan', $laporan_arsip->mode_pelatihan) == $item->pelaksanaan_pelatihan ? 'selected' : '' }}>
                                                    {{ $item->pelaksanaan_pelatihan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('mode_pelatihan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label for="waktu_pelaksanaan" class="form-label">Waktu Pelaksanaan</label>
                                        <input type="text" class="form-control" id="waktu_pelaksanaan" name="waktu_pelaksanaan" value="{{ old('waktu_pelaksanaan', $laporan_arsip->waktu_pelaksanaan) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="judul_laporan" class="form-label">Judul Laporan</label>
                                        <input type="text" class="form-control" id="judul_laporan" name="judul_laporan" value="{{ old('judul_laporan', $laporan_arsip->judul_laporan) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="latar_belakang" class="form-label">Latar Belakang</label>
                                        <textarea class="form-control" id="latar_belakang" name="latar_belakang" rows="6" required>{{ old('latar_belakang', $laporan_arsip->latar_belakang) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="unggah_laporan" class="form-label">Unggah Laporan</label>
                                        <input type="file" class="form-control" id="unggah_laporan" name="unggah_laporan" accept=".pdf">
                                        @if($laporan_arsip->unggah_laporan)
                                            <p>File saat ini: <a href="{{ asset($laporan_arsip->unggah_laporan) }}" target="_blank">Lihat Laporan</a></p>
                                        @else
                                            <p>Tidak ada laporan yang diunggah.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('laporan.arsip') }}" class="btn btn-danger">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-check"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            max-width: 1200px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            background-color: #f8f8f8;
        }

        .form-control:focus {
            border-color: #D1B28B;
            box-shadow: 0 0 10px rgba(209, 178, 139, 0.2);
            background-color: white;
        }

        .btn-danger, .btn-success {
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .btn-success {
            background-color: #27ae60;
            border-color: #27ae60;
        }

        .btn-success:hover {
            background-color: #218c53;
            border-color: #218c53;
        }
    </style>
@endsection
