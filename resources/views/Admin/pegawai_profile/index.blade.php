@extends('layouts.app')

@section('content')
<div class="container py-4">

  {{-- Header + Quick tabs status --}}
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h3 class="mb-0">Verifikasi Perubahan Data Pegawai</h3>

    <div class="d-flex gap-2">
      <a href="{{ route('Admin.pegawai_profile.index',['status'=>'pending']) }}"
         class="btn btn-sm btn-warning">Pending ({{ $counts['pending'] ?? 0 }})</a>
      <a href="{{ route('Admin.pegawai_profile.index',['status'=>'approved']) }}"
         class="btn btn-sm btn-success">Approved ({{ $counts['approved'] ?? 0 }})</a>
      <a href="{{ route('Admin.pegawai_profile.index',['status'=>'rejected']) }}"
         class="btn btn-sm btn-secondary">Rejected ({{ $counts['rejected'] ?? 0 }})</a>
      <a href="{{ route('Admin.pegawai_profile.index') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif
  @if(session('info'))    <div class="alert alert-info">{{ session('info') }}</div> @endif

  {{-- Search bar + tombol Filter --}}
  <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-stretch gap-2 mb-3" id="mainFilterForm">
    <div class="input-group">
      <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
      <input type="text" name="q" class="form-control" placeholder="Cari: Nama / NIP"
             value="{{ request('q') }}">
    </div>

    <button class="btn btn-primary" type="submit">
      <i class="bi bi-funnel me-1"></i> Terapkan
    </button>



    @if(request()->hasAny(['q','status','date_from','date_to','reviewed']))
      <a href="{{ url()->current() }}" class="btn btn-outline-dark">Reset</a>
    @endif
  </form>

  {{-- Tabel --}}
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Pegawai</th>
          <th>Status</th>
          <th>Pengajuan</th>
          <th>Ditinjau</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse($items as $row)
        <tr>
          <td>{{ ($items->firstItem() ?? 1) + $loop->index }}</td>
          <td>
            {{ $row->pegawai->nama ?? '-' }}<br>
            <small class="text-muted">NIP: {{ $row->pegawai->nip ?? '-' }}</small>
          </td>
          <td>
            @php
              $badge = $row->status==='pending' ? 'warning' : ($row->status==='approved' ? 'success' : 'secondary');
            @endphp
            <span class="badge text-bg-{{ $badge }}">{{ ucfirst($row->status) }}</span>
          </td>
          <td>{{ optional($row->created_at)->format('d M Y H:i') }}</td>
          <td>
            @if($row->reviewed_at)
              {{ $row->reviewed_at->format('d M Y H:i') }}
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td class="text-end">
            @if($row->status === 'pending')
              <a class="btn btn-sm btn-warning" href="{{ route('Admin.pegawai_profile.show',$row->id) }}">
                <i class="bi bi-exclamation-circle me-1"></i> Butuh Tinjauan
              </a>
            @elseif($row->status === 'approved')
              <a class="btn btn-sm btn-success" href="{{ route('Admin.pegawai_profile.show',$row->id) }}">
                <i class="bi bi-eye me-1"></i> Lihat Data
              </a>
            @else
              <a class="btn btn-sm btn-secondary" href="{{ route('Admin.pegawai_profile.show',$row->id) }}">
                <i class="bi bi-eye-slash me-1"></i> Lihat Data
              </a>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination bawa query filter --}}
  <div class="mt-3">
    {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
  </div>
</div>

{{-- Offcanvas Filter --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="ocFilter" aria-labelledby="ocFilterLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="ocFilterLabel">Opsi Filter</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
  </div>
  <div class="offcanvas-body">
    <form method="GET" action="{{ url()->current() }}">
      {{-- Status --}}
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua</option>
          <option value="pending"  @selected(request('status')==='pending')>Pending</option>
          <option value="approved" @selected(request('status')==='approved')>Approved</option>
          <option value="rejected" @selected(request('status')==='rejected')>Rejected</option>
        </select>
      </div>

      {{-- Review flag --}}
      <div class="mb-3">
        <label class="form-label">Status Tinjauan</label>
        <select name="reviewed" class="form-select">
          <option value="">Semua</option>
          <option value="0" @selected(request('reviewed')==='0')>Belum ditinjau</option>
          <option value="1" @selected(request('reviewed')==='1')>Sudah ditinjau</option>
        </select>
      </div>

      {{-- Keyword --}}
      <div class="mb-3">
        <label class="form-label">Kata Kunci</label>
        <input type="text" name="q" class="form-control" placeholder="Nama / NIP"
               value="{{ request('q') }}">
        <div class="form-text">Pencarian pada nama pegawai atau NIP.</div>
      </div>

      {{-- Rentang tanggal pengajuan --}}
      <div class="row g-2 mb-3">
        <div class="col-sm-6">
          <label class="form-label">Pengajuan dari</label>
          <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-sm-6">
          <label class="form-label">Pengajuan sampai</label>
          <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
        </a>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Tutup</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-funnel me-1"></i> Terapkan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
