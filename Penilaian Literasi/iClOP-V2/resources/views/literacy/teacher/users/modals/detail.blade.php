<!-- Modal Detail User -->
<div class="modal fade" id="detailUserModal{{ $user->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail User</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <div class="form-control bg-light">{{ $user->name }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <div class="form-control bg-light">{{ $user->email }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role:</label>
                    <div class="form-control bg-light">{{ $user->role == 'teacher' ? 'Teacher' : 'Student' }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>