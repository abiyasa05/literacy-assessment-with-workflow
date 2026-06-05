<?php

namespace App\Http\Controllers\Literacy;

use App\Http\Controllers\Controller;
use App\Models\Literacy\LiteracyGeneratedText;
use App\Models\Literacy\LiteracyMaterial;
use App\Models\Literacy\LiteracyQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;

class LiteracyGenerateQuestionsController extends Controller
{
    public function __construct()
    {
        // Middleware memastikan hanya teacher yang bisa mengakses
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'teacher') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function generate_questions()
    {
        $generate_questions = LiteracyQuestion::all();
        $materials = LiteracyMaterial::all();
        $users = User::all();
        $questions = LiteracyQuestion::all();

        // Ambil generate_text berdasarkan user yang sedang login
        $latestGeneratedText = LiteracyGeneratedText::where('user_id', Auth::id())->latest()->first();

        // Mengirimkan data terbaru ke view
        return view('literacy.teacher.generate_questions.index', [
            'generate_questions' => $generate_questions,
            'materials' => $materials,
            'users' => $users,
            'questions' => $questions,
            'latestGeneratedText' => $latestGeneratedText,
        ]);
    }

    public function generate_from_ai_prev(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:literacy_materials,id',
            'question_type' => 'required|in:multiple_choice,essay',
            'question_count' => 'required|integer|min:1|max:20',
        ]);

        try {
            ini_set('max_execution_time', 300);

            $material = LiteracyMaterial::findOrFail($request->material_id);
            $relativePath = $material->file_path;

            // Kirim permintaan ke FastAPI
            $response = Http::timeout(300)->asJson()->post('http://127.0.0.1:8010/generate-from-file/', [
                'file_name' => $relativePath,
                'question_type' => $request->question_type,
                'question_count' => (int) $request->question_count,
            ]);

            if ($response->successful()) {
                $generatedText = LiteracyGeneratedText::create([
                    'user_id' => Auth::id(),
                    'material_id' => $request->material_id,
                    'question_type' => $request->question_type,
                    'generate_text' => $response->json()['generated_questions'],
                    'question_count' => $request->question_count,
                ]);

                return response()->json([
                    'status' => 'success',
                    'generated_questions' => $generatedText->generate_text
                ]);
            }

            Log::error('FastAPI Error Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghasilkan soal dari model',
                'response' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Generate Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal: ' . $e->getMessage()
            ], 500);
        }
    }

    /* =====================================================
       GENERATE SOAL
    ===================================================== */
    public function generate_from_ai(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:literacy_materials,id',
            'question_type' => 'required|in:multiple_choice,essay',
            'question_count' => 'required|integer|min:1|max:20',
            'start_page' => 'nullable|integer|min:0',
            'max_chars' => 'nullable|integer|min:100|max:10000'
        ]);

        try {
            ini_set('max_execution_time', 1200);

            $material = LiteracyMaterial::findOrFail($request->material_id);
            $relativePath = $material->file_path;

            // ===== BUILD FULL FILE PATH =====
            $basePath = 'D:\\Projek Tesis\\Penilaian Literasi\\iClOP-V2\\storage\\app\\public';
            $fullPath = $basePath . DIRECTORY_SEPARATOR . $relativePath;
            
            // Validasi file exists
            if (!file_exists($fullPath)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File tidak ditemukan: ' . $fullPath
                ], 404);
            }

            // Get file extension
            $fileExtension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            
            // Parameter untuk pembacaan file
            $startPage = $request->input('start_page', 25);
            $maxChars = $request->input('max_chars', 5000);
            
            Log::info('Sending file to N8N', [
                'file_path' => $fullPath,
                'extension' => $fileExtension,
                'question_type' => $request->question_type,
                'question_count' => $request->question_count,
                'start_page' => $startPage,
                'max_chars' => $maxChars
            ]);

            // ===== KIRIM FILE PATH KE N8N =====
            $response = Http::timeout(1200)->asJson()->post(
                'http://localhost:5678/webhook/ai-gateway-rag',
                [
                    'action' => 'generate',
                    'file_path' => $fullPath,
                    'file_type' => $fileExtension,
                    'question_type' => $request->question_type,
                    'question_count' => (int) $request->question_count,
                    'start_page' => $startPage,
                    'max_chars' => $maxChars,
                    'grade_level' => 'SD kelas 3-5'
                ]
            );

            if ($response->successful()) {

                $generatedText = LiteracyGeneratedText::create([
                    'user_id' => Auth::id(),
                    'material_id' => $request->material_id,
                    'question_type' => $request->question_type,
                    'generate_text' => $response->body(),
                    'question_count' => $request->question_count,
                ]);

                return response()->json([
                    'status' => 'success',
                    'generated_questions' => $generatedText->generate_text
                ]);
            }

            Log::error('N8N error response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'AI gagal generate soal: ' . $response->body()
            ], 500);

        } catch (\Exception $e) {

            Log::error('Generate Error:', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    /* =====================================================
       FILE READER (DEPRECATED - TIDAK DIGUNAKAN LAGI)
       Sekarang N8N yang handle pembacaan file via Extract from File node
    ===================================================== */
    private function readFileText($relativePath, $startPage = 0, $maxChars = 5000)
    {
        $basePath = 'D:\\Projek Tesis\\Penilaian Literasi\\iClOP-V2\\storage\\app\\public';
        $fullPath = $basePath . DIRECTORY_SEPARATOR . $relativePath;

        if (!file_exists($fullPath)) {
            throw new \Exception("File tidak ditemukan: " . $fullPath);
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        /* PDF */
        if ($ext === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($fullPath);
            $pages = $pdf->getPages();

            if ($startPage >= count($pages)) {
                return "";
            }

            $text = "";
            for ($i = $startPage; $i < count($pages); $i++) {
                $text .= $pages[$i]->getText();
                if (strlen($text) >= $maxChars)
                    break;
            }

            return substr($text, 0, $maxChars);
        }

        /* DOCX */
        if ($ext === 'docx') {

            $phpWord = IOFactory::load($fullPath);
            $paragraphs = [];

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {

                    if ($element instanceof Text) {
                        $value = trim($element->getText());
                        if ($value !== '')
                            $paragraphs[] = $value;
                    }

                    if ($element instanceof TextRun) {
                        foreach ($element->getElements() as $child) {
                            if ($child instanceof Text) {
                                $value = trim($child->getText());
                                if ($value !== '')
                                    $paragraphs[] = $value;
                            }
                        }
                    }
                }
            }

            $startIndex = $startPage * 20;
            $selected = array_slice($paragraphs, $startIndex);

            return substr(implode("\n", $selected), 0, $maxChars);
        }

        /* TXT */
        if ($ext === 'txt') {
            $lines = file($fullPath, FILE_IGNORE_NEW_LINES);
            $startIndex = $startPage * 40;
            $selected = array_slice($lines, $startIndex);

            return substr(implode("\n", $selected), 0, $maxChars);
        }

        throw new \Exception("Format file tidak didukung: " . $ext);
    }

    public function history_generated_texts()
    {
        $history_generated_texts = LiteracyGeneratedText::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $materials = LiteracyMaterial::all();

        return view('literacy.teacher.history_generated.index', [
            'history_generated_texts' => $history_generated_texts,
            'materials' => $materials,
        ]);
    }

    public function show_history_generated_texts($id)
    {
        $history_generated_texts = LiteracyGeneratedText::all();
        return view('literacy.teacher.history_generated.index', compact('history_generated_texts'));
    }

    public function history_index()
    {
        $history_generated_texts = LiteracyGeneratedText::orderBy('created_at', 'desc')->get();

        return view('literacy.teacher.history_generated.index', compact('history_generated_texts'));
    }

    public function destroy_history($id)
    {
        $deleted = LiteracyGeneratedText::findOrFail($id);
        $deleted->delete();

        return redirect()->route('literacy_teacher_generate_questions_history')
            ->with('success', 'History deleted successfully.');
    }
}