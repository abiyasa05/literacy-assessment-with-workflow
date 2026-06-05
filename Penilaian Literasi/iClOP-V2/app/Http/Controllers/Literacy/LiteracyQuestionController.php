<?php

namespace App\Http\Controllers\Literacy;

use App\Http\Controllers\Controller;
use App\Models\Literacy\LiteracyQuestion;
use App\Models\Literacy\LiteracyStoryText;
use App\Models\Literacy\LiteracyMaterial;
use App\Models\Literacy\LiteracyOption;
use App\Models\Literacy\LiteracyAssessment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LiteracyQuestionController extends Controller
{
    public function questions()
    {
        $questions = LiteracyQuestion::with(['material', 'storyTexts', 'options'])->orderBy('created_at', 'desc')->get();
            foreach ($questions as $question) {
            $question->first_story_text = $question->storyTexts->first();
        }
        $materials = LiteracyMaterial::all();
        $users = User::all();
        return view('literacy.teacher.questions.index', [
            'questions' => $questions,
            'materials' => $materials,
            'users' => $users,
        ]);
    }

    public function show($id)
    {
        $questions = LiteracyQuestion::all();
        return view('literacy.teacher.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('literacy.teacher.questions.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:literacy_materials,id',
            'story_text_id' => 'nullable|exists:literacy_story_texts,id',
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,essay',
        ]);

        $question = LiteracyQuestion::create([
            'material_id' => $validated['material_id'],
            'story_text_id' => $validated['story_text_id'] ?? null,
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
        ]);

        if ($request->type === 'multiple_choice' && $request->has('options')) {
            foreach ($request->options as $option) {
                $question->options()->create([
                    'option_text' => $option['text'] ?? null,
                    'score' => $option['score'] ?? 0,
                    'is_correct' => isset($option['is_correct']) ? 1 : 0,
                ]);
            }
        } elseif ($request->type === 'essay') {
            $question->update([
                'essay_score' => $request->essay_score ?? 0,
                'essay_answer' => $request->essay_answer ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function publishAssessment()
    {
        // Ambil hanya pengguna dengan role 'student'
        $users = User::where('role', 'student')->get();
        $questionsCount = LiteracyQuestion::count();

        if ($questionsCount === 0) {
            return redirect()->back()->with('error', 'Belum ada soal yang tersedia untuk asesmen.');
        }

        foreach ($users as $user) {
            LiteracyAssessment::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'status' => 'pending',
                    'score' => 0,
                    'feedback' => '',
                    'assessed_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Asesmen berhasil dipublikasikan untuk semua siswa.');
    }

    public function edit($id)
    {
        $question = LiteracyQuestion::with('options')->findOrFail($id);
        return view('literacy.teacher.questions.index', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|string|in:multiple_choice,essay',
            'essay_score' => 'nullable|integer|min:0|max:100',
            'essay_answer' => 'nullable|string',
            'options' => 'nullable|array',
            'options.*.id' => 'nullable|exists:literacy_options,id',
            'options.*.option_text' => 'required_if:type,multiple_choice|string',
            'options.*.score' => 'nullable|integer|min:0|max:100',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            // Ambil data pertanyaan
            $question = LiteracyQuestion::with('options')->findOrFail($id);

            // Update pertanyaan
            $question->update([
                'question_text' => $request->question_text,
                'type' => $request->type,
                'essay_score' => $request->type === 'essay' ? $request->essay_score : null,
                'essay_answer' => $request->type === 'essay' ? $request->essay_answer : null,
            ]);

            // Hanya untuk multiple_choice: proses opsi
            if ($request->type === 'multiple_choice' && $request->has('options')) {

                // Ambil ulang options yang terbaru
                $question->refresh()->load('options');

                $existingOptionIds = $question->options->pluck('id')->toArray();
                $requestOptionIds = collect($request->options)->pluck('id')->filter()->toArray();

                // Hapus opsi yang tidak ada dalam request
                $optionsToDelete = array_diff($existingOptionIds, $requestOptionIds);
                if (!empty($optionsToDelete)) {
                    LiteracyOption::whereIn('id', $optionsToDelete)->delete();
                }

                // Proses insert/update opsi
                foreach ($request->input('options', []) as $option) {
                    $isCorrect = isset($option['is_correct']) && $option['is_correct'] ? 1 : 0;
                    $score = isset($option['score']) ? intval($option['score']) : 0;

                    if (!empty($option['id'])) {
                        // Update existing option
                        $existingOption = LiteracyOption::find($option['id']);
                        if ($existingOption) {
                            $existingOption->update([
                                'option_text' => $option['option_text'],
                                'score' => $score,
                                'is_correct' => $isCorrect,
                            ]);
                        }
                    } else {
                        // Create new option
                        LiteracyOption::create([
                            'question_id' => $question->id,
                            'option_text' => $option['option_text'],
                            'score' => $score,
                            'is_correct' => $isCorrect,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('literacy_teacher_questions')->with('success', 'Pertanyaan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Error:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $question = LiteracyQuestion::findOrFail($id);
        $question->delete();

        return redirect()->route('literacy_teacher_questions')->with('success', 'Question deleted successfully.');
    }
}
