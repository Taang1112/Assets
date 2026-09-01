@extends('layouts.app')
@section('title','Edit Karyawan')
@section('page-title','Edit Karyawan')
@section('content')
<div class="row justify-content-center"><div class="col-lg-9">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}" class="text-decoration-none text-primary">Karyawan</a></li><li class="breadcrumb-item"><a href="{{ route('karyawan.show',$karyawan) }}" class="text-decoration-none text-primary">{{ $karyawan->nik }}</a></li><li class="breadcrumb-item active">Edit</li></ol>
    </nav>
    <div class="card">
        <div class="card-header"><h6 class="mb-0 fw-bold">Edit: {{ $karyawan->nama_lengkap }}</h6></div>
        <div class="card-body p-4">
            <form action="{{ route('karyawan.update',$karyawan) }}" method="POST" novalidate>
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">NIK <span class="text-danger">*</span></label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik',$karyawan->nik) }}" required>
                        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap',$karyawan->nama_lengkap) }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir',$karyawan->tempat_lahir) }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">— Pilih —</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin',$karyawan->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin',$karyawan->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-select">
                            <option value="">— Pilih —</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                                <option value="{{ $ag }}" {{ old('agama',$karyawan->agama) === $ag ? 'selected' : '' }}>{{ $ag }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email',$karyawan->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon',$karyawan->no_telepon) }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Status Pernikahan</label>
                        <select name="status_pernikahan" class="form-select">
                            <option value="">— Pilih —</option>
                            @foreach(['Belum Menikah','Menikah','Cerai'] as $sp)
                                <option value="{{ $sp }}" {{ old('status_pernikahan',$karyawan->status_pernikahan) === $sp ? 'selected' : '' }}>{{ $sp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="form-select">
                            <option value="">— Pilih —</option>
                            @foreach(['SD','SMP','SMA/SMK','D3','S1','S2','S3'] as $pd)
                                <option value="{{ $pd }}" {{ old('pendidikan_terakhir',$karyawan->pendidikan_terakhir) === $pd ? 'selected' : '' }}>{{ $pd }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" rows="3" class="form-control">{{ old('alamat',$karyawan->alamat) }}</textarea>
                    </div>
                </div>
                <hr class="my-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('karyawan.show',$karyawan) }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-5"><i class="bi bi-save me-1"></i>Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
