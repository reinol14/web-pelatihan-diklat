@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h1 class="h4 mb-3">Detail Registrasi Pegawai</h1>

  {{-- Flash message --}}
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

  <div class="card shadow-sm">
    <div class="card-body">

      @php
        $fmt = function($v, $withTime = false) {
          if (empty($v)) return null;
          try { return \Carbon\Carbon::parse($v)->translatedFormat($withTime ? 'd M Y H:i' : 'd M Y'); }
          catch (\Exception $e) { return $v; }
        };
        $status = $registration->status ?? 'pending';
        $statusColor = match($status) {
          'approved' => 'success',
          'rejected' => 'danger',
          default    => 'warning',
        };
      @endphp

      {{-- Header ringkas --}}
      <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
          <div class="fw-semibold">{{ $registration->nama ?? '-' }}</div>
          <div class="text-muted small">NIP: {{ $registration->nip ?? '-' }}</div>
        </div>
        <span class="badge bg-{{ $statusColor }}">
          {{ ucfirst($status) }}
        </span>
      </div>

      {{-- Detail --}}
      <dl class="row mb-0">
        @foreach([
          'Nama' => $registration->nama ?? null,
          'NIP' => $registration->nip ?? null,
          'Email' => $registration->email ?? null,
          'Tempat Lahir' => $registration->tempat_lahir ?? null,
          'Tanggal Lahir' => $fmt($registration->tanggal_lahir),
          'Pangkat' => $registration->pangkat ?? null,
          'Golongan' => $registration->golongan ?? null,
          'Jabatan' => $registration->jabatan ?? null,
          'Jenis ASN' => $registration->jenis_asn ?? null,
          'Kategori Jabatan ASN' => $registration->kategori_jabatanasn ?? null,
          'Unit Kerja' => $registration->unitkerja ?? null,
          'ID Unit Kerja' => $registration->kode_unitkerja ?? null,
          'No. HP' => $registration->no_hp ?? null,
          'Alamat' => $registration->alamat ?? null,
          'TMT' => $fmt($registration->tmt),
          'ID Atasan' => $registration->id_atasan ?? null,
          'Dibuat' => $fmt($registration->created_at, true),
          'Diperbarui' => $fmt($registration->updated_at, true),
        ] as $label => $value)
          @continue(is_null($value) || $value === '')
          <dt class="col-sm-4">{{ $label }}</dt>
          <dd class="col-sm-8">{{ $value }}</dd>
        @endforeach

        {{-- Info APPROVED --}}
        @if($status === 'approved')
          <dt class="col-sm-4">Disetujui Pada</dt>
          <dd class="col-sm-8">{{ $fmt($registration->approved_at, true) ?? '-' }}</dd>

          <dt class="col-sm-4">Disetujui Oleh (ID)</dt>
          <dd class="col-sm-8">{{ $registration->approved_by ?? '-' }}</dd>

          @if(!empty($registration->approved_note))
            <dt class="col-sm-4">Catatan Persetujuan</dt>
            <dd class="col-sm-8">
              <div class="p-2 border rounded bg-light">{{ $registration->approved_note }}</div>
            </dd>
          @endif
        @endif

        {{-- Info REJECT --}}
        @if($status === 'rejected' && !empty($registration->keterangan))
          <dt class="col-sm-4">Alasan Penolakan</dt>
          <dd class="col-sm-8">
            <div class="p-2 border rounded bg-light">{{ $registration->keterangan }}</div>
          </dd>
        @endif

        {{-- Foto (jika ada) --}}
        @if(!empty($registration->foto))
          <dt class="col-sm-4">Foto</dt>
          <dd class="col-sm-8">
            <img
              src="{{ \Illuminate\Support\Str::startsWith($registration->foto, ['http://','https://']) ? $registration->foto : asset('storage/'.ltrim($registration->foto,'/')) }}"
              alt="Foto Pegawai"
              class="img-thumbnail"
              style="max-width: 180px;">
          </dd>
        @endif
      </dl>
    </div>
  </div>

  <div class="mt-3">
    <a href="{{ route('Admin.Pegawai.PegawaiApproval.index') }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>

    {{-- Tombol aksi hanya saat pending --}}
    @if($status === 'pending')
      <div class="d-flex flex-wrap gap-2 mt-3">
        {{-- Approve --}}
        <form method="POST" action="{{ route('Admin.Pegawai.PegawaiApproval.approve',$registration->id) }}" class="d-flex gap-2">
          @csrf
          <input type="text" name="approved_note" class="form-control" placeholder="Catatan persetujuan (opsional)" style="max-width:320px">
          <button class="btn btn-success">
            <i class="bi bi-check-lg"></i> Setujui
          </button>
        </form>

        {{-- Reject --}}
        <form method="POST" action="{{ route('Admin.Pegawai.PegawaiApproval.reject',$registration->id) }}" class="d-flex gap-2">
          @csrf
          <input type="text" name="keterangan" class="form-control" placeholder="Alasan penolakan (opsional)" style="max-width:320px">
          <button class="btn btn-outline-danger">
            <i class="bi bi-x-lg"></i> Tolak
          </button>
        </form>
      </div>
    @endif
  </div>
</div>
@endsection
