@extends('layouts.app')
@section('title','Data Transaksi')
@section('page-title','Data Transaksi')
@section('content')

<!-- Stat Cards (100% Mobile Responsive Grid) -->
<div class="row g-2 g-sm-3 mb-3 mb-sm-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-receipt-cutoff"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Total Transaksi</div>
                <div class="stat-value">{{ \App\Models\Transaksi::count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-wallet2"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Pendapatan Selesai</div>
                <div class="stat-value" style="font-size:1.15rem;">Rp {{ number_format(\App\Models\Transaksi::where('status','Selesai')->sum('total_harga'), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-clock-history"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Pending</div>
                <div class="stat-value">{{ \App\Models\Transaksi::where('status','Pending')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-check2-circle"></i></div>
            <div class="overflow-hidden">
                <div class="stat-label text-truncate">Selesai</div>
                <div class="stat-value">{{ \App\Models\Transaksi::where('status','Selesai')->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2.5">
        <div>
            <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-receipt me-2 text-primary"></i>Daftar Transaksi</h6>
            <small class="text-muted" style="font-size:0.75rem;">Kelola data transaksi penjualan toko</small>
        </div>
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-sm w-100 w-sm-auto"><i class="bi bi-plus-lg me-1"></i>Tambah Transaksi</a>
    </div>

    <div class="px-3 px-sm-4 py-3 border-bottom bg-light bg-opacity-50">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-5 col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 ps-0" placeholder="Cari kode TRX, pelanggan, produk…">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <select name="metode_pembayaran" class="form-select">
                    <option value="">Semua Metode</option>
                    @foreach(['Cash','Transfer','QRIS','E-Wallet'] as $m)
                        <option value="{{ $m }}" {{ $metode === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['Pending','Selesai','Batal'] as $s)
                        <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2 ms-auto">
                <button type="submit" class="btn btn-primary w-100 w-md-auto px-4"><i class="bi bi-filter me-1"></i>Filter</button>
                @if($search || $status || $metode)
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary w-100 w-md-auto"><i class="bi bi-x-circle me-1"></i>Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-3 ps-sm-4">#</th>
                    <th>KODE TRX</th>
                    <th>TANGGAL</th>
                    <th>PELANGGAN</th>
                    <th>PRODUK</th>
                    <th>QTY</th>
                    <th>TOTAL HARGA</th>
                    <th>METODE</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center pe-3 pe-sm-4">AKSI</th>
                </tr>
            </thead>
            <tbody>
            @forelse($transaksi as $item)
            <tr>
                <td class="ps-3 ps-sm-4 text-muted font-monospace" style="font-size:0.75rem;">{{ $transaksi->firstItem() + $loop->index }}</td>
                <td><span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-semibold" style="font-size:0.78rem;">{{ $item->kode_transaksi }}</span></td>
                <td>
                    <div class="fw-semibold text-dark">{{ $item->tanggal_transaksi->format('d M Y') }}</div>
                    <small class="text-muted" style="font-size:0.72rem;">{{ $item->tanggal_transaksi->format('H:i') }} WIB</small>
                </td>
                <td class="fw-semibold">{{ $item->pelanggan->nama_pelanggan ?? '—' }}</td>
                <td>{{ $item->produk->nama_produk ?? '—' }}</td>
                <td><span class="badge bg-secondary-subtle text-secondary px-2 py-1 fw-bold">{{ $item->jumlah }}</span></td>
                <td class="fw-bold text-primary">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                <td><span class="badge bg-light text-secondary border px-2 py-1 fw-semibold">{{ $item->metode_pembayaran }}</span></td>
                <td class="text-center">
                    @if($item->status === 'Pending')
                        <span class="badge-pill-custom badge-pending"><i class="bi bi-clock-history"></i> Pending</span>
                    @elseif($item->status === 'Selesai')
                        <span class="badge-pill-custom badge-selesai"><i class="bi bi-check-circle-fill"></i> Selesai</span>
                    @else
                        <span class="badge-pill-custom badge-batal"><i class="bi bi-x-circle-fill"></i> Batal</span>
                    @endif
                </td>
                <td class="text-center pe-3 pe-sm-4">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('transaksi.show', $item) }}" class="btn btn-icon btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('transaksi.edit', $item) }}" class="btn btn-icon btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <button class="btn btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $item->transaksi_id }}" data-nama="{{ $item->kode_transaksi }}" title="Hapus"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-receipt-cutoff"></i></div>
                        <h6 class="fw-bold mb-1">Belum Ada Transaksi</h6>
                        <p class="text-muted small mb-3">Data transaksi tidak ditemukan atau belum pernah dibuat.</p>
                        <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-sm px-4"><i class="bi bi-plus-lg me-1"></i>Buat Transaksi Pertama</a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($transaksi->hasPages())
    <div class="card-footer bg-white d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 px-3 px-sm-4">
        <small class="text-muted fw-semibold" style="font-size:0.75rem;">Menampilkan {{ $transaksi->firstItem() }}–{{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} data</small>
        {{ $transaksi->links() }}
    </div>
    @endif
</div>

@include('partials.modal-hapus')
@endsection

@push('scripts')
<script>
    document.getElementById('modalHapus').addEventListener('show.bs.modal', e => {
        const btn = e.relatedTarget;
        document.getElementById('hapusNama').textContent = 'Transaksi ' + btn.dataset.nama;
        document.getElementById('formHapus').action = `/transaksi/${btn.dataset.id}`;
    });
</script>
@endpush
