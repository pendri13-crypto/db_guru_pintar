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
            $uh1 = isset($row['uh1']) && $row['uh1'] !== '' ? floatval($row['uh1']) : null;
            $uh2 = isset($row['uh2']) && $row['uh2'] !== '' ? floatval($row['uh2']) : null;
            $th1 = isset($row['th1']) && $row['th1'] !== '' ? floatval($row['th1']) : null;
            $th2 = isset($row['th2']) && $row['th2'] !== '' ? floatval($row['th2']) : null;
            $pts = isset($row['pts']) && $row['pts'] !== '' ? floatval($row['pts']) : null;
            $sumatifAkhir = isset($row['sumatif_akhir']) && $row['sumatif_akhir'] !== '' ? floatval($row['sumatif_akhir']) : null;

            $vals = array_filter([$uh1, $uh2, $th1, $th2, $pts, $sumatifAkhir], fn($v) => !is_null($v));
            $final = null;
            $predicate = null;

            if (count($vals) > 0) {
                $harianVals = array_filter([$uh1, $uh2, $th1, $th2], fn($v) => !is_null($v));
                $rataHarian = count($harianVals) > 0 ? array_sum($harianVals) / count($harianVals) : 0;
                
                $final = round(($rataHarian * 0.60) + (($pts ?? 0) * 0.10) + (($sumatifAkhir ?? 0) * 0.30), 1);
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
                    'uh1' => $uh1,
                    'uh2' => $uh2,
                    'th1' => $th1,
                    'th2' => $th2,
                    'pts' => $pts,
                    'sumatif_akhir' => $sumatifAkhir,
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
