<?php

namespace App\Http\Controllers;

use App\Models\KategoriInventaris;
use Illuminate\Http\Request;

class KategoriInventarisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $kategori = KategoriInventaris::withCount('inventaris')
            ->when($search, fn($q) => $q
                ->where('nama_kategori', 'like', "%{$search}%")
                ->orWhere('kode_kategori', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kategori-inventaris.index', compact('kategori', 'search'));
    }

    public function create()
    {
        $kode = $this->generateKode();
        return view('kategori-inventaris.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori' => ['required', 'string', 'max:20', 'unique:kategori_inventaris,kode_kategori'],
            'nama_kategori' => ['required', 'string', 'max:100'],
            'deskripsi'     => ['nullable', 'string'],
            'status'        => ['required', 'in:Aktif,Tidak Aktif'],
        ]);

        KategoriInventaris::create($request->all());
        return redirect()->route('kategori-inventaris.index')->with('success', 'Kategori inventaris berhasil ditambahkan.');
    }

    public function show(KategoriInventaris $kategoriInventaris)
    {
        $kategoriInventaris->loadCount('inventaris');
        return view('kategori-inventaris.show', compact('kategoriInventaris'));
    }

    public function edit(KategoriInventaris $kategoriInventaris)
    {
        return view('kategori-inventaris.edit', compact('kategoriInventaris'));
    }

    public function update(Request $request, KategoriInventaris $kategoriInventaris)
    {
        $request->validate([
            'kode_kategori' => ['required', 'string', 'max:20', 'unique:kategori_inventaris,kode_kategori,' . $kategoriInventaris->kategori_inventaris_id . ',kategori_inventaris_id'],
            'nama_kategori' => ['required', 'string', 'max:100'],
            'deskripsi'     => ['nullable', 'string'],
            'status'        => ['required', 'in:Aktif,Tidak Aktif'],
        ]);

        $kategoriInventaris->update($request->all());
        return redirect()->route('kategori-inventaris.index')->with('success', 'Kategori inventaris berhasil diperbarui.');
    }

    public function destroy(KategoriInventaris $kategoriInventaris)
    {
        if ($kategoriInventaris->inventaris()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki inventaris terkait.');
        }
        $kategoriInventaris->delete();
        return redirect()->route('kategori-inventaris.index')->with('success', 'Kategori inventaris berhasil dihapus.');
    }

    private function generateKode(): string
    {
        $last = KategoriInventaris::orderBy('kategori_inventaris_id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_kategori, -4)) + 1 : 1;
        return 'KTI-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
