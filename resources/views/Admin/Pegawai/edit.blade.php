@extends('layouts.app')

@section('content')
<!-- di head atau sebelum </body> -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<div class="container mt-4">
    <h4>Edit Data Pegawai</h4>

    <form action="{{ route('Admin.Pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip', $pegawai->nip) }}" required>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="pangkat" class="form-label">Pangkat</label>
            <input type="text" class="form-control" id="pangkat" name="pangkat" value="{{ old('pangkat', $pegawai->pangkat) }}">
        </div>

        <div class="mb-3">
            <label for="golongan" class="form-label">Golongan</label>
            <input type="text" class="form-control" id="golongan" name="golongan" value="{{ old('golongan', $pegawai->golongan) }}">
        </div>

        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}">
        </div>

        <div class="mb-3">
            <label for="jenis_asn" class="form-label">Jenis ASN</label>
            <input type="text" class="form-control" id="jenis_asn" name="jenis_asn" value="{{ old('jenis_asn', $pegawai->jenis_asn) }}">
        </div>

        <div class="mb-3">
            <label for="kategori_jabatanasn" class="form-label">Kategori Jabatan ASN</label>
            <input type="text" class="form-control" id="kategori_jabatanasn" name="kategori_jabatanasn" value="{{ old('kategori_jabatanasn', $pegawai->kategori_jabatanasn) }}">
        </div>

        <div class="mb-3">
    <label for="kode_unitkerja" class="form-label">Unit / Sub-Unit Kerja</label>

    @if (auth()->check() && auth()->user()->is_admin == 1)
        <!-- Superadmin: Bisa memilih -->
        <select name="kode_unitkerja" id="kode_unitkerja" class="form-select select2">
            <option value="">-- Pilih Unit / Sub-Unit --</option>
            @foreach($unitKerjaGrouped as $unitName => $subs)
                <optgroup label="{{ $unitName }}">
                    @foreach($subs as $row)
                        <option value="{{ $row->kode_unitkerja }}"
                            {{ old('kode_unitkerja', $pegawai->kode_unitkerja) == $row->kode_unitkerja ? 'selected' : '' }}>
                            {{ $row->sub_unitkerja }} — {{ $row->kode_unitkerja }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>

    @elseif (auth()->check() && auth()->user()->is_admin == 2)
        <!-- Admin biasa: Tidak bisa memilih, hanya tampilkan sebagai readonly -->
        @php
            $unit = $unitKerjaGrouped->flatten()->firstWhere('kode_unitkerja', $pegawai->kode_unitkerja);
        @endphp
        <input type="text" class="form-control" value="{{ $unit->sub_unitkerja ?? 'Tidak diketahui' }} — {{ $pegawai->kode_unitkerja }}" readonly>
        <input type="hidden" name="kode_unitkerja" value="{{ $pegawai->kode_unitkerja }}">
    @endif
</div>






        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $pegawai->email) }}">
        </div>

        <div class="mb-3">
            <label for="no_hp" class="form-label">No HP</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}">
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat">{{ old('alamat', $pegawai->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="tmt" class="form-label">TMT</label>
            <input type="date" class="form-control" id="tmt" name="tmt" value="{{ old('tmt', $pegawai->tmt) }}">
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto Profil</label><br>
            @if ($pegawai->foto)
                <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="Foto Pegawai" class="img-thumbnail mb-2" width="150">
            @else
                <img src="{{ asset('images/default-profile.png') }}" alt="Default Foto" class="img-thumbnail mb-2" width="150">
            @endif
            <input type="file" class="form-control" id="foto" name="foto">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>

    <a href="{{ route('Admin.Pegawai.index') }}" class="btn btn-secondary">Kembali</a>
</div>

@push('scripts')
  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
          title: 'Harap mengisi semua form',
          html: `{!! implode('<br>', $errors->all()) !!}`,
          icon: 'error',
          confirmButtonText: 'Tutup'
        });
      });
    </script>
  @endif
@endpush

@endsection
