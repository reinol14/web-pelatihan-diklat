@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Kelola Admin</h3>
    <a href="{{ route('SuperAdmin.Admins.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg me-1"></i> Tambah Admin
    </a>
  </div>

  @foreach (['success','error','info'] as $f)
    @if(session($f))
      <div class="alert alert-{{ $f==='error'?'danger':$f }}">{{ session($f) }}</div>
    @endif
  @endforeach

  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <div class="input-group">
        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Cari nama/email/unit/subunit/kode..."
               value="{{ $q }}">
      </div>
    </div>
    <div class="col-md-3">
      <select name="role" class="form-select">
        <option value="">Semua Peran</option>
        <option value="1" @selected($role==='1')>Superadmin</option>
        <option value="2" @selected($role==='2')>Admin Unit</option>
      </select>
    </div>
    <div class="col-md-3">
      <select name="unit" class="form-select">
        <option value="">Semua Unit Kerja</option>
        @foreach($unitkerjas as $u)
          <option value="{{ $u->id_unitkerja }}" @selected($unit==$u->id_unitkerja)">
            ({{ $u->kode_unitkerja }}) {{ $u->unitkerja }}{{ $u->sub_unitkerja ? ' — '.$u->sub_unitkerja : '' }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2 d-grid d-md-flex gap-2">
      <button class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Terapkan</button>
      <a href="{{ route('SuperAdmin.Admins.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Peran</th>
          <th>Kode Unit</th>
          <th>Unit Kerja</th>
          <th>Sub Unit</th>
          <th>Dibuat</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($admins as $a)
          <tr>
            <td>{{ ($admins->firstItem() ?? 1) + $loop->index }}</td>
            <td>{{ $a->name }}</td>
            <td>{{ $a->email }}</td>
            <td>
              <span class="badge text-bg-{{ $a->is_admin==1 ? 'primary' : 'secondary' }}">
                {{ $a->is_admin==1 ? 'Superadmin' : 'Admin Unit' }}
              </span>
            </td>
            <td>{{ $a->kode_uk ?? '—' }}</td>
            <td>{{ $a->unitkerja ?? '—' }}</td>
            <td>{{ $a->sub_unitkerja ?? '—' }}</td>
            <td>{{ \Illuminate\Support\Carbon::parse($a->created_at)->format('d M Y') }}</td>
            <td class="text-end">
              <a href="{{ route('SuperAdmin.Admins.edit',$a->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                <i class="bi bi-pencil-square"></i>
              </a>
              <form class="d-inline" method="POST" action="{{ route('SuperAdmin.Admins.destroy',$a->id) }}"
                    onsubmit="return confirm('Hapus admin ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="9" class="text-center text-muted">Tidak ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $admins->appends(request()->query())->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
