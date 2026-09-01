@extends('layouts.app')
@section('title','Edit Kategori Inventaris')
@section('page-title','Edit Kategori Inventaris')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
    <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('kategori-inventaris.index') }}" class="text-decoration-none text-primary">Kategori Inventaris</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    <div class="card"><div class="card-header"><h6 class="mb-0 fw-bold">Edit: {{ $kategoriInventaris->nama_kategori }}</h6></div>
    <div class="card-body p-4">
        <form action="{{ route('kategori-inventaris.update',$kategoriInventaris) }}" method="POST" novalidate>
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="kode_kategori" class="form-control @error('kode_kategori') is-invalid @enderror" value="{{ old('kode_kategori',$kategoriInventaris->kode_kategori) }}" required>
                    @error('kode_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="Aktif"       {{ old('status',$kategoriInventaris->status) === 'Aktif'       ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status',$kategoriInventaris->status) === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori',$kategoriInventaris->nama_kategori) }}" required>
                    @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control">{{ old('deskripsi',$kategoriInventaris->deskripsi) }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('kategori-inventaris.show',$kategoriInventaris) }}" class="btn btn-outline-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-5"><i class="bi bi-save me-1"></i>Perbarui</button>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
