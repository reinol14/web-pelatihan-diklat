{{-- resources/views/pegawai/laporan/index.blade.php --}}
@extends('layouts.pegawai')

@section('title', 'Laporan Pelatihan — Daftar')

@push('styles')
<style>
  :root { 
    --line: #e9ecef;
    --muted: #6c757d; 
    --primary: #0d6efd;
    --success: #198754; 
    --warn: #fd7e14; 
    --danger: #dc3545;
  }
  .card-modern { 
    border: 1px solid var(--line); 
    border-radius: 15px; 
    background-color: #fff; 
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); 
  }
  .badge-modern { 
    font-weight: 600; 
    border-radius: 12px; 
    padding: 5px 10px; 
  }
  .badge-belum       { background-color: #fef3c7; color: #8a6d3b; }   /* Belum diajukan */
  .badge-diajukan    { background-color: #e7f1ff; color: #0d6efd; }   /* Diajukan */
  .badge-disetujui   { background-color: #e8f5e9; color: #198754; }   /* Disetujui */
  .badge-ditolak     { background-color: #fcf0f0; color: #dc3545; }   /* Ditolak */
  .table thead th { white-space: nowrap; font-weight: 600; }
</style>
@endpush

@section('content')
<section class="py-4">
  <div class="container">
    <h1 class="h4 mb-4 text-primary text-center">Laporan Pelatihan Saya</h1>

    {{-- Flash Messages --}}
    @foreach (['success', 'error', 'info', 'warning'] as $f)
      @if(session($f))
        <div class="alert alert-{{ $f === 'error' ? 'danger' : $f }}">{{ session($f) }}</div>
      @endif
    @endforeach

    @php
      $today = $today ?? \Illuminate\Support\Carbon::today()->toDateString();
    @endphp

    <div class="card-modern p-4">
      @if(count($items) > 0)
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 44%">Pelatihan</th>
              <th>Periode</th>
              <th>Status Laporan</th>
              <th style="text-align: end; width: 220px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $it)
              @php
                $mulai = $it->tanggal_mulai
                  ? \Illuminate\Support\Carbon::parse($it->tanggal_mulai)->isoFormat('D MMM Y') : '-';
                $selesai = $it->tanggal_selesai
                  ? \Illuminate\Support\Carbon::parse($it->tanggal_selesai)->isoFormat('D MMM Y') : '-';

                $selesaiStr = $it->tanggal_selesai
                  ? \Illuminate\Support\Carbon::parse($it->tanggal_selesai)->toDateString() : null;

                $wajibKirim = $selesaiStr && $selesaiStr <= $today;
                $status = $it->status_laporan ?? 'belum';

                $badgeClass = match($status) {
                  'pending' => 'badge-diajukan',
                  'approved' => 'badge-disetujui',
                  'rejected' => 'badge-ditolak',
                  default => 'badge-belum',
                };

                $label = match($status) {
                  'pending' => 'Diajukan',
                  'approved' => 'Disetujui',
                  'rejected' => 'Ditolak',
                  default => 'Belum Diajukan',
                };

                $laporanId = $it->laporan_id ?? null;
              @endphp

              <tr>
                <td>
                  <div class="fw-bold text-dark">{{ $it->nama_pelatihan }}</div>
                  <div class="text-muted small">Jenis: {{ $it->jenis_pelatihan ?? '-' }}</div>
                </td>
                <td>
                  <div>{{ $mulai }} – {{ $selesai }}</div>
                </td>
                <td>
                  <span class="badge badge-modern {{ $badgeClass }}">{{ $label }}</span>
                  @if($wajibKirim && $status === 'belum')
                    <div class="small text-danger mt-1">* Wajib kirim laporan</div>
                  @endif
                </td>
                <td class="text-end">
                  @if($status === 'belum' && $wajibKirim)
                    <a href="{{ route('Pegawai.Laporan.create', ['id' => $it->id]) }}" class="btn btn-primary btn-sm">
                      <i class="bi bi-upload me-1"></i> Kirim Laporan
                    </a>
                  @elseif($status === 'pending')
                    <button class="btn btn-info btn-sm" disabled>Menunggu Verifikasi</button>
                  @elseif($status === 'approved')
                    <button class="btn btn-success btn-sm" disabled>Disetujui</button>
                  @elseif($status === 'rejected' && $laporanId)
                    <a href="{{ route('Pegawai.Laporan.edit', ['laporan' => $laporanId]) }}" class="btn btn-warning btn-sm">
                      <i class="bi bi-pencil-square me-1"></i> Perbaiki & Kirim Ulang
                    </a>
                  @else
                    <button class="btn btn-secondary btn-sm" disabled>{{ $wajibKirim ? '-' : 'Belum Jatuh Tempo' }}</button>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
        <div class="text-center text-muted py-4">
          <i class="bi bi-journal-xmark fs-1 mb-2"></i>
          <p>Belum ada pelatihan yang dapat dilaporkan.</p>
        </div>
      @endif

      {{-- Pagination --}}
      @if($items instanceof \Illuminate\Contracts\Pagination\Paginator || $items instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <div class="mt-3 d-flex justify-content-center">
          {{ $items->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection