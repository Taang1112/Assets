<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $pelanggan = Pelanggan::query()
            ->when($search, function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('kode_pelanggan', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pelanggan.index', compact('pelanggan', 'search', 'status'));
    }

    public function create()
    {
        $kode = $this->generateKode();
        return view('pelanggan.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_pelanggan' => ['required', 'string', 'max:20', 'unique:pelanggan,kode_pelanggan'],
            'nama_pelanggan' => ['required', 'string', 'max:100'],
            'email'          => ['nullable', 'email', 'max:100'],
            'no_telepon'     => ['required', 'string', 'max:20'],
            'alamat'         => ['nullable', 'string'],
            'tanggal_daftar' => ['required', 'date'],
            'status'         => ['required', 'in:Aktif,Tidak Aktif'],
        ], [
            'kode_pelanggan.required'  => 'Kode pelanggan wajib diisi.',
            'kode_pelanggan.unique'    => 'Kode pelanggan sudah digunakan.',
            'nama_pelanggan.required'  => 'Nama pelanggan wajib diisi.',
            'no_telepon.required'      => 'Nomor telepon wajib diisi.',
            'tanggal_daftar.required'  => 'Tanggal daftar wajib diisi.',
        ]);

        Pelanggan::create($request->all());

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Pelanggan $pelanggan)
    {
        return view('pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'kode_pelanggan' => ['required', 'string', 'max:20', 'unique:pelanggan,kode_pelanggan,' . $pelanggan->pelanggan_id . ',pelanggan_id'],
            'nama_pelanggan' => ['required', 'string', 'max:100'],
            'email'          => ['nullable', 'email', 'max:100'],
            'no_telepon'     => ['required', 'string', 'max:20'],
            'alamat'         => ['nullable', 'string'],
            'tanggal_daftar' => ['required', 'date'],
            'status'         => ['required', 'in:Aktif,Tidak Aktif'],
        ], [
            'kode_pelanggan.required'  => 'Kode pelanggan wajib diisi.',
            'kode_pelanggan.unique'    => 'Kode pelanggan sudah digunakan.',
            'nama_pelanggan.required'  => 'Nama pelanggan wajib diisi.',
            'no_telepon.required'      => 'Nomor telepon wajib diisi.',
            'tanggal_daftar.required'  => 'Tanggal daftar wajib diisi.',
        ]);

        $pelanggan->update($request->all());

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    private function generateKode(): string
    {
        $last = Pelanggan::orderBy('pelanggan_id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_pelanggan, -5)) + 1 : 1;
        return 'PLG-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
