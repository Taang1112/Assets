@extends('layouts.app')
@section('title', 'Riwayat Stok')
@section('page-title', 'Riwayat Stok & Restock')
@section('content')

<!-- Stat Cards -->
<div class="row g-2 g-sm-3 mb-3 mb-sm-4">
    <div class="col-12 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-clock-history"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Total Restock</div>
                <div class="stat-value">{{ \App\Models\Stok::count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-box-seam"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Total Unit Masuk</div>
                <div class="stat-value">{{ \App\Models\Stok::sum('stok_masuk') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-truck"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Supplier Terlibat</div>
                <div class="stat-value">{{ \App\Models\Stok::distinct('supplier_id')->count('supplier_id') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2.5">
        <div>
            <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-journal-text me-2 text-primary"></i>Riwayat Pergerakan Stok (Batch Layer)</h6>
            <small class="text-muted" style="font-size:0.75rem;">Log lengkap aktivitas restock dan sisa batch persediaan FIFO</small>
        </div>
        <a href="{{ route('stok.create') }}" class="btn btn-primary btn-sm w-100 w-sm-auto"><i class="bi bi-plus-lg me-1"></i>Restock Produk</a>
    </div>

    <div class="px-3 px-sm-4 py-3 border-bottom bg-light bg-opacity-50">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 ps-0" placeholder="Cari nama atau kode produk…">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="supplier_id" class="form-select">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->supplier_id }}" {{ $supplierId == $sup->supplier_id ? 'selected' : '' }}>{{ $sup->nama_supplier }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control">
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 px-3"><i class="bi bi-filter me-1"></i>Filter</button>
                @if($search || $supplierId || $tanggal)
                    <a href="{{ route('stok.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-3 ps-sm-4">BATCH ID</th>
                    <th>TANGGAL MASUK</th>
                    <th>PRODUK</th>
                    <th>SUPPLIER</th>
                    <th>STOK MASUK</th>
                    <th>STOK TERSISA</th>
                    <th>HARGA BELI</th>
                    <th>HARGA JUAL BATCH</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
            @forelse($stok as $item)
            <tr>
                <td class="ps-3 ps-sm-4 text-muted font-monospace" style="font-size:0.75rem;">Batch #{{ $item->stok_id }}</td>
                <td>
                    <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') }}</div>
                    <small class="text-muted" style="font-size:0.72rem;">{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('H:i') }} WIB</small>
                </td>
                <td>
                    <div class="fw-bold text-dark">{{ $item->produk->nama_produk ?? '—' }}</div>
                    <span class="badge bg-light text-dark border font-monospace" style="font-size:0.72rem;">{{ $item->produk->kode_produk ?? '—' }}</span>
                </td>
                <td><span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 fw-semibold">{{ $item->supplier->nama_supplier ?? '—' }}</span></td>
                <td><span class="badge bg-light text-secondary border">+{{ $item->stok_masuk }} {{ $item->produk->satuan ?? '' }}</span></td>
                <td>
                    @if($item->stok_tersisa > 0)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-bold">{{ $item->stok_tersisa }} {{ $item->produk->satuan ?? '' }}</span>
                    @else
                        <span class="badge bg-light text-muted border px-2 py-1">Habis (0)</span>
                    @endif
                </td>
                <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                <td class="fw-bold text-success">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                <td class="text-muted small">{{ $item->keterangan ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-clock-history text-muted"></i></div>
                        <h6 class="fw-bold">Belum Ada Riwayat Restock</h6>
                        <p class="text-muted small">Lakukan restock produk untuk mencatat riwayat pergerakan stok masuk.</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($stok->hasPages())
    <div class="card-footer bg-white border-top py-3 px-3 px-sm-4">
        {{ $stok->links() }}
    </div>
    @endif
</div>

@endsection
