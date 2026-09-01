@extends('layouts.app')
@section('title','Detail Kategori Produk')
@section('page-title','Detail Kategori Produk')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
    <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('kategori-produk.index') }}" class="text-decoration-none text-primary">Kategori Produk</a></li><li class="breadcrumb-item active">{{ $kategoriProduk->kode_kategori }}</li></ol></nav>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><h6 class="mb-0 fw-bold">{{ $kategoriProduk->nama_kategori }}</h6><small class="text-muted">{{ $kategoriProduk->kode_kategori }}</small></div>
            <a href="{{ route('kategori-produk.edit',$kategoriProduk) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">KODE</div><div class="fw-semibold">{{ $kategoriProduk->kode_kategori }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">STATUS</div>
                    <span class="badge {{ $kategoriProduk->status === 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' }} rounded-pill px-3">{{ $kategoriProduk->status }}</span>
                </div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">JUMLAH PRODUK</div><div class="fw-semibold">{{ $kategoriProduk->produk_count }} produk</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">DIBUAT</div><div class="fw-semibold">{{ $kategoriProduk->created_at->format('d M Y') }}</div></div></div>
                <div class="col-12"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">DESKRIPSI</div><div class="fw-semibold">{{ $kategoriProduk->deskripsi ?? '—' }}</div></div></div>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('kategori-produk.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $kategoriProduk->kategori_produk_id }}" data-nama="{{ $kategoriProduk->nama_kategori }}"><i class="bi bi-trash me-1"></i>Hapus</button>
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
        document.getElementById('formHapus').action = `/kategori-produk/${btn.dataset.id}`;
    });
</script>
@endpush
