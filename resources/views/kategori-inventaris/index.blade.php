@extends('layouts.app')
@section('title','Kategori Inventaris')
@section('page-title','Kategori Inventaris')
@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h6 class="mb-0 fw-bold fs-6"><i class="bi bi-tag me-2 text-primary"></i>Daftar Kategori Inventaris</h6>
            <small class="text-muted">Kelola pengelompokan aset & barang toko</small>
        </div>
        <a href="{{ route('kategori-inventaris.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Kategori</a>
    </div>

    <div class="px-4 py-3 border-bottom bg-light bg-opacity-50">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-7 col-lg-8">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 ps-0" placeholder="Cari nama atau kode kategori…">
                </div>
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary px-3"><i class="bi bi-filter me-1"></i>Filter</button>
                @if($search)
                    <a href="{{ route('kategori-inventaris.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">#</th>
                    <th>KODE KATEGORI</th>
                    <th>NAMA KATEGORI</th>
                    <th>DESKRIPSI</th>
                    <th>TOTAL INVENTARIS</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center pe-4">AKSI</th>
                </tr>
            </thead>
            <tbody>
            @forelse($kategori as $item)
            <tr>
                <td class="ps-4 text-muted font-monospace" style="font-size:0.78rem;">{{ $kategori->firstItem() + $loop->index }}</td>
                <td><span class="badge bg-light text-dark border px-2 py-1 font-monospace fw-semibold" style="font-size:0.8rem;">{{ $item->kode_kategori }}</span></td>
                <td class="fw-bold text-dark">{{ $item->nama_kategori }}</td>
                <td class="text-muted">{{ Str::limit($item->deskripsi, 60) ?? '—' }}</td>
                <td><span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 fw-bold">{{ $item->inventaris_count }} barang</span></td>
                <td class="text-center">
                    @if($item->status === 'Aktif')
                        <span class="badge-pill-custom badge-aktif"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                    @else
                        <span class="badge-pill-custom badge-nonaktif"><i class="bi bi-x-circle-fill"></i> Tidak Aktif</span>
                    @endif
                </td>
                <td class="text-center pe-4">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('kategori-inventaris.show', $item) }}" class="btn btn-icon btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('kategori-inventaris.edit', $item) }}" class="btn btn-icon btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <button class="btn btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $item->kategori_inventaris_id }}" data-nama="{{ $item->nama_kategori }}" title="Hapus"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-tag"></i></div>
                        <h6 class="fw-bold mb-1">Belum Ada Kategori Inventaris</h6>
                        <p class="text-muted small mb-3">Data kategori tidak ditemukan atau belum pernah ditambahkan.</p>
                        <a href="{{ route('kategori-inventaris.create') }}" class="btn btn-primary btn-sm px-4"><i class="bi bi-plus-lg me-1"></i>Tambah Kategori Pertama</a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($kategori->hasPages())
    <div class="card-footer bg-white d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 px-4">
        <small class="text-muted fw-semibold">Menampilkan {{ $kategori->firstItem() }}–{{ $kategori->lastItem() }} dari {{ $kategori->total() }} data</small>
        {{ $kategori->links() }}
    </div>
    @endif
</div>

@include('partials.modal-hapus')
@endsection

@push('scripts')
<script>
    document.getElementById('modalHapus').addEventListener('show.bs.modal', e => {
        const btn = e.relatedTarget;
        document.getElementById('hapusNama').textContent = btn.dataset.nama;
        document.getElementById('formHapus').action = `/kategori-inventaris/${btn.dataset.id}`;
    });
</script>
@endpush
