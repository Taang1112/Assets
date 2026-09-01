<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:52px;height:52px;background:#fee2e2;">
                    <i class="bi bi-trash3 text-danger fs-5"></i>
                </div>
                <h6 class="fw-bold mb-1">Konfirmasi Hapus</h6>
                <p class="text-muted mb-0" style="font-size:.82rem;">
                    <strong id="hapusNama"></strong> akan dihapus permanen.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0 pb-4">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">Batal</button>
                <form id="formHapus" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-4">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
