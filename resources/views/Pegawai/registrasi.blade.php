<div class="container py-5" style="max-width: 780px;">
  <div class="card shadow border-0 rounded-4 p-4">
    <h1 class="h4 text-center text-primary fw-bold mb-4">Registrasi ASN</h1>

    {{-- Flash Messages (untuk SweetAlert2) --}}
    @if(session('error')) 
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
            title: "Error!",
            text: "{{ session('error') }}",
            icon: "error",
            confirmButtonText: "OK",
          });
        });
      </script>
    @endif

    @if(session('success')) 
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
            title: "Sukses!",
            text: "{{ session('success') }}",
            icon: "success",
            confirmButtonText: "OK",
          });
        });
      </script>
    @endif

    @if($errors->any()) 
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
            title: "Peringatan!",
            text: "{{ $errors->first() }}",
            icon: "warning",
            confirmButtonText: "OK",
          });
        });
      </script>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('Pegawai.register.submit') }}" enctype="multipart/form-data" class="row g-4">
      @csrf

      {{-- Nama & NIP --}}
      <div class="col-md-6">
        <div class="form-floating">
          <input type="text" name="nama" id="nama" class="form-control shadow-sm" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
          <label for="nama">Nama Lengkap</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating">
          <input type="text" name="nip" id="nip" class="form-control shadow-sm" placeholder="Nomor Induk Pegawai" value="{{ old('nip') }}" required>
          <label for="nip">NIP</label>
        </div>
      </div>

      {{-- Email & No HP --}}
      <div class="col-md-6">
        <div class="form-floating">
          <input type="email" name="email" id="email" class="form-control shadow-sm" placeholder="nama@contoh.go.id" value="{{ old('email') }}" required>
          <label for="email">Email</label>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-floating">
          <input type="text" name="no_hp" id="no_hp" class="form-control shadow-sm" placeholder="Nomor HP" value="{{ old('no_hp') }}">
          <label for="no_hp">Nomor HP</label>
        </div>
      </div>

      {{-- Unit Kerja --}}
      <div class="col-md-6">
        <div class="form-floating">
          <select name="kode_unitkerja" id="unitKerja" class="form-select shadow-sm" required>
            <option value="" selected>-- Pilih Unit Kerja --</option>
            @foreach($unitKerjas as $u)
              @php
                $labelUk = trim(($u->unitkerja ?? '') . (empty($u->sub_unitkerja) ? '' : ' — '.$u->sub_unitkerja));
              @endphp
              <option value="{{ $u->kode_unitkerja }}" @selected(old('kode_unitkerja') == $u->kode_unitkerja)>
                {{ $labelUk !== '' ? $labelUk : $u->kode_unitkerja }}
              </option>
            @endforeach
          </select>
          <label for="unitKerja">Unit Kerja</label>
        </div>
      </div>

      {{-- Jabatan --}}
      <div class="col-md-6">
        <div class="form-floating">
          <input type="text" name="jabatan" id="jabatan" class="form-control shadow-sm" placeholder="Jabatan" value="{{ old('jabatan') }}">
          <label for="jabatan">Jabatan</label>
        </div>
      </div>

      {{-- Tempat & Tanggal Lahir --}}
      <div class="col-md-6">
        <div class="form-floating">
          <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control shadow-sm" placeholder="Tempat Lahir" value="{{ old('tempat_lahir') }}">
          <label for="tempat_lahir">Tempat Lahir</label>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-floating">
          <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control shadow-sm" value="{{ old('tanggal_lahir') }}">
          <label for="tanggal_lahir">Tanggal Lahir</label>
        </div>
      </div>

      {{-- Golongan --}}
      <div class="col-md-6">
        <div class="form-floating">
          <select name="golongan" id="golongan" class="form-select shadow-sm" required>
            <option value="">-- Pilih Golongan --</option>
            @foreach(['I/a', 'I/b', 'I/c', 'I/d', 'II/a', 'II/b', 'II/c', 'II/d', 'III/a', 'III/b', 'III/c', 'III/d', 'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e'] as $gol)
              <option value="{{ $gol }}" @selected(old('golongan') == $gol)>{{ $gol }}</option>
            @endforeach
          </select>
          <label for="golongan">Golongan</label>
        </div>
      </div>

      {{-- Pangkat --}}
      <div class="col-md-6">
        <div class="form-floating">
          <select name="pangkat" id="pangkat" class="form-select shadow-sm" required>
            <option value="">-- Pilih Pangkat --</option>
            @foreach(['Juru Muda', 'Juru Muda Tingkat I', 'Juru', 'Juru Tingkat I', 'Pengatur Muda', 'Pengatur Muda Tingkat I', 'Pengatur', 'Pengatur Tingkat I', 'Penata Muda', 'Penata Muda Tingkat I', 'Penata', 'Penata Tingkat I', 'Pembina', 'Pembina Tingkat I', 'Pembina Utama Muda', 'Pembina Utama Madya', 'Pembina Utama'] as $pangkat)
              <option value="{{ $pangkat }}" @selected(old('pangkat') == $pangkat)>{{ $pangkat }}</option>
            @endforeach
          </select>
          <label for="pangkat">Pangkat</label>
        </div>
      </div>

      {{-- Alamat --}}
      <div class="col-md-12">
        <div class="form-floating">
          <input type="text" name="alamat" id="alamat" class="form-control shadow-sm" placeholder="Alamat Lengkap" value="{{ old('alamat') }}">
          <label for="alamat">Alamat Lengkap</label>
        </div>
      </div>

      {{-- TMT --}}
      <div class="col-md-6">
        <div class="form-floating">
          <input type="date" name="tmt" id="tmt" class="form-control shadow-sm" value="{{ old('tmt') }}">
          <label for="tmt">TMT</label>
        </div>
      </div>

      {{-- Foto --}}
      <div class="col-md-6">
        <div class="form-floating">
          <input type="file" name="foto" id="foto" class="form-control shadow-sm" accept="image/*">
          <label for="foto">Foto (Opsional)</label>
        </div>
      </div>

      {{-- Buttons --}}
      <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary btn-lg px-4">Kirim Registrasi</button>
        <a href="{{ route('Pegawai.login') }}" class="btn btn-outline-secondary px-4">Sudah punya akun?</a>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- SweetAlert2 (Tambahkan CDN) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>