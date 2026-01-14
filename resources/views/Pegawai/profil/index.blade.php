@extends('layouts.pegawai')

@section('title','Dashboard Pegawai')

@push('styles')
<style>
  .profile-card{border:none;border-radius:16px;overflow:hidden;box-shadow:0 12px 28px rgba(24,16,63,.10)}
  .profile-banner{background:linear-gradient(135deg,#6f42c1,#0d6efd);min-height:88px}
  .profile-body{margin-top:-46px}
  .avatar-wrap{width:92px;height:92px;border-radius:50%;border:4px solid #fff;overflow:hidden;background:#f3f4f6;box-shadow:0 8px 20px rgba(16,24,40,.18)}
  .avatar-fallback{width:100%;height:100%;display:grid;place-items:center;font-weight:700;font-size:34px;color:#475467;background:#EEF2FF}
  .object-fit-cover{object-fit:cover}
</style>
@endpush

@section('content')

@php
  $pegawai = $pegawai ?? auth('pegawais')->user();
  $atasan  = $atasan ?? null;
  $latestChange = $latestChange ?? null;

  $nama  = $pegawai?->nama ?? 'Pegawai';
  $nip   = $pegawai?->nip ?? '-';

  $unitName    = $pegawai?->unitKerja?->unitkerja ?? '-';
  $subUnitName = $pegawai?->unitKerja?->sub_unitkerja ?? '-';

  $tglLahir = $pegawai?->tanggal_lahir
    ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->isoFormat('D MMM Y')
    : '-';

  $foto    = $pegawai?->foto ?? null;
  $fotoUrl = $foto ? asset('storage/'.ltrim($foto,'/')) : null;
  $initial = strtoupper(mb_substr($nama,0,1));

  $atasanFoto = $atasan?->foto
    ? asset('storage/'.ltrim($atasan->foto,'/'))
    : asset('images/avatar-default.png');
@endphp

{{-- ================= PROFIL ================= --}}
<div class="card profile-card mb-4">
  <div class="profile-banner"></div>
  <div class="card-body profile-body">
    <div class="d-flex align-items-center gap-3">
      <div class="avatar-wrap">
        @if($fotoUrl)
          <img src="{{ $fotoUrl }}" class="w-100 h-100 object-fit-cover">
        @else
          <div class="avatar-fallback">{{ $initial }}</div>
        @endif
      </div>
      <div>
        <h4 class="mb-1">{{ $nama }}</h4>
        <div class="text-muted small">
          NIP: {{ $nip }} • {{ $unitName }}{{ $subUnitName !== '-' ? ' — '.$subUnitName : '' }}
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ================= STATUS AJUAN ================= --}}
@if($latestChange)
<div class="card mb-4 shadow-sm border-0">
  <div class="card-body d-flex flex-column flex-md-row align-items-start justify-content-between gap-3">

    {{-- STATUS INFO --}}
    <div class="d-flex align-items-start gap-3">
      {{-- ICON STATUS --}}
      <div class="rounded-circle d-flex align-items-center justify-content-center"
        style="width:56px;height:56px;
        @if($latestChange->status==='pending') background:#fff3cd;color:#856404;
        @elseif($latestChange->status==='rejected') background:#f8d7da;color:#842029;
        @else background:#d1e7dd;color:#0f5132; @endif">
        
        @if($latestChange->status==='pending')
          <i class="bi bi-hourglass-split fs-4"></i>
        @elseif($latestChange->status==='rejected')
          <i class="bi bi-x-circle fs-4"></i>
        @else
          <i class="bi bi-check-circle fs-4"></i>
        @endif
      </div>

      {{-- TEKS --}}
      <div>
        @if($latestChange->status === 'pending')
          <div class="fw-semibold fs-6">Ajuan Perubahan Profil</div>
          <div class="text-warning fw-semibold">Menunggu Verifikasi Admin</div>
        @elseif($latestChange->status === 'rejected')
          <div class="fw-semibold fs-6">Ajuan Perubahan Profil</div>
          <div class="text-danger fw-semibold">Ajuan Ditolak</div>
        @else
          <div class="fw-semibold fs-6">Ajuan Perubahan Profil</div>
          <div class="text-success fw-semibold">Ajuan Disetujui</div>
        @endif

        <div class="text-muted small mt-1">
          Diajukan pada {{ $latestChange->created_at->format('d M Y • H:i') }}
        </div>

        {{-- CATATAN ADMIN --}}
        @if($latestChange->status === 'rejected' && $latestChange->review_note)
          <div class="alert alert-danger mt-2 mb-0 p-2 small">
            <strong>Catatan Admin:</strong><br>
            {{ $latestChange->review_note }}
          </div>
        @endif
      </div>
    </div>

    {{-- ACTION --}}
    <div class="d-flex flex-column gap-2 text-end">
      <a href="{{ route('Pegawai.profil.status') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye me-1"></i> Detail Status
      </a>

      @if($latestChange->status === 'pending')
        <span class="text-muted small">
          <i class="bi bi-lock-fill me-1"></i> Edit profil sementara dikunci
        </span>
      @endif
    </div>

  </div>
</div>
@endif


{{-- ================= DATA ASN ================= --}}
<div class="card mb-4">
  <div class="card-header bg-white"><strong>Data ASN</strong></div>
  <div class="card-body row g-4">
    <div class="col-md-6">
      <table class="table table-sm">
        <tr><th>Nama</th><td>{{ $nama }}</td></tr>
        <tr><th>NIP</th><td>{{ $nip }}</td></tr>
        <tr><th>Tgl Lahir</th><td>{{ $tglLahir }}</td></tr>
        <tr><th>Email</th><td>{{ $pegawai?->email ?? '-' }}</td></tr>
        <tr><th>No HP</th><td>{{ $pegawai?->no_hp ?? '-' }}</td></tr>
      </table>
    </div>
    <div class="col-md-6">
      <table class="table table-sm">
        <tr><th>Unit Kerja</th><td>{{ $unitName }}</td></tr>
        <tr><th>Sub Unit</th><td>{{ $subUnitName }}</td></tr>
        <tr><th>Jabatan</th><td>{{ $pegawai?->jabatan ?? '-' }}</td></tr>
        <tr><th>Pangkat/Gol</th><td>{{ ($pegawai?->pangkat ?? '-') . ' / ' . ($pegawai?->golongan ?? '-') }}</td></tr>
        <tr><th>Alamat</th><td>{{ $pegawai?->alamat ?? '-' }}</td></tr>
      </table>
    </div>
  </div>
</div>

{{-- ================= ATASAN ================= --}}
<div class="card mb-4">
  <div class="card-header bg-white"><strong>Atasan Langsung</strong></div>
  <div class="card-body">
    @if($atasan)
      <div class="d-flex align-items-center gap-3">
        <img src="{{ $atasanFoto }}" class="rounded-circle" width="56" height="56">
        <div>
          <div class="fw-semibold">{{ $atasan->nama }}</div>
          <div class="text-muted small">{{ $atasan->jabatan }}</div>
          <div class="text-muted small">NIP: {{ $atasan->nip }}</div>
        </div>
      </div>
    @else
      <span class="text-muted">Belum ada data atasan.</span>
    @endif
  </div>
</div>

{{-- ================= ACTION ================= --}}
<div class="d-flex gap-2">
  <a href="{{ route('Pegawai.dashboard') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
  </a>

  @if($latestChange && $latestChange->status === 'pending')
    <button class="btn btn-secondary" disabled>
      <i class="bi bi-lock me-1"></i> Edit Profil
    </button>
  @else
    <a href="{{ route('Pegawai.profil.edit') }}" class="btn btn-primary">
      <i class="bi bi-pencil-square me-1"></i> Edit Profil
    </a>
  @endif
</div>

@endsection
