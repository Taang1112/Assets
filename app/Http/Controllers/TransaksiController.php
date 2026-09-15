<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\Transaksi;
use App\Models\TransaksiBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->get('search');
        $status  = $request->get('status');
        $metode  = $request->get('metode_pembayaran');

        $transaksi = Transaksi::with(['produk', 'pelanggan', 'karyawan'])
            ->when($search, fn($q) => $q
                ->where('kode_transaksi', 'like', "%{$search}%")
                ->orWhereHas('pelanggan', fn($q2) => $q2->where('nama_pelanggan', 'like', "%{$search}%"))
                ->orWhereHas('produk', fn($q2) => $q2->where('nama_produk', 'like', "%{$search}%")))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($metode, fn($q) => $q->where('metode_pembayaran', $metode))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('transaksi.index', compact('transaksi', 'search', 'status', 'metode'));
    }

    public function create()
    {
        $kode      = $this->generateKode();
        $produk    = Produk::where('status', 'Aktif')->orderBy('nama_produk')->get();
        $pelanggan = Pelanggan::where('status', 'Aktif')->orderBy('nama_pelanggan')->get();
        $karyawan  = Karyawan::orderBy('nama_lengkap')->get();

        $activeBatches = Stok::with('supplier')
            ->where('stok_tersisa', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('stok_id', 'asc')
            ->get()
            ->groupBy('produk_id');

        foreach ($produk as $pr) {
            $batches = $activeBatches->get($pr->produk_id, collect());
            $pr->fifoBatches = $batches->map(function ($b) {
                return [
                    'stok_id'       => $b->stok_id,
                    'qty'           => (int) $b->stok_tersisa,
                    'stok_tersisa'  => (int) $b->stok_tersisa,
                    'harga_jual'    => (float) $b->harga_jual,
                    'harga_beli'    => (float) $b->harga_beli,
                    'tanggal_masuk' => $b->tanggal_masuk ? $b->tanggal_masuk->format('Y-m-d H:i:s') : '',
                    'supplier'      => $b->supplier ? $b->supplier->nama_supplier : '-',
                ];
            })->values();
        }

        return view('transaksi.create', compact('kode', 'produk', 'pelanggan', 'karyawan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id'          => ['required', 'exists:produk,produk_id'],
            'pelanggan_id'       => ['required', 'exists:pelanggan,pelanggan_id'],
            'karyawan_id'        => ['required', 'exists:karyawan,karyawan_id'],
            'tanggal_transaksi'  => ['required', 'date'],
            'jumlah'             => ['required', 'integer', 'min:1'],
            'metode_pembayaran'  => ['required', 'in:Cash,Transfer,QRIS,E-Wallet'],
            'status'             => ['required', 'in:Pending,Selesai,Batal'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $preview = $this->calculateFifoPreview((int) $validated['produk_id'], (int) $validated['jumlah']);

                $validated['kode_transaksi'] = $this->generateKode();
                $validated['harga_satuan']   = $preview['harga_satuan'];
                $validated['total_harga']    = $preview['total_harga'];

                $transaksi = Transaksi::create($validated);

                if ($transaksi->status === 'Selesai') {
                    $this->processFifoSales($transaksi);
                }
            });

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['produk', 'pelanggan', 'karyawan', 'transaksiBatch.stok.supplier']);
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        $produk    = Produk::where('status', 'Aktif')->orderBy('nama_produk')->get();
        $pelanggan = Pelanggan::where('status', 'Aktif')->orderBy('nama_pelanggan')->get();
        $karyawan  = Karyawan::orderBy('nama_lengkap')->get();

        $activeBatches = Stok::with('supplier')
            ->where('stok_tersisa', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('stok_id', 'asc')
            ->get()
            ->groupBy('produk_id');

        foreach ($produk as $pr) {
            $batches = $activeBatches->get($pr->produk_id, collect());
            $pr->fifoBatches = $batches->map(function ($b) {
                return [
                    'stok_id'       => $b->stok_id,
                    'qty'           => (int) $b->stok_tersisa,
                    'stok_tersisa'  => (int) $b->stok_tersisa,
                    'harga_jual'    => (float) $b->harga_jual,
                    'harga_beli'    => (float) $b->harga_beli,
                    'tanggal_masuk' => $b->tanggal_masuk ? $b->tanggal_masuk->format('Y-m-d H:i:s') : '',
                    'supplier'      => $b->supplier ? $b->supplier->nama_supplier : '-',
                ];
            })->values();
        }

        return view('transaksi.edit', compact('transaksi', 'produk', 'pelanggan', 'karyawan'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'produk_id'          => ['required', 'exists:produk,produk_id'],
            'pelanggan_id'       => ['required', 'exists:pelanggan,pelanggan_id'],
            'karyawan_id'        => ['required', 'exists:karyawan,karyawan_id'],
            'tanggal_transaksi'  => ['required', 'date'],
            'jumlah'             => ['required', 'integer', 'min:1'],
            'metode_pembayaran'  => ['required', 'in:Cash,Transfer,QRIS,E-Wallet'],
            'status'             => ['required', 'in:Pending,Selesai,Batal'],
        ]);

        $oldStatus = $transaksi->status;
        $newStatus = $validated['status'];

        try {
            DB::transaction(function () use ($validated, $transaksi, $oldStatus, $newStatus) {
                // Revert previous FIFO allocation if transaction was previously Selesai
                if ($oldStatus === 'Selesai') {
                    $this->revertFifoSales($transaksi);
                }

                $preview = $this->calculateFifoPreview((int) $validated['produk_id'], (int) $validated['jumlah']);
                $validated['harga_satuan'] = $preview['harga_satuan'];
                $validated['total_harga']  = $preview['total_harga'];

                // Explicitly exclude kode_transaksi to guarantee code immutability
                $transaksi->update($validated);

                // Process new FIFO allocation if updated status is Selesai
                if ($newStatus === 'Selesai') {
                    $this->processFifoSales($transaksi);
                }
            });

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    public function destroy(Transaksi $transaksi)
    {
        try {
            DB::transaction(function () use ($transaksi) {
                if ($transaksi->status === 'Selesai') {
                    $this->revertFifoSales($transaksi);
                }
                $transaksi->delete();
            });

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function calculateFifoPreview(int $produkId, int $jumlah): array
    {
        $batches = Stok::where('produk_id', $produkId)
            ->where('stok_tersisa', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('stok_id', 'asc')
            ->get();

        $remainingToDeduct = $jumlah;
        $allocations       = [];
        $totalHarga        = 0;
        $firstHarga        = 0;

        foreach ($batches as $batch) {
            if ($remainingToDeduct <= 0) {
                break;
            }

            $deductQty = min($batch->stok_tersisa, $remainingToDeduct);
            if ($deductQty > 0) {
                if (empty($allocations)) {
                    $firstHarga = (float) $batch->harga_jual;
                }
                $subtotal = $deductQty * (float) $batch->harga_jual;
                $totalHarga += $subtotal;
                $allocations[] = [
                    'stok_id'    => $batch->stok_id,
                    'jumlah'     => $deductQty,
                    'harga_jual' => (float) $batch->harga_jual,
                    'subtotal'   => $subtotal,
                ];
                $remainingToDeduct -= $deductQty;
            }
        }

        return [
            'harga_satuan' => $firstHarga,
            'total_harga'  => $totalHarga,
            'allocations'  => $allocations,
            'multi_batch'  => count($allocations) > 1,
        ];
    }

    private function processFifoSales(Transaksi $transaksi): void
    {
        $produkId  = $transaksi->produk_id;
        $qtyNeeded = $transaksi->jumlah;

        // Fetch active batches ordered by tanggal_masuk ASC, stok_id ASC with lockForUpdate()
        $batches = Stok::where('produk_id', $produkId)
            ->where('stok_tersisa', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('stok_id', 'asc')
            ->lockForUpdate()
            ->get();

        $totalAvailable = $batches->sum('stok_tersisa');
        if ($totalAvailable < $qtyNeeded) {
            throw new \Exception("Stok produk tidak mencukupi untuk alokasi FIFO. Stok tersisa: {$totalAvailable}, dibutuhkan: {$qtyNeeded}.");
        }

        $remainingToDeduct = $qtyNeeded;
        $totalHargaActual  = 0;
        $firstHargaSatuan  = 0;
        $isFirst           = true;

        foreach ($batches as $batch) {
            if ($remainingToDeduct <= 0) {
                break;
            }

            $deductQty = min($batch->stok_tersisa, $remainingToDeduct);
            if ($deductQty <= 0) {
                continue;
            }

            if ($isFirst) {
                $firstHargaSatuan = (float) $batch->harga_jual;
                $isFirst = false;
            }

            $batch->stok_tersisa -= $deductQty;
            $batch->save();

            $subtotal = $deductQty * (float) $batch->harga_jual;
            $totalHargaActual += $subtotal;

            TransaksiBatch::create([
                'transaksi_id' => $transaksi->transaksi_id,
                'stok_id'      => $batch->stok_id,
                'jumlah'       => $deductQty,
                'harga_beli'   => $batch->harga_beli,
                'harga_jual'   => $batch->harga_jual,
                'subtotal'     => $subtotal,
            ]);

            $remainingToDeduct -= $deductQty;
        }

        $transaksi->update([
            'harga_satuan' => $firstHargaSatuan,
            'total_harga'  => $totalHargaActual,
        ]);

        // Sync total product stock from SUM(stok_tersisa)
        $newTotalStok = Stok::where('produk_id', $produkId)->sum('stok_tersisa');
        Produk::where('produk_id', $produkId)->update(['stok' => $newTotalStok]);
    }

    private function revertFifoSales(Transaksi $transaksi): void
    {
        $batches = TransaksiBatch::where('transaksi_id', $transaksi->transaksi_id)->get();

        foreach ($batches as $tb) {
            $stok = Stok::where('stok_id', $tb->stok_id)->lockForUpdate()->first();
            if ($stok) {
                $stok->stok_tersisa += $tb->jumlah;
                $stok->save();
            }
            $tb->delete();
        }

        // Sync total product stock from SUM(stok_tersisa)
        $newTotalStok = Stok::where('produk_id', $transaksi->produk_id)->sum('stok_tersisa');
        Produk::where('produk_id', $transaksi->produk_id)->update(['stok' => $newTotalStok]);
    }

    private function generateKode(): string
    {
        $last = Transaksi::orderBy('transaksi_id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_transaksi, -6)) + 1 : 1;
        return 'TRX-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
