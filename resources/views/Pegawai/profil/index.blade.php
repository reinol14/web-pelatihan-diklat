@extends('layouts.pegawai')

@section('title','Dashboard Pegawai')

@push('styles')
<style>
  .profile-card{border:none;border-radius:16px;overflow:hidden;box-shadow:0 12px 28px rgba(24,16,63,.10)}
  .profile-banner{background:linear-gradient(135deg,#6f42c1,#0d6efd);min-height:88px}
  .profile-body{margin-top:-46px}
  .avatar-wrap{width:92px;height:92px;border-radius:50%;border:4px solid #fff;overflow:hidden;background:#f3f4f6;box-shadow:0 8px 20px rgba(16,24,40,.18)}
  .avatar-fallback{width:100%;height:100%;display:grid;place-items:center;font-weight:700;font-size:34px;color:#475467;background:#EEF2FF}
  .stat-card{border:none;border-radius:14px;box-shadow:0 10px 26px rgba(24,16,63,.08)}
  .stat-icon{width:42px;height:42px;border-radius:10px;display:grid;place-items:center}
  .table>:not(caption)>*>*{vertical-align:middle}
  .object-fit-cover{object-fit:cover}
</style>
@endpush

@section('content')

@php
  // Pastikan variabel ada supaya tidak "Undefined variable"
  $pegawai = $pegawai ?? auth('pegawais')->user();
  $atasan  = $atasan  ?? null;

  // Nilai aman
  $nama     = $pegawai?->nama ?? 'Pegawai';
  $nip      = $pegawai?->nip ?? '-';

  $unitName    = $pegawai?->unitKerja?->unitkerja ?? '-';
  $subUnitName = $pegawai?->unitKerja?->sub_unitkerja ?? '-';

  // Tanggal lahir (pakai Carbon namespace global)
  $tglLahir = $pegawai?->tanggal_lahir
    ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->isoFormat('D MMM Y')
    : '-';

  // Foto
  $foto     = $pegawai?->foto ?? null;
  $fotoUrl  = $foto ? asset('storage/'.ltrim($foto,'/')) : null;
  $initial  = strtoupper(mb_substr($nama,0,1));

  // Data atasan (aman walau $atasan null)
  $atasanFoto     = $atasan?->foto ? asset('storage/'.ltrim($atasan->foto,'/')) : asset('images/avatar-default.png');
  $atasanUnitName = $atasan?->unitKerja?->unitkerja ?? '-';
  $atasanSubUnit  = $atasan?->unitKerja?->sub_unitkerja ?? '-';

  // Stats fallback supaya tidak error saat tidak dipassing
  $stats = $stats ?? ['aktif_count'=>0,'completed_count'=>0,'sertifikat_count'=>0];
@endphp

{{-- Kartu Profil --}}
<div class="card profile-card mb-4">
  <div class="profile-banner"></div>
  <div class="card-body profile-body">
    <div class="d-flex align-items-center gap-3">
      <div class="avatar-wrap">
        @if($fotoUrl)
          <img src="{{ $fotoUrl }}" alt="Foto {{ $nama }}" class="w-100 h-100 object-fit-cover">
        @else
          <div class="avatar-fallback" aria-hidden="true">{{ $initial }}</div>
        @endif
      </div>
      <div class="flex-grow-1">
        <h2 class="h4 mb-1">{{ $nama }}</h2>
        <div class="text-muted small">
          NIP: {{ $nip }} • Unit: {{ $unitName }}{{ $subUnitName !== '-' ? ' — '.$subUnitName : '' }}
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Data ASN --}}
<div class="card mb-4">
  <div class="card-header bg-white">
    <strong>Data ASN</strong>
  </div>
  <div class="card-body">
    <div class="row g-4">
      <div class="col-md-6">
        <table class="table table-sm mb-0">
          <tr><th class="w-35 text-muted">Nama</th><td>{{ $nama }}</td></tr>
          <tr><th class="text-muted">NIP</th><td>{{ $nip }}</td></tr>
          <tr><th class="text-muted">Tanggal Lahir</th><td>{{ $tglLahir }}</td></tr>
          <tr><th class="text-muted">Email</th><td>{{ $pegawai?->email ?? '-' }}</td></tr>
          <tr><th class="text-muted">No. HP</th><td>{{ $pegawai?->no_hp ?? '-' }}</td></tr>
        </table>
      </div>
      <div class="col-md-6">
        @php
          $pang = trim($pegawai?->pangkat ?? '');
          $gol  = trim($pegawai?->golongan ?? '');
          $pangGol = ($pang === '' && $gol === '') ? '-' : (($pang ?: '-') . ' / ' . ($gol ?: '-'));
        @endphp
        <table class="table table-sm mb-0">
          <tr><th class="w-35 text-muted">Unit Kerja</th><td>{{ $unitName }}</td></tr>
          <tr><th class="text-muted">Sub Unit Kerja</th><td>{{ $subUnitName }}</td></tr>
          <tr><th class="text-muted">Jabatan</th><td>{{ $pegawai?->jabatan ?? '-' }}</td></tr>
          <tr><th class="text-muted">Pangkat/Golongan</th><td>{{ $pangGol }}</td></tr>
          <tr><th class="text-muted">Alamat</th><td>{{ $pegawai?->alamat ?? '-' }}</td></tr>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Data Atasan --}}
<div class="card mb-4">
  <div class="card-header bg-white"><strong>Atasan Langsung</strong></div>
  <div class="card-body">
    @if($atasan)
      <div class="d-flex align-items-center gap-3">
        <img src="{{ $atasanFoto }}" class="rounded-circle" alt="Foto Atasan" width="56" height="56">
        <div>
          <div class="fw-semibold">{{ $atasan?->nama ?? '-' }}</div>
          <div class="text-muted small">{{ $atasan?->jabatan ?? '-' }}</div>
          <div class="text-muted small">NIP: {{ $atasan?->nip ?? '-' }}</div>
          <div class="text-muted small">
            Unit: {{ $atasanUnitName }}{{ $atasanSubUnit !== '-' ? ' — '.$atasanSubUnit : '' }}
          </div>
        </div>
      </div>
    @else
      <span class="text-muted">Belum terdata / tidak ditemukan.</span>
    @endif
  </div>
</div>

<div class="d-flex gap-2">
  <a href="{{ route('Pegawai.dashboard') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
  </a>
  <a href="{{ route('Pegawai.profil.edit') }}" class="btn btn-primary">
    <i class="bi bi-pencil-square me-1"></i> Edit Profil
  </a>
</div>

@endsection
