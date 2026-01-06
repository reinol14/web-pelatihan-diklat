@extends('layouts.pegawai')

@section('title','Dashboard Pegawai')

@push('styles')
<style>
  .stat-card{border:none;border-radius:14px;box-shadow:0 10px 26px rgba(24,16,63,.08)}
  .stat-icon{width:42px;height:42px;border-radius:10px;display:grid;place-items:center}
</style>
@endpush

@section('content')
<div class="container py-4">

  {{-- Pemetaan status (Indonesia) --}}
  @php
    $statusMap = [
      'menunggu'          => ['label' => 'Menunggu Verifikasi', 'class' => 'secondary'],
      'diterima'          => ['label' => 'Diterima',             'class' => 'primary'],
      'berjalan'          => ['label' => 'Berjalan',             'class' => 'success'],
      'menunggu_laporan'  => ['label' => 'Menunggu Laporan',     'class' => 'warning'],
      'dibatalkan'        => ['label' => 'Dibatalkan',           'class' => 'secondary'],
      'ditolak'           => ['label' => 'Ditolak',              'class' => 'danger'],
      'lulus'             => ['label' => 'Lulus',                'class' => 'success'],
      'tidak_lulus'       => ['label' => 'Tidak Lulus',          'class' => 'danger'],
    ];
  @endphp

  {{-- Header --}}
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
    <div>
      <h1 class="h4 mb-1">Halo, {{ $pegawai->nama ?? 'Pegawai' }}</h1>
      <div class="text-muted small">
        NIP: {{ $pegawai->nip ?? '-' }}
        • Unit: {{ optional($pegawai->unitKerja)->unitkerja ?? '-' }}
      </div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('Pegawai.profil.edit') }}" class="btn btn-outline-primary">
        <i class="bi bi-pencil-square me-1"></i> Edit Data
      </a>
    </div>
  </div>

  {{-- Ringkasan / Stats --}}
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card stat-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small">Pelatihan Diikuti (Aktif/Proses)</div>
            <div class="fs-4 fw-semibold">{{ $stats['aktif_count'] ?? 0 }}</div>
          </div>
          <div class="stat-icon bg-primary-subtle text-primary">
            <i class="bi bi-activity"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small">Total Pelatihan Selesai</div>
            <div class="fs-4 fw-semibold">{{ $stats['completed_count'] ?? 0 }}</div>
          </div>
          <div class="stat-icon bg-success-subtle text-success">
            <i class="bi bi-check2-circle"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small">Sertifikat</div>
            <div class="fs-4 fw-semibold">{{ $stats['sertifikat_count'] ?? 0 }}</div>
          </div>
          <div class="stat-icon bg-warning-subtle text-warning">
            <i class="bi bi-award"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Status Pelatihan (menunggu/diterima/berjalan/menunggu_laporan) --}}
  <div class="card mb-4">
    <div class="card-header bg-white d-flex align-items-center justify-content-between">
      <strong>Status Pelatihan</strong>
      <a href="{{ route('Pegawai.Laporan.index') }}" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-list-task me-1"></i> Lihat Semua
      </a>
    </div>
    <div class="card-body">
      @if(($ongoing ?? collect())->isEmpty())
        <div class="text-muted">Belum ada pengajuan atau pelatihan yang sedang diikuti.</div>
      @else
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-light">
              <tr>
                <th>Pelatihan</th>
                <th>Jadwal</th>
                <th>Metode</th>
                <th>Lokasi</th>
                <th class="text-center">Status</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($ongoing as $row)
                @php
                  $sesi    = $row->pelatihan;
                  $sesiId  = $sesi?->id;
                  $mulai   = $sesi?->tanggal_mulai ? \Illuminate\Support\Carbon::parse($sesi->tanggal_mulai)->isoFormat('D MMM Y') : '-';
                  $selesai = $sesi?->tanggal_selesai ? \Illuminate\Support\Carbon::parse($sesi->tanggal_selesai)->isoFormat('D MMM Y') : '-';

                  $st      = $row->status;
                  $map     = $statusMap[$st] ?? ['label' => $st, 'class' => 'light'];

                  $sudahSelesai = $sesi?->tanggal_selesai
                    ? now()->toDateString() > \Illuminate\Support\Carbon::parse($sesi->tanggal_selesai)->toDateString()
                    : false;

                  $canCancel = in_array($st, ['menunggu','diterima']);
                @endphp
                <tr>
                  <td>{{ $sesi?->nama_pelatihan ?? '-' }}</td>
                  <td>{{ $mulai }} – {{ $selesai }}</td>
                  <td>{{ $sesi?->metode_pelatihan ?? '-' }}</td>
                  <td>{{ $sesi?->lokasi ?? '-' }}</td>
                  <td class="text-center">
                    <span class="badge text-bg-{{ $map['class'] }}">{{ $map['label'] }}</span>
                  </td>
                  <td class="text-end">
                    {{-- Detail --}}
                    <a href="{{ $sesiId ? route('Pelatihan.show', ['id'=>$sesiId]) : '#' }}"
                       class="btn btn-sm btn-outline-primary {{ $sesiId ? '' : 'disabled' }}"
                       title="Detail">
                      <i class="bi bi-eye"></i>
                    </a>

                    {{-- Ajukan laporan saat menunggu_laporan (atau kalau 'berjalan' tapi sudah lewat tanggal selesai) --}}
                    @if($sesiId && ($st === 'menunggu_laporan' || ($st === 'berjalan' && $sudahSelesai)))
                      <a href="{{ route('Pegawai.Laporan.create', ['id' => $sesiId]) }}"
                         class="btn btn-sm btn-outline-success" title="Ajukan Laporan">
                        <i class="bi bi-journal-text"></i>
                      </a>
                    @endif

                    {{-- Batalkan pengajuan hanya untuk menunggu/diterima --}}
                    @if($sesiId && $canCancel)
                      <form action="{{ route('pelatihan.leave', ['id'=>$sesiId]) }}"
                            method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger" title="Batalkan"
                                onclick="return confirm('Batalkan pengajuan/pendaftaran?')">
                          <i class="bi bi-x-circle"></i>
                        </button>
                      </form>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  {{-- Riwayat Pelatihan --}}
  <div class="card mb-4">
    <div class="card-header bg-white">
      <strong>Riwayat Pelatihan</strong>
    </div>
    <div class="card-body">
      @if(($history ?? collect())->isEmpty())
        <div class="text-muted">Belum ada riwayat pelatihan.</div>
      @else
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Pelatihan</th>
                <th>Periode</th>
                <th>Sertifikat</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($history as $row)
                @php
                  $sesi    = $row->pelatihan;
                  $mulai   = $sesi?->tanggal_mulai ? \Illuminate\Support\Carbon::parse($sesi->tanggal_mulai)->isoFormat('D MMM Y') : '-';
                  $selesai = $sesi?->tanggal_selesai ? \Illuminate\Support\Carbon::parse($sesi->tanggal_selesai)->isoFormat('D MMM Y') : '-';
                  $st      = $row->status;
                  $map     = $statusMap[$st] ?? ['label' => $st, 'class' => 'light'];
                  
                  $fileUrl = $row->file_path ? ( \Illuminate\Support\Str::startsWith($row->file_path,'storage/') ? asset($row->file_path) : asset('storage/'.ltrim($row->file_path,'/')) ) : null;
                  $sertifikat = $row->sertifikat ? ( \Illuminate\Support\Str::startsWith($row->sertifikat,'storage/') ? asset($row->sertifikat) : asset('storage/'.ltrim($row->sertifikat,'/')) ) : null;
        
                @endphp
                <tr>
                  <td>{{ $sesi?->nama_pelatihan ?? '-' }}</td>
                  <td>{{ $mulai }} – {{ $selesai }}</td>
                  
                  <td>
                      @if($sertifikat)
                        <a href="{{ $sertifikat }}" target="_blank" class="btn btn-sm btn-outline-success">
                          <i class="bi bi-file-earmark-text me-1"></i> Sertifikat
                        </a>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                  </td>
                  <td>
                    <span class="badge text-bg-{{ $map['class'] }}">{{ $map['label'] }}</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

</div>
@endsection
