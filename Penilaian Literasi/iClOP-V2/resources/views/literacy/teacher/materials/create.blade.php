<form action="{{ route('literacy.teacher.materials.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Title:</label>
    <input type="text" name="title" required>

    <label>Description:</label>
    <textarea name="description"></textarea>

    <label>Upload File:</label>
    <input type="file" name="file" accept=".pdf,.doc,.docx,.txt">

    <button type="submit">Submit</button>
</form>