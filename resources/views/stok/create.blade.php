@extends('layouts.app')
@section('title', 'Restock Produk')
@section('page-title', 'Form Restock Produk')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size:.8125rem;">
                <li class="breadcrumb-item">
                    <a href="{{ route('stok.index') }}" class="text-decoration-none text-primary">
                        <i class="bi bi-clock-history me-1"></i>Riwayat Stok
                    </a>
                </li>
                <li class="breadcrumb-item active">Restock Baru</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:36px;height:36px;background:#ede9fe;">
                    <i class="bi bi-box-seam-fill text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Form Tambah Stok (Restock)</h6>
                    <small class="text-muted">Perbarui stok dan harga beli produk dari supplier</small>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('stok.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3">
                        {{-- Produk --}}
                        <div class="col-12">
                            <label for="produk_id" class="form-label">
                                Pilih Produk (Aktif) <span class="text-danger">*</span>
                            </label>
                            <select id="produk_id" name="produk_id"
                                    class="form-select @error('produk_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($produks as $p)
                                    <option value="{{ $p->produk_id }}"
                                        data-stok="{{ $p->stok }}"
                                        data-harga="{{ $p->harga_beli }}"
                                        data-satuan="{{ $p->satuan }}"
                                        {{ (old('produk_id', request('produk_id')) == $p->produk_id) ? 'selected' : '' }}>
                                        [{{ $p->kode_produk }}] {{ $p->nama_produk }} (Stok: {{ $p->stok }} {{ $p->satuan }} | Beli: Rp {{ number_format($p->harga_beli, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('produk_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Supplier --}}
                        <div class="col-sm-6">
                            <label for="supplier_id" class="form-label">
                                Supplier (Aktif) <span class="text-danger">*</span>
                            </label>
                            <select id="supplier_id" name="supplier_id"
                                    class="form-select @error('supplier_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->supplier_id }}" {{ old('supplier_id') == $sup->supplier_id ? 'selected' : '' }}>
                                        {{ $sup->nama_supplier }} @if($sup->nama_perusahaan) ({{ $sup->nama_perusahaan }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Karyawan / Petugas --}}
                        <div class="col-sm-6">
                            <label for="karyawan_id" class="form-label">
                                Petugas / Karyawan <span class="text-danger">*</span>
                            </label>
                            <select id="karyawan_id" name="karyawan_id"
                                    class="form-select @error('karyawan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Petugas --</option>
                                @foreach($karyawans as $kar)
                                    <option value="{{ $kar->karyawan_id }}" {{ old('karyawan_id') == $kar->karyawan_id ? 'selected' : '' }}>
                                        {{ $kar->nama_lengkap }} ({{ $kar->nik }})
                                    </option>
                                @endforeach
                            </select>
                            @error('karyawan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Masuk --}}
                        <div class="col-sm-6">
                            <label for="tanggal_masuk" class="form-label">
                                Tanggal Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" id="tanggal_masuk" name="tanggal_masuk"
                                   class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                   value="{{ old('tanggal_masuk', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stok Masuk --}}
                        <div class="col-sm-6">
                            <label for="stok_masuk" class="form-label">
                                Jumlah Stok Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="stok_masuk" name="stok_masuk" min="1"
                                   class="form-control @error('stok_masuk') is-invalid @enderror"
                                   value="{{ old('stok_masuk', 1) }}" required>
                            @error('stok_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga Beli Baru --}}
                        <div class="col-sm-6">
                            <label for="harga_beli" class="form-label">
                                Harga Beli Terbaru (Rp) <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="harga_beli" name="harga_beli" min="0" step="100"
                                   class="form-control @error('harga_beli') is-invalid @enderror"
                                   value="{{ old('harga_beli') }}" placeholder="Contoh: 35000" required>
                            @error('harga_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga Jual Batch --}}
                        <div class="col-sm-6">
                            <label for="harga_jual" class="form-label">
                                Harga Jual Batch (Rp) <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="harga_jual" name="harga_jual" min="0" step="100"
                                   class="form-control @error('harga_jual') is-invalid @enderror"
                                   value="{{ old('harga_jual') }}" placeholder="Contoh: 55000" required>
                            @error('harga_jual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-12">
                            <label for="keterangan" class="form-label">Keterangan / Catatan</label>
                            <textarea id="keterangan" name="keterangan" rows="2"
                                      class="form-control @error('keterangan') is-invalid @enderror"
                                      placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('stok.index') }}" class="btn btn-light px-4 border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i>Simpan Restock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('produk_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const hargaBeliInput = document.getElementById('harga_beli');
        if (selectedOption && selectedOption.dataset.harga) {
            if (!hargaBeliInput.value) {
                hargaBeliInput.value = selectedOption.dataset.harga;
            }
        }
    });
</script>
@endpush

@endsection
