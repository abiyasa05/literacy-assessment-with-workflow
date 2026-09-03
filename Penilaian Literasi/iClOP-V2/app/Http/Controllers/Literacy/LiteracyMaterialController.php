<?php

namespace App\Http\Controllers\Literacy;

use App\Http\Controllers\Controller;
use App\Models\Literacy\LiteracyMaterial;
use App\Models\Literacy\LiteracyQuestion;
use App\Models\Literacy\LiteracyStoryText;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LiteracyMaterialController extends Controller
{
    public function materials()
    {
        $materials = LiteracyMaterial::with('storyTexts')
            ->orderBy('created_at', 'desc')
            ->get();

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
            'description' => 'nullable|string',

            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:1048576',

            'story_texts' => 'nullable|array',
            'story_texts.*' => 'nullable|string',
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {

            $originalName = $request->file('file')->getClientOriginalName();

            $path = $request->file('file')->storeAs(
                'public/literacy_materials',
                $originalName
            );

            $filePath = str_replace('public/', '', $path);
        }

        // Buat materi utama
        $material = LiteracyMaterial::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'user_id' => auth()->id()
        ]);


        // Simpan semua Reading Text
        $storyTexts = $request->input('story_texts', []);

        foreach ($storyTexts as $storyText) {

            $cleaned = trim($storyText);

            if (!empty($cleaned)) {

                LiteracyStoryText::create([
                    'material_id' => $material->id,
                    'story_text' => $cleaned
                ]);

            }
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
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:1048576',

            'story_texts' => 'nullable|array',
            'story_texts.*' => 'nullable|string',

            'story_text_ids' => 'nullable|array',
            'story_text_ids.*' => 'nullable|integer',
        ]);

        $material = LiteracyMaterial::findOrFail($id);

        $filePath = $material->file_path;

        /*
        |--------------------------------------------------------------------------
        | Update File
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            if ($filePath && Storage::exists('public/' . $filePath)) {
                Storage::delete('public/' . $filePath);
            }

            $originalName = $request->file('file')->getClientOriginalName();

            $path = $request->file('file')->storeAs(
                'public/literacy_materials',
                $originalName
            );

            $filePath = str_replace('public/', '', $path);
        }


        /*
        |--------------------------------------------------------------------------
        | Update Material
        |--------------------------------------------------------------------------
        */

        $material->update([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update / Create Story Text
        |--------------------------------------------------------------------------
        */

        $texts = $request->input('story_texts', []);
        $ids = $request->input('story_text_ids', []);

        DB::transaction(function () use ($material, $texts, $ids) {
            $existingStories = $material->storyTexts()->get()->keyBy('id');
            $submittedIds = [];

            foreach ($texts as $index => $text) {
                $storyId = $ids[$index] ?? null;
                $storyText = trim((string) $text);

                if ($storyId && $existingStories->has($storyId)) {
                    if ($storyText !== '') {
                        $existingStories->get($storyId)->update([
                            'story_text' => $storyText,
                        ]);
                        $submittedIds[] = (int) $storyId;
                    }

                    continue;
                }

                if (!$storyId && $storyText !== '') {
                    $newStory = $material->storyTexts()->create([
                        'story_text' => $storyText,
                    ]);
                    $submittedIds[] = $newStory->id;
                }
            }

            $idsToDelete = $existingStories->keys()
                ->diff($submittedIds)
                ->all();

            if ($idsToDelete) {
                $material->storyTexts()->whereIn('id', $idsToDelete)->delete();
            }
        });


        return redirect()
            ->route('literacy_teacher_materials');
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