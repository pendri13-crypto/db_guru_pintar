<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\TeachingJournal;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::getSortedClasses();
        $subjects = Subject::orderBy('name')->get();
        $setting = SchoolSetting::first() ?? new SchoolSetting();

        return view('reports.index', compact('classes', 'subjects', 'setting'));
    }

    public function printAttendance(Request $request)
    {
        $classId = $request->get('class_id');
        $month = $request->get('month', Carbon::now()->format('m'));
        $year = $request->get('year', Carbon::now()->format('Y'));

        $schoolClass = SchoolClass::findOrFail($classId);
        $students = Student::where('class_id', $classId)->orderBy('name')->get();
        $setting = SchoolSetting::first() ?? new SchoolSetting();

        // Get attendances in the selected month
        $attendances = Attendance::where('class_id', $classId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        return view('reports.print_attendance', compact('schoolClass', 'students', 'setting', 'attendances', 'month', 'year'));
    }

    public function printGrades(Request $request)
    {
        $classId = $request->get('class_id');
        $subjectId = $request->get('subject_id');
        $semester = $request->get('semester', 'Ganjil');

        $schoolClass = SchoolClass::findOrFail($classId);
        $subject = Subject::findOrFail($subjectId);
        $students = Student::where('class_id', $classId)->orderBy('name')->get();
        $grades = Grade::where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('semester', $semester)
            ->get()
            ->keyBy('student_id');
        $setting = SchoolSetting::first() ?? new SchoolSetting();

        return view('reports.print_grades', compact('schoolClass', 'subject', 'students', 'grades', 'semester', 'setting'));
    }

    public function printJournals(Request $request)
    {
        $classId = $request->get('class_id');
        $subjectId = $request->get('subject_id');

        $query = TeachingJournal::with(['schoolClass', 'subject']);
        if ($classId) $query->where('class_id', $classId);
        if ($subjectId) $query->where('subject_id', $subjectId);

        $journals = $query->orderBy('date')->get();
        $setting = SchoolSetting::first() ?? new SchoolSetting();

        return view('reports.print_journals', compact('journals', 'setting'));
    }
}
