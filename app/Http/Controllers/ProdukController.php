<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $kategoriId = $request->get('kategori_produk_id');

        $produk = Produk::with('kategori')
            ->when($search, fn($q) => $q
                ->where('nama_produk', 'like', "%{$search}%")
                ->orWhere('kode_produk', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($kategoriId, fn($q) => $q->where('kategori_produk_id', $kategoriId))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kategoris = KategoriProduk::where('status', 'Aktif')->orderBy('nama_kategori')->get();

        return view('produk.index', compact('produk', 'search', 'status', 'kategoriId', 'kategoris'));
    }

    public function create()
    {
        $kode = $this->generateKode();
        $kategoris = KategoriProduk::where('status', 'Aktif')->orderBy('nama_kategori')->get();
        return view('produk.create', compact('kode', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_produk_id' => ['required', 'exists:kategori_produk,kategori_produk_id'],
            'nama_produk'        => ['required', 'string', 'max:100'],
            'deskripsi'          => ['nullable', 'string'],
            'harga_beli'         => ['required', 'numeric', 'min:0'],
            'harga_jual'         => ['required', 'numeric', 'min:0'],
            'stok'               => ['required', 'integer', 'min:0'],
            'satuan'             => ['required', 'string', 'max:20'],
            'status'             => ['required', 'in:Aktif,Tidak Aktif'],
        ]);

        $validated['kode_produk'] = $this->generateKode();

        Produk::create($validated);
        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        $produk->load('kategori');
        $activeBatches = $produk->stok()
            ->with(['supplier', 'karyawan'])
            ->where('stok_tersisa', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('stok_id', 'asc')
            ->get();

        $totalStokAktif = $activeBatches->sum('stok_tersisa');

        return view('produk.show', compact('produk', 'activeBatches', 'totalStokAktif'));
    }

    public function edit(Produk $produk)
    {
        $kategoris = KategoriProduk::where('status', 'Aktif')->orderBy('nama_kategori')->get();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'kategori_produk_id' => ['required', 'exists:kategori_produk,kategori_produk_id'],
            'nama_produk'        => ['required', 'string', 'max:100'],
            'deskripsi'          => ['nullable', 'string'],
            'harga_beli'         => ['required', 'numeric', 'min:0'],
            'harga_jual'         => ['required', 'numeric', 'min:0'],
            'stok'               => ['required', 'integer', 'min:0'],
            'satuan'             => ['required', 'string', 'max:20'],
            'status'             => ['required', 'in:Aktif,Tidak Aktif'],
        ]);

        // Explicitly exclude kode_produk to guarantee code immutability
        $produk->update($validated);
        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->transaksi()->count() > 0) {
            return back()->with('error', 'Produk tidak dapat dihapus karena memiliki transaksi terkait.');
        }
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function generateKode(): string
    {
        $last = Produk::orderBy('produk_id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_produk, -5)) + 1 : 1;
        return 'PRD-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
