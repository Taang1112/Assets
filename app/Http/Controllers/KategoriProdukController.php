<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $kategori = KategoriProduk::withCount('produk')
            ->when($search, fn($q) => $q
                ->where('nama_kategori', 'like', "%{$search}%")
                ->orWhere('kode_kategori', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kategori-produk.index', compact('kategori', 'search'));
    }

    public function create()
    {
        $kode = $this->generateKode();
        return view('kategori-produk.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
            'deskripsi'     => ['nullable', 'string'],
            'status'        => ['required', 'in:Aktif,Tidak Aktif'],
        ]);

        $validated['kode_kategori'] = $this->generateKode();

        KategoriProduk::create($validated);
        return redirect()->route('kategori-produk.index')->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    public function show(KategoriProduk $kategoriProduk)
    {
        $kategoriProduk->loadCount('produk');
        return view('kategori-produk.show', compact('kategoriProduk'));
    }

    public function edit(KategoriProduk $kategoriProduk)
    {
        return view('kategori-produk.edit', compact('kategoriProduk'));
    }

    public function update(Request $request, KategoriProduk $kategoriProduk)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
            'deskripsi'     => ['nullable', 'string'],
            'status'        => ['required', 'in:Aktif,Tidak Aktif'],
        ]);

        // Explicitly exclude kode_kategori to guarantee code immutability
        $kategoriProduk->update($validated);
        return redirect()->route('kategori-produk.index')->with('success', 'Kategori produk berhasil diperbarui.');
    }

    public function destroy(KategoriProduk $kategoriProduk)
    {
        if ($kategoriProduk->produk()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait.');
        }
        $kategoriProduk->delete();
        return redirect()->route('kategori-produk.index')->with('success', 'Kategori produk berhasil dihapus.');
    }

    private function generateKode(): string
    {
        $last = KategoriProduk::orderBy('kategori_produk_id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_kategori, -4)) + 1 : 1;
        return 'KTP-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
