@extends('layouts.app')

@section('content')
@php
    $payload = (array) $item->payload;

    // Tentukan role reviewer
    $reviewerRole = null;
    if ($item->reviewed_by == 1) {
        $reviewerRole = 'Super Admin';
    } elseif ($item->reviewed_by == 2) {
        $reviewerRole = 'Admin Unit Kerja';
    }

    // Label status
    $statusLabel = match($item->status) {
        'pending'  => ['Menunggu Verifikasi', 'warning'],
        'approved' => ['Disetujui', 'success'],
        'rejected' => ['Ditolak', 'danger'],
        default    => [$item->status, 'secondary'],
    };
@endphp

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Detail Pengajuan Perubahan Profil</h4>
            <div class="text-muted small">
                Pegawai: <strong>{{ $item->pegawai?->nama }}</strong> — NIP {{ $item->pegawai?->nip }}
            </div>
        </div>
        <a href="{{ route('Admin.pegawai_profile.index') }}" class="btn btn-outline-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    {{-- STATUS --}}
    <div class="card mb-4 border-{{ $statusLabel[1] }}">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center">
            <span class="badge bg-{{ $statusLabel[1] }}">
                {{ $statusLabel[0] }}
            </span>

            @if($reviewerRole)
                <span class="badge bg-primary">
                    Diverifikasi oleh {{ $reviewerRole }}
                </span>
            @endif

            @if($item->reviewed_at)
                <span class="text-muted small">
                    {{ $item->reviewed_at->format('d M Y H:i') }}
                </span>
            @endif
        </div>

        @if($item->review_note)
            <div class="card-footer bg-light small">
                <strong>Catatan Admin:</strong><br>
                {{ $item->review_note }}
            </div>
        @endif
    </div>

    {{-- DATA PERUBAHAN --}}
    <div class="row g-4">
        {{-- DATA USULAN --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <strong>Data Usulan</strong>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        @forelse($payload as $field => $value)
                              <tr>
                                <th width="40%" class="text-muted">
                                    {{ ucfirst(str_replace('_',' ', $field)) }}
                                </th>
                                <td>
                                    @if($field === 'foto' && $value)
                                        <img
                                            src="{{ asset('storage/'.$value) }}"
                                            alt="Foto Usulan"
                                            class="img-thumbnail"
                                            style="max-height:160px"
                                        >
                                    @else
                                        {{ $value ?: '-' }}
                                    @endif
                                </td>
                              </tr>
                            @empty

                            <tr>
                                <td class="text-muted">Tidak ada data usulan.</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>

        {{-- DATA SAAT INI --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <strong>Data Saat Ini</strong>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        @forelse($payload as $field => $value)
                          <tr>
                              <th width="40%" class="text-muted">
                                  {{ ucfirst(str_replace('_',' ', $field)) }}
                              </th>
                              <td>
                                  @if($field === 'foto' && $item->pegawai?->foto)
                                      <img
                                          src="{{ asset('storage/'.$item->pegawai->foto) }}"
                                          alt="Foto Saat Ini"
                                          class="img-thumbnail"
                                          style="max-height:160px"
                                      >
                                  @elseif($field === 'kode_unitkerja' && $item->pegawai?->unitKerja)
                                      {{ $item->pegawai->unitKerja->unitkerja ?? '-' }}
                                      @if($item->pegawai->unitKerja->sub_unitkerja)
                                          — {{ $item->pegawai->unitKerja->sub_unitkerja }}
                                      @endif
                                  @elseif($field === 'id_atasan' && $item->pegawai?->atasan)
                                      {{ $item->pegawai->atasan->nama ?? '-' }} ({{ $item->pegawai->atasan->nip ?? '-' }})
                                  @elseif(in_array($field, ['tempat_lahir', 'tanggal_lahir', 'alamat', 'nama', 'nip', 'email', 'no_hp', 'jabatan', 'pangkat', 'golongan']))
                                      {{ $item->pegawai?->$field ?? '-' }}
                                  @else
                                      {{ $item->pegawai?->$field ?? '-' }}
                                  @endif
                              </td>
                          </tr>
                          @empty

                            <tr>
                                <td class="text-muted">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTION --}}
    @if($item->status === 'pending')
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="mb-3">Tindakan Admin</h6>

                <div class="d-flex flex-wrap gap-3">
                    {{-- APPROVE --}}
                    <form method="POST" action="{{ route('Admin.pegawai_profile.approve', $item->id) }}" class="d-flex gap-2">
                        @csrf
                        <input
                            type="text"
                            name="review_note"
                            class="form-control"
                            placeholder="Catatan persetujuan (opsional)"
                            style="max-width:300px"
                        >
                        <button class="btn btn-success">
                            ✔ Setujui & Terapkan
                        </button>
                    </form>

                    {{-- REJECT --}}
                    <form method="POST" action="{{ route('Admin.pegawai_profile.reject', $item->id) }}" class="d-flex gap-2">
                        @csrf
                        <input
                            type="text"
                            name="review_note"
                            class="form-control"
                            placeholder="Alasan penolakan"
                            style="max-width:300px"
                        >
                        <button class="btn btn-outline-danger">
                            ✖ Tolak
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
