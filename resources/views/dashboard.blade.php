@extends('layouts.app')

{{-- ====== MODERN DASHBOARD STYLES ====== --}}
<style>
  :root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --shadow-sm: 0 0.125rem 0.5rem rgba(0,0,0,0.075);
    --shadow-md: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
    --shadow-lg: 0 1rem 3rem rgba(0,0,0,0.175);
  }

  /* Modern Card Styles */
  .modern-stat-card {
    border-radius: 1.25rem;
    border: none;
    background: white;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    position: relative;
  }

  .modern-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
    transition: height 0.3s ease;
  }

  .modern-stat-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
  }

  .modern-stat-card:hover::before {
    height: 6px;
  }

  .stat-icon-wrapper {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
  }

  .stat-icon-wrapper::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: inherit;
    opacity: 0.1;
  }

  .stat-icon-wrapper i {
    font-size: 28px;
    z-index: 1;
  }

  .stat-value {
    font-size: 2.25rem;
    font-weight: 700;
    line-height: 1.2;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* Component Container Modern */
  .modern-container {
    background: white;
    border-radius: 1.25rem;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
  }

  .modern-container:hover {
    border-color: #c7cad1;
    box-shadow: var(--shadow-md);
  }

  .modern-container-header {
    padding: 1.5rem 1.75rem 1rem;
    border-bottom: 1px solid #f3f4f6;
  }

  .modern-container-body {
    padding: 1.75rem;
  }

  /* Action Center Modern */
  .action-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 0.75rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .action-item:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    transform: translateX(4px);
  }

  .action-badge {
    background: var(--primary-gradient);
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    transition: all 0.2s ease;
  }

  .action-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
  }

  /* Progress Bar Modern */
  .progress-modern {
    height: 8px;
    border-radius: 10px;
    background: #f3f4f6;
    overflow: hidden;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
  }

  .progress-bar-modern {
    height: 100%;
    background: var(--primary-gradient);
    border-radius: 10px;
    transition: width 0.6s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
  }

  /* List Modern */
  .list-modern {
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .list-modern li {
    padding: 0.875rem 0;
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.2s ease;
  }

  .list-modern li:last-child {
    border-bottom: none;
  }

  .list-modern li:hover {
    background: #f9fafb;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    border-radius: 8px;
  }

  /* Badge Modern */
  .badge-modern {
    padding: 0.35rem 0.85rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.75rem;
    letter-spacing: 0.3px;
  }

  .badge-modern-primary { background: rgba(102, 126, 234, 0.1); color: #667eea; }
  .badge-modern-success { background: rgba(17, 153, 142, 0.1); color: #11998e; }
  .badge-modern-warning { background: rgba(245, 87, 108, 0.1); color: #f5576c; }
  .badge-modern-info { background: rgba(79, 172, 254, 0.1); color: #4facfe; }

  /* Table Modern */
  .table-modern {
    border-collapse: separate;
    border-spacing: 0;
  }

  .table-modern thead th {
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    border: none;
    color: #495057;
    font-weight: 600;
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
  }

  .table-modern tbody tr {
    transition: all 0.2s ease;
  }

  .table-modern tbody tr:hover {
    background: #f8f9fa;
    transform: scale(1.01);
  }

  .table-modern tbody td {
    border-bottom: 1px solid #f3f4f6;
    padding: 1rem 0.75rem;
    vertical-align: middle;
  }

  /* Text Utilities */
  .text-xxs { font-size: 0.75rem; }
  .text-xs { font-size: 0.8125rem; }
  .text-gradient {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* Animations */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out;
  }

  /* Chart Container */
  .chart-wrapper {
    position: relative;
    height: 320px;
    padding: 1rem;
  }

  /* Compliance Meter */
  .compliance-meter {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 1.25rem;
    margin-top: 1rem;
  }

  .compliance-meter-bar {
    height: 12px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 0.75rem;
  }

  .compliance-meter-fill {
    height: 100%;
    background: var(--success-gradient);
    border-radius: 10px;
    transition: width 0.8s ease;
    box-shadow: 0 2px 8px rgba(17, 153, 142, 0.3);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .stat-value { font-size: 1.75rem; }
    .stat-icon-wrapper { width: 52px; height: 52px; }
    .modern-container-header { padding: 1.25rem; }
    .modern-container-body { padding: 1.25rem; }
  }
</style>

@section('content')
@if (session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <strong>Akses Ditolak!</strong> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@php
  // ====== FALLBACK NILAI SUPAYA AMAN ======
  $pegawaiCount          = $pegawaiCount          ?? 0;
  $pelatihanTotal        = $pelatihanTotal        ?? 0;
  $pelatihanDapatDiakses = $pelatihanDapatDiakses ?? 0;   // aktif & masih bisa daftar (termasuk bukan H-7)
  $pelatihanTertutup     = $pelatihanTertutup     ?? max(0, $pelatihanTotal - $pelatihanDapatDiakses);
  $profilePending        = $profilePending        ?? 0;   // perubahan profil pending
  $regPending            = $regPending            ?? 0;   // akun pegawai pending

  // Kalender & Deadline (30 hari ke depan)
  // item: id, nama_pelatihan, tanggal_mulai, status
  $deadlineSoon = $deadlineSoon ?? collect();

  // Kuota & kepadatan top 5
  // item: id, nama_pelatihan, kuota, terpakai, ratio(float 0..1)
  $topPadat = $topPadat ?? collect();

  // Ringkasan status laporan
  $lapSum = $lapSum ?? ['pending'=>0,'approved'=>0,'rejected'=>0];
  // Pending terlama (max 5) : id, pelatihan_nama, pegawai_nama, created_at
  $lapAging = $lapAging ?? collect();

  // Action center
  $ac = [
    'reg_pending'       => $regPending,
    'profile_pending'   => $profilePending,
    'laporan_pending'   => ($lapSum['pending'] ?? 0),
  ];

  // Chart mingguan 8 minggu
  $chartWeekly = $chartWeekly ?? [
    'labels'        => ['-7','-6','-5','-4','-3','-2','-1','Minggu ini'],
    'registrations' => [0,0,0,0,0,0,0,0],
    'enrollments'   => [0,0,0,0,0,0,0,0],
    'reports'       => [0,0,0,0,0,0,0,0],
  ];

  // Distribusi metode
  // contoh: ['Online'=>10,'Offline'=>7,'Hybrid'=>3]
  $metodeDistribusi = $metodeDistribusi ?? ['Online'=>0,'Offline'=>0,'Hybrid'=>0];

  // Kepatuhan laporan
  $compApproved = $compApproved ?? 0;
  $compTotal    = $compTotal    ?? 0;
  $complianceRate = $compTotal > 0 ? round(100 * $compApproved / $compTotal) : 0;

  // Pending akun list (detail tabel)
  $pendingRegs = $pendingRegs ?? collect();
@endphp

<div class="">
  {{-- ====== KARTU TOP ====== --}}
  <div class="row mb-4">
    @php
      $isSuperAdmin = auth()->check() && auth()->user()->is_admin == 1;
      
      $cards = [
        [
          'title'=>'Total Pegawai',
          'value'=>number_format($pegawaiCount),
          'unit'=>'Orang',
          'icon'=>'iconPegawai.png',
          'icon-class'=>'people-fill',
          'bg'  =>'bg-primary',
          'sub' => null,
          'link'=> route('Admin.Pegawai.index'),
          'show' => true,
        ],
        [
          'title'=>'Perubahan Profil',
          'value'=>number_format($profilePending),
          'unit'=>'menunggu verifikasi',
          'icon'=>'iconGapPegawai.png',
          'icon-class'=>'person-badge-fill',
          'bg'  =>'bg-success',
          'sub' => null,
          'link'=> route('Admin.pegawai_profile.index',['status'=>'pending']),
          'show' => true,
        ],
        [
          'title'=>'Akun Pegawai Baru',
          'value'=>number_format($regPending),
          'unit'=>'menunggu verifikasi',
          'icon'=>'iconUsulanPelatihan.png',
          'icon-class'=>'person-plus-fill',
          'bg'  =>'bg-warning',
          'sub' => null,
          'link'=> route('Admin.Pegawai.PegawaiApproval.index',['status'=>'pending']),
          'show' => true,
        ],
      ];
      
      // Tambahkan card Total Pelatihan hanya untuk superadmin
      if ($isSuperAdmin && Route::has('Admin.Pelatihan.index')) {
        $cards[] = [
          'title'=>'Total Pelatihan',
          'value'=>number_format($pelatihanTotal),
          'unit'=>'sesi pelatihan',
          'icon'=>'iconAsessment.png',
          'icon-class'=>'calendar-event-fill',
          'bg'  =>'bg-danger',
          'sub' =>'Aktif: '.number_format($pelatihanDapatDiakses).' • Ditutup: '.number_format($pelatihanTertutup),
          'link'=> route('Admin.Pelatihan.index'),
          'show' => true,
        ];
      }
    @endphp

    @foreach ($cards as $card)
      @if ($card['show'] ?? true)
        <div class="col-md-{{ $isSuperAdmin ? '3' : '4' }} mb-3 animate-fade-in-up">
          <div class="modern-stat-card h-100">
            <div class="p-4">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="flex-grow-1">
                  <p class="text-muted text-xs mb-2 text-uppercase" style="letter-spacing: 0.5px;">{{ $card['title'] }}</p>
                  <h2 class="stat-value mb-1">{{ $card['value'] }}</h2>
                  <span class="text-muted" style="font-size: 0.875rem;">{{ $card['unit'] }}</span>
                  @if(!empty($card['sub']))
                    <div class="mt-2">
                      <small class="text-muted" style="font-size: 0.75rem;">{{ $card['sub'] }}</small>
                    </div>
                  @endif
                </div>
                <div class="stat-icon-wrapper {{ $card['bg'] }} bg-opacity-10">
                  <i class="bi bi-{{ $card['icon-class'] ?? 'graph-up' }} {{ str_replace('bg-', 'text-', $card['bg']) }}"></i>
                </div>
              </div>
              <a href="{{ $card['link'] }}" class="btn btn-sm btn-link text-decoration-none p-0 text-primary fw-semibold">
                <span>Lihat Detail</span>
                <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      @endif
    @endforeach
  </div>

  {{-- ====== ROW: CHART & PIE ====== --}}
  <div class="row mt-4">
    <div class="col-lg-8 mb-4">
      <div class="modern-container h-100">
        <div class="modern-container-header">
          <div class="d-flex align-items-center">
            <i class="bi bi-graph-up text-primary me-2" style="font-size: 1.25rem;"></i>
            <div>
              <h5 class="mb-0 fw-bold">Tren Aktivitas 8 Minggu Terakhir</h5>
              <small class="text-muted">Registrasi • Pendaftaran • Laporan</small>
            </div>
          </div>
        </div>
        <div class="modern-container-body">
          <div class="chart-wrapper">
            <canvas id="chartWeekly"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 mb-4">
      <div class="modern-container h-100">
        <div class="modern-container-header">
          <div class="d-flex align-items-center">
            <i class="bi bi-pie-chart-fill text-success me-2" style="font-size: 1.25rem;"></i>
            <div>
              <h5 class="mb-0 fw-bold">Metode Pelatihan</h5>
              <small class="text-muted">Distribusi jenis</small>
            </div>
          </div>
        </div>
        <div class="modern-container-body">
          <div style="position:relative;height:240px">
            <canvas id="chartPieMetode"></canvas>
          </div>
          <div class="mt-3 d-flex flex-wrap gap-2">
            @foreach($metodeDistribusi as $m=>$n)
              <span class="badge-modern badge-modern-primary">{{ $m }}: <strong>{{ (int)$n }}</strong></span>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ====== ROW: KALENDER & ACTION CENTER ====== --}}
  <div class="row mt-4">
    @if($isSuperAdmin && Route::has('Admin.Pelatihan.index'))
    <div class="col-lg-8 mb-4">
      <div class="modern-container">
        <div class="modern-container-header">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <i class="bi bi-calendar-week text-danger me-2" style="font-size: 1.25rem;"></i>
              <div>
                <h5 class="mb-0 fw-bold">Jadwal & Deadline</h5>
                <small class="text-muted">30 hari ke depan</small>
              </div>
            </div>
            <a href="{{ route('Admin.Pelatihan.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
              <span>Lihat Semua</span>
              <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
        <div class="modern-container-body">
          <ul class="list-modern">
            @forelse($deadlineSoon as $s)
              @php
                $mulai = $s->tanggal_mulai ? \Illuminate\Support\Carbon::parse($s->tanggal_mulai) : null;
                $dStr  = $mulai ? $mulai->format('d M Y') : '-';
                $hmin7 = $mulai ? $mulai->copy()->startOfDay()->subDays(7) : null;
                $isClosedH7 = $hmin7 ? now()->greaterThanOrEqualTo($hmin7) : false;
              @endphp
              <li class="d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-start">
                  <div class="me-3" style="min-width: 48px;">
                    <div class="text-center p-2 rounded" style="background: rgba(102, 126, 234, 0.1);">
                      <div class="fw-bold text-primary" style="font-size: 1.25rem; line-height: 1;">{{ $mulai ? $mulai->format('d') : '-' }}</div>
                      <div class="text-muted text-xxs text-uppercase">{{ $mulai ? $mulai->format('M') : '' }}</div>
                    </div>
                  </div>
                  <div>
                    <div class="fw-semibold mb-1">{{ $s->nama_pelatihan ?? '-' }}</div>
                    <div class="text-muted text-xs">
                      <i class="bi bi-calendar3 me-1"></i>{{ $dStr }}
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <span class="badge-modern {{ ($s->status??'aktif')==='aktif' ? 'badge-modern-success' : 'badge-modern-info' }}">
                    {{ $s->status ?? 'aktif' }}
                  </span>
                  @if($isClosedH7)
                    <div class="text-danger text-xxs mt-1">
                      <i class="bi bi-exclamation-circle-fill me-1"></i>Ditutup H-7
                    </div>
                  @endif
                </div>
              </li>
            @empty
              <li class="text-muted text-center py-4">
                <i class="bi bi-calendar-x" style="font-size: 2rem; opacity: 0.3;"></i>
                <div class="mt-2">Tidak ada jadwal dalam 30 hari ke depan</div>
              </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
    @endif

    <div class="col-lg-{{ $isSuperAdmin ? '4' : '12' }}">
      <div class="modern-container h-100">
        <div class="modern-container-header">
          <div class="d-flex align-items-center">
            <i class="bi bi-bell-fill text-warning me-2" style="font-size: 1.25rem;"></i>
            <div>
              <h5 class="mb-0 fw-bold">Pusat Tindakan</h5>
              <small class="text-muted">Item yang perlu perhatian</small>
            </div>
          </div>
        </div>
        <div class="modern-container-body">
          <div class="action-item">
            <div class="d-flex align-items-center">
              <i class="bi bi-person-fill-add text-primary me-3" style="font-size: 1.5rem;"></i>
              <div>
                <div class="fw-semibold">Akun Pegawai Baru</div>
                <small class="text-muted">Menunggu verifikasi</small>
              </div>
            </div>
            <a class="action-badge text-decoration-none" href="{{ route('Admin.Pegawai.PegawaiApproval.index',['status'=>'pending']) }}">
              {{ $ac['reg_pending'] }}
            </a>
          </div>

          <div class="action-item">
            <div class="d-flex align-items-center">
              <i class="bi bi-person-badge text-success me-3" style="font-size: 1.5rem;"></i>
              <div>
                <div class="fw-semibold">Perubahan Profil</div>
                <small class="text-muted">Menunggu approval</small>
              </div>
            </div>
            <a class="action-badge text-decoration-none" href="{{ route('Admin.pegawai_profile.index',['status'=>'pending']) }}" style="background: var(--success-gradient);">
              {{ $ac['profile_pending'] }}
            </a>
          </div>

          <div class="action-item">
            <div class="d-flex align-items-center">
              <i class="bi bi-file-earmark-text text-info me-3" style="font-size: 1.5rem;"></i>
              <div>
                <div class="fw-semibold">Laporan Pelatihan</div>
                <small class="text-muted">Perlu ditinjau</small>
              </div>
            </div>
            <a class="action-badge text-decoration-none" href="{{ route('Admin.Pelatihan.index',[],false) ?? '#' }}" style="background: var(--info-gradient);">
              {{ $ac['laporan_pending'] }}
            </a>
          </div>

          <div class="compliance-meter">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <span class="text-muted text-xs">Tingkat Kepatuhan Laporan</span>
              </div>
              <span class="fw-bold h4 mb-0 text-gradient">{{ $complianceRate }}%</span>
            </div>
            <div class="compliance-meter-bar mt-2">
              <div class="compliance-meter-fill" style="width: {{ $complianceRate }}%"></div>
            </div>
            <div class="text-xxs text-muted mt-2">
              <i class="bi bi-check-circle-fill text-success me-1"></i>
              {{ $compApproved }} dari {{ $compTotal }} laporan disetujui
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ====== ROW: KUOTA & RINGKASAN LAPORAN ====== --}}
  @if($isSuperAdmin)
  <div class="row mt-4">
    <div class="col-lg-8 mb-4">
      <div class="modern-container">
        <div class="modern-container-header">
          <div class="d-flex align-items-center">
            <i class="bi bi-bar-chart-fill text-info me-2" style="font-size: 1.25rem;"></i>
            <div>
              <h5 class="mb-0 fw-bold">Pelatihan Terpadat</h5>
              <small class="text-muted">Top 5 berdasarkan kuota</small>
            </div>
          </div>
        </div>
        <div class="modern-container-body">
          <table class="table table-modern mb-0">
            <thead>
              <tr>
                <th>Nama Pelatihan</th>
                <th class="text-center">Kuota</th>
                <th class="text-end">Persentase</th>
              </tr>
            </thead>
            <tbody>
              @forelse($topPadat as $row)
                @php
                  $kuota    = (int)($row->kuota ?? 0);
                  $terpakai = (int)($row->terpakai ?? 0);
                  $ratio    = $kuota>0 ? $terpakai/max(1,$kuota) : 0;
                  $pct      = round($ratio*100);
                  $colorClass = $pct >= 90 ? 'danger' : ($pct >= 70 ? 'warning' : 'success');
                @endphp
                <tr>
                  <td>
                    <div class="fw-semibold">{{ $row->nama_pelatihan ?? '-' }}</div>
                    <div class="text-muted text-xxs">ID: {{ $row->id }}</div>
                  </td>
                  <td class="text-center">
                    <span class="badge-modern badge-modern-info">{{ $terpakai }} / {{ $kuota>0?$kuota:'∞' }}</span>
                  </td>
                  <td class="text-end">
                    <div class="d-flex align-items-center justify-content-end">
                      <div class="progress-modern me-2" style="width: 80px;">
                        <div class="progress-bar-modern bg-{{ $colorClass }}" style="width: {{ min($pct, 100) }}%"></div>
                      </div>
                      <span class="fw-semibold text-{{ $colorClass }}">{{ $pct }}%</span>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-muted text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                    <div class="mt-2">Belum ada data</div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-4 mb-4">
      <div class="modern-container">
        <div class="modern-container-header">
          <div class="d-flex align-items-center">
            <i class="bi bi-file-text-fill text-success me-2" style="font-size: 1.25rem;"></i>
            <div>
              <h5 class="mb-0 fw-bold">Status Laporan</h5>
              <small class="text-muted">Ringkasan pelatihan</small>
            </div>
          </div>
        </div>
        <div class="modern-container-body">
          <div class="d-flex flex-wrap gap-2 mb-4">
            <div class="flex-fill text-center p-3 rounded" style="background: rgba(245, 158, 11, 0.1);">
              <div class="text-warning fw-bold" style="font-size: 1.5rem;">{{ $lapSum['pending'] ?? 0 }}</div>
              <div class="text-muted text-xs">Pending</div>
            </div>
            <div class="flex-fill text-center p-3 rounded" style="background: rgba(16, 185, 129, 0.1);">
              <div class="text-success fw-bold" style="font-size: 1.5rem;">{{ $lapSum['approved'] ?? 0 }}</div>
              <div class="text-muted text-xs">Disetujui</div>
            </div>
            <div class="flex-fill text-center p-3 rounded" style="background: rgba(239, 68, 68, 0.1);">
              <div class="text-danger fw-bold" style="font-size: 1.5rem;">{{ $lapSum['rejected'] ?? 0 }}</div>
              <div class="text-muted text-xs">Ditolak</div>
            </div>
          </div>

          <div class="border-top pt-3">
            <div class="text-muted text-xs mb-2 fw-semibold">Pending Terlama</div>
            <ul class="list-modern">
              @forelse($lapAging as $l)
                <li class="d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fw-semibold text-xs">{{ $l->pelatihan_nama ?? '-' }}</div>
                    <div class="text-xxs text-muted">{{ $l->pegawai_nama ?? '-' }}</div>
                  </div>
                  <small class="badge-modern badge-modern-warning">
                    {{ \Illuminate\Support\Carbon::parse($l->created_at)->diffForHumans(['parts'=>1,'short'=>true]) }}
                  </small>
                </li>
              @empty
                <li class="text-muted text-center py-2 text-xs">Tidak ada data</li>
              @endforelse
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  {{-- ====== DETAIL TABEL: AKUN PENDING ====== --}}
  <div class="row mt-4">
    <div class="col-lg-8 mb-4">
      <div class="modern-container">
        <div class="modern-container-header">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <i class="bi bi-hourglass-split text-warning me-2" style="font-size: 1.25rem;"></i>
              <div>
                <h5 class="mb-0 fw-bold">Akun Pegawai Menunggu Verifikasi</h5>
                <small class="text-muted">Registrasi terbaru</small>
              </div>
            </div>
            <a href="{{ route('Admin.Pegawai.PegawaiApproval.index',['status'=>'pending']) }}" class="btn btn-sm btn-outline-primary rounded-pill">
              <span>Lihat Semua</span>
              <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
        <div class="modern-container-body p-0">
          <div class="table-responsive">
            <table class="table table-modern mb-0">
              <thead>
                <tr>
                  <th style="width:5%">No</th>
                  <th style="width:22%">Tanggal</th>
                  <th style="width:26%">Nama</th>
                  <th style="width:27%">Email / NIP</th>
                  <th style="width:10%">Status</th>
                  <th class="text-center" style="width:10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pendingRegs as $i => $reg)
                  <tr>
                    <td class="px-3">{{ $i+1 }}</td>
                    <td>
                      <div class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($reg->created_at)->format('d/m/Y') }}</div>
                      <small class="text-muted">{{ \Illuminate\Support\Carbon::parse($reg->created_at)->format('H:i') }} WIB</small>
                    </td>
                    <td class="fw-semibold">{{ $reg->nama }}</td>
                    <td>
                      <div>{{ $reg->email }}</div>
                      <small class="text-muted">{{ $reg->nip }}</small>
                    </td>
                    <td>
                      <span class="badge-modern badge-modern-warning">Pending</span>
                    </td>
                    <td class="text-center">
                      <a class="btn btn-sm btn-outline-primary rounded-circle" href="{{ route('Admin.Pegawai.PegawaiApproval.show', $reg->id) }}" title="Tinjau">
                        <i class="bi bi-eye"></i>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                      <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.2;"></i>
                      <div class="mt-2">Tidak ada akun pending</div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 mb-4">
      <div class="modern-container h-100">
        <div class="modern-container-header">
          <div class="d-flex align-items-center">
            <i class="bi bi-megaphone-fill text-info me-2" style="font-size: 1.25rem;"></i>
            <div>
              <h5 class="mb-0 fw-bold">Pengumuman</h5>
              <small class="text-muted">Informasi penting</small>
            </div>
          </div>
        </div>
        <div class="modern-container-body">
          <div class="p-3 rounded" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-left: 4px solid #667eea;">
            <div class="d-flex align-items-start">
              <i class="bi bi-info-circle-fill text-primary me-2 mt-1" style="font-size: 1.25rem;"></i>
              <div>
                <div class="fw-semibold mb-2">Selamat Datang!</div>
                <div class="text-muted text-xs">
                  Panel ini dapat digunakan untuk menampilkan pengumuman admin atau ringkasan kebijakan terbaru kepada seluruh pengguna sistem.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ====== SCRIPTS ====== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Data chart weekly dari server
  const chartWeekly = @json($chartWeekly);
  const labels = chartWeekly.labels || [];
  const datasets = {
    registrations: chartWeekly.registrations || [],
    enrollments:   chartWeekly.enrollments   || [],
    reports:       chartWeekly.reports       || []
  };

  const ctxW = document.getElementById('chartWeekly').getContext('2d');
  new Chart(ctxW, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {label:'Registrasi Akun', data:datasets.registrations, borderColor:'#3A58F2', backgroundColor:'rgba(58,88,242,.08)', tension:.35, fill:true},
        {label:'Pendaftaran Pelatihan', data:datasets.enrollments, borderColor:'#53D1EF', backgroundColor:'rgba(83,209,239,.08)', tension:.35, fill:true},
        {label:'Pengajuan Laporan', data:datasets.reports, borderColor:'#45C58A', backgroundColor:'rgba(69,197,138,.08)', tension:.35, fill:true},
      ]
    },
    options: {
      responsive:true, maintainAspectRatio:false,
      scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 } } },
      plugins:{ legend:{ labels:{ boxWidth:12 } } }
    }
  });

  // Pie distribusi metode
  const metodeDistribusi = @json(array_values($metodeDistribusi));
  const metodeLabels     = @json(array_keys($metodeDistribusi));
  const ctxP = document.getElementById('chartPieMetode').getContext('2d');
  new Chart(ctxP, {
    type: 'pie',
    data: {
      labels: metodeLabels,
      datasets: [{ data: metodeDistribusi }]
    },
    options: { responsive:true, maintainAspectRatio:false }
  });
</script>
@endsection
