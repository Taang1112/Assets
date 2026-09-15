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
                    <label class="form-label">Kode Transaksi</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-light" value="{{ $kode }} (Otomatis)" readonly disabled>
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-magic"></i></span>
                    </div>
                    <small class="text-muted">Kode transaksi dibuat otomatis oleh sistem.</small>
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
                            @php
                                $batches = $pr->fifoBatches ?? collect();
                                $totalStokActive = $batches->sum('qty');
                                $firstBatch = $batches->first();
                                $refHarga = $firstBatch ? $firstBatch['harga_jual'] : $pr->harga_jual;
                            @endphp
                            <option value="{{ $pr->produk_id }}"
                                    data-stok="{{ $totalStokActive }}"
                                    data-batches="{{ json_encode($batches->toArray()) }}"
                                    {{ old('produk_id') == $pr->produk_id ? 'selected' : '' }}>
                                {{ $pr->nama_produk }} — Stok Active: {{ $totalStokActive }} {{ $pr->satuan }} (Batch awal: Rp {{ number_format($refHarga, 0, ',', '.') }})
                            </option>
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
                    <label class="form-label">Harga Satuan (First Batch) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-lock-fill"></i> Rp</span>
                        <input type="number" id="hargaInput" name="harga_satuan" step="100" class="form-control bg-light" value="{{ old('harga_satuan', 0) }}" readonly required>
                    </div>
                    <small class="text-muted" style="font-size:.75rem;">Batch FIFO pertama.</small>
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Total Harga FIFO <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-lock-fill"></i> Rp</span>
                        <input type="number" id="totalInput" name="total_harga" step="100" class="form-control bg-light" value="{{ old('total_harga', 0) }}" readonly required>
                    </div>
                    <small class="text-muted" style="font-size:.75rem;">Total seluruh batch.</small>
                </div>

                <div class="col-12">
                    <div class="alert alert-info py-2 px-3 mb-0 d-flex align-items-center gap-2" style="font-size:0.85rem;">
                        <i class="bi bi-lock-fill fs-5"></i>
                        <div>
                            <strong>Catatan FIFO Stock Layer:</strong>
                            Harga jual otomatis mengikuti harga batch FIFO dan tidak dapat diubah oleh Petugas.
                        </div>
                    </div>
                </div>

                <div class="col-12" id="fifoNoticeContainer" style="display:none;"></div>

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
    const fifoNoticeContainer = document.getElementById('fifoNoticeContainer');

    function hitungTotalFifo() {
        fifoNoticeContainer.style.display = 'none';
        fifoNoticeContainer.innerHTML = '';

        const selectedOption = produkSelect.options[produkSelect.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            hargaInput.value = 0;
            totalInput.value = 0;
            return;
        }

        const totalStok = parseFloat(selectedOption.dataset.stok) || 0;
        const batchesData = selectedOption.dataset.batches ? JSON.parse(selectedOption.dataset.batches) : [];
        const qtyNeeded = parseInt(jumlahInput.value) || 0;

        if (qtyNeeded <= 0) {
            hargaInput.value = 0;
            totalInput.value = 0;
            return;
        }

        if (qtyNeeded > totalStok) {
            fifoNoticeContainer.style.display = 'block';
            fifoNoticeContainer.innerHTML = `
                <div class="alert alert-danger py-2 px-3 mb-0" style="font-size:0.85rem;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <strong>Peringatan Stok:</strong> Jumlah transaksi (${qtyNeeded}) melebihi stok aktif yang tersedia (${totalStok}). Transaksi akan ditolak oleh sistem saat disimpan.
                </div>`;
        }

        let remaining = qtyNeeded;
        let totalHarga = 0;
        let firstHargaSatuan = 0;
        let allocatedBatches = [];

        for (let i = 0; i < batchesData.length; i++) {
            if (remaining <= 0) break;
            let b = batchesData[i];
            let avail = parseInt(b.qty || b.stok_tersisa) || 0;
            if (avail <= 0) continue;

            let deduct = Math.min(avail, remaining);
            if (allocatedBatches.length === 0) {
                firstHargaSatuan = parseFloat(b.harga_jual) || 0;
            }

            let subtotal = deduct * (parseFloat(b.harga_jual) || 0);
            totalHarga += subtotal;

            allocatedBatches.push({
                stok_id: b.stok_id,
                qty: deduct,
                harga_jual: parseFloat(b.harga_jual) || 0,
                subtotal: subtotal,
                supplier: b.supplier || '-'
            });

            remaining -= deduct;
        }

        hargaInput.value = firstHargaSatuan;
        totalInput.value = totalHarga;

        if (allocatedBatches.length > 1) {
            let detailsHtml = allocatedBatches.map(b =>
                `<li>Batch ID ${b.stok_id}: ${b.qty} unit × Rp ${b.harga_jual.toLocaleString('id-ID')} = Rp ${b.subtotal.toLocaleString('id-ID')} (${b.supplier})</li>`
            ).join('');

            if (qtyNeeded <= totalStok) {
                fifoNoticeContainer.style.display = 'block';
                fifoNoticeContainer.innerHTML = `
                    <div class="alert alert-warning py-2 px-3 mb-0" style="font-size:0.85rem;">
                        <i class="bi bi-layers-fill me-1"></i>
                        <strong>Multi-Batch FIFO Detected:</strong> Transaksi ini mengambil stok dari <strong>${allocatedBatches.length} batch FIFO berbeda</strong>:
                        <ul class="mb-0 mt-1 ps-3">${detailsHtml}</ul>
                    </div>`;
            }
        }
    }

    produkSelect.addEventListener('change', hitungTotalFifo);
    jumlahInput.addEventListener('input', hitungTotalFifo);

    document.addEventListener('DOMContentLoaded', hitungTotalFifo);
</script>
@endpush
