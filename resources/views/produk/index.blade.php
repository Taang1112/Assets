@extends('layouts.app')

@section('title', 'Manajemen Produk')
@section('page-title', 'Data Produk')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0f172a;">Daftar Produk</h4>
            <p class="text-muted small mb-0">Kelola inventaris dan catalog produk dalam sistem</p>
        </div>
        <div>
            <a href="{{ route('produk.create') }}" class="btn btn-primary px-3 py-2 fw-medium d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Produk Baru</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card border-start border-4 border-indigo">
                <div class="stat-icon bg-indigo-subtle text-indigo" style="background-color: #e0e7ff; color: #4338ca;">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-value">{{ number_format($totalProduk) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card border-start border-4 border-success">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Produk Aktif</div>
                    <div class="stat-value">{{ number_format($produkAktif) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card border-start border-4 border-warning">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-stack"></i>
                </div>
                <div>
                    <div class="stat-label">Total Stok Unit</div>
                    <div class="stat-value">{{ number_format($totalStok) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card border-start border-4 border-info">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="stat-label">Nilai Total Aset</div>
                    <div class="stat-value" style="font-size: 1.25rem;">Rp {{ number_format($nilaiAset, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card mb-4">
        <div class="card-body p-3">
            <form action="{{ route('produk.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama, kode, deskripsi..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select name="kategori_produk_id" class="form-select">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->kategori_produk_id }}" {{ $kategori_id == $kat->kategori_produk_id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif" {{ $status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ $status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-medium">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if($search || $kategori_id || $status)
                        <a href="{{ route('produk.index') }}" class="btn btn-light border text-muted" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-slate-800"><i class="bi bi-table me-2 text-indigo-600"></i>Data List Produk</h6>
            <span class="badge bg-light text-secondary border">Total {{ $produk->total() }} Produk</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produk as $index => $item)
                        <tr>
                            <td class="ps-4 font-monospace text-muted">{{ $produk->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-slate-100 text-dark fw-semibold px-2 py-1 border font-monospace" style="font-size: 0.78rem;">
                                    {{ $item->kode_produk }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-slate-800">{{ $item->nama_produk }}</div>
                                @if($item->deskripsi)
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 220px;">
                                        {{ Str::limit($item->deskripsi, 40) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border border-primary-subtle">
                                    <i class="bi bi-tag me-1"></i>{{ $item->kategori->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="text-end font-monospace">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-end font-monospace fw-semibold text-indigo">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($item->stok == 0)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                        <i class="bi bi-exclamation-circle me-1"></i>Habis (0)
                                    </span>
                                @elseif($item->stok < 10)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        {{ $item->stok }} {{ $item->satuan }}
                                    </span>
                                @else
                                    <span class="fw-semibold text-slate-700">{{ number_format($item->stok) }}</span>
                                    <small class="text-muted">{{ $item->satuan }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status == 'Aktif')
                                    <span class="badge badge-aktif px-2 py-1 rounded-pill">
                                        <i class="bi bi-dot"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge badge-nonaktif px-2 py-1 rounded-pill">
                                        <i class="bi bi-dot"></i> Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('produk.show', $item->produk_id) }}" class="btn btn-light border text-info btn-icon" title="Detail Produk">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('produk.edit', $item->produk_id) }}" class="btn btn-light border text-warning btn-icon" title="Edit Produk">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-light border text-danger btn-icon" title="Hapus Produk"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->produk_id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $item->produk_id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start py-3">
                                                Apakah Anda yakin ingin menghapus produk <strong>"{{ $item->nama_produk }}"</strong> ({{ $item->kode_produk }})?
                                                <p class="text-muted small mb-0 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('produk.destroy', $item->produk_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger px-3">Hapus Produk</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-box-seam display-5 text-muted opacity-50 mb-3 d-block"></i>
                                    <h6 class="fw-semibold text-secondary">Belum Ada Data Produk</h6>
                                    <p class="text-muted small mb-3">Silakan tambah produk baru atau ubah kata kunci filter Anda.</p>
                                    <a href="{{ route('produk.create') }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($produk->hasPages())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $produk->firstItem() }} - {{ $produk->lastItem() }} dari {{ $produk->total() }} produk
                    </small>
                    {{ $produk->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
