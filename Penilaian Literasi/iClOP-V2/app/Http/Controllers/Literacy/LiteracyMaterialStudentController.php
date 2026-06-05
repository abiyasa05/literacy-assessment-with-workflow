<?php

namespace App\Http\Controllers\Literacy;

use App\Http\Controllers\Controller;
use App\Models\Literacy\LiteracyMaterial;
use App\Models\Literacy\LiteracyQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LiteracyMaterialStudentController extends Controller
{
    public function materials()
    {
        $materials = LiteracyMaterial::orderBy('created_at', 'desc')->get();
        $users = User::all();
        $questions = LiteracyQuestion::all();

        return view('literacy.student.materials.index', [
            'materials' => $materials,
            'users' => $users,
            'questions' => $questions,
        ]);
    }

    public function show($id)
    {
        $material = LiteracyMaterial::findOrFail($id);
        return view('literacy.student.materials.show', compact('material'));
    }

    function show_materials()
    {
        $materials_student = LiteracyMaterial::orderBy('created_at', 'desc')->get();
        return view('literacy.student.materials.index', [
            'materials_student' => $materials_student,
        ]);
    }

    public function view_materials($id)
    {
        $material_student = LiteracyMaterial::findOrFail($id);
        $path = storage_path('app/public/' . $material_student->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($path);
    }
}