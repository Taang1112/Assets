@extends('layouts.app')
@section('title', 'Detail Supplier')
@section('page-title', 'Detail Supplier')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-10">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size:.8125rem;">
                <li class="breadcrumb-item">
                    <a href="{{ route('supplier.index') }}" class="text-decoration-none text-primary">
                        <i class="bi bi-truck me-1"></i>Data Supplier
                    </a>
                </li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px;background:#e0e7ff;color:#4f46e5;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $supplier->nama_supplier }}</h6>
                        <small class="text-muted font-monospace">{{ $supplier->kode_supplier }}</small>
                    </div>
                </div>
                <div>
                    <a href="{{ route('supplier.edit', $supplier) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit Supplier</a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <span class="text-muted d-block small fw-bold text-uppercase">Nama Perusahaan</span>
                        <span class="fw-semibold text-dark">{{ $supplier->nama_perusahaan ?? '—' }}</span>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <span class="text-muted d-block small fw-bold text-uppercase">Status</span>
                        @if($supplier->status === 'Aktif')
                            <span class="badge-pill-custom badge-aktif mt-1"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                        @else
                            <span class="badge-pill-custom badge-nonaktif mt-1"><i class="bi bi-x-circle-fill"></i> Tidak Aktif</span>
                        @endif
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <span class="text-muted d-block small fw-bold text-uppercase">No. Telepon</span>
                        <span class="fw-semibold text-dark">{{ $supplier->no_telepon ?? '—' }}</span>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <span class="text-muted d-block small fw-bold text-uppercase">Email</span>
                        <span class="fw-semibold text-dark">{{ $supplier->email ?? '—' }}</span>
                    </div>
                    <div class="col-12 col-md-8">
                        <span class="text-muted d-block small fw-bold text-uppercase">Alamat</span>
                        <span class="fw-semibold text-dark">{{ $supplier->alamat ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Pasokan / Restock dari Supplier Ini --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Restock dari Supplier Ini</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">#</th>
                            <th>TANGGAL</th>
                            <th>PRODUK</th>
                            <th>STOK MASUK</th>
                            <th>HARGA BELI</th>
                            <th>KARYAWAN</th>
                            <th>KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($supplier->stok as $item)
                    <tr>
                        <td class="ps-3 text-muted font-monospace" style="font-size:0.75rem;">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold text-dark">{{ $item->produk->nama_produk ?? '—' }}</td>
                        <td><span class="badge bg-success-subtle text-success border px-2 py-1">+{{ $item->stok_masuk }} {{ $item->produk->satuan ?? '' }}</span></td>
                        <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                        <td>{{ $item->karyawan->nama_lengkap ?? '—' }}</td>
                        <td class="text-muted small">{{ $item->keterangan ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted small">Belum ada riwayat restock dari supplier ini.</td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection
