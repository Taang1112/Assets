@extends('layouts.app')

@section('title', 'Detail Produk - ' . $produk->nama_produk)
@section('page-title', 'Detail Produk')

@section('content')
<div class="container-fluid p-0" style="max-width: 900px;">
    <!-- Page Navigation -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('produk.index') }}" class="text-decoration-none text-muted small fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Produk
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('produk.edit', $produk->produk_id) }}" class="btn btn-sm btn-warning text-dark font-medium">
                <i class="bi bi-pencil me-1"></i> Edit Produk
            </a>
        </div>
    </div>

    <!-- Product Detail Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-indigo-subtle text-indigo px-3 py-2 font-monospace fs-6" style="background-color: #e0e7ff; color: #4338ca;">
                    {{ $produk->kode_produk }}
                </span>
                <h5 class="fw-bold mb-0 text-slate-800">{{ $produk->nama_produk }}</h5>
            </div>
            <div>
                @if($produk->status == 'Aktif')
                    <span class="badge badge-aktif px-3 py-2 rounded-pill"><i class="bi bi-dot"></i> Aktif</span>
                @else
                    <span class="badge badge-nonaktif px-3 py-2 rounded-pill"><i class="bi bi-dot"></i> Tidak Aktif</span>
                @endif
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <!-- Pricing & Stock Metrics -->
                <div class="col-12">
                    <div class="p-3 bg-light rounded-3 d-flex flex-wrap justify-content-around align-items-center text-center gap-3 border">
                        <div>
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: .7rem;">Harga Beli (Modal)</small>
                            <span class="fs-5 fw-bold text-slate-700">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</span>
                        </div>
                        <div class="vr d-none d-md-block"></div>
                        <div>
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: .7rem;">Harga Jual</small>
                            <span class="fs-5 fw-bold text-indigo" style="color: #4f46e5;">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                        </div>
                        <div class="vr d-none d-md-block"></div>
                        <div>
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: .7rem;">Profit per Unit</small>
                            @php $profit = $produk->harga_jual - $produk->harga_beli; @endphp
                            <span class="fs-5 fw-bold {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($profit, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="vr d-none d-md-block"></div>
                        <div>
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: .7rem;">Stok Saat Ini</small>
                            <span class="fs-5 fw-bold {{ $produk->stok > 0 ? 'text-slate-800' : 'text-danger' }}">
                                {{ number_format($produk->stok) }} {{ $produk->satuan }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Attributes Table -->
                <div class="col-12 col-md-6">
                    <h6 class="fw-bold text-slate-700 mb-3 border-bottom pb-2">Informasi Produk</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" style="width: 140px;">Kategori</td>
                            <td class="fw-semibold">: {{ $produk->kategori->nama_kategori ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kode Produk</td>
                            <td class="fw-semibold font-monospace">: {{ $produk->kode_produk }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Satuan</td>
                            <td class="fw-semibold">: {{ $produk->satuan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Nilai Stok</td>
                            <td class="fw-semibold text-success">: Rp {{ number_format($produk->harga_beli * $produk->stok, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-12 col-md-6">
                    <h6 class="fw-bold text-slate-700 mb-3 border-bottom pb-2">Informasi Sistem</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" style="width: 140px;">Dibuat Pada</td>
                            <td>: {{ $produk->created_at ? $produk->created_at->translatedFormat('d F Y, H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terakhir Diubah</td>
                            <td>: {{ $produk->updated_at ? $produk->updated_at->translatedFormat('d F Y, H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <h6 class="fw-bold text-slate-700 mb-2 border-bottom pb-2">Deskripsi Produk</h6>
                    <p class="text-slate-600 mb-0" style="white-space: pre-line;">
                        {{ $produk->deskripsi ?: 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
