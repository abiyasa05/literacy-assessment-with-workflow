<!-- Edit Question Modal -->
<div class="modal fade" id="modalEditQuestion{{ $question->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Question</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('literacy_questions_update', $question->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Material -->
                    <div class="mb-3">
                        <label class="form-label">Material:</label>
                        <div class="form-control bg-light">
                            {{ $question->material->title ?? '-' }}
                        </div>
                    </div>

                    <!-- Story Text -->
                    <div class="mb-3">
                        <label class="form-label">Reading Text:</label>
                        <textarea class="form-control bg-light" readonly rows="5" style="resize: none">
                            {{ $question->first_story_text->story_text ?? '-' }}
                        </textarea>
                    </div>

                    <!-- Question -->
                    <div class="mb-3">
                        <label class="form-label">Question</label>
                        <textarea name="question_text" class="form-control" required>{{ $question->question_text }}</textarea>
                    </div>

                    <!-- Question Type -->
                    <div class="mb-3">
                        <label class="form-label">Question Type</label>
                        <select name="type" class="form-control edit_questionType" required>
                            <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>
                                Multiple Choice
                            </option>
                            <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}>
                                Short Answer
                            </option>
                        </select>
                    </div>

                    <!-- Opsi Multiple Choice -->
                    <div class="mb-3 edit_multipleChoiceOptions"
                        style="display: {{ $question->type == 'multiple_choice' ? 'block' : 'none' }};">
                        <label class="form-label">Answer Options</label>
                        <div class="edit_answerOptions">
                            @foreach ($question->options as $index => $option)
                                <div class="option-group mb-2 d-flex align-items-center gap-2">
                                    <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option->id }}">

                                    <input type="text" name="options[{{ $index }}][option_text]" class="form-control"
                                        style="width: 40%;" value="{{ $option->option_text }}" required>

                                    <input type="number" name="options[{{ $index }}][score]" class="form-control"
                                        style="width: 15%;" value="{{ $option->score }}" min="0" max="100" required>

                                    <label class="d-flex align-items-center ms-3">
                                        <input type="checkbox" name="options[{{ $index }}][is_correct]" value="1"
                                            class="me-2" {{ $option->is_correct ? 'checked' : '' }}>
                                        <span>Correct</span>
                                    </label>

                                    <button type="button" class="btn btn-danger btn-sm remove-option">X</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-success btn-sm addOption">+ Add Option</button>
                    </div>

                    <!-- Short Answer -->
                    <div id="edit_essayScoreField" class="mb-3"
                        style="display: {{ $question->type == 'essay' ? 'block' : 'none' }};">
                        <label class="form-label">Score for Short Answer</label>
                        <input type="number" name="essay_score" class="form-control" min="0" max="100"
                            value="{{ $question->essay_score ?? '' }}" placeholder="Enter score">
                    </div>

                    <div id="edit_essayReferenceAnswerField" class="mb-3"
                        style="display: {{ $question->type == 'essay' ? 'block' : 'none' }};">
                        <label class="form-label">Reference Answer</label>
                        <textarea name="essay_answer" class="form-control"
                            placeholder="Enter reference answer">{{ $question->essay_answer ?? '' }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>