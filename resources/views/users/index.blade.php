@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-lock-fill text-primary me-2"></i>Daftar User Sistem</h5>
            <small class="text-muted">Kelola akun pengguna dan wewenang hak akses (Admin & Petugas)</small>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah User Baru
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;" class="text-center">#</th>
                        <th>NAMA USER</th>
                        <th>EMAIL</th>
                        <th>ROLE HAK AKSES</th>
                        <th>TANGGAL TERDAFTAR</th>
                        <th style="width: 120px;" class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                        <tr>
                            <td class="text-center fw-semibold text-secondary">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light border text-primary d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;font-size:0.78rem;">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                        @if(auth()->id() === $u->id)
                                            <span class="badge bg-soft-primary text-primary" style="font-size:0.65rem;">(Akun Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold text-secondary">{{ $u->email }}</td>
                            <td>
                                @if($u->role === 'admin')
                                    <span class="badge bg-primary text-white px-2.5 py-1" style="border-radius: 6px; font-size:0.75rem;">
                                        <i class="bi bi-shield-check me-1"></i> Admin
                                    </span>
                                @else
                                    <span class="badge bg-info text-dark px-2.5 py-1" style="border-radius: 6px; font-size:0.75rem;">
                                        <i class="bi bi-person me-1"></i> Petugas
                                    </span>
                                @endif
                            </td>
                            <td class="text-secondary">{{ $u->created_at ? $u->created_at->format('d M Y H:i') : '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <a href="{{ route('users.edit', $u->id) }}" class="btn btn-icon btn-sm btn-light border text-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(auth()->id() !== $u->id)
                                        <button type="button" class="btn btn-icon btn-sm btn-light border text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $u->id }}"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Modal Hapus -->
                                <div class="modal fade" id="deleteModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-body text-center p-4">
                                                <div class="empty-icon bg-danger-subtle text-danger mb-3 mx-auto">
                                                    <i class="bi bi-exclamation-triangle fs-3"></i>
                                                </div>
                                                <h6 class="fw-bold mb-1">Hapus User?</h6>
                                                <p class="text-muted small mb-4">User <strong>{{ $u->name }}</strong> ({{ $u->email }}) akan dihapus permanen.</p>

                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-people"></i></div>
                                    <h6 class="fw-bold">Belum Ada Data User</h6>
                                    <p class="text-muted small mb-0">Klik tombol "Tambah User Baru" untuk menambahkan user ke sistem.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
