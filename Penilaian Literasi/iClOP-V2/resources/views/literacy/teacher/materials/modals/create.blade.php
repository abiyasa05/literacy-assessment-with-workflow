<div class="modal fade" id="modalTambahMaterial" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Add Material</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('literacy_materials_store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reading Text</label>

                        <div id="createStoryTextContainer">

                            <div class="d-flex mb-2 align-items-start">
                                <textarea
                                    name="story_texts[]"
                                    class="form-control me-2"
                                    rows="3"
                                    placeholder="Enter reading text"></textarea>

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm remove-story-text">
                                    Delete
                                </button>
                            </div>

                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-success mt-2"
                            onclick="addCreateStoryText()">
                            + Add Reading Text
                        </button>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">
                            File
                        </label>

                        <input
                            type="file"
                            class="form-control"
                            name="file">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>