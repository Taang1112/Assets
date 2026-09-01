@extends('layouts.app')
@section('title','Data Produk')
@section('page-title','Data Produk')
@section('content')

<!-- Stat Cards (100% Mobile Responsive Grid) -->
<div class="row g-2 g-sm-3 mb-3 mb-sm-4">
    <div class="col-12 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-box-seam-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Total Produk</div>
                <div class="stat-value">{{ \App\Models\Produk::count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-check-circle-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Produk Aktif</div>
                <div class="stat-value">{{ \App\Models\Produk::where('status','Aktif')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-exclamation-circle-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Stok Tipis (&lt;5)</div>
                <div class="stat-value">{{ \App\Models\Produk::where('stok','<',5)->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2.5">
        <div>
            <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-box-seam me-2 text-primary"></i>Daftar Produk</h6>
            <small class="text-muted" style="font-size:0.75rem;">Kelola barang dan stok barang dagangan toko</small>
        </div>
        <a href="{{ route('produk.create') }}" class="btn btn-primary btn-sm w-100 w-sm-auto"><i class="bi bi-plus-lg me-1"></i>Tambah Produk</a>
    </div>

    <div class="px-3 px-sm-4 py-3 border-bottom bg-light bg-opacity-50">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-5 col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 ps-0" placeholder="Cari nama atau kode produk…">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <select name="kategori_produk_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->kategori_produk_id }}" {{ $kategoriId == $kat->kategori_produk_id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ $status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ $status === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2 ms-auto">
                <button type="submit" class="btn btn-primary w-100 w-md-auto px-4"><i class="bi bi-filter me-1"></i>Filter</button>
                @if($search || $status || $kategoriId)
                    <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary w-100 w-md-auto"><i class="bi bi-x-circle me-1"></i>Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-3 ps-sm-4">#</th>
                    <th>KODE PRODUK</th>
                    <th>NAMA PRODUK</th>
                    <th>KATEGORI</th>
                    <th>HARGA BELI</th>
                    <th>HARGA JUAL</th>
                    <th>STOK</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center pe-3 pe-sm-4">AKSI</th>
                </tr>
            </thead>
            <tbody>
            @forelse($produk as $item)
            <tr>
                <td class="ps-3 ps-sm-4 text-muted font-monospace" style="font-size:0.75rem;">{{ $produk->firstItem() + $loop->index }}</td>
                <td><span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-semibold" style="font-size:0.78rem;">{{ $item->kode_produk }}</span></td>
                <td class="fw-bold text-dark">{{ $item->nama_produk }}</td>
                <td><span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 fw-semibold">{{ $item->kategori->nama_kategori ?? '—' }}</span></td>
                <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                <td class="fw-bold text-success">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                <td>
                    @if($item->stok < 5)
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $item->stok }} {{ $item->satuan }}</span>
                    @else
                        <span class="badge bg-light text-dark border px-2 py-1 fw-bold">{{ $item->stok }} {{ $item->satuan }}</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->status === 'Aktif')
                        <span class="badge-pill-custom badge-aktif"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                    @else
                        <span class="badge-pill-custom badge-nonaktif"><i class="bi bi-x-circle-fill"></i> Tidak Aktif</span>
                    @endif
                </td>
                <td class="text-center pe-3 pe-sm-4">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('produk.show', $item) }}" class="btn btn-icon btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('produk.edit', $item) }}" class="btn btn-icon btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <button class="btn btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $item->produk_id }}" data-nama="{{ $item->nama_produk }}" title="Hapus"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-box-seam"></i></div>
                        <h6 class="fw-bold mb-1">Belum Ada Produk</h6>
                        <p class="text-muted small mb-3">Produk tidak ditemukan atau belum pernah ditambahkan.</p>
                        <a href="{{ route('produk.create') }}" class="btn btn-primary btn-sm px-4"><i class="bi bi-plus-lg me-1"></i>Tambah Produk Pertama</a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($produk->hasPages())
    <div class="card-footer bg-white d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 px-3 px-sm-4">
        <small class="text-muted fw-semibold" style="font-size:0.75rem;">Menampilkan {{ $produk->firstItem() }}–{{ $produk->lastItem() }} dari {{ $produk->total() }} data</small>
        {{ $produk->links() }}
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
        document.getElementById('formHapus').action = `/produk/${btn.dataset.id}`;
    });
</script>
@endpush
