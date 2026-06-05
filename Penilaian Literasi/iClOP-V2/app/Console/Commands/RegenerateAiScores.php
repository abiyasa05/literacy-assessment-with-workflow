<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Literacy\LiteracyAssessment;
use App\Models\Literacy\LiteracyAnswer;
use App\Models\Literacy\LiteracyQuestion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegenerateAiScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:regenerate {assessmentId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate AI scores and feedback for essay answers of an assessment';

    public function handle()
    {
        $assessmentId = $this->argument('assessmentId');

        $assessment = LiteracyAssessment::find($assessmentId);
        if (!$assessment) {
            $this->error("Assessment id {$assessmentId} not found");
            return 1;
        }

        $answers = LiteracyAnswer::where('assessment_id', $assessmentId)
            ->whereNotNull('answer_text')
            ->get();

        $feedbackEntries = [];

        foreach ($answers as $answer) {
            $question = LiteracyQuestion::find($answer->question_id);
            if (!$question) continue;
            if ($question->type !== 'essay') continue;

            $this->info("Processing answer id {$answer->id} (question {$question->id})...");

            $aiScore = 0;
            $aiRelevansi = 0;
            $aiKelengkapan = 0;
            $aiKejelasan = 0;
            $aiFeedback = null;

            try {
                $scoreResponse = Http::timeout(300)->post('http://127.0.0.1:8010/ai-score-essay/', [
                    'user_answer' => $answer->answer_text,
                    'expected_answer' => $question->essay_answer,
                    'question_text' => $question->question_text,
                ]);

                if ($scoreResponse->ok()) {
                    $data = $scoreResponse->json();
                    $aiScore = isset($data['final_score']) ? floatval($data['final_score']) : 0;
                    $aiRelevansi = isset($data['relevansi']) ? floatval($data['relevansi']) : 0;
                    $aiKelengkapan = isset($data['kelengkapan']) ? floatval($data['kelengkapan']) : 0;
                    $aiKejelasan = isset($data['kejelasan']) ? floatval($data['kejelasan']) : 0;
                }

                $feedbackResponse = Http::timeout(300)->post('http://127.0.0.1:8010/generate-feedback/', [
                    'user_answer' => $answer->answer_text,
                    'expected_answer' => $question->essay_answer,
                    'question_text' => $question->question_text,
                ]);

                if ($feedbackResponse->ok()) {
                    $aiFeedback = $feedbackResponse->json()['feedback'] ?? null;
                }

            } catch (\Exception $e) {
                Log::error('AI regenerate error: '.$e->getMessage());
                $this->error('AI request failed for answer id '.$answer->id.': '.$e->getMessage());
            }

            // store AI data into answer->feedback as JSON (text + scores)
            $answerFeedbackPayload = [
                'text' => $aiFeedback,
                'ai_score' => $aiScore,
                'relevansi' => $aiRelevansi,
                'kelengkapan' => $aiKelengkapan,
                'kejelasan' => $aiKejelasan,
            ];

            $answer->feedback = json_encode($answerFeedbackPayload);
            $answer->save();

            $feedbackEntries[] = [
                'question_id' => $question->id,
                'feedback' => $aiFeedback,
                'ai_score' => $aiScore,
                'relevansi' => $aiRelevansi,
                'kelengkapan' => $aiKelengkapan,
                'kejelasan' => $aiKejelasan,
            ];
        }

        $this->info('Regeneration complete. Updated '.count($feedbackEntries).' answers with AI feedback.');

        return 0;
    }
}
