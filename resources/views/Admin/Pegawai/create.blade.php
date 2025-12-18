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
                            <h3 class="mb-1"><i class="bi bi-person-plus-fill me-2"></i>Tambah Data Pegawai</h3>
                            <p class="mb-0 opacity-75">Masukkan informasi data pegawai baru</p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="bi bi-person-badge" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('Admin.Pegawai.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Foto Profil Section -->
                        <div class="text-center mb-4 pb-4 section-divider">
                            <label class="form-label d-block mb-3">Foto Profil</label>
                            <div class="profile-upload-wrapper">
                                <img src="{{ asset('images/default-profile.png') }}" alt="Default Foto" class="profile-preview" id="preview-image">
                                <label for="foto" class="upload-overlay">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                            </div>
                            <input type="file" class="d-none" id="foto" name="foto" accept="image/*">
                            <small class="text-muted d-block mt-2">Klik pada foto untuk mengunggah</small>
                        </div>

                        <!-- Data Identitas -->
                        <div class="section-title">
                            <i class="bi bi-person-circle"></i>
                            Data Identitas
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP" required>
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Masukkan Tempat Lahir">
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Data Kepegawaian -->
                        <div class="section-title">
                            <i class="bi bi-briefcase"></i>
                            Data Kepegawaian
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="pangkat" class="form-label">Pangkat <span class="text-danger">*</span></label>
                                <select name="pangkat" id="pangkat" class="form-select @error('pangkat') is-invalid @enderror" required>
                                    <option value="">-- Pilih Pangkat --</option>
                                    @foreach(config('pegawai.pangkat') as $pangkat)
                                    <option value="{{ $pangkat }}" {{ old('pangkat') == $pangkat ? 'selected' : '' }}>{{ $pangkat }}</option>
                                    @endforeach
                                </select>
                                @error('pangkat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="golongan" class="form-label">Golongan <span class="text-danger">*</span></label>
                                <select name="golongan" id="golongan" class="form-select @error('golongan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Golongan --</option>
                                    @foreach(config('pegawai.golongan') as $golongan)
                                    <option value="{{ $golongan }}" {{ old('golongan') == $golongan ? 'selected' : '' }}>{{ $golongan }}</option>
                                    @endforeach
                                </select>
                                @error('golongan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: Kepala Seksi" required>
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_asn" class="form-label">Jenis ASN <span class="text-danger">*</span></label>
                                <select name="jenis_asn" id="jenis_asn" class="form-select @error('jenis_asn') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis ASN --</option>
                                    @foreach(config('pegawai.jenis_asn') as $key => $label)
                                    <option value="{{ $key }}" {{ old('jenis_asn') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_asn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="kategori_jabatanasn" class="form-label">Kategori Jabatan ASN <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kategori_jabatanasn') is-invalid @enderror" id="kategori_jabatanasn" name="kategori_jabatanasn" value="{{ old('kategori_jabatanasn') }}" placeholder="Contoh: Fungsional" required>
                                @error('kategori_jabatanasn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="tmt" class="form-label">TMT (Terhitung Mulai Tanggal) <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tmt') is-invalid @enderror" id="tmt" name="tmt" value="{{ old('tmt') }}" required>
                                @error('tmt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Unit Kerja -->
                        <div class="section-title">
                            <i class="bi bi-building"></i>
                            Unit Kerja
                        </div>
                        <div class="mb-4">
                            <label for="kode_unitkerja" class="form-label">Unit / Sub-Unit Kerja <span class="text-danger">*</span></label>
                            
                            @if (auth()->check() && auth()->user()->is_admin == 1)
                                <!-- Superadmin: Bisa memilih -->
                                <select name="kode_unitkerja" id="kode_unitkerja" class="form-select select2 @error('kode_unitkerja') is-invalid @enderror" required>
                                    <option value="">-- Pilih Unit / Sub-Unit --</option>
                                    @foreach($unitKerjaGrouped as $unitName => $subs)
                                        <optgroup label="{{ $unitName }}">
                                            @foreach($subs as $row)
                                                <option value="{{ $row->kode_unitkerja }}"
                                                    {{ old('kode_unitkerja') == $row->kode_unitkerja ? 'selected' : '' }}>
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
                                <!-- Admin biasa: Otomatis menggunakan unit kerja admin (tidak dapat diubah) -->
                                @php
                                    $unit = $unitKerjaGrouped->flatten()->firstWhere('kode_unitkerja', auth()->user()->kode_unitkerja);
                                @endphp
                                <input type="text" class="form-control bg-light" value="{{ $unit->sub_unitkerja ?? 'Tidak diketahui' }} — {{ auth()->user()->kode_unitkerja }}" readonly>
                                <input type="hidden" name="kode_unitkerja" value="{{ auth()->user()->kode_unitkerja }}">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Unit kerja Anda (tidak dapat diubah)
                                </small>
                            @endif
                        </div>

                        <!-- Kontak -->
                        <div class="section-title">
                            <i class="bi bi-envelope"></i>
                            Informasi Kontak
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
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
                                <i class="bi bi-check-circle me-1"></i> Simpan Data
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