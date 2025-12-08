@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Data Pegawai</h4>

    <!-- Search Bar -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan kolom yang tampil...">
    </div>

    <!-- Filter Kolom -->
    <div class="mb-3">
        <strong>Tampilkan Kolom:</strong><br>
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
            <label class="me-2">
                <input type="checkbox" class="column-check" value="{{ $key }}"> {{ $label }}
            </label>
        @endforeach

    </div>

    <!-- Tabel -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" id="pegawaiTable">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    @foreach ($columns as $key => $label)
                        <th data-column="{{ $key }}">{{ $label }}</th>
                    @endforeach
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pegawais as $index => $pegawai)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pegawai->nip }}</td>
                        <td>{{ $pegawai->nama }}</td>
                        <td data-column="pangkat_golongan">{{ $pegawai->pangkat }} / {{ $pegawai->golongan }}</td>
                        <td data-column="jabatan">{{ $pegawai->jabatan }}</td>
                        <td data-column="jenis_asn">{{ $pegawai->jenis_asn }}</td>
                        <td data-column="kategori_jabatanasn">{{ $pegawai->kategori_jabatanasn }}</td>
                        <td data-column="unitkerja">{{ $pegawai->unitKerja->unitkerja ?? '-' }}</td>
                        <td data-column="email">{{ $pegawai->email }}</td>
                        <td data-column="no_hp">{{ $pegawai->no_hp }}</td>
                        <td data-column="alamat">{{ $pegawai->alamat }}</td>
                        <td data-column="tmt">{{ $pegawai->tmt ? \Carbon\Carbon::parse($pegawai->tmt)->format('d-m-Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('Admin.Pegawai.show', $pegawai->getKey()) }}" class="btn btn-sm btn-info">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('Admin.Pegawai.edit', $pegawai->getKey()) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('Admin.Pegawai.destroy', $pegawai->getKey()) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="text-center">Tidak ada data pegawai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.column-check');

    // Fungsi untuk menampilkan atau menyembunyikan kolom
    function toggleColumn(columnKey, show) {
        const elements = document.querySelectorAll(`[data-column="${columnKey}"]`);
        elements.forEach(el => {
            el.style.display = show ? '' : 'none';
        });
    }

    // Menyembunyikan semua kolom saat pertama kali
    checkboxes.forEach(cb => {
        toggleColumn(cb.value, false);
    });

    // Menambahkan event listener untuk checkbox
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            toggleColumn(this.value, this.checked);
        });
    });

    // Fungsi pencarian
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('#pegawaiTable tbody tr');

        rows.forEach(row => {
            let visible = false;

            // Memeriksa setiap checkbox yang dicentang
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const cells = row.querySelectorAll(`[data-column="${cb.value}"]`);
                    cells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(keyword)) {
                            visible = true;
                        }
                    });
                }
            });

            // NIP dan Nama wajib selalu bisa dicari
            const nip = row.children[1]?.textContent.toLowerCase();
            const nama = row.children[2]?.textContent.toLowerCase();
            if (nip.includes(keyword) || nama.includes(keyword)) visible = true;

            row.style.display = visible ? '' : 'none';
        });
    });
});

</script>
@endpush
