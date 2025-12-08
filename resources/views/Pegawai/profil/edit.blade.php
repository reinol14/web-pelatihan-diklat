@extends('layouts.pegawai')

@section('content')
<div class="container" style="max-width: 900px;">
  <h2 class="mb-2">Edit Profil Pegawai</h2>
  <p class="text-muted mb-4">Perubahan tidak langsung aktif. Pengajuan akan ditinjau admin terlebih dahulu.</p>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif
  @if($errors->any())     <div class="alert alert-danger mb-3">{{ $errors->first() }}</div> @endif

  @php
    // Opsi pangkat & golongan ASN (ringkas, silakan lengkapi jika perlu)
    $pangkatOptions = [
      'Juru Muda','Juru Muda Tk. I','Juru','Juru Tk. I',
      'Pengatur Muda','Pengatur Muda Tk. I','Pengatur','Pengatur Tk. I',
      'Penata Muda','Penata Muda Tk. I','Penata','Penata Tk. I',
      'Pembina','Pembina Tk. I','Pembina Utama Muda','Pembina Utama Madya','Pembina Utama'
    ];
    $golonganOptions = ['(I/a)','(I/b)','(I/c)','(I/d)','(II/a)','(II/b)','(II/c)','(II/d)','(III/a)','(III/b)','(III/c)','(III/d)','(IV/a)','(IV/b)','(IV/c)','(IV/d)','(IV/e)'];
    $jenisAsnOptions = ['PNS','PPPK'];
    $kategoriJabOptions = ['Jabatan Pimpinan Tinggi','Jabatan Administrasi','Jabatan Fungsional'];
  @endphp

  <form method="POST" action="{{ route('pegawai.profil.store') }}" class="row g-3">
    @csrf

    {{-- DATA PRIBADI --}}
    <div class="col-12"><h5 class="mt-3">Data Pribadi</h5></div>

    <div class="col-md-4">
      <label class="form-label">NIP</label>
      <input type="text" name="nip" class="form-control" value="{{ old('nip', $pegawai->nip) }}" readonly>
    </div>

    <div class="col-md-8">
      <label class="form-label">Nama</label>
      <input type="text" name="nama" class="form-control" value="{{ old('nama', $pegawai->nama) }}">
      @error('nama')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
      <label class="form-label">Tempat Lahir</label>
      <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}">
    </div>

    <div class="col-md-6">
      <label class="form-label">Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir) }}">
    </div>

    {{-- DATA KEPEGAWAIAN --}}
    <div class="col-12"><h5 class="mt-4">Data Kepegawaian</h5></div>

    <div class="col-md-6">
      <label class="form-label">Pangkat</label>
      <select name="pangkat" class="form-select">
        <option value="">-- Pilih Pangkat --</option>
        @foreach($pangkatOptions as $opt)
          <option value="{{ $opt }}" @selected(old('pangkat', $pegawai->pangkat)==$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Golongan</label>
      <select name="golongan" class="form-select">
        <option value="">-- Pilih Golongan --</option>
        @foreach($golonganOptions as $opt)
          <option value="{{ $opt }}" @selected(old('golongan', $pegawai->golongan)==$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Jabatan</label>
      <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $pegawai->jabatan) }}">
    </div>

    {{-- UNIT KERJA: tampilkan "unitkerja — sub_unitkerja", kirim nilai = kode_unitkerja --}}
    <div class="col-md-6">
      <label class="form-label">Unit Kerja</label>
      <select name="kode_unitkerja" class="form-select">
        <option value="">-- Pilih Unit Kerja --</option>
        @foreach(($unitKerjas ?? []) as $u)
          @php
            // support dua skema kolom: kode_unitkerja / id_unitkerja (ambil value kode_unitkerja)
            $val = $u->kode_unitkerja ?? $u->id_unitkerja ?? null;
            $label = trim(($u->unitkerja ?? '').(isset($u->sub_unitkerja)&&$u->sub_unitkerja ? ' — '.$u->sub_unitkerja : ''));
          @endphp
          @if($val)
            <option value="{{ $val }}" @selected(old('kode_unitkerja', $pegawai->kode_unitkerja)==$val)>{{ $label }}</option>
          @endif
        @endforeach
      </select>
      @error('kode_unitkerja')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    {{-- KONTAK --}}
    <div class="col-12"><h5 class="mt-4">Kontak</h5></div>

    <div class="col-md-4">
      <label class="form-label">No HP</label>
      <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pegawai->no_hp) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="{{ old('email', $pegawai->email) }}">
    </div>

    <div class="col-12">
      <label class="form-label">Alamat</label>
      <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $pegawai->alamat) }}</textarea>
    </div>

    {{-- DATA TAMBAHAN --}}
    <div class="col-12"><h5 class="mt-4">Data Tambahan</h5></div>

    <div class="col-md-6">
      <label class="form-label">Jenis ASN</label>
      <select name="jenis_asn" class="form-select">
        <option value="">-- Pilih --</option>
        @foreach($jenisAsnOptions as $opt)
          <option value="{{ $opt }}" @selected(old('jenis_asn', $pegawai->jenis_asn)==$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Kategori Jabatan ASN</label>
      <select name="kategori_jabatanasn" class="form-select">
        <option value="">-- Pilih --</option>
        @foreach($kategoriJabOptions as $opt)
          <option value="{{ $opt }}" @selected(old('kategori_jabatanasn', $pegawai->kategori_jabatanasn)==$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    {{-- ATASAN LANGSUNG (Drop-down, satu kode_unitkerja) --}}
    <div class="col-md-6">
      <label class="form-label">Atasan Langsung</label>
      <select name="id_atasan" id="id_atasan" class="form-select">
        <option value="">-- Pilih Atasan (satu unit kerja) --</option>
        @foreach(($atasanCandidates ?? []) as $a)
          <option
            value="{{ $a->id }}"
            data-jabatan="{{ $a->jabatan ?? '' }}"
            data-nip="{{ $a->nip ?? '' }}"
            @selected( (string)old('id_atasan', $pegawai->id_atasan) === (string)$a->id )
          >
            {{ $a->nama }} — {{ $a->jabatan ?? '-' }} ({{ $a->nip ?? '-' }})
          </option>
        @endforeach
      </select>
      <small class="text-muted">
        Menampilkan pegawai dengan kode unit kerja: <strong>{{ $pegawai->kode_unitkerja ?? '-' }}</strong>.
      </small>
      @error('id_atasan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>



    {{-- CATATAN UNTUK ADMIN (opsional) --}}
    <div class="col-12">
      <label class="form-label">Catatan untuk Admin (opsional)</label>
      <textarea name="note" class="form-control" rows="2" placeholder="Contoh: Perubahan karena promosi per {{ now()->format('d/m/Y') }}.">{{ old('note') }}</textarea>
    </div>

    <div class="col-12 d-flex gap-2 mt-2">
      <button type="submit" class="btn btn-primary">Ajukan Perubahan</button>
      <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Batal</a>
    </div>
  </form>
</div>
@endsection
