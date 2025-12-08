{{-- resources/views/pegawai/laporan/index.blade.php --}}
@extends('layouts.pegawai')

@section('title', 'Laporan Pelatihan — Daftar')

@push('styles')
<style>
  :root{ --line:#e9ecef; --muted:#6c757d; }
  .cardish{ border:1px solid var(--line); border-radius:14px; background:#fff; }
  .status-badge{ font-weight:600; }
  /* Badge warna */
  .badge-belum       { background:#fff3cd; color:#8a6d3b; }   /* Belum diajukan */
  .badge-diajukan    { background:#e7f1ff; color:#0b5ed7; }   /* Diajukan (pending) */
  .badge-disetujui   { background:#e8f5e9; color:#0a7c2f; }   /* Disetujui (approved) */
  .badge-ditolak     { background:#ffebee; color:#b71c1c; }   /* Ditolak (rejected) */
  .table thead th{ white-space:nowrap; }
</style>
@endpush

@section('content')
<section class="py-4">
  <div class="container">
    <h1 class="h4 mb-3">Laporan Pelatihan Saya</h1>

    {{-- Flash --}}
    @foreach (['success','error','info','warning'] as $f)
      @if(session($f))
        <div class="alert alert-{{ $f === 'error' ? 'danger' : $f }}">{{ session($f) }}</div>
      @endif
    @endforeach

    @php
      // fallback jika controller belum kirim $today
      $today = $today ?? \Illuminate\Support\Carbon::today()->toDateString();
    @endphp

    <div class="cardish p-3 p-md-4">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th style="width:44%">Pelatihan</th>
              <th>Periode</th>
              <th>Status Laporan</th>
              <th class="text-end" style="width:220px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($items as $it)
              @php
                // tanggal
                $mulai   = $it->tanggal_mulai
                  ? \Illuminate\Support\Carbon::parse($it->tanggal_mulai)->isoFormat('D MMM Y') : '-';
                $selesai = $it->tanggal_selesai
                  ? \Illuminate\Support\Carbon::parse($it->tanggal_selesai)->isoFormat('D MMM Y') : '-';

                $selesaiStr = $it->tanggal_selesai
                  ? \Illuminate\Support\Carbon::parse($it->tanggal_selesai)->toDateString() : null;

                // wajib kirim bila sesi selesai (<= hari ini)
                $wajibKirim = $selesaiStr && $selesaiStr <= $today;

                // status dari laporan_pelatihan: 'pending','approved','rejected' atau 'belum' jika belum ada
                $status = $it->status_laporan ?? 'belum';

                $badgeClass = match($status){
                  'pending'  => 'badge-diajukan',
                  'approved' => 'badge-disetujui',
                  'rejected' => 'badge-ditolak',
                  default    => 'badge-belum',
                };
                $label = match($status){
                  'pending'  => 'Diajukan',
                  'approved' => 'Disetujui',
                  'rejected' => 'Ditolak',
                  default    => 'Belum Diajukan',
                };

                // id laporan terakhir (opsional; kirim dari controller bila perlu)
                $laporanId = $it->laporan_id ?? null;
              @endphp

              <tr>
                <td>
                  <div class="fw-semibold">{{ $it->nama_pelatihan }}</div>
                  <div class="text-muted small">Jenis: {{ $it->jenis_pelatihan ?? '-' }}</div>
                </td>
                <td>
                  <div>{{ $mulai }} – {{ $selesai }}</div>
                </td>
                <td>
                  <span class="badge status-badge {{ $badgeClass }}">{{ $label }}</span>
                  @if($wajibKirim && $status === 'belum')
                    <div class="small text-danger mt-1">* Wajib kirim laporan</div>
                  @endif
                </td>
                <td class="text-end">
                  @if($status === 'belum' && $wajibKirim)
                    <a href="{{ route('pegawai.laporan.create', ['id' => $it->id]) }}" class="btn btn-primary btn-sm">
                      <i class="bi bi-upload me-1"></i> Kirim Laporan
                    </a>

                  @elseif($status === 'pending')
                    <button class="btn btn-outline-info btn-sm" disabled>Menunggu verifikasi</button>

                  @elseif($status === 'approved')
                    <button class="btn btn-outline-success btn-sm" disabled>Disetujui</button>

                  @elseif($status === 'rejected' && $laporanId)
                    <a href="{{ route('pegawai.laporan.edit', ['laporan' => $laporanId]) }}" class="btn btn-warning btn-sm">
                      <i class="bi bi-pencil-square me-1"></i> Perbaiki & Kirim Ulang
                    </a>

                  @else
                    {{-- default: belum jatuh tempo / tidak ada aksi --}}
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                      {{ $wajibKirim ? '-' : 'Belum jatuh tempo' }}
                    </button>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">
                  Belum ada pelatihan yang dapat dilaporkan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($items instanceof \Illuminate\Contracts\Pagination\Paginator || $items instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <div class="mt-3 d-flex justify-content-center">
          {{ $items->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection
