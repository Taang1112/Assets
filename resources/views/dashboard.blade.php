@extends('layouts.app')
@section('title', 'Dashboard Inventory & Ringkasan Toko')
@section('page-title', 'Dashboard Inventory')
@section('content')

<!-- Stat Cards Utama -->
<div class="row g-2 g-sm-3 mb-3 mb-sm-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-box-seam-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Total Produk</div>
                <div class="stat-value">{{ number_format($totalProduk) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-boxes"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Total Stok</div>
                <div class="stat-value">{{ number_format($totalStok) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Stok Rendah</div>
                <div class="stat-value text-danger">{{ number_format($stokRendahCount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-truck"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Supplier</div>
                <div class="stat-value">{{ number_format($totalSupplier) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0f2fe;color:#0284c7;"><i class="bi bi-people-fill"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Pelanggan</div>
                <div class="stat-value">{{ number_format($totalPelanggan) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f3e8ff;color:#9333ea;"><i class="bi bi-receipt-cutoff"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Total Transaksi</div>
                <div class="stat-value">{{ number_format($totalTransaksi) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan Penjualan & FIFO Summary Row -->
<div class="row g-3 mb-4">
    <!-- Ringkasan Omzet & Status Penjualan -->
    <div class="col-12 col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Ringkasan Penjualan Toko</h6>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-semibold">Khusus Status Selesai</span>
            </div>
            <div class="card-body p-3.5">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <div class="p-3 rounded-3 bg-light border">
                            <div class="text-muted mb-1" style="font-size:0.72rem;font-weight:700;letter-spacing:0.5px;">PENJUALAN HARI INI</div>
                            <div class="fw-extrabold text-success fs-4">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</div>
                            <small class="text-muted" style="font-size:0.7rem;">Transaksi selesai hari ini</small>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="p-3 rounded-3 bg-light border">
                            <div class="text-muted mb-1" style="font-size:0.72rem;font-weight:700;letter-spacing:0.5px;">PENJUALAN BULAN INI</div>
                            <div class="fw-extrabold text-primary fs-4">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</div>
                            <small class="text-muted" style="font-size:0.7rem;">Bulan {{ now()->translatedFormat('F Y') }}</small>
                        </div>
                    </div>
                </div>

                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="p-2 rounded-2 bg-success-subtle border border-success-subtle">
                            <div class="text-success fw-bold" style="font-size:0.7rem;">SELESAI</div>
                            <div class="fw-extrabold text-success fs-5">{{ $transaksiSelesai }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded-2 bg-warning-subtle border border-warning-subtle">
                            <div class="text-warning-emphasis fw-bold" style="font-size:0.7rem;">PENDING</div>
                            <div class="fw-extrabold text-warning-emphasis fs-5">{{ $transaksiPending }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded-2 bg-danger-subtle border border-danger-subtle">
                            <div class="text-danger fw-bold" style="font-size:0.7rem;">BATAL</div>
                            <div class="fw-extrabold text-danger fs-5">{{ $transaksiBatal }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FIFO / Batch Layer Summary -->
    <div class="col-12 col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-layers-half me-2 text-primary"></i>Ringkasan FIFO & Batch Stok</h6>
                <a href="{{ route('stok.index') }}" class="btn btn-outline-primary btn-sm px-2.5 py-1" style="font-size:0.72rem;">Detail Batch</a>
            </div>
            <div class="card-body p-3.5">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-2.5 rounded-3 bg-light border text-center">
                            <small class="text-muted d-block font-monospace" style="font-size:0.68rem;font-weight:700;">BATCH AKTIF</small>
                            <span class="fw-extrabold text-dark fs-5">{{ number_format($totalBatchAktif) }}</span>
                            <small class="text-muted d-block" style="font-size:0.65rem;">Stok Tersisa > 0</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 rounded-3 bg-light border text-center">
                            <small class="text-muted d-block font-monospace" style="font-size:0.68rem;font-weight:700;">SUM STOK BATCH</small>
                            <span class="fw-extrabold text-primary fs-5">{{ number_format($totalStokBatchAktif) }}</span>
                            <small class="text-muted d-block" style="font-size:0.65rem;">Total Layer Aktif</small>
                        </div>
                    </div>
                </div>

                <div class="fw-bold mb-1.5 text-secondary" style="font-size:0.75rem;">BATCH HAMPR HABIS (<= 5 PCS):</div>
                <div class="list-group list-group-flush border rounded-3 overflow-hidden" style="font-size:0.78rem;">
                    @forelse($batchHampirHabis as $b)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-1.5 px-2.5">
                            <div class="overflow-hidden me-2">
                                <div class="fw-semibold text-truncate">{{ $b->produk->nama_produk ?? '—' }}</div>
                                <small class="text-muted" style="font-size:0.68rem;">Batch #{{ $b->stok_id }} ({{ \Carbon\Carbon::parse($b->tanggal_masuk)->format('d/m/Y') }})</small>
                            </div>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fw-bold">Sisa {{ $b->stok_tersisa }} {{ $b->produk->satuan ?? '' }}</span>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-2 small">
                            Tidak ada batch aktif yang hampir habis.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grid Produk Stok Rendah & Produk Stok Terbanyak -->
<div class="row g-3 mb-4">
    <!-- Tabel Produk Stok Rendah -->
    <div class="col-12 col-xl-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold fs-6 text-danger"><i class="bi bi-exclamation-octagon-fill me-2"></i>Produk Stok Rendah (<= 5 Unit)</h6>
                    <small class="text-muted" style="font-size:0.72rem;">Perlu restock segera dari supplier</small>
                </div>
                <a href="{{ route('stok.create') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-in-down me-1"></i>Restock</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">KODE PRODUK</th>
                            <th>NAMA PRODUK</th>
                            <th>KATEGORI</th>
                            <th>STOK</th>
                            <th class="pe-3">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($produkStokRendah as $p)
                    <tr>
                        <td class="ps-3 font-monospace text-muted" style="font-size:0.75rem;">{{ $p->kode_produk }}</td>
                        <td class="fw-bold text-dark">{{ $p->nama_produk }}</td>
                        <td><span class="badge bg-light text-secondary border">{{ $p->kategori->nama_kategori ?? '—' }}</span></td>
                        <td>
                            @if($p->stok == 0)
                                <span class="badge bg-danger text-white px-2 py-1 fw-bold">HABIS (0 {{ $p->satuan }})</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fw-bold">{{ $p->stok }} {{ $p->satuan }}</span>
                            @endif
                        </td>
                        <td class="pe-3">
                            <span class="badge {{ $p->status === 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' }} rounded-pill px-2.5">{{ $p->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted small">
                            <i class="bi bi-check-circle text-success me-1"></i> Semua stok produk dalam kondisi aman (> 5 unit).
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Produk Stok Terbanyak -->
    <div class="col-12 col-xl-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-award-fill me-2 text-primary"></i>Produk Stok Terbanyak</h6>
                    <small class="text-muted" style="font-size:0.72rem;">Top 5 produk dengan persediaan terbanyak</small>
                </div>
                <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary btn-sm">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">NAMA PRODUK</th>
                            <th class="text-end pe-3">STOK TERSEDIA</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($produkStokTerbanyak as $p)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-dark">{{ $p->nama_produk }}</div>
                            <small class="text-muted font-monospace" style="font-size:0.7rem;">{{ $p->kode_produk }}</small>
                        </td>
                        <td class="text-end pe-3">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 fw-bold fs-6">{{ number_format($p->stok) }} {{ $p->satuan }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center py-4 text-muted small">
                            Belum ada data produk.
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Transaksi Terbaru (Maksimal 10) -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-clock-history me-2 text-primary"></i>10 Transaksi Penjualan Terbaru</h6>
            <small class="text-muted" style="font-size:0.72rem;">Log transaksi aktivitas kasir terbaru</small>
        </div>
        <a href="{{ route('transaksi.index') }}" class="btn btn-primary btn-sm"><i class="bi bi-receipt me-1"></i>Semua Transaksi</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-3 ps-sm-4">KODE TRANSAKSI</th>
                    <th>TANGGAL</th>
                    <th>PELANGGAN</th>
                    <th>PRODUK</th>
                    <th>QTY</th>
                    <th>TOTAL HARGA</th>
                    <th class="pe-3 ps-sm-4">STATUS</th>
                </tr>
            </thead>
            <tbody>
            @forelse($transaksiTerbaru as $t)
            <tr>
                <td class="ps-3 ps-sm-4">
                    <a href="{{ route('transaksi.show', $t) }}" class="fw-bold text-primary text-decoration-none font-monospace">{{ $t->kode_transaksi }}</a>
                </td>
                <td>
                    <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d/m/Y') }}</div>
                    <small class="text-muted" style="font-size:0.7rem;">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('H:i') }} WIB</small>
                </td>
                <td>{{ $t->pelanggan->nama_pelanggan ?? '—' }}</td>
                <td class="fw-semibold text-dark">{{ $t->produk->nama_produk ?? '—' }}</td>
                <td><span class="badge bg-light text-dark border">{{ $t->jumlah }} {{ $t->produk->satuan ?? '' }}</span></td>
                <td class="fw-bold text-dark">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                <td class="pe-3 ps-sm-4">
                    @if($t->status === 'Pending') <span class="badge badge-pending rounded-pill px-3">Pending</span>
                    @elseif($t->status === 'Selesai') <span class="badge badge-selesai rounded-pill px-3">Selesai</span>
                    @else <span class="badge badge-batal rounded-pill px-3">Batal</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-receipt text-muted"></i></div>
                        <h6 class="fw-bold">Belum Ada Transaksi</h6>
                        <p class="text-muted small">Transaksi penjualan yang baru dicatat akan muncul di sini.</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
