@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h3 class="mb-0">Verifikasi Laporan Pelatihan</h3>

    <div class="d-flex gap-2">
      <a href="{{ route('Admin.Laporan.approval.index',['status'=>'pending']) }}"  class="btn btn-sm btn-warning">Pending ({{ $counts['pending'] ?? 0 }})</a>
      <a href="{{ route('Admin.Laporan.approval.index',['status'=>'approved']) }}" class="btn btn-sm btn-success">Approved ({{ $counts['approved'] ?? 0 }})</a>
      <a href="{{ route('Admin.Laporan.approval.index',['status'=>'rejected']) }}" class="btn btn-sm btn-secondary">Rejected ({{ $counts['rejected'] ?? 0 }})</a>
      <a href="{{ route('Admin.Laporan.approval.index') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif
  @if(session('info'))    <div class="alert alert-info">{{ session('info') }}</div> @endif

  {{-- Bar pencarian + offcanvas filter --}}
  <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-stretch gap-2 mb-3" id="mainFilterForm">
    <div class="input-group">
      <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
      <input type="text" name="q" class="form-control" placeholder="Cari: Nama pelatihan / Pegawai / NIP"
             value="{{ request('q') }}">
    </div>
    <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i> Terapkan</button>
    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#ocFilter" aria-controls="ocFilter">
      Opsi Filter
    </button>
    @if(request()->hasAny(['q','status','date_from','date_to']))
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
        <th>Pelatihan</th>
        <th>Judul Laporan</th>
        <th>Lampiran</th>
        <th>Sertifikat</th>
        <th>Status</th>
        <th class="text-end">Aksi</th>
      </tr>
      </thead>
      <tbody>
      @forelse($items as $row)
        @php
          $badge = $row->status==='pending' ? 'warning' : ($row->status==='approved' ? 'success' : 'secondary');
          $fileUrl = $row->file_path ? ( \Illuminate\Support\Str::startsWith($row->file_path,'storage/') ? asset($row->file_path) : asset('storage/'.ltrim($row->file_path,'/')) ) : null;
          $sertifikat = $row->sertifikat ? ( \Illuminate\Support\Str::startsWith($row->sertifikat,'storage/') ? asset($row->sertifikat) : asset('storage/'.ltrim($row->sertifikat,'/')) ) : null;
        @endphp
        <tr>
          <td>{{ ($items->firstItem() ?? 1) + $loop->index }}</td>
          <td>
            {{ $row->nama_pegawai ?? '-' }}<br>
            <small class="text-muted">NIP: {{ $row->nip }}</small>
          </td>
          <td>{{ $row->nama_pelatihan }}</td>
          <td>{{ $row->judul }}</td>
          <td>
            @if($fileUrl)
              <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-text me-1"></i> Lampiran
              </a>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
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
            <span class="badge text-bg-{{ $badge }}">{{ ucfirst($row->status) }}</span>
            @if($row->keterangan)
              <div class="small text-muted mt-1">Catatan: {{ $row->keterangan }}</div>
            @endif

          <td class="text-end">
            @if($row->status === 'pending')
              <button
                type="button"
                class="btn btn-sm btn-success btn-open-modal"
                data-bs-toggle="modal"
                data-bs-target="#modalDecision"
                data-id="{{ $row->id }}"
                data-action="approve"
                data-title="Setujui Laporan"
                data-label="Alasan persetujuan (opsional)">
                <i class="bi bi-check2-circle me-1"></i> Approve
              </button>

              <button
                type="button"
                class="btn btn-sm btn-danger btn-open-modal"
                data-bs-toggle="modal"
                data-bs-target="#modalDecision"
                data-id="{{ $row->id }}"
                data-action="reject"
                data-title="Tolak Laporan"
                data-label="Alasan penolakan (opsional)">
                <i class="bi bi-x-circle me-1"></i> Reject
              </button>
            @elseif($row->status === 'approved')
              <button class="btn btn-sm btn-outline-success" disabled>
                <i class="bi bi-check2-circle me-1"></i> Approved
              </button>
            @else
              <button class="btn btn-sm btn-outline-secondary" disabled>
                <i class="bi bi-x-circle me-1"></i> Rejected
              </button>
            @endif
          </td>

        </tr>
      @empty
        <tr><td colspan="8" class="text-center text-muted">Tidak ada data.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

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
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua</option>
          <option value="pending"  @selected(request('status')==='pending')>Pending</option>
          <option value="approved" @selected(request('status')==='approved')>Approved</option>
          <option value="rejected" @selected(request('status')==='rejected')>Rejected</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Kata Kunci</label>
        <input type="text" name="q" class="form-control" placeholder="Nama pelatihan / pegawai / NIP" value="{{ request('q') }}">
      </div>
      <div class="row g-2">
        <div class="col-sm-6">
          <label class="form-label">Dari</label>
          <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-sm-6">
          <label class="form-label">Sampai</label>
          <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ url()->current() }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Tutup</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Terapkan</button>
        </div>
      </div>
    </form>
  </div>
</div>
{{-- Modal Keputusan --}}
<div class="modal fade" id="modalDecision" tabindex="-1" aria-labelledby="modalDecisionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="formDecision" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="modalDecisionLabel">Keputusan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label" id="reasonLabel">Alasan (opsional)</label>
          <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Tulis alasan bila perlu (opsional)"></textarea>
          <div class="form-text">Kosongkan bila tidak ada catatan.</div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" id="btnDecisionSubmit">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Keputusan --}}
<div class="modal fade" id="modalDecision" tabindex="-1" aria-labelledby="modalDecisionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="formDecision" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="modalDecisionLabel">Keputusan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label" id="reasonLabel">Alasan (opsional)</label>
          <textarea name="keterangan" class="form-control" rows="3" placeholder="Tulis alasan jika perlu"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" id="btnDecisionSubmit">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const modal = document.getElementById('modalDecision');
  const form  = document.getElementById('formDecision');
  const label = document.getElementById('reasonLabel');
  const title = document.getElementById('modalDecisionLabel');
  const btn   = document.getElementById('btnDecisionSubmit');

  modal?.addEventListener('show.bs.modal', function (e) {
    const tr = e.relatedTarget;
    if (!tr) return;
    const id     = tr.getAttribute('data-id');
    const action = tr.getAttribute('data-action'); // approve | reject
    const mtitle = tr.getAttribute('data-title') || 'Keputusan';
    const mlabel = tr.getAttribute('data-label') || 'Alasan (opsional)';

    // Set title + label
    title.textContent = mtitle;
    label.textContent = mlabel;

    // Set form action sesuai tombol
    if (action === 'approve') {
      form.action = "{{ route('Admin.Laporan.approval.approve', ':id') }}".replace(':id', id);
      btn.classList.remove('btn-danger'); btn.classList.add('btn-primary');
      btn.textContent = 'Setujui';
    } else {
      form.action = "{{ route('Admin.Laporan.approval.reject', ':id') }}".replace(':id', id);
      btn.classList.remove('btn-primary'); btn.classList.add('btn-danger');
      btn.textContent = 'Tolak';
    }
  });
});
</script>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const modal = document.getElementById('modalDecision');
  const form  = document.getElementById('formDecision');
  const label = document.getElementById('reasonLabel');
  const title = document.getElementById('modalDecisionLabel');
  const btn   = document.getElementById('btnDecisionSubmit');

  modal?.addEventListener('show.bs.modal', function (e) {
    const trg = e.relatedTarget; if (!trg) return;
    const id     = trg.getAttribute('data-id');
    const action = trg.getAttribute('data-action'); // approve | reject
    const mtitle = trg.getAttribute('data-title') || 'Keputusan';
    const mlabel = trg.getAttribute('data-label') || 'Alasan (opsional)';

    // set judul & label
    title.textContent = mtitle;
    label.textContent = mlabel;

    // reset textarea
    form.querySelector('textarea[name="keterangan"]').value = '';

    // set action + gaya tombol submit
    if (action === 'approve') {
      form.action = "{{ route('Admin.Laporan.approval.approve', ':id') }}".replace(':id', id);
      btn.classList.remove('btn-danger'); btn.classList.add('btn-primary');
      btn.textContent = 'Setujui';
    } else {
      form.action = "{{ route('Admin.Laporan.approval.reject', ':id') }}".replace(':id', id);
      btn.classList.remove('btn-primary'); btn.classList.add('btn-danger');
      btn.textContent = 'Tolak';
    }
  });
});
</script>
@endpush
