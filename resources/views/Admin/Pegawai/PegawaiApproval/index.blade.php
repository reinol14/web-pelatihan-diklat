@extends('layouts.app')

@section('content')

<div class="container mt-4">
  {{-- Header + Quick Status Tabs --}}
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
    <h1 class="h4 mb-0">Verifikasi Registrasi Pegawai</h1>

    @php
      // Pertahankan filter lain (kecuali status & page)
      $keep   = request()->except('status','page');
      $status = request('status');
    @endphp
    <div class="d-flex gap-2">
      <a href="{{ route('Admin.Pegawai.PegawaiApproval.index', array_merge($keep,['status'=>'pending'])) }}"
         class="btn btn-sm {{ $status==='pending' ? 'btn-warning' : 'btn-outline-warning' }}">
        Pending
      </a>
      <a href="{{ route('Admin.Pegawai.PegawaiApproval.index', array_merge($keep,['status'=>'approved'])) }}"
         class="btn btn-sm {{ $status==='approved' ? 'btn-success' : 'btn-outline-success' }}">
        Approved
      </a>
      <a href="{{ route('Admin.Pegawai.PegawaiApproval.index', array_merge($keep,['status'=>'rejected'])) }}"
         class="btn btn-sm {{ $status==='rejected' ? 'btn-secondary' : 'btn-outline-secondary' }}">
        Rejected
      </a>
      <a href="{{ route('Admin.Pegawai.PegawaiApproval.index', request()->except('status','page')) }}"
         class="btn btn-sm btn-outline-dark">
        Semua
      </a>
    </div>
  </div>

  {{-- Search bar ringkas + tombol Filter --}}
  <form method="GET" action="{{ route('Admin.Pegawai.PegawaiApproval.index') }}" class="d-flex align-items-stretch gap-2 mb-3">
    <div class="input-group">
      <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
      <input type="text" name="q" class="form-control" placeholder="Cari: Nama / NIP / Email" value="{{ request('q') }}">
    </div>

    {{-- pertahankan filter lain saat submit --}}
    <input type="hidden" name="status" value="{{ request('status') }}">
    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
    <input type="hidden" name="date_to" value="{{ request('date_to') }}">

    <button class="btn btn-primary" type="submit">
      <i class="bi bi-funnel me-1"></i> Terapkan
    </button>



    @if(request()->hasAny(['q','status','date_from','date_to']))
      <a href="{{ route('Admin.Pegawai.PegawaiApproval.index') }}" class="btn btn-outline-dark">Reset</a>
    @endif
  </form>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif
  @if(session('info'))    <div class="alert alert-info">{{ session('info') }}</div> @endif

  <div class="table-responsive">
    <table class="table align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>NIP</th>
          <th>Email</th>
          <th>Status</th>
          <th>Tanggal Ajuan</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($registrations as $r)
          <tr>
            <td>{{ ($registrations->firstItem() ?? 1) + $loop->index }}</td>
            <td>{{ $r->nama }}</td>
            <td>{{ $r->nip }}</td>
            <td>{{ $r->email }}</td>
            <td>
              @php
                $badge = match($r->status){
                  'approved' => 'success',
                  'rejected' => 'danger',
                  'pending'  => 'warning',
                  default    => 'secondary',
                };
              @endphp
              <span class="badge text-bg-{{ $badge }}">{{ ucfirst($r->status) }}</span>
            </td>
            <td>{{ \Illuminate\Support\Carbon::parse($r->created_at)->format('d M Y H:i') }}</td>
            <td class="text-end">
              @if($r->status === 'pending')
                <a class="btn btn-sm btn-warning" href="{{ route('Admin.Pegawai.PegawaiApproval.show',$r->id) }}">
                  <i class="bi bi-exclamation-circle me-1"></i> Butuh Tinjauan
                </a>
              @else
                <a href="{{ route('Admin.Pegawai.PegawaiApproval.show', $r->id) }}" class="btn btn-sm btn-success" title="Lihat Data">
                  <i class="bi bi-eye"></i> Lihat Data
                </a>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted">Tidak ada pengajuan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- penting: pagination ikut query filter --}}
  {{ $registrations->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>

<!-- Offcanvas Filter -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="ocFilter" aria-labelledby="ocFilterLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="ocFilterLabel">Opsi Filter</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
  </div>
  <div class="offcanvas-body">
    <form method="GET" action="{{ route('Admin.Pegawai.PegawaiApproval.index') }}" id="formFilter">
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua</option>
          <option value="pending"  @selected(request('status')==='pending')>Pending</option>
          <option value="approved" @selected(request('status')==='approved')>Disetujui</option>
          <option value="rejected" @selected(request('status')==='rejected')>Ditolak</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Kata Kunci</label>
        <input type="text" name="q" class="form-control" placeholder="Nama / NIP / Email" value="{{ request('q') }}">
        <div class="form-text">Pencarian melakukan pencocokan parsial (LIKE).</div>
      </div>

      <div class="row g-2">
        <div class="col-sm-6">
          <label class="form-label">Dari Tanggal</label>
          <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-sm-6">
          <label class="form-label">Sampai Tanggal</label>
          <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('Admin.Pegawai.PegawaiApproval.index') }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
        </a>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Tutup</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Terapkan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Reject (tetap) -->
<div class="modal fade" id="modalReject" tabindex="-1" aria-labelledby="modalRejectLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="formReject" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="modalRejectLabel">Tolak Registrasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Alasan penolakan (opsional)</label>
          <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Data tidak valid atau tidak sesuai."></textarea>
        </div>
        <div class="text-muted small">Pengajuan akan berstatus <b>rejected</b>.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Tolak</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const modal = document.getElementById('modalReject');
  modal?.addEventListener('show.bs.modal', function (e) {
    const id = e.relatedTarget?.getAttribute('data-id');
    const form = document.getElementById('formReject');
    if (id && form) {
      form.action = "{{ route('Admin.Pegawai.PegawaiApproval.reject', ':id') }}".replace(':id', id);
    }
  });
});
</script>
@endpush
@endsection
