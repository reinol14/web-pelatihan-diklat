<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Pelatihan</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --line:#e9ecef;
      --muted:#6c757d;
      --primary:#0b5ed7;
      --bg-soft:#f7f8fa;
    }

    body{ background:var(--bg-soft); font-size:15px; }

    /* HERO */
    .hero{
      background:white;
      border-bottom:1px solid var(--line);
      padding:1.2rem 0;
    }

    .hero-title{
      font-size:1.8rem;
      font-weight:700;
    }

    /* TAGS */
    .chip{
      background:#f6f7fb;
      border:1px solid #eef0f6;
      border-radius:999px;
      padding:.35rem .75rem;
      font-size:.85rem;
      color:var(--muted);
    }

    /* CARD */
    .content-card{
      border:1px solid var(--line);
      border-radius:14px;
      background:white;
      padding:1.5rem;
    }

    .info-box{
      border-radius:12px;
      padding:1rem;
      background:#fff;
      border:1px solid #eee;
      transition:.2s;
    }
    .info-box:hover{
      background:#f8faff;
      border-color:#dbe6ff;
    }

    /* STATUS */
    .status-pill{
      padding:.35rem .75rem;
      border-radius:999px;
      font-size:.8rem;
      font-weight:600;
      border:1px solid var(--line);
    }
    .status-aktif{
      background:#e9f3ff;
      color:#0b5ed7;
      border-color:#cfe4ff;
    }
    .status-tutup{
      background:#fff0f0;
      color:#b42318;
      border-color:#ffcccc;
    }

    /* PROGRESS KUOTA */
    .progress {
      height: 10px;
      border-radius: 20px;
    }
  </style>
</head>

<body>

{{-- HERO --}}
<section class="hero">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="{{ route('Pelatihan.index') }}" class="text-decoration-none">
      <i class="bi bi-arrow-left me-1"></i> Kembali ke daftar
    </a>
  </div>
</section>

@php
use Illuminate\Support\Carbon;

$nama     = $session->nama_pelatihan ?? '-';
$jenis    = $session->jenis_pelatihan ?? ($session->jenis ?? '-');
$metode   = $session->metode_pelatihan ?? ($session->metode ?? '-');
$lokasi   = $session->lokasi ?? '-';
$info     = $session->deskripsi ?? '-';

$mulai    = $session->tanggal_mulai ? Carbon::parse($session->tanggal_mulai)->isoFormat('D MMM Y') : null;
$selesai  = $session->tanggal_selesai ? Carbon::parse($session->tanggal_selesai)->isoFormat('D MMM Y') : null;
$rentang  = $mulai && $selesai ? "$mulai – $selesai" : ($mulai ?? ($selesai ?? '-'));

$kuota    = (int)($session->kuota ?? 0);
$terisi   = (int)($session->peserta_registered ?? 0);
$sisa     = max(0, $kuota - $terisi);
$status   = $session->status ?? 'aktif';

$fileUrl  = !empty($session->file_pelatihan) ? asset('storage/' . ltrim($session->file_pelatihan,'/')) : null;

$progress = $kuota > 0 ? round(($terisi / $kuota) * 100) : 0;
@endphp

<main class="container my-4">
  <div class="row g-4">

    <div class="col-lg-8">
      <article class="content-card">

        {{-- Judul + Status --}}
        <div class="d-flex justify-content-between align-items-start mb-3">
          <h1 class="hero-title">{{ $nama }}</h1>
          <span class="status-pill {{ $status=='aktif' ? 'status-aktif' : 'status-tutup' }}">
            {{ strtoupper($status) }}
          </span>
        </div>

        {{-- Chips Meta --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
          <span class="chip"><i class="bi bi-layers me-1"></i> {{ $jenis }}</span>
          <span class="chip"><i class="bi bi-broadcast me-1"></i> {{ $metode }}</span>
          <span class="chip"><i class="bi bi-calendar-event me-1"></i> {{ $rentang }}</span>
          <span class="chip"><i class="bi bi-geo-alt me-1"></i> {{ $lokasi }}</span>
        </div>

        {{-- Deskripsi --}}
        <h5 class="mt-3">Deskripsi Pelatihan</h5>
        <p class="text-muted">{{ $info }}</p>

        @if($fileUrl)
        <a href="{{ $fileUrl }}" class="btn btn-outline-primary btn-sm mt-2" target="_blank">
          <i class="bi bi-file-earmark-text me-1"></i> Lihat File Pelatihan
        </a>
        @endif

        <hr class="my-4">

        {{-- Informasi Tambahan --}}
        <h5>Informasi Tambahan</h5>
        <div class="row g-3 mt-1">

          <div class="col-md-6">
            <div class="info-box">
              <div class="small text-muted">Tanggal Pelaksanaan</div>
              <div class="fw-semibold">{{ $rentang }}</div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-box">
              <div class="d-flex justify-content-between">
                <span class="small text-muted">Kuota Peserta</span>
                <span class="small">{{ $terisi }}/{{ $kuota }}</span>
              </div>

              {{-- Progress Bar --}}
              @if($kuota > 0)
              <div class="progress mt-2">
                <div class="progress-bar bg-primary" role="progressbar"
                     style="width: {{ $progress }}%"></div>
              </div>
              <div class="small text-muted mt-1">Sisa kuota: {{ $sisa }}</div>
              @else
              <div class="small">Tanpa batas peserta</div>
              @endif
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-box">
              <div class="small text-muted">Provinsi</div>
              <div>{{ $provinsis->firstWhere('id',$session->provinsi_id)->nama ?? '-' }}</div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-box">
              <div class="small text-muted">Kota</div>
              <div>{{ $kotas->firstWhere('id',$session->kota_id)->nama ?? '-' }}</div>
            </div>
          </div>

        </div>
      </article>
    </div>

    {{-- ---- SIDEBAR AKSI (Jika ada tombol daftar dll) ---- --}}
    <div class="col-lg-4">
      <div class="content-card p-3">

        <h6 class="mb-2">Aksi</h6>
        <p class="text-muted small">Gunakan tombol di bawah untuk melakukan tindakan.</p>

        {{-- Contoh tombol daftar --}}
        @if($status == 'aktif' && $sisa > 0)
        <a href="#" class="btn btn-primary w-100 mb-2">
          <i class="bi bi-pencil-square me-1"></i> Daftar Pelatihan
        </a>
        @else
        <button class="btn btn-secondary w-100 mb-2" disabled>
          <i class="bi bi-lock me-1"></i> Pendaftaran Ditutup
        </button>
        @endif

      </div>
    </div>

  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
