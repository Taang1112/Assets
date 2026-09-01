<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jenisKelamin = $request->get('jenis_kelamin');

        $karyawan = Karyawan::query()
            ->when($search, fn($q) => $q
                ->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->when($jenisKelamin, fn($q) => $q->where('jenis_kelamin', $jenisKelamin))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('karyawan.index', compact('karyawan', 'search', 'jenisKelamin'));
    }

    public function create()
    {
        return view('karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'                 => ['required', 'string', 'max:20', 'unique:karyawan,nik'],
            'nama_lengkap'        => ['required', 'string', 'max:100'],
            'tempat_lahir'        => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'       => ['nullable', 'date'],
            'jenis_kelamin'       => ['nullable', 'in:Laki-laki,Perempuan'],
            'agama'               => ['nullable', 'string', 'max:50'],
            'email'               => ['nullable', 'email', 'unique:karyawan,email'],
            'no_telepon'          => ['nullable', 'string', 'max:20'],
            'alamat'              => ['nullable', 'string'],
            'status_pernikahan'   => ['nullable', 'string', 'max:50'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
        ]);

        Karyawan::create($request->all());

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function show(Karyawan $karyawan)
    {
        return view('karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nik'                 => ['required', 'string', 'max:20', 'unique:karyawan,nik,' . $karyawan->karyawan_id . ',karyawan_id'],
            'nama_lengkap'        => ['required', 'string', 'max:100'],
            'tempat_lahir'        => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'       => ['nullable', 'date'],
            'jenis_kelamin'       => ['nullable', 'in:Laki-laki,Perempuan'],
            'agama'               => ['nullable', 'string', 'max:50'],
            'email'               => ['nullable', 'email', 'unique:karyawan,email,' . $karyawan->karyawan_id . ',karyawan_id'],
            'no_telepon'          => ['nullable', 'string', 'max:20'],
            'alamat'              => ['nullable', 'string'],
            'status_pernikahan'   => ['nullable', 'string', 'max:50'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
        ]);

        $karyawan->update($request->all());

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        if ($karyawan->transaksi()->count() > 0) {
            return back()->with('error', 'Karyawan tidak dapat dihapus karena memiliki transaksi terkait.');
        }
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
