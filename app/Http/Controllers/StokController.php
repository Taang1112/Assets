<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Karyawan;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $supplierId = $request->get('supplier_id');
        $tanggal = $request->get('tanggal');

        $stok = Stok::with(['produk', 'supplier', 'karyawan'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('produk', function ($p) use ($search) {
                    $p->where('nama_produk', 'like', "%{$search}%")
                      ->orWhere('kode_produk', 'like', "%{$search}%");
                });
            })
            ->when($supplierId, function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            })
            ->when($tanggal, function ($q) use ($tanggal) {
                $q->whereDate('tanggal_masuk', $tanggal);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('stok.index', compact('stok', 'search', 'supplierId', 'tanggal', 'suppliers'));
    }

    public function create()
    {
        $produks = Produk::where('status', 'Aktif')->orderBy('nama_produk')->get();
        $suppliers = Supplier::where('status', 'Aktif')->orderBy('nama_supplier')->get();
        
        // Asumsi Karyawan juga memiliki status (bisa di-check di migrasi Karyawan jika ada, 
        // tapi di migrasi Karyawan tadi tidak ada field 'status'. Mari kita check).
        // Jika tidak ada status aktif, maka ambil semua karyawan.
        $karyawans = Karyawan::orderBy('nama_lengkap')->get();

        return view('stok.create', compact('produks', 'suppliers', 'karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id'     => ['required', 'exists:produk,produk_id'],
            'supplier_id'   => ['required', 'exists:supplier,supplier_id'],
            'karyawan_id'   => ['required', 'exists:karyawan,karyawan_id'],
            'tanggal_masuk' => ['required', 'date'],
            'stok_masuk'    => ['required', 'integer', 'min:1'],
            'harga_beli'    => ['required', 'numeric', 'min:0'],
            'harga_jual'    => ['required', 'numeric', 'min:0'],
            'keterangan'    => ['nullable', 'string'],
        ], [
            'produk_id.required'     => 'Produk wajib dipilih.',
            'produk_id.exists'       => 'Produk tidak valid.',
            'supplier_id.required'   => 'Supplier wajib dipilih.',
            'supplier_id.exists'     => 'Supplier tidak valid.',
            'karyawan_id.required'   => 'Karyawan/petugas wajib dipilih.',
            'karyawan_id.exists'     => 'Karyawan tidak valid.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'stok_masuk.required'    => 'Stok masuk wajib diisi.',
            'stok_masuk.integer'     => 'Stok masuk harus berupa angka.',
            'stok_masuk.min'         => 'Stok masuk minimal 1.',
            'harga_beli.required'    => 'Harga beli wajib diisi.',
            'harga_beli.numeric'     => 'Harga beli harus berupa angka.',
            'harga_beli.min'         => 'Harga beli minimal 0.',
            'harga_jual.required'    => 'Harga jual batch wajib diisi.',
            'harga_jual.numeric'     => 'Harga jual batch harus berupa angka.',
            'harga_jual.min'         => 'Harga jual batch minimal 0.',
        ]);

        // Verifikasi status Supplier
        $supplier = Supplier::findOrFail($request->supplier_id);
        if ($supplier->status !== 'Aktif') {
            return back()->withInput()->with('error', 'Supplier yang dipilih tidak aktif.');
        }

        // Verifikasi status Produk
        $produk = Produk::findOrFail($request->produk_id);
        if ($produk->status !== 'Aktif') {
            return back()->withInput()->with('error', 'Produk yang dipilih tidak aktif.');
        }

        try {
            DB::transaction(function () use ($request, $produk) {
                // 1. Simpan ke riwayat stok sebagai batch baru
                Stok::create([
                    'produk_id'     => $request->produk_id,
                    'supplier_id'   => $request->supplier_id,
                    'karyawan_id'   => $request->karyawan_id,
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'stok_lama'     => $produk->stok,
                    'stok_masuk'    => $request->stok_masuk,
                    'stok_tersisa'  => $request->stok_masuk, // Aturan: stok_tersisa = stok_masuk
                    'harga_lama'    => $produk->harga_beli,
                    'harga_beli'    => $request->harga_beli,
                    'harga_jual'    => $request->harga_jual, // Harga jual khusus batch
                    'keterangan'    => $request->keterangan,
                ]);

                // 2. Update stok total produk
                $produk->increment('stok', $request->stok_masuk);
                
                // Update legacy harga beli terakhir sebagai referensi
                $produk->update(['harga_beli' => $request->harga_beli]);
            });

            return redirect()->route('stok.index')
                ->with('success', 'Stok produk berhasil ditambah sebagai batch baru.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
