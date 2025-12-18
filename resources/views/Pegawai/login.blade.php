@include('layouts.headerpegawai')
<style>
  /* Body Container */
  .login-wrap {
    min-height: 100vh;
    background: 
      radial-gradient(120% 120% at 100% 0, #ece9ff 0%, #f7f7ff 35%, #ffffff 70%), 
      linear-gradient(135deg, #6f42c1 0%, #0d6efd 100%);
    background-blend-mode: screen, normal;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 16px;
  }

  /* Login Card */
  .login-card {
    width: 420px;
    max-width: 92vw;
    border: none;
    border-radius: 16px;
    background: rgba(255, 255, 255, .95);
    backdrop-filter: blur(8px);
    box-shadow: 0 15px 40px rgba(24, 16, 63, .15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .login-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 60px rgba(24, 16, 63, .2);
  }

  /* Brand Mark */
  .brand-mark {
    width: 52px;
    height: 52px;
    display: grid;
    place-items: center;
    border-radius: 12px;
    background: linear-gradient(135deg, #6f42c1, #0d6efd);
    color: #fff;
    font-size: 1.25rem;
    font-weight: 700;
  }

  /* Button Style */
  .btn-gradient {
    background: linear-gradient(135deg, #6f42c1, #0d6efd);
    border: none;
    color: #fff;
    font-weight: 600;
    transition: box-shadow 0.3s ease;
  }

  .btn-gradient:hover {
    box-shadow: 0px 4px 12px rgba(0, 123, 255, .3);
    filter: brightness(0.95);
    color: #fff;
  }

  /* Link Style */
  .small a {
    color: #0d6efd;
  }
  .small a:hover {
    text-decoration: underline;
  }

  /* Placeholder Text */
  .form-floating input::placeholder {
    opacity: 0.5;
  }

  /* Icon Style */
  .input-icon {
    position: absolute;
    left: 15px;
    top: 13px;
    color: #6c757d;
    font-size: 1.2rem;
  }

  .form-control {
    padding-left: 2.5rem;
  }
</style>

<div class="login-wrap">
  <div class="card login-card p-4 p-md-5">
    <div class="d-flex align-items-center gap-3 mb-4">
      <div class="brand-mark">PG</div>
      <div>
        <h1 class="h4 mb-0 text-primary">Portal Pegawai</h1>
        <div class="text-muted small">Login untuk melanjutkan</div>
      </div>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
      <div class="alert alert-danger mb-3">
        <i class="fa fa-exclamation-circle me-2"></i>{{ $errors->first() }}
      </div>
    @endif

    {{-- Login Form --}}
    <form method="POST" action="{{ route('Pegawai.login.submit') }}" class="mt-3">
      @csrf

      {{-- Input NIP --}}
      <div class="position-relative mb-3">
        <span class="input-icon"><i class="fa fa-id-card"></i></span>
        <input type="text" name="nip" id="nip" class="form-control form-floating" placeholder="NIP" value="{{ old('nip') }}" required>
        <label for="nip">NIP</label>
        @error('nip') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      {{-- Input Email --}}
      <div class="position-relative mb-3">
        <span class="input-icon"><i class="fa fa-envelope"></i></span>
        <input type="email" name="email" id="email" class="form-control form-floating" placeholder="nama@contoh.go.id" value="{{ old('email') }}" required>
        <label for="email">Email</label>
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      @if(!empty($returnTo))
        <input type="hidden" name="return_to" value="{{ $returnTo }}">
      @endif
      <input type="hidden" name="require_email" value="1">

      {{-- Submit Button --}}
      <button type="submit" class="btn btn-gradient w-100 py-2 shadow-lg">
        <i class="fa fa-sign-in-alt me-2"></i> Login
      </button>

      <div class="text-muted small text-center mt-3">
        Sistem akan memverifikasi NIP & Email Anda.
      </div>
    </form>

    {{-- Link ke Registrasi --}}
    <div class="text-center mt-4">
      <span class="small text-muted">Belum punya akun?</span>
      <a href="{{ route('Pegawai.register') }}" class="small fw-semibold text-decoration-none">
        Daftar sekarang
      </a>
    </div>

    {{-- Tombol Kembali --}}
    <div class="text-center mt-4">
      <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-100 py-2">
        <i class="fa fa-arrow-left me-2"></i> Kembali
      </a>
    </div>
  </div>
</div>

{{-- External Dependencies --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

{{-- CSS Lokal --}}
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/Pegawai.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

{{-- Select2 (opsional jika digunakan) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>