@extends('layouts.app')
@section('title','Detail Transaksi')
@section('page-title','Detail Transaksi')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}" class="text-decoration-none text-primary">Transaksi</a></li><li class="breadcrumb-item active">{{ $transaksi->kode_transaksi }}</li></ol></nav>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><h6 class="mb-0 fw-bold">Transaksi: {{ $transaksi->kode_transaksi }}</h6><small class="text-muted">{{ $transaksi->tanggal_transaksi->format('d F Y, H:i') }}</small></div>
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
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">HARGA SATUAN</div><div class="fw-semibold">Rp {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">TOTAL HARGA</div><div class="fw-bold text-primary fs-5">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">METODE PEMBAYARAN</div><div class="fw-semibold">{{ $transaksi->metode_pembayaran }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">DICATAT TANGGAL</div><div class="fw-semibold">{{ $transaksi->created_at->format('d M Y, H:i') }}</div></div></div>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $transaksi->transaksi_id }}" data-nama="{{ $transaksi->kode_transaksi }}"><i class="bi bi-trash me-1"></i>Hapus</button>
        </div>
    </div>
</div></div>
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
