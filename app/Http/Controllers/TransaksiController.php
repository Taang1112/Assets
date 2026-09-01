<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;

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

        Transaksi::create($request->all());
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['produk', 'pelanggan', 'karyawan']);
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

        $transaksi->update($request->all());
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function generateKode(): string
    {
        $last = Transaksi::orderBy('transaksi_id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_transaksi, -6)) + 1 : 1;
        return 'TRX-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
