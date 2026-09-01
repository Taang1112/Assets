@extends('layouts.app')
@section('title', 'Data Inventaris')
@section('page-title', 'Data Inventaris Toko')
@section('content')

<div class="row g-2 g-sm-3 mb-3 mb-sm-4">
    <div class="col-12 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-archive-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Total Aset/Item</div>
                <div class="stat-value">{{ \App\Models\Inventaris::sum('jumlah') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-check-circle-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Kondisi Baik</div>
                <div class="stat-value">{{ \App\Models\Inventaris::where('kondisi','Baik')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Perlu Perbaikan</div>
                <div class="stat-value">{{ \App\Models\Inventaris::whereIn('kondisi',['Rusak Ringan','Rusak Berat'])->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2.5">
        <div>
            <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-archive me-2 text-primary"></i>Daftar Aset & Inventaris</h6>
            <small class="text-muted" style="font-size:0.75rem;">Kelola barang perlengkapan operasional toko</small>
        </div>
        <a href="{{ route('inventaris.create') }}" class="btn btn-primary btn-sm w-100 w-sm-auto"><i class="bi bi-plus-lg me-1"></i>Tambah Inventaris</a>
    </div>

    <div class="px-3 px-sm-4 py-3 border-bottom bg-light bg-opacity-50">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Cari nama, kode, lokasi…">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="kategori_inventaris_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->kategori_inventaris_id }}" {{ request('kategori_inventaris_id') == $k->kategori_inventaris_id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="kondisi" class="form-select">
                    <option value="">Semua Kondisi</option>
                    @foreach(['Baik','Rusak Ringan','Rusak Berat'] as $kon)
                        <option value="{{ $kon }}" {{ request('kondisi') === $kon ? 'selected' : '' }}>{{ $kon }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2 ms-auto">
                <button type="submit" class="btn btn-primary w-100 w-md-auto px-4"><i class="bi bi-filter me-1"></i>Filter</button>
                @if(request('search') || request('kondisi') || request('kategori_inventaris_id'))
                    <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary w-100 w-md-auto"><i class="bi bi-x-circle me-1"></i>Reset</a>
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
                    <th>NAMA INVENTARIS</th>
                    <th>KATEGORI</th>
                    <th>QTY</th>
                    <th>KONDISI</th>
                    <th>LOKASI</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center pe-3 pe-sm-4">AKSI</th>
                </tr>
            </thead>
            <tbody>
            @forelse($inventaris as $item)
            <tr>
                <td class="ps-3 ps-sm-4 text-muted font-monospace" style="font-size:0.75rem;">{{ $inventaris->firstItem() + $loop->index }}</td>
                <td><span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-semibold" style="font-size:0.78rem;">{{ $item->kode_inventaris }}</span></td>
                <td class="fw-bold text-dark">{{ $item->nama_inventaris }}</td>
                <td><span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 fw-semibold">{{ $item->kategori->nama_kategori ?? '—' }}</span></td>
                <td><span class="badge bg-light text-dark border px-2 py-1 fw-bold">{{ $item->jumlah }} Unit</span></td>
                <td>
                    @if($item->kondisi === 'Baik')
                        <span class="badge-pill-custom badge-baik"><i class="bi bi-check-circle-fill"></i> Baik</span>
                    @elseif($item->kondisi === 'Rusak Ringan')
                        <span class="badge-pill-custom badge-rringan"><i class="bi bi-exclamation-triangle-fill"></i> Rusak Ringan</span>
                    @else
                        <span class="badge-pill-custom badge-rberat"><i class="bi bi-x-circle-fill"></i> Rusak Berat</span>
                    @endif
                </td>
                <td><i class="bi bi-geo-alt me-1 text-secondary"></i>{{ $item->lokasi ?? '—' }}</td>
                <td class="text-center">
                    @if($item->status === 'Dipakai')
                        <span class="badge-pill-custom badge-dipakai"><i class="bi bi-box-seam"></i> Dipakai</span>
                    @elseif($item->status === 'Dipinjam')
                        <span class="badge-pill-custom badge-dipinjam"><i class="bi bi-person-check"></i> Dipinjam</span>
                    @elseif($item->status === 'Tersedia')
                        <span class="badge-pill-custom badge-tersedia"><i class="bi bi-check-circle"></i> Tersedia</span>
                    @else
                        <span class="badge-pill-custom badge-dihapus"><i class="bi bi-trash"></i> Dihapus</span>
                    @endif
                </td>
                <td class="text-center pe-3 pe-sm-4">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('inventaris.show', $item) }}" class="btn btn-icon btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('inventaris.edit', $item) }}" class="btn btn-icon btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <button class="btn btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $item->inventaris_id }}" data-nama="{{ $item->nama_inventaris }}" title="Hapus"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-archive"></i></div>
                        <h6 class="fw-bold mb-1">Belum Ada Inventaris</h6>
                        <p class="text-muted small mb-3">Data aset/inventaris tidak ditemukan atau belum pernah ditambahkan.</p>
                        <a href="{{ route('inventaris.create') }}" class="btn btn-primary btn-sm px-4"><i class="bi bi-plus-lg me-1"></i>Tambah Inventaris Pertama</a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($inventaris->hasPages())
    <div class="card-footer bg-white d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 px-3 px-sm-4">
        <small class="text-muted fw-semibold" style="font-size:0.75rem;">Menampilkan {{ $inventaris->firstItem() }}–{{ $inventaris->lastItem() }} dari {{ $inventaris->total() }} data</small>
        {{ $inventaris->links() }}
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
        document.getElementById('formHapus').action = `/inventaris/${btn.dataset.id}`;
    });
</script>
@endpush
