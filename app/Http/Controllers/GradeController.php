<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::getSortedClasses();
        $subjects = Subject::orderBy('name')->get();

        $defaultClassId = $classes->where('students_count', '>', 0)->first()->id ?? $classes->first()->id ?? null;
        $selectedClassId = $request->get('class_id', $defaultClassId);
        $selectedSubjectId = $request->get('subject_id', $subjects->first()->id ?? null);
        $selectedSemester = $request->get('semester', 'Ganjil');

        $students = [];
        $grades = collect();

        if ($selectedClassId && $selectedSubjectId) {
            $students = Student::where('class_id', $selectedClassId)->orderBy('name')->get();
            $grades = Grade::where('class_id', $selectedClassId)
                ->where('subject_id', $selectedSubjectId)
                ->where('semester', $selectedSemester)
                ->get()
                ->keyBy('student_id');
        }

        $avgScore = $grades->count() > 0 ? round($grades->avg('final_score'), 1) : 0;
        $highestScore = $grades->count() > 0 ? $grades->max('final_score') : 0;
        $lowestScore = $grades->count() > 0 ? $grades->min('final_score') : 0;

        return view('grades.index', compact(
            'classes',
            'subjects',
            'selectedClassId',
            'selectedSubjectId',
            'selectedSemester',
            'students',
            'grades',
            'avgScore',
            'highestScore',
            'lowestScore'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'semester' => 'required|string',
            'grades' => 'required|array',
        ]);

        $classId = $request->class_id;
        $subjectId = $request->subject_id;
        $semester = $request->semester;

        foreach ($request->grades as $studentId => $row) {
            $tugas1 = isset($row['tugas_1']) && $row['tugas_1'] !== '' ? floatval($row['tugas_1']) : null;
            $tugas2 = isset($row['tugas_2']) && $row['tugas_2'] !== '' ? floatval($row['tugas_2']) : null;
            $formatif = isset($row['formatif']) && $row['formatif'] !== '' ? floatval($row['formatif']) : null;
            $sumatif = isset($row['sumatif']) && $row['sumatif'] !== '' ? floatval($row['sumatif']) : null;
            $uts = isset($row['uts']) && $row['uts'] !== '' ? floatval($row['uts']) : null;
            $uas = isset($row['uas']) && $row['uas'] !== '' ? floatval($row['uas']) : null;

            $vals = array_filter([$tugas1, $tugas2, $formatif, $sumatif, $uts, $uas], fn($v) => !is_null($v));
            $final = null;
            $predicate = null;

            if (count($vals) > 0) {
                $final = round((($tugas1 ?? 0) * 0.15) + (($tugas2 ?? 0) * 0.15) + (($formatif ?? 0) * 0.20) + (($sumatif ?? 0) * 0.20) + (($uts ?? 0) * 0.15) + (($uas ?? 0) * 0.15), 1);
                if ($final >= 88) $predicate = 'A';
                elseif ($final >= 75) $predicate = 'B';
                elseif ($final >= 60) $predicate = 'C';
                else $predicate = 'D';
            }

            Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'semester' => $semester,
                ],
                [
                    'class_id' => $classId,
                    'tugas_1' => $tugas1,
                    'tugas_2' => $tugas2,
                    'formatif' => $formatif,
                    'sumatif' => $sumatif,
                    'uts' => $uts,
                    'uas' => $uas,
                    'final_score' => $final,
                    'predicate' => $predicate,
                    'notes' => $row['notes'] ?? null,
                ]
            );
        }

        return redirect()->route('grades.index', [
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'semester' => $semester
        ])->with('success', 'Rekap nilai berhasil disimpan & dikalkulasi!');
    }
}
