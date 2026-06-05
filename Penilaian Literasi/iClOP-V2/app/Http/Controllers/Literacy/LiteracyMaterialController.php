<?php

namespace App\Http\Controllers\Literacy;

use App\Http\Controllers\Controller;
use App\Models\Literacy\LiteracyMaterial;
use App\Models\Literacy\LiteracyQuestion;
use App\Models\Literacy\LiteracyStoryText;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LiteracyMaterialController extends Controller
{
    public function materials()
    {
        $materials = LiteracyMaterial::orderBy('created_at', 'desc')->get();
        $users = User::all();
        $questions = LiteracyQuestion::all();

        return view('literacy.teacher.materials.index', [
            'materials' => $materials,
            'users' => $users,
            'questions' => $questions,
        ]);
    }

    public function show($id)
    {
        $material = LiteracyMaterial::findOrFail($id);
        return view('literacy.teacher.materials.index', compact('material'));
    }

    public function create()
    {
        return view('literacy.teacher.materials.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'story_text' => 'nullable|string',
            'description' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:1048576'
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $originalName = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('public/literacy_materials', $originalName);
            $filePath = str_replace('public/', '', $path); // Simpan path relatif
        }

        // Buat materi utama
        $material = LiteracyMaterial::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'user_id' => auth()->id()
        ]);

        // Simpan story_text jika ada
        if (!empty(trim($request->story_text))) {
            LiteracyStoryText::create([
                'material_id' => $material->id,
                'story_text' => $request->story_text
            ]);
        }

        return redirect()->route('literacy_teacher_materials');
    }

    public function edit($id)
    {
        $material = LiteracyMaterial::findOrFail($id);
        return view('literacy.teacher.materials.index', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,txt|max:1048576',
            'story_texts' => 'array',
            'story_texts.*' => 'nullable|string',
            'story_text_ids' => 'array',
            'story_text_ids.*' => 'nullable|integer',
        ]);

        $material = LiteracyMaterial::findOrFail($id);
        $filePath = $material->file_path;

        if ($request->hasFile('file')) {
            if ($filePath && Storage::exists('public/' . $filePath)) {
                Storage::delete('public/' . $filePath);
            }

            $originalName = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('public/literacy_materials', $originalName);
            $filePath = str_replace('public/', '', $path);
        }

        $material->update([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
        ]);

        // Update, Tambah, dan Hapus Story Texts
        $existingIds = $material->storyTexts->pluck('id')->toArray(); // semua ID lama
        $sentIds = $request->input('story_text_ids', []);
        $texts = $request->input('story_texts', []);

        foreach ($texts as $index => $text) {
            $id = $sentIds[$index] ?? null;
            $cleaned = trim($text);

            if ($id && in_array($id, $existingIds)) {
                // Update
                LiteracyStoryText::where('id', $id)->update([
                    'story_text' => $cleaned
                ]);
            } elseif (!$id && !empty($cleaned)) {
                // Tambah baru
                LiteracyStoryText::create([
                    'material_id' => $material->id,
                    'story_text' => $cleaned
                ]);
            }
        }

        // Hapus yang tidak dikirim lagi
        $deletedIds = array_diff($existingIds, array_filter($sentIds));
        LiteracyStoryText::destroy($deletedIds);

        return redirect()->route('literacy_teacher_materials');
    }

    public function destroy($id)
    {
        $material = LiteracyMaterial::findOrFail($id);

        if ($material->file_path && Storage::exists('public/' . $material->file_path)) {
            Storage::delete('public/' . $material->file_path);
        }

        $material->delete();
        return redirect()->route('literacy_teacher_materials');
    }

    public function show_materials($id)
    {
        $material = LiteracyMaterial::findOrFail($id);
        $path = storage_path('app/public/' . $material->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($path);
    }

    public function getStoryTexts($id)
    {
        $material = LiteracyMaterial::with('storyTexts')->find($id);

        if (!$material) {
            return response()->json([], 404); // tanggapi dengan error jika tidak ditemukan
        }

        return response()->json($material->storyTexts);
    }
}