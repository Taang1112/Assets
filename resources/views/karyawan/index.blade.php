@extends('layouts.app')
@section('title', 'Data Karyawan')
@section('page-title', 'Data Karyawan Toko')
@section('content')

<div class="row g-2 g-sm-3 mb-3 mb-sm-4">
    <div class="col-6 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-person-badge-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Total Karyawan</div>
                <div class="stat-value">{{ \App\Models\Karyawan::count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-person-workspace"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Karyawan Aktif</div>
                <div class="stat-value">{{ \App\Models\Karyawan::count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2.5">
        <div>
            <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-person-badge me-2 text-primary"></i>Daftar Karyawan</h6>
            <small class="text-muted" style="font-size:0.75rem;">Kelola data staff dan karyawan toko</small>
        </div>
        <a href="{{ route('karyawan.create') }}" class="btn btn-primary btn-sm w-100 w-sm-auto"><i class="bi bi-plus-lg me-1"></i>Tambah Karyawan</a>
    </div>

    <div class="px-3 px-sm-4 py-3 border-bottom bg-light bg-opacity-50">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Cari NIK, nama lengkap, email, no hp…">
                </div>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2 ms-auto">
                <button type="submit" class="btn btn-primary w-100 w-md-auto px-4"><i class="bi bi-filter me-1"></i>Filter</button>
                @if(request('search'))
                    <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary w-100 w-md-auto"><i class="bi bi-x-circle me-1"></i>Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-3 ps-sm-4">#</th>
                    <th>NIK</th>
                    <th>NAMA LENGKAP</th>
                    <th>JENIS KELAMIN</th>
                    <th>EMAIL</th>
                    <th>NO. TELEPON</th>
                    <th>PENDIDIKAN</th>
                    <th class="text-center pe-3 pe-sm-4">AKSI</th>
                </tr>
            </thead>
            <tbody>
            @forelse($karyawan as $item)
            <tr>
                <td class="ps-3 ps-sm-4 text-muted font-monospace" style="font-size:0.75rem;">{{ $karyawan->firstItem() + $loop->index }}</td>
                <td><span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-semibold" style="font-size:0.78rem;">{{ $item->nik }}</span></td>
                <td class="fw-bold text-dark">{{ $item->nama_lengkap }}</td>
                <td>
                    @if($item->jenis_kelamin === 'Laki-laki')
                        <span class="badge bg-blue-subtle text-primary border px-2 py-1"><i class="bi bi-gender-male me-1"></i>Laki-laki</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border px-2 py-1"><i class="bi bi-gender-female me-1"></i>Perempuan</span>
                    @endif
                </td>
                <td>{{ $item->email ?? '—' }}</td>
                <td>{{ $item->no_telepon ?? '—' }}</td>
                <td><span class="badge bg-light text-secondary border px-2 py-1 fw-semibold">{{ $item->pendidikan_terakhir ?? '—' }}</span></td>
                <td class="text-center pe-3 pe-sm-4">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('karyawan.show', $item) }}" class="btn btn-icon btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('karyawan.edit', $item) }}" class="btn btn-icon btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <button class="btn btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $item->karyawan_id }}" data-nama="{{ $item->nama_lengkap }}" title="Hapus"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-person-badge"></i></div>
                        <h6 class="fw-bold mb-1">Belum Ada Karyawan</h6>
                        <p class="text-muted small mb-3">Data karyawan tidak ditemukan atau belum pernah ditambahkan.</p>
                        <a href="{{ route('karyawan.create') }}" class="btn btn-primary btn-sm px-4"><i class="bi bi-plus-lg me-1"></i>Tambah Karyawan Pertama</a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($karyawan->hasPages())
    <div class="card-footer bg-white d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 px-3 px-sm-4">
        <small class="text-muted fw-semibold" style="font-size:0.75rem;">Menampilkan {{ $karyawan->firstItem() }}–{{ $karyawan->lastItem() }} dari {{ $karyawan->total() }} data</small>
        {{ $karyawan->links() }}
    </div>
    @endif
</div>

@include('partials.modal-hapus')
@endsection

@push('scripts')
<script>
    document.getElementById('modalHapus').addEventListener('show.bs.modal', e => {
        const btn = e.relatedTarget;
        document.getElementById('hapusNama').textContent = btn.dataset.nama;
        document.getElementById('formHapus').action = `/karyawan/${btn.dataset.id}`;
    });
</script>
@endpush
