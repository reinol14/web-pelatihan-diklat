
{{-- resources/views/MenuUmum/Pelatihan/index.blade.php --}}
@extends('layouts.pegawai')

@section('title', 'Pelatihan Diklat — Daftar')

@push('styles')
<style>
  :root{ --brand:#6f42c1; --brand-2:#0d6efd; --surface:#ffffff; --line:#e9ecef; --text-muted:#6c757d; }
  .hero{background:#fff;border-bottom:1px solid var(--line);}
  .hero .title{font-weight:700;}
  .hero .lead{color:var(--text-muted);}
  .searchbar{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:.875rem;box-shadow:0 .25rem .75rem rgba(16,24,40,.04);}
  @media (min-width:992px){ .searchbar.sticky-lg-top{ top:.75rem; z-index:101; } }
  .input-group .form-control,.input-group-text{ min-height:44px; }
  .input-group-text{ border-right:0; }
  .input-group .form-control{ border-left:0; }
  .form-select:focus,.form-control:focus{ box-shadow:0 0 0 .25rem rgba(13,110,253,.15); border-color:#b8d2ff; }
  .btn-uniform{ display:inline-flex; align-items:center; gap:.5rem; white-space:nowrap; min-height:44px; padding:0 .95rem; font-weight:600; }
  .btn .bi{ margin-right:.25rem }
  .course-card{ border:1px solid var(--line); border-radius:16px; background:var(--surface); transition:transform .15s ease, box-shadow .15s ease, border-color .15s; }
  .course-card:hover{ transform:translateY(-2px); box-shadow:0 10px 24px rgba(16,24,40,.08); border-color:#dde3ea; }
  .course-head{ padding:12px 16px; border-bottom:1px solid var(--line); display:flex; align-items:center; justify-content:space-between; gap:12px; }
  .status-pill{ padding:.25rem .55rem; border-radius:999px; font-size:.76rem; font-weight:600; border:1px solid var(--line); text-transform:uppercase; }
  .status-aktif{ background:#f1f8ff; color:#0b5ed7; border-color:#dbe8ff; }
  .status-tutup{ background:#fff5f5; color:#b42318; border-color:#ffd6d6; }
  .meta{ display:flex; flex-wrap:wrap; gap:.35rem .5rem; font-size:.9rem; color:var(--text-muted); }
  .meta .chip{ background:#f6f7fb; border:1px solid #eef0f6; border-radius:999px; padding:.25rem .55rem; }
  .btn-ikut{ background:linear-gradient(135deg,var(--brand),var(--brand-2)); color:#fff; border:none; font-weight:600; min-height:38px; display:inline-flex; align-items:center; gap:.35rem; white-space:nowrap; }
  .btn-ikut:hover{ filter:brightness(.96); color:#fff; }
  .btn-ikut.full,.btn-ikut:disabled{ background:#a0a4ab; cursor:not-allowed; }
  .badge-cap{ --bg:#e8f5e9; --fg:#0a7c2f; background:var(--bg); color:var(--fg); border:1px solid #d9efe0; }
  .badge-cap[data-level="mid"]{ --bg:#fff8e1; --fg:#b26a00; border-color:#fde9b9; }
  .badge-cap[data-level="low"]{ --bg:#ffebee; --fg:#b71c1c; border-color:#ffd0d6; }
  .empty{ background:var(--surface); border:1px dashed #cbd5e1; border-radius:12px; padding:2rem; }
  .text-truncate-2{ display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="hero py-4">
  <div class="container">
    <div class="row gy-3">
      <div class="col-12">
        <h1 class="h3 title mb-2">Tingkatkan Kompetensi Anda</h1>

        {{-- Search + Filter Button --}}
        <form method="GET" action="{{ url()->current() }}" id="filterForm" class="searchbar sticky-lg-top mb-2" aria-label="Cari & filter pelatihan">
          <div class="d-flex align-items-stretch gap-2">
            <div class="flex-grow-1">
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control" placeholder="Cari pelatihan, contoh: Manajemen Waktu" value="{{ request('q') }}">
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
<section id="list" class="py-4">
  <div class="container">
    <div class="row g-3 g-md-4">

      @php
        $hasOngoing      = $hasOngoing      ?? false;
        $myRegisteredIds = $myRegisteredIds ?? [];
      @endphp

      @forelse($trainings as $training)
        @php
          $kuota    = (int)($training->kuota ?? 0);

          // Ambil "terpakai" yang bener2 makan kursi: diterima/berjalan/menunggu_laporan
          $terpakai = (int)($training->peserta_terpakai
                      ?? $training->peserta_registered
                      ?? 0);

          $sisa       = $kuota > 0 ? max(0, $kuota - $terpakai) : null;
          $statusRaw  = $training->status ?? 'aktif';

          $jenis      = $training->jenis_pelatihan ?? '-';
          $lokasi     = $training->lokasi ?? null;

          $mulai      = !empty($training->tanggal_mulai)   ? \Illuminate\Support\Carbon::parse($training->tanggal_mulai)   : null;
          $selesai    = !empty($training->tanggal_selesai) ? \Illuminate\Support\Carbon::parse($training->tanggal_selesai) : null;

          $tglMulai   = $mulai   ? $mulai->isoFormat('D MMM Y')   : null;
          $tglSelesai = $selesai ? $selesai->isoFormat('D MMM Y') : null;
          $rentangTanggal = $tglMulai && $tglSelesai ? "{$tglMulai} – {$tglSelesai}" : ($tglMulai ?? ($tglSelesai ?? '-'));

          $deskripsi = \Illuminate\Support\Str::limit(
            $training->informasi_pelatihan
            ?? $training->detail_pelatihan
            ?? $training->deskripsi
            ?? '',
            140
          );

          // Kuota level chip
          $level = 'high';
          if ($kuota > 0) {
            $ratio = $terpakai / max(1,$kuota);
            $level = $ratio >= .9 ? 'low' : ($ratio >= .65 ? 'mid' : 'high');
          }

          $alreadyRegistered       = in_array($training->id, $myRegisteredIds);
          $blockBecauseOngoingRule = ($hasOngoing && !$alreadyRegistered);

          // H-7 tutup
          $isHMinus7Closed = false;
          if ($mulai) {
            $cutoff = $mulai->copy()->startOfDay()->subDays(7);
            $isHMinus7Closed = now()->startOfDay()->greaterThanOrEqualTo($cutoff);
          }

          // Penuh?
          $isKuotaFull = $kuota > 0 && $terpakai >= $kuota;

          // Status yang ditampilkan di pill:
          // close jika: status non-aktif OR H-7 OR kuota penuh
          $isClosed     = ($statusRaw !== 'aktif') || $isHMinus7Closed || $isKuotaFull;
          $statusLabel  = $isClosed ? 'close' : 'aktif';
          $statusClass  = $isClosed ? 'status-tutup' : 'status-aktif';

          // Alasan disable tombol daftar
          $disableReason = null;
          if ($isKuotaFull) {
            $disableReason = 'Kuota penuh';
          } elseif ($statusRaw !== 'aktif') {
            $disableReason = 'Pendaftaran ditutup';
          } elseif ($isHMinus7Closed) {
            $disableReason = 'Pendaftaran ditutup mulai H-7 sebelum pelatihan dimulai';
          } elseif ($blockBecauseOngoingRule) {
            // ini aturan personal, tidak mengubah pill status
            $disableReason = 'Anda sedang mengikuti pelatihan lain yang belum selesai';
          }
        @endphp

        <div class="col-12 col-md-6 col-xl-4">
          <article class="card course-card h-100" aria-labelledby="title-{{ $training->id }}">
            <header class="course-head">
              <h2 class="h6 mb-0 text-truncate" id="title-{{ $training->id }}" title="{{ $training->nama_pelatihan ?? '-' }}">
                <i class="bi bi-journal-text me-1"></i> {{ $training->nama_pelatihan ?? '-' }}
              </h2>
              <span class="status-pill {{ $statusClass }}">
                <i class="bi bi-activity me-1"></i>{{ $statusLabel }}
              </span>
            </header>

            <div class="card-body d-flex flex-column">
              <div class="meta mb-2">
                <span class="chip" title="Jenis Pelatihan"><i class="bi bi-layers me-1"></i> {{ $jenis }}</span>
                <span class="chip" title="Jadwal"><i class="bi bi-calendar-event me-1"></i> {{ $rentangTanggal }}</span>

                @if($lokasi)
                  <span class="chip" title="Lokasi"><i class="bi bi-geo-alt me-1"></i> {{ $lokasi }}</span>
                @endif
                @if($isHMinus7Closed)
                  <span class="chip" title="Pendaftaran ditutup H-7"><i class="bi bi-lock me-1"></i> H-7 ditutup</span>
                @endif
              </div>

              @if($deskripsi)
                <p class="text-secondary mb-3 text-truncate-2">{{ $deskripsi }}</p>
              @endif

              <div class="d-flex align-items-center justify-content-between mt-auto">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge badge-cap" data-level="{{ $level }}" title="Terpakai / Kuota">
                    <i class="bi bi-people me-1"></i>
                    {{ $kuota > 0 ? "{$terpakai}/{$kuota}" : 'Tanpa Batas' }}
                  </span>
                </div>

                <div class="d-flex gap-1">
                  @if(!empty($training->file_pelatihan))
                    <a class="btn btn-outline-success btn-sm" target="_blank" rel="noopener"
                       href="{{ asset('storage/' . ltrim($training->file_pelatihan,'/')) }}" aria-label="Buka file pelatihan" data-bs-toggle="tooltip" title="Berkas">
                      <i class="bi bi-file-earmark-text"></i>
                    </a>
                  @endif

                  <a href="{{ route('Pelatihan.show', ['id' => $training->id]) }}"
                     class="btn btn-outline-primary btn-sm" aria-label="Lihat detail" data-bs-toggle="tooltip" title="Detail">
                    <i class="bi bi-eye"></i>
                  </a>

                  @if($disableReason)
                    <button type="button" class="btn btn-ikut btn-sm full" disabled
                            data-bs-toggle="tooltip" title="{{ $disableReason }}">Tidak Bisa Daftar</button>
                  @else
                    <button type="button"
                            class="btn btn-ikut btn-sm btn-daftar"
                            @auth('pegawais') data-bs-toggle="modal" data-bs-target="#modalKonfirmasi" @endauth
                            data-session-id="{{ $training->id }}"
                            data-nama-pelatihan="{{ $training->nama_pelatihan }}"
                            data-jenis="{{ $jenis }}"
                            data-lokasi="{{ $lokasi }}"
                            data-tanggal="{{ $rentangTanggal }}"
                            aria-label="Daftar">
                      <i class="bi bi-plus-circle"></i> Daftar
                    </button>
                  @endif
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
                    <tr><th class="w-35 text-muted">Nama</th><td>{{ $akun->nama ?? '-' }}</td></tr>
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
                    <tr><th class="w-35 text-muted">Nama</th><td id="m-nama"></td></tr>
                    <tr><th class="text-muted">Jenis Pelatihan</th><td id="m-jenis"></td></tr>
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

        <div class="modal-footer d-flex justify-content-between">
          <small class="text-muted">Catatan: ASN tidak dapat mengikuti dua pelatihan dengan jadwal bertumpuk.</small>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const isLoggedIn = @json(auth('pegawais')->check());

  // Tooltip
  [...document.querySelectorAll('[data-bs-toggle="tooltip"]')].forEach(el => new bootstrap.Tooltip(el));

  // Loading state tombol Terapkan
  const formFilter = document.getElementById('filterForm');
  const btnFilter  = document.getElementById('btnFilter');
  formFilter?.addEventListener('submit', function () {
    if (!btnFilter) return;
    btnFilter.setAttribute('disabled', true);
    btnFilter.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menerapkan...';
  });

  // ===== OFFCANVAS: Dependent Select Kota =====
  const allKotas = @json($kotas ?? []); // [{id,provinsi_id,nama}]
  const provSel  = document.getElementById('f-provinsi');
  const kotaSel  = document.getElementById('f-kota');

  function fillKotaFromProv(init=false){
    if (!provSel || !kotaSel) return;

    const selectedProv    = provSel.value || provSel.dataset.selectedProvinsi || '';
    const selectedKotaReq = kotaSel.dataset.selectedKota || '';

    kotaSel.innerHTML = '<option value="">Semua</option>';
    kotaSel.disabled = !selectedProv;

    if (!selectedProv) return;

    allKotas.forEach(k => {
      if (String(k.provinsi_id) === String(selectedProv)) {
        const isSelected = init && String(selectedKotaReq) === String(k.id);
        kotaSel.add(new Option(k.nama, k.id, isSelected, isSelected));
      }
    });
  }

  provSel?.addEventListener('change', function(){
    if (kotaSel) kotaSel.dataset.selectedKota = '';
    fillKotaFromProv(false);
  });

  const offcanvasEl = document.getElementById('offcanvasFilter');
  offcanvasEl?.addEventListener('shown.bs.offcanvas', () => fillKotaFromProv(true));

  // ===== Handler tombol daftar =====
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.btn-daftar');
    if (!btn) return;

    if (!isLoggedIn) {
      e.preventDefault();
      if (typeof Swal === 'undefined') {
        if (confirm('Anda harus login untuk mendaftar. Pergi ke halaman login?')) {
          window.location.href = "{{ route('pegawai.login', ['require_email'=>1, 'return_to'=>url()->current()]) }}";
        }
        return;
      }
      Swal.fire({
        icon: 'warning',
        title: 'Belum login',
        text: 'Anda harus login terlebih dahulu untuk mendaftar pelatihan.',
        showCancelButton: true,
        confirmButtonText: 'Login',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#0d6efd'
      }).then((r)=>{ if (r.isConfirmed) window.location.href = "{{ route('pegawai.login', ['require_email'=>1, 'return_to'=>url()->current()]) }}"; });
      return;
    }

    const form = document.getElementById('formJoin');
    if (form) form.action = "{{ route('pelatihan.join', ['id' => '___ID___']) }}".replace('___ID___', btn.dataset.sessionId);

    const setText = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val || '-'; };
    setText('m-nama',   btn.dataset.namaPelatihan);
    setText('m-jenis',  btn.dataset.jenis);
    setText('m-lokasi', btn.dataset.lokasi);
    setText('m-tanggal',btn.dataset.tanggal);
  });

  // Flash alerts
  @if(session('success')) Swal.fire({ icon:'success', title:'Berhasil', text:@json(session('success')), confirmButtonColor:'#3085d6' }); @endif
  @if(session('error'))   Swal.fire({ icon:'error',   title:'Gagal',    text:@json(session('error')),   confirmButtonColor:'#d33' });    @endif
  @if(session('info'))    Swal.fire({ icon:'info',    title:'Info',     text:@json(session('info')) });                                   @endif
  @if(session('warning')) Swal.fire({ icon:'warning', title:'Peringatan',text:@json(session('warning')) });                               @endif
});
</script>
@endpush