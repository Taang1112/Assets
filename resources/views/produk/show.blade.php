@extends('layouts.app')
@section('title','Detail Produk')
@section('page-title','Detail Produk')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('produk.index') }}" class="text-decoration-none text-primary">Produk</a></li>
                <li class="breadcrumb-item active">{{ $produk->kode_produk }}</li>
            </ol>
        </nav>

        {{-- Detail Produk Main Card --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">{{ $produk->nama_produk }}</h6>
                    <small class="text-muted">{{ $produk->kode_produk }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('stok.create', ['produk_id' => $produk->produk_id]) }}" class="btn btn-outline-success btn-sm"><i class="bi bi-box-arrow-in-down me-1"></i>Restock</a>
                    <a href="{{ route('produk.edit',$produk) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">KODE PRODUK</div><div class="fw-semibold">{{ $produk->kode_produk }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">KATEGORI</div><div class="fw-semibold">{{ $produk->kategori->nama_kategori ?? '—' }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">HARGA BELI (ACUAN)</div><div class="fw-semibold">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">HARGA JUAL (ACUAN)</div><div class="fw-semibold text-success">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">TOTAL STOK AKTIF (SUM FIFO)</div><div class="fw-bold text-primary fs-5">{{ $totalStokAktif }} {{ $produk->satuan }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">STATUS PRODUK</div>
                        <span class="badge {{ $produk->status === 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' }} rounded-pill px-3">{{ $produk->status }}</span>
                    </div></div>
                    <div class="col-12"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">DESKRIPSI</div><div class="fw-semibold">{{ $produk->deskripsi ?? '—' }}</div></div></div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-end gap-2">
                <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $produk->produk_id }}" data-nama="{{ $produk->nama_produk }}"><i class="bi bi-trash me-1"></i>Hapus</button>
            </div>
        </div>

        {{-- Detail Batch Layer Aktif (FIFO) --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-layers-half me-2 text-primary"></i>Daftar Batch / Stock Layer Aktif (FIFO)</h6>
                    <small class="text-muted" style="font-size:0.75rem;">Batch persediaan aktif diurutkan berdasarkan tanggal masuk pertama (FIFO)</small>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 fw-bold" style="font-size:0.78rem;">
                    {{ $activeBatches->count() }} Batch Aktif
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3 ps-sm-4">#</th>
                            <th>TANGGAL MASUK</th>
                            <th>SUPPLIER</th>
                            <th>STOK AWAL</th>
                            <th>STOK TERSISA</th>
                            <th>HARGA BELI BATCH</th>
                            <th>HARGA JUAL BATCH</th>
                            <th class="text-center pe-3 pe-sm-4">PRIORITAS FIFO</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($activeBatches as $batch)
                    <tr>
                        <td class="ps-3 ps-sm-4 text-muted font-monospace" style="font-size:0.75rem;">Batch #{{ $batch->stok_id }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($batch->tanggal_masuk)->format('d M Y') }}</div>
                            <small class="text-muted" style="font-size:0.72rem;">{{ \Carbon\Carbon::parse($batch->tanggal_masuk)->format('H:i') }} WIB</small>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 fw-semibold">{{ $batch->supplier->nama_supplier ?? '—' }}</span></td>
                        <td><span class="badge bg-light text-secondary border">{{ $batch->stok_masuk }} {{ $produk->satuan }}</span></td>
                        <td><span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fw-bold fs-6">+{{ $batch->stok_tersisa }} {{ $produk->satuan }}</span></td>
                        <td>Rp {{ number_format($batch->harga_beli, 0, ',', '.') }}</td>
                        <td class="fw-bold text-success">Rp {{ number_format($batch->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-center pe-3 pe-sm-4">
                            @if($loop->first)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 fw-bold"><i class="bi bi-lightning-fill me-1"></i>Urutan Pertukaran (Keluar Pertama)</span>
                            @else
                                <span class="badge bg-light text-dark border px-2 py-1 fw-semibold">Antrean #{{ $loop->iteration }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="empty-state py-3">
                                <div class="empty-icon"><i class="bi bi-box-seam text-muted"></i></div>
                                <h6 class="fw-bold mb-1">Tidak Ada Batch Stok Aktif</h6>
                                <p class="text-muted small mb-3">Stok produk ini sedang habis atau belum dilakukan restock.</p>
                                <a href="{{ route('stok.create', ['produk_id' => $produk->produk_id]) }}" class="btn btn-primary btn-sm px-4"><i class="bi bi-plus-lg me-1"></i>Restock Produk Sekarang</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
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
