{{-- resources/views/MenuUmum/Pelatihan/index.blade.php --}}
@extends('layouts.pegawai')

@section('title', 'Pelatihan Diklat — Daftar')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pelatihan.css') }}">
@endpush

@section('content')

{{-- HERO --}}
<section class="hero py-3 py-md-4">
  <div class="container">
    <div class="row gy-3">
      <div class="col-12">
        <h1 class="title mb-2">Tingkatkan Kompetensi Anda</h1>

        {{-- Search + Filter Button --}}
        <form method="GET" action="{{ url()->current() }}" id="filterForm" class="searchbar sticky-lg-top mb-2" aria-label="Cari & filter pelatihan">
          <div class="d-flex align-items-stretch gap-2 flex-column flex-sm-row">
            <div class="flex-grow-1">
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control" placeholder="Cari pelatihan..." value="{{ request('q') }}">
              </div>
            </div>

            {{-- Tombol buka panel filter --}}
            <button type="button" class="btn btn-outline-secondary btn-uniform" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter">
              <i class="bi bi-funnel me-1"></i> Filter
            </button>
          </div>
        </form>
        <p class="lead mb-0">Telusuri pelatihan aktif, lihat jadwal, lalu daftar.</p>
      </div>
    </div>
  </div>
</section>

{{-- OFFCANVAS FILTER --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilter" aria-labelledby="offcanvasFilterLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasFilterLabel">Filter Pelatihan</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
  </div>
  <div class="offcanvas-body">
    {{-- Semua kontrol di bawah submit ke form utama via form="filterForm" --}}
    <div class="mb-3">
      <label for="f-jenis" class="form-label">Jenis Pelatihan</label>
      <select id="f-jenis" name="jenis" class="form-select" form="filterForm">
        <option value="">Semua</option>
        @foreach(($jenisList ?? []) as $j)
          <option value="{{ $j }}" @selected(request('jenis')==$j)>{{ $j }}</option>
        @endforeach
      </select>
    </div>

    <div class="row g-2">
      <div class="col-12">
        <label for="f-provinsi" class="form-label">Provinsi</label>
        <select id="f-provinsi" name="provinsi" class="form-select" form="filterForm" data-selected-provinsi="{{ (string)request('provinsi') }}">
          <option value="">Semua</option>
          @foreach(($provinsis ?? []) as $p)
            <option value="{{ $p->id }}" @selected(request('provinsi')==$p->id)>{{ $p->nama }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-12">
        <label for="f-kota" class="form-label">Kota/Kabupaten</label>
        <select id="f-kota" name="kota" class="form-select" form="filterForm" data-selected-kota="{{ (string)request('kota') }}" {{ request('provinsi') ? '' : 'disabled' }}>
          <option value="">Semua</option>
          {{-- opsi kota diisi via JS sesuai provinsi --}}
        </select>
      </div>
    </div>

    <hr class="my-3">

    <div class="row g-2">
      <div class="col-6">
        <label for="f-bulan" class="form-label">Bulan</label>
        <select id="f-bulan" name="bulan" class="form-select" form="filterForm">
          <option value="">Semua</option>
          @foreach(($bulanList ?? []) as $num => $label)
            <option value="{{ $num }}" @selected((int)request('bulan') === (int)$num)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-6">
        <label for="f-tahun" class="form-label">Tahun</label>
        <select id="f-tahun" name="tahun" class="form-select" form="filterForm">
          <option value="">Semua</option>
          @foreach(($years ?? []) as $yy)
            <option value="{{ $yy }}" @selected((int)request('tahun') === (int)$yy)>{{ $yy }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
      <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
      </a>
      <div class="d-flex gap-2">
        <button class="btn btn-light" type="button" data-bs-dismiss="offcanvas">Tutup</button>
        <button class="btn btn-primary" type="submit" form="filterForm" id="btnFilter">
          <i class="bi bi-funnel me-1"></i> Terapkan
        </button>
      </div>
    </div>
  </div>
</div>

{{-- LIST --}}
<section id="list" class="py-3 py-md-4">
  <div class="container">
    <div class="row g-3 g-md-4">

      @php
        $hasOngoing      = $hasOngoing      ?? false;
        $myRegisteredIds = $myRegisteredIds ?? [];
      @endphp

      @forelse($trainings as $training)
        @php
          $kuota    = (int)($training->kuota ?? 0);
          $terpakai = (int)($training->peserta_terpakai ?? $training->peserta_registered ?? 0);
          $sisa       = $kuota > 0 ? max(0, $kuota - $terpakai) : null;
          $statusRaw  = $training->status ?? 'aktif';
          $jenis      = $training->jenis_pelatihan ?? '-';
          $lokasi     = $training->lokasi ?? null;
          $mulai      = !empty($training->tanggal_mulai)   ? \Illuminate\Support\Carbon::parse($training->tanggal_mulai)   : null;
          $selesai    = !empty($training->tanggal_selesai) ? \Illuminate\Support\Carbon::parse($training->tanggal_selesai) : null;
          $tglMulai   = $mulai   ? $mulai->isoFormat('D MMM Y')   : null;
          $tglSelesai = $selesai ? $selesai->isoFormat('D MMM Y') : null;
          $rentangTanggal = $tglMulai && $tglSelesai ? "{$tglMulai} – {$tglSelesai}" : ($tglMulai ?? ($tglSelesai ?? '-'));
          $deskripsi = \Illuminate\Support\Str::limit($training->informasi_pelatihan ?? $training->detail_pelatihan ?? $training->deskripsi ?? '', 140);
          
          $level = 'high';
          if ($kuota > 0) {
            $ratio = $terpakai / max(1,$kuota);
            $level = $ratio >= .9 ? 'low' : ($ratio >= .65 ? 'mid' : 'high');
          }

          $alreadyRegistered       = in_array($training->id, $myRegisteredIds);
          $blockBecauseOngoingRule = ($hasOngoing && !$alreadyRegistered);

          $isHMinus7Closed = false;
          if ($mulai) {
            $cutoff = $mulai->copy()->startOfDay()->subDays(7);
            $isHMinus7Closed = now()->startOfDay()->greaterThanOrEqualTo($cutoff);
          }

          $isKuotaFull = $kuota > 0 && $terpakai >= $kuota;
          $isClosed     = ($statusRaw !== 'aktif') || $isHMinus7Closed || $isKuotaFull;
          $statusLabel  = $isClosed ? 'close' : 'aktif';
          $statusClass  = $isClosed ? 'status-tutup' : 'status-aktif';

          $disableReason = null;
          if ($isKuotaFull) {
            $disableReason = 'Kuota penuh';
          } elseif ($statusRaw !== 'aktif') {
            $disableReason = 'Pendaftaran ditutup';
          } elseif ($isHMinus7Closed) {
            $disableReason = 'Pendaftaran ditutup mulai H-7';
          } elseif ($blockBecauseOngoingRule) {
            $disableReason = 'Anda sedang mengikuti pelatihan lain';
          }
        @endphp

        <div class="col-12 col-md-6 col-xl-4">
          <article class="card course-card h-100" aria-labelledby="title-{{ $training->id }}">
            <header class="course-head">
              <h2 class="h6 mb-0" id="title-{{ $training->id }}" title="{{ $training->nama_pelatihan ?? '-' }}">
                <i class="bi bi-journal-text me-1"></i> {{ $training->nama_pelatihan ?? '-' }}
              </h2>
              <span class="status-pill {{ $statusClass }}">
                <i class="bi bi-activity me-1"></i>{{ $statusLabel }}
              </span>
            </header>

            <div class="card-body d-flex flex-column">
              <div class="meta mb-2">
                <span class="chip" title="Jenis Pelatihan"><i class="bi bi-layers"></i> {{ $jenis }}</span>
                <span class="chip" title="Jadwal"><i class="bi bi-calendar-event"></i> {{ $rentangTanggal }}</span>
                @if($lokasi)
                  <span class="chip" title="Lokasi"><i class="bi bi-geo-alt"></i> {{ $lokasi }}</span>
                @endif
                @if($isHMinus7Closed)
                  <span class="chip" title="Pendaftaran ditutup H-7"><i class="bi bi-lock"></i> H-7 ditutup</span>
                @endif
              </div>

              @if($deskripsi)
                <p class="text-secondary mb-3 text-truncate-2 small">{{ $deskripsi }}</p>
              @endif

              <div class="card-actions mt-auto">
                <div class="card-actions-row">
                  <span class="badge badge-cap" data-level="{{ $level }}" title="Terpakai / Kuota">
                    <i class="bi bi-people me-1"></i>
                    {{ $kuota > 0 ? "{$terpakai}/{$kuota}" : 'Tanpa Batas' }}
                  </span>

                  <div class="card-actions-buttons">
                    @if(!empty($training->file_pelatihan))
                      <a class="btn btn-outline-success btn-sm" target="_blank" rel="noopener"
                         href="{{ asset('storage/' . ltrim($training->file_pelatihan,'/')) }}" 
                         aria-label="Buka file pelatihan" data-bs-toggle="tooltip" title="Berkas">
                        <i class="bi bi-file-earmark-text"></i>
                      </a>
                    @endif

                    <a href="{{ route('Pelatihan.show', ['id' => $training->id]) }}"
                       class="btn btn-outline-primary btn-sm" aria-label="Lihat detail" 
                       data-bs-toggle="tooltip" title="Detail">
                      <i class="bi bi-eye"></i>
                    </a>

                    @if($disableReason)
                      <button type="button" class="btn btn-ikut btn-sm full" disabled
                              data-bs-toggle="tooltip" title="{{ $disableReason }}">
                        Tidak Bisa Daftar
                      </button>
                    @else
                      @auth('pegawais')
                        <button type="button"
                                class="btn btn-ikut btn-sm btn-daftar"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalKonfirmasi"
                                data-session-id="{{ $training->id }}"
                                data-nama-pelatihan="{{ $training->nama_pelatihan }}"
                                data-jenis="{{ $jenis }}"
                                data-lokasi="{{ $lokasi }}"
                                data-tanggal="{{ $rentangTanggal }}"
                                aria-label="Daftar">
                          <i class="bi bi-plus-circle"></i> Daftar
                        </button>
                      @else
                        <button type="button"
                                class="btn btn-ikut btn-sm btn-daftar-guest"
                                aria-label="Daftar">
                          <i class="bi bi-plus-circle"></i> Daftar
                        </button>
                      @endauth
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </article>
        </div>
      @empty
        <div class="col-12">
          <div class="empty text-center">
            <div class="display-6 mb-2" aria-hidden="true">😕</div>
            <h5 class="mb-1">Belum ada pelatihan tersedia</h5>
            <p class="text-muted mb-3">Coba ubah kata kunci atau filter. Pelatihan baru ditambahkan secara berkala.</p>
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-counterclockwise me-1"></i> Muat ulang
            </a>
          </div>
        </div>
      @endforelse
    </div>

    @if($trainings instanceof \Illuminate\Contracts\Pagination\Paginator || $trainings instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
      <nav class="mt-4 d-flex justify-content-center" aria-label="Pagination">
        {{ $trainings->fragment('list')->onEachSide(1)->links('pagination::bootstrap-5') }}
      </nav>
    @endif

  </div>
</section>

{{-- Modal Konfirmasi --}}
@auth('pegawais')
  @php $akun = auth('pegawais')->user(); @endphp
  <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="modalKonfirmasiLabel" class="modal-title">Konfirmasi Pendaftaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="card h-100 border-0">
                <div class="card-header bg-white border-bottom-0"><strong>Data Pegawai</strong></div>
                <div class="card-body pt-0">
                  <table class="table table-sm mb-0">
                    <tr><th class="text-muted">Nama</th><td>{{ $akun->nama ?? '-' }}</td></tr>
                    <tr><th class="text-muted">NIP</th><td>{{ $akun->nip ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Email</th><td>{{ $akun->email ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Unit Kerja</th><td>{{ optional($akun->unitKerja)->unitkerja ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Jabatan</th><td>{{ $akun->jabatan ?? '-' }}</td></tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card h-100 border-0">
                <div class="card-header bg-white border-bottom-0"><strong>Detail Pelatihan</strong></div>
                <div class="card-body pt-0">
                  <table class="table table-sm mb-0">
                    <tr><th class="text-muted">Nama</th><td id="m-nama"></td></tr>
                    <tr><th class="text-muted">Jenis</th><td id="m-jenis"></td></tr>
                    <tr><th class="text-muted">Lokasi</th><td id="m-lokasi"></td></tr>
                    <tr><th class="text-muted">Jadwal</th><td id="m-tanggal"></td></tr>
                  </table>
                  <div class="alert alert-warning mt-3 mb-0 small">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Dengan menekan <b>Konfirmasi</b>, Anda menyatakan data sudah benar dan siap mengikuti pelatihan ini.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-between flex-column flex-md-row gap-2">
          <small class="text-muted text-center text-md-start">Catatan: ASN tidak dapat mengikuti dua pelatihan dengan jadwal bertumpuk.</small>
          <form id="formJoin" method="POST" class="m-0">
            @csrf
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Konfirmasi & Ikuti</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endauth
@endsection

@push('scripts')
<script>
  // Set authentication status and URLs
  window.authPegawaisCheck = @json(auth('pegawais')->check());
  window.loginUrl = "{{ route('Pegawai.login') }}";
  window.joinRouteTemplate = "{{ url('/pelatihan/___ID___/join') }}";
  
  // Set kota list for dependent select
  window.kotasList = @json($kotas ?? []);
  
  // Set flash messages
  window.flashMessages = {
    success: @json(session('success')),
    error: @json(session('error')),
    info: @json(session('info')),
    warning: @json(session('warning'))
  };
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/pelatihan-index.js') }}"></script>
<script>
  // Handle form submission
  document.addEventListener('DOMContentLoaded', function() {
    const formJoin = document.getElementById('formJoin');
    if (formJoin) {
      formJoin.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Disable submit button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        
        // Get form data
        const formData = new FormData(this);
        const actionUrl = this.action;
        
        // Submit via fetch
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
            const modalEl = document.getElementById('modalKonfirmasi');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            
            // Show success message
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: data.message || 'Anda berhasil mendaftar pelatihan ini.',
              confirmButtonText: 'OK',
              confirmButtonColor: '#0d6efd'
            }).then(() => {
              window.location.reload();
            });
          } else {
            throw new Error(data.message || 'Terjadi kesalahan');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat mendaftar.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'
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
@endpush