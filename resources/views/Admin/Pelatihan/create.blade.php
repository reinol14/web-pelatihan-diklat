@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Buat Sesi Pelatihan</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('Admin.pelatihan.store') }}" class="row g-3">
        @csrf

        {{-- Katalog (opsional) --}}
        <div class="col-12">
            <label for="id_katalog2" class="form-label">Pilih Katalog (opsional)</label>
            <select name="id_katalog2" id="id_katalog2" class="form-select">
                <option value="">-- Pilih (atau kosong untuk input manual) --</option>
                @foreach($katalogs as $k)
                    <option value="{{ $k->id }}"
                        data-nama="{{ $k->nama_pelatihan }}"
                        data-jenis="{{ $k->jenis_pelatihan }}"
                        data-penyelenggara="{{ $k->penyelenggara }}"
                        {{ old('id_katalog2') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_pelatihan }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Jika memilih katalog, Nama/Jenis/Penyelenggara akan terisi otomatis (tetap bisa diubah).</small>
        </div>

        {{-- Nama / Jenis / Penyelenggara --}}
        <div class="col-md-6">
            <label for="nama_pelatihan" class="form-label">Nama Pelatihan</label>
            <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control" value="{{ old('nama_pelatihan') }}">
            @error('nama_pelatihan') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="jenis_pelatihan" class="form-label">Jenis Pelatihan</label>
            <input type="text" name="jenis_pelatihan" id="jenis_pelatihan" class="form-control" value="{{ old('jenis_pelatihan') }}">
            @error('jenis_pelatihan') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="penyelenggara" class="form-label">Penyelenggara</label>
            <input type="text" name="penyelenggara" id="penyelenggara" class="form-control" value="{{ old('penyelenggara') }}">
            @error('penyelenggara') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Kuota --}}
        <div class="col-md-3">
            <label for="kuota" class="form-label">Kuota</label>
            <input type="number" name="kuota" id="kuota" class="form-control" value="{{ old('kuota', 0) }}" min="0" required>
            @error('kuota') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Pelaksanaan (deskripsi teks panjang) --}}
        <div class="col-12">
            <label for="pelaksanaan" class="form-label">Deskripsi Pelaksanaan</label>
            <textarea name="pelaksanaan" id="pelaksanaan" class="form-control" rows="4"
                      placeholder="Contoh: Pertemuan sinkron Senin–Rabu 09.00–12.00; tugas mandiri via LMS; ujian akhir daring.">{{ old('pelaksanaan') }}</textarea>
            @error('pelaksanaan') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            <div class="form-text">Jelaskan mekanisme pelaksanaan/jadwal secara ringkas (boleh kosong).</div>
        </div>

        {{-- Lokasi / Provinsi & Kota --}}
        <div class="col-md-6">
            <label for="lokasi" class="form-label">Lokasi (alamat/rincian)</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" value="{{ old('lokasi') }}">
            @error('lokasi') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label for="provinsi" class="form-label">Provinsi</label>
            <select id="provinsi" name="provinsi_id" class="form-select">
                <option value="">Pilih Provinsi</option>
                @foreach($provinsis ?? [] as $prov)
                    <option value="{{ $prov->id }}" {{ old('provinsi_id') == $prov->id ? 'selected' : '' }}>
                        {{ $prov->nama }}
                    </option>
                @endforeach
            </select>
            @error('provinsi_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label for="kota" class="form-label">Kota/Kabupaten</label>
            <select id="kota" name="kota_id" class="form-select" @disabled(!old('provinsi_id'))>
                <option value="">Pilih Kota</option>
                {{-- opsi akan diisi via JS --}}
            </select>
            @error('kota_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Tanggal --}}
        <div class="col-md-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}">
            @error('tanggal_mulai') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}">
            @error('tanggal_selesai') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Status --}}
        <div class="col-md-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="aktif" {{ old('status','aktif') == 'aktif' ? 'selected' : '' }}>aktif</option>
                <option value="tutup" {{ old('status') == 'tutup' ? 'selected' : '' }}>tutup</option>
            </select>
            @error('status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <button class="btn btn-primary mt-2">Simpan</button>
            <a href="{{ route('Admin.pelatihan.index') }}" class="btn btn-outline-secondary mt-2">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Auto-isi field dari katalog saat dipilih
    const selKatalog = document.getElementById('id_katalog2');
    const namaInput  = document.getElementById('nama_pelatihan');
    const jenisInput = document.getElementById('jenis_pelatihan');
    const penyInput  = document.getElementById('penyelenggara');

    selKatalog?.addEventListener('change', function(){
        const opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) return;
        const dNama  = opt.getAttribute('data-nama');
        const dJenis = opt.getAttribute('data-jenis');
        const dPeny  = opt.getAttribute('data-penyelenggara');

        if (!namaInput.value)  namaInput.value  = dNama  || '';
        if (!jenisInput.value) jenisInput.value = dJenis || '';
        if (!penyInput.value)  penyInput.value  = dPeny  || '';
    });

    // Cascading Provinsi -> Kota
    const allKotas = @json($kotas ?? []); // [{id,nama,provinsi_id}, ...]
    const provSel  = document.getElementById('provinsi');
    const kotaSel  = document.getElementById('kota');
    const oldKota  = @json(old('kota_id'));

    function fillKota(){
        const provId = provSel.value;
        kotaSel.innerHTML = '<option value="">Pilih Kota</option>';
        kotaSel.disabled = !provId;

        if (!provId) return;

        allKotas.forEach(k => {
            if (String(k.provinsi_id) === String(provId)) {
                const selected = String(oldKota) === String(k.id);
                kotaSel.add(new Option(k.nama, k.id, selected, selected));
            }
        });
    }

    provSel?.addEventListener('change', fillKota);

    // init saat halaman dibuka
    fillKota();
});
</script>
@endpush
