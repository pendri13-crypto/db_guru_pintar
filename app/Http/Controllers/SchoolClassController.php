<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        // Use the model's custom sorting to naturally order classes (e.g. VII, VIII, IX or 7A, 8B, 9C)
        $classes = SchoolClass::getSortedClasses();

        return view('classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
            'level' => 'required|string|max:50',
            'academic_year' => 'nullable|string|max:50',
            'homeroom_teacher' => 'nullable|string|max:255',
        ]);

        SchoolClass::create([
            'name' => $request->name,
            'level' => $request->level,
            'academic_year' => $request->academic_year ?? '2025/2026',
            'homeroom_teacher' => $request->homeroom_teacher,
        ]);

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil ditambahkan!');
    }

    public function update(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $class->id,
            'level' => 'required|string|max:50',
            'academic_year' => 'nullable|string|max:50',
            'homeroom_teacher' => 'nullable|string|max:255',
        ]);

        $class->update([
            'name' => $request->name,
            'level' => $request->level,
            'academic_year' => $request->academic_year ?? '2025/2026',
            'homeroom_teacher' => $request->homeroom_teacher,
        ]);

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy(SchoolClass $class)
    {
        if ($class->students()->count() > 0) {
            return redirect()->route('classes.index')->with('error', 'Gagal menghapus! Kelas ini masih memiliki ' . $class->students()->count() . ' siswa. Hapus atau pindahkan siswa terlebih dahulu.');
        }

        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil dihapus!');
    }
}
