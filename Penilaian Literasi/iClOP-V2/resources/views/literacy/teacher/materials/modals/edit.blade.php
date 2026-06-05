<div class="modal fade" id="editMaterialModal-{{ $material->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Material</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('literacy_materials_update', $material->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <textarea name="title" class="form-control overflow-auto" rows="2">{{ $material->title }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="story_texts" class="form-label">Reading Text</label>
                        <div id="editStoryTextContainer-{{ $material->id }}">
                            @foreach ($material->storyTexts as $story)
                                <div class="d-flex mb-2 align-items-start">
                                    <input type="hidden" name="story_text_ids[]" value="{{ $story->id }}">
                                    <textarea name="story_texts[]" class="form-control me-2" rows="2">{{ $story->story_text }}</textarea>
                                    <button type="button" class="btn btn-danger btn-sm remove-story-text">Delete</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-success mt-2" onclick="addStoryText({{ $material->id }})">Add Reading Text</button>
                    </div>                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control">{{ $material->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Saat Ini</label>
                        <div class="form-control bg-light">
                            {{ $material->file_path ? basename($material->file_path) : '-' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">Ganti File</label>
                        <input type="file" name="file" class="form-control">
                    </div>

                    <!-- Input Hidden untuk Path File Lama -->
                    <input type="hidden" name="old_file_path" value="{{ $material->file }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>