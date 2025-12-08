<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title','Dashboard')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

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

  @stack('styles')

  <style>
    body.no-sidebar .layout .main-container{ margin-left:0 !important; }
    body.no-sidebar #pg-sidebar{ display:none !important; }
    /* Layout dasar agar cocok dengan sidebar custom-mu */
    body{ background:#f4f6f9; }
    .layout{ min-height:100vh; }
    .main-container{
      position:relative;
      min-height:100vh;
      margin-left:250px; /* lebar sidebar normal */
      padding:20px;
      transition:all .3s ease;
      background:#f4f6f9;
    }
    /* Saat sidebar dikompak (class .closed pada sidebar), konten bergeser */
    .sidebar.closed + .main-container{ margin-left:60px; }
    main.content{ min-height: calc(100vh - 120px); } /* ruang untuk footer/header */
    @media (max-width: 768px){
      .main-container{ margin-left:60px; }
    }
  </style>
</head>
<body>
  <div class="layout">
    {{-- Sidebar pegawai (fixed kiri) --}}
    @include('layouts.sidebarpegawai')

    {{-- Konten utama --}}
    <div class="main-container">
      {{-- Header opsional (hapus baris ini jika tidak punya partialnya) --}}
      @includeWhen(View::exists('layouts.headerpegawai'), 'layouts.headerpegawai')

      {{-- Flash messages global --}}
      {{-- di layouts.* tepat setelah navbar --}}
<div class="container mt-3">
  @foreach (['success','error','info','warning'] as $f)
    @if (session($f))
      <div class="alert alert-{{ $f === 'error' ? 'danger' : $f }} alert-dismissible fade show" role="alert">
        {!! session($f) !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
      </div>
    @endif
  @endforeach
</div>


      {{-- Isi halaman --}}
      <main class="content">
        @yield('content')
      </main>

      <footer class="py-4 text-center text-muted small">
        &copy; {{ date('Y') }} — BKPSDM
      </footer>
    </div>
  </div>

  {{-- Bootstrap JS bundle --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Select2 (opsional) --}}
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

  {{-- JS lokal (opsional) --}}
  <script src="{{ asset('js/script.js') }}"></script>

  @yield('scripts')
  @stack('scripts')
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const btn = document.getElementById('pgSidebarToggle');
  btn?.addEventListener('click', function(){
    document.body.classList.toggle('pg-collapsed');
  });
});
</script>
