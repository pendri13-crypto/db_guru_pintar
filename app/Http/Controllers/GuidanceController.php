<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Guidance;
use Illuminate\Http\Request;

class GuidanceController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::getSortedClasses();
        $query = Guidance::with(['student.schoolClass', 'schoolClass']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $guidances = $query->orderBy('date', 'desc')->paginate(10)->withQueryString();
        $students = Student::orderBy('name')->get();

        $stats = [
            'total' => Guidance::count(),
            'apresiasi' => Guidance::where('type', 'Apresiasi')->count(),
            'konseling' => Guidance::where('type', 'Konseling')->count(),
            'pelanggaran' => Guidance::where('type', 'Pelanggaran')->count(),
        ];

        return view('guidance.index', compact('guidances', 'classes', 'students', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'type' => 'required|in:Konseling,Apresiasi,Pelanggaran,Panggilan Ortu',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'action_taken' => 'nullable|string',
            'parent_followup' => 'nullable|string',
            'status' => 'required|in:Selesai,Dalam Proses,Perlu Pemantauan',
        ]);

        $student = Student::findOrFail($request->student_id);

        Guidance::create(array_merge($request->all(), [
            'class_id' => $student->class_id,
        ]));

        return redirect()->route('guidance.index')->with('success', 'Catatan bimbingan siswa berhasil ditambahkan!');
    }

    public function update(Request $request, Guidance $guidance)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Konseling,Apresiasi,Pelanggaran,Panggilan Ortu',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'action_taken' => 'nullable|string',
            'parent_followup' => 'nullable|string',
            'status' => 'required|in:Selesai,Dalam Proses,Perlu Pemantauan',
        ]);

        $guidance->update($request->all());

        return redirect()->route('guidance.index')->with('success', 'Catatan bimbingan siswa berhasil diperbarui!');
    }

    public function destroy(Guidance $guidance)
    {
        $guidance->delete();
        return redirect()->route('guidance.index')->with('success', 'Catatan bimbingan berhasil dihapus!');
    }
}
