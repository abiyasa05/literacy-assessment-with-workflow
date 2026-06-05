<?php

namespace App\Http\Controllers\Literacy;

use App\Http\Controllers\Controller;
use App\Models\Literacy\LiteracyAssessment;
use App\Models\Literacy\LiteracyQuestion;
use App\Models\User;
use Illuminate\Http\Request;

class LiteracyTeacherAssessmentController extends Controller
{
    /**
     * Menampilkan semua hasil asesmen siswa.
     */
    public function index()
    {
        // Ambil asesmen hanya dari user dengan role student
        $assessments = LiteracyAssessment::whereHas('user', function ($query) {
            $query->where('role', 'student');
        })->whereIn('status', ['in_progress', 'completed']) // Tambahkan filter status
            ->latest()
            ->get();

        return view('literacy.teacher.assessment_results.index', compact('assessments'));
    }

    /**
     * Menampilkan detail hasil asesmen siswa.
     */
    public function show($id)
    {
        // Ambil asesmen berdasarkan ID, hanya jika usernya adalah student
        $assessment = LiteracyAssessment::whereHas('user', function ($query) {
            $query->where('role', 'student');
        })->findOrFail($id);

        // Ambil pertanyaan terkait asesmen
        $questions = $assessment->questions()->with('answers.option')->get();

        return view('literacy.teacher.assessment_results.show', compact('assessment', 'questions'));
    }

    public function viewResult($id)
    {
        // Ambil asesmen berdasarkan ID, pastikan asesmen milik user dengan role 'student' dan statusnya 'completed'
        $assessment = LiteracyAssessment::where('id', $id)
            ->whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->where('status', 'completed')
            ->firstOrFail();

        // Ambil semua pertanyaan, beserta jawaban dan opsi untuk asesmen ini
        $questions = LiteracyQuestion::with([
            'answers' => function ($query) use ($id) {
                $query->where('assessment_id', $id);
            },
            'options'
        ])->get();

        return view('literacy.teacher.assessment_results.show', compact('assessment', 'questions'));
    }
}
