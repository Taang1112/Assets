@extends('layouts.app')
@section('title','Detail Transaksi')
@section('page-title','Detail Transaksi')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}" class="text-decoration-none text-primary">Transaksi</a></li>
                <li class="breadcrumb-item active">{{ $transaksi->kode_transaksi }}</li>
            </ol>
        </nav>

        {{-- Main Transaksi Info Card --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">Transaksi: {{ $transaksi->kode_transaksi }}</h6>
                    <small class="text-muted">{{ $transaksi->tanggal_transaksi->format('d F Y, H:i') }} WIB</small>
                </div>
                <a href="{{ route('transaksi.edit',$transaksi) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">KODE TRANSAKSI</div><div class="fw-semibold">{{ $transaksi->kode_transaksi }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">STATUS TRANSAKSI</div>
                        @if($transaksi->status === 'Pending') <span class="badge badge-pending rounded-pill px-3">Pending</span>
                        @elseif($transaksi->status === 'Selesai') <span class="badge badge-selesai rounded-pill px-3">Selesai</span>
                        @else <span class="badge badge-batal rounded-pill px-3">Batal</span>
                        @endif
                    </div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">PELANGGAN</div><div class="fw-semibold">{{ $transaksi->pelanggan->nama_pelanggan ?? '—' }}</div><small class="text-muted">{{ $transaksi->pelanggan->kode_pelanggan ?? '' }}</small></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">KARYAWAN (KASIR)</div><div class="fw-semibold">{{ $transaksi->karyawan->nama_lengkap ?? '—' }}</div><small class="text-muted">NIK: {{ $transaksi->karyawan->nik ?? '' }}</small></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">PRODUK</div><div class="fw-semibold">{{ $transaksi->produk->nama_produk ?? '—' }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">JUMLAH (QTY)</div><div class="fw-semibold">{{ $transaksi->jumlah }} {{ $transaksi->produk->satuan ?? 'unit' }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">HARGA SATUAN (ACUAN)</div><div class="fw-semibold">Rp {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">TOTAL HARGA TRANSAKSI</div><div class="fw-bold text-primary fs-5">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">METODE PEMBAYARAN</div><div class="fw-semibold">{{ $transaksi->metode_pembayaran }}</div></div></div>
                    <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">DICATAT TANGGAL</div><div class="fw-semibold">{{ $transaksi->created_at->format('d M Y, H:i') }} WIB</div></div></div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-end gap-2">
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $transaksi->transaksi_id }}" data-nama="{{ $transaksi->kode_transaksi }}"><i class="bi bi-trash me-1"></i>Hapus</button>
            </div>
        </div>

        {{-- Rincian Alokasi Batch (FIFO) --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-diagram-3-fill me-2 text-primary"></i>Rincian Alokasi Batch (FIFO)</h6>
                    <small class="text-muted" style="font-size:0.75rem;">Breakdown pengambilan persediaan dari masing-masing batch berdasarkan urutan FIFO</small>
                </div>
                @if($transaksi->transaksiBatch->count() > 0)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 fw-bold" style="font-size:0.78rem;">
                        {{ $transaksi->transaksiBatch->count() }} Allocation Batch
                    </span>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3 ps-sm-4">BATCH / STOK ID</th>
                            <th>TANGGAL MASUK BATCH</th>
                            <th>SUPPLIER BATCH</th>
                            <th>QTY ALOKASI</th>
                            <th>HARGA BELI SNAPSHOT</th>
                            <th>HARGA JUAL SNAPSHOT</th>
                            <th class="text-end pe-3 pe-sm-4">SUBTOTAL BATCH</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($transaksi->transaksiBatch as $tb)
                    <tr>
                        <td class="ps-3 ps-sm-4 text-muted font-monospace" style="font-size:0.75rem;">Batch #{{ $tb->stok_id }}</td>
                        <td>
                            @if($tb->stok)
                                <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($tb->stok->tanggal_masuk)->format('d M Y') }}</div>
                                <small class="text-muted" style="font-size:0.72rem;">{{ \Carbon\Carbon::parse($tb->stok->tanggal_masuk)->format('H:i') }} WIB</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 fw-semibold">
                                {{ $tb->stok->supplier->nama_supplier ?? '—' }}
                            </span>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-bold">{{ $tb->jumlah }} {{ $transaksi->produk->satuan ?? 'unit' }}</span></td>
                        <td>Rp {{ number_format($tb->harga_beli, 0, ',', '.') }}</td>
                        <td class="fw-semibold text-success">Rp {{ number_format($tb->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-end pe-3 pe-sm-4 fw-bold text-dark">Rp {{ number_format($tb->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="empty-state py-3">
                                <div class="empty-icon"><i class="bi bi-info-circle text-muted"></i></div>
                                <h6 class="fw-bold mb-1">Tidak Ada Alokasi Batch</h6>
                                <p class="text-muted small mb-0">
                                    @if($transaksi->status === 'Pending')
                                        Transaksi masih berstatus <strong>Pending</strong>. Alokasi batch FIFO akan dilakukan otomatis saat status diubah menjadi <strong>Selesai</strong>.
                                    @elseif($transaksi->status === 'Batal')
                                        Transaksi berstatus <strong>Batal</strong>. Alokasi stok batch telah dikembalikan ke persediaan (Reversed).
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1">Transaksi Lama / Legacy (Dibuat Sebelum FIFO Integrated)</span>
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                    @if($transaksi->transaksiBatch->count() > 0)
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td colspan="3" class="ps-3 ps-sm-4 text-uppercase text-muted" style="font-size:0.75rem;">Total Alokasi FIFO</td>
                            <td class="text-primary">{{ $transaksi->transaksiBatch->sum('jumlah') }} {{ $transaksi->produk->satuan ?? 'unit' }}</td>
                            <td colspan="2"></td>
                            <td class="text-end pe-3 pe-sm-4 text-primary fs-6">Rp {{ number_format($transaksi->transaksiBatch->sum('subtotal'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
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
        document.getElementById('hapusNama').textContent = 'Transaksi ' + btn.dataset.nama;
        document.getElementById('formHapus').action = `/transaksi/${btn.dataset.id}`;
    });
</script>
@endpush
