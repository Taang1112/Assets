@extends('layouts.app')

@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan')

@section('content')

{{-- ── Stat Cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ede9fe; color:#7c3aed;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Pelanggan</div>
                <div class="stat-value">{{ \App\Models\Pelanggan::count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7; color:#16a34a;">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <div class="stat-label">Pelanggan Aktif</div>
                <div class="stat-value">{{ \App\Models\Pelanggan::where('status','Aktif')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2; color:#dc2626;">
                <i class="bi bi-person-x-fill"></i>
            </div>
            <div>
                <div class="stat-label">Tidak Aktif</div>
                <div class="stat-value">{{ \App\Models\Pelanggan::where('status','Tidak Aktif')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe; color:#2563eb;">
                <i class="bi bi-calendar-plus"></i>
            </div>
            <div>
                <div class="stat-label">Daftar Bulan Ini</div>
                <div class="stat-value">{{ \App\Models\Pelanggan::whereMonth('tanggal_daftar', now()->month)->whereYear('tanggal_daftar', now()->year)->count() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Table Card ── --}}
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h6 class="mb-0 fw-600" style="font-weight:600;">
            <i class="bi bi-list-ul me-1 text-primary"></i> Daftar Pelanggan
        </h6>
        <a href="{{ route('pelanggan.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i> Tambah Pelanggan
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="px-3 py-2 border-bottom bg-light" style="border-color:#f1f5f9!important;">
        <form method="GET" action="{{ route('pelanggan.index') }}" class="row g-2 align-items-center">
            <div class="col-auto flex-grow-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>
                    <input type="text" name="search" value="{{ $search }}"
                           class="form-control" placeholder="Cari nama, kode, telepon, email…">
                </div>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm" style="min-width:130px;">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ $status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ $status === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-auto d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm px-3">Filter</button>
                @if($search || $status)
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:48px;">#</th>
                        <th>Kode</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Telepon</th>
                        <th>Email</th>
                        <th>Tgl Daftar</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggan as $item)
                    <tr>
                        <td class="text-secondary">{{ $pelanggan->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="badge bg-light text-dark border fw-semibold" style="font-size:.78rem; letter-spacing:.3px;">
                                {{ $item->kode_pelanggan }}
                            </span>
                        </td>
                        <td class="fw-semibold text-dark">{{ $item->nama_pelanggan }}</td>
                        <td>
                            <i class="bi bi-telephone text-secondary me-1"></i>{{ $item->no_telepon }}
                        </td>
                        <td>
                            @if($item->email)
                                <a href="mailto:{{ $item->email }}" class="text-decoration-none text-secondary">
                                    {{ $item->email }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $item->tanggal_daftar->format('d M Y') }}</td>
                        <td class="text-center">
                            @if($item->status === 'Aktif')
                                <span class="badge badge-aktif rounded-pill px-3 py-1">
                                    <i class="bi bi-circle-fill me-1" style="font-size:.45rem;"></i>Aktif
                                </span>
                            @else
                                <span class="badge badge-nonaktif rounded-pill px-3 py-1">
                                    <i class="bi bi-circle-fill me-1" style="font-size:.45rem;"></i>Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('pelanggan.show', $item) }}"
                                   class="btn btn-icon btn-outline-secondary btn-sm"
                                   title="Lihat Detail">
                                    <i class="bi bi-eye" style="font-size:.875rem;"></i>
                                </a>
                                <a href="{{ route('pelanggan.edit', $item) }}"
                                   class="btn btn-icon btn-outline-primary btn-sm"
                                   title="Edit">
                                    <i class="bi bi-pencil" style="font-size:.875rem;"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-icon btn-outline-danger btn-sm"
                                        title="Hapus"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapus"
                                        data-id="{{ $item->pelanggan_id }}"
                                        data-nama="{{ $item->nama_pelanggan }}">
                                    <i class="bi bi-trash" style="font-size:.875rem;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-30"></i>
                            Tidak ada data pelanggan ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($pelanggan->hasPages())
    <div class="card-footer bg-white d-flex align-items-center justify-content-between flex-wrap gap-2">
        <small class="text-muted">
            Menampilkan {{ $pelanggan->firstItem() }}–{{ $pelanggan->lastItem() }}
            dari {{ $pelanggan->total() }} pelanggan
        </small>
        {{ $pelanggan->links() }}
    </div>
    @endif
</div>

{{-- ── Modal Hapus ── --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:56px;height:56px;background:#fee2e2;">
                    <i class="bi bi-trash3 text-danger fs-4"></i>
                </div>
                <h6 class="fw-bold mb-1">Hapus Pelanggan?</h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    <strong id="hapusNama"></strong> akan dihapus secara permanen.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0 pb-4">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4"
                        data-bs-dismiss="modal">Batal</button>
                <form id="formHapus" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-4">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const modalHapus = document.getElementById('modalHapus');
    modalHapus.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('hapusNama').textContent = btn.dataset.nama;
        document.getElementById('formHapus').action = `/pelanggan/${btn.dataset.id}`;
    });
</script>
@endpush
