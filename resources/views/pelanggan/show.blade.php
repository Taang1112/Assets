@extends('layouts.app')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size:.8125rem;">
                <li class="breadcrumb-item">
                    <a href="{{ route('pelanggan.index') }}" class="text-decoration-none text-primary">
                        <i class="bi bi-people-fill me-1"></i>Data Pelanggan
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ $pelanggan->kode_pelanggan }}</li>
            </ol>
        </nav>

        <div class="card">
            {{-- Header --}}
            <div class="card-header d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;background:linear-gradient(135deg,#4f46e5,#06b6d4);color:#fff;font-size:1.25rem;font-weight:700;">
                        {{ strtoupper(substr($pelanggan->nama_pelanggan, 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $pelanggan->nama_pelanggan }}</h6>
                        <small class="text-muted">{{ $pelanggan->kode_pelanggan }}</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pelanggan.edit', $pelanggan) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    {{-- Info Utama --}}
                    <div class="col-12">
                        <p class="text-muted mb-2" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">
                            Informasi Pelanggan
                        </p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted mb-1" style="font-size:.75rem;">Kode Pelanggan</div>
                                    <div class="fw-semibold">{{ $pelanggan->kode_pelanggan }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted mb-1" style="font-size:.75rem;">Status</div>
                                    @if($pelanggan->status === 'Aktif')
                                        <span class="badge badge-aktif rounded-pill px-3 py-1">
                                            <i class="bi bi-circle-fill me-1" style="font-size:.45rem;"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge badge-nonaktif rounded-pill px-3 py-1">
                                            <i class="bi bi-circle-fill me-1" style="font-size:.45rem;"></i>Tidak Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted mb-1" style="font-size:.75rem;">No. Telepon</div>
                                    <div class="fw-semibold">
                                        <i class="bi bi-telephone text-primary me-1"></i>{{ $pelanggan->no_telepon }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted mb-1" style="font-size:.75rem;">Email</div>
                                    <div class="fw-semibold">
                                        @if($pelanggan->email)
                                            <i class="bi bi-envelope text-primary me-1"></i>
                                            <a href="mailto:{{ $pelanggan->email }}" class="text-decoration-none">
                                                {{ $pelanggan->email }}
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Tidak ada email</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted mb-1" style="font-size:.75rem;">Tanggal Daftar</div>
                                    <div class="fw-semibold">
                                        <i class="bi bi-calendar3 text-primary me-1"></i>
                                        {{ $pelanggan->tanggal_daftar->format('d F Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted mb-1" style="font-size:.75rem;">Bergabung</div>
                                    <div class="fw-semibold">{{ $pelanggan->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted mb-1" style="font-size:.75rem;">Alamat</div>
                                    <div class="fw-semibold">
                                        @if($pelanggan->alamat)
                                            <i class="bi bi-geo-alt text-primary me-1"></i>{{ $pelanggan->alamat }}
                                        @else
                                            <span class="text-muted fst-italic">Tidak ada alamat</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex gap-2">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Diperbarui {{ $pelanggan->updated_at->format('d M Y, H:i') }}
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button class="btn btn-outline-danger btn-sm"
                            data-bs-toggle="modal" data-bs-target="#modalHapus">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
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
                    <strong>{{ $pelanggan->nama_pelanggan }}</strong> akan dihapus secara permanen.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0 pb-4">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4"
                        data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('pelanggan.destroy', $pelanggan) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-4">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
