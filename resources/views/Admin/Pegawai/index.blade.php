@extends('layouts.app')

@section('title', 'Manajemen Data Pegawai')

@section('content')
<style>
    @media (max-width: 768px) {
        .container {
            padding: 1rem !important;
        }
        
        h1 {
            font-size: 1.5rem !important;
            margin-bottom: 1rem !important;
        }
        
        .column-check {
            display: none;
        }
        
        .d-flex.gap-3 {
            flex-direction: column;
            gap: 0.5rem !important;
            width: 100%;
        }
        
        .d-flex.gap-3 .btn {
            width: 100%;
        }
        
        .card {
            border-radius: 0.5rem;
        }
        
        .table {
            font-size: 0.75rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
            white-space: nowrap;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .btn-sm i {
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        h1 {
            font-size: 1.25rem !important;
        }
        
        .input-group {
            margin-bottom: 0.75rem;
        }
        
        .table {
            font-size: 0.7rem;
        }
        
        .table th,
        .table td {
            padding: 0.4rem 0.2rem;
        }
        
        /* Make action buttons stack vertically on very small screens */
        .dropdown-menu {
            font-size: 0.8rem;
        }
    }
</style>

<div class="container py-4">
    <h1 class="text-center text-primary fw-bold mb-4">Manajemen Data Pegawai</h1>

    <!-- Kotak Pencarian -->
    <div class="mb-3">
        <div class="input-group shadow-sm">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari data berdasarkan kolom...">
            <span class="input-group-text bg-primary text-white">
                <i class="fa fa-search"></i>
            </span>
        </div>
    </div>

    <!-- Filter Kolom dan Tombol Navigasi -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <!-- Filter Kolom -->
        <div class="d-none d-md-block">
            <strong class="text-muted">Tampilkan Kolom:</strong>
            @php
                $columns = [
                    'pangkat_golongan' => 'Pangkat / Golongan',
                    'jabatan' => 'Jabatan',
                    'jenis_asn' => 'Jenis ASN',
                    'kategori_jabatanasn' => 'Kategori Jabatan',
                    'unitkerja' => 'Unit Kerja',
                    'email' => 'Email',
                    'no_hp' => 'No HP',
                    'alamat' => 'Alamat',
                    'tmt' => 'TMT',
                ];
            @endphp
            @foreach ($columns as $key => $label)
                <span class="d-inline-block me-3 mb-2">
                    <input type="checkbox" class="column-check" value="{{ $key }}"> <small>{{ $label }}</small>
                </span>
            @endforeach
        </div>

        <!-- Tombol Navigasi -->
        <div class="d-flex gap-3 align-items-center">
            <a href="{{ route('checkDuplicates') }}" class="btn btn-danger btn-sm shadow-sm">
                <i class="fa fa-layer-group"></i> <span class="d-none d-sm-inline">Cek</span> Duplikasi
            </a>
            <a href="{{ route('Admin.Pegawai.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fa fa-plus-circle"></i> Tambah <span class="d-none d-sm-inline">Data Pegawai</span>
            </a>
        </div>
    </div>

    <!-- Tabel Data Pegawai -->
    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pegawaiTable">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            @foreach ($columns as $key => $label)
                                <th data-column="{{ $key }}" class="d-none">{{ $label }}</th>
                            @endforeach
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pegawais as $index => $pegawai)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pegawai->nip }}</td>
                                <td>{{ $pegawai->nama }}</td>
                                <td data-column="pangkat_golongan" class="d-none">{{ $pegawai->pangkat }} / {{ $pegawai->golongan }}</td>
                                <td data-column="jabatan" class="d-none">{{ $pegawai->jabatan }}</td>
                                <td data-column="jenis_asn" class="d-none">{{ $pegawai->jenis_asn }}</td>
                                <td data-column="kategori_jabatanasn" class="d-none">{{ $pegawai->kategori_jabatanasn }}</td>
                                <td data-column="unitkerja" class="d-none">{{ $pegawai->unitKerja->unitkerja ?? '-' }}</td>
                                <td data-column="email" class="d-none">{{ $pegawai->email }}</td>
                                <td data-column="no_hp" class="d-none">{{ $pegawai->no_hp }}</td>
                                <td data-column="alamat" class="d-none">{{ $pegawai->alamat }}</td>
                                <td data-column="tmt" class="d-none">{{ $pegawai->tmt ? \Carbon\Carbon::parse($pegawai->tmt)->format('d-m-Y') : '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('Admin.Pegawai.show', $pegawai->getKey()) }}" class="btn btn-info btn-sm shadow-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('Admin.Pegawai.edit', $pegawai->getKey()) }}" class="btn btn-warning btn-sm shadow-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm shadow-sm delete-btn" data-id="{{ $pegawai->getKey() }}" data-name="{{ $pegawai->nama }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center py-4">Tidak ada data pegawai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // SweetAlert2 Konfirmasi Penghapusan
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;

            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                html: `Apakah Anda yakin ingin menghapus pegawai <strong>${name}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                allowOutsideClick: false
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/admin/pegawai/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    }).then(async response => {
                        const data = await response.json();

                        if (response.ok) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Pegawai telah berhasil dihapus.',
                                icon: 'success'
                            });

                            const row = document.querySelector(`button[data-id="${id}"]`).closest('tr');
                            if (row) row.remove();
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message || 'Terjadi masalah saat menghapus data.',
                                icon: 'error'
                            });
                        }
                    }).catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi masalah dengan server. Silakan coba lagi.',
                            icon: 'error'
                        });
                        console.error('Fetch Error:', error);
                    });
                }
            });
        });
    });

    // Filter Kolom
    const checkboxes = document.querySelectorAll('.column-check');

    function toggleColumn(columnKey, show) {
        const elements = document.querySelectorAll(`[data-column="${columnKey}"]`);
        elements.forEach(el => {
            el.classList.toggle('d-none', !show);
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            toggleColumn(this.value, this.checked);
        });

        toggleColumn(cb.value, cb.checked);
    });

    // Fungsi Pencarian
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function () {
        const keyword = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('#pegawaiTable tbody tr');

        rows.forEach(row => {
            let visible = false;
            const nip = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const nama = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

            if (nip.includes(keyword) || nama.includes(keyword)) visible = true;

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const cells = row.querySelectorAll(`[data-column="${cb.value}"]`);
                    cells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(keyword)) visible = true;
                    });
                }
            });

            row.style.display = visible ? '' : 'none';
        });
    });
});
</script>
@endpush