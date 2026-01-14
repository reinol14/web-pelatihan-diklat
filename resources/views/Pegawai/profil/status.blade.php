@extends('layouts.pegawai')

@section('title','Status Ajuan Profil')

@push('styles')
<style>
  .status-card{
    border:none;
    border-radius:16px;
    box-shadow:0 12px 28px rgba(24,16,63,.08);
  }
  .status-badge{
    padding:.4rem .7rem;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
  }
  .badge-pending{background:#fff3cd;color:#856404}
  .badge-approved{background:#d1e7dd;color:#0f5132}
  .badge-rejected{background:#f8d7da;color:#842029}
  .note-box{
    background:#f8f9fb;
    border-left:4px solid #dc3545;
    border-radius:8px;
    padding:.75rem;
    font-size:.85rem;
  }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Status Ajuan Perubahan Profil</h4>
  <a href="{{ route('Pegawai.profil') }}" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left me-1"></i> Kembali
  </a>
</div>

<div class="card status-card">
  <div class="card-body p-0">

    @if($changes->isEmpty())
      <div class="p-4 text-center text-muted">
        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
        Belum ada pengajuan perubahan profil
      </div>
    @else
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Tanggal Pengajuan</th>
              <th>Status</th>
              <th>Catatan Admin</th>
            </tr>
          </thead>
          <tbody>
            @foreach($changes as $c)
              <tr>
                <td>
                  <div class="fw-semibold">
                    {{ $c->created_at->format('d M Y') }}
                  </div>
                  <div class="text-muted small">
                    {{ $c->created_at->format('H:i') }} WIB
                  </div>
                </td>

                <td>
                  @if($c->status === 'pending')
                    <span class="status-badge badge-pending">
                      <i class="bi bi-hourglass-split me-1"></i> Menunggu
                    </span>
                  @elseif($c->status === 'approved')
                    <span class="status-badge badge-approved">
                      <i class="bi bi-check-circle me-1"></i> Disetujui
                    </span>
                  @else
                    <span class="status-badge badge-rejected">
                      <i class="bi bi-x-circle me-1"></i> Ditolak
                    </span>
                  @endif
                </td>

                <td>
                  @if($c->status === 'rejected' && $c->review_note)
                    <div class="note-box">
                      <strong>Catatan:</strong><br>
                      {{ $c->review_note }}
                    </div>
                  @elseif($c->status === 'approved')
                    <span class="text-success small">
                      Data telah diterapkan ke profil
                    </span>
                  @else
                    <span class="text-muted small">-</span>
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

@endsection
