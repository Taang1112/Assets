@extends('layouts.app')
@section('title','Edit Produk')
@section('page-title','Edit Produk')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('produk.index') }}" class="text-decoration-none text-primary">Produk</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    <div class="card"><div class="card-header"><h6 class="mb-0 fw-bold">Edit: {{ $produk->nama_produk }}</h6></div>
    <div class="card-body p-4">
        <form action="{{ route('produk.update',$produk) }}" method="POST" novalidate>
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Kode Produk <span class="text-danger">*</span></label>
                    <input type="text" name="kode_produk" class="form-control @error('kode_produk') is-invalid @enderror" value="{{ old('kode_produk', $produk->kode_produk) }}" required>
                    @error('kode_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori_produk_id" class="form-select @error('kategori_produk_id') is-invalid @enderror" required>
                        <option value="">— Pilih Kategori —</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->kategori_produk_id }}" {{ old('kategori_produk_id', $produk->kategori_produk_id) == $kat->kategori_produk_id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori_produk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                    @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_beli" step="100" class="form-control @error('harga_beli') is-invalid @enderror" value="{{ old('harga_beli', $produk->harga_beli) }}" required>
                    </div>
                    @error('harga_beli')<div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_jual" step="100" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual', $produk->harga_jual) }}" required>
                    </div>
                    @error('harga_jual')<div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', $produk->stok) }}" required>
                    @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Satuan <span class="text-danger">*</span></label>
                    <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" value="{{ old('satuan', $produk->satuan) }}" required>
                    @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="Aktif" {{ old('status', $produk->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status', $produk->status) === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('produk.show',$produk) }}" class="btn btn-outline-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-5"><i class="bi bi-save me-1"></i>Perbarui</button>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
