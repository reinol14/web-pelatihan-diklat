<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $session->nama_pelatihan ?? 'Detail Pelatihan' }}</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --gradient-info: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --line: #e9ecef;
      --muted: #6c757d;
      --primary: #667eea;
      --bg-soft: #f8f9fa;
      --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
      --shadow-md: 0 4px 20px rgba(0,0,0,0.12);
      --shadow-lg: 0 8px 30px rgba(0,0,0,0.15);
    }

    body { 
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      font-size: 15px;
      line-height: 1.6;
    }

    /* HERO */
    .hero {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 2rem 0;
      box-shadow: var(--shadow-md);
      margin-bottom: 2rem;
    }

    .hero .btn-back {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      background: rgba(255,255,255,0.1);
      backdrop-filter: blur(10px);
    }

    .hero .btn-back:hover {
      background: rgba(255,255,255,0.2);
      transform: translateX(-5px);
    }

    /* CONTENT CARD */
    .content-card {
      border: none;
      border-radius: 20px;
      background: white;
      padding: 2rem;
      box-shadow: var(--shadow-md);
      transition: all 0.3s ease;
      margin-bottom: 1.5rem;
    }

    .content-card:hover {
      box-shadow: var(--shadow-lg);
      transform: translateY(-5px);
    }

    /* MODERN TITLE */
    .hero-title {
      font-size: 2rem;
      font-weight: 800;
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1rem;
    }

    /* CHIPS/TAGS */
    .chip {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
      border: 1px solid rgba(102, 126, 234, 0.2);
      border-radius: 999px;
      padding: 0.4rem 1rem;
      font-size: 0.85rem;
      color: var(--primary);
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      transition: all 0.3s ease;
    }

    .chip:hover {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }

    .chip i {
      margin-right: 0.4rem;
    }

    /* INFO BOX */
    .info-box {
      border-radius: 16px;
      padding: 1.25rem;
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      border: 1px solid var(--line);
      transition: all 0.3s ease;
      height: 100%;
    }

    .info-box:hover {
      background: linear-gradient(135deg, #f8faff 0%, #e8ecff 100%);
      border-color: rgba(102, 126, 234, 0.3);
      transform: translateY(-3px);
      box-shadow: var(--shadow-sm);
    }

    .info-box .label {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--muted);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .info-box .value {
      font-size: 1rem;
      font-weight: 600;
      color: #2d3748;
    }

    /* STATUS PILLS */
    .status-pill {
      padding: 0.5rem 1.25rem;
      border-radius: 999px;
      font-size: 0.85rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      box-shadow: var(--shadow-sm);
    }

    .status-aktif {
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      color: white;
    }

    .status-tutup {
      background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
      color: white;
    }

    /* PROGRESS BAR */
    .progress {
      height: 12px;
      border-radius: 20px;
      background: #e9ecef;
      overflow: hidden;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }

    .progress-bar {
      background: var(--gradient-primary);
      border-radius: 20px;
      transition: width 0.6s ease;
      box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
    }

    /* SIDEBAR ACTIONS */
    .sidebar-actions {
      position: sticky;
      top: 2rem;
    }

    .action-card {
      border: none;
      border-radius: 20px;
      background: white;
      padding: 1.5rem;
      box-shadow: var(--shadow-md);
      margin-bottom: 1.5rem;
    }

    .action-card h6 {
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 0.5rem;
    }

    /* BUTTONS */
    .btn-primary-gradient {
      background: var(--gradient-primary);
      border: none;
      color: white;
      font-weight: 600;
      padding: 0.875rem 1.5rem;
      border-radius: 12px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary-gradient:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
      color: white;
    }

    .btn-secondary-gradient {
      background: linear-gradient(135deg, #a8b3c5 0%, #8b95a8 100%);
      border: none;
      color: white;
      font-weight: 600;
      padding: 0.875rem 1.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(139, 149, 168, 0.2);
    }

    .btn-outline-primary-gradient {
      border: 2px solid #667eea;
      color: #667eea;
      background: transparent;
      font-weight: 600;
      padding: 0.75rem 1.5rem;
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .btn-outline-primary-gradient:hover {
      background: var(--gradient-primary);
      color: white;
      border-color: transparent;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    /* DESCRIPTION */
    .description-section {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
      padding: 1.5rem;
      border-radius: 16px;
      border-left: 4px solid #667eea;
      margin: 1.5rem 0;
    }

    .description-section h5 {
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 1rem;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      .hero-title {
        font-size: 1.5rem;
      }

      .content-card {
        padding: 1.5rem;
      }

      .chip {
        font-size: 0.75rem;
        padding: 0.3rem 0.75rem;
      }

      .info-box {
        padding: 1rem;
      }

      .sidebar-actions {
        position: static;
        margin-top: 1.5rem;
      }
    }

    @media (max-width: 576px) {
      .hero {
        padding: 1.5rem 0;
      }

      .hero-title {
        font-size: 1.25rem;
      }

      .content-card {
        padding: 1.25rem;
        border-radius: 16px;
      }

      .chip {
        font-size: 0.7rem;
        padding: 0.25rem 0.6rem;
      }

      .status-pill {
        padding: 0.4rem 1rem;
        font-size: 0.75rem;
      }

      .btn-primary-gradient,
      .btn-secondary-gradient {
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
      }
    }
  </style>
</head>

<body>

{{-- HERO --}}
<section class="hero">
  <div class="container">
    <a href="{{ route('Pelatihan.index') }}" class="btn-back d-inline-flex align-items-center">
      <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Pelatihan
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

$mulai    = $session->tanggal_mulai ? Carbon::parse($session->tanggal_mulai) : null;
$selesai  = $session->tanggal_selesai ? Carbon::parse($session->tanggal_selesai) : null;
$mulaiStr = $mulai ? $mulai->isoFormat('D MMM Y') : null;
$selesaiStr = $selesai ? $selesai->isoFormat('D MMM Y') : null;
$rentang  = $mulaiStr && $selesaiStr ? "$mulaiStr – $selesaiStr" : ($mulaiStr ?? ($selesaiStr ?? '-'));

$kuota    = (int)($session->kuota ?? 0);
$terisi   = (int)($session->peserta_registered ?? 0);
$sisa     = max(0, $kuota - $terisi);
$status   = $session->status ?? 'aktif';

$fileUrl  = !empty($session->file_pelatihan) ? asset('storage/' . ltrim($session->file_pelatihan,'/')) : null;

$progress = $kuota > 0 ? round(($terisi / $kuota) * 100) : 0;

// Check if H-7 closed
$isHMinus7Closed = false;
if ($mulai) {
  $cutoff = $mulai->copy()->startOfDay()->subDays(7);
  $isHMinus7Closed = now()->startOfDay()->greaterThanOrEqualTo($cutoff);
}

$isKuotaFull = $kuota > 0 && $terisi >= $kuota;
$isClosed = ($status !== 'aktif') || $isHMinus7Closed || $isKuotaFull;

// Check if user already registered or has ongoing training
$hasOngoing = $hasOngoing ?? false;
$myRegisteredIds = $myRegisteredIds ?? [];
$alreadyRegistered = in_array($session->id, $myRegisteredIds);
$blockBecauseOngoingRule = ($hasOngoing && !$alreadyRegistered);

$disableReason = null;
if ($isKuotaFull) {
  $disableReason = 'Kuota penuh';
} elseif ($status !== 'aktif') {
  $disableReason = 'Pendaftaran ditutup';
} elseif ($isHMinus7Closed) {
  $disableReason = 'Pendaftaran ditutup mulai H-7';
} elseif ($blockBecauseOngoingRule) {
  $disableReason = 'Anda sedang mengikuti pelatihan lain';
}

$canRegister = !$isClosed && !$blockBecauseOngoingRule;
@endphp

<main class="container my-4 my-md-5">
  <div class="row g-4">

    <div class="col-lg-8">
      <article class="content-card">

        {{-- Judul + Status --}}
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
          <h1 class="hero-title mb-0">{{ $nama }}</h1>
          <span class="status-pill {{ $isClosed ? 'status-tutup' : 'status-aktif' }}">
            <i class="bi bi-{{ $isClosed ? 'lock-fill' : 'check-circle-fill' }}"></i>
            {{ $isClosed ? 'DITUTUP' : 'TERBUKA' }}
          </span>
        </div>

        {{-- Chips Meta --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
          <span class="chip"><i class="bi bi-layers"></i> {{ $jenis }}</span>
          <span class="chip"><i class="bi bi-broadcast"></i> {{ $metode }}</span>
          <span class="chip"><i class="bi bi-calendar-event"></i> {{ $rentang }}</span>
          <span class="chip"><i class="bi bi-geo-alt"></i> {{ $lokasi }}</span>
          @if($isHMinus7Closed)
            <span class="chip"><i class="bi bi-exclamation-triangle"></i> H-7 Ditutup</span>
          @endif
        </div>

        {{-- Deskripsi --}}
        <div class="description-section">
          <h5><i class="bi bi-info-circle me-2"></i>Deskripsi Pelatihan</h5>
          <p class="mb-0 text-muted">{{ $info }}</p>
        </div>

        @if($fileUrl)
        <a href="{{ $fileUrl }}" class="btn btn-outline-primary-gradient mt-3" target="_blank">
          <i class="bi bi-file-earmark-text me-2"></i> Unduh File Pelatihan
        </a>
        @endif

        <hr class="my-4">

        {{-- Informasi Detail --}}
        <h5 class="mb-4 fw-bold"><i class="bi bi-clipboard-data me-2"></i>Informasi Detail</h5>
        <div class="row g-3">

          <div class="col-md-6">
            <div class="info-box">
              <div class="label"><i class="bi bi-calendar3 me-1"></i>Tanggal Pelaksanaan</div>
              <div class="value">{{ $rentang }}</div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-box">
              <div class="label"><i class="bi bi-people me-1"></i>Kuota Peserta</div>
              <div class="value mb-2">{{ $terisi }} / {{ $kuota > 0 ? $kuota : 'Tanpa Batas' }}</div>
              
              {{-- Progress Bar --}}
              @if($kuota > 0)
              <div class="progress mb-2">
                <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"
                     aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <div class="small text-{{ $sisa > 0 ? 'success' : 'danger' }}">
                <i class="bi bi-{{ $sisa > 0 ? 'check-circle' : 'x-circle' }} me-1"></i>
                {{ $sisa > 0 ? "Sisa $sisa kuota" : 'Kuota penuh' }}
              </div>
              @else
              <div class="small text-success"><i class="bi bi-infinity me-1"></i>Tanpa batas peserta</div>
              @endif
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-box">
              <div class="label"><i class="bi bi-map me-1"></i>Provinsi</div>
              <div class="value">{{ $provinsis->firstWhere('id',$session->provinsi_id)->nama ?? '-' }}</div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-box">
              <div class="label"><i class="bi bi-pin-map me-1"></i>Kota/Kabupaten</div>
              <div class="value">{{ $kotas->firstWhere('id',$session->kota_id)->nama ?? '-' }}</div>
            </div>
          </div>

        </div>
      </article>
    </div>

    {{-- SIDEBAR ACTIONS --}}
    <div class="col-lg-4">
      <div class="sidebar-actions">
        <div class="action-card">
          <h6><i class="bi bi-hand-index me-2"></i>Aksi Cepat</h6>
          <p class="text-muted small mb-3">Daftar pelatihan atau lihat informasi lengkap</p>

          @auth('pegawais')
            @if($alreadyRegistered)
              <button class="btn btn-secondary-gradient w-100 mb-2" disabled>
                <i class="bi bi-check-circle me-2"></i> Sudah Terdaftar
              </button>
            @elseif($disableReason)
              <button class="btn btn-secondary-gradient w-100 mb-2" disabled 
                      title="{{ $disableReason }}">
                <i class="bi bi-lock me-2"></i> {{ $disableReason }}
              </button>
            @else
              <button type="button" 
                      class="btn btn-primary-gradient w-100 mb-2 btn-daftar"
                      data-bs-toggle="modal" 
                      data-bs-target="#modalKonfirmasi"
                      data-session-id="{{ $session->id }}"
                      data-nama-pelatihan="{{ $nama }}"
                      data-jenis="{{ $jenis }}"
                      data-lokasi="{{ $lokasi }}"
                      data-tanggal="{{ $rentang }}">
                <i class="bi bi-pencil-square me-2"></i> Daftar Sekarang
              </button>
            @endif
          @else
            <a href="{{ route('Pegawai.login') }}" class="btn btn-primary-gradient w-100 mb-2">
              <i class="bi bi-box-arrow-in-right me-2"></i> Login untuk Daftar
            </a>
          @endauth

          <a href="{{ route('Pelatihan.index') }}" class="btn btn-outline-primary-gradient w-100">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
          </a>
        </div>

        {{-- Info Card --}}
        <div class="action-card">
          <h6><i class="bi bi-info-circle me-2"></i>Informasi</h6>
          <div class="small text-muted">
            <p class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Verifikasi otomatis</p>
            <p class="mb-2"><i class="bi bi-shield-check text-primary me-2"></i>Data aman terlindungi</p>
            <p class="mb-0"><i class="bi bi-telephone text-info me-2"></i>Hubungi admin jika ada kendala</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

{{-- Modal Konfirmasi Pendaftaran --}}
@auth('pegawais')
  @php $akun = auth('pegawais')->user(); @endphp
  <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden;">
        <div class="modal-header" style="background: var(--gradient-primary); color: white; border: none;">
          <h5 id="modalKonfirmasiLabel" class="modal-title fw-bold">
            <i class="bi bi-check-circle me-2"></i>Konfirmasi Pendaftaran
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom-0 py-3">
                  <strong><i class="bi bi-person-badge me-2"></i>Data Pegawai</strong>
                </div>
                <div class="card-body pt-0">
                  <table class="table table-sm mb-0">
                    <tr><th class="text-muted border-0 py-2">Nama</th><td class="border-0 py-2">{{ $akun->nama ?? '-' }}</td></tr>
                    <tr><th class="text-muted border-0 py-2">NIP</th><td class="border-0 py-2">{{ $akun->nip ?? '-' }}</td></tr>
                    <tr><th class="text-muted border-0 py-2">Email</th><td class="border-0 py-2">{{ $akun->email ?? '-' }}</td></tr>
                    <tr><th class="text-muted border-0 py-2">Unit Kerja</th><td class="border-0 py-2">{{ optional($akun->unitKerja)->unitkerja ?? '-' }}</td></tr>
                    <tr><th class="text-muted border-0 py-2">Jabatan</th><td class="border-0 py-2">{{ $akun->jabatan ?? '-' }}</td></tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom-0 py-3">
                  <strong><i class="bi bi-journal-text me-2"></i>Detail Pelatihan</strong>
                </div>
                <div class="card-body pt-0">
                  <table class="table table-sm mb-0">
                    <tr><th class="text-muted border-0 py-2">Nama</th><td class="border-0 py-2" id="m-nama"></td></tr>
                    <tr><th class="text-muted border-0 py-2">Jenis</th><td class="border-0 py-2" id="m-jenis"></td></tr>
                    <tr><th class="text-muted border-0 py-2">Lokasi</th><td class="border-0 py-2" id="m-lokasi"></td></tr>
                    <tr><th class="text-muted border-0 py-2">Jadwal</th><td class="border-0 py-2" id="m-tanggal"></td></tr>
                  </table>
                  <div class="alert alert-warning mt-3 mb-0 small" style="border-radius: 12px; border-left: 4px solid #f5576c;">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Dengan menekan <b>Konfirmasi</b>, Anda menyatakan data sudah benar dan siap mengikuti pelatihan ini.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer border-0 d-flex justify-content-between flex-column flex-md-row gap-2 px-4 pb-4">
          <small class="text-muted text-center text-md-start">
            <i class="bi bi-info-circle me-1"></i>
            ASN tidak dapat mengikuti dua pelatihan dengan jadwal bertumpuk.
          </small>
          <form id="formJoin" method="POST" class="m-0">
            @csrf
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">
              Batal
            </button>
            <button type="submit" class="btn btn-primary-gradient" style="border-radius: 10px;">
              <i class="bi bi-check-circle me-2"></i>Konfirmasi & Ikuti
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Handle modal data population
    const modalElement = document.getElementById('modalKonfirmasi');
    if (modalElement) {
      modalElement.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const sessionId = button.getAttribute('data-session-id');
        const namaPelatihan = button.getAttribute('data-nama-pelatihan');
        const jenis = button.getAttribute('data-jenis');
        const lokasi = button.getAttribute('data-lokasi');
        const tanggal = button.getAttribute('data-tanggal');

        // Populate modal
        document.getElementById('m-nama').textContent = namaPelatihan;
        document.getElementById('m-jenis').textContent = jenis;
        document.getElementById('m-lokasi').textContent = lokasi;
        document.getElementById('m-tanggal').textContent = tanggal;

        // Set form action
        const form = document.getElementById('formJoin');
        form.action = `/pelatihan/${sessionId}/join`;
      });
    }

    // Handle form submission
    const formJoin = document.getElementById('formJoin');
    if (formJoin) {
      formJoin.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const actionUrl = this.action;

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';

        fetch(actionUrl, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
          },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(modalElement);
            modal.hide();

            // Show success message
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: data.message || 'Anda berhasil mendaftar pelatihan ini.',
              confirmButtonText: 'OK',
              confirmButtonColor: '#667eea',
              timer: 3000
            }).then(() => {
              // Reload page or redirect
              window.location.reload();
            });
          } else {
            throw new Error(data.message || 'Terjadi kesalahan');
          }
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat mendaftar.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#f5576c'
          });
        })
        .finally(() => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        });
      });
    }
  });
</script>

</body>
</html>
