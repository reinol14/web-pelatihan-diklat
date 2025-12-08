@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manajemen Sesi Pelatihan</h1>

    <div class="row mb-3">
        <div class="col-md-8">
            <!-- Filter: provinsi, kota, jenis, penyelenggara -->
            <form method="GET" action="{{ route('Admin.pelatihan.index') }}" class="row g-2">
                <div class="col-md-3">
                    <label for="provinsi" class="form-label">Provinsi</label>
                    <select id="provinsi" name="provinsi" class="form-select">
                        <option value="">Pilih Provinsi</option>
                        @foreach(($provinsis ?? []) as $prov)
                            <option value="{{ $prov->id }}" @selected(request('provinsi') == $prov->id)>{{ $prov->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="kota" class="form-label">Kota / Kabupaten</label>
                    <select id="kota" name="kota" class="form-select" @disabled(!request('provinsi'))>
                        <option value="">Pilih Kota</option>
                        {{-- opsi kota diisi via JS --}}
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="jenis_pelatihan" class="form-label">Jenis Pelatihan</label>
                    <select id="jenis_pelatihan" name="jenis_pelatihan" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach(($jenisList ?? []) as $jenis)
                            <option value="{{ $jenis }}" @selected(request('jenis_pelatihan') == $jenis)>{{ $jenis }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="penyelenggara" class="form-label">Penyelenggara</label>
                    <select id="penyelenggara" name="penyelenggara" class="form-select">
                        <option value="">Semua Penyelenggara</option>
                        @foreach(($penyelenggaraList ?? []) as $p)
                            <option value="{{ $p }}" @selected(request('penyelenggara') == $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 d-flex gap-2 mt-1">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('Admin.pelatihan.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="col-md-4 text-end align-self-end">
            <a href="{{ route('Admin.pelatihan.create') }}" class="btn btn-primary">Buat Sesi Baru</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Pelatihan (Katalog)</th>
                    <th>Jenis</th>
                    <th>Penyelenggara</th>
                    <th>Kuota</th>
                    <th>Provinsi</th>
                    <th>Kota/Kab.</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th style="width:140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    use Illuminate\Support\Str;
                @endphp

                @forelse($sessions as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ optional($s->katalog)->nama_pelatihan ?? $s->nama_pelatihan ?? '-' }}</td>
                    <td>{{ $s->jenis_pelatihan ?? optional($s->katalog)->jenis_pelatihan ?? '-' }}</td>
                    <td>{{ $s->penyelenggara ?? optional($s->katalog)->penyelenggara ?? '-' }}</td>
                    <td>{{ $s->kuota }}</td>
                    <td>{{ optional($s->provinsi)->nama ?? '-' }}</td>
                    <td>{{ optional($s->kota)->nama ?? '-' }}</td>
                    <td>{{ $s->lokasi ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $s->status === 'aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">
                            {{ $s->status }}
                        </span>
                    </td>
                        <td>
                            <a href="{{ route('Admin.pelatihan.edit', $s->id) }}" 
                            class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('Admin.pelatihan.destroy', $s->id) }}" 
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Hapus sesi?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>

                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center">Data pelatihan tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pertahankan filter saat paging --}}
    @if(method_exists($sessions, 'links'))
        {{ $sessions->appends(request()->query())->links() }}
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const allKotas = @json($kotas ?? []); // [{id,nama,provinsi_id}, ...]
    const provSel  = document.getElementById('provinsi');
    const kotaSel  = document.getElementById('kota');
    const reqKota  = @json(request('kota'));

    function fillKota(){
        const provId = provSel.value;
        kotaSel.innerHTML = '<option value="">Pilih Kota</option>';
        kotaSel.disabled = !provId;

        if (!provId) return;

        allKotas.forEach(k => {
            if (String(k.provinsi_id) === String(provId)) {
                const opt = new Option(k.nama, k.id, false, String(reqKota) === String(k.id));
                kotaSel.add(opt);
            }
        });
    }

    provSel?.addEventListener('change', function(){
        // reset kota ketika provinsi berubah
        fillKota();
    });

    // init saat halaman dibuka
    fillKota();
});
</script>
@endpush
