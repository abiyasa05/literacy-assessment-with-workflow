<?php

use App\Http\Controllers\Literacy\LiteracyController;
use App\Http\Controllers\Literacy\LiteracyGuruController;
use App\Http\Controllers\Literacy\Student\LiteracyLogicalController;
use App\Http\Controllers\Literacy\LiteracyMaterialController;
use App\Http\Controllers\Literacy\LiteracyMaterialStudentController;
use App\Http\Controllers\Literacy\LiteracyTeacherAssessmentController;
use App\Http\Controllers\Literacy\LiteracyUserController;
use App\Http\Controllers\Literacy\LiteracyAssessmentController;
use App\Http\Controllers\Literacy\LiteracyQuestionController;
use App\Http\Controllers\Literacy\LiteracyGenerateQuestionsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\PHP\PHPController;
use App\Http\Controllers\PHP\PHPDosenController;
use App\Http\Controllers\PHP\Student\DashboardUnitControllers;
use App\Http\Controllers\PHP\Student\StudikasusController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

// Routes khusus untuk siswa
Route::group(['middleware' => ['auth', 'student']], function () {
    Route::prefix('literacy/student')->group(function () {
        Route::get('/assessments', [LiteracyAssessmentController::class, 'index'])->name('literacy_assessments');
        Route::get('/{id}', [LiteracyAssessmentController::class, 'show'])->name('literacy_assessments_show');
        Route::post('/start/{id}', [LiteracyAssessmentController::class, 'start'])->name('literacy_assessments_start');
        Route::get('/continue/{id}', [LiteracyAssessmentController::class, 'continue'])->name('literacy_assessments_continue');
        Route::post('/assessments/{assessmentId}/save-answer', [LiteracyAssessmentController::class, 'storeAnswer'])->name('literacy_assessment_store_answer');
        Route::post('/assessment/submit/{id}', [LiteracyAssessmentController::class, 'submitAssessment'])->name('assessment.submit');

        // Melihat hasil asesmen setelah selesai
        Route::get('/assessments/{id}/result', [LiteracyAssessmentController::class, 'viewResult'])->name('literacy_assessment_result');

        // Menampilkan daftar soal dalam asesmen tertentu
        Route::get('/assessments/{id}/questions', [LiteracyAssessmentController::class, 'getQuestions'])->name('literacy_assessment_questions');

        // Menampilkan materi literasi yang tersedia untuk siswa
        Route::get('/materials/view/{id}', [LiteracyMaterialStudentController::class, 'view_materials'])->name('literacy_materials_view');
        Route::get('/materials/all', [LiteracyMaterialStudentController::class, 'show_materials'])->name('literacy_student_materials');
        Route::get('/materials/show/{id}', [LiteracyMaterialStudentController::class, 'show'])->name('literacy_materials_show');
    });
});

// Routes khusus untuk guru
Route::group(['middleware' => ['auth', 'teacher']], function () {
    Route::prefix('literacy/teacher')->group(function () {  // Tambahkan prefix /teacher
        Route::get('/materials', [LiteracyMaterialController::class, 'materials'])->name('literacy_teacher_materials');

        Route::get('/materials/{id}/detail', [LiteracyMaterialController::class, 'show'])->name('literacy_materials_detail');

        Route::get('/materials/show/{id}', [LiteracyMaterialController::class, 'show_materials'])->name('literacy_materials_show');

        Route::get('/materials/create', [LiteracyMaterialController::class, 'create'])
            ->name('literacy_materials_create');

        Route::post('/materials/store', [LiteracyMaterialController::class, 'store'])
            ->name('literacy_materials_store');

        Route::get('/materials/{id}/edit', [LiteracyMaterialController::class, 'edit'])
            ->name('literacy_materials_edit');

        Route::put('/materials/{id}', [LiteracyMaterialController::class, 'update'])
            ->name('literacy_materials_update');

        Route::delete('/materials/{id}', [LiteracyMaterialController::class, 'destroy'])
            ->name('literacy_materials_destroy');

        Route::get('/users', [LiteracyUserController::class, 'users'])->name('literacy_teacher_users');

        Route::get('/users/{id}/detail', [LiteracyUserController::class, 'show'])->name('literacy_users_detail');

        Route::get('/users/create', [LiteracyUserController::class, 'create'])
            ->name('literacy_users_create');

        Route::post('/users/store', [LiteracyUserController::class, 'store'])
            ->name('literacy_users_store');

        Route::get('/users/{id}/edit', [LiteracyUserController::class, 'edit'])
            ->name('literacy_users_edit');

        Route::put('/users/{id}', [LiteracyUserController::class, 'update'])
            ->name('literacy_users_update');

        Route::delete('/users/{id}', [LiteracyUserController::class, 'destroy'])
            ->name('literacy_users_destroy');

        Route::get('/questions', [LiteracyQuestionController::class, 'questions'])->name('literacy_teacher_questions');

        Route::get('/materials/{id}/story-texts', [LiteracyMaterialController::class, 'getStoryTexts']);

        Route::get('/questions/{id}/detail', [LiteracyQuestionController::class, 'show'])->name('literacy_users_detail');

        Route::get('/questions/create', [LiteracyQuestionController::class, 'create'])
            ->name('literacy_questions_create');

        Route::post('/questions/store', [LiteracyQuestionController::class, 'store'])
            ->name('literacy_questions_store');

        Route::post('/questions/assessments/publish', [LiteracyQuestionController::class, 'publishAssessment'])
            ->name('literacy_questions_publish_assessment');

        Route::get('/questions/{id}/edit', [LiteracyQuestionController::class, 'edit'])
            ->name('literacy_questions_edit');

        Route::put('/questions/{id}', [LiteracyQuestionController::class, 'update'])
            ->name('literacy_questions_update');

        Route::delete('/questions/{id}', [LiteracyQuestionController::class, 'destroy'])
            ->name('literacy_questions_destroy');

        Route::get('/generate_questions', [LiteracyGenerateQuestionsController::class, 'generate_questions'])->name('literacy_teacher_generate_questions');

        Route::post('/generate_questions/ai', [LiteracyGenerateQuestionsController::class, 'generate_from_ai'])
            ->name('literacy_teacher_generate_from_ai');
        
        Route::get('/generate_questions/history', [LiteracyGenerateQuestionsController::class, 'history_index'])
            ->name('literacy_teacher_generate_questions_history');
        
        Route::delete('/generate_questions/history/{id}', [LiteracyGenerateQuestionsController::class, 'destroy_history'])
            ->name('literacy_teacher_generate_questions_destroy');

        Route::get('/assessment_results', [LiteracyTeacherAssessmentController::class, 'index'])
            ->name('literacy_teacher_assessment_results');

        Route::get('/assessment_results/{id}', [LiteracyTeacherAssessmentController::class, 'show'])
            ->name('literacy_teacher_assessment_results_show');

        Route::get('/assessment_results/view/{id}', [LiteracyTeacherAssessmentController::class, 'viewResult'])
            ->name('literacy_teacher_assessment_results_view');
    });
});