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
        return view('transaksi.create', compact('kode', 'produk', 'pelanggan', 'karyawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id'          => ['required', 'exists:produk,produk_id'],
            'pelanggan_id'       => ['required', 'exists:pelanggan,pelanggan_id'],
            'karyawan_id'        => ['required', 'exists:karyawan,karyawan_id'],
            'kode_transaksi'     => ['required', 'string', 'max:30', 'unique:transaksi,kode_transaksi'],
            'tanggal_transaksi'  => ['required', 'date'],
            'jumlah'             => ['required', 'integer', 'min:1'],
            'harga_satuan'       => ['required', 'numeric', 'min:0'],
            'total_harga'        => ['required', 'numeric', 'min:0'],
            'metode_pembayaran'  => ['required', 'in:Cash,Transfer,QRIS,E-Wallet'],
            'status'             => ['required', 'in:Pending,Selesai,Batal'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $transaksi = Transaksi::create($request->all());

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
        return view('transaksi.edit', compact('transaksi', 'produk', 'pelanggan', 'karyawan'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'produk_id'          => ['required', 'exists:produk,produk_id'],
            'pelanggan_id'       => ['required', 'exists:pelanggan,pelanggan_id'],
            'karyawan_id'        => ['required', 'exists:karyawan,karyawan_id'],
            'kode_transaksi'     => ['required', 'string', 'max:30', 'unique:transaksi,kode_transaksi,' . $transaksi->transaksi_id . ',transaksi_id'],
            'tanggal_transaksi'  => ['required', 'date'],
            'jumlah'             => ['required', 'integer', 'min:1'],
            'harga_satuan'       => ['required', 'numeric', 'min:0'],
            'total_harga'        => ['required', 'numeric', 'min:0'],
            'metode_pembayaran'  => ['required', 'in:Cash,Transfer,QRIS,E-Wallet'],
            'status'             => ['required', 'in:Pending,Selesai,Batal'],
        ]);

        $oldStatus = $transaksi->status;
        $newStatus = $request->status;

        try {
            DB::transaction(function () use ($request, $transaksi, $oldStatus, $newStatus) {
                // Revert previous FIFO allocation if transaction was previously Selesai
                if ($oldStatus === 'Selesai') {
                    $this->revertFifoSales($transaksi);
                }

                $transaksi->update($request->all());

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

        foreach ($batches as $batch) {
            if ($remainingToDeduct <= 0) {
                break;
            }

            $deductQty = min($batch->stok_tersisa, $remainingToDeduct);
            $batch->stok_tersisa -= $deductQty;
            $batch->save();

            $subtotal = $deductQty * $batch->harga_jual;

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
