@extends('layouts.app')

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    .profile-upload-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }
    .profile-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e9ecef;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .profile-preview:hover {
        border-color: #0d6efd;
        transform: scale(1.05);
    }
    .upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }
    .profile-upload-wrapper:hover .upload-overlay {
        opacity: 1;
    }
    .upload-overlay i {
        color: white;
        font-size: 28px;
    }
    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
    }
    .section-divider {
        border-bottom: 2px solid #f0f0f0;
        margin: 2rem 0 1.5rem 0;
        padding-bottom: 0.5rem;
    }
    .section-title {
        color: #495057;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-title i {
        color: #667eea;
    }
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 0.625rem 0.875rem;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        padding: 0.625rem 2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    .btn-secondary {
        border-radius: 8px;
        padding: 0.625rem 2rem;
        font-weight: 500;
    }
    .readonly-badge {
        display: inline-block;
        background: #e7f3ff;
        color: #0066cc;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-left: 0.5rem;
    }
    @media (max-width: 768px) {
        .form-header {
            padding: 1.5rem;
        }
        .btn-primary, .btn-secondary {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Card -->
            <div class="form-card mb-4">
                <div class="form-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="mb-1"><i class="bi bi-pencil-square me-2"></i>Edit Data Pegawai</h3>
                            <p class="mb-0 opacity-75">Perbarui informasi data pegawai</p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="bi bi-person-badge" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('Admin.Pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Foto Profil Section -->
                        <div class="text-center mb-4 pb-4 section-divider">
                            <label class="form-label d-block mb-3">Foto Profil</label>
                            <div class="profile-upload-wrapper">
                                @if ($pegawai->foto)
                                    <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="Foto Pegawai" class="profile-preview" id="preview-image">
                                @else
                                    <img src="{{ asset('images/default-profile.png') }}" alt="Default Foto" class="profile-preview" id="preview-image">
                                @endif
                                <label for="foto" class="upload-overlay">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                            </div>
                            <input type="file" class="d-none" id="foto" name="foto" accept="image/*">
                            <small class="text-muted d-block mt-2">Klik pada foto untuk mengganti</small>
                        </div>

                        <!-- Data Identitas -->
                        <div class="section-title">
                            <i class="bi bi-person-circle"></i>
                            Data Identitas
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip', $pegawai->nip) }}" required>
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Lahir</label>
                                <input 
                                    type="date" 
                                    name="tanggal_lahir" 
                                    class="form-control" 
                                    value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir ? date('Y-m-d', strtotime($pegawai->tanggal_lahir)) : '') }}">
                            </div>
                        </div>
                        

                        <!-- Data Kepegawaian -->
                        <div class="section-title">
                            <i class="bi bi-briefcase"></i>
                            Data Kepegawaian
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="pangkat" class="form-label">Pangkat</label>
                                <select name="pangkat" id="pangkat" class="form-select" required>
                                    @foreach(config('pegawai.pangkat') as $pangkat)
                                    <option value="{{ $pangkat }}" {{ $pegawai->pangkat == $pangkat ? 'selected' : '' }}>{{ $pangkat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="golongan" class="form-label">Golongan</label>
                                <select name="golongan" id="golongan" class="form-select" required>
                                    @foreach(config('pegawai.golongan') as $golongan)
                                    <option value="{{ $golongan }}" {{ $pegawai->golongan == $golongan ? 'selected' : '' }}>{{ $golongan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" placeholder="Contoh: Kepala Seksi">
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_asn" class="form-label">Jenis ASN</label>
                                <select name="jenis_asn" id="jenis_asn" class="form-select" required>
                                    @foreach(config('pegawai.jenis_asn') as $key => $label)
                                    <option value="{{ $key }}" {{ $pegawai->jenis_asn == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kategori_jabatanasn" class="form-label">Kategori Jabatan ASN</label>
                                <input type="text" class="form-control @error('kategori_jabatanasn') is-invalid @enderror" id="kategori_jabatanasn" name="kategori_jabatanasn" value="{{ old('kategori_jabatanasn', $pegawai->kategori_jabatanasn) }}" placeholder="Contoh: Fungsional">
                                @error('kategori_jabatanasn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">TMT (Terhitung Mulai Tanggal)</label>
                                <input 
                                    type="date" 
                                    name="tmt" 
                                    class="form-control" 
                                    value="{{ old('tmt', $pegawai->tmt ? date('Y-m-d', strtotime($pegawai->tmt)) : '') }}">
                            </div>
                        </div>

                        <!-- Unit Kerja -->
                        <div class="section-title">
                            <i class="bi bi-building"></i>
                            Unit Kerja
                        </div>
                        <div class="mb-4">
                            <label for="kode_unitkerja" class="form-label">
                                Unit / Sub-Unit Kerja
                                @if (auth()->check() && auth()->user()->is_admin == 2)
                                    <span class="readonly-badge">READ ONLY</span>
                                @endif
                            </label>

                            @if (auth()->check() && auth()->user()->is_admin == 1)
                                <!-- Superadmin: Bisa memilih -->
                                <select name="kode_unitkerja" id="kode_unitkerja" class="form-select select2 @error('kode_unitkerja') is-invalid @enderror">
                                    <option value="">-- Pilih Unit / Sub-Unit --</option>
                                    @foreach($unitKerjaGrouped as $unitName => $subs)
                                        <optgroup label="{{ $unitName }}">
                                            @foreach($subs as $row)
                                                <option value="{{ $row->kode_unitkerja }}"
                                                    {{ old('kode_unitkerja', $pegawai->kode_unitkerja) == $row->kode_unitkerja ? 'selected' : '' }}>
                                                    {{ $row->sub_unitkerja }} — {{ $row->kode_unitkerja }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('kode_unitkerja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @elseif (auth()->check() && auth()->user()->is_admin == 2)
                                <!-- Admin biasa: Readonly -->
                                @php
                                    $unit = $unitKerjaGrouped->flatten()->firstWhere('kode_unitkerja', $pegawai->kode_unitkerja);
                                @endphp
                                <input type="text" class="form-control" value="{{ $unit->sub_unitkerja ?? 'Tidak diketahui' }} — {{ $pegawai->kode_unitkerja }}" readonly>
                                <input type="hidden" name="kode_unitkerja" value="{{ $pegawai->kode_unitkerja }}">
                            @endif
                        </div>

                        <!-- Kontak -->
                        <div class="section-title">
                            <i class="bi bi-envelope"></i>
                            Informasi Kontak
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $pegawai->email) }}" placeholder="contoh@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="no_hp" class="form-label">No. HP</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}" placeholder="08xxxxxxxxxx">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat', $pegawai->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end pt-3">
                            <a href="{{ route('Admin.Pegawai.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Pilih Unit / Sub-Unit --',
        allowClear: true
    });

    // Preview image before upload
    const fotoInput = document.getElementById('foto');
    const previewImage = document.getElementById('preview-image');
    
    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>

@push('scripts')
  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
          title: 'Terdapat Kesalahan!',
          html: `
            <div class="text-start">
              <ul class="list-unstyled mb-0">
                @foreach($errors->all() as $error)
                  <li class="mb-1"><i class="bi bi-x-circle-fill text-danger me-2"></i>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          `,
          icon: 'error',
          confirmButtonText: 'Tutup',
          confirmButtonColor: '#667eea'
        });
      });
    </script>
  @endif

  @if(session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
          title: 'Berhasil!',
          text: '{{ session("success") }}',
          icon: 'success',
          confirmButtonText: 'OK',
          confirmButtonColor: '#667eea'
        });
      });
    </script>
  @endif
@endpush

@endsection