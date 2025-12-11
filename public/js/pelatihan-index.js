// public/js/pelatihan-index.js

document.addEventListener('DOMContentLoaded', function () {
  // Get login status from Laravel
  const isLoggedIn = window.authPegawaisCheck || false;
  
  // Initialize tooltips
  initializeTooltips();
  
  // Setup filter form loading state
  setupFilterFormLoadingState();
  
  // Setup dependent select for Kota based on Provinsi
  setupDependentKotaSelect();
  
  // Setup registration button handlers
  setupRegistrationHandlers();
  
  // Show flash messages
  showFlashMessages();
});

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
}

/**
 * Setup loading state for filter form
 */
function setupFilterFormLoadingState() {
  const formFilter = document.getElementById('filterForm');
  const btnFilter = document.getElementById('btnFilter');
  
  if (!formFilter || !btnFilter) return;
  
  formFilter.addEventListener('submit', function () {
    btnFilter.setAttribute('disabled', true);
    btnFilter.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menerapkan...';
  });
}

/**
 * Setup dependent Kota select based on Provinsi selection
 */
function setupDependentKotaSelect() {
  const allKotas = window.kotasList || [];
  const provSel = document.getElementById('f-provinsi');
  const kotaSel = document.getElementById('f-kota');
  
  if (!provSel || !kotaSel) return;
  
  /**
   * Fill kota options based on selected provinsi
   */
  function fillKotaFromProv(init = false) {
    const selectedProv = provSel.value || provSel.dataset.selectedProvinsi || '';
    const selectedKotaReq = kotaSel.dataset.selectedKota || '';
    
    // Reset kota select
    kotaSel.innerHTML = '<option value="">Semua</option>';
    kotaSel.disabled = !selectedProv;
    
    if (!selectedProv) return;
    
    // Populate kota options based on selected provinsi
    allKotas.forEach(k => {
      if (String(k.provinsi_id) === String(selectedProv)) {
        const isSelected = init && String(selectedKotaReq) === String(k.id);
        kotaSel.add(new Option(k.nama, k.id, isSelected, isSelected));
      }
    });
  }
  
  // Event listener for provinsi change
  provSel.addEventListener('change', function () {
    if (kotaSel) kotaSel.dataset.selectedKota = '';
    fillKotaFromProv(false);
  });
  
  // Initialize kota options when offcanvas is shown
  const offcanvasEl = document.getElementById('offcanvasFilter');
  if (offcanvasEl) {
    offcanvasEl.addEventListener('shown.bs.offcanvas', () => fillKotaFromProv(true));
  }
}

/**
 * Setup registration button handlers
 */
function setupRegistrationHandlers() {
  const isLoggedIn = window.authPegawaisCheck || false;
  
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-daftar');
    if (!btn) return;
    
    // Check if user is logged in
    if (!isLoggedIn) {
      e.preventDefault();
      handleNotLoggedIn();
      return;
    }
    
    // Fill modal with training data
    fillModalData(btn);
  });
}

/**
 * Handle not logged in scenario
 */
function handleNotLoggedIn() {
  const loginUrl = window.loginUrl || '/pegawai/login';
  
  // Use SweetAlert if available, otherwise use native confirm
  if (typeof Swal === 'undefined') {
    if (confirm('Anda harus login untuk mendaftar. Pergi ke halaman login?')) {
      window.location.href = loginUrl;
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
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = loginUrl;
    }
  });
}

/**
 * Fill modal with training data
 */
function fillModalData(btn) {
  const form = document.getElementById('formJoin');
  if (form && window.joinRouteTemplate) {
    form.action = window.joinRouteTemplate.replace('___ID___', btn.dataset.sessionId);
  }
  
  // Helper function to set text content
  const setText = (id, val) => {
    const el = document.getElementById(id);
    if (el) el.textContent = val || '-';
  };
  
  setText('m-nama', btn.dataset.namaPelatihan);
  setText('m-jenis', btn.dataset.jenis);
  setText('m-lokasi', btn.dataset.lokasi);
  setText('m-tanggal', btn.dataset.tanggal);
}

/**
 * Show flash messages using SweetAlert
 */
function showFlashMessages() {
  if (typeof Swal === 'undefined') return;
  
  const flashMessages = window.flashMessages || {};
  
  if (flashMessages.success) {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: flashMessages.success,
      confirmButtonColor: '#3085d6'
    });
  }
  
  if (flashMessages.error) {
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: flashMessages.error,
      confirmButtonColor: '#d33'
    });
  }
  
  if (flashMessages.info) {
    Swal.fire({
      icon: 'info',
      title: 'Info',
      text: flashMessages.info
    });
  }
  
  if (flashMessages.warning) {
    Swal.fire({
      icon: 'warning',
      title: 'Peringatan',
      text: flashMessages.warning
    });
  }
}