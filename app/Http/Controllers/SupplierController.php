<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $supplier = Supplier::query()
            ->when($search, function ($q) use ($search) {
                $q->where('nama_supplier', 'like', "%{$search}%")
                  ->orWhere('kode_supplier', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('supplier.index', compact('supplier', 'search', 'status'));
    }

    public function create()
    {
        $kode = $this->generateKode();
        return view('supplier.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier'   => ['required', 'string', 'max:100'],
            'nama_perusahaan' => ['nullable', 'string', 'max:100'],
            'email'           => ['nullable', 'email', 'max:100', 'unique:supplier,email'],
            'no_telepon'      => ['nullable', 'string', 'max:30'],
            'alamat'          => ['nullable', 'string'],
            'status'          => ['required', 'in:Aktif,Tidak Aktif'],
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah terdaftar.',
            'status.required'        => 'Status wajib dipilih.',
        ]);

        $validated['kode_supplier'] = $this->generateKode();

        Supplier::create($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('stok.produk');
        return view('supplier.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama_supplier'   => ['required', 'string', 'max:100'],
            'nama_perusahaan' => ['nullable', 'string', 'max:100'],
            'email'           => ['nullable', 'email', 'max:100', 'unique:supplier,email,' . $supplier->supplier_id . ',supplier_id'],
            'no_telepon'      => ['nullable', 'string', 'max:30'],
            'alamat'          => ['nullable', 'string'],
            'status'          => ['required', 'in:Aktif,Tidak Aktif'],
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah terdaftar.',
            'status.required'        => 'Status wajib dipilih.',
        ]);

        // Explicitly exclude kode_supplier to guarantee code immutability
        $supplier->update($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->stok()->count() > 0) {
            return back()->with('error', 'Supplier tidak dapat dihapus karena memiliki riwayat stok / restock terkait.');
        }

        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }

    private function generateKode(): string
    {
        $last = Supplier::orderBy('supplier_id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_supplier, -5)) + 1 : 1;
        return 'SPL-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
