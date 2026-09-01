@extends('layouts.app')

@section('title', 'Edit Pelanggan')
@section('page-title', 'Edit Data Pelanggan')

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
                <li class="breadcrumb-item">
                    <a href="{{ route('pelanggan.show', $pelanggan) }}" class="text-decoration-none text-primary">
                        {{ $pelanggan->kode_pelanggan }}
                    </a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:36px;height:36px;background:#dbeafe;">
                    <i class="bi bi-pencil-fill text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Edit Pelanggan</h6>
                    <small class="text-muted">{{ $pelanggan->kode_pelanggan }} — {{ $pelanggan->nama_pelanggan }}</small>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('pelanggan.update', $pelanggan) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Kode Pelanggan --}}
                        <div class="col-sm-6">
                            <label for="kode_pelanggan" class="form-label">
                                Kode Pelanggan <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="kode_pelanggan" name="kode_pelanggan"
                                   class="form-control @error('kode_pelanggan') is-invalid @enderror"
                                   value="{{ old('kode_pelanggan', $pelanggan->kode_pelanggan) }}" required>
                            @error('kode_pelanggan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-sm-6">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select id="status" name="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                <option value="Aktif"       {{ old('status', $pelanggan->status) === 'Aktif'       ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ old('status', $pelanggan->status) === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama --}}
                        <div class="col-12">
                            <label for="nama_pelanggan" class="form-label">
                                Nama Pelanggan <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nama_pelanggan" name="nama_pelanggan"
                                   class="form-control @error('nama_pelanggan') is-invalid @enderror"
                                   value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required>
                            @error('nama_pelanggan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- No Telepon --}}
                        <div class="col-sm-6">
                            <label for="no_telepon" class="form-label">
                                No. Telepon <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" id="no_telepon" name="no_telepon"
                                       class="form-control @error('no_telepon') is-invalid @enderror"
                                       value="{{ old('no_telepon', $pelanggan->no_telepon) }}" required>
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-sm-6">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" id="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $pelanggan->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Tanggal Daftar --}}
                        <div class="col-sm-6">
                            <label for="tanggal_daftar" class="form-label">
                                Tanggal Daftar <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="tanggal_daftar" name="tanggal_daftar"
                                   class="form-control @error('tanggal_daftar') is-invalid @enderror"
                                   value="{{ old('tanggal_daftar', $pelanggan->tanggal_daftar->format('Y-m-d')) }}" required>
                            @error('tanggal_daftar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3"
                                      class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            Dibuat: {{ $pelanggan->created_at->format('d M Y, H:i') }}
                        </small>
                        <div class="d-flex gap-2">
                            <a href="{{ route('pelanggan.show', $pelanggan) }}" class="btn btn-outline-secondary px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-save me-1"></i>Perbarui
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
