@extends('layouts.pegawai')

@section('title', 'Kirim Laporan Pelatihan')

@push('styles')
<style>
  :root{ --line:#e9ecef; --muted:#6c757d; }
  .cardish{ border:1px solid var(--line); border-radius:14px; background:#fff; }
  .hero{ background:#fff; border-bottom:1px solid var(--line); }
  .invalid-feedback { display: none; font-size: 0.875rem; color: #dc3545; margin-top: 0.25rem; }
  .form-control.is-invalid, .form-control:invalid:not(:placeholder-shown) { border-color: #dc3545; }
  .form-control.is-invalid ~ .invalid-feedback { display: block; }
  .was-validated .form-control:invalid ~ .invalid-feedback { display: block; }
</style>
@endpush

@section('content')

<section class="hero py-3">
  <div class="container">
    <a href="{{ route('Pegawai.Laporan.index') }}" class="text-decoration-none">&larr; Kembali ke daftar laporan</a>
  </div>
</section>

@php
  $mulai   = $sesi->tanggal_mulai ? \Illuminate\Support\Carbon::parse($sesi->tanggal_mulai)->isoFormat('D MMM Y') : '-';
  $selesai = $sesi->tanggal_selesai ? \Illuminate\Support\Carbon::parse($sesi->tanggal_selesai)->isoFormat('D MMM Y') : '-';
@endphp

<section class="py-4">
  <div class="container">
    <h1 class="h4 mb-3">Kirim Laporan — {{ $sesi->nama_pelatihan }}</h1>

    {{-- Error validasi --}}
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-start">
          <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
          <div class="flex-grow-1">
            <div class="fw-bold mb-2">Terdapat {{ count($errors) }} kesalahan dalam pengisian form:</div>
            <ul class="mb-0 ps-3">
              @foreach($errors->all() as $err)
                <li class="mb-1">{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
      </div>
    @endif

    <div class="row g-3 g-lg-4">
      <div class="col-lg-7">
        <div class="cardish p-3 p-md-4">
          <form method="POST" action="{{ route('Pegawai.Laporan.store', $sesi->id) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
              <label class="form-label">Judul Laporan <span class="text-danger">*</span></label>
              <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required maxlength="150" placeholder="Contoh: Implementasi Manajemen Waktu di Unit X">
              @error('judul')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @else
                <div class="invalid-feedback">Judul laporan wajib diisi (maksimal 150 karakter).</div>
              @enderror
              <div class="form-text">Maks 150 karakter.</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Ringkasan <span class="text-danger">*</span></label>
              <textarea name="ringkasan" class="form-control @error('ringkasan') is-invalid @enderror" rows="7" required maxlength="5000" placeholder="Tuliskan ringkasan materi, hasil, dan rencana tindak lanjut...">{{ old('ringkasan') }}</textarea>
              @error('ringkasan')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @else
                <div class="invalid-feedback">Ringkasan laporan wajib diisi (maksimal 5000 karakter).</div>
              @enderror
              <div class="form-text">Maks 5000 karakter.</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Lampiran <span class="text-danger">*</span></label>
              <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx" required>
              @error('file')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @else
                <div class="invalid-feedback">File lampiran wajib diupload (PDF/DOC/DOCX, maksimal 5MB).</div>
              @enderror
              <div class="form-text">PDF/DOC/DOCX, maks 5MB.</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Sertifikat (opsional)</label>
              <input type="file" name="sertifikat" class="form-control @error('sertifikat') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
              @error('sertifikat')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
              <div class="form-text">PDF/JPG/JPEG/PNG, maks 5MB.</div>
            </div>

            <div class="d-flex gap-2">
              <a href="{{ route('Pegawai.Laporan.index') }}" class="btn btn-outline-secondary">Batal</a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload me-1"></i> Kirim Laporan
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="cardish p-3 p-md-4">
          <h6 class="mb-2">Detail Pelatihan</h6>
          <table class="table table-sm mb-0">
            <tr><th class="text-muted">Nama</th><td>{{ $sesi->nama_pelatihan }}</td></tr>
            <tr><th class="text-muted">Jenis</th><td>{{ $sesi->jenis_pelatihan ?? '-' }}</td></tr>
            <tr><th class="text-muted">Periode</th><td>{{ $mulai }} – {{ $selesai }}</td></tr>
            <tr><th class="text-muted">Status Sesi</th><td>{{ $sesi->status ?? '-' }}</td></tr>
          </table>
          <div class="alert alert-info mt-3 mb-0 small">
            Laporan hanya dapat dikirim setelah pelatihan dinyatakan selesai.
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

@push('scripts')
<script>
(function() {
  'use strict';
  
  // Bootstrap form validation
  const forms = document.querySelectorAll('.needs-validation');
  
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
        
        // Scroll to first invalid field
        const firstInvalid = form.querySelector(':invalid');
        if (firstInvalid) {
          firstInvalid.focus();
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
      form.classList.add('was-validated');
    }, false);
  });
  
  // Real-time validation feedback
  const inputs = document.querySelectorAll('.needs-validation input[required], .needs-validation textarea[required]');
  inputs.forEach(input => {
    input.addEventListener('blur', function() {
      if (this.value.trim() === '') {
        this.classList.add('is-invalid');
      } else {
        this.classList.remove('is-invalid');
      }
    });
    
    input.addEventListener('input', function() {
      if (this.value.trim() !== '') {
        this.classList.remove('is-invalid');
      }
    });
  });
})();
</script>
@endpush

@endsection
