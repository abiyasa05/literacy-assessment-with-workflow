<!-- Modal Detail Hasil Generated -->
<div class="modal fade" id="detailGeneratedTextModal{{ $history_generated_text->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Hasil Generated</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Hasil Generate:</label>
                    <textarea class="form-control bg-light" readonly
                        style="height: 150px !important; overflow-y: auto !important; resize: none !important;">{{ $history_generated_text->generate_text }}</textarea>
                    </textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Question Type:</label>
                    <div class="form-control bg-light">
                        {{ $history_generated_text->question_type == 'multiple_choice' ? 'Multiple Choice' : 'Short Answer' }}
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Number of Questions:</label>
                    <div class="form-control bg-light">{{ $history_generated_text->question_count }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>