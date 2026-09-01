@extends('layouts.app')

@section('title', 'Tambah Produk Baru')
@section('page-title', 'Tambah Produk')

@section('content')
<div class="container-fluid p-0" style="max-width: 900px;">
    <!-- Page Breadcrumb / Navigation -->
    <div class="mb-3">
        <a href="{{ route('produk.index') }}" class="text-decoration-none text-muted small fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Produk
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-slate-800"><i class="bi bi-box-seam me-2 text-indigo"></i>Form Tambah Produk Baru</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('produk.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Kode Produk -->
                    <div class="col-12 col-md-6">
                        <label for="kode_produk" class="form-label">Kode Produk <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-qr-code"></i></span>
                            <input type="text" id="kode_produk" name="kode_produk"
                                   class="form-control @error('kode_produk') is-invalid @enderror"
                                   value="{{ old('kode_produk', $kode) }}" required placeholder="Contoh: PRD-00001">
                        </div>
                        @error('kode_produk')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kategori Produk -->
                    <div class="col-12 col-md-6">
                        <label for="kategori_produk_id" class="form-label">Kategori Produk <span class="text-danger">*</span></label>
                        <select id="kategori_produk_id" name="kategori_produk_id"
                                class="form-select @error('kategori_produk_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->kategori_produk_id }}" {{ old('kategori_produk_id') == $kat->kategori_produk_id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_produk_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nama Produk -->
                    <div class="col-12">
                        <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" id="nama_produk" name="nama_produk"
                               class="form-control @error('nama_produk') is-invalid @enderror"
                               value="{{ old('nama_produk') }}" required placeholder="Masukkan nama produk">
                        @error('nama_produk')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Harga Beli -->
                    <div class="col-12 col-md-6">
                        <label for="harga_beli" class="form-label">Harga Beli (Modal) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" step="0.01" min="0" id="harga_beli" name="harga_beli"
                                   class="form-control @error('harga_beli') is-invalid @enderror"
                                   value="{{ old('harga_beli', 0) }}" required placeholder="0">
                        </div>
                        @error('harga_beli')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div class="col-12 col-md-6">
                        <label for="harga_jual" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" step="0.01" min="0" id="harga_jual" name="harga_jual"
                                   class="form-control @error('harga_jual') is-invalid @enderror"
                                   value="{{ old('harga_jual', 0) }}" required placeholder="0">
                        </div>
                        @error('harga_jual')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stok -->
                    <div class="col-12 col-md-4">
                        <label for="stok" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" min="0" id="stok" name="stok"
                               class="form-control @error('stok') is-invalid @enderror"
                               value="{{ old('stok', 0) }}" required placeholder="0">
                        @error('stok')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Satuan -->
                    <div class="col-12 col-md-4">
                        <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                        <input type="text" id="satuan" name="satuan"
                               class="form-control @error('satuan') is-invalid @enderror"
                               value="{{ old('satuan', 'Pcs') }}" required placeholder="Contoh: Pcs, Unit, Box, Kg">
                        @error('satuan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-md-4">
                        <label for="status" class="form-label">Status Produk <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-12">
                        <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  placeholder="Masukkan rincian atau catatan mengenai produk (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4" style="border-color: #f1f5f9;">

                <!-- Form Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('produk.index') }}" class="btn btn-light border px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
