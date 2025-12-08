<!-- resources/views/layouts/headerpegawai.blade.php -->
<header class="pg-header">
  <div class="container-fluid d-flex align-items-center">
    <a href="{{ route('Pelatihan.index') }}" class="pg-title text-decoration-none">
      Portal Pelatihan
    </a>
  </div>
</header>

<!-- spacer agar konten tak ketutupan header fixed -->
<div class="pg-header-spacer" aria-hidden="true"></div>

<style>
  /* Header fixed & responsif terhadap lebar sidebar */
  .pg-header{
    position: fixed; top: 0; left: var(--sb-open);
    width: calc(100% - var(--sb-open));
    background: linear-gradient(135deg, #007bff, #6610f2);
    color: #fff;
    padding: 12px 16px;
    z-index: 1030;
    transition: left .25s ease, width .25s ease;
    border-bottom: 1px solid rgba(255,255,255,.15);
  }
  .pg-title{
    font-weight: 700; font-size: 1.125rem; color: #fff;
  }
  .pg-header-spacer{ height: 56px; }

  /* Saat sidebar collapse (desktop) — class ini dipasang oleh sidebar */
  body.sidebar-collapsed .pg-header{
    left: var(--sb-closed);
    width: calc(100% - var(--sb-closed));
  }

  /* Mobile: sidebar model overlay, header tetap “nyundul” konten */
  @media (max-width: 992px){
    .pg-header{
      left: var(--sb-closed);
      width: calc(100% - var(--sb-closed));
    }
    /* Saat overlay dibuka, header tetap; sidebar berada di atas konten, bukan mendorongnya */
    body.sidebar-open .pg-header{
      left: var(--sb-closed);
      width: calc(100% - var(--sb-closed));
    }
  }
</style>
