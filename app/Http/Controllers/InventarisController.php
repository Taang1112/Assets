<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\KategoriInventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->get('search');
        $kondisi    = $request->get('kondisi');
        $status     = $request->get('status');
        $kategoriId = $request->get('kategori_inventaris_id');

        $inventaris = Inventaris::with('kategori')
            ->when($search, fn($q) => $q
                ->where('nama_inventaris', 'like', "%{$search}%")
                ->orWhere('kode_inventaris', 'like', "%{$search}%"))
            ->when($kondisi, fn($q) => $q->where('kondisi', $kondisi))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($kategoriId, fn($q) => $q->where('kategori_inventaris_id', $kategoriId))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kategoris = KategoriInventaris::where('status', 'Aktif')->orderBy('nama_kategori')->get();

        return view('inventaris.index', compact('inventaris', 'search', 'kondisi', 'status', 'kategoriId', 'kategoris'));
    }

    public function create()
    {
        $kode = $this->generateKode();
        $kategoris = KategoriInventaris::where('status', 'Aktif')->orderBy('nama_kategori')->get();
        return view('inventaris.create', compact('kode', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_inventaris_id' => ['required', 'exists:kategori_inventaris,kategori_inventaris_id'],
            'kode_inventaris'        => ['required', 'string', 'max:20', 'unique:inventaris,kode_inventaris'],
            'nama_inventaris'        => ['required', 'string', 'max:100'],
            'jumlah'                 => ['required', 'integer', 'min:1'],
            'kondisi'                => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'lokasi'                 => ['required', 'string', 'max:100'],
            'tanggal_masuk'          => ['required', 'date'],
            'harga_perolehan'        => ['required', 'numeric', 'min:0'],
            'status'                 => ['required', 'in:Tersedia,Dipakai,Dipinjam,Dihapus'],
        ]);

        Inventaris::create($request->all());
        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil ditambahkan.');
    }

    public function show(Inventaris $inventaris)
    {
        $inventaris->load('kategori');
        return view('inventaris.show', compact('inventaris'));
    }

    public function edit(Inventaris $inventaris)
    {
        $kategoris = KategoriInventaris::where('status', 'Aktif')->orderBy('nama_kategori')->get();
        return view('inventaris.edit', compact('inventaris', 'kategoris'));
    }

    public function update(Request $request, Inventaris $inventaris)
    {
        $request->validate([
            'kategori_inventaris_id' => ['required', 'exists:kategori_inventaris,kategori_inventaris_id'],
            'kode_inventaris'        => ['required', 'string', 'max:20', 'unique:inventaris,kode_inventaris,' . $inventaris->inventaris_id . ',inventaris_id'],
            'nama_inventaris'        => ['required', 'string', 'max:100'],
            'jumlah'                 => ['required', 'integer', 'min:1'],
            'kondisi'                => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'lokasi'                 => ['required', 'string', 'max:100'],
            'tanggal_masuk'          => ['required', 'date'],
            'harga_perolehan'        => ['required', 'numeric', 'min:0'],
            'status'                 => ['required', 'in:Tersedia,Dipakai,Dipinjam,Dihapus'],
        ]);

        $inventaris->update($request->all());
        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil diperbarui.');
    }

    public function destroy(Inventaris $inventaris)
    {
        $inventaris->delete();
        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil dihapus.');
    }

    private function generateKode(): string
    {
        $last = Inventaris::orderBy('inventaris_id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_inventaris, -5)) + 1 : 1;
        return 'INV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
