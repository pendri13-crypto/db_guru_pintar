<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::getSortedClasses();
        $subjects = Subject::orderBy('name')->get();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $selectedDay = $request->get('day', 'Semua');

        $query = Schedule::with(['schoolClass', 'subject']);
        if ($selectedDay !== 'Semua') {
            $query->where('day', $selectedDay);
        }

        $schedules = $query->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('start_time')
            ->get();

        return view('schedules.index', compact('schedules', 'classes', 'subjects', 'days', 'selectedDay'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'room' => 'required|string',
        ]);

        Schedule::create($request->all());
        return redirect()->route('schedules.index')->with('success', 'Jadwal mengajar berhasil ditambahkan!');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'room' => 'required|string',
        ]);

        $schedule->update($request->all());
        return redirect()->route('schedules.index')->with('success', 'Jadwal mengajar berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Jadwal mengajar berhasil dihapus!');
    }
}
