@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Sesi Pelatihan</h1>

    {{-- Flash success / error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif>

    <form method="POST" action="{{ route('Admin.pelatihan.update', $session->id) }}" class="row g-3">
        @csrf
        @method('PUT')

        {{-- Katalog (opsional) --}}
        <div class="col-12">
            <label for="id_katalog2" class="form-label">Pilih Katalog (opsional)</label>
            <select name="id_katalog2" id="id_katalog2" class="form-select">
                <option value="">-- Pilih (atau kosong untuk input manual) --</option>
                @foreach(($katalogs ?? []) as $k)
                    <option value="{{ $k->id }}"
                        data-nama="{{ $k->nama_pelatihan }}"
                        data-jenis="{{ $k->jenis_pelatihan }}"
                        data-penyelenggara="{{ $k->penyelenggara }}"
                        {{ old('id_katalog2', $session->id_katalog2) == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_pelatihan }}
                    </option>
                @endforeach
            </select>
            @error('id_katalog2') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            <small class="text-muted">Jika memilih katalog, Nama/Jenis/Penyelenggara akan otomatis terisi (tetap dapat diubah).</small>
        </div>

        {{-- Nama / Jenis / Penyelenggara --}}
        <div class="col-md-6">
            <label for="nama_pelatihan" class="form-label">Nama Pelatihan</label>
            <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control"
                   value="{{ old('nama_pelatihan', $session->nama_pelatihan) }}">
            @error('nama_pelatihan') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="jenis_pelatihan" class="form-label">Jenis Pelatihan</label>
            <select name="jenis_pelatihan" id="jenis_pelatihan" class="form-select">
                <option value="">-- Pilih / kosongkan --</option>
                @foreach(($jenisList ?? []) as $jenis)
                    <option value="{{ $jenis }}" {{ old('jenis_pelatihan', $session->jenis_pelatihan) == $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>
            @error('jenis_pelatihan') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="penyelenggara" class="form-label">Penyelenggara</label>
            <select name="penyelenggara" id="penyelenggara" class="form-select">
                <option value="">-- Pilih / kosongkan --</option>
                @foreach(($penyelenggaraList ?? []) as $p)
                    <option value="{{ $p }}" {{ old('penyelenggara', $session->penyelenggara) == $p ? 'selected' : '' }}>
                        {{ $p }}
                    </option>
                @endforeach
            </select>
            @error('penyelenggara') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Kuota --}}
        <div class="col-md-3">
            <label for="kuota" class="form-label">Kuota</label>
            <input type="number" name="kuota" id="kuota" class="form-control"
                   value="{{ old('kuota', $session->kuota) }}" min="0" required>
            @error('kuota') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Pelaksanaan (deskripsi teks panjang) --}}
        <div class="col-12">
            <label for="deskripsi" class="form-label">Deskripsi </label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4"
                      placeholder="Contoh: Pertemuan sinkron Senin–Rabu 09.00–12.00; tugas mandiri via LMS.">{{ old('deskripsi', $session->deskripsi) }}</textarea>
            @error('deskripsi') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Lokasi / Provinsi & Kota --}}
        <div class="col-md-6">
            <label for="lokasi" class="form-label">Lokasi (alamat/rincian)</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control"
                   value="{{ old('lokasi', $session->lokasi) }}">
            @error('lokasi') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        @if(!empty($provinsis))
        <div class="col-md-3">
            <label for="provinsi" class="form-label">Provinsi</label>
            <select id="provinsi" name="provinsi_id" class="form-select">
                <option value="">-- Pilih --</option>
                @foreach($provinsis as $prov)
                    <option value="{{ $prov->id }}" {{ old('provinsi_id', $session->provinsi_id ?? '') == $prov->id ? 'selected' : '' }}>
                        {{ $prov->nama }}
                    </option>
                @endforeach
            </select>
            @error('provinsi_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>
        @endif

        @if(!empty($kotas))
        <div class="col-md-3">
            <label for="kota" class="form-label">Kota/Kabupaten</label>
            <select id="kota" name="kota_id" class="form-select" {{ empty($session->provinsi_id) && empty(old('provinsi_id')) ? 'disabled' : '' }}>
                <option value="">-- Pilih --</option>
                {{-- opsi akan diisi via JS --}}
            </select>
            @error('kota_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>
        @endif

        {{-- Tanggal --}}
        <div class="col-md-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                   value="{{ old('tanggal_mulai', optional($session->tanggal_mulai)->format('Y-m-d')) }}">
            @error('tanggal_mulai') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                   value="{{ old('tanggal_selesai', optional($session->tanggal_selesai)->format('Y-m-d')) }}">
            @error('tanggal_selesai') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Status --}}
        <div class="col-md-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="aktif" {{ old('status', $session->status) == 'aktif' ? 'selected' : '' }}>aktif</option>
                <option value="tutup" {{ old('status', $session->status) == 'tutup' ? 'selected' : '' }}>tutup</option>
            </select>
            @error('status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <button class="btn btn-primary">Perbarui</button>
            <a href="{{ route('Admin.pelatihan.index') }}" class="btn btn-outline-secondary">Batal</a>
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
    const jenisSel   = document.getElementById('jenis_pelatihan');
    const penySel    = document.getElementById('penyelenggara');

    function setIfEmpty(el, val) {
        if (!el) return;
        if (!el.value || el.value.trim() === '') el.value = val || '';
    }

    function selectIfMatch(selectEl, value){
        if (!selectEl) return;
        let matched = false;
        Array.from(selectEl.options).forEach(opt => {
            if (String(opt.value) === String(value)) { opt.selected = true; matched = true; }
        });
        if (!matched && value) {
            const opt = new Option(value, value, true, true);
            selectEl.add(opt);
        }
    }

    selKatalog?.addEventListener('change', function(){
        const opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) return;
        const dNama  = opt.getAttribute('data-nama');
        const dJenis = opt.getAttribute('data-jenis');
        const dPeny  = opt.getAttribute('data-penyelenggara');

        setIfEmpty(namaInput, dNama);
        selectIfMatch(jenisSel, dJenis);
        selectIfMatch(penySel, dPeny);
    });

    // Cascading Provinsi -> Kota
    const allKotas = @json($kotas ?? []); // [{id,nama,provinsi_id}, ...]
    const provSel  = document.getElementById('provinsi');
    const kotaSel  = document.getElementById('kota');

    const currentProvId = @json(old('provinsi_id', $session->provinsi_id ?? null));
    const currentKotaId = @json(old('kota_id', $session->kota_id ?? null));

    function fillKota(){
        if (!provSel || !kotaSel) return;
        const provId = provSel.value;
        kotaSel.innerHTML = '<option value="">-- Pilih --</option>';
        kotaSel.disabled = !provId;

        if (!provId) return;
        allKotas.forEach(k => {
            if (String(k.provinsi_id) === String(provId)) {
                const selected = String(k.id) === String(currentKotaId);
                kotaSel.add(new Option(k.nama, k.id, selected, selected));
            }
        });
    }

    provSel?.addEventListener('change', function(){
        // reset kota saat provinsi berubah
        // juga hapus pilihan sebelumnya agar tidak terkirim salah
        if (kotaSel) kotaSel.value = '';
        fillKota();
    });

    // init saat halaman dibuka
    if (provSel) {
        if (currentProvId) provSel.value = String(currentProvId);
        fillKota();
    }
});
</script>
@endpush
