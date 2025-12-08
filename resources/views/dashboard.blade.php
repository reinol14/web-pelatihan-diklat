@extends('layouts.app')

{{-- ====== STYLE ====== --}}
<style>
  .card{border-radius:1rem;border:none;transition:.3s;box-shadow:0 .75rem 1.25rem rgba(0,0,0,.08)}
  .card:hover{transform:translateY(-.25rem)}
  .card-footer{border-radius:0 0 1rem 1rem}
  .component-container{border:1px solid #e5e7eb;border-radius:.75rem}
  .component-container:hover{border-color:#d1d5db}
  .list-mini{margin:0;padding:0;list-style:none}
  .list-mini li{display:flex;align-items:center;justify-content:space-between;padding:.5rem .25rem;border-bottom:1px dashed #edf2f7}
  .list-mini li:last-child{border-bottom:0}
  .badge-soft{border:1px solid rgba(0,0,0,.06);background:#f8fafc}
  .ratio-pill{min-width:64px; text-align:right}
  .text-xxs{font-size:.75rem}
  .text-xs{font-size:.8125rem}
</style>

@section('content')
@if (session('error'))
  <script>alert(@json(session('error')));</script>
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
      $cards = [
        [
          'title'=>'Total Pegawai',
          'value'=>number_format($pegawaiCount),
          'unit'=>'Orang',
          'icon'=>'iconPegawai.png',
          'bg'  =>'bg-primary',
          'sub' => null,
          'link'=> route('Admin.Pegawai.index'),
        ],
        [
          'title'=>'Total Pelatihan',
          'value'=>number_format($pelatihanTotal),
          'unit'=>'pelatihan',
          'icon'=>'iconAsessment.png',
          'bg'  =>'bg-danger',
          'sub' =>'Terbuka: '.number_format($pelatihanDapatDiakses).' • Tertutup: '.number_format($pelatihanTertutup),
          'link'=> route('Admin.pelatihan.index') // ganti jika route berbeda
        ],
        [
          'title'=>'Perubahan Profil (Butuh Verifikasi)',
          'value'=>number_format($profilePending),
          'unit'=>'pending',
          'icon'=>'iconGapPegawai.png',
          'bg'  =>'bg-success',
          'sub' => null,
          'link'=> route('Admin.pegawai_profile.index',['status'=>'pending']),
        ],
        [
          'title'=>'Akun Pegawai (Butuh Verifikasi)',
          'value'=>number_format($regPending),
          'unit'=>'pending',
          'icon'=>'iconUsulanPelatihan.png',
          'bg'  =>'bg-warning',
          'sub' => null,
          'link'=> route('Admin.Pegawai.PegawaiApproval.index',['status'=>'pending']),
        ],
      ];
    @endphp

    @foreach ($cards as $card)
      <div class="col-md-3">
        <div class="card shadow-sm rounded h-100">
          <div class="card-body d-flex align-items-center p-3">
            <div class="d-flex flex-column flex-grow-1">
              <span class="text-muted text-xs mb-1">{{ $card['title'] }}</span>
              <div class="d-flex flex-column">
                <h2 class="fw-bold mb-0">{{ $card['value'] }}</h2>
                <span class="text-muted text-xs">{{ $card['unit'] }}</span>
                @if(!empty($card['sub']))
                  <small class="text-muted">{{ $card['sub'] }}</small>
                @endif
              </div>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center {{ $card['bg'] }} bg-opacity-10" style="width:48px;height:48px">
              <img src="{{ asset('images/'.$card['icon']) }}" alt="Icon" width="24" height="24">
            </div>
          </div>
          <div class="card-footer bg-white border-0 text-end py-2">
            <a href="{{ $card['link'] }}" class="text-decoration-none text-primary text-xs">Detail</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- ====== ROW: CHART & PIE ====== --}}
  <div class="row mt-2">
    <div class="col-lg-8">
      <div class="component-container p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h6 class="mb-0 fw-bold">Aktivitas 8 Minggu</h6>
            <small class="text-muted">Registrasi akun • Pendaftaran pelatihan • Pengajuan laporan</small>
          </div>
        </div>
        <div style="position:relative;height:300px">
          <canvas id="chartWeekly"></canvas>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="component-container p-4 h-100">
        <h6 class="fw-bold mb-2">Distribusi Metode Pelatihan</h6>
        <small class="text-muted d-block mb-3">Online vs Offline vs Hybrid</small>
        <div style="position:relative;height:260px">
          <canvas id="chartPieMetode"></canvas>
        </div>
        <div class="mt-3 d-flex justify-content-between text-xs">
          @foreach($metodeDistribusi as $m=>$n)
            <span class="badge badge-soft">{{ $m }}: {{ (int)$n }}</span>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- ====== ROW: KALENDER & ACTION CENTER ====== --}}
  <div class="row mt-4">
    <div class="col-lg-8">
      <div class="component-container p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0 fw-bold">Kalender & Deadline (30 hari)</h6>
          <a href="{{ route('Admin.pelatihan.index') }}" class="text-decoration-none text-primary text-xs">Lihat semua</a>
        </div>

        <ul class="list-mini">
          @forelse($deadlineSoon as $s)
            @php
              $mulai = $s->tanggal_mulai ? \Illuminate\Support\Carbon::parse($s->tanggal_mulai) : null;
              $dStr  = $mulai ? $mulai->format('d M Y') : '-';
              // H-7 tutup pendaftaran
              $hmin7 = $mulai ? $mulai->copy()->startOfDay()->subDays(7) : null;
              $isClosedH7 = $hmin7 ? now()->greaterThanOrEqualTo($hmin7) : false;
            @endphp
            <li>
              <div>
                <div class="fw-semibold">{{ $s->nama_pelatihan ?? '-' }}</div>
                <div class="text-muted text-xxs">Mulai: {{ $dStr }}</div>
              </div>
              <div class="text-end">
                <span class="badge {{ ($s->status??'aktif')==='aktif' ? 'bg-primary' : 'bg-secondary' }} bg-opacity-10 text-primary text-xs">
                  {{ $s->status ?? 'aktif' }}
                </span>
                @if($isClosedH7)
                  <div class="text-danger text-xxs">Pendaftaran H-7 ditutup</div>
                @endif
              </div>
            </li>
          @empty
            <li class="text-muted">Tidak ada jadwal dalam 30 hari ke depan.</li>
          @endforelse
        </ul>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="component-container p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0 fw-bold">Action Center</h6>
        </div>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Akun pending
            <a class="btn btn-sm btn-outline-primary" href="{{ route('Admin.Pegawai.PegawaiApproval.index',['status'=>'pending']) }}">
              {{ $ac['reg_pending'] }} tindak
            </a>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Perubahan profil pending
            <a class="btn btn-sm btn-outline-primary" href="{{ route('Admin.pegawai_profile.index',['status'=>'pending']) }}">
              {{ $ac['profile_pending'] }} tindak
            </a>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Laporan pelatihan pending
            <a class="btn btn-sm btn-outline-primary" href="{{ route('Admin.pelatihan.index',[],false) ?? '#' }}">
              {{ $ac['laporan_pending'] }} tindak
            </a>
          </li>
        </ul>

        <div class="mt-3 p-2 rounded border bg-light">
          <div class="d-flex justify-content-between">
            <span class="text-xs text-muted">Kepatuhan laporan</span>
            <span class="fw-semibold">{{ $complianceRate }}%</span>
          </div>
          <div class="text-xxs text-muted">
            Approved: {{ $compApproved }} / {{ $compTotal }}
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ====== ROW: KUOTA & RINGKASAN LAPORAN ====== --}}
  <div class="row mt-4">
    <div class="col-lg-8">
      <div class="component-container p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0 fw-bold">Kuota & Kepadatan (Top 5)</h6>
        </div>

        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Pelatihan</th>
              <th class="text-center">Terpakai / Kuota</th>
              <th class="text-end">Rasio</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topPadat as $row)
              @php
                $kuota    = (int)($row->kuota ?? 0);
                $terpakai = (int)($row->terpakai ?? 0);
                $ratio    = $kuota>0 ? $terpakai/max(1,$kuota) : 0;
                $pct      = round($ratio*100);
              @endphp
              <tr>
                <td>
                  <div class="fw-semibold">{{ $row->nama_pelatihan ?? '-' }}</div>
                  <div class="text-muted text-xxs">ID: {{ $row->id }}</div>
                </td>
                <td class="text-center">
                  <span class="badge badge-soft">{{ $terpakai }}/{{ $kuota>0?$kuota:'∞' }}</span>
                </td>
                <td class="text-end ratio-pill">
                  {{ $pct }}%
                </td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-muted text-center">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="component-container p-4">
        <h6 class="fw-bold mb-2">Ringkasan Laporan Pelatihan</h6>

        <div class="d-flex gap-2 mb-3">
          <span class="badge bg-warning text-dark">Pending: {{ $lapSum['pending'] ?? 0 }}</span>
          <span class="badge bg-success">Approved: {{ $lapSum['approved'] ?? 0 }}</span>
          <span class="badge bg-danger">Rejected: {{ $lapSum['rejected'] ?? 0 }}</span>
        </div>

        <div class="text-muted text-xs mb-2">Pending paling lama</div>
        <ul class="list-mini">
          @forelse($lapAging as $l)
            <li>
              <div>
                <div class="fw-semibold text-xs">{{ $l->pelatihan_nama ?? '-' }}</div>
                <div class="text-xxs text-muted">{{ $l->pegawai_nama ?? '-' }}</div>
              </div>
              <small class="text-muted">
                {{ \Illuminate\Support\Carbon::parse($l->created_at)->diffForHumans(['parts'=>2,'short'=>true]) }}
              </small>
            </li>
          @empty
            <li class="text-muted">Tidak ada.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  {{-- ====== DETAIL TABEL: AKUN PENDING (seperti semula) ====== --}}
  <div class="row mt-4">
    <div class="col-lg-8">
      <div class="component-container">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3 px-4 border-0">
          <h5 class="mb-0 fw-bold">Akun Pegawai Menunggu Verifikasi</h5>
          <a href="{{ route('Admin.Pegawai.PegawaiApproval.index',['status'=>'pending']) }}" class="text-primary text-decoration-none">View All</a>
        </div>
        <div class="card-body px-4 pb-4 pt-0">
          <table class="table table-hover mb-0">
            <thead style="background:#F0F4FF">
              <tr>
                <th class="py-3" style="width:5%">No</th>
                <th class="py-3" style="width:22%">Tanggal Pengajuan</th>
                <th class="py-3" style="width:26%">Nama</th>
                <th class="py-3" style="width:27%">Email / NIP</th>
                <th class="py-3" style="width:10%">Status</th>
                <th class="py-3 text-center" style="width:10%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pendingRegs as $i => $reg)
                <tr>
                  <td class="py-3 px-4">{{ $i+1 }}</td>
                  <td class="py-3">
                    <div>{{ \Illuminate\Support\Carbon::parse($reg->created_at)->format('d/m/Y') }}</div>
                    <small class="text-muted">{{ \Illuminate\Support\Carbon::parse($reg->created_at)->format('H:i') }} WIB</small>
                  </td>
                  <td class="py-3">{{ $reg->nama }}</td>
                  <td class="py-3">
                    <div>{{ $reg->email }}</div>
                    <small class="text-muted">{{ $reg->nip }}</small>
                  </td>
                  <td class="py-3">
                    <span class="badge bg-warning text-warning bg-opacity-10">Pending</span>
                  </td>
                  <td class="py-3 text-center">
                    <a class="btn btn-sm btn-outline-primary rounded-circle"
                       href="{{ route('Admin.Pegawai.PegawaiApproval.show', $reg->id) }}" title="Tinjau">
                      <i class="bi bi-eye"></i>
                    </a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada akun pending.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Kosongkan/isi panel kanan sesuai kebutuhanmu --}}
    <div class="col-lg-4">
      <div class="component-container p-4 h-100">
        <h6 class="fw-bold mb-2">Catatan</h6>
        <div class="text-muted text-xs">
          Panel ini bisa dipakai untuk pengumuman admin atau ringkasan kebijakan terbaru.
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
