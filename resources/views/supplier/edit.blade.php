@extends('layouts.app')
@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size:.8125rem;">
                <li class="breadcrumb-item">
                    <a href="{{ route('supplier.index') }}" class="text-decoration-none text-primary">
                        <i class="bi bi-truck me-1"></i>Data Supplier
                    </a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:36px;height:36px;background:#ede9fe;">
                    <i class="bi bi-pencil-square text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Form Edit Supplier</h6>
                    <small class="text-muted">Perbarui informasi supplier</small>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('supplier.update', $supplier) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Kode Supplier --}}
                        <div class="col-sm-6">
                            <label for="kode_supplier" class="form-label">
                                Kode Supplier <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="kode_supplier" name="kode_supplier"
                                   class="form-control @error('kode_supplier') is-invalid @enderror"
                                   value="{{ old('kode_supplier', $supplier->kode_supplier) }}" required>
                            @error('kode_supplier')
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
                                <option value="Aktif"       {{ old('status', $supplier->status) === 'Aktif'       ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ old('status', $supplier->status) === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Supplier --}}
                        <div class="col-12">
                            <label for="nama_supplier" class="form-label">
                                Nama Supplier <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nama_supplier" name="nama_supplier"
                                   class="form-control @error('nama_supplier') is-invalid @enderror"
                                   value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required>
                            @error('nama_supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Perusahaan --}}
                        <div class="col-12">
                            <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                            <input type="text" id="nama_perusahaan" name="nama_perusahaan"
                                   class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                   value="{{ old('nama_perusahaan', $supplier->nama_perusahaan) }}">
                            @error('nama_perusahaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- No Telepon --}}
                        <div class="col-sm-6">
                            <label for="no_telepon" class="form-label">No. Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" id="no_telepon" name="no_telepon"
                                       class="form-control @error('no_telepon') is-invalid @enderror"
                                       value="{{ old('no_telepon', $supplier->no_telepon) }}">
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
                                       value="{{ old('email', $supplier->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3"
                                      class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $supplier->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('supplier.index') }}" class="btn btn-light px-4 border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>Perbarui Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
