@extends('layouts.app')

@section('title', 'Pengecekan Data Pegawai')

@section('content')
<div class="container py-5">
    <h1 class="h4 text-center text-primary fw-bold mb-4">Pengecekan Data Pegawai</h1>

    <div class="row g-4 justify-content-center">
        {{-- Informasi Duplikasi --}}
        @php
            $duplicates = [
                'nip' => ['label' => 'NIP', 'count' => $nipDuplicates->count(), 'data' => $nipDuplicates, 'icon' => 'fa-id-card'],
                'email' => ['label' => 'Email', 'count' => $emailDuplicates->count(), 'data' => $emailDuplicates, 'icon' => 'fa-envelope'],
                'no_hp' => ['label' => 'Nomor HP', 'count' => $phoneDuplicates->count(), 'data' => $phoneDuplicates, 'icon' => 'fa-phone'],
            ];
        @endphp
        
        @foreach($duplicates as $key => $dup)
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fa {{ $dup['icon'] }} fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">{{ $dup['label'] }}</h5>
                    <p class="h6 mb-3">{{ $dup['count'] }} Duplikasi</p>
                    @if($dup['count'] > 0)
                    <button class="btn btn-primary btn-sm view-details" data-key="{{ $key }}" data-label="{{ $dup['label'] }}" data-data="{{ json_encode($dup['data']) }}">
                        <i class="fa fa-eye"></i> Lihat Detail
                    </button>
                    @else
                    <p class="text-success mb-0">Tidak ada duplikasi.</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <hr class="my-5">

    {{-- Bagian Data Kosong --}}
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark fw-bold">
            <i class="fa fa-exclamation-circle"></i> Data Kosong
        </div>
        <div class="card-body">
            @if($emptyFields->isEmpty())
                <p class="text-success mb-0">Tidak ada data kosong ditemukan.</p>
            @else
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>NIP</th>
                            <th>Email</th>
                            <th>Nomor HP</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($emptyFields as $row)
                        <tr>
                            <td>{{ $row->nip ?? '-' }}</td>
                            <td>{{ $row->email ?? '-' }}</td>
                            <td>{{ $row->no_hp ?? '-' }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->jabatan }}</td>
                            <td class="text-center">
                                <a href="{{ route('Admin.Pegawai.edit', $row->id ?? $row->nip) }}" 
                                   class="btn btn-sm btn-warning" 
                                   title="Edit data pegawai">
                                    <i class="fa fa-edit me-1"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailButtons = document.querySelectorAll('.view-details');

        detailButtons.forEach(button => {
            button.addEventListener('click', function () {
                const key = this.dataset.key;
                const label = this.dataset.label;
                const data = JSON.parse(this.dataset.data);

                let htmlTable = `
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                ${key === 'nip' ? '<th>NIP</th>' : ''}
                                ${key === 'email' ? '<th>Email</th>' : ''}
                                ${key === 'no_hp' ? '<th>Nomor HP</th>' : ''}
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.forEach(row => {
                    htmlTable += `<tr>
                        ${key === 'nip' ? `<td>${row.nip}</td>` : ''}
                        ${key === 'email' ? `<td>${row.email}</td>` : ''}
                        ${key === 'no_hp' ? `<td>${row.no_hp}</td>` : ''}
                        <td>${row.nama}</td>
                        <td>${row.jabatan}</td>
                        <td class="text-center">
                            <a href="/admin/pegawai/${row.id || row.nip}/edit" 
                               class="btn btn-sm btn-primary" 
                               target="_blank"
                               title="Edit data pegawai">
                                <i class="fa fa-edit me-1"></i> Edit
                            </a>
                        </td>
                    </tr>`;
                });

                htmlTable += `</tbody></table>`;

                // SweetAlert2 Popup
                Swal.fire({
                    title: `Detail Duplikasi ${label}`,
                    html: htmlTable,
                    width: '90%',
                    confirmButtonText: 'Tutup',
                });
            });
        });
    });
</script>
@endpush