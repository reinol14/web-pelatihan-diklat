{{-- Font Awesome (1x saja) --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

  {{-- Bootstrap (CSS) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- CSS lokal (opsional, sesuaikan kebutuhanmu) --}}
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"><!-- jika kamu punya tema lokal -->
  <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pegawai.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  {{-- Select2 (opsional, jika dipakai) --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>


<div class="container" style="max-width: 780px">
  <h1 class="h4 mb-3">Registrasi ASN</h1>

  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if($errors->any())     <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <form method="POST" action="{{ route('pegawai.register.submit') }}" enctype="multipart/form-data" class="row g-3">
    @csrf

    <div class="col-md-6">
      <label class="form-label">Nama</label>
      <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">NIP</label>
      <input type="text" name="nip" class="form-control" value="{{ old('nip') }}" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">No. HP</label>
      <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
    </div>

    {{-- Unit Kerja: label menampilkan "unitkerja — sub_unitkerja" tapi value tetap kode_unitkerja --}}
    <div class="col-md-6">
      <label class="form-label">Unit Kerja</label>
      <select name="kode_unitkerja" class="form-select" required>
        <option value="">-- Pilih --</option>
        @foreach($unitKerjas as $u)
          @php
            $labelUk = trim(($u->unitkerja ?? '') . (empty($u->sub_unitkerja) ? '' : ' — '.$u->sub_unitkerja));
          @endphp
          <option value="{{ $u->kode_unitkerja }}" @selected(old('kode_unitkerja')==$u->kode_unitkerja)>
            {{ $labelUk !== '' ? $labelUk : $u->kode_unitkerja }}
          </option>
        @endforeach
      </select>
      @error('kode_unitkerja')
        <div class="text-danger small mt-1">{{ $message }}</div>
      @enderror
    </div>

    <div class="col-md-6">
      <label class="form-label">Jabatan</label>
      <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}">
    </div>

    <div class="col-md-6">
      <label class="form-label">Tempat Lahir</label>
      <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
    </div>

    <div class="col-md-6">
      <label class="form-label">Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
    </div>

    {{-- Golongan & Pangkat (sinkronisasi dua arah) --}}
    <div class="col-md-6">
      <label class="form-label">Golongan</label>
      <select name="golongan" id="golongan" class="form-select">
        <option value="">-- Pilih --</option>
        @php
          $golList = [
            'I/a','I/b','I/c','I/d',
            'II/a','II/b','II/c','II/d',
            'III/a','III/b','III/c','III/d',
            'IV/a','IV/b','IV/c','IV/d','IV/e',
          ];
        @endphp
        @foreach($golList as $g)
          <option value="{{ $g }}" @selected(old('golongan')===$g)>{{ $g }}</option>
        @endforeach
      </select>
      @error('golongan') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
      <label class="form-label">Pangkat</label>
      <select name="pangkat" id="pangkat" class="form-select">
        <option value="">-- Pilih --</option>
        @php
          // Mapping resmi pangkat ASN (PNS): Pangkat => Golongan
          $pangkatOptions = [
            'Juru Muda'               => 'I/a',
            'Juru Muda Tingkat I'     => 'I/b',
            'Juru'                    => 'I/c',
            'Juru Tingkat I'          => 'I/d',
            'Pengatur Muda'           => 'II/a',
            'Pengatur Muda Tingkat I' => 'II/b',
            'Pengatur'                => 'II/c',
            'Pengatur Tingkat I'      => 'II/d',
            'Penata Muda'             => 'III/a',
            'Penata Muda Tingkat I'   => 'III/b',
            'Penata'                  => 'III/c',
            'Penata Tingkat I'        => 'III/d',
            'Pembina'                 => 'IV/a',
            'Pembina Tingkat I'       => 'IV/b',
            'Pembina Utama Muda'      => 'IV/c',
            'Pembina Utama Madya'     => 'IV/d',
            'Pembina Utama'           => 'IV/e',
          ];
        @endphp
        @foreach($pangkatOptions as $pangkatLabel => $gol)
          <option value="{{ $pangkatLabel }}" data-gol="{{ $gol }}"
            @selected(old('pangkat')===$pangkatLabel)>
            {{ $pangkatLabel }} ({{ $gol }})
          </option>
        @endforeach
      </select>
      @error('pangkat') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-12">
      <label class="form-label">Alamat</label>
      <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}">
    </div>

    <div class="col-md-6">
      <label class="form-label">TMT</label>
      <input type="date" name="tmt" class="form-control" value="{{ old('tmt') }}">
    </div>

    <div class="col-md-6">
      <label class="form-label">Foto (opsional)</label>
      <input type="file" name="foto" class="form-control" accept="image/*">
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary">Kirim Registrasi</button>
      <a href="{{ route('pegawai.login') }}" class="btn btn-outline-secondary">Sudah punya akun?</a>
    </div>
  </form>
</div>

{{-- Sinkronisasi Golongan <-> Pangkat --}}
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const selGol = document.getElementById('golongan');
    const selPangkat = document.getElementById('pangkat');

    function syncFromGolongan() {
      const g = selGol.value;
      if (!g) return;
      // cari option pangkat dengan data-gol == g
      let matched = false;
      Array.from(selPangkat.options).forEach(opt => {
        if (opt.getAttribute('data-gol') === g) {
          selPangkat.value = opt.value;
          matched = true;
        }
      });
      if (!matched) {
        // jika tidak ada, kosongkan pangkat
        selPangkat.value = '';
      }
    }

    function syncFromPangkat() {
      const opt = selPangkat.options[selPangkat.selectedIndex];
      if (!opt) return;
      const g = opt.getAttribute('data-gol');
      if (!g) return;
      selGol.value = g;
    }

    selGol?.addEventListener('change', syncFromGolongan);
    selPangkat?.addEventListener('change', syncFromPangkat);

    // inisialisasi awal (honor old() values)
    if (selGol.value && !selPangkat.value) {
      syncFromGolongan();
    } else if (selPangkat.value && !selGol.value) {
      syncFromPangkat();
    }
  });
</script>
@endpush

