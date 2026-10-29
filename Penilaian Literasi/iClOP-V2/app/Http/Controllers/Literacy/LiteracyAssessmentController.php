<?php

namespace App\Http\Controllers\Literacy;

use App\Http\Controllers\Controller;
use App\Models\Literacy\LiteracyAssessment;
use App\Models\Literacy\LiteracyQuestion;
use App\Models\Literacy\LiteracyMaterial;
use App\Models\Literacy\LiteracyOption;
use App\Models\Literacy\LiteracyAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use function similar_text;
use Illuminate\Support\Facades\Http;

class LiteracyAssessmentController extends Controller
{
    public function __construct()
    {
        // Middleware memastikan hanya student yang bisa mengakses
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'student') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan daftar asesmen yang sedang berjalan atau sudah selesai untuk student yang login.
     */
    public function index()
    {
        $assessments = LiteracyAssessment::where('user_id', Auth::id())
            ->with('material')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('literacy.student.assessments.index', compact('assessments'));
    }

    /**
     * Memulai asesmen dan mengubah status menjadi "in_progress".
     */
    public function start($id)
    {
        $assessment = LiteracyAssessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Ubah status asesmen menjadi in_progress
        $assessment->update(['status' => 'in_progress']);

        // Hanya siapkan soal dari materi yang menjadi scope asesmen ini.
        $questions = LiteracyQuestion::when($assessment->material_id, function ($query) use ($assessment) {
            $query->where('material_id', $assessment->material_id);
        })->get();

        // Insert jawaban kosong jika belum ada
        foreach ($questions as $question) {
            LiteracyAnswer::updateOrInsert(
                [
                    'assessment_id' => $assessment->id,
                    'question_id' => $question->id,
                ],
                [
                    'option_id' => null,
                    'answer_text' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return redirect()->route('literacy_assessments_show', $id);
    }

    /**
     * Menampilkan halaman asesmen dengan pertanyaan yang diurutkan.
     */
    public function show($id)
    {
        $assessment = LiteracyAssessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Ambil semua pertanyaan, urutkan pilihan ganda dulu baru essay
        $questions = LiteracyQuestion::when($assessment->material_id, function ($query) use ($assessment) {
                $query->where('material_id', $assessment->material_id);
            })
            ->orderByRaw("FIELD(type, 'multiple_choice', 'essay')")
            ->with([
                'answers' => function ($query) use ($id) {
                    $query->where('assessment_id', $id);
                },
                'options',
                'material.storyTexts'
            ])
            ->get();

        return view('literacy.student.assessments.assessment', compact('assessment', 'questions'));
    }

    /**
     * Melanjutkan asesmen yang belum selesai.
     */
    public function continue($id)
    {
        $assessment = LiteracyAssessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return redirect()->route('literacy_assessments_show', $id);
    }

    /**
     * Menyimpan jawaban siswa ke database secara otomatis saat memilih atau mengisi jawaban.
     */
    public function storeAnswer(Request $request, $assessmentId): JsonResponse
    {
        Log::info('Request Data:', $request->all());

        $request->validate([
            'question_id' => 'required|exists:literacy_questions,id',
            'answer' => 'nullable|string',
        ]);

        // Pastikan pengguna memiliki asesmen ini
        $assessment = LiteracyAssessment::where('id', $assessmentId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$assessment) {
            return response()->json(['error' => 'Asesmen tidak ditemukan'], 404);
        }

        $questionId = $request->question_id;
        $answerValue = $request->answer;

        $question = LiteracyQuestion::when($assessment->material_id, function ($query) use ($assessment) {
                $query->where('material_id', $assessment->material_id);
            })->find($questionId);
        if (!$question) {
            return response()->json(['error' => 'Pertanyaan tidak ditemukan'], 404);
        }

        $answerData = ['option_id' => null, 'answer_text' => $answerValue];

        if ($question->type === 'multiple_choice') {
            $option = LiteracyOption::where('id', $answerValue)
                ->where('question_id', $questionId)
                ->first();

            if (!$option) {
                return response()->json(['error' => 'Jawaban tidak valid'], 400);
            }

            $answerData = ['option_id' => $option->id, 'answer_text' => null];
        }

        LiteracyAnswer::updateOrCreate(
            [
                'assessment_id' => $assessment->id,
                'question_id' => $questionId,
            ],
            $answerData
        );

        // Hitung jumlah pertanyaan dalam asesmen ini
        $totalQuestions = $assessment->questions()->count();

        // Hitung jumlah pertanyaan yang sudah dijawab
        $answeredQuestions = LiteracyAnswer::where('assessment_id', $assessment->id)
            ->where(function ($query) {
                $query->whereNotNull('option_id')
                    ->orWhere('answer_text', '!=', '');
            })
            ->count();

        Log::info('Answered Questions:', ['answered' => $answeredQuestions, 'total' => $totalQuestions]);

        return response()->json(['success' => true]);
    }


    public function submitAssessment($id)
    {
        try {

            $user = Auth::user();

            $assessment = LiteracyAssessment::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            if ($assessment->status !== 'in_progress') {
                return response()->json(['error' => 'Asesmen tidak sedang berjalan.'], 400);
            }

            $answers = LiteracyAnswer::where('assessment_id', $id)
                ->whereHas('question', function ($query) use ($assessment) {
                    $query->where('material_id', $assessment->material_id);
                })
                ->get();

            $correctWeight = 0;
            $totalWeight = 0;
            $feedback = [];
            $answerStatus = [];

            $essayThreshold = 70;

            $persistEssayResult = function ($answerId, $answerModel, $questionId, $weight, $aiRelevance, $aiCompleteness, $aiClarity, $aiFeedback, $evaluationDetails = []) use (&$correctWeight, &$answerStatus, &$feedback, $essayThreshold) {
                $aiPercentage = round(($aiRelevance + $aiCompleteness + $aiClarity) / 3, 2);
                $isCorrectEssay = $aiPercentage >= $essayThreshold;
                $essayScore = ($aiPercentage / 100) * $weight;

                $correctWeight += $essayScore;
                $answerStatus[$answerId] = $isCorrectEssay;

                $feedbackPayload = [
                    'feedback' => $aiFeedback,
                    'ai_score' => $aiPercentage,
                    'relevansi' => $aiRelevance,
                    'kelengkapan' => $aiCompleteness,
                    'kejelasan' => $aiClarity,
                    'evaluation' => $evaluationDetails,
                ];

                $answerModel->feedback = json_encode($feedbackPayload);
                $answerModel->save();

                $feedback[] = [
                    'question_id' => $questionId,

                    'feedback' => $aiFeedback,

                    'ai_score' => $aiPercentage,

                    'relevansi' => $aiRelevance,
                    'kelengkapan' => $aiCompleteness,
                    'kejelasan' => $aiClarity,

                    'evaluation' => $evaluationDetails,
                ];
            };

            $essayPayload = [];
            $essayAnswerMap = [];

            foreach ($answers as $answer) {

                $question = LiteracyQuestion::find($answer->question_id);
                if (!$question)
                    continue;

                $weight = $question->weight ?? ($question->type === 'essay' ? 5 : 1);
                $totalWeight += $weight;

                /* =========================
                   PILIHAN GANDA
                ========================= */
                if ($question->type === 'multiple_choice') {

                    if (
                        $answer->option_id &&
                        LiteracyOption::where('id', $answer->option_id)
                            ->where('is_correct', true)
                            ->exists()
                    ) {
                        $correctWeight += $weight;
                        $answerStatus[$answer->id] = true;
                    } else {
                        $answerStatus[$answer->id] = false;
                    }
                }

                /* =========================
                   ESAI
                ========================= */ elseif ($question->type === 'essay') {

                    // Validasi data essay sebelum dikirim ke N8N
                    $questionText = $question->question_text ?? '';
                    $userAnswer = $answer->answer_text ?? '';
                    $expectedAnswer = $question->essay_answer ?? '';

                    // Pastikan tipe data adalah string
                    if (!is_string($questionText))
                        $questionText = '';
                    if (!is_string($userAnswer))
                        $userAnswer = '';
                    if (!is_string($expectedAnswer))
                        $expectedAnswer = '';

                    // Trim dan validasi
                    $questionText = trim($questionText);
                    $userAnswer = trim($userAnswer);
                    $expectedAnswer = trim($expectedAnswer);

                    $fallbackScore = $this->calculateEssayFallbackScore($userAnswer, $expectedAnswer);

                    // Jika ada field yang kosong, tetap simpan skor fallback agar setiap esai punya nilai
                    if (empty($questionText) || empty($userAnswer) || empty($expectedAnswer)) {
                        Log::warning('Essay data tidak lengkap, skip essay ini', [
                            'answer_id' => $answer->id,
                            'question_id' => $question->id,
                            'question_text_type' => gettype($question->question_text),
                            'user_answer_type' => gettype($answer->answer_text),
                            'expected_answer_type' => gettype($question->essay_answer),
                        ]);

                        $persistEssayResult(
                            $answer->id,
                            $answer,
                            $question->id,
                            $weight,
                            $fallbackScore,
                            $fallbackScore,
                            $fallbackScore,
                            'Penilaian AI tidak lengkap, menggunakan skor fallback berdasarkan kemiripan jawaban.'
                        );

                        continue;
                    }

                    $essayPayload[] = [
                        'answer_id' => $answer->id,
                        'question_text' => $questionText,
                        'user_answer' => $userAnswer,
                        'expected_answer' => $expectedAnswer,
                        'weight' => $weight
                    ];

                    $essayAnswerMap[$answer->id] = [
                        'model' => $answer,
                        'weight' => $weight,
                        'question_id' => $question->id
                    ];
                }
            }


            /* ======================================================
               KIRIM ESSAY SATU-PER-SATU KE N8N
            ====================================================== */
            if (!empty($essayPayload)) {

                Log::info('Sending essays to N8N', [
                    'count' => count($essayPayload),
                    'answer_ids' => array_column($essayPayload, 'answer_id')
                ]);

                $successCount = 0;
                $failedCount = 0;
                $loopCount = 0;

                foreach ($essayPayload as $index => $essay) {

                    $loopCount++;
                    Log::info("=== LOOP ITERATION {$loopCount} START ===", ['answer_id' => $essay['answer_id'] ?? 'unknown']);

                    $fallbackScore = $this->calculateEssayFallbackScore(
                        $essay['user_answer'] ?? '',
                        $essay['expected_answer'] ?? ''
                    );

                    try {
                        $totalEssays = count($essayPayload);
                        $currentNumber = $index + 1;

                        // Safety check untuk logging
                        $qtLen = is_string($essay['question_text'] ?? null) ? strlen($essay['question_text']) : 0;
                        $uaLen = is_string($essay['user_answer'] ?? null) ? strlen($essay['user_answer']) : 0;
                        $eaLen = is_string($essay['expected_answer'] ?? null) ? strlen($essay['expected_answer']) : 0;

                        Log::info("Processing essay {$currentNumber}/{$totalEssays}", [
                            'answer_id' => $essay['answer_id'] ?? 'unknown',
                            'question_text_length' => $qtLen,
                            'user_answer_length' => $uaLen,
                            'expected_answer_length' => $eaLen,
                        ]);

                        Log::info("Sending HTTP request to N8N for answer_id: " . ($essay['answer_id'] ?? 'unknown'));

                        $response = Http::timeout(300)->post(
			    'http://192.168.60.24:5678/webhook/literacy-rag-v7',
                            //'http://192.168.60.24:5678/webhook/literacy-rag-v6',
                            //'http://192.168.60.24:5678/webhook/ai-gateway-rag',
                            [
                                'action' => 'score',
                                'answer_id' => $essay['answer_id'],
                                'question_text' => $essay['question_text'],
                                'user_answer' => $essay['user_answer'],
                                'expected_answer' => $essay['expected_answer'],
                            ]
                        );

                        Log::info("N8N response received", [
                            'answer_id' => $essay['answer_id'],
                            'status' => $response->status(),
                            'ok' => $response->ok(),
                            'body_length' => strlen($response->body())
                        ]);

                        if (!$response->ok()) {
                            Log::error('N8N response error', [
                                'status' => $response->status(),
                                'response' => $response->body(),
                                'answer_id' => $essay['answer_id'],
                            ]);
                            $failedCount++;

                            $persistEssayResult(
                                $essay['answer_id'],
                                $essayAnswerMap[$essay['answer_id']]['model'],
                                $essayAnswerMap[$essay['answer_id']]['question_id'],
                                $essay['weight'],
                                $fallbackScore,
                                $fallbackScore,
                                $fallbackScore,
                                'Penilaian AI tidak tersedia, menggunakan skor fallback berdasarkan kemiripan jawaban.',
                                []
                            );

                            continue;
                        }

                        $result = $this->parseN8nEssayResponse($response);

                        Log::info("N8N response parsed", [
                            'answer_id' => $essay['answer_id'],
                            'is_array' => is_array($result),
                            'keys' => is_array($result) ? array_keys($result) : 'not array'
                        ]);

                        if (!is_array($result)) {
                            Log::error('N8N response is not JSON array', [
                                'response' => $response->body(),
                                'answer_id' => $essay['answer_id'],
                            ]);
                            $failedCount++;

                            $persistEssayResult(
                                $essay['answer_id'],
                                $essayAnswerMap[$essay['answer_id']]['model'],
                                $essayAnswerMap[$essay['answer_id']]['question_id'],
                                $essay['weight'],
                                $fallbackScore,
                                $fallbackScore,
                                $fallbackScore,
                                'Penilaian AI tidak tersedia, menggunakan skor fallback berdasarkan kemiripan jawaban.'
                            );

                            continue;
                        }

                        $answerId = $result['answer_id'] ?? $essay['answer_id'];
                        if (!isset($essayAnswerMap[$answerId])) {
                            Log::warning('Answer ID not found in map', ['answer_id' => $answerId]);
                            $failedCount++;
                            continue;
                        }

                        $answerData = $essayAnswerMap[$answerId];
                        $answerModel = $answerData['model'];
                        $weight = $answerData['weight'];

                        $normalizedEssayResult = $this->normalizeEssayScoringResult($result);

                        $aiRelevance = $normalizedEssayResult['relevansi'];
                        $aiCompleteness = $normalizedEssayResult['kelengkapan'];
                        $aiClarity = $normalizedEssayResult['kejelasan'];
                        $aiFeedback = $normalizedEssayResult['feedback'];
                        $evaluationDetails = $normalizedEssayResult['evaluation'];

                        $persistEssayResult(
                            $answerId,
                            $answerModel,
                            $answerData['question_id'],
                            $weight,
                            $aiRelevance,
                            $aiCompleteness,
                            $aiClarity,
                            $aiFeedback,
                            $evaluationDetails
                        );

                        Log::info('Essay graded successfully', [
                            'answer_id' => $answerId,
                            'ai_score' => round(($aiRelevance + $aiCompleteness + $aiClarity) / 3, 2),
                            'relevansi' => $aiRelevance,
                            'kelengkapan' => $aiCompleteness,
                            'kejelasan' => $aiClarity,
                        ]);

                        $successCount++;

                        // Removed delay untuk testing - n8n harus bisa handle concurrent requests

                        Log::info("=== LOOP ITERATION {$loopCount} END (SUCCESS) ===");

                    } catch (\Exception $e) {
                        $errorAnswerId = $essay['answer_id'] ?? 'unknown';
                        Log::error("N8N Scoring Error for answer_id {$errorAnswerId}: " . $e->getMessage(), [
                            'answer_id' => $errorAnswerId,
                            'error' => $e->getMessage(),
                            'line' => $e->getLine(),
                            'file' => $e->getFile()
                        ]);
                        $failedCount++;

                        $persistEssayResult(
                            $essay['answer_id'],
                            $essayAnswerMap[$essay['answer_id']]['model'],
                            $essayAnswerMap[$essay['answer_id']]['question_id'],
                            $essay['weight'],
                            $fallbackScore,
                            $fallbackScore,
                            $fallbackScore,
                            'Penilaian AI gagal, menggunakan skor fallback berdasarkan kemiripan jawaban.',
                            []
                        );

                        Log::info("=== LOOP ITERATION {$loopCount} END (FAILED) ===");
                        // Continue ke essay berikutnya meskipun ada error
                        continue;
                    }
                }

                Log::info('Essay scoring completed', [
                    'total' => count($essayPayload),
                    'success' => $successCount,
                    'failed' => $failedCount
                ]);
            }

            /* =========================
               HITUNG NILAI AKHIR
            ========================= */
            $score = $totalWeight > 0
                ? ($correctWeight / $totalWeight) * 100
                : 0;

            $assessment->update([
                'score' => round($score, 2),
                'status' => 'completed',
                'assessed_at' => now(),
                'feedback' => json_encode($feedback),
            ]);


            /* =========================
               BUAT ASESMEN BARU
            ========================= */
            LiteracyAssessment::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'material_id' => $assessment->material_id,
                    'status' => 'pending',
                ],
                [
                    'score' => null,
                    'feedback' => '',
                    'assessed_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );


            /* =========================
               LOAD RESULT VIEW
            ========================= */
            $answers = LiteracyAnswer::where('assessment_id', $assessment->id)->get();
            $questionIds = $answers->pluck('question_id')->unique();

            $questions = LiteracyQuestion::whereIn('id', $questionIds)
                ->with([
                    'answers' => function ($q) use ($assessment) {
                        $q->where('assessment_id', $assessment->id);
                    },
                    'answers.option'
                ])->get();

            return view('literacy.student.assessments.result', [
                'assessment' => $assessment,
                'questions' => $questions,
                'answerStatus' => $answerStatus
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewResult($id)
    {
        // Ambil asesmen berdasarkan ID, pastikan asesmen milik user dan statusnya sudah 'completed'
        $assessment = LiteracyAssessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Ambil semua pertanyaan beserta jawaban untuk asesmen ini
        $questions = LiteracyQuestion::where('material_id', $assessment->material_id)
            ->with([
            'answers' => function ($query) use ($id) {
                $query->where('assessment_id', $id);
            },
            'options'
        ])->get();

        // Hitung answerStatus untuk setiap jawaban
        $answerStatus = $this->calculateAnswerStatus($id);

        return view('literacy.student.assessments.result', compact('assessment', 'questions', 'answerStatus'));
    }

    public function result($id)
    {
        $assessment = LiteracyAssessment::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        $questions = LiteracyQuestion::where('material_id', $assessment->material_id)
            ->with([
            'answers' => function ($query) use ($id) {
                $query->where('assessment_id', $id);
            },
            'options'
        ])->get();

        // Hitung answerStatus untuk setiap jawaban
        $answerStatus = $this->calculateAnswerStatus($id);

        return view('literacy.student.assessments.result', compact('assessment', 'questions', 'answerStatus'));
    }

    /**
     * Helper: Hitung status setiap jawaban (benar/salah) dengan logika yang sama seperti submitAssessment
     */
    private function calculateAnswerStatus($assessmentId)
    {
        $answerStatus = [];
        $answers = LiteracyAnswer::where('assessment_id', $assessmentId)->get();
        $assessment = LiteracyAssessment::find($assessmentId);
        $essayThreshold = 70; // %

        $normalizeText = function ($text) {
            $text = strtolower($text);
            $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
            $text = preg_replace('/\s+/', ' ', $text);
            return trim($text);
        };

        // Parse feedback dari assessment untuk mendapat ai_score
        $feedbackData = [];
        if (!empty($assessment->feedback)) {
            $feedbackData = json_decode($assessment->feedback, true) ?? [];
        }

        foreach ($answers as $answer) {
            $question = LiteracyQuestion::find($answer->question_id);
            if (!$question)
                continue;

            if ($question->type === 'multiple_choice') {
                // Cek apakah option yang dipilih adalah jawaban yang benar
                $isCorrect = $answer->option_id &&
                    LiteracyOption::where('id', $answer->option_id)
                        ->where('is_correct', true)
                        ->exists();
                $answerStatus[$answer->id] = $isCorrect;

            } elseif ($question->type === 'essay') {
                // Baseline similarity check
                $userAnswer = $normalizeText($answer->answer_text ?? '');
                $correctAnswerRaw = $question->essay_answer ?? '';
                $correctAnswers = array_map(
                    $normalizeText,
                    preg_split('/\r\n|\r|\n/', $correctAnswerRaw)
                );

                $maxMatchPercent = 0;
                foreach ($correctAnswers as $correctAnswer) {
                    similar_text($userAnswer, $correctAnswer, $percent);
                    if ($percent > $maxMatchPercent) {
                        $maxMatchPercent = $percent;
                    }
                }

                $baselineCorrect = $maxMatchPercent >= 70;

                // Cek apakah ada AI score dalam feedback data
                $aiScore = null;
                foreach ($feedbackData as $fb) {
                    if (isset($fb['question_id']) && $fb['question_id'] == $question->id) {
                        $aiScore = array_key_exists('ai_score', $fb) ? floatval($fb['ai_score']) : null;
                        break;
                    }
                }

                // Gunakan AI score jika tersedia, jika tidak gunakan baseline
                if ($aiScore !== null) {
                    $isCorrect = $aiScore >= $essayThreshold;
                } else {
                    $isCorrect = $baselineCorrect;
                }

                $answerStatus[$answer->id] = $isCorrect;
            }
        }

        return $answerStatus;
    }

    private function calculateEssayFallbackScore($userAnswer, $expectedAnswer): float
    {
        $normalizeText = function ($text) {
            $text = strtolower((string) $text);
            $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
            $text = preg_replace('/\s+/', ' ', $text);
            return trim($text);
        };

        $userAnswer = $normalizeText($userAnswer);
        $correctAnswers = preg_split('/\r\n|\r|\n/', (string) $expectedAnswer) ?: [''];

        $maxMatchPercent = 0;

        foreach ($correctAnswers as $correctAnswer) {
            $correctNormalized = $normalizeText($correctAnswer);
            similar_text($userAnswer, $correctNormalized, $percent);
            $maxMatchPercent = max($maxMatchPercent, $percent);
        }

        return round($maxMatchPercent, 2);
    }

    private function parseN8nEssayResponse($response): ?array
    {
        $body = trim((string) $response->body());

        if ($body === '') {
            return null;
        }

        $tryDecode = function (string $candidate): ?array {
            $candidate = trim($candidate);

            if ($candidate === '') {
                return null;
            }

            $decoded = json_decode($candidate, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            return null;
        };

        $extractFromText = function (string $text) use ($tryDecode): ?array {
            $direct = $tryDecode($text);
            if (is_array($direct)) {
                return $direct;
            }

            $start = strpos($text, '{');
            $end = strrpos($text, '}');

            if ($start !== false && $end !== false && $end > $start) {
                $jsonBlock = substr($text, $start, $end - $start + 1);
                $parsed = $tryDecode($jsonBlock);
                if (is_array($parsed)) {
                    return $parsed;
                }
            }

            return null;
        };

        $decodedBody = $tryDecode($body);

        if (is_array($decodedBody)) {
            if (isset($decodedBody['relevansi']) || isset($decodedBody['kelengkapan']) || isset($decodedBody['kejelasan']) || isset($decodedBody['feedback']) || isset($decodedBody['evaluation'])) {
                return $decodedBody;
            }

            if (isset($decodedBody['output']) && is_string($decodedBody['output'])) {
                $parsedOutput = $extractFromText($decodedBody['output']);
                if (is_array($parsedOutput)) {
                    return $parsedOutput;
                }
            }

            if (isset($decodedBody[0]) && is_array($decodedBody[0]) && isset($decodedBody[0]['output']) && is_string($decodedBody[0]['output'])) {
                $parsedOutput = $extractFromText($decodedBody[0]['output']);
                if (is_array($parsedOutput)) {
                    return $parsedOutput;
                }
            }
        }

        $parsedBody = $extractFromText($body);
        if (is_array($parsedBody)) {
            return $parsedBody;
        }

        return null;
    }

    private function normalizeEssayScoringResult(array $result): array
    {
        $relevance = floatval($result['relevansi'] ?? $result['relevance']['score'] ?? 0);
        $completeness = floatval($result['kelengkapan'] ?? $result['completeness']['score'] ?? 0);
        $clarity = floatval($result['kejelasan'] ?? $result['clarity']['score'] ?? 0);
        $feedback = (string) ($result['feedback'] ?? $result['text'] ?? '');

        $evaluation = $result['evaluation'] ?? [];
        if (!is_array($evaluation)) {
            $evaluation = [];
        }

        $normalizedEvaluation = [
            'relevance' => [
                'score' => $evaluation['relevance']['score'] ?? $relevance,
                'justification' => $evaluation['relevance']['justification'] ?? $result['relevance']['justification'] ?? null,
            ],
            'clarity' => [
                'score' => $evaluation['clarity']['score'] ?? $clarity,
                'justification' => $evaluation['clarity']['justification'] ?? $result['clarity']['justification'] ?? null,
            ],
            'completeness' => [
                'score' => $evaluation['completeness']['score'] ?? $completeness,
                'justification' => $evaluation['completeness']['justification'] ?? $result['completeness']['justification'] ?? null,
            ],
        ];

        return [
            'relevansi' => $relevance,
            'kelengkapan' => $completeness,
            'kejelasan' => $clarity,
            'feedback' => $feedback,
            'evaluation' => $normalizedEvaluation,
        ];
    }
}
