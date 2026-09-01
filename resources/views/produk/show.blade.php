@extends('layouts.app')
@section('title','Detail Produk')
@section('page-title','Detail Produk')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('produk.index') }}" class="text-decoration-none text-primary">Produk</a></li><li class="breadcrumb-item active">{{ $produk->kode_produk }}</li></ol></nav>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><h6 class="mb-0 fw-bold">{{ $produk->nama_produk }}</h6><small class="text-muted">{{ $produk->kode_produk }}</small></div>
            <a href="{{ route('produk.edit',$produk) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">KODE PRODUK</div><div class="fw-semibold">{{ $produk->kode_produk }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">KATEGORI</div><div class="fw-semibold">{{ $produk->kategori->nama_kategori ?? '—' }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">HARGA BELI</div><div class="fw-semibold">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">HARGA JUAL</div><div class="fw-semibold text-success">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">STOK</div><div class="fw-semibold">{{ $produk->stok }} {{ $produk->satuan }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">STATUS</div>
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
</div></div>
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
