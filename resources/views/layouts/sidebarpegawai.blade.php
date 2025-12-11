<aside id="pg-sidebar" class="pg-sidebar">
  {{-- Header sticky: tombol toggle --}}
  <div class="pg-head">
    <button class="pg-toggle" id="pgSidebarToggle" type="button" aria-label="Toggle sidebar">
      <i class="bi bi-list"></i>
    </button>
  </div>

  @if(auth('pegawais')->check())
    {{-- === USER (logged in) === --}}
    @php
      $u = auth('pegawais')->user();
      $inisial = strtoupper(mb_substr($u->nama ?? 'P',0,1));
    @endphp
    <div class="pg-user">
      <div class="pg-avatar">{{ $inisial }}</div>
      <div class="pg-usertext">
        <div class="pg-name text-truncate">{{ $u->nama ?? 'Pegawai' }}</div>
        <div class="pg-nip text-truncate">{{ $u->nip ?? '-' }}</div>
      </div>
    </div>

    <div class="pg-divider"></div>
    
    <nav class="pg-menu">
      <a class="pg-item @if(request()->routeIs('Pelatihan.index')) is-active @endif"
         href="{{ route('Pelatihan.index') }}">
        <i class="bi bi-journal-text"></i><span class="pg-text">Portal Pelatihan</span>
      </a>

      <div class="pg-divider"></div>

      <div class="pg-section">Portal ASN</div>

      <a class="pg-item @if(request()->routeIs('Pegawai.dashboard')) is-active @endif"
         href="{{ route('Pegawai.dashboard') }}">
        <i class="bi bi-speedometer2"></i><span class="pg-text">Dashboard</span>
      </a>

      
      <a class="pg-item @if(request()->routeIs('Pegawai.profil') || request()->routeIs('Pegawai.profil.edit')) is-active @endif"
         href="{{ route('Pegawai.profil') }}">
        <i class="bi bi-person-circle"></i><span class="pg-text">Profil</span>
      </a>

      <div class="pg-divider"></div>

      <form action="{{ route('Pegawai.logout') }}" method="POST" class="mt-auto px-1">
        @csrf
        <button class="pg-item pg-danger w-100" type="submit">
          <i class="bi bi-box-arrow-right"></i><span class="pg-text">Keluar</span>
        </button>
      </form>
    </nav>
  @else
    {{-- === GUEST (belum login) === --}}
    <div class="pg-guest">
      <div class="pg-guest-icon"><i class="bi bi-person-circle"></i></div>
      <div class="pg-guest-text">Anda belum masuk.</div>
    </div>

    <nav class="pg-menu">
      <a class="pg-item pg-primary"
         href="{{ route('Pegawai.login', ['require_email'=>1, 'return_to'=>url()->current()]) }}">
        <i class="bi bi-box-arrow-in-right"></i><span class="pg-text">Login Pegawai</span>
      </a>
    </nav>
  @endif
</aside>

{{-- Styles khusus sidebar pegawai --}}
<style>
  :root{
    --sb-open: 240px;
    --sb-closed: 64px;
    --sb-bg: #343a40;
    --sb-fg: #f8f9fa;
    --sb-muted: #adb5bd;
    --sb-border: #2b3035;
  }

  .pg-sidebar{
    position: fixed; inset: 0 auto 0 0;
    width: var(--sb-open);
    background: var(--sb-bg); color: var(--sb-fg);
    border-right: 1px solid var(--sb-border);
    z-index: 1040;
    transition: width .25s ease;
    display: flex; flex-direction: column; min-width: 0;
  }

  .pg-head{
    position: sticky; top: 0;
    height: 52px; background: var(--sb-bg);
    border-bottom: 1px solid var(--sb-border);
    display: flex; align-items: center; justify-content: flex-end;
    padding: 0 .5rem; z-index: 1;
  }
  .pg-toggle{ width:36px;height:36px;border-radius:.375rem;background:transparent;border:0;color:var(--sb-fg); }
  .pg-toggle:hover{ background: rgba(255,255,255,.08); }

  .pg-user{ display:flex; gap:.75rem; align-items:center; padding:.9rem .75rem .5rem; }
  .pg-avatar{ width:36px;height:36px;display:grid;place-items:center;border-radius:50%;font-weight:700;background:#6c757d; }
  .pg-usertext{ min-width:0; }
  .pg-name{ font-weight:600; }
  .pg-nip{ font-size:.8rem; color: var(--sb-muted); }

  .pg-menu{ padding:.25rem; overflow-y:auto; display:flex; flex-direction:column; gap:2px; }
  .pg-section{ color:var(--sb-muted); font-size:.75rem; text-transform:uppercase; letter-spacing:.04em; padding:.5rem .75rem; }
  .pg-divider{ height:1px; background:var(--sb-border); margin:.5rem .5rem; }

  .pg-item{ display:flex; align-items:center; gap:.75rem; width:100%; padding:.62rem .75rem; border:0; background:none; color:var(--sb-fg); text-decoration:none; border-radius:.5rem; }
  .pg-item i{ font-size:1.1rem; width:1.25rem; text-align:center; }
  .pg-item:hover{ background: rgba(255,255,255,.06); color:#fff; }
  .pg-item.is-active{ background: rgba(13,110,253,.18); color:#fff; }
  .pg-item.pg-danger:hover{ background: rgba(220,53,69,.18); }
  .pg-item.pg-primary{ background: rgba(13,110,253,.18); }
  .pg-item.pg-primary:hover{ background: rgba(13,110,253,.28); }

  /* Guest block */
  .pg-guest{ padding:1rem .75rem .25rem; text-align:center; }
  .pg-guest-icon{ font-size:2.25rem; opacity:.9; margin-bottom:.25rem; }
  .pg-guest-text{ color:var(--sb-muted); font-size:.9rem; }

  /* Shift konten utama */
  .layout .main-container{ margin-left: var(--sb-open); transition: margin-left .25s ease; }

  /* Collapsed (desktop) */
  body.sidebar-collapsed .pg-sidebar{ width: var(--sb-closed); }
  body.sidebar-collapsed .layout .main-container{ margin-left: var(--sb-closed); }
  body.sidebar-collapsed .pg-text,
  body.sidebar-collapsed .pg-section,
  body.sidebar-collapsed .pg-usertext,
  body.sidebar-collapsed .pg-divider{ display:none !important; }
  body.sidebar-collapsed .pg-user{ justify-content:center; padding:.75rem 0 .25rem; }

  /* Mobile: overlay */
  @media (max-width: 992px){
    .layout .main-container{ margin-left: var(--sb-closed); }
    .pg-sidebar{ width: var(--sb-closed); }
    body.sidebar-open{ overflow:hidden; }
    body.sidebar-open .pg-sidebar{ width: var(--sb-open); box-shadow: 0 0 0 9999px rgba(0,0,0,.35); }
    body.sidebar-open .layout .main-container{ margin-left: var(--sb-closed); }
  }
</style>

{{-- JS toggle --}}
<script>
  (function(){
    const btn = document.getElementById('pgSidebarToggle');
    const isMobile = () => window.matchMedia('(max-width: 992px)').matches;
    const KEY = 'pg-sb-collapsed';

    // apply saved collapsed (desktop)
    const saved = localStorage.getItem(KEY);
    if(!isMobile() && saved === '1'){ document.body.classList.add('sidebar-collapsed'); }

    btn?.addEventListener('click', function(){
      if(isMobile()){
        document.body.classList.toggle('sidebar-open');
      }else{
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem(KEY, document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
      }
    });

    // bersihkan overlay saat resize ke desktop
    window.addEventListener('resize', () => {
      if(!isMobile()) document.body.classList.remove('sidebar-open');
    });
  })();
</script>
