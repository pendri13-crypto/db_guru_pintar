<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::getSortedClasses();
        
        // Pilih kelas pertama yang memiliki siswa sebagai default
        $defaultClassId = $classes->where('students_count', '>', 0)->first()->id ?? $classes->first()->id ?? null;
        $selectedClassId = $request->get('class_id', $defaultClassId);

        $students = [];
        if ($selectedClassId) {
            $students = Student::with('schoolClass')
                ->where('class_id', $selectedClassId)
                ->orderBy('name')
                ->get();
        }

        $setting = SchoolSetting::first() ?? new SchoolSetting();

        return view('cards.index', compact('classes', 'selectedClassId', 'students', 'setting'));
    }

    public function print(Request $request)
    {
        $classId = $request->get('class_id');
        $studentId = $request->get('student_id');

        $query = Student::with('schoolClass');
        if ($studentId) {
            $query->where('id', $studentId);
        } elseif ($classId) {
            $query->where('class_id', $classId);
        }

        $students = $query->orderBy('name')->get();
        $setting = SchoolSetting::first() ?? new SchoolSetting();

        return view('cards.print', compact('students', 'setting'));
    }
}
