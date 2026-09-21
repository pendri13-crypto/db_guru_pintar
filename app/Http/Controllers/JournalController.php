<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeachingJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::getSortedClasses();
        $subjects = Subject::orderBy('name')->get();

        $query = TeachingJournal::with(['schoolClass', 'subject']);
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $journals = $query->orderBy('date', 'desc')->paginate(10)->withQueryString();
        $totalJournals = TeachingJournal::count();

        return view('journals.index', compact('journals', 'classes', 'subjects', 'totalJournals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'meeting_number' => 'required|integer|min:1',
            'topic' => 'required|string|max:255',
            'learning_objective' => 'required|string',
            'activities' => 'nullable|string',
            'total_present' => 'nullable|integer',
            'total_absent' => 'nullable|integer',
            'obstacle_solution' => 'nullable|string',
        ]);

        TeachingJournal::create(array_merge($request->all(), [
            'is_completed' => $request->has('is_completed') ? 1 : 1,
        ]));

        return redirect()->route('journals.index')->with('success', 'Agenda mengajar (Jurnal KBM) berhasil ditambahkan!');
    }

    public function update(Request $request, TeachingJournal $journal)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'meeting_number' => 'required|integer|min:1',
            'topic' => 'required|string|max:255',
            'learning_objective' => 'required|string',
            'activities' => 'nullable|string',
            'total_present' => 'nullable|integer',
            'total_absent' => 'nullable|integer',
            'obstacle_solution' => 'nullable|string',
        ]);

        $journal->update($request->all());

        return redirect()->route('journals.index')->with('success', 'Agenda mengajar berhasil diperbarui!');
    }

    public function destroy(TeachingJournal $journal)
    {
        $journal->delete();
        return redirect()->route('journals.index')->with('success', 'Agenda mengajar berhasil dihapus!');
    }
}
