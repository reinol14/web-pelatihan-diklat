@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Detail Pengajuan #{{ $item->id }}</h3>
    <a href="{{ route('Admin.pegawai_profile.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header bg-white"><strong>Data Usulan</strong></div>
        <div class="card-body">
          <pre class="mb-0" style="white-space:pre-wrap">{{ json_encode($item->payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header bg-white"><strong>Data Saat Ini</strong></div>
        <div class="card-body">
          <pre class="mb-0" style="white-space:pre-wrap">
{{ json_encode($item->pegawai?->only(array_keys($item->payload ?? [])) ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}
          </pre>
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-body d-flex flex-wrap gap-2 align-items-center">
      <div>Status:
        <span class="badge text-bg-{{ $item->status=='pending'?'warning':($item->status=='approved'?'success':'secondary') }}">
          {{ $item->status }}
        </span>
      </div>
      @if($item->review_note)
        <div class="text-muted">Catatan: {{ $item->review_note }}</div>
      @endif
    </div>
  </div>

  @if($item->status === 'pending')
    <div class="d-flex flex-wrap gap-2 mt-3">
      <form method="POST" action="{{ route('Admin.pegawai_profile.approve',$item->id) }}" class="d-flex gap-2">
        @csrf
        <input type="text" name="review_note" class="form-control" placeholder="Catatan (opsional)" style="max-width:320px">
        <button class="btn btn-success">Setujui & Terapkan</button>
      </form>

      <form method="POST" action="{{ route('Admin.pegawai_profile.reject',$item->id) }}" class="d-flex gap-2">
        @csrf
        <input type="text" name="review_note" class="form-control" placeholder="Alasan penolakan (opsional)" style="max-width:320px">
        <button class="btn btn-outline-danger">Tolak</button>
      </form>
    </div>
  @else
    <div class="alert alert-info mt-3 mb-0">
      Pengajuan sudah <strong>{{ $item->status }}</strong>.
      @if($item->reviewed_at)
        Ditinjau pada {{ $item->reviewed_at->format('d M Y H:i') }}.
      @endif
    </div>
  @endif
</div>
@endsection
