@extends('layouts.app')
@section('title','Tambah Transaksi')
@section('page-title','Tambah Transaksi')
@section('content')
<div class="row justify-content-center"><div class="col-lg-9">
    <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}" class="text-decoration-none text-primary">Transaksi</a></li><li class="breadcrumb-item active">Tambah</li></ol></nav>
    <div class="card"><div class="card-header"><h6 class="mb-0 fw-bold">Form Transaksi Baru</h6></div>
    <div class="card-body p-4">
        <form action="{{ route('transaksi.store') }}" method="POST" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Kode Transaksi <span class="text-danger">*</span></label>
                    <input type="text" name="kode_transaksi" class="form-control @error('kode_transaksi') is-invalid @enderror" value="{{ old('kode_transaksi', $kode) }}" required readonly>
                    @error('kode_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Tanggal & Waktu Transaksi <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="tanggal_transaksi" class="form-control @error('tanggal_transaksi') is-invalid @enderror" value="{{ old('tanggal_transaksi', date('Y-m-d\TH:i')) }}" required>
                    @error('tanggal_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Pelanggan <span class="text-danger">*</span></label>
                    <select name="pelanggan_id" class="form-select @error('pelanggan_id') is-invalid @enderror" required>
                        <option value="">— Pilih Pelanggan —</option>
                        @foreach($pelanggan as $p)
                            <option value="{{ $p->pelanggan_id }}" {{ old('pelanggan_id') == $p->pelanggan_id ? 'selected' : '' }}>{{ $p->nama_pelanggan }} ({{ $p->kode_pelanggan }})</option>
                        @endforeach
                    </select>
                    @error('pelanggan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Karyawan (Kasir) <span class="text-danger">*</span></label>
                    <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror" required>
                        <option value="">— Pilih Karyawan —</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->karyawan_id }}" {{ old('karyawan_id') == $k->karyawan_id ? 'selected' : '' }}>{{ $k->nama_lengkap }} ({{ $k->nik }})</option>
                        @endforeach
                    </select>
                    @error('karyawan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Produk <span class="text-danger">*</span></label>
                    <select id="produkSelect" name="produk_id" class="form-select @error('produk_id') is-invalid @enderror" required>
                        <option value="">— Pilih Produk —</option>
                        @foreach($produk as $pr)
                            <option value="{{ $pr->produk_id }}" data-harga="{{ $pr->harga_jual }}" {{ old('produk_id') == $pr->produk_id ? 'selected' : '' }}>{{ $pr->nama_produk }} — Rp {{ number_format($pr->harga_jual, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                    @error('produk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" id="jumlahInput" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', 1) }}" min="1" required>
                    @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Harga Satuan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="hargaInput" name="harga_satuan" step="100" class="form-control @error('harga_satuan') is-invalid @enderror" value="{{ old('harga_satuan') }}" required>
                    </div>
                    @error('harga_satuan')<div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Total Harga <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="totalInput" name="total_harga" step="100" class="form-control @error('total_harga') is-invalid @enderror" value="{{ old('total_harga') }}" required readonly>
                    </div>
                    @error('total_harga')<div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                        @foreach(['Cash','Transfer','QRIS','E-Wallet'] as $m)
                            <option value="{{ $m }}" {{ old('metode_pembayaran','Cash') === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                    @error('metode_pembayaran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach(['Pending','Selesai','Batal'] as $s)
                            <option value="{{ $s }}" {{ old('status','Pending') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-5"><i class="bi bi-save me-1"></i>Simpan</button>
            </div>
        </form>
    </div></div>
</div></div>
@endsection

@push('scripts')
<script>
    const produkSelect = document.getElementById('produkSelect');
    const jumlahInput = document.getElementById('jumlahInput');
    const hargaInput = document.getElementById('hargaInput');
    const totalInput = document.getElementById('totalInput');

    function hitungTotal() {
        const qty = parseFloat(jumlahInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        totalInput.value = qty * harga;
    }

    produkSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const harga = selectedOption.dataset.harga || 0;
        if(harga) {
            hargaInput.value = harga;
        }
        hitungTotal();
    });

    jumlahInput.addEventListener('input', hitungTotal);
    hargaInput.addEventListener('input', hitungTotal);
</script>
@endpush
