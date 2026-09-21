<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::getSortedClasses();
        $defaultClassId = $classes->where('students_count', '>', 0)->first()->id ?? $classes->first()->id ?? null;
        $selectedClassId = $request->get('class_id', $defaultClassId);
        $selectedDate = $request->get('date', Carbon::now()->format('Y-m-d'));

        $students = [];
        $attendances = collect();
        if ($selectedClassId) {
            $students = Student::where('class_id', $selectedClassId)->orderBy('name')->get();
            $attendances = Attendance::where('class_id', $selectedClassId)
                ->where('date', $selectedDate)
                ->get()
                ->keyBy('student_id');
        }

        $stats = [
            'total' => count($students),
            'hadir' => $attendances->where('status', 'Hadir')->count(),
            'izin' => $attendances->where('status', 'Izin')->count(),
            'sakit' => $attendances->where('status', 'Sakit')->count(),
            'alpa' => $attendances->where('status', 'Alpa')->count(),
        ];

        return view('attendance.index', compact(
            'classes',
            'selectedClassId',
            'selectedDate',
            'students',
            'attendances',
            'stats'
        ));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'statuses' => 'required|array',
        ]);

        $classId = $request->class_id;
        $date = $request->date;

        foreach ($request->statuses as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                [
                    'class_id' => $classId,
                    'status' => $status,
                    'check_in_time' => $status === 'Hadir' ? Carbon::now()->format('H:i:s') : null,
                    'method' => 'Manual',
                    'notes' => $request->notes[$studentId] ?? null,
                ]
            );
        }

        return redirect()->route('attendance.index', ['class_id' => $classId, 'date' => $date])
            ->with('success', 'Presensi berhasil disimpan!');
    }

    public function scanner()
    {
        $classes = SchoolClass::getSortedClasses();
        $today = Carbon::now()->format('Y-m-d');
        $recentScans = Attendance::with(['student.schoolClass'])
            ->where('date', $today)
            ->where('method', 'QR Scan')
            ->latest('updated_at')
            ->take(15)
            ->get();

        return view('attendance.scanner', compact('classes', 'recentScans'));
    }

    public function scanProcess(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $qrCode = trim($request->qr_code);
        $student = Student::with('schoolClass')->where('qr_code', $qrCode)->orWhere('nisn', $qrCode)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Kode QR / NISN tidak terdaftar dalam sistem!'
            ], 404);
        }

        $today = Carbon::now()->format('Y-m-d');
        $nowTime = Carbon::now()->format('H:i:s');

        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'date' => $today,
            ],
            [
                'class_id' => $student->class_id,
                'status' => 'Hadir',
                'check_in_time' => $nowTime,
                'method' => 'QR Scan',
                'notes' => 'Presensi via Scan Kamera QR',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => "Presensi Berhasil: {$student->name} ({$student->schoolClass->name})",
            'student' => [
                'name' => $student->name,
                'nisn' => $student->nisn,
                'class' => $student->schoolClass->name,
                'time' => $nowTime,
                'status' => 'Hadir',
            ]
        ]);
    }
}
