@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  {{-- Header Section --}}
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
          <h3 class="mb-1"><i class="bi bi-clipboard-check me-2"></i>Verifikasi Laporan Pelatihan</h3>
          <p class="text-muted mb-0 small">Kelola dan verifikasi laporan pelatihan yang diajukan pegawai</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
          <a href="{{ route('Admin.Laporan.approval.index',['status'=>'pending']) }}"  
             class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
            <i class="bi bi-clock-history me-1"></i> Pending 
            <span class="badge bg-white text-warning ms-1">{{ $counts['pending'] ?? 0 }}</span>
          </a>
          <a href="{{ route('Admin.Laporan.approval.index',['status'=>'approved']) }}" 
             class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
            <i class="bi bi-check-circle me-1"></i> Disetujui
            <span class="badge bg-white text-success ms-1">{{ $counts['approved'] ?? 0 }}</span>
          </a>
          <a href="{{ route('Admin.Laporan.approval.index',['status'=>'rejected']) }}" 
             class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
            <i class="bi bi-x-circle me-1"></i> Ditolak
            <span class="badge bg-white text-danger ms-1">{{ $counts['rejected'] ?? 0 }}</span>
          </a>
          <a href="{{ route('Admin.Laporan.approval.index') }}" 
             class="btn btn-sm {{ !request('status') ? 'btn-secondary' : 'btn-outline-secondary' }}">
            <i class="bi bi-list-ul me-1"></i> Semua
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Flash Messages --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
      <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Search & Filter Bar --}}
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
      <form method="GET" action="{{ url()->current() }}" id="mainFilterForm">
        <div class="row g-2">
          <div class="col-md-6 col-lg-8">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" name="q" class="form-control border-start-0 ps-0" 
                     placeholder="Cari: Nama pelatihan, nama pegawai, atau NIP..."
                     value="{{ request('q') }}">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="d-flex gap-2">
              <button class="btn btn-primary flex-grow-1" type="submit">
                <i class="bi bi-search me-1"></i> Cari
              </button>
              <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" 
                      data-bs-target="#ocFilter" aria-controls="ocFilter">
                <i class="bi bi-sliders me-1"></i> Filter
              </button>
              @if(request()->hasAny(['q','status','date_from','date_to']))
                <a href="{{ url()->current() }}" class="btn btn-outline-danger" title="Reset Filter">
                  <i class="bi bi-x-circle"></i>
                </a>
              @endif
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Table --}}
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="px-4" style="width: 50px;">#</th>
              <th style="width: 180px;">Pegawai</th>
              <th style="width: 200px;">Pelatihan</th>
              <th>Judul Laporan</th>
              <th class="text-center" style="width: 100px;">Berkas</th>
              <th class="text-center" style="width: 130px;">Status</th>
              <th class="text-center" style="width: 200px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
          <tbody>
          @forelse($items as $row)
            @php
              $badge = $row->status==='pending' ? 'warning' : ($row->status==='approved' ? 'success' : 'danger');
              $badgeIcon = $row->status==='pending' ? 'clock-history' : ($row->status==='approved' ? 'check-circle-fill' : 'x-circle-fill');
              $badgeText = $row->status==='pending' ? 'Pending' : ($row->status==='approved' ? 'Disetujui' : 'Ditolak');
              $fileUrl = $row->file_path ? ( \Illuminate\Support\Str::startsWith($row->file_path,'storage/') ? asset($row->file_path) : asset('storage/'.ltrim($row->file_path,'/')) ) : null;
              $sertifikat = $row->sertifikat ? ( \Illuminate\Support\Str::startsWith($row->sertifikat,'storage/') ? asset($row->sertifikat) : asset('storage/'.ltrim($row->sertifikat,'/')) ) : null;
            @endphp
            <tr>
              <td class="px-4 text-muted">{{ ($items->firstItem() ?? 1) + $loop->index }}</td>
              <td>
                <div class="fw-semibold text-dark">{{ $row->nama_pegawai ?? '-' }}</div>
                <small class="text-muted"><i class="bi bi-person-badge me-1"></i>{{ $row->nip }}</small>
              </td>
              <td>
                <div class="text-dark">{{ \Illuminate\Support\Str::limit($row->nama_pelatihan, 50) }}</div>
              </td>
              <td>
                <div class="fw-medium">{{ \Illuminate\Support\Str::limit($row->judul, 60) }}</div>
              </td>
              <td class="text-center">
                <div class="d-flex gap-1 justify-content-center">
                  @if($fileUrl)
                    <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary" 
                       data-bs-toggle="tooltip" title="Lihat Lampiran">
                      <i class="bi bi-file-earmark-pdf"></i>
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                  
                  @if($sertifikat)
                    <a href="{{ $sertifikat }}" target="_blank" class="btn btn-sm btn-outline-info" 
                       data-bs-toggle="tooltip" title="Lihat Sertifikat">
                      <i class="bi bi-award"></i>
                    </a>
                  @endif
                </div>
              </td>
              <td class="text-center">
                <span class="badge bg-{{ $badge }} d-inline-flex align-items-center">
                  <i class="bi bi-{{ $badgeIcon }} me-1"></i>{{ $badgeText }}
                </span>
                @if($row->keterangan)
                  <div class="small text-muted mt-2" style="max-width: 120px;">
                    <i class="bi bi-chat-left-text me-1"></i>
                    <span data-bs-toggle="tooltip" title="{{ $row->keterangan }}">
                      {{ \Illuminate\Support\Str::limit($row->keterangan, 30) }}
                    </span>
                  </div>
                @endif
                @if($row->reviewed_by && $row->reviewed_at)
                  <div class="small text-muted mt-2">
                    <i class="bi bi-person-check me-1"></i>
                    <span data-bs-toggle="tooltip" title="Diverifikasi oleh: {{ $row->reviewer_name ?? 'Admin' }} ({{ $row->reviewer_is_admin == 1 ? 'Superadmin' : 'Admin Unit' }}) pada {{ \Carbon\Carbon::parse($row->reviewed_at)->isoFormat('D MMM Y, HH:mm') }}">
                      {{ \Illuminate\Support\Str::limit($row->reviewer_name ?? 'Admin', 15) }}
                    </span>
                  </div>
                @endif
              </td>
              <td class="text-center">
                @if($row->status === 'pending')
                  <div class="d-flex gap-1 justify-content-center">
                    <button
                      type="button"
                      class="btn btn-sm btn-success btn-open-modal"
                      data-bs-toggle="modal"
                      data-bs-target="#modalDecision"
                      data-id="{{ $row->id }}"
                      data-action="approve"
                      data-title="Setujui Laporan"
                      data-label="Catatan persetujuan (opsional)"
                      title="Setujui">
                      <i class="bi bi-check-circle me-1"></i> Setujui
                    </button>

                    <button
                      type="button"
                      class="btn btn-sm btn-danger btn-open-modal"
                      data-bs-toggle="modal"
                      data-bs-target="#modalDecision"
                      data-id="{{ $row->id }}"
                      data-action="reject"
                      data-title="Tolak Laporan"
                      data-label="Alasan penolakan (wajib)"
                      title="Tolak">
                      <i class="bi bi-x-circle me-1"></i> Tolak
                    </button>
                  </div>
                @elseif($row->status === 'approved')
                  <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                    <i class="bi bi-check-circle-fill me-1"></i> Sudah Disetujui
                  </span>
                @else
                  <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                    <i class="bi bi-x-circle-fill me-1"></i> Sudah Ditolak
                  </span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-5">
                <div class="text-muted">
                  <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                  <p class="mb-0">Tidak ada data laporan ditemukan</p>
                </div>
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>

    @if($items->hasPages())
      <div class="card-footer bg-white border-top">
        <div class="d-flex justify-content-center">
          {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
      </div>
    @endif
  </div>
</div>

{{-- Offcanvas Filter --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="ocFilter" aria-labelledby="ocFilterLabel">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="ocFilterLabel">
      <i class="bi bi-sliders me-2"></i>Filter Lanjutan
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
  </div>
  <div class="offcanvas-body">
    <form method="GET" action="{{ url()->current() }}">
      <div class="mb-4">
        <label class="form-label fw-semibold">
          <i class="bi bi-flag me-1"></i> Status Laporan
        </label>
        <select name="status" class="form-select">
          <option value="">Semua Status</option>
          <option value="pending"  @selected(request('status')==='pending')>⏳ Pending</option>
          <option value="approved" @selected(request('status')==='approved')>✅ Disetujui</option>
          <option value="rejected" @selected(request('status')==='rejected')>❌ Ditolak</option>
        </select>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold">
          <i class="bi bi-search me-1"></i> Kata Kunci
        </label>
        <input type="text" name="q" class="form-control" 
               placeholder="Nama pelatihan, pegawai, atau NIP" 
               value="{{ request('q') }}">
        <div class="form-text">Cari berdasarkan nama pelatihan, nama pegawai, atau NIP</div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold">
          <i class="bi bi-calendar-range me-1"></i> Periode Pengajuan
        </label>
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label small text-muted">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
          </div>
          <div class="col-6">
            <label class="form-label small text-muted">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
          </div>
        </div>
      </div>

      <div class="border-top pt-3 mt-auto">
        <div class="d-flex gap-2">
          <a href="{{ url()->current() }}" class="btn btn-outline-secondary flex-grow-1">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
          </a>
          <button type="submit" class="btn btn-primary flex-grow-1">
            <i class="bi bi-funnel me-1"></i> Terapkan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Modal Keputusan --}}
<div class="modal fade" id="modalDecision" tabindex="-1" aria-labelledby="modalDecisionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" id="formDecision" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="modalDecisionLabel">
          <i class="bi bi-check-circle me-2"></i>Keputusan Verifikasi
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <div class="modal-body">
        <div class="alert alert-info mb-3">
          <i class="bi bi-info-circle me-2"></i>
          <small>Berikan catatan atau alasan untuk keputusan Anda</small>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold" id="reasonLabel">Catatan/Alasan</label>
          <textarea name="keterangan" class="form-control" rows="4"
                    placeholder="Tulis catatan atau alasan di sini..."></textarea>
          <div class="form-text" id="reasonHelp">
            Untuk penolakan, sebaiknya berikan alasan yang jelas agar pegawai dapat memperbaiki laporan.
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x me-1"></i> Batal
        </button>
        <button type="submit" class="btn btn-primary" id="btnDecisionSubmit">
          <i class="bi bi-check-circle me-1"></i> Simpan Keputusan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  const modal = document.getElementById('modalDecision');
  const form  = document.getElementById('formDecision');
  const label = document.getElementById('reasonLabel');
  const title = document.getElementById('modalDecisionLabel');
  const btn   = document.getElementById('btnDecisionSubmit');
  const reasonHelp = document.getElementById('reasonHelp');

  modal?.addEventListener('show.bs.modal', function (e) {
    const trg = e.relatedTarget;
    if (!trg) return;
    
    const id     = trg.getAttribute('data-id');
    const action = trg.getAttribute('data-action'); // approve | reject
    const mtitle = trg.getAttribute('data-title') || 'Keputusan Verifikasi';
    const mlabel = trg.getAttribute('data-label') || 'Catatan/Alasan';

    // Set title & label
    title.innerHTML = action === 'approve' 
      ? '<i class="bi bi-check-circle me-2 text-success"></i>' + mtitle
      : '<i class="bi bi-x-circle me-2 text-danger"></i>' + mtitle;
    label.textContent = mlabel;

    // Reset textarea
    form.querySelector('textarea[name="keterangan"]').value = '';

    // Update help text based on action
    if (action === 'reject') {
      reasonHelp.innerHTML = '<strong>Wajib:</strong> Berikan alasan penolakan yang jelas agar pegawai dapat memperbaiki laporan.';
      reasonHelp.classList.remove('text-muted');
      reasonHelp.classList.add('text-danger');
    } else {
      reasonHelp.innerHTML = 'Opsional: Anda dapat memberikan catatan tambahan untuk persetujuan ini.';
      reasonHelp.classList.remove('text-danger');
      reasonHelp.classList.add('text-muted');
    }

    // Set action & button style
    if (action === 'approve') {
      form.action = "{{ route('Admin.Laporan.approval.approve', ':id') }}".replace(':id', id);
      btn.className = 'btn btn-success';
      btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Setujui Laporan';
    } else {
      form.action = "{{ route('Admin.Laporan.approval.reject', ':id') }}".replace(':id', id);
      btn.className = 'btn btn-danger';
      btn.innerHTML = '<i class="bi bi-x-circle me-1"></i> Tolak Laporan';
    }
  });

  // Form submission validation for reject action
  form?.addEventListener('submit', function(e) {
    const action = form.action;
    const isReject = action.includes('/reject');
    const keterangan = form.querySelector('textarea[name="keterangan"]').value.trim();
    
    if (isReject && !keterangan) {
      e.preventDefault();
      alert('Alasan penolakan wajib diisi!');
      form.querySelector('textarea[name="keterangan"]').focus();
      return false;
    }
  });
});
</script>
@endpush