<?php

namespace App\Http\Controllers\Literacy;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Literacy\LiteracyMaterial;
use App\Models\Literacy\LiteracyQuestion;
use Illuminate\Support\Facades\Hash;

class LiteracyUserController extends Controller
{
    // 🛡 Middleware untuk autentikasi dan cek admin
    public function __construct()
    {
        $this->middleware(['auth', 'teacher']);
    }

    // 📋 1. Menampilkan daftar pengguna
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();;
        $materials = LiteracyMaterial::all();
        $questions = LiteracyQuestion::all();
        return view('literacy.teacher.users.index', [
            'materials' => $materials,
            'users' => $users,
            'questions' => $questions,
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('literacy.teacher.users.index', compact('user'));
    }

    // ➕ 2. Form tambah pengguna
    public function create()
    {
        return view('literacy.teacher.users.index');
    }

    // 💾 3. Menyimpan pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'role' => 'required|in:teacher,student',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('literacy_teacher_users')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    // 🛠 4. Form edit pengguna
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('literacy.teacher.users.index', compact('user'));
    }

    // 🔄 5. Update pengguna
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:teacher,student',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('literacy_teacher_users')->with('success', 'Pengguna berhasil diperbarui!');
    }

    // ❌ 6. Hapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('literacy_teacher_users')->with('success', 'Pengguna berhasil dihapus!');
    }
}