@include('layouts.headerpegawai')
<style>
  .login-wrap{
    min-height:100vh;
    background:
      radial-gradient(120% 120% at 100% 0, #ece9ff 0%, #f7f7ff 35%, #ffffff 70%),
      linear-gradient(135deg, #6f42c1 0%, #0d6efd 100%);
    background-blend-mode: screen, normal;
    display:flex;align-items:center;justify-content:center;padding:32px 16px;
  }
  .login-card{
    width: 420px; max-width: 92vw;
    border: none; border-radius: 16px;
    background: rgba(255,255,255,.9);
    backdrop-filter: blur(6px);
    box-shadow: 0 18px 60px rgba(24,16,63,.18);
  }
  .brand-mark{
    width: 52px; height: 52px; display:grid; place-items:center;
    border-radius: 12px; background: linear-gradient(135deg, #6f42c1, #0d6efd);
    color:#fff; font-weight:700;
  }
  .btn-gradient{ background: linear-gradient(135deg, #6f42c1, #0d6efd); border: none; color:#fff; font-weight:600; }
  .btn-gradient:hover{ filter:brightness(.97); color:#fff; }
</style>

<div class="login-wrap">
  <div class="card login-card p-4 p-md-5">
    <div class="d-flex align-items-center gap-3 mb-3">
      <div class="brand-mark">PG</div>
      <div>
        <h1 class="h4 mb-0">Portal Pegawai</h1>
        <div class="text-muted small">Masuk untuk melanjutkan</div>
      </div>
    </div>

    @if($errors->any())
      <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('Pegawai.login.submit') }}" class="mt-2">
      @csrf

      <div class="form-floating mb-3">
        <input type="text" name="nip" id="nip" class="form-control" placeholder="NIP" value="{{ old('nip') }}" required>
        <label for="nip">NIP</label>
      </div>
      @error('nip') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

      <div class="form-floating mb-3">
        <input type="email" name="email" id="email" class="form-control" placeholder="nama@contoh.go.id" value="{{ old('email') }}" required>
        <label for="email">Email</label>
      </div>
      @error('email') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

      @if(!empty($returnTo))
        <input type="hidden" name="return_to" value="{{ $returnTo }}">
      @endif
      <input type="hidden" name="require_email" value="1">

      <button type="submit" class="btn btn-gradient w-100 py-2">Login</button>

      <div class="text-muted small text-center mt-3">
        Sistem akan memverifikasi NIP & Email Anda.
      </div>
    </form>

    {{-- Tambah link ke registrasi --}}
    <div class="text-center mt-3">
      <span class="small text-muted">Belum punya akun?</span>
      <a href="{{ route('Pegawai.register') }}" class="small fw-semibold text-decoration-none">
        Daftar sekarang
      </a>
    </div>

  </div>
</div>
{{-- Font Awesome (1x saja) --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

  {{-- Bootstrap (CSS) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- CSS lokal (opsional, sesuaikan kebutuhanmu) --}}
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"><!-- jika kamu punya tema lokal -->
  <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/Pegawai.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  {{-- Select2 (opsional, jika dipakai) --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
