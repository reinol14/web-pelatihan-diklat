@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">Daftar Usulan Laporan</h2>

        <!-- Tombol Tambah Data -->
        <a href="{{ route('laporan.createusulan') }}" class="btn btn-success mb-3">
            <i class="fa fa-plus"></i> Tambah Data
        </a>

        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('laporan.usulan') }}" class="row g-3 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Cari nama laporan...">
            </div>
            <div class="col-md-4">
                <select name="tahun" class="form-select">
                    <option value="">-- Pilih Tahun --</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa fa-search"></i> Cari
                </button>
            </div>
            </form>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Judul Laporan</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Nama Pelatihan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($directory_2_laporans as $item)
                            <tr>
                                <td>
                                    {{ ($directory_2_laporans->firstItem() ?? 0) + $loop->iteration - 1 }}
                                </td>
                                <td>
                                    {{ $item->judul_laporan }}
                                </td>
                                <td>
                                    {{ $item->nama }}
                                </td>
                                <td>
                                    {{ $item->email }}
                                </td>
                                <td>
                                    {{ $item->nama_pelatihan }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('Y-m-d') }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('Y-m-d') }}
                                </td>
                                <td>
                                    <span class="badge {{ $item->hasil_pelatihan == 'lulus' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($item->hasil_pelatihan) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('laporan.editlaporan', $item->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('deleteusulanlaporan', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE') <!-- ✅ Ganti GET jadi DELETE -->
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>



                                        <!-- Tombol Approve -->
                                        <form action="{{ route('approvelaporan', $item->id) }}" method="GET"
                                            onsubmit="return confirm('Yakin ingin menyetujui?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $directory_2_laporans->links() }}
        </div>
    </div>
@endsection
