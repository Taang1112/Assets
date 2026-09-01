@extends('layouts.app')
@section('title','Tambah Inventaris')
@section('page-title','Tambah Inventaris')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('inventaris.index') }}" class="text-decoration-none text-primary">Inventaris</a></li><li class="breadcrumb-item active">Tambah</li></ol></nav>
    <div class="card"><div class="card-header"><h6 class="mb-0 fw-bold">Form Tambah Inventaris Toko</h6></div>
    <div class="card-body p-4">
        <form action="{{ route('inventaris.store') }}" method="POST" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Kode Inventaris <span class="text-danger">*</span></label>
                    <input type="text" name="kode_inventaris" class="form-control @error('kode_inventaris') is-invalid @enderror" value="{{ old('kode_inventaris', $kode) }}" required>
                    @error('kode_inventaris')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori_inventaris_id" class="form-select @error('kategori_inventaris_id') is-invalid @enderror" required>
                        <option value="">— Pilih Kategori —</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->kategori_inventaris_id }}" {{ old('kategori_inventaris_id') == $kat->kategori_inventaris_id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori_inventaris_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Nama Inventaris <span class="text-danger">*</span></label>
                    <input type="text" name="nama_inventaris" class="form-control @error('nama_inventaris') is-invalid @enderror" value="{{ old('nama_inventaris') }}" required>
                    @error('nama_inventaris')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', 1) }}" min="1" required>
                    @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                    <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                        <option value="Baik" {{ old('kondisi','Baik') === 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('kondisi') === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi') === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                    @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="Tersedia" {{ old('status','Tersedia') === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipakai" {{ old('status') === 'Dipakai' ? 'selected' : '' }}>Dipakai</option>
                        <option value="Dipinjam" {{ old('status') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dihapus" {{ old('status') === 'Dihapus' ? 'selected' : '' }}>Dihapus</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi') }}" placeholder="Contoh: Gudang A, Kasir Utama…" required>
                    @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                    @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Harga Perolehan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_perolehan" step="100" class="form-control @error('harga_perolehan') is-invalid @enderror" value="{{ old('harga_perolehan') }}" required>
                    </div>
                    @error('harga_perolehan')<div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>@enderror
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-5"><i class="bi bi-save me-1"></i>Simpan</button>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
