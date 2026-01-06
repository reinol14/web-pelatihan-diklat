@extends('layouts.app') {{-- ganti ke layout admin-mu --}}

@section('title','Verifikasi Pelatihan')

@push('styles')
<style>
  .stat { font-size:.9rem; color:#6c757d }
  .table td, .table th { vertical-align: middle !important; }
</style>
@endpush

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h5 mb-0">Verifikasi Pelatihan (Admin)</h1>
  </div>

  {{-- FLASH MESSAGES --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  {{-- Filter bar --}}
  <form method="GET" class="row g-2 align-items-end mb-3">
    <div class="col-md-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select">
        @foreach(['menunggu','diterima','berjalan','menunggu_laporan','ditolak','dibatalkan','lulus','tidak_lulus'] as $st)
          <option value="{{ $st }}" @selected(($status ?? 'menunggu')===$st)>{{ ucfirst(str_replace('_',' ',$st)) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Pelatihan</label>
      <select name="pelatihan_id" class="form-select">
        <option value="">Semua</option>
        @foreach($sessions as $s)
          <option value="{{ $s->id }}" @selected((string)$sesiId === (string)$s->id)>{{ $s->nama_pelatihan }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Cari (NIP / Nama / Unit)</label>
      <input type="text" name="q" class="form-control" value="{{ $q ?? '' }}">
    </div>
    <div class="col-md-2 d-flex gap-2">
      <button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel me-1"></i> Terapkan</button>
      {{-- PERBAIKAN: route reset --}}
      <a href="{{ route('Admin.Pelatihan.verifikasi') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
  </form>

  {{-- Bulk actions --}}
  <form method="POST" action="{{ route('Admin.Pelatihan.verifikasi.bulk') }}">
    @csrf
    <div class="d-flex align-items-center justify-content-between mb-2">
      <div class="d-flex gap-2">
        <select name="action" class="form-select form-select-sm" style="width:auto">
          <option value="approve">Setujui Terpilih</option>
          <option value="reject">Tolak Terpilih</option>
        </select>
        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-check2-circle me-1"></i> Jalankan</button>
      </div>
      <div class="stat">
        Menampilkan <b>{{ $pesertas->total() }}</b> data (hal. {{ $pesertas->currentPage() }} / {{ $pesertas->lastPage() }})
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:32px"><input type="checkbox" id="checkAll"></th>
            <th>ASN</th>
            <th>Pelatihan</th>
            <th>Jadwal</th>
            <th>Kuota</th>
            <th class="text-center">Status</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($pesertas as $row)
          @php
            $sesi = $row->pelatihan;
            $mulai = $sesi?->tanggal_mulai ? \Illuminate\Support\Carbon::parse($sesi->tanggal_mulai)->isoFormat('D MMM Y') : '-';
            $selesai = $sesi?->tanggal_selesai ? \Illuminate\Support\Carbon::parse($sesi->tanggal_selesai)->isoFormat('D MMM Y') : '-';
            $kuota = (int)($sesi->kuota ?? 0);
            $used = (int)($terpakaiMap[$sesi->id] ?? 0);
            $penuh = $kuota > 0 && $used >= $kuota;
          @endphp
          <tr>
            <td>
              @if($row->status === 'menunggu')
                <input type="checkbox" name="selected[]" value="{{ $row->pelatihan_id }}|{{ $row->nip }}">
              @endif
            </td>
            <td>
              <div class="fw-semibold">{{ $row->nama ?? '-' }}</div>
              <div class="text-muted small">NIP: {{ $row->nip }}</div>
              <div class="text-muted small">{{ $row->unitkerja ?? '-' }}</div>
            </td>
            <td>
              <div class="fw-semibold">{{ $sesi?->nama_pelatihan ?? '-' }}</div>
              <div class="text-muted small">{{ $sesi?->metode_pelatihan ?? '-' }} • {{ $sesi?->lokasi ?? '-' }}</div>
            </td>
            <td>{{ $mulai }} – {{ $selesai }}</td>
            <td>
              @if($kuota>0)
                <span class="badge {{ $penuh ? 'text-bg-danger' : 'text-bg-secondary' }}"
                      title="{{ $penuh ? 'Kuota penuh' : 'Kursi terpakai' }}">
                  {{ $used }}/{{ $kuota }}
                </span>
              @else
                <span class="badge text-bg-secondary">Tanpa batas</span>
              @endif
            </td>
            <td class="text-center">
              @php
                $badge = match($row->status) {
                  'menunggu'          => 'secondary',
                  'diterima'          => 'primary',
                  'berjalan'          => 'success',
                  'menunggu_laporan'  => 'warning',
                  'ditolak'           => 'danger',
                  'dibatalkan'        => 'secondary',
                  'lulus'             => 'success',
                  'tidak_lulus'       => 'danger',
                  default             => 'light',
                };
                $label = ucfirst(str_replace('_',' ',$row->status));
              @endphp
              <span class="badge text-bg-{{ $badge }}">{{ $label }}</span>
            </td>
<td class="text-end">
  {{-- Detail sesi (opsional) --}}
  @if($sesi)
    <a href="{{ route('Pelatihan.show',['id'=>$sesi->id]) }}"
       class="btn btn-sm btn-outline-primary" target="_blank" title="Lihat sesi">
      <i class="bi bi-eye"></i>
    </a>
  @endif

  {{-- Approve / Reject TANPA nested form --}}
  @if($row->status === 'menunggu')
    <button type="submit"
            class="btn btn-sm btn-success"
            title="{{ $penuh ? 'Kuota penuh' : 'Setujui' }}"
            {{ $penuh ? 'disabled' : '' }}
            formaction="{{ route('Admin.Pelatihan.verifikasi.approve', ['pelatihan'=>$row->pelatihan_id, 'nip'=>$row->nip]) }}"
            formmethod="POST"
            onclick="return {{ $penuh ? 'false' : 'confirm(\'Setujui pengajuan ini?\')' }}">
      <i class="bi bi-check2"></i>
    </button>

    <button type="submit"
            class="btn btn-sm btn-outline-danger"
            title="Tolak"
            formaction="{{ route('Admin.Pelatihan.verifikasi.reject', ['pelatihan'=>$row->pelatihan_id, 'nip'=>$row->nip]) }}"
            formmethod="POST"
            onclick="return confirm('Tolak pengajuan ini?')">
      <i class="bi bi-x"></i>
    </button>
  @endif
</td>

          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $pesertas->links('pagination::bootstrap-5') }}
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('checkAll')?.addEventListener('change', function(){
  document.querySelectorAll('input[name="selected[]"]').forEach(cb => cb.checked = this.checked);
});
</script>
@endpush
