@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3 class="mb-3">Edit Admin</h3>

  @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

  <form method="POST" action="{{ route('SuperAdmin.Admins.update',$admin->id) }}">
    @csrf @method('PUT')

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name',$admin->name) }}" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email',$admin->email) }}" required>
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Password (kosongkan jika tidak ganti)</label>
        <input type="password" name="password" class="form-control">
        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control">
      </div>

      <div class="col-md-4">
        <label class="form-label">Peran</label>
        <select name="is_admin" class="form-select" required>
          <option value="1" @selected(old('is_admin',$admin->is_admin)==1)>Superadmin</option>
          <option value="2" @selected(old('is_admin',$admin->is_admin)==2)>Admin Unit</option>
        </select>
      </div>

<label class="form-label">Unit Kerja (untuk Admin Unit)</label>
<select name="kode_unitkerja" class="form-select">
  <option value="">-- Pilih Unit Kerja --</option>
  @foreach($unitkerjas as $u)
    {{-- value = KODE (wajib) --}}
    <option value="{{ $u->kode_unitkerja }}"
      @selected(old('kode_unitkerja', $admin->kode_unitkerja ?? '') == $u->kode_unitkerja)>
      {{ $u->unitkerja }}@if($u->sub_unitkerja) — {{ $u->sub_unitkerja }} @endif
      ({{ $u->kode_unitkerja }})
    </option>
  @endforeach
</select>
<small class="text-muted">Pastikan setiap Unit Kerja punya <b>kode_unitkerja</b> di tabel referensi.</small>

      </div>
    </div>

    <div class="mt-4 d-flex gap-2">
      <a href="{{ route('SuperAdmin.Admins.index') }}" class="btn btn-outline-secondary">Kembali</a>
      <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
    </div>
  </form>
</div>
@endsection
