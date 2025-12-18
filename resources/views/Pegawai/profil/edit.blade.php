@extends('layouts.pegawai')

@section('content')
<div class="container p-5 my-5" style="max-width: 900px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);">
  <h2 class="mb-4 text-center text-primary">Edit Profil Pegawai</h2>
  <p class="text-muted text-center">
    Perubahan tidak langsung aktif. Pengajuan akan ditinjau oleh admin terlebih dahulu.
  </p>
  <hr class="mb-4">

  @if(session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  @php
    $foto = $foto ?? 'default-foto.png'; // Foto default untuk fallback
  @endphp

  <form method="POST" action="{{ route('Pegawai.profil.store') }}" enctype="multipart/form-data" class="row g-4">
    @csrf

    {{-- FOTO --}}
    <div class="col-12 text-center mb-4">
      <h5 class="text-secondary fw-bold">Foto Profil</h5>
      <hr>
      <img id="fotoInitialPreview" 
           src="{{ asset('storage/foto/' . $foto) }}" 
           alt="Foto Profil Pegawai" 
           onerror="this.onerror=null;this.src='{{ asset('storage/foto/default-foto.png') }}';"
           style="max-height: 200px; max-width: 200px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
      <div class="mt-3">
        <label for="fotoInput" class="btn btn-outline-primary btn-sm">Ganti Foto</label>
        <input type="file" name="foto" id="fotoInput" class="form-control d-none" accept="image/*">
      </div>
      <div class="mt-3">
        <small class="text-muted">Foto harus dalam format gambar (JPG, PNG).</small>
      </div>
    </div>

    {{-- Data Pribadi --}}
    <div class="col-12">
      <h5 class="text-secondary fw-bold">Data Pribadi</h5>
      <hr>
    </div>

    <div class="col-md-4">
      <label class="form-label fw-semibold">NIP</label>
      <input type="text" name="nip" class="form-control" value="{{ old('nip', $pegawai->nip) }}" readonly>
    </div>

    <div class="col-md-8">
      <label class="form-label fw-semibold">Nama</label>
      <input type="text" name="nama" class="form-control" value="{{ old('nama', $pegawai->nama) }}">
      @error('nama')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
      <label class="form-label fw-semibold">Tempat Lahir</label>
      <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}">
    </div>

    <div class="col-md-6">
      <label class="form-label fw-semibold">Tanggal Lahir</label>
      <input 
        type="date" 
        name="tanggal_lahir" 
        class="form-control" 
        value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir ? date('Y-m-d', strtotime($pegawai->tanggal_lahir)) : '') }}">
    </div>

    {{-- Data Kepegawaian --}}
    <div class="col-12 mt-4">
      <h5 class="text-secondary fw-bold">Data Kepegawaian</h5>
      <hr>
    </div>

    <div class="col-md-6">
      <label class="form-label fw-semibold">Pangkat</label>
      <select name="pangkat" class="form-select">
        <option value="">-- Pilih Pangkat --</option>
        @foreach($pangkatList as $pangkat)
          <option value="{{ $pangkat }}" @selected(old('pangkat', $pegawai->pangkat) == $pangkat)>{{ $pangkat }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label fw-semibold">Golongan</label>
      <select name="golongan" class="form-select">
        <option value="">-- Pilih Golongan --</option>
        @foreach($golonganList as $golongan)
          <option value="{{ $golongan }}" @selected(old('golongan', $pegawai->golongan) == $golongan)>{{ $golongan }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label fw-semibold">Jabatan</label>
      <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $pegawai->jabatan) }}">
    </div>

    <div class="col-md-6">
      <label class="form-label fw-semibold">Unit Kerja</label>
      <select name="kode_unitkerja" class="form-select">
        <option value="">-- Pilih Unit Kerja --</option>
        @foreach($unitKerjas as $unitKerja)
          <option value="{{ $unitKerja->kode_unitkerja }}" @selected(old('kode_unitkerja', $pegawai->kode_unitkerja) == $unitKerja->kode_unitkerja)>
            {{ $unitKerja->unitkerja }}{{ $unitKerja->sub_unitkerja ? ' — '.$unitKerja->sub_unitkerja : '' }}
          </option>
        @endforeach
      </select>
      @error('kode_unitkerja')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    {{-- Info Atasan --}}
    <div class="col-12 mt-4">
      <h5 class="text-secondary fw-bold">Info Atasan Langsung</h5>
      <hr>
    </div>

    <div class="col-md-6">
      <label class="form-label fw-semibold">Atasan Langsung</label>
      <select name="id_atasan" id="id_atasan" class="form-select">
        <option value="">-- Pilih Atasan Langsung --</option>
        @foreach($atasanCandidates as $atasan)
          <option value="{{ $atasan->id }}" @selected(old('id_atasan', $pegawai->id_atasan) == $atasan->id)>
            {{ $atasan->nama }} — {{ $atasan->jabatan ?? '-' }} ({{ $atasan->nip ?? '-' }})
          </option>
        @endforeach
      </select>
      <small class="text-muted">
        Menampilkan pegawai dari unit kerja: <strong>{{ $pegawai->kode_unitkerja }}</strong>.
      </small>
      @error('id_atasan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    {{-- Button Submit --}}
    <div class="col-12 d-flex justify-content-end gap-3 mt-4">
      <button type="submit" class="btn btn-lg btn-success px-4">Simpan Perubahan</button>
      <a href="{{ url()->previous() }}" class="btn btn-lg btn-outline-secondary px-4">Batal</a>
    </div>
  </form>
</div>

{{-- JavaScript for Foto Preview --}}
<script>
  document.getElementById('fotoInput').addEventListener('change', function(event) {
    const fotoPreview = document.getElementById('fotoInitialPreview');
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        fotoPreview.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
</script>
@endsection