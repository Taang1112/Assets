@extends('layouts.app')
@section('title','Detail Inventaris')
@section('page-title','Detail Inventaris')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('inventaris.index') }}" class="text-decoration-none text-primary">Inventaris</a></li><li class="breadcrumb-item active">{{ $inventaris->kode_inventaris }}</li></ol></nav>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><h6 class="mb-0 fw-bold">{{ $inventaris->nama_inventaris }}</h6><small class="text-muted">{{ $inventaris->kode_inventaris }}</small></div>
            <a href="{{ route('inventaris.edit',$inventaris) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">KODE INVENTARIS</div><div class="fw-semibold">{{ $inventaris->kode_inventaris }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">KATEGORI</div><div class="fw-semibold">{{ $inventaris->kategori->nama_kategori ?? '—' }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">JUMLAH</div><div class="fw-semibold">{{ $inventaris->jumlah }} unit</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">KONDISI</div>
                    @if($inventaris->kondisi === 'Baik') <span class="badge badge-baik rounded-pill px-3">Baik</span>
                    @elseif($inventaris->kondisi === 'Rusak Ringan') <span class="badge badge-rringan rounded-pill px-3">Rusak Ringan</span>
                    @else <span class="badge badge-rberat rounded-pill px-3">Rusak Berat</span>
                    @endif
                </div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">LOKASI</div><div class="fw-semibold">{{ $inventaris->lokasi }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">STATUS</div>
                    @if($inventaris->status === 'Tersedia') <span class="badge badge-tersedia rounded-pill px-3">Tersedia</span>
                    @elseif($inventaris->status === 'Dipakai') <span class="badge badge-dipakai rounded-pill px-3">Dipakai</span>
                    @elseif($inventaris->status === 'Dipinjam') <span class="badge badge-dipinjam rounded-pill px-3">Dipinjam</span>
                    @else <span class="badge badge-dihapus rounded-pill px-3">Dihapus</span>
                    @endif
                </div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">TANGGAL MASUK</div><div class="fw-semibold">{{ $inventaris->tanggal_masuk->format('d M Y') }}</div></div></div>
                <div class="col-sm-6"><div class="p-3 rounded-3 bg-light"><div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;">HARGA PEROLEHAN</div><div class="fw-semibold">Rp {{ number_format($inventaris->harga_perolehan, 0, ',', '.') }}</div></div></div>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $inventaris->inventaris_id }}" data-nama="{{ $inventaris->nama_inventaris }}"><i class="bi bi-trash me-1"></i>Hapus</button>
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
        document.getElementById('formHapus').action = `/inventaris/${btn.dataset.id}`;
    });
</script>
@endpush
