@extends('layouts.app')
@section('title', 'Data Supplier')
@section('page-title', 'Data Supplier')
@section('content')

<!-- Stat Cards (100% Responsive Grid) -->
<div class="row g-2 g-sm-3 mb-3 mb-sm-4">
    <div class="col-12 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-truck"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Total Supplier</div>
                <div class="stat-value">{{ \App\Models\Supplier::count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-check-circle-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Aktif</div>
                <div class="stat-value">{{ \App\Models\Supplier::where('status','Aktif')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-x-circle-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Tidak Aktif</div>
                <div class="stat-value">{{ \App\Models\Supplier::where('status','Tidak Aktif')->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2.5">
        <div>
            <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-truck me-2 text-primary"></i>Daftar Supplier</h6>
            <small class="text-muted" style="font-size:0.75rem;">Kelola data pemasok barang toko</small>
        </div>
        <a href="{{ route('supplier.create') }}" class="btn btn-primary btn-sm w-100 w-sm-auto"><i class="bi bi-plus-lg me-1"></i>Tambah Supplier</a>
    </div>

    <div class="px-3 px-sm-4 py-3 border-bottom bg-light bg-opacity-50">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 ps-0" placeholder="Cari nama, kode, perusahaan, no hp, email…">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ $status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ $status === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2 ms-auto">
                <button type="submit" class="btn btn-primary w-100 w-md-auto px-4"><i class="bi bi-filter me-1"></i>Filter</button>
                @if($search || $status)
                    <a href="{{ route('supplier.index') }}" class="btn btn-outline-secondary w-100 w-md-auto"><i class="bi bi-x-circle me-1"></i>Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-3 ps-sm-4">#</th>
                    <th>KODE</th>
                    <th>NAMA SUPPLIER</th>
                    <th>PERUSAHAAN</th>
                    <th>NO. TELEPON</th>
                    <th>EMAIL</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center pe-3 pe-sm-4">AKSI</th>
                </tr>
            </thead>
            <tbody>
            @forelse($supplier as $item)
            <tr>
                <td class="ps-3 ps-sm-4 text-muted font-monospace" style="font-size:0.75rem;">{{ $supplier->firstItem() + $loop->index }}</td>
                <td><span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-semibold" style="font-size:0.78rem;">{{ $item->kode_supplier }}</span></td>
                <td class="fw-bold text-dark">{{ $item->nama_supplier }}</td>
                <td>{{ $item->nama_perusahaan ?? '—' }}</td>
                <td>{{ $item->no_telepon ?? '—' }}</td>
                <td>{{ $item->email ?? '—' }}</td>
                <td class="text-center">
                    @if($item->status === 'Aktif')
                        <span class="badge-pill-custom badge-aktif"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                    @else
                        <span class="badge-pill-custom badge-nonaktif"><i class="bi bi-x-circle-fill"></i> Tidak Aktif</span>
                    @endif
                </td>
                <td class="text-center pe-3 pe-sm-4">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('supplier.show', $item) }}" class="btn btn-icon btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('supplier.edit', $item) }}" class="btn btn-icon btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <button class="btn btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $item->supplier_id }}" data-nama="{{ $item->nama_supplier }}" title="Hapus"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-truck text-muted"></i></div>
                        <h6 class="fw-bold">Belum Ada Data Supplier</h6>
                        <p class="text-muted small">Tambahkan supplier baru untuk mengelola pemasok barang toko.</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($supplier->hasPages())
    <div class="card-footer bg-white border-top py-3 px-3 px-sm-4">
        {{ $supplier->links() }}
    </div>
    @endif
</div>

@include('partials.modal-hapus', ['route' => 'supplier.destroy', 'id' => 'supplier_id'])

@endsection
