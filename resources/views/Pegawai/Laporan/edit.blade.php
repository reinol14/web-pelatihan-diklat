@extends('layouts.pegawai')

@section('title', (isset($laporan) ? 'Perbaiki Laporan Pelatihan' : 'Kirim Laporan Pelatihan'))

@push('styles')
<style>
  :root{ --line:#e9ecef; --muted:#6c757d; }
  .cardish{ border:1px solid var(--line); border-radius:14px; background:#fff; }
  .hero{ background:#fff; border-bottom:1px solid var(--line); }
</style>
@endpush

@section('content')

@php
  // ====== Safe defaults & mode detection ======
  /** @var \stdClass|null $sesi */
  /** @var \stdClass|null $laporan */
  $mode = ($mode ?? null) ?: (isset($laporan) ? 'edit' : 'create');

  // Normalisasi file lama (karena di DB ada yang pakai 'file_path' dan ada yang pakai 'file')
  $rawFile  = $laporan->file_path ?? $laporan->file ?? null;
  $fileUrl  = null;
  if ($rawFile) {
    // Jika sudah berupa 'storage/...' langsung asset(); jika path relatif di disk public, prefiks 'storage/'
    $fileUrl = str_starts_with($rawFile, 'http')
      ? $rawFile
      : (str_starts_with($rawFile, 'storage/')
          ? asset($rawFile)
          : asset('storage/'.ltrim($rawFile, '/')));
  }

  // Tanggal sesi (aman jika null)
  $mulai   = !empty($sesi?->tanggal_mulai)   ? \Illuminate\Support\Carbon::parse($sesi->tanggal_mulai)->isoFormat('D MMM Y') : '-';
  $selesai = !empty($sesi?->tanggal_selesai) ? \Illuminate\Support\Carbon::parse($sesi->tanggal_selesai)->isoFormat('D MMM Y') : '-';

  // Action & method form
  $action = $mode === 'edit'
      ? route('Pegawai.Laporan.update', $laporan->id)
      : route('Pegawai.Laporan.store',  $sesi->id);
@endphp

<section class="hero py-3">
  <div class="container">
    <a href="{{ route('Pegawai.Laporan.index') }}" class="text-decoration-none">&larr; Kembali ke daftar laporan</a>
  </div>
</section>

<section class="py-4">
  <div class="container">
    <h1 class="h4 mb-3">
      {{ $mode === 'edit' ? 'Perbaiki Laporan — ' : 'Kirim Laporan — ' }}{{ $sesi?->nama_pelatihan ?? '-' }}
    </h1>

    {{-- Error validasi --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <div class="fw-semibold mb-1">Periksa kembali isian Anda:</div>
        <ul class="mb-0">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row g-3 g-lg-4">
      <div class="col-lg-7">
        <div class="cardish p-3 p-md-4">

          <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            <div class="mb-3">
              <label class="form-label">Judul Laporan <span class="text-danger">*</span></label>
              <input
                type="text"
                name="judul"
                class="form-control"
                value="{{ old('judul', $laporan->judul ?? '') }}"
                required
                maxlength="150"
                placeholder="Contoh: Implementasi Manajemen Waktu di Unit X">
              <div class="form-text">Maks 150 karakter.</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Ringkasan <span class="text-danger">*</span></label>
              <textarea
                name="ringkasan"
                class="form-control"
                rows="7"
                required
                maxlength="5000"
                placeholder="Tuliskan ringkasan materi, hasil, dan rencana tindak lanjut...">{{ old('ringkasan', $laporan->ringkasan ?? '') }}</textarea>
              <div class="form-text">Maks 5000 karakter.</div>
            </div>

            <div class="mb-3">
              <label class="form-label">
                {{ $mode === 'edit' ? 'Ganti Lampiran (opsional)' : 'Lampiran (opsional)' }}
              </label>

              @if($mode === 'edit' && $fileUrl)
                <div class="mb-2">
                  <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-earmark-text me-1"></i> Lihat Lampiran Saat Ini
                  </a>
                </div>
              @endif

              <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
              <div class="form-text">PDF/DOC/DOCX, maks 5MB.</div>
            </div>

            <div class="mb-3">
              <label class="form-label">
                {{ $mode === 'edit' ? 'Ganti Sertifikat (opsional)' : 'Sertifikat (opsional)' }}
              </label>

              @if($mode === 'edit' && !empty($laporan->sertifikat))
                @php
                  $sertifikatUrl = str_starts_with($laporan->sertifikat, 'http')
                    ? $laporan->sertifikat
                    : (str_starts_with($laporan->sertifikat, 'storage/')
                        ? asset($laporan->sertifikat)
                        : asset('storage/'.ltrim($laporan->sertifikat, '/')));
                @endphp
                <div class="mb-2">
                  <a href="{{ $sertifikatUrl }}" target="_blank" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-award me-1"></i> Lihat Sertifikat Saat Ini
                  </a>
                </div>
              @endif

              <input type="file" name="sertifikat" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
              <div class="form-text">PDF/JPG/PNG, maks 5MB.</div>
            </div>

            <div class="d-flex gap-2">
              <a href="{{ route('Pegawai.Laporan.index') }}" class="btn btn-outline-secondary">Batal</a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload me-1"></i>
                {{ $mode === 'edit' ? 'Simpan Perbaikan & Kirim Ulang' : 'Kirim Laporan' }}
              </button>
            </div>
          </form>

        </div>
      </div>

      <div class="col-lg-5">
        <div class="cardish p-3 p-md-4">
          <h6 class="mb-2">Detail Pelatihan</h6>
          <table class="table table-sm mb-0">
            <tr><th class="text-muted">Nama</th><td>{{ $sesi?->nama_pelatihan ?? '-' }}</td></tr>
            <tr><th class="text-muted">Jenis</th><td>{{ $sesi?->jenis_pelatihan ?? '-' }}</td></tr>
            <tr><th class="text-muted">Periode</th><td>{{ $mulai }} – {{ $selesai }}</td></tr>
            <tr><th class="text-muted">Status Sesi</th><td>{{ $sesi?->status ?? '-' }}</td></tr>
            @if($mode === 'edit' && isset($laporan->status))
              <tr><th class="text-muted">Status Laporan</th><td>{{ ucfirst($laporan->status) }}</td></tr>
            @endif
          </table>

          @if($mode === 'create')
            <div class="alert alert-info mt-3 mb-0 small">
              Laporan hanya dapat dikirim setelah pelatihan dinyatakan selesai.
            </div>
          @else
            <div class="alert alert-warning mt-3 mb-0 small">
              Perbaiki data lalu kirim ulang. Status akan kembali <b>pending</b> setelah disimpan.
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
