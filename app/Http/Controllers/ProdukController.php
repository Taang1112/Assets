<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $kategori_id = $request->get('kategori_produk_id');
        $status = $request->get('status');

        $query = Produk::with('kategori');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kode_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategori_id) {
            $query->where('kategori_produk_id', $kategori_id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $produk = $query->latest('produk_id')
            ->paginate(10)
            ->withQueryString();

        // Summary statistics
        $totalProduk = Produk::count();
        $totalStok = Produk::sum('stok');
        $produkAktif = Produk::where('status', 'Aktif')->count();
        $nilaiAset = Produk::select(DB::raw('SUM(harga_beli * stok) as total_nilai'))->value('total_nilai') ?? 0;

        $kategoriList = KategoriProduk::orderBy('nama_kategori')->get();

        return view('produk.index', compact(
            'produk',
            'search',
            'kategori_id',
            'status',
            'totalProduk',
            'totalStok',
            'produkAktif',
            'nilaiAset',
            'kategoriList'
        ));
    }

    public function create()
    {
        $kode = $this->generateKode();
        $kategoriList = KategoriProduk::where('status', 'Aktif')->orderBy('nama_kategori')->get();
        return view('produk.create', compact('kode', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_produk_id' => ['required', 'exists:kategori_produk,kategori_produk_id'],
            'kode_produk'        => ['required', 'string', 'max:20', 'unique:produk,kode_produk'],
            'nama_produk'        => ['required', 'string', 'max:100'],
            'deskripsi'          => ['nullable', 'string'],
            'harga_beli'         => ['required', 'numeric', 'min:0'],
            'harga_jual'         => ['required', 'numeric', 'min:0'],
            'stok'               => ['required', 'integer', 'min:0'],
            'satuan'             => ['required', 'string', 'max:20'],
            'status'             => ['required', 'in:Aktif,Tidak Aktif'],
        ], [
            'kategori_produk_id.required' => 'Kategori produk wajib dipilih.',
            'kategori_produk_id.exists'   => 'Kategori produk tidak valid.',
            'kode_produk.required'        => 'Kode produk wajib diisi.',
            'kode_produk.unique'          => 'Kode produk sudah digunakan.',
            'nama_produk.required'        => 'Nama produk wajib diisi.',
            'harga_beli.required'         => 'Harga beli wajib diisi.',
            'harga_jual.required'         => 'Harga jual wajib diisi.',
            'stok.required'               => 'Stok wajib diisi.',
            'satuan.required'             => 'Satuan wajib diisi.',
            'status.required'             => 'Status produk wajib dipilih.',
        ]);

        Produk::create($request->all());

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        $produk->load('kategori');
        return view('produk.show', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        $kategoriList = KategoriProduk::orderBy('nama_kategori')->get();
        return view('produk.edit', compact('produk', 'kategoriList'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kategori_produk_id' => ['required', 'exists:kategori_produk,kategori_produk_id'],
            'kode_produk'        => ['required', 'string', 'max:20', 'unique:produk,kode_produk,' . $produk->produk_id . ',produk_id'],
            'nama_produk'        => ['required', 'string', 'max:100'],
            'deskripsi'          => ['nullable', 'string'],
            'harga_beli'         => ['required', 'numeric', 'min:0'],
            'harga_jual'         => ['required', 'numeric', 'min:0'],
            'stok'               => ['required', 'integer', 'min:0'],
            'satuan'             => ['required', 'string', 'max:20'],
            'status'             => ['required', 'in:Aktif,Tidak Aktif'],
        ], [
            'kategori_produk_id.required' => 'Kategori produk wajib dipilih.',
            'kategori_produk_id.exists'   => 'Kategori produk tidak valid.',
            'kode_produk.required'        => 'Kode produk wajib diisi.',
            'kode_produk.unique'          => 'Kode produk sudah digunakan.',
            'nama_produk.required'        => 'Nama produk wajib diisi.',
            'harga_beli.required'         => 'Harga beli wajib diisi.',
            'harga_jual.required'         => 'Harga jual wajib diisi.',
            'stok.required'               => 'Stok wajib diisi.',
            'satuan.required'             => 'Satuan wajib diisi.',
            'status.required'             => 'Status produk wajib dipilih.',
        ]);

        $produk->update($request->all());

        return redirect()->route('produk.index')
            ->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function generateKode(): string
    {
        $last = Produk::orderBy('produk_id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_produk, -5)) + 1 : 1;
        return 'PRD-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
