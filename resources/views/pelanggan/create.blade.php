@extends('layouts.app')

@section('title', 'Tambah Pelanggan')
@section('page-title', 'Tambah Pelanggan Baru')

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
                <li class="breadcrumb-item active">Tambah Baru</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:36px;height:36px;background:#ede9fe;">
                    <i class="bi bi-person-plus-fill text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Form Tambah Pelanggan</h6>
                    <small class="text-muted">Isi semua data yang diperlukan</small>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('pelanggan.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3">
                        {{-- Kode Pelanggan --}}
                        <div class="col-sm-6">
                            <label for="kode_pelanggan" class="form-label">
                                Kode Pelanggan <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="kode_pelanggan" name="kode_pelanggan"
                                   class="form-control @error('kode_pelanggan') is-invalid @enderror"
                                   value="{{ old('kode_pelanggan', $kode) }}"
                                   placeholder="PLG-00001" required>
                            @error('kode_pelanggan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Kode unik untuk pelanggan.</div>
                        </div>

                        {{-- Status --}}
                        <div class="col-sm-6">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select id="status" name="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                <option value="Aktif"       {{ old('status','Aktif') === 'Aktif'       ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ old('status','Aktif') === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
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
                                   value="{{ old('nama_pelanggan') }}"
                                   placeholder="Masukkan nama lengkap" required>
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
                                       value="{{ old('no_telepon') }}"
                                       placeholder="08xxxxxxxxxx" required>
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
                                       value="{{ old('email') }}"
                                       placeholder="contoh@email.com">
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
                                   value="{{ old('tanggal_daftar', now()->format('Y-m-d')) }}" required>
                            @error('tanggal_daftar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3"
                                      class="form-control @error('alamat') is-invalid @enderror"
                                      placeholder="Masukkan alamat lengkap…">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
