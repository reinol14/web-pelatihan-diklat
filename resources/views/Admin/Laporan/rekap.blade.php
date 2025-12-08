@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Rekap Data</h2>

    {{-- Dropdown Filter --}}
    <div class="d-flex justify-content-center mb-4">
        <form method="GET" action="{{ route('laporan.rekap') }}" class="d-flex gap-2">
            <select name="filter" class="form-select" onchange="this.form.submit()">
                <option value="brosur" {{ $filter == 'brosur' ? 'selected' : '' }}>Brosur</option>
                <option value="direktori" {{ $filter == 'direktori' ? 'selected' : '' }}>Direktori</option>
                <option value="katalog" {{ $filter == 'katalog' ? 'selected' : '' }}>Katalog</option>
            </select>
        </form>
    </div>

    {{-- Tampilkan Brosur Jika Filter Dipilih --}}
    @if($filter == 'brosur')
        {{-- Kotak Indikator Brosur --}}
        <div class="row text-center">
            <div class="col-md-3">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h5>Diterima</h5>
                        <h3>{{ $jumlahDiterima }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        <h5>Ditolak</h5>
                        <h3>{{ $jumlahDitolak }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark shadow">
                    <div class="card-body">
                        <h5>Diproses</h5>
                        <h3>{{ $jumlahDiproses }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        <h5>Total Data</h5>
                        <h3>{{ $totalDataBrosur }}</h3>
                    </div>
                </div>
            </div>
        </div>
        

        {{-- Tabel Brosur --}}
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Rekap Data Brosur</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Diterima</th>
                            <th>Ditolak</th>
                            <th>Diproses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapBrosur as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->tahun }}</td>
                            <td>{{ $data->jumlah_diterima ?? 0 }}</td>
                            <td>{{ $data->jumlah_ditolak ?? 0 }}</td>
                            <td>{{ $data->jumlah_diproses ?? 0 }}</td>
                        </tr>
                        @endforeach
                        @if(count($rekapBrosur) == 0)
                        <tr>
                            <td colspan="5">Tidak ada data tersedia</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Tampilkan Direktori Jika Filter Dipilih --}}
    @if($filter == 'direktori')

    <div class="row text-center">
            <div class="col-md-3">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h5>Lulus</h5>
                        <h3>{{ $totalLulus }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        <h5>Tidak Lulus</h5>
                        <h3>{{ $totalTidakLulus }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        <h5>Total Data</h5>
                        <h3>{{ $totalDataDirektori }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Tabel Direktori --}}
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Rekap Data Direktori</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Nama Pelatihan</th>
                            <th>Lulus</th>
                            <th>Tidak Lulus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapDirektori as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->tahun }}</td>
                            <td>{{ $data->nama_pelatihan }}</td>
                            <td>{{ $data->jumlah_lulus ?? 0 }}</td>
                            <td>{{ $data->jumlah_tidak_lulus ?? 0 }}</td>
                        </tr>
                        @endforeach
                        @if(count($rekapDirektori) == 0)
                        <tr>
                            <td colspan="5">Tidak ada data tersedia</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($filter == 'katalog')

    <div class="row text-center">
            <div class="col-md-3">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h5>Show</h5>
                        <h3>{{ $totalShow }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        <h5>Hide</h5>
                        <h3>{{ $totalHide }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        <h5>Total Data</h5>
                        <h3>{{ $totalDataKatalog }}</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Tabel Direktori --}}
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Rekap Data Direktori</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Jenis Pelatihan</th>
                            <th>Visible</th>
                            <th>Hide</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapKatalog as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->tahun }}</td>
                            <td>{{ $data->jenis_pelatihan }}</td>
                            <td>{{ $data->jumlah_show ?? 0 }}</td>
                            <td>{{ $data->jumlah_hide ?? 0 }}</td>
                        </tr>
                        @endforeach
                        @if(count($rekapKatalog) == 0)
                        <tr>
                            <td colspan="5">Tidak ada data tersedia</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
