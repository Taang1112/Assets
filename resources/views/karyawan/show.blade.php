@extends('layouts.app')
@section('title','Detail Karyawan')
@section('page-title','Detail Karyawan')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}" class="text-decoration-none text-primary">Karyawan</a></li><li class="breadcrumb-item active">{{ $karyawan->nik }}</li></ol>
    </nav>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                     style="width:44px;height:44px;background:linear-gradient(135deg,#4f46e5,#06b6d4);font-size:1.1rem;">
                    {{ strtoupper(substr($karyawan->nama_lengkap,0,1)) }}
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">{{ $karyawan->nama_lengkap }}</h6>
                    <small class="text-muted">NIK: {{ $karyawan->nik }}</small>
                </div>
            </div>
            <a href="{{ route('karyawan.edit',$karyawan) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @php
                    $fields = [
                        'Tempat, Tgl Lahir' => ($karyawan->tempat_lahir ?? '—') . ($karyawan->tanggal_lahir ? ', ' . $karyawan->tanggal_lahir->format('d M Y') : ''),
                        'Jenis Kelamin'     => $karyawan->jenis_kelamin ?? '—',
                        'Agama'             => $karyawan->agama ?? '—',
                        'No. Telepon'       => $karyawan->no_telepon ?? '—',
                        'Email'             => $karyawan->email ?? '—',
                        'Alamat'            => $karyawan->alamat ?? '—',
                        'Status Pernikahan' => $karyawan->status_pernikahan ?? '—',
                        'Pendidikan'        => $karyawan->pendidikan_terakhir ?? '—',
                        'Dibuat'            => $karyawan->created_at->format('d M Y, H:i'),
                    ];
                @endphp
                @foreach($fields as $label => $value)
                <div class="col-sm-6">
                    <div class="p-3 rounded-3 bg-light h-100">
                        <div class="text-muted mb-1" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">{{ $label }}</div>
                        <div class="fw-semibold" style="font-size:.875rem;">{{ $value }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus" data-id="{{ $karyawan->karyawan_id }}" data-nama="{{ $karyawan->nama_lengkap }}"><i class="bi bi-trash me-1"></i>Hapus</button>
        </div>
    </div>
</div></div>
@include('partials.modal-hapus')
@endsection
@push('scripts')
<script>
    document.getElementById('modalHapus').addEventListener('show.bs.modal', e => {
        const btn = e.relatedTarget;
        document.getElementById('hapusNama').textContent = btn.dataset.nama;
        document.getElementById('formHapus').action = `/karyawan/${btn.dataset.id}`;
    });
</script>
@endpush
